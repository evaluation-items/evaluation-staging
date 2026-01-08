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

                     $tId = DB::table('imaster.cspro_title')->insertGetId([
                        'title' => $title,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ], 't_id'); 
                    
                    if ($request->hasFile('cspro_dcf_file')) {
                        $dcfOriginalName = pathinfo($request->file('cspro_dcf_file')->getClientOriginalName(), PATHINFO_FILENAME) . '_' . time() . '.dcf';
                        $dcfPath = $request->file('cspro_dcf_file')->move(public_path('uploads'), $dcfOriginalName);
                        
                        $this->processDcfFile($dcfPath, $tId);
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
                        $this->processCsdbData($csdbPath, $tId);
                    }
                }

            return back()->with('success', 'Files uploaded and processed successfully.');
    
        } catch (Exception $e) {
           
            return back()->withErrors(['upload_error' => $e->getMessage()]);
        }
    }
    private function processDcfFile($dcfFilePath, $tId)
    {
        // Read DCF file content
        $dcfContent = file_get_contents($dcfFilePath);
        $dcfData = json_decode($dcfContent, true);

        if (!$dcfData || !isset($dcfData['levels'])) {
            throw new Exception("Invalid DCF file structure.");
        }

        foreach ($dcfData['levels'] as $level) {
            foreach ($level['records'] ?? [] as $record) {
                foreach ($record['items'] ?? [] as $item) {
                    $itemName = $item['name'] ?? 'unknown_item';
                    $question = $item['labels'][0]['text'] ?? 'No label';
    
                    // Check if the question already exists with the same item name then just return the id
                    $questionId = DB::table('imaster.questions')->insertGetId([
                        'question' => $question,
                        'item_name' => $itemName,
                        't_id' => $tId,
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);
    
                    if (!empty($item['valueSets'])) {
                        foreach ($item['valueSets'] as $valueSet) {
                            foreach ($valueSet['values'] ?? [] as $valueObj) {
                                $optionLabel = $valueObj['labels'][0]['text'] ?? null;
                                $optionValue = $valueObj['pairs'][0]['value'] ?? null;
    
                                if ($optionLabel !== null && $optionValue !== null) {
                                    DB::table('imaster.question_options')->insert([
                                        'question_id' => $questionId,
                                        'options' => $optionLabel,
                                        'values' => $optionValue,
                                        't_id' => $tId,
                                        'created_at' => now(),
                                        'updated_at' => now(),
                                    ]);
                                }
                            }
                        }
                    }
                }
            }
        }
    }
   
    private function processCsdbData($csdbFilePath, $tId)
    {
          

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

            // Useful key variations
            $formKeys = ['form_number', 'form_no', 'f_n', 'from_no'];
            $personKeys = ['person_id', 'p_id', 'pid', 'person', 'id_info'];

            // Process in chunks

            foreach ($cases as $case) {
                $personId = 'N/A';
                $formNumber = 'N/A';
                $titleId = $tId; // Set your title dynamically as required
            
            
                // Insert case data into cspro_cases table
                $caseId = DB::table('imaster.cspro_cases')->insertGetId([
                    't_id' => $titleId, 
                    'case_id' => $case->id,
                    'person_id' => $personId,
                    'form_id' => $formNumber,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                // dump($caseId);
                foreach ($tables as $tableName) {
                    // Skip the 'cases' table
                    if ($tableName === 'cases') continue;
                
                    // Fetch data for the current table
                    $data = DB::connection('cspro')->table($tableName)
                            ->where('level-1-id', function ($query) use ($case) {
                                $query->select('level-1-id')
                                      ->from('level-1')
                                      ->where('case-id', $case->id)
                                      ->limit(1);
                            })
                            ->first(); // Use first to get a single row
                    // Skip empty data (if no result found)
                    if (!$data) continue;
                    // dd($data);
                
                    // Loop over columns in the retrieved data (object-based access)
                    foreach ($data as $column => $value) {
                        // dump($column); // Debugging line to check the column and value
                        // dump($value); // Debugging line to check the column and value
                        // Check if the value starts with 'q' (indicating a question ID)
                        if (str_starts_with($column, 'q')) {
            
                            // Fetch the corresponding question from the database
                            // we should convert column to uppercase
                            $column = strtoupper($column);
                            $question = DB::table('imaster.questions')->where('item_name', ($column))->first();
                            //dump($question); // Debugging line to check the question
                            if ($question) {
                                // dump("In if question"); // Debugging line to check the column and value
                                $csproCases = DB::table('imaster.cspro_cases')->where('case_id', $case->id)->first();
                                // Insert the answer into cspro_answer table
                                DB::table('imaster.cspro_answer')->insert([
                                    'cased_id' => $csproCases->id,
                                    'question_id' => $question->id,
                                    'answers' => $value,
                                    't_id' => $titleId,
                                ]);
                            }
                        }
                    }
                }
   
        }
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
        $case_title = DB::table('imaster.cspro_title')->get();
        return view('dashboards.eva-dir.csPro.detail-reports',compact('case_title'));
        // return view('dashboards.eva-dir.csPro.detail-reports', [
        //     'case' => Cases::with('answers')->get(),
        //     'answers' => CaseAnswer::with('case')->get(),
        // ]);
    }
    public function caseFiltter(Request $request){
        $titleId = (int) $request->id;

        $question_option = DB::table('imaster.question_options as qo')
            ->join('imaster.questions as q', 'qo.question_id', '=', 'q.id')
            ->where('qo.t_id', $titleId)
            ->select('qo.*', 'q.question') // add any fields from questions you need
            ->get();

        //dd($question_option);
        return response()->json([
            'question_option' => $question_option, 
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
            $t_id = $request->t_id;
            $selectedItems = $request->input('selected_items');
            if(!is_null( $t_id) && $selectedItems){
                $answers = DB::table('imaster.cspro_answer')
                ->whereIn('answers', $selectedItems) // Corrected usage of whereIn
                ->where('t_id', $t_id)
                ->get();
                            
                $tId = $answers->first()->t_id;
                $caseIds = DB::table('imaster.cspro_answer')->whereIn('answers',  $selectedItems)->where('t_id',  $t_id)->pluck('cased_id');
                $questionId = $answers->first()->question_id;
                $questions = DB::table('imaster.questions')->where('t_id', $tId)->whereNot('id',$questionId)->get();

                $questionsWithAnswers = [];
                foreach ($questions as $question) {
                    $answer = DB::table('imaster.cspro_answer')->where('t_id', $tId)->where("question_id", $question->id)->whereIn('cased_id',$caseIds)->get()->toArray();
                    // dump($question);
                    $questionsWithAnswers[] = [
                        'question' => $question,
                        'answers' => $answer
                    ];
                }
                $answersQuestion = DB::table('imaster.questions')->where('id',$questionId)->first();
                $questionsWithAnswers[] = [
                    'question' => $answersQuestion,
                    'answers' => $answers->toArray()
                ];
             //   dd( $questionsWithAnswers['answers']);
                return response()->json($questionsWithAnswers);
            }else{
                return response()->json('message','Something went Wrong');
            }

           
           

    }

}
