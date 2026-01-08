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

            // Process DCF file (store structure in DB)
            $title = $request->title;
            $this->processDcfFile($dcfPath, $title);

            // Validate CSDB structure against DCF
           // if (!$this->validateCsdbWithDcf($csdbPath)) {
               // return back()->withErrors('File validation failed.');
           // }

            // Process and insert CSDB data into DB
            $this->processCsdbData($csdbPath, $title);

            return back()->with('success', 'Files uploaded and processed successfully.');
    
        } catch (Exception $e) {
           
            return back()->withErrors($e->getMessage());
        }
    }
    private function processDcfFile($dcfFilePath, $title)
    {
        // Read DCF file content
        $dcfContent = file_get_contents($dcfFilePath);
        $dcfData = json_decode($dcfContent, true);

        if (!$dcfData || !isset($dcfData['levels'])) {
            throw new Exception("Invalid DCF file structure.");
        }

        $records = [];

        // Extract record and item details
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

        // Insert into database (update if exists)
        foreach ($records as $record) {
            DB::table('imaster.item_labels')->updateOrInsert(
                ['title' => $record['title'], 'record_name' => $record['record_name'], 'item_name' => $record['item_name']],
                ['record_label' => $record['record_label'], 'item_label' => $record['item_label']]
            );
        }
    }
    private function validateCsdbWithDcf($csdbFilePath)
    {
        // Fetch expected item names from DCF stored in the database
        $dcfItems = DB::table('imaster.item_labels')->pluck('item_name')->toArray();
    
        // Configure SQLite connection dynamically
        config(['database.connections.cspro' => [
            'driver' => 'sqlite',
            'database' => $csdbFilePath,
            'prefix' => '',
        ]]);
    
        DB::purge('cspro'); // Clear previous connection cache
    
        // Fetch table names from CSDB
        $tables = collect(DB::connection('cspro')->select("SELECT name FROM sqlite_master WHERE type='table'"))
                    ->pluck('name')
                    ->toArray();
    
        foreach ($tables as $tableName) {
            // Fetch column names from each table
            $csdbItems = collect(DB::connection('cspro')->select("PRAGMA table_info($tableName)"))
                            ->pluck('name')
                            ->filter(fn($item) => str_starts_with($item, 'q')) // Only check question fields
                            ->toArray();
    
            // Check if CSDB columns exist in DCF
            foreach ($csdbItems as $item) {
                if (!in_array($item, $dcfItems)) {
                    return false; // Validation failed
                }
            }
        }
    
        return true; // Validation successful
    }
    private function processCsdbData($csdbFilePath, $title)
    {
        // Fetch question labels from DCF metadata
        $labelNames = DB::table('imaster.item_labels')->pluck('item_label', 'item_name')
            ->mapWithKeys(fn($label, $key) => [strtolower($key) => $label]);
    
        // Configure SQLite connection dynamically
        config(['database.connections.cspro' => [
            'driver' => 'sqlite',
            'database' => $csdbFilePath,
            'prefix' => '',
        ]]);
    
        DB::purge('cspro'); // Clear previous connection cache
    
        // Fetch case records
        $cases = DB::connection('cspro')->table('cases')->get();
        $tables = collect(DB::connection('cspro')->select("SELECT name FROM sqlite_master WHERE type='table'"))
                    ->pluck('name')
                    ->toArray();
    
        foreach ($cases as $case) {
            $personId = 'N/A';
            $formNumber = 'N/A';
    
            foreach ($tables as $tableName) {
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
                    foreach (['form_number', 'form_no', 'f_n'] as $col) {
                        if (!empty($data->$col)) {
                            $formNumber = $data->$col;
                            break;
                        }
                    }
                    $personId = $data->person_id ?? $personId;
                }
            }
    
            // Insert case data
            $caseId = DB::table('imaster.cases')->insertGetId([
                'title' => $title, 
                'case_id' => $case->id,
                'person_id' => $personId,
                'form_number' => $formNumber,
                'created_at' => now(),
                'updated_at' => now()
            ]);
    
            // Insert question answers
            foreach ($tables as $tableName) {
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
                        if (str_starts_with($column, 'q')) { 
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
