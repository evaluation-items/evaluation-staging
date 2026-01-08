<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\CsproData;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Config;
use Exception;
use Illuminate\Support\Facades\Schema;
use App\Models\CaseAnswer;
use App\Models\Cases;

class CsproDataController extends Controller
{
    public function index()
    {
    //     // Get the latest .csdb file from the uploads folder
    //     $csdbDirectory = public_path('uploads');
    //     $csdbFiles = glob($csdbDirectory . '/*.csdb');
    //     $cases = 0;
    //     $labelTitle = null;
    //     if (!empty($csdbFiles)) {
    //         // return response()->json(['error' => 'No CSDB file found in uploads folder'], 400);
    //          // Sort files by modified time in descending order and get the latest file
    //             usort($csdbFiles, function ($a, $b) {
    //                 return filemtime($b) - filemtime($a);
    //             });
            
    //             $csdbFilePath = $csdbFiles[0]; // Most recent file
            
    //             // Check if the file exists
    //             if (!file_exists($csdbFilePath)) {
    //                 return response()->json(['error' => 'CSDB file not found'], 400);
    //             }
            
    //             // Connect to the CSDB file
    //             config(['database.connections.cspro' => [
    //                 'driver' => 'sqlite',
    //                 'database' => $csdbFilePath,
    //                 'prefix' => '',
    //             ]]);
       
    
       
    
    //     // Fetch all cases from CSDB
    //     $cases = DB::connection('cspro')->table('cases')->get();
    //     $tables = DB::connection('cspro')->select("SELECT name FROM sqlite_master WHERE type='table'");
    //     $labelTitle = DB::table('imaster.item_labels')->select('record_name', 'title')->distinct()->get();
    //     $labelNames = DB::table('imaster.item_labels')->pluck('item_label', 'item_name')->mapWithKeys(fn ($label, $key) => [strtolower($key) => $label]);
    
    //     foreach ($cases as $case) {
           
    //         $case->answers = [];
    //         $case->form_number = 'N/A';
    //         $case->person_id = 'N/A';
    
    //         foreach ($tables as $table) {
    //             $tableName = $table->name;
    //             if ($tableName === 'cases') continue;
    
    //             // Get columns for the current table
    //             $columns = DB::connection('cspro')->getSchemaBuilder()->getColumnListing($tableName);
    
    //             // Fetch data
    //             $data = DB::connection('cspro')->table($tableName)
    //                 ->where('level-1-id', function ($query) use ($case) {
    //                     $query->select('level-1-id')
    //                         ->from('level-1')
    //                         ->where('case-id', $case->id)
    //                         ->limit(1);
    //                 })
    //                 ->first();
              
    //             if ($data) {
                   
    //                 if (isset($data->form_number)) {
    //                     $case->form_number = 233556;
    //                 }

    //                 if (isset($data->person_id)) {
    //                     $case->person_id = $data->person_id;
    //                 }
    
    //                 // Fetch record names (categories) for each question field
    //                 $recordNames = DB::table('imaster.item_labels')
    //                     ->pluck('record_name', 'item_name')
    //                     ->mapWithKeys(fn ($record, $key) => [strtolower($key) => $record]);
    
    //                 foreach ($columns as $column) {
    //                     if (str_starts_with($column, 'q')) { // Process only question fields
    //                         $normalizedKey = strtolower($column);
    //                         $label = $labelNames[$normalizedKey] ?? $column; // Match with labels
    
    //                         // Get the record name (category)
    //                         $recordName = $recordNames[$normalizedKey] ?? 'Uncategorized';
    
    //                         // Store answer under its respective record category
    //                         $case->answers[$recordName][$label] = $data->$column ?? 'No Answer';
    //                     }
    //                 }
    //             }
    //         }
    //     }
    // }
         // Fetch all cases with their answers
     $cases = Cases::with('answers')->get();

        // Group cases by title
        $groupedCases = $cases->groupBy('title');

        // Inside each case, group answers by record_name
        foreach ($groupedCases as $title => $cases) {
            foreach ($cases as $case) {
                $case->groupedAnswers = $case->answers->groupBy('record_name');
            }
        }

        
        return view('dashboards.eva-dir.cspro-item', compact('groupedCases'));
    }
    
