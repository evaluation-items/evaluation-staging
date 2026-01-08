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
use App\Exports\CsproAnswerExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;


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

            foreach ($request->file('cspro_csdb_file') as $csdbFile) {
                $csdbFilePath = $csdbFile->storeAs('cspro_csdb', $csdbFile->getClientOriginalName(), 'public');
              //  $validationResult = $this->validateCsdbWithDcf(storage_path('app/public/' . $csdbFilePath));
                // if ($validationResult !== true) {
                //     Log::error("Validation failed for {$csdbFile->getClientOriginalName()}: {$validationResult}");
                //     continue;
                // }
                $this->processCsdbData(storage_path('app/public/' . $csdbFilePath), $tId);
            }

            return redirect()->back()->withSuccess('CSDB and DCF files processed successfully');
        } catch (Exception $e) {
            Log::error('Error processing CSPRO files: ' . $e->getMessage());
          return back()->withErrors($e->getMessage());
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

        // Cache questions to avoid repeated queries
        $questionMap = DB::table('imaster.questions')
            ->where('t_id', $tId)
            ->pluck('id', 'item_name')
            ->mapWithKeys(function ($id, $itemName) {
                return [strtoupper(trim($itemName)) => $id];
            })
            ->toArray();


        // Define key variations for metadata
        $formKeys = ['form_number', 'form_no', 'f_n', 'from_no', 'Form_no', 'FROM_NO'];
        $personKeys = ['person_id', 'p_id', 'pid', 'person', 'id_info', 'Person_id', 'PERSON_ID'];

        foreach ($cases as $case) {
            // Initialize metadata
            $personId = 'N/A';
            $formNumber = 'N/A';
            $titleId = $tId;

            // Fetch data for metadata (e.g., from the main table)
            $mainData = DB::connection('cspro')
                ->table('level-1')
                ->where('case-id', $case->id)
                ->first();

            // Extract person_id and form_id if available
            if ($mainData) {
                $mainDataArray = (array) $mainData;
             
                foreach ($personKeys as $key) {
                    if (isset($mainDataArray[$key]) && !empty($mainDataArray[$key])) {
                        $personId = $mainDataArray[$key];
                        break;
                    }
                }
                foreach ($formKeys as $key) {
                    if (isset($mainDataArray[$key]) && !empty($mainDataArray[$key])) {
                        $formNumber = $mainDataArray[$key];
                        break;
                    }
                }
            } 

            // Insert case data into cspro_cases table
            $caseId = DB::table('imaster.cspro_cases')->insertGetId([
                't_id' => $titleId,
                'case_id' => $case->id,
                'person_id' => $personId,
                'form_id' => $formNumber,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            foreach ($tables as $tableName) {
                if ($tableName === 'cases' || in_array($tableName, ['meta', 'file_revisions', 'sync_history', 'vector_clock', 'notes'])) {
                    continue;
                }

                // Fetch data for the case
                $data = DB::connection('cspro')
                    ->table($tableName)
                    ->where('level-1-id', function ($query) use ($case) {
                        $query->select('level-1-id')
                            ->from('level-1')
                            ->where('case-id', $case->id)
                            ->limit(1);
                    })
                    ->first();

                if (!$data) {
                    Log::warning("No data found for table {$tableName} and case ID {$case->id}");
                    continue;
                }

                $answers = []; // Store answers for this table to link with notes
                foreach ((array) $data as $column => $value) {
                    // Skip null or empty values
                    if (is_null($value) || trim($value) === '') {
                        continue;
                    }

               
                    // Skip metadata columns unless they are valid questions
                    $normalizedColumn = strtoupper(trim($column));
                    if (in_array($normalizedColumn, array_merge(['LEVEL-1-ID', 'CASE-ID'], $formKeys, $personKeys))) {
                        continue;
                    }

                    // Check if question exists in questionMap
                    if (!isset($questionMap[$normalizedColumn])) {
                        Log::warning("No question found for item_name: {$normalizedColumn}, t_id: {$titleId}");
                        continue;
                    }

                    // Insert answer
                    $answerId = DB::table('imaster.cspro_answer')->insertGetId([
                        'cased_id' => $caseId,
                        'question_id' => $questionMap[$normalizedColumn],
                        'answers' => $value,
                        't_id' => $titleId,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);

                    // Store answer for linking with notes
                    $answers[] = [
                        'question_id' => $questionMap[$normalizedColumn],
                        'answer_id' => $answerId,
                    ];
                }

                    $notes = DB::connection('cspro')->table('notes')->where('case_id', $case->id)->get();

                    // Process notes (one per case, no duplicates)
                    foreach ($notes as $note) {
                        // Assume notes table has a field_name column linking to question
                        $normalizedField = strtoupper(trim($note->field_name ?? ''));
                        if (!$normalizedField || !isset($questionMap[$normalizedField])) {
                            Log::warning("No question found for note field_name: {$normalizedField}, case ID: {$case->id}");
                            continue;
                        }

                        // Find the answer for this question
                        $matchingAnswer = collect($answers)->firstWhere('question_id', $questionMap[$normalizedField]);
                        if (!$matchingAnswer) {
                            Log::warning("No answer found for note field_name: {$normalizedField}, case ID: {$case->id}");
                            continue;
                        }

                        // Check for existing note
                        $existingNote = DB::table('imaster.cspro_notes')
                            ->where('cased_id', $caseId)
                            ->where('question_id', $matchingAnswer['question_id'])
                            ->where('notes', $note->content)
                            ->exists();

                        if (!$existingNote) {
                            DB::table('imaster.cspro_notes')->insert([
                                'cased_id' => $caseId,
                                'question_id' => $matchingAnswer['question_id'],
                                't_id' => $titleId,
                                'cspro_answer_id' => $matchingAnswer['answer_id'],
                                'notes' => $note->content,
                                'created_at' => now(),
                                'updated_at' => now(),
                            ]);
                        } 
                    }
            }
        }

    }

    private function validateCsdbWithDcf($csdbFilePath, $dcfFilePath)
    {
        try {
            // Configure SQLite connection for CSDB
            config(['database.connections.cspro' => [
                'driver' => 'sqlite',
                'database' => $csdbFilePath,
                'prefix' => '',
            ]]);
            DB::purge('cspro');

            // Get tables in CSDB
            $tables = collect(DB::connection('cspro')->select("SELECT name FROM sqlite_master WHERE type='table'"))
                ->pluck('name')
                ->toArray();
            Log::debug('CSDB Tables: ', $tables);

            // Check for case_id in CSDB and extract values
            $caseIds = [];
            $caseIdTable = null;
            foreach ($tables as $tableName) {
                $columns = collect(DB::connection('cspro')->select("PRAGMA table_info('$tableName')"))
                    ->pluck('name')
                    ->map(fn($col) => strtolower(trim($col)))
                    ->toArray();
                if (in_array('case_id', $columns) || in_array('case-id', $columns)) {
                    $caseIdTable = $tableName;
                    $caseIds = DB::connection('cspro')->table($tableName)->pluck('case_id')->toArray();
                    break;
                }
            }

            if (empty($caseIdTable)) {
                return "No case_id or case-id column found in .csdb file";
            }
            Log::debug("CSDB case_id table: $caseIdTable, case_ids: ", $caseIds);

            // Check for duplicate case_id values
            $uniqueCaseIds = array_unique($caseIds);
            if (count($caseIds) !== count($uniqueCaseIds)) {
                $duplicates = array_diff_assoc($caseIds, $uniqueCaseIds);
                return "Duplicate case_id values found in .csdb file: " . implode(', ', array_unique($duplicates));
            }

            // Extract fields from DCF
            $dcfMetadata = $this->parseDcfFile($dcfFilePath);
            Log::debug('DCF Metadata: ', $dcfMetadata);

            // Use item_names if fields is not present
            $dcfFields = $dcfMetadata['fields'] ?? $dcfMetadata['item_names'] ?? [];
            if (empty($dcfFields)) {
                return "No fields or item_names found in .dcf file, cannot validate field consistency";
            }

            // Check if case_id is defined in DCF (optional)
            if (!in_array('case_id', array_map('strtolower', $dcfFields)) && !in_array('case-id', array_map('strtolower', $dcfFields))) {
                Log::warning('case_id or case-id not defined in .dcf file, proceeding with CSDB uniqueness');
            }

            // Normalize item names for comparison
            $ignoreColumns = [
                'schema_version', 'cspro_version', 'dictionary', 'dictionary_structure', 'dictionary_timestamp',
                'id', 'device_id', 'timestamp', 'file_revision', 'device_name', 'user_name', 'universe',
                'direction', 'server_revision', 'partial', 'last_id', 'key', 'label', 'questionnaire',
                'last_modified_revision', 'deleted', 'file_order', 'verified', 'partial_save_mode',
                'partial_save_field_name', 'partial_save_level_key', 'partial_save_record_occurrence',
                'partial_save_item_occurrence', 'partial_save_subitem_occurrence', 'case_id', 'case-id',
                'device', 'revision', 'field_name', 'level_key', 'record_occurrence', 'item_occurrence',
                'subitem_occurrence', 'content', 'operator_id', 'modified_time', 'level-1-id',
                'simple_info-id', 'process_of_implementation-id', 'utility_of_the_plan-id',
                'regarding_making_economic_activity_more_sustainable-id', 'fill_code-id',
                'complete_sentence-id', 'signature', 'data', 'binary-data-signature', 'sync-history-id',
                'binary-sync-history-id', 'binary-sync-history-archive', 'person_id', 'form_no', 'personid', 'formno'
            ];

            $csdbItemNames = [];
            foreach ($tables as $tableName) {
                $columns = collect(DB::connection('cspro')->select("PRAGMA table_info('$tableName')"))
                    ->pluck('name')
                    ->map(fn($col) => strtoupper(str_replace('_', '', trim($col))))
                    ->toArray();
                $csdbItemNames = array_merge($csdbItemNames, array_diff($columns, array_map('strtoupper', $ignoreColumns)));
            }
            Log::debug('CSDB Item Names (filtered): ', $csdbItemNames);

            if (empty($dcfMetadata['item_names'])) {
                return "No item_names found in .dcf file, cannot validate field consistency";
            }

            // Normalize DCF item names
            $dcfItemNames = array_map(fn($name) => strtoupper(str_replace('_', '', trim($name))), $dcfMetadata['item_names']);
            Log::debug('DCF Item Names (normalized): ', $dcfItemNames);

            // Check for mismatches
            $missingInDcf = array_diff($csdbItemNames, $dcfItemNames);
            if (!empty($missingInDcf)) {
                return "Item names in .csdb not defined in .dcf: " . implode(', ', $missingInDcf);
            }
            $missingInCsdb = array_diff($dcfItemNames, $csdbItemNames);
            if (!empty($missingInCsdb)) {
                return "Item names defined in .dcf not found in .csdb: " . implode(', ', $missingInCsdb);
            }

            return true;
        } catch (Exception $e) {
            Log::error('Validation error: ' . $e->getMessage());
            return "Validation error: " . $e->getMessage();
        }
    }

    private function parseDcfFile($dcfFilePath)
    {
        try {
            $dcfContent = file_get_contents($dcfFilePath);
            Log::debug('Raw DCF Content: ', [$dcfContent]);
            $dcfMetadata = json_decode($dcfContent, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new Exception("Invalid .dcf file format: " . json_last_error_msg());
            }
            Log::debug('Parsed DCF Metadata: ', $dcfMetadata);
            return $dcfMetadata;
        } catch (Exception $e) {
            Log::error('Error parsing .dcf file: ' . $e->getMessage());
            throw $e;
        }
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

        $select_field = DB::table('imaster.case_answers')
                    ->select('question_label', 'case_id')
                    ->whereIn('record_name', $select_info_array) // use the lowercased array here
                    ->whereIn('case_id', $field_officer_array)
                    ->get();

        return response()->json([
            'select_field' => $select_field
        ]);
    }
   
    public function export(Request $request)
    {
        $t_id = $request->t_id;
        $selectedItems = $request->input('selected_items');
        $questionIds = $request->input('question_ids');
        $search = $request->input('search'); // Receive search text from front-end
      //  dd($t_id, $selectedItems, $questionIds, $search);
        return  Excel::download(new CsproAnswerExport($t_id, $selectedItems, $questionIds, $search), 'answers.xlsx');

        //return Excel::download(new CsproAnswerExport($search), 'answers.xlsx');
    }
   


    public function caseFieldFiltter(Request $request)
    {
        $t_id = $request->t_id;
        $selectedItems = $request->input('selected_items');
        $questionIds = $request->input('question_ids');
        
        $inputQuestions = [];
        $html = '';
        
        // Prepare filter input
        foreach ($questionIds as $questionId) {
            if (isset($selectedItems[$questionId])) {
                $inputQuestions[$questionId] = $selectedItems[$questionId];
            }
        }
        
        if (!is_null($t_id) && $selectedItems) {
            // Step 1: Get matching case IDs
            $caseIds = DB::table('imaster.cspro_answer')
                ->where('t_id', $t_id)
                ->whereIn('question_id', array_keys($inputQuestions))
                ->where(function ($query) use ($inputQuestions) {
                    foreach ($inputQuestions as $questionId => $answers) {
                        $query->orWhere(function ($subQuery) use ($questionId, $answers) {
                            $subQuery->where('question_id', $questionId)
                                ->whereIn('answers', $answers);
                        });
                    }
                })
                ->select('cased_id', 'question_id')
                ->get()
                ->groupBy('cased_id')
                ->filter(function ($group) use ($inputQuestions) {
                    $matchedQuestions = $group->pluck('question_id')->unique()->toArray();
                    return count(array_intersect(array_keys($inputQuestions), $matchedQuestions)) === count($inputQuestions);
                })
                ->keys()
                ->toArray();
        
            // Step 2: Load all related questions
            $questions = DB::table('imaster.questions')
                ->where('t_id', $t_id)
                ->get()
                ->keyBy('id');
        
            // Step 3: Load all options for easy mapping
            $optionsMap = DB::table('imaster.question_options')
                ->where('t_id', $t_id)
                ->get()
                ->groupBy(function ($item) {
                    return $item->question_id . ':' . $item->values;
                });
        
            // Step 4: Load all answers
            $allAnswers = DB::table('imaster.cspro_answer')
                ->whereIn('cased_id', $caseIds)
                ->where('t_id', $t_id)
                ->get();
        
            $groupedRows = [];
            $headers = [];
        
            // Step 5: Group answers by case and map values to option labels
            foreach ($allAnswers as $answer) {
                $caseId = $answer->cased_id;
                $questionId = $answer->question_id;
                $answerValue = $answer->answers;
        
                $questionLabel = $questions[$questionId]->question ?? 'Unknown Question';
        
                // Prepare table headers
                if (!in_array($questionLabel, $headers)) {
                    $headers[] = $questionLabel;
                }
        
                // Map value to option text
                $optionKey = $questionId . ':' . $answerValue;
                $optionText = $optionsMap[$optionKey][0]->options ?? $answerValue;
        
                // Group by caseId + question
                if (!isset($groupedRows[$caseId])) {
                    $groupedRows[$caseId] = [];
                }
        
                $groupedRows[$caseId][$questionLabel][] = $optionText;
            }
        
            // Step 6: Build the HTML table
            $html .= '<table id="example1" class="table table-bordered table-striped">';
            $html .= '<thead><tr>';
            foreach ($headers as $header) {
                $html .= "<th>$header</th>";
            }
            $html .= '</tr></thead><tbody>';
        
            if (!empty($groupedRows)) {
                foreach ($groupedRows as $caseId) {
                    $html .= '<tr>';
                    foreach ($headers as $header) {
                        $answer = $caseId[$header] ?? [];
                        $html .= "<td>" . ($answer[0] ?? 'No answer') . "</td>";
                    }
                    $html .= '</tr>';
                }
            } else {
                $html .= '<tr><td colspan="' . count($headers) . '" class="text-center">No data available</td></tr>';
            }
        
            $html .= '</tbody></table>';
        
        
            // Return the HTML table as part of the response
            return response()->json(['html' => $html]);
        } else {
            return response()->json('message', 'Something went wrong');
        }
    }

    public function graphdetailReports(Request $request){
        $case_title = DB::table('imaster.cspro_title')->get();
        return view('dashboards.eva-dir.csPro.graph-detail-reports',compact('case_title'));
    }
    public function caseGraphreport(Request $request){
        try {
            $t_id = $request->t_id;
        
            if ($t_id) {
                $questions = DB::table('imaster.questions')
                            ->where('t_id', $t_id)
                            ->get();
        
                if (!$questions) {
                    return response()->json(['error' => 'Question not found'], 404);
                }
        
                $finalResults = [];
        
                foreach ($questions as $question) {
                    $options = DB::table('imaster.question_options')
                        ->where('t_id', $t_id)
                        ->where('question_id', $question->id)
                        ->get();
        
                    if ($options->isEmpty()) {
                        continue;
                    }
        
                    $totalAnswers = DB::table('imaster.cspro_answer')
                        ->where('t_id', $t_id)
                        ->where('question_id', $question->id)
                        ->count();
        
                    $counts = DB::table('imaster.cspro_answer')
                        ->select('answers', DB::raw('COUNT(*) as count'))
                        ->where('t_id', $t_id)
                        ->where('question_id', $question->id)
                        ->groupBy('answers')
                        ->pluck('count', 'answers');
        
                    $results = [];
                    $totalCount = 0; // ✅ Reset total count for each question

                    foreach ($options as $option) {
                        $count = $counts[$option->values] ?? 0;
                        $totalCount += $count;
                    }

                    // Check if total is 0 — if yes, use '-' for all entries
                        if ($totalCount === 0) {
                            foreach ($options as $option) {
                                $results[] = [
                                    'option'     => $option->options,
                                    'value'      => $option->values,
                                    'count'      => '-',
                                    'percentage' => '-',
                                ];
                            }

                            $results[] = [
                                'option'     => 'Total Summary',
                                'value'      => null,
                                'count'      => '-',
                                'percentage' => '-',
                            ];
                        } else {
                            foreach ($options as $option) {
                                $count = $counts[$option->values] ?? 0;
                                $results[] = [
                                    'option'     => $option->options,
                                    'value'      => $option->values,
                                    'count'      => $count, // show 0 if value is 0
                                    'percentage' => round(($count / $totalCount) * 100, 2),
                                ];
                            }

                            $results[] = [
                                'option'     => 'Total Summary',
                                'value'      => null,
                                'count'      => $totalCount,
                                'percentage' => 100,
                            ];
                        }

                        $finalResults[] = [
                            'question_id' => $question->id,
                            'question'    => $question->question,
                            'data'        => $results
                        ];
                                        //     $showPercentage = $totalCount > 0;


                //     foreach ($options as $option) {
                //         $count = $counts[$option->values] ?? 0;
                //         $percent = $showPercentage ? round(($count / $totalCount) * 100, 2) : '-';

                //         $results[] = [
                //             'option'     => $option->options,
                //             'value'      => $option->values,
                //             'count'      => $count,
                //             'percentage' => $percent,
                //         ];
                //     }
                //     // foreach ($options as $option) {
                //     //     $count = $counts[$option->values] ?? 0;
                //     //     $percent = $totalAnswers ? round(($count / $totalAnswers) * 100, 2) : '-';
                       
                //     //     $results[] = [
                //     //         'option'     => $option->options,
                //     //         'value'      => $option->values,
                //     //         'count'      => $count,
                //     //         'percentage' => $percent,
                //     //     ];
        
                //     //     $totalCount += $count;
                //     // }
        
                //     // ✅ Append Total Summary for this question
                //    // Append Total Summary row
                //     $results[] = [
                //         'option'     => 'Total Summary',
                //         'value'      => null,
                //         'count'      => $totalCount,
                //         'percentage' => $showPercentage ? 100 : '-', // ✅ Display '-' if all counts are 0
                //     ];

                //     $finalResults[] = [
                //         'question_id' => $question->id,
                //         'question'    => $question->question,
                //         'data'        => $results
                //     ];
                }
               
                return response()->json($finalResults);
            }
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
        }
        
    }
    public function downloadPdf(Request $request)
    {
        $html = $request->input('html');
    
        $pdf = \PDF::loadHTML($html)->setPaper('a4', 'portrait');
        return $pdf->download('Report.pdf');
    }    
}
