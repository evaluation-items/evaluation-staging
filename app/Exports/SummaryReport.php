<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use App\Models\Stage;
use App\Models\Proposal;
use Carbon\Carbon;

class SummaryReport implements FromCollection, WithHeadings, WithStyles
{
    protected $draft_id;

    function __construct($draft_id)
    {
        $this->draft_id = $draft_id;
    }

   public function collection()
{
    $exportData = [];

    $proposal_list = Proposal::where('status_id', 23)
        ->whereIn('draft_id', $this->draft_id)
        ->get();

    foreach ($proposal_list as $proposal) {

        $stage = Stage::where('draft_id', $proposal->draft_id)->first();
        if (!$stage) {
            continue;
        }

        $schemeName = proposal_name($proposal->draft_id);

        // Always show all 5 stages
        $stageMap = [
            'No. of days for Requisition' => [
                'from' => $stage->requistion_sent_hod ?? null,
                'to'   => $stage->requisition ?? null
            ],
            'Approval Study Design' => [
                'from' => $stage->study_entrusted ?? null,
                'to'   => $stage->study_design_hod_date ?? null
            ],
            'Suggestion of I.O. for Draft Report' => [
                'from' => $stage->report_sent_hod_date ?? null,
                'to'   => $stage->report_draft_hod_date ?? null
            ],
            'Date of Draft Report sent to DEC' => [
                'from' => $stage->report_draft_sent_hod_date ?? null,
                'to'   => $stage->dept_eval_committee_datetime ?? null
            ],
            'Minutes of Meeting From DEC' => [
                'from' => $stage->dept_eval_committee_datetime ?? null,
                'to'   => $stage->minutes_meeting_dec ?? null
            ],
        ];
        $firstRow = true;

        foreach ($stageMap as $stageName => $dates) {

           $from = $dates['from'];
            $to   = $dates['to'];

            if ($from && $to) {
                // both available
                $showFrom = \Carbon\Carbon::parse($from)->format('d-m-Y');
                $showTo   = \Carbon\Carbon::parse($to)->format('d-m-Y');
                $days     = $this->countDays($from, $to);
            } elseif ($from && !$to) {
                // only from available
                $showFrom = \Carbon\Carbon::parse($from)->format('d-m-Y');
                $showTo   = '-';
                $days     = 0;
            } elseif (!$from && $to) {
                // only to available
                $showFrom = '-';
                $showTo   = \Carbon\Carbon::parse($to)->format('d-m-Y');
                $days     = 0;
            } else {
                // both null
                $showFrom = '-';
                $showTo   = '-';
                $days     = 0;
            }

            $exportData[] = [
                $firstRow ? $schemeName : '',
                $stageName,
                $days,
                $showFrom,
                $showTo,
            ];


            $firstRow = false;
        }
    }

    return collect($exportData);
}


    public function headings(): array
    {
        return [
            'Name of Scheme',
            'Stage',
            'Days',
            'From Date',
            'To Date'
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }

    private function countDays($start, $end)
    {
        if (empty($start) || empty($end)) {
            return 0;
        }

        return Carbon::parse($start)->diffInDays(Carbon::parse($end));
    }
}
