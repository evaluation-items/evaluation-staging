@extends($layout)
@section('title','Stages Detail')
@php
  use Carbon\Carbon;
@endphp
<style>
  .borderless {
    border:0px !important;
  }
  #convergence_table tbody tr th {
    width: 30% !important;
  }
  #convergence_table tbody tr td {
    width: 70% !important;
  }
  .small-row th {
    font-size: 12px;
    font-weight: normal;
  }
  .small-row td {
    font-size: 12px;
  }
  .containers {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .deptName, .complted,.schemeName {
        flex: 1;
        text-align: left;
    }

    .complted {
        text-align: center;
    }

    .schemeName {
        text-align: right;
    }
</style>
@section('content')
@php
  use Illuminate\Support\Facades\Crypt;
@endphp
<!--begin::Content-->
<div class="content  d-flex flex-column flex-column-fluid" id="kt_content">
  <!--begin::Subheader-->
  <div class="subheader py-2 py-lg-6  subheader-solid " id="kt_subheader">
    <div class=" container-fluid  d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
    <!--begin::Info-->
    <div class="d-flex align-items-center flex-wrap mr-1">
      <!--begin::Page Heading-->
      <div class="d-flex align-items-baseline flex-wrap mr-5">
        <!--begin::Page Title-->
        <h5 class="text-dark font-weight-bold my-1 mr-5">
          Stages Detail
        </h5>
        <!--end::Page Title-->                  
      </div>
      <!--end::Page Heading-->
    </div>
    <!--end::Info-->
    </div>
  </div>
  <!--end::Subheader-->
  <!--begin::Entry-->
  <div class="d-flex flex-column-fluid">
  <!--begin::Container-->
    <div class="container">
      {{-- <div class="card card-custom card-transparent">
          <div class="card-body p-0"> --}}
          <!--begin: Wizard-->
         
          <div class="containers">
            <div class="deptName">
              <label><strong>{{ __('message.department_name')}} :</strong></label>
              <span>{{ department_name($stages->dept_id)}}</span>
            </div>
            @if(!is_null($stages_completed))
            <div class="complted">
              <label><strong>{{ __('message.stages')}}</strong></label>
              <span>{{(!empty($stages->final_report) ?  Carbon::Parse($stages->final_report)->format('d-m-Y') : "" )}}</span >
            </div>
            @endif
            <div class="schemeName">
              <label><strong>{{ __('message.scheme_name')}} :</strong></label>
              <span>{{ SchemeName($stages->scheme_id)}}</span>
            </div>
          </div>
         <div class="wizard wizard-4" id="kt_wizard_v4" data-wizard-state="step-first" data-wizard-clickable="true">
            <div class="card card-custom card-shadowless rounded-top-0">
              <div class="card-body p-10">
                <div class="row table-responsive">
                  <table width="100%" class="table table-bordered table-hover table-stripped dataTable">
                  <tr style="background-color: #c8cbcd;">
                      <th width="50%" class="text-center"><strong>{{ __('message.stages')}}</strong></th>
                      <td width="25%" class="text-center"><strong>{{ __('message.stages')}} {{ __('message.date')}}</strong></td>
                      <td width="25%" class="text-center"><strong>{{ __('message.remarks')}}</strong></td>
                    </tr>
                    <tr>
                      <th width="50%">{{ __('message.proposal_date')}}</th>
                      <td width="25%">
                        @php
                           $proposal_date = proposalDatestage($stages->scheme_id)->first();
                        @endphp
                          {{ (!empty($proposal_date) ? Carbon::Parse($proposal_date)->format('d-m-Y') : '') }}
                      </td>
                      <td width="25%"></td>
                    </tr>
					 <tr>
                      <th width="50%">{{ __('message.requistion_sent_hod')}}</th>
                      <td width="25%">
                          {{ ((!empty($stages->requistion_sent_hod)) ? Carbon::Parse($stages->requistion_sent_hod)->format('d-m-Y') : "") }}
                      </td>
                      <td width="25%">
                        {{ ((!empty($stages->requistion_sent_hod_text)) ? $stages->requistion_sent_hod_text : "") }}
                      </td>
                    </tr>
                    <tr>
                      <th width="50%">{{ trans('message.requistion_received_date') }}</th>
                      <td width="25%">
                          {{ ((!empty($stages->requisition)) ? Carbon::Parse($stages->requisition)->format('d-m-Y') : "") }}
                      </td>
                      <td width="25%">
                        {{ ((!empty($stages->requisition_text)) ? $stages->requisition_text : "") }}
                      </td>
                    </tr>
                    <tr>
                      <th width="50%">{{ __('message.additional_info')}}</th>
                      <td width="25%">
                          {{ ((!empty($stages->scheme_hod_date)) ? Carbon::Parse($stages->scheme_hod_date)->format('d-m-Y') : "") }}
                      </td>
                      <td width="25%">
                        {{ ((!empty($stages->scheme_hod_text)) ? $stages->scheme_hod_text : "") }}
                      </td>
                    </tr>
					 <tr>
                      <th width="50%">{{ __('message.information_received_from_io')}}</th>
                      <td width="25%">
                          {{ ((!empty($stages->information_received_from_io)) ? Carbon::Parse($stages->information_received_from_io)->format('d-m-Y') : "") }}
                      </td>
                      <td width="25%">
                        {{ ((!empty($stages->information_received_from_io_text)) ? $stages->information_received_from_io_text : "") }}
                      </td>
                    </tr>
                  
                    <tr>
                      <th width="50%">{{ __('message.study_design')}}</th>
                      <td width="25%">
                        {{ (!empty($stages->study_design_date) ? Carbon::Parse($stages->study_design_date)->format('d-m-Y') : "" ) }}
                      </td>
                      <td width="25%">
                        {{ ((!empty($stages->study_design_text)) ? $stages->study_design_text : "") }}
                      </td>
                    </tr>
					
					<tr>
                      <th width="50%">{{ __('message.study_design_sent_concern_department')}}</th>
                      <td width="25%">
                        {{ (!empty($stages->study_entrusted) ? Carbon::Parse($stages->study_entrusted)->format('d-m-Y') : "" ) }}
                      </td>
                      <td width="25%">
                        {{ ((!empty($stages->study_entrusted_text)) ? $stages->study_entrusted_text : "") }}
                      </td>
                    </tr>

                    <tr>
                      <th width="50%">{{ __('message.study_design_survey')}}</th>
                      <td width="25%">
                        {{ (!empty($stages->study_design_hod_date) ? Carbon::Parse($stages->study_design_hod_date)->format('d-m-Y') : "" ) }}
                      </td>
                      <td width="25%">
                        {{ ((!empty($stages->study_design_hod_text)) ? $stages->study_design_hod_text : "") }}
                      </td>
                    </tr> 

                    <tr>
                      <th width="50%">{{ __('message.input_study_design')}}</th>
                      <td width="25%">
                        {{ (!empty($stages->study_design_receive_hod_date) ? Carbon::Parse($stages->study_design_receive_hod_date)->format('d-m-Y') : "" ) }}
                      </td>
                      <td width="25%">
                        {{ ((!empty($stages->study_design_receive_hod_text)) ? $stages->study_design_receive_hod_text : "") }}
                      </td>
                    </tr>
                    <tr>
                      <th width="50%"> {{ __('message.pilot_study')}}</th>
                      <td width="25%">
                        {{ (!empty($stages->polot_study_date) ? Carbon::Parse($stages->polot_study_date)->format('d-m-Y') : "" ) }}
                      </td>
                      <td width="25%">
                        {{ ((!empty($stages->polot_study_text)) ? $stages->polot_study_text : "") }}
                      </td>
                    </tr>
                    <tr>
                      <th width="50%">{{ __('message.field_survey_start')}}</th>
                      <td width="25%">
                        {{(!empty($stages->field_survey_startdate) ? Carbon::Parse($stages->field_survey_startdate)->format('d-m-Y') : "" ) }} 
                      </td>
                      <td width="25%">
                        {{ ((!empty($stages->field_survey_text)) ? $stages->field_survey_text : "") }}
                      </td>
                    </tr>
                     <tr>
                        <th width="50%">{{ __('message.field_survey_end')}}</th>
                        <td width="25%">
                          {{ (!empty($stages->field_survey_enddate) ? Carbon::Parse($stages->field_survey_enddate)->format('d-m-Y') : "" ) }} 
                        </td>
                        <td width="25%">
                          {{ ((!empty($stages->field_survey_end_text)) ? $stages->field_survey_end_text : "") }}
                        </td>
                    </tr>
                    <tr>
                      <th width="50%">{{ __('message.data_clean')}}</th>
                      <td width="25%">
                        {{ (!empty($stages->data_statistical_startdate) ? Carbon::Parse($stages->data_statistical_startdate)->format('d-m-Y') : "" ) }}
                      </td>
                      <td width="25%">
                        {{ ((!empty($stages->data_statistical_text)) ? $stages->data_statistical_text : "") }}
                      </td>
                    </tr>

                    <tr>
                      <th width="50%">{{ __('message.data_clean_end')}}</th>
                      <td width="25%">
                        {{ (!empty($stages->data_statistical_enddate) ? Carbon::Parse($stages->data_statistical_enddate)->format('d-m-Y') : "" ) }}
                      </td>
                      <td width="25%">
                        {{ ((!empty($stages->data_statistical_end_text)) ? $stages->data_statistical_end_text : "") }}
                      </td>
                    </tr>

                    <tr>
                      <th width="50%">{{ __('message.report_start')}}</th>
                      <td width="25%">
                        {{ (!empty($stages->report_startdate) ? Carbon::Parse($stages->report_startdate)->format('d-m-Y') : "" ) }}
                      </td>
                      <td width="25%">
                        {{ ((!empty($stages->report_text)) ? $stages->report_text : "") }}
                      </td>
                    </tr>
                    <tr>
                      <th width="50%">{{ __('message.report_end')}}</th>
                      <td width="25%">{{ (!empty($stages->report_enddate) ? Carbon::Parse($stages->report_enddate)->format('d-m-Y') : "" ) }}</td>
                      <td width="25%">
                        {{ ((!empty($stages->report_end_text)) ? $stages->report_end_text : "") }}
                      </td>
                    </tr>
                   <tr>
                      <th width="50%">{{ __('message.draft_report')}}</th>
                      <td width="25%">{{ (!empty($stages->report_sent_hod_date) ? Carbon::Parse($stages->report_sent_hod_date)->format('d-m-Y') : "" )  }}</td>
                      <td width="25%">
                        {{ ((!empty($stages->report_sent_text)) ? $stages->report_sent_text : "") }}
                      </td>
                    </tr>

                    <tr>
                      <th width="50%">{{ __('message.input_draft_report')}}</th>
                      <td width="25%">
                        {{ (!empty($stages->report_draft_hod_date) ? Carbon::Parse($stages->report_draft_hod_date)->format('d-m-Y') : "" ) }}
                      </td>
                      <td width="25%">{{!empty($stages->report_draft_hod_text) ? $stages->report_draft_hod_text : ""}}</td>
                    </tr>

                    <tr>
                        <th width="50%">{{ __('message.draft_report_send')}}</th>
                        <td width="25%"> {{ (!empty($stages->report_draft_sent_hod_date) ? Carbon::Parse($stages->report_draft_sent_hod_date)->format('d-m-Y') : "" ) }}</td>
                        <td width="25%">{{!empty($stages->report_draft_sent_hod_text) ? $stages->report_draft_sent_hod_text : ""}}</td>
                    </tr>

                    <tr>
                      <th width="50%">{{ __('message.date_department')}}</th>
                      <td width="25%">{{(!empty($stages->dept_eval_committee_datetime) ? Carbon::Parse($stages->dept_eval_committee_datetime)->format('d-m-Y') : '')}}</td>
                      <td width="25%">{{!empty($stages->dept_eval_committee_text) ? $stages->dept_eval_committee_text : ""}}</td>
                    </tr>
					
					<tr>
                      <th width="50%">{{ __('message.minutes_meeting_dec')}}</th>
                      <td width="25%">{{(!empty($stages->minutes_meeting_dec) ? Carbon::Parse($stages->minutes_meeting_dec)->format('d-m-Y H:i A') : '')}}</td>
                      <td width="25%">{{!empty($stages->minutes_meeting_dec_text) ? $stages->minutes_meeting_dec_text : ""}}</td>
                    </tr>
					
                     <tr>
                        <th width="50%">{{ __('message.draft_report_send_eval')}}</th>
                        <td width="25%">{{(!empty($stages->draft_sent_eval_committee_date) ? Carbon::Parse($stages->draft_sent_eval_committee_date)->format('d-m-Y')  : '')}}</td>
                        <td width="25%">{{!empty($stages->draft_sent_eval_committee_text) ? $stages->draft_sent_eval_committee_text : ""}}</td>
                     </tr>
					 <tr>
                        <th width="50%">{{ __('message.minutes_meeting_eval')}}</th>
                        <td width="25%">{{(!empty($stages->minutes_meeting_eval) ? Carbon::Parse($stages->minutes_meeting_eval)->format('d-m-Y')  : '')}}</td>
                        <td width="25%">{{!empty($stages->minutes_meeting_eval_text) ? $stages->minutes_meeting_eval_text : ""}}</td>
                     </tr>
                    <tr>
                        <th width="50%">{{ __('message.committee')}}</th>
                        <td width="25%">{{(!empty($stages->eval_cor_date) ?  Carbon::Parse($stages->eval_cor_date)->format('d-m-Y H:I A') : '')}}</td>
                        <td width="25%">{{!empty($stages->eval_cor_text) ? $stages->eval_cor_text : ""}}</td>
                    </tr>
                    <tr>
                      <th width="50%"> {{ __('message.final_report')}}</th>
                      <td width="25%">{{(!empty($stages->final_report) ?  Carbon::Parse($stages->final_report)->format('d-m-Y') : "" )}}</td>
                      <td width="25%">
                        @if(!empty($stages->document))  
                           <a href="{{ route('stages.get_the_file',[Crypt::encrypt($stages->scheme_id),$stages->document]) }}" target="_blank" title="{{ $stages->document }}">  <i class="fas fa-file-pdf fa-2x" style="color:red;"></i></a>
                        @else
                        No Report Found
                        @endif
                      </td>
                    </tr>
                    <tr>
                      <th width="50%">{{ __('message.dropped')}}</th>
                      <td width="25%">{{(!empty($stages->dropped) ?  Carbon::Parse($stages->dropped)->format('d-m-Y') : '')}}</td>
                      <td width="25%">{{!empty($stages->dropped_text) ? $stages->dropped_text : ""}}</td>
                    </tr>
                  </table>
                </div>
              </div>
            </div>
          </div>
    </div>
  </div>
</div>
@endsection
{{-- <script src="{{asset('js/3.2.1.jquery.min.js')}}"></script> --}}
<script src="{{asset('js/jquery.min.js')}}"></script>
<script type="text/javascript">
  $(document).ready(function(){
    var ktcontent = $("#kt_content").height();
  });
 
</script>
