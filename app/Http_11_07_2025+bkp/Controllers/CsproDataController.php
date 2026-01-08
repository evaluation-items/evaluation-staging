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

        $select_field = DB::table('imaster.case_answers')
                    ->select('question_label', 'case_id')
                    ->whereIn('record_name', $select_info_array) // use the lowercased array here
                    ->whereIn('case_id', $field_officer_array)
                    ->get();

        return response()->json([
            'select_field' => $select_field
        ]);
    }
    public function caseFieldFiltter_old(Request $request)
    {
       
            $t_id = $request->t_id;
            $selectedItems = $request->input('selected_items');
            $questionIds = $request->input('question_ids'); // array of question ids

            if(!is_null( $t_id) && $selectedItems){
                //DB::connection()->enableQueryLog();

                $answers = DB::table('imaster.cspro_answer')
                                ->whereIn('question_id', $questionIds)
                                ->whereIn('answers', $selectedItems) // Corrected usage of whereIn
                                ->where('t_id', $t_id)
                                ->get();
                                //$queries = DB::getQueryLog();

              //  dd($queries);    
                // $tId = $answers->first()->t_id;
                $caseIds = DB::table('imaster.cspro_answer')->whereIn('answers',  $selectedItems)->where('t_id',  $t_id)->pluck('cased_id');
                $questionId = $answers->get()->question_id;
                $questions = DB::table('imaster.questions')->where('t_id', $t_id)->whereNot('id',$questionId)->get();

                $questionsWithAnswers = [];
                foreach ($questions as $question) {
                    $answer = DB::table('imaster.cspro_answer')->where('t_id', $t_id)->where("question_id", $question->id)->whereIn('cased_id',$caseIds)->get()->toArray();
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
                
             // Now, build the HTML table
            $headers = [];
            $groupedRows = [];

        // Loop through the questions and their answers to build the table headers and rows
        foreach ($questionsWithAnswers as $item) {
            $questionLabel = $item['question']->question;

            if (!in_array($questionLabel, $headers)) {
                $headers[] = $questionLabel;
            }

            foreach ($item['answers'] as $answer) {
                $groupedRows[$questionLabel][] = $answer->answers; // Store the answer data for each question
            }
        }
        // Start building the HTML table
        $html = '<table id="example1" class="table table-bordered table-striped">';
        $html .= '<thead><tr>';
        foreach ($headers as $header) {
            $html .= "<th>$header</th>";
        }
        $html .= '</tr></thead><tbody>';

        // Find the maximum number of answers for any question
        $totalRows = max(array_map(function ($header) use ($groupedRows) {
            return count($groupedRows[$header]);
        }, $headers));
        

        // Loop through and create the table rows
        for ($i = 0; $i < $totalRows; $i++) {
            $html .= '<tr>';
            foreach ($headers as $header) {
                $answers = $groupedRows[$header] ?? [];
                $answer = $answers[$i] ?? ''; // If no answer, leave it empty
                $html .= "<td>$answer</td>";
            }
            $html .= '</tr>';
        }
        $html .= '</tbody></table>';

        // Return the HTML table as part of the response
        return response()->json(['html' => $html]);
        } else {
            return response()->json('message', 'Something went wrong');
        }
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
    // public function caseFieldFiltter_old(Request $request){
    //     $t_id = $request->t_id;
    //     $selectedItems = $request->input('selected_items');
    //     $questionIds = $request->input('question_ids');
    //     $inputQuestions = [];

    //     // Loop through the question IDs
    //     foreach ($questionIds as $questionId) {
    //         if (isset($selectedItems[$questionId])) {
    //             // Assign the selected items for the current question ID
    //             $inputQuestions[$questionId] = $selectedItems[$questionId];
    //         }
    //     }
    //     $caseIds = DB::table('imaster.cspro_answer')
    //     ->where('t_id', $t_id)
    //     ->where(function ($query) use ($inputQuestions) {
    //         foreach ($inputQuestions as $questionId => $answers) {
    //             $query->where(function ($subQuery) use ($questionId, $answers) {
    //                 $subQuery->where('question_id', $questionId)
    //                          ->whereIn('answers', $answers);
    //             });
    //         }
    //     })
    //     ->pluck('cased_id')
    //     ->unique()
    //     ->toArray();
    //     if (empty($caseIds)) {
    //         return response()->json(['message' => 'No matching cases found.']);
    //     }
    //     $allAnswers = DB::table('imaster.cspro_answer')
    //         ->whereIn('cased_id', $caseIds)
    //         ->where('t_id', $t_id)
    //         ->get();

    //     $questions = DB::table('imaster.questions')
    //     ->where('t_id', $t_id)
    //     ->get()
    //     ->keyBy('id'); // Key by question ID for easy lookup

    // // Step 3: Group answers by case ID and question
    //     $groupedData = [];
    //     foreach ($allAnswers as $answer) {
    //         $caseId = $answer->cased_id;
    //         $questionId = $answer->question_id;
    //         $questionLabel = $questions[$questionId]->question ?? 'Unknown Question';

    //         if (!isset($groupedData[$caseId])) {
    //             $groupedData[$caseId] = [];
    //         }

    //         $groupedData[$caseId][$questionLabel][] = $answer->answers;
    //     }
    // }


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
        
                /*    foreach ($options as $option) {
                        $count = $counts[$option->values] ?? 0;
                        $percent = $totalAnswers ? round(($count / $totalAnswers) * 100, 2) : 0;
        
                        $results[] = [
                            'option'     => $option->options,
                            'value'      => $option->values,
                            'count'      => $count,
                            'percentage' => $percent,
                        ];
        
                        $totalCount += $count;
                    }
        
                    // ✅ Append Total Summary for this question
                    $results[] = [
                        'option'     => 'Total Summary',
                        'value'      => null,
                        'count'      => $totalCount,
                        'percentage' => $totalCount > 0 ? 100 : 0, // or optional: sum of all percentages
                    ];
        
                    $finalResults[] = [
                        'question_id' => $question->id,
                        'question'    => $question->question,
                        'data'        => $results
                    ];*/
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
