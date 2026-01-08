<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use App\Models\SchemeSend;
use Carbon\Carbon;
use App\Models\Stage;
use App\Models\Proposal;

class SummaryReport implements FromCollection
{
    protected $draft_id;

    function __construct($draft_id) {
        $this->draft_id = $draft_id;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $this->array1 = [];
        $this->array2 = [];


        $proposal_list = Proposal::where('status_id',23)->whereIn('draft_id',$this->draft_id)->get();
       

        $exportData = [['Name of Scheme', 'Proper Report count', 'Delay Report Count']];
        foreach ($proposal_list as $key => $proposal_data) {

            $item = StageCount($proposal_data->draft_id);
            if(!is_null($item)){
            $schemeName = proposal_name($proposal_data->draft_id);
                
            //Proper Count
            $properReportCount = 'Requisition = ' . $item['get_count']['requisition'] . ', ';
            $properReportCount .= 'Preparation of study design and question = ' . $item['get_count']['study_design_date'] . ', ';
            $properReportCount .= 'Approval from concern department = ' . $item['get_count']['study_design_receive_hod_date'] . ', ';
            $properReportCount .= 'Pilot study = ' . $item['get_count']['polot_study_date'] . ', ';
            $properReportCount .= 'Field Work = ' . $item['get_count']['field_survey_startdate']  . ', ';
            $properReportCount .= 'Data scrutiny, entry/Validation = ' . $item['get_count']['data_entry_level_start'] . ', ';
            $properReportCount .= 'Report writing = ' . $item['get_count']['data_entry_level_start'] . ', ';
            $properReportCount .= 'Suggestion of concern department on report = ' . $item['get_count']['report_draft_hod_date'] . ', ';
            $properReportCount .= 'DEC = ' . $item['get_count']['dept_eval_committee_datetime'] . ', ';
            $properReportCount .= 'ECC = ' . $item['get_count']['eval_cor_date'] . ', ';
            $properReportCount .= 'Publication = ' . $item['get_count']['final_report'] . ', ';
            $properReportCount .= 'Dropped = ' . $item['get_count']['dropped'] . ', ';

            //Delay Count
            $delayReportCount = 'Requisition = ' .$item['get_count_delay']['requisition_delay']  . ', ';
            $delayReportCount .= 'Preparation of study design and question = ' .$item['get_count_delay']['study_design_date_delay'] . ', ';
            $delayReportCount .= 'Approval from concern department = ' .$item['get_count_delay']['study_design_receive_hod_date_delay'] . ', ';
            $delayReportCount .= 'Pilot study = ' .$item['get_count_delay']['polot_study_date_delay'] . ', ';
            $delayReportCount .= 'Field Work = ' .$item['get_count_delay']['field_survey_startdate_delay'] . ', ';
            $delayReportCount .= 'Data scrutiny, entry/Validation = ' . $item['get_count_delay']['data_entry_level_start_delay'] . ', ';
            $delayReportCount .= 'Report writing = ' .$item['get_count_delay']['data_entry_level_start_delay'] . ', ';
            $delayReportCount .= 'Suggestion of concern department on report = ' .$item['get_count_delay']['report_draft_hod_date_delay'] . ', ';
            $delayReportCount .= 'DEC = ' .$item['get_count_delay']['dept_eval_committee_datetime_delay'] . ', ';
            $delayReportCount .= 'ECC = ' .$item['get_count_delay']['eval_cor_date_delay'] . ', ';
            $delayReportCount .= 'Publication = ' .$item['get_count_delay']['final_report_delay'] . ', ';
            $delayReportCount .= 'Dropped = ' .$item['get_count_delay']['dropped_delay'] . ', ';

            $exportData[] = [$schemeName, $properReportCount, $delayReportCount];
            }
        

        }
         return collect($exportData);
    }
}
