<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Carbon\Carbon;

class ExcelReport implements FromCollection, WithHeadings
{
    protected $data;

    function __construct($data) {
        $this->data = $data;
    }
     /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'Department Name',
            'Stages Completed',
            'Scheme Name',
            'Proposal Date',
            'Additional information / data of the scheme is sought to Implementing Office (HOD)',
            'Requisition',
            'Study Design and Schedule Preparation',
            'Inputs on Study Design and Survey Forms received from Implementing Office (HOD)',
            'Pilot study and Digitization of Survey Forms Completed',
            'Field Survey',
            'Data cleaning and Statistical Analysis',
            'Data Entry Level',
            'Report writing',
            'Inputs on Draft Report received from Implementing Office (HOD)',
            'Date of Departmental Evaluation Committee (DEC)',
            'Date of Evaluation Coordination Committee (ECC)',
            'Final Report',
            'Dropped',
        ];
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return collect([
            [
                'Department Name' => department_name($this->data['dept_id']),
                'Stages Completed' => (!empty( $this->data['final_report']) ?  Carbon::Parse( $this->data['final_report'])->format('M d Y') : "" ),
                'Scheme Name' => 'Test scheme Name',
                'Proposal Date' => (!empty( $this->data['proposal_date']) ? Carbon::Parse( $this->data['proposal_date'])->format('d-m-Y') : ''),
                'Additional information / data of the scheme is sought to Implementing Office (HOD)' => ((!empty( $this->data['scheme_hod_date'])) ? Carbon::Parse($this->data['scheme_hod_date'])->format('d-m-Y') : "") ,
                'Requisition' => ((!empty( $this->data['requisition'])) ? Carbon::Parse( $this->data['requisition'])->format('d-m-Y') : ""),
                'Study Design and Schedule Preparation' => (!empty($this->data['study_design_date']) ? Carbon::Parse($this->data['study_design_date'])->format('d-m-Y') : "" ),
                'Inputs on Study Design and Survey Forms received from Implementing Office (HOD)' => (!empty($this->data['study_design_receive_hod_date']) ? Carbon::Parse($this->data['study_design_receive_hod_date'])->format('d-m-Y') : ""),
                'Pilot study and Digitization of Survey Forms Completed' =>(!empty($this->data['polot_study_date']) ? Carbon::Parse($this->data['polot_study_date'])->format('d-m-Y') : "" ),
                'Field Survey' => (!empty($this->data['field_survey_startdate']) ? Carbon::Parse($this->data['field_survey_startdate'])->format('d-m-Y') : "" ),
                'Data cleaning and Statistical Analysis' => (!empty($this->data['data_statistical_startdate']) ? Carbon::Parse($this->data['data_statistical_startdate'])->format('d-m-Y') : "" ),
                'Data Entry Level' => (!empty($this->data['data_entry_level_start']) ? Carbon::Parse($this->data['data_entry_level_start'])->format('d-m-Y') : "" ),
                'Report writing' => (!empty($this->data['report_startdate']) ? Carbon::Parse($this->data['report_startdate'])->format('d-m-Y') : "" ),
                'Inputs on Draft Report received from Implementing Office (HOD)' => (!empty($this->data['report_draft_hod_date']) ? Carbon::Parse($this->data['report_draft_hod_date'])->format('d-m-Y') : ""),
                'Date of Departmental Evaluation Committee (DEC)' => (!empty($this->data['dept_eval_committee_datetime']) ? Carbon::Parse($this->data['dept_eval_committee_datetime'])->format('d-m-Y H:i A') : ''),
                'Date of Evaluation Coordination Committee (ECC)' => (!empty($this->data['eval_cor_date']) ?  Carbon::Parse($this->data['eval_cor_date'])->format('d-m-Y H:I A') : ''),
                'Final Report' => (!empty($this->data['final_report']) ?  Carbon::Parse($this->data['final_report'])->format('d-m-Y') : "" ),
                'Dropped' => (!empty($this->data['dropped']) ?  Carbon::Parse($this->data['dropped'])->format('d-m-Y') : ''),
                
            ],
        ]);
    }
}
