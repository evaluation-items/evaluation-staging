<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use App\Models\Stage;
use App\Models\Proposal;
use Carbon\Carbon;

class SummaryReport implements FromCollection, WithStyles
{
    protected $draft_id;

    function __construct($draft_id)
    {
        $this->draft_id = $draft_id;
    }

    public function collection()
{
    $exportData = [];

    // Header row
    $exportData[] = ['Name of Scheme', 'Stage', 'Proper Report Count', 'Delay Report Count'];

    $proposal_list = Proposal::where('status_id', 23)
        ->whereIn('draft_id', $this->draft_id)
        ->get();

    foreach ($proposal_list as $proposal) {

        $item = StageCount($proposal->draft_id);
        if (!$item) {
            continue;
        }

        $schemeName = proposal_name($proposal->draft_id);

        // Stage Mapping (VERTICAL OUTPUT)
        $stageMap = [
            'Requisition' => [
                'proper' => $item['get_count']['requisition'] ?? 0,
                'delay'  => $item['get_count_delay']['requisition_delay'] ?? 0,
            ],
            'Preparation of study design and question' => [
                'proper' => $item['get_count']['study_design_date'] ?? 0,
                'delay'  => $item['get_count_delay']['study_design_date_delay'] ?? 0,
            ],
            'Approval from concern department' => [
                'proper' => $item['get_count']['study_design_receive_hod_date'] ?? 0,
                'delay'  => $item['get_count_delay']['study_design_receive_hod_date_delay'] ?? 0,
            ],
            'Pilot study' => [
                'proper' => $item['get_count']['polot_study_date'] ?? 0,
                'delay'  => $item['get_count_delay']['polot_study_date_delay'] ?? 0,
            ],
            'Field Work' => [
                'proper' => $item['get_count']['field_survey_startdate'] ?? 0,
                'delay'  => $item['get_count_delay']['field_survey_startdate_delay'] ?? 0,
            ],
            'Data scrutiny, entry/Validation' => [
                'proper' => $item['get_count']['data_entry_level_start'] ?? 0,
                'delay'  => $item['get_count_delay']['data_entry_level_start_delay'] ?? 0,
            ],
            'Report writing' => [
                'proper' => $item['get_count']['data_entry_level_start'] ?? 0,
                'delay'  => $item['get_count_delay']['data_entry_level_start_delay'] ?? 0,
            ],
            'Suggestion of concern department on report' => [
                'proper' => $item['get_count']['report_draft_hod_date'] ?? 0,
                'delay'  => $item['get_count_delay']['report_draft_hod_date_delay'] ?? 0,
            ],
            'DEC' => [
                'proper' => $item['get_count']['dept_eval_committee_datetime'] ?? 0,
                'delay'  => $item['get_count_delay']['dept_eval_committee_datetime_delay'] ?? 0,
            ],
            'ECC' => [
                'proper' => $item['get_count']['eval_cor_date'] ?? 0,
                'delay'  => $item['get_count_delay']['eval_cor_date_delay'] ?? 0,
            ],
            'Publication' => [
                'proper' => $item['get_count']['final_report'] ?? 0,
                'delay'  => $item['get_count_delay']['final_report_delay'] ?? 0,
            ],
            'Dropped' => [
                'proper' => $item['get_count']['dropped'] ?? 0,
                'delay'  => $item['get_count_delay']['dropped_delay'] ?? 0,
            ],
        ];

        $firstRow = true;

        foreach ($stageMap as $stageName => $counts) {

            $exportData[] = [
                $firstRow ? $schemeName : '', // show scheme name once
                $stageName,
                (int) ($counts['proper'] ?? 0),
                (int) ($counts['delay'] ?? 0),
            ];

            $firstRow = false;
        }
    }

    return collect($exportData);
}

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
 
}
