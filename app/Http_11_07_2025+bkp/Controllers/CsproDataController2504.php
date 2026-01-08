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
use Illuminate\Support\Str;


class CsproDataController extends Controller
{
    public function index()
    {
   
        $cases = Cases::with('answers')->get();

        $groupedCases = $cases->groupBy('title');

        foreach ($groupedCases as $title => $cases) {
            foreach ($cases as $case) {
                $case->groupedAnswers = $case->answers->groupBy('record_name');
            }
        }
        return view('dashboards.eva-dir.cspro-item', compact('groupedCases'));
    }
    public function viewCspro(){ 
        return view('dashboards.eva-dir.csPro.accordion');
    }
    public function uploadCspro(Request $request)
    {
        try {
            $request->validate([
                'title' => 'required',
                'cspro_csdb_file' => 'required|array',
                'cspro_csdb_file.*' => 'file',
                'cspro_dcf_file' => 'required|file'
            ]);        
    
                     $title = $request->title;
                    if ($request->hasFile('cspro_dcf_file')) {
                        $dcfOriginalName = pathinfo($request->file('cspro_dcf_file')->getClientOriginalName(), PATHINFO_FILENAME) . '_' . time() . '.dcf';
                        $dcfPath = $request->file('cspro_dcf_file')->move(public_path('uploads'), $dcfOriginalName);
                        
                        $this->processDcfFile($dcfPath, $title);
                    }

                if ($request->hasFile('cspro_csdb_file')) {
                    foreach ($request->file('cspro_csdb_file') as $file) {
                        // Generate unique filename with timestamp
                        $csdbOriginalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)
                            . '_' . time() . '_' . uniqid() . '.csdb';
                
                        // Move file to public/uploads
                        $csdbPath = $file->move(public_path('uploads'), $csdbOriginalName);
                
                        // Validate CSDB structure against DCF
                        // if (!$this->validateCsdbWithDcf($csdbPath)) {
                        //     return back()->withErrors("File {$file->getClientOriginalName()} failed validation.");
                        // }
                
                        // Process and insert CSDB data into DB
                        $this->processCsdbData($csdbPath, $title);
                    }
                }

            return back()->with('success', 'Files uploaded and processed successfully.');
    
        } catch (Exception $e) {
           
            return back()->withErrors(['upload_error' => $e->getMessage()]);
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
   
    private function processCsdbData($csdbFilePath, $title)
        {
            // Fetch question labels from DCF metadata
            $labelNames = DB::table('imaster.item_labels')
                ->pluck('item_label', 'item_name')
                ->mapWithKeys(fn($label, $key) => [strtolower($key) => $label]);

            // Configure SQLite connection dynamically
            config(['database.connections.cspro' => [
                'driver' => 'sqlite',
                'database' => $csdbFilePath,
                'prefix' => '',
            ]]);

            DB::purge('cspro'); // Clear previous connection cache

            // Get only data tables (exclude meta/system ones)
            $excludeTables = ['cases', 'meta', 'file_revisions', 'sync_history', 'vector_clock', 'notes', 'level-1'];
            $tables = collect(DB::connection('cspro')
                ->select("SELECT name FROM sqlite_master WHERE type='table'"))
                ->pluck('name')
                ->reject(fn($table) => in_array($table, $excludeTables))
                ->values()
                ->toArray();

            // Useful key variations
            $formKeys = ['form_number', 'form_no', 'f_n', 'from_no'];
            $personKeys = ['person_id', 'p_id', 'pid', 'person', 'id_info'];

            // Process in chunks
            DB::connection('cspro')->table('cases')->orderBy('id')->chunk(100, function ($cases) use ($tables, $labelNames, $title, $formKeys, $personKeys) {
                foreach ($cases as $case) {
                    $personId = 'N/A';
                    $formNumber = 'N/A';

                    foreach ($tables as $tableName) {
                        $data = DB::connection('cspro')->table($tableName)
                            ->where('level-1-id', function ($query) use ($case) {
                                $query->select('level-1-id')
                                    ->from('level-1')
                                    ->where('case-id', $case->id)
                                    ->limit(1);
                            })->first();

                        if ($data) {
                            foreach ($formKeys as $col) {
                                if (!empty($data->$col)) {
                                    $formNumber = $data->$col;
                                    break;
                                }
                            }
                            foreach ($personKeys as $col) {
                                if (!empty($data->$col)) {
                                    $personId = $data->$col;
                                    break;
                                }
                            }
                            if ($formNumber !== 'N/A' || $personId !== 'N/A') break;
                        }
                    }

                    // Insert case
                    $caseId = DB::table('imaster.cases')->insertGetId([
                        'title' => $title,
                        'case_id' => $case->id,
                        'person_id' => $personId,
                        'form_number' => $formNumber,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);

                    // Insert answers
                    foreach ($tables as $tableName) {
                        $data = DB::connection('cspro')->table($tableName)
                            ->where('level-1-id', function ($query) use ($case) {
                                $query->select('level-1-id')
                                    ->from('level-1')
                                    ->where('case-id', $case->id)
                                    ->limit(1);
                            })->first();

                        if ($data) {
                            foreach ((array) $data as $column => $value) {
                                
                                $normalizedKey = strtolower(trim($column));

                                // Log missing label (for debug)
                                if (!isset($labelNames[$normalizedKey]) && str_starts_with($normalizedKey, 'q')) {
                                    logger()->info("Skipped key (label not found): $normalizedKey");
                                }

                                // Only insert if key starts with q and has a label
                                if (str_starts_with($normalizedKey, 'q') && isset($labelNames[$normalizedKey])) {
                                    logger()->info("Inserting answer", [
                                        'case_id' => $caseId,
                                        'record_name' => $tableName,
                                        'question_label' => $labelNames[$normalizedKey],
                                        'answer' => $value ?? 'No Answer',
                                    ]);

                                    DB::table('imaster.case_answers')->insert([
                                        'case_id' => $caseId,
                                        'record_name' => $tableName,
                                        'question_label' => $labelNames[$normalizedKey],
                                        'answer' => $value ?? 'No Answer',
                                        'created_at' => now(),
                                        'updated_at' => now(),
                                    ]);
                                }
                            }
                        }
                    }

                    // Insert notes
                    $notes = DB::connection('cspro')->table('notes')->where('case_id', $case->id)->get();
                    foreach ($notes as $note) {
                        DB::table('imaster.case_notes')->insert([
                            'case_id' => $caseId,
                            'field_name' => $note->field_name ?? null,
                            'note' => $note->content ?? null,
                            'operator_id' => $note->operator_id ?? null,
                            'modified_time' => $note->modified_time ?? null,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }
                }
            });
        }

    
    private function validateCsdbWithDcf($csdbFilePath)
    {
        // Get expected item names from DCF metadata
        $expectedColumns = DB::table('imaster.item_labels')
            ->pluck('item_name')
            ->map(fn($item) => strtolower(trim($item)))
            ->toArray();
    
        // Columns to ignore during validation
        $ignoreColumns = [
            // System/meta fields
            'device', 'field_name', 'level_key', 'record_occurrence', 'item_occurrence',
            'subitem_occurrence', 'content', 'operator_id', 'modified_time',
            'schema_version', 'cspro_version', 'dictionary', 'dictionary_structure', 'dictionary_timestamp',
            'id', 'device_id', 'timestamp', 'file_revision', 'device_name', 'user_name', 'universe',
            'direction', 'server_revision', 'partial', 'last_id', 'key', 'label', 'questionnaire',
            'last_modified_revision', 'deleted', 'file_order', 'verified', 'partial_save_mode',
            'partial_save_field_name', 'partial_save_level_key', 'partial_save_record_occurrence',
            'partial_save_item_occurrence', 'partial_save_subitem_occurrence', 'revision',
            'signature', 'data', 'binary-data-signature', 'sync-history-id', 'binary-sync-history-id',
            'binary-sync-history-archive',
    
            // Linking fields (these are needed but not DCF defined)
            'case_id', 'case-id', 'level-1-id', 'name_rec-id', 'mahiti_info-id'
        ];
    
        // Configure SQLite connection
        config(['database.connections.cspro' => [
            'driver' => 'sqlite',
            'database' => $csdbFilePath,
            'prefix' => '',
        ]]);
    
        DB::purge('cspro');
    
        $tables = collect(DB::connection('cspro')->select("SELECT name FROM sqlite_master WHERE type='table'"))
            ->pluck('name')
            ->toArray();
    
        $mismatchedItems = [];
    
        // Iterate over tables to find column names
        foreach ($tables as $tableName) {
            $columns = collect(DB::connection('cspro')->select("PRAGMA table_info('$tableName')"))
                ->pluck('name')
                ->map(fn($col) => strtolower(trim($col)))
                ->toArray();
    
            foreach ($columns as $column) {
                if (!in_array($column, $expectedColumns) && !in_array($column, $ignoreColumns)) {
                    $mismatchedItems[] = "$column (in table $tableName)";
                }
            }
        }
    
        if (!empty($mismatchedItems)) {
            session()->flash('validation_mismatches', $mismatchedItems);
            return false;
        }
    
        return true;
    }
    
    private function findFirstMatchingValue($data, array $keys)
    {
        foreach ($keys as $key) {
            if (!empty($data->$key)) {
                return $data->$key;
            }
        }
        return 'N/A';
    }
    public function detailReports(Request $request){
        $case_title = DB::table('imaster.title')->get();
        return view('dashboards.eva-dir.csPro.detail-reports',compact('case_title'));
        // return view('dashboards.eva-dir.csPro.detail-reports', [
        //     'case' => Cases::with('answers')->get(),
        //     'answers' => CaseAnswer::with('case')->get(),
        // ]);
    }
    public function caseFiltter(Request $request){
        $caseId = (int) $request->id;

        $field_officer = Cases::select('person_id','form_number','id')
            ->where('t_id', $caseId)
            ->get();
           

        $select_info = DB::table('imaster.item_labels')
                        ->select('record_label','record_name')
                        ->where('t_id', $caseId)
                        ->distinct()
                        ->get();
           
        return response()->json([
            'field_officer' => $field_officer,
            'select_info' => $select_info,
        
        ]);
    }
    public function caseInfoFiltter(Request $request){
        $select_info_array = array_map(function ($item) {
            return Str::lower($item);
        }, $request->select_info);
    
        $field_officer_array = $request->field_officer;

    //    dd($request->all());
   

        $select_field = DB::table('imaster.case_answers')
                    ->select('question_label', 'case_id')
                    ->whereIn('record_name', $select_info_array) // use the lowercased array here
                    ->whereIn('case_id', $field_officer_array)
                    ->get();
    
                                
            

        return response()->json([
            'select_field' => $select_field
        ]);
    }
    public function caseFieldFiltter(Request $request)
    {
        // Get selected items from the request

        $selectedItems = $request->input('selected_items'); // array of { question_label, case_id }
    
            $results = [];
            DB::connection()->enableQueryLog();
            foreach ($selectedItems as $item) {

                $data = DB::table('imaster.case_answers')
                        ->where('question_label', 'LIKE', '%' . $item['question_label'] . '%')
                        ->where('case_id', $item['case_id'])
                        ->get();
            
                $results[] = [
                    'question_label' => $item['question_label'],
                   // 'case_id' => $item['case_id'],
                    'answers' => $data,
                ];
            }
       //     dd($results);
            return response()->json($results);

    }

}