    public function viewCspro(){ //enter details

       return view('dashboards.eva-dir.csPro.accordion');
    }
    public function uploadCspro(Request $request)
    {
        try {
            // Validate input files
            $request->validate([
                'cspro_csdb_file' => 'required|file',
                'cspro_dcf_file' => 'required|file'
            ]);
    
            // Get original filenames and append timestamp
            $csdbOriginalName = pathinfo($request->file('cspro_csdb_file')->getClientOriginalName(), PATHINFO_FILENAME) . '_' . time() . '.csdb';
            $dcfOriginalName = pathinfo($request->file('cspro_dcf_file')->getClientOriginalName(), PATHINFO_FILENAME) . '_' . time() . '.dcf';
    
            // Move files to public/uploads
            $csdbPath = $request->file('cspro_csdb_file')->move(public_path('uploads'), $csdbOriginalName);
            $dcfPath = $request->file('cspro_dcf_file')->move(public_path('uploads'), $dcfOriginalName);
    
            // Process DCF file
            $title = $request->title;
            $this->processDcfFile($dcfPath, $title);
    
            // Validate CSDB structure against DCF
            if ($this->validateCsdbWithDcf($csdbPath)) {
                $cases = $this->processCsdbData($csdbPath,$title);
            } else {
                return back()->withErrors('File validation failed.');
            }
    
            return redirect()->route('evaldir.cspro.index')->withSuccess('Files uploaded and processed successfully.');
    
        } catch (Exception $e) {
           
            return back()->withErrors($e->getMessage());
        }
    }
    
    
    function processDcfFile($dcfFilePath, $title)
    {
        // Read the JSON structure from the DCF file
        $dcfContent = file_get_contents($dcfFilePath);
        $dcfData = json_decode($dcfContent, true);
    
        if (!$dcfData || !isset($dcfData['levels'])) {
            throw new Exception("Invalid DCF file structure.");
        }
    
        $records = [];
    
        foreach ($dcfData['levels'] as $level) {
            foreach ($level['records'] ?? [] as $record) {
                $recordName = $record['name'] ?? 'Unknown';
                $recordLabel = $record['labels'][0]['text'] ?? 'No Label';
    
                foreach ($record['items'] ?? [] as $item) {
                    $itemName = $item['name'] ?? 'Unknown';
                    $itemLabel = $item['labels'][0]['text'] ?? 'No Label';
    
                    $records[] = [
                        'title' => $title,
                        'record_name' => $recordName,
                        'record_label' => $recordLabel,
                        'item_name' => $itemName,
                        'item_label' => $itemLabel,
                    ];
                }
            }
        }
    
        // Insert or update data into the database
        foreach ($records as $record) {
            DB::table('imaster.item_labels')->updateOrInsert(
                ['title' => $record['title'], 'record_name' => $record['record_name'], 'item_name' => $record['item_name']],
                ['record_label' => $record['record_label'], 'item_label' => $record['item_label']]
            );
        }
    }
    
    
    function validateCsdbWithDcf($csdbFilePath) {
        // Fetch item names from the .dcf file (stored in DB)
        $dcfItems = DB::table('imaster.item_labels')->pluck('item_name')->toArray();
    
        // Connect to CSDB (SQLite)
        config(['database.connections.cspro' => [
            'driver' => 'sqlite',
            'database' => $csdbFilePath,
            'prefix' => '',
        ]]);
    
        // Get table names (levels)
       // $tables = Schema::connection('cspro')->getAllTables();
        $tables = DB::connection('cspro')->select("SELECT name FROM sqlite_master WHERE type='table'");
        
        foreach ($tables as $table) {
            $tableName = $table->name;
            $csdbItems = Schema::connection('cspro')->getColumnListing($tableName);
            
            // Only check question fields (starting with 'q')
            $csdbItems = array_filter($csdbItems, fn($item) => str_starts_with($item, 'q'));
    
            // Check if items exist in dcf
            foreach ($csdbItems as $item) {
                if (!in_array($item, $dcfItems)) {
                    return response()->json(['error' => "Mismatch: CSDB item $item not found in DCF."], 400);
                }
            }
        }
    
        return true; // Files match
    }
    function processCsdbData($csdbFilePath, $title)
    {
        // Validate CSDB structure before processing
        if (!$this->validateCsdbWithDcf($csdbFilePath)) {
            return response()->json(['error' => 'CSDB file does not match DCF structure'], 400);
        }
    
        // Fetch question labels from DCF metadata
        $labelNames = DB::table('imaster.item_labels')->pluck('item_label', 'item_name')
            ->mapWithKeys(fn($label, $key) => [strtolower($key) => $label]);
    
        // Connect to CSDB (SQLite)
        config(['database.connections.cspro' => [
            'driver' => 'sqlite',
            'database' => $csdbFilePath,
            'prefix' => '',
        ]]);
    
        $cases = DB::connection('cspro')->table('cases')->get();
        $tables = DB::connection('cspro')->select("SELECT name FROM sqlite_master WHERE type='table'");
    
        foreach ($cases as $case) {
            // Extract Person ID & Form Number dynamically
            $personId = 'N/A';
            $formNumber = 'N/A';
    
            foreach ($tables as $table) {
                $tableName = $table->name;
                if ($tableName === 'cases') continue;
    
                $data = DB::connection('cspro')->table($tableName)
                    ->where('level-1-id', function ($query) use ($case) {
                        $query->select('level-1-id')
                              ->from('level-1')
                              ->where('case-id', $case->id)
                              ->limit(1);
                    })
                    ->first();
    
                if ($data) {
                    // Try to fetch form number using different possible column names
                    foreach (['form_number', 'form_no', 'f_n'] as $col) {
                        if (isset($data->$col)) {
                            $formNumber = $data->$col;
                            break; // Stop if we found one
                        }
                    }
    
                    $personId = $data->person_id ?? $personId;
                }
            }
    
            // Insert into cases table
            $caseId = DB::table('imaster.cases')->insertGetId([
                'title' => $title, // ✅ Store the title
                'case_id' => $case->id,
                'person_id' => $personId,
                'form_number' => $formNumber,
                'created_at' => now(),
                'updated_at' => now()
            ]);
    
            // Process answers
            foreach ($tables as $table) {
                $tableName = $table->name;
                if ($tableName === 'cases') continue;
    
                $data = DB::connection('cspro')->table($tableName)
                    ->where('level-1-id', function ($query) use ($case) {
                        $query->select('level-1-id')
                              ->from('level-1')
                              ->where('case-id', $case->id)
                              ->limit(1);
                    })
                    ->first();
    
                if ($data) {
                    foreach ($data as $column => $value) {
                        if (str_starts_with($column, 'q')) { // Only process question fields
                            $normalizedKey = strtolower($column);
                            $label = $labelNames[$normalizedKey] ?? $column;
    
                            DB::table('imaster.case_answers')->insert([
                                'case_id' => $caseId,
                                'record_name' => $tableName,
                                'question_label' => $label,
                                'answer' => $value ?? 'No Answer',
                                'created_at' => now(),
                                'updated_at' => now()
                            ]);
                        }
                    }
                }
            }
        }
    }
    


}
