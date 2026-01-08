<?php

namespace App\Exports;

use App\Models\CsproAnswer;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Sheet;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Facades\DB;
use App\Models\Question;
use Maatwebsite\Excel\Concerns\WithMapping;

class CsproAnswerExport implements FromArray, WithHeadings, WithStyles
{
    protected $search;
    protected $t_id;
    protected $selectedItems;
    protected $questionIds;
   // protected $questions;

    protected $headers;
    protected $rows;

    public function __construct($t_id, $selectedItems, $questionIds, $search = null)
{
    $this->t_id = $t_id;
    $this->selectedItems = $selectedItems;
    $this->questionIds = $questionIds;
    $this->search = $search;

    $this->rows = [];       // Optional default
    $this->headers = [];    // Optional default
    $this->prepareData();   // Prepares headers and rows
}

    private function prepareData()
    {
    
        $inputQuestions = [];

        foreach ($this->questionIds as $questionId) {
            if (isset($this->selectedItems[$questionId])) {
                $inputQuestions[$questionId] = $this->selectedItems[$questionId];
            }
        }
    
        if (!is_null($this->t_id) && $inputQuestions) {

            $optionMap = DB::table('imaster.question_options')
                        ->whereNull('deleted_at')
                        ->get()
                        ->groupBy('question_id')
                        ->map(function ($options) {
                            return collect($options)->pluck('options', 'values');
                        });
          
            // $answers = DB::table('imaster.cspro_answer')
            //     ->whereIn('question_id', array_keys($inputQuestions))
            //     ->where(function ($query) use ($inputQuestions) {
            //         foreach ($inputQuestions as $questionId => $answers) {
            //             $query->orWhere(function ($subQuery) use ($questionId, $answers) {
            //                 $subQuery->where('question_id', $questionId)->whereIn('answers', $answers);
            //             });
            //         }
            //     })
            //     ->where('t_id', $this->t_id)
            //     ->get();
    
          //  $caseIds = $answers->pluck('cased_id')->unique()->toArray();
          $caseIds = DB::table('imaster.cspro_answer')
                        ->where('t_id', $this->t_id)
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
                              
            if (!empty($this->search)) {
                $searchCaseIds = DB::table('imaster.cspro_answer')
                    ->where('t_id', $this->t_id)
                    ->where('answers', 'ILIKE', "%{$this->search}%")
                    ->whereIn('cased_id', $caseIds)
                    ->pluck('cased_id')
                    ->unique();
            
                $caseIds = collect($caseIds)->intersect($searchCaseIds)->values();
            }
                $answers = DB::table('imaster.cspro_answer')
                            ->where('t_id', $this->t_id)
                        ->whereIn('question_id', array_keys($inputQuestions))
                        ->whereIn('cased_id', $caseIds)
                        ->get();
                        
            $selectedQuestionIds = array_keys($inputQuestions);
    
            // Fetch all other questions under this t_id excluding selected filter questions
            $otherQuestions = DB::table('imaster.questions')
                ->where('t_id', $this->t_id)
                ->whereNotIn('id', $selectedQuestionIds)
                ->get();
    
            $questionsWithAnswers = [];
    
            foreach ($otherQuestions as $question) {
                $answerRaw = DB::table('imaster.cspro_answer')
                            ->where('t_id', $this->t_id)
                            ->where('question_id', $question->id)
                            ->whereIn('cased_id', $caseIds)
                            ->pluck('answers')
                            ->toArray();

            $answerData = array_map(function ($val) use ($question, $optionMap) {
                return $optionMap[$question->id][$val] ?? $val;
            }, $answerRaw);
                // $answerData = DB::table('imaster.cspro_answer')
                //     ->where('t_id', $this->t_id)
                //     ->where('question_id', $question->id)
                //     ->whereIn('cased_id', $caseIds)
                //     ->pluck('answers')
                //     ->toArray();
    
                $questionsWithAnswers[] = [
                    'question' => $question->question,
                    'answers' => $answerData,
                ];
            }
          
            // Add back selected filter questions too
            $filteredQuestions = DB::table('imaster.questions')
                            ->whereIn('id', $selectedQuestionIds)
                            ->get()
                            ->keyBy('id');
    
            // foreach ($inputQuestions as $questionId => $filterAnswers) {
            //     $qLabel = $filteredQuestions[$questionId]->question ?? 'Unknown Question';
            //     $answerRaw = $answers->where('question_id', $questionId)->pluck('answers')->toArray();

            //     $answerData = array_map(function ($val) use ($questionId, $optionMap) {
            //         return $optionMap[$questionId][$val] ?? $val;
            //     }, $answerRaw);
            //   //  $answerData = $answers->where('question_id', $questionId)->pluck('answers')->toArray();
    
            //     $questionsWithAnswers[] = [
            //         'question' => $qLabel,
            //         'answers' => $answerData,
            //     ];
            // }
            foreach ($inputQuestions as $questionId => $filterAnswers) {
                $qLabel = $filteredQuestions[$questionId]->question ?? 'Unknown Question';
                $answerRaw = $answers->where('question_id', $questionId)->pluck('answers')->toArray();
            
                $answerData = array_map(function ($val) use ($questionId, $optionMap) {
                    return $optionMap[$questionId][$val] ?? $val;
                }, $answerRaw);
            
                $questionsWithAnswers[] = [
                    'question' => $qLabel,
                    'answers' => $answerData,
                ];
            }
           
            // Prepare headers
            $this->headers = array_column($questionsWithAnswers, 'question');
    
            // Determine max number of rows
            $maxRows = max(array_map(function ($item) {
                return count($item['answers']);
            }, $questionsWithAnswers));
    
            // Prepare data rows (by index)
            // for ($i = 0; $i < $maxRows; $i++) {
            //     $row = [];
            //     foreach ($questionsWithAnswers as $item) {
            //         $row[] = $item['answers'][$i] ?? '';
            //     }
            //     $this->rows[] = $row;
            // }
            for ($i = 0; $i < $maxRows; $i++) {
                $row = [];
                foreach ($questionsWithAnswers as $item) {
                    $row[] = $item['answers'][$i] ?? '';
                }
            
                // Skip row if all values are empty
                if (collect($row)->filter()->isNotEmpty()) {
                    $this->rows[] = $row;
                }
            }
            
        }
    }
    public function array(): array
    {
        return $this->rows ?? [['No data']];
    }

    public function headings(): array
    {
        return $this->headers ?? ['No Headers'];
    }

    public function styles(Worksheet $sheet)
    {
         $columnCount = count($this->headers);
        $lastColumn = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($columnCount);

        $sheet->getStyle("A1:{$lastColumn}1")
            ->getFont()
            ->setName('Shruti')
            ->setSize(12)
            ->setBold(true); // Make header text bold
    }
}
