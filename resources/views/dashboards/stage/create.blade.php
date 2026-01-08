{{-- @extends('dashboards.eva-dd.layouts.evaldd-dash-layout') --}}

@extends(Auth::user()->role == 25 ? 'dashboards.admins.layouts.admin-dash-layout' : 'dashboards.eva-dd.layouts.evaldd-dash-layout')
@section('title','Stage Create')
<link rel="stylesheet" type="text/css" href="{{ asset('css/style.css') }}">
@php
    use Carbon\Carbon;
@endphp
<link href="{{asset('css/jquery-ui.css')}}" rel="Stylesheet" type="text/css" />
@section('content')

<!--begin::Content-->
    <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
            <!--begin::Subheader-->
            <div class="subheader py-2 py-lg-6  subheader-solid " id="kt_subheader">
              <div class=" container-fluid  d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
                <!--begin::Info-->
                <div class="d-flex align-items-center flex-wrap mr-1">
                  <!--begin::Page Heading-->
                  <div class="d-flex align-items-baseline flex-wrap mr-5">
                    <!--begin::Page Title-->
                    <h5 class="text-dark font-weight-bold my-1 mr-5">
                     {{ __('message.update_stages')}}
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
                <div class=" container ">
                  <div class="card card-custom card-transparent">
                    <div class="card-body p-10">
                        @if(session()->has('success'))
                            <div class="alert alert-success alert-dismissible" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close" style="color: white;"><span aria-hidden="true">&times;</span></button>
                                {{ session()->get('success') }}
                            </div>  
                        @endif
                        
                          @if(session()->has('error'))
                            <div class="alert alert-custom alert-notice alert-light-danger fade show" role="alert">
                              <div class="alert-icon"><i class="flaticon-warning"></i></div>
                              <div class="alert-text"> {{ session()->get('error') }}</div>
                              <div class="alert-close">
                                  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                      <span aria-hidden="true"><i class="ki ki-close"></i></span>
                                  </button>
                              </div>
                          </div>
                          @endif

                        <div class="row">
                          <div class="col-xl-12 col-xxl-12">
                            @if($errors->any())
                              @foreach($errors->all() as $key=>$value)
                                <ul>
                                  <li style="text-danger">
                                    {{ $value }}
                                  </li>
                                </ul>
                              @endforeach
                            @endif
                            
                             <form 
                                @if (isset($stages))
                                    action="{{ route('stages.update', $stages->id) }}"
                                @else
                                    action="{{ route('stages.store') }}"
                                @endif
                                method="POST" enctype="multipart/form-data">

                                @if (isset($stages))
                                    @method('PUT')
                                @endif

                                @csrf
                                
                                <div class="col-xl-12">
                                    <div class="form-group">
                                        <label> {{ __('message.department_name')}} * :</label>
                                        <input type="text" id="next_dept_name" value="{{department_name($data->dept_id)}}" name="" readonly class="form-control pattern @error('dept_id') is-invalid @enderror">
                                         <input type="hidden" class="form-control" id="next_dept_id" name="dept_id" value="{{ $data->dept_id }}" />
                                       
                                    </div>
                                </div>
                                <div class="col-xl-12">
                                    <div class="form-group">
                                        <label> {{ __('message.scheme_name')}} * :</label>
                                        <input type="text" id="next_dept_name" value="{{ SchemeName($data->scheme_id)}}" name="" readonly class="form-control pattern @error('dept_id') is-invalid @enderror">
                                         <input type="hidden" class="form-control" id="scheme_id" name="scheme_id" value="{{ $data->scheme_id }}" />
                                         <input type="hidden" class="form-control" id="draft_id" name="draft_id" value="{{ $data->draft_id }}" />

                                    </div>
                                </div>

                                <div class="row" id="text_item_0">
                                    <div class="col-xl-6">
                                        <div class="form-group">
                                            <label> {{ __('message.proposal_date')}}:</label>
                                        </div>
                                    </div>
                                    <div class="col-xl-5">
                                        <div class="form-group">
                                            @php
                                             $formatDate = '';
                                             $proposal_date = proposalDatestage($data->scheme_id)->first();
                                            if(!empty($proposal_date)){
                                                $formatDate = Carbon::Parse($proposal_date)->format('Y-m-d');
                                            }else{
                                                $formatDate = '';
                                            }
                                            @endphp
                                            <input type="text" id="proposal_date" readonly name="proposal_date" class="form-control disbleTxt"  value="{{ $formatDate }}">
                                        </div>
                                    </div>
                                    <div class="col-xl-1 hide_span">
                                            <input type="text"  class="btn show-all-rows btn-info pattern" value="Add" style="width: 55%; height: 50%;margin-top: 7px;">
                                    </div>
                                </div>
                                  
                                <div class="row auto-increment" id="text_item_">
                                    <div class="col-xl-6">
                                        <div class="form-group">
                                            <label>{{ trans('message.chart_labels')[0] }}:</label>
                                        </div>
                                    </div>
                                    <div class="col-xl-2">
                                        <div class="form-group">
                                            <input type="text" id="requisition" value="{{ old('requisition', (isset($stages) && !empty($stages->requisition)) ? Carbon::Parse($stages->requisition)->format('Y-m-d') : "" ) }}" name="requisition"  class="form-control datepicker checkVal disbleTxt pattern">
                                        </div>
                                    </div>
                                    <div class="col-xl-3">
                                        <div class="form-group">
                                            <textarea id="requisition_text" name="requisition_text"  class="pattern form-control checkVal disbleTxt" maxlength="250">{{ (isset($stages) ? $stages->requisition_text : "" ) }}</textarea>
                                        </div>
                                    </div>
                                </div>

                                <div class="row auto-increment" id="text_item_">
                                    <div class="col-xl-6">
                                        <div class="form-group">
                                            <label> {{ __('message.additional_info')}}:</label>
                                        </div>
                                    </div>
                                    <div class="col-xl-2">
                                        <div class="form-group"> 
                                            <input type="text" id="scheme_hod_date" value="{{ old('scheme_hod_date', (isset($stages) && !empty($stages->scheme_hod_date)) ? Carbon::Parse($stages->scheme_hod_date)->format('Y-m-d'): "" ) }}" name="scheme_hod_date" class="form-control datepicker checkVal disbleTxt pattern">
                                        </div>
                                    </div>
                                    <div class="col-xl-3">
                                        <div class="form-group">
                                            <textarea id="scheme_hod_text" name="scheme_hod_text"  class="form-control checkVal disbleTxt pattern" maxlength="250">{{ (isset($stages) ? $stages->scheme_hod_text : "" ) }}</textarea>
                                        </div>
                                    </div>
                                </div>

                                <div class="row auto-increment" id="text_item_">
                                    <div class="col-xl-6">
                                        <div class="form-group">
                                            <label> {{ __('message.study_design')}}:</label>
                                        </div>
                                    </div>
                                    <div class="col-xl-2">
                                        <div class="form-group">
                                            <input type="text" id="study_design_date" value="{{ old('study_design_date', (isset($stages) && !empty($stages->study_design_date)) ? Carbon::Parse($stages->study_design_date)->format('Y-m-d') : "" ) }}" name="study_design_date"  class="form-control datepicker checkVal disbleTxt pattern">
                                        </div>
                                    </div>
                                    <div class="col-xl-3">
                                        <div class="form-group">
                                            <textarea id="study_design_text" name="study_design_text"  class="form-control checkVal pattern disbleTxt" maxlength="250">{{ (isset($stages) ? $stages->study_design_text : "" ) }}</textarea>
                                        </div>
                                    </div>
                                </div>

                                 <div class="row auto-increment" id="text_item_">
                                    <div class="col-xl-6">
                                        <div class="form-group">
                                            <label> {{ __('message.study_design_survey')}}:</label>
                                        </div>
                                    </div>
                                    <div class="col-xl-2">
                                        <div class="form-group">
                                            <input type="text" id="study_design_hod_date" value="{{ old('study_design_hod_date', (isset($stages) && !empty($stages->study_design_hod_date)) ? Carbon::Parse($stages->study_design_hod_date)->format('Y-m-d') : "" ) }}" name="study_design_hod_date"  class="form-control datepicker checkVal disbleTxt pattern">
                                        </div>
                                    </div>
                                    <div class="col-xl-3">
                                        <div class="form-group">
                                            <textarea id="study_design_hod_text" name="study_design_hod_text"  class="form-control checkVal disbleTxt pattern" maxlength="250">{{ (isset($stages) ? $stages->study_design_hod_text : "" ) }}</textarea>
                                        </div>
                                    </div>
                                </div> 

                                <div class="row auto-increment" id="text_item_">
                                    <div class="col-xl-6">
                                        <div class="form-group">
                                            <label> {{ __('message.input_study_design')}} :</label>
                                        </div>
                                    </div>
                                    <div class="col-xl-2">
                                        <div class="form-group">
                                            <input type="text" id="study_design_receive_hod_date" value="{{ old('study_design_receive_hod_date', (isset($stages) && !empty($stages->study_design_receive_hod_date)) ? Carbon::Parse($stages->study_design_receive_hod_date)->format('Y-m-d') : "" ) }}" name="study_design_receive_hod_date"  class="form-control datepicker checkVal disbleTxt">
                                        </div>
                                    </div>
                                    <div class="col-xl-3">
                                        <div class="form-group">
                                            <textarea id="study_design_receive_hod_text" name="study_design_receive_hod_text"  class="form-control checkVal disbleTxt pattern" maxlength="250">{{ (isset($stages) ? $stages->study_design_receive_hod_text : "" ) }}</textarea>
                                        </div>
                                    </div>
                                </div>
                                
                            <div class="row auto-increment" id="text_item_">
                                <div class="col-xl-6">
                                    <div class="form-group">
                                        <label> {{ __('message.pilot_study')}} :</label>
                                    </div>
                                </div>
                                <div class="col-xl-2">
                                    <div class="form-group"> 
                                        <input type="text" id="polot_study_date" value="{{ old('polot_study_date', (isset($stages)  && !empty($stages->polot_study_date))  ? Carbon::Parse($stages->polot_study_date)->format('Y-m-d') : "" ) }}" name="polot_study_date"  class="form-control datepicker checkVal disbleTxt">
                                    </div>
                                </div>
                                <div class="col-xl-3">
                                    <div class="form-group">
                                        <textarea id="polot_study_text" name="polot_study_text"  class="form-control checkVal disbleTxt pattern" maxlength="250">{{ (isset($stages) ? $stages->polot_study_text : "" ) }}</textarea>
                                    </div>
                                </div>
                            </div>
                                            
                            <div class="row auto-increment" id="text_item_">
                                <div class="col-xl-6">
                                    <div class="form-group">
                                        <label> {{ __('message.field_survey_start')}}:</label>
                                    </div>
                                </div>
                                <div class="col-xl-2">
                                    <div class="form-group">
                                        <input type="text" id="field_survey_startdate" value="{{ old('field_survey_startdate', (isset($stages)  && !empty($stages->field_survey_startdate)) ? Carbon::Parse($stages->field_survey_startdate)->format('Y-m-d') : "" ) }}" name="field_survey_startdate"  class="form-control datepicker checkVal disbleTxt">
                                    </div>
                                </div>
                                <div class="col-xl-3">
                                    <div class="form-group">
                                        <textarea id="field_survey_text" name="field_survey_text"  class="pattern form-control checkVal disbleTxt" maxlength="250">{{ (isset($stages) ? $stages->field_survey_text : "" ) }}</textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="row auto-increment" id="text_item_">
                                <div class="col-xl-6">
                                    <div class="form-group">
                                        <label> {{ __('message.field_survey_end')}}:</label>
                                    </div>
                                </div>
                                <div class="col-xl-2">
                                    <div class="form-group">
                                        <input type="text" id="field_survey_enddate" value="{{ old('field_survey_enddate', (isset($stages)  && !empty($stages->field_survey_enddate)) ? Carbon::Parse($stages->field_survey_enddate)->format('Y-m-d') : "" ) }}" name="field_survey_enddate"  class="form-control datepicker checkVal disbleTxt">
                                    </div>
                                </div>
                                <div class="col-xl-3">
                                    <div class="form-group">
                                        <textarea id="field_survey_end_text" name="field_survey_end_text"  class="pattern form-control checkVal disbleTxt" maxlength="250">{{ (isset($stages) ? $stages->field_survey_end_text : "" ) }}</textarea>
                                    </div>
                                </div>
                            </div>


                            <div class="row auto-increment" id="text_item_">
                                <div class="col-xl-6">
                                    <div class="form-group">
                                        <label> {{ __('message.data_clean')}}:</label>
                                    </div>
                                </div>
                                <div class="col-xl-2">
                                    <div class="form-group">
                                        <input type="text" id="data_statistical_startdate" value="{{ old('data_statistical_startdate', (isset($stages)  && !empty($stages->data_statistical_startdate)) ? Carbon::Parse($stages->data_statistical_startdate)->format('Y-m-d') : "" ) }}" name="data_statistical_startdate"  class="pattern form-control datepicker checkVal disbleTxt">
                                    </div>
                                </div>
                                <div class="col-xl-3">
                                    <div class="form-group">
                                        <textarea id="data_statistical_text" name="data_statistical_text"  class="pattern form-control checkVal disbleTxt" maxlength="250">{{ (isset($stages) ? $stages->data_statistical_text : "" ) }}</textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="row auto-increment" id="text_item_">
                                <div class="col-xl-6">
                                    <div class="form-group">
                                        <label> {{ __('message.data_clean_end')}}:</label>
                                    </div>
                                </div>
                                <div class="col-xl-2">
                                    <div class="form-group">
                                        <input type="text" id="data_statistical_enddate" value="{{ old('data_statistical_enddate', (isset($stages)  && !empty($stages->data_statistical_enddate)) ? Carbon::Parse($stages->data_statistical_enddate)->format('Y-m-d') : "" ) }}" name="data_statistical_enddate"  class="form-control datepicker checkVal disbleTxt">
                                    </div>
                                </div>
                                <div class="col-xl-3">
                                    <div class="form-group">
                                        <textarea id="data_statistical_end_text" name="data_statistical_end_text"  class="pattern form-control checkVal disbleTxt" maxlength="250">{{ (isset($stages) ? $stages->data_statistical_end_text : "" ) }}</textarea>
                                    </div>
                                </div>
                            </div>
                            {{-- <div class="row auto-increment" id="text_item_">
                                <div class="col-xl-6">
                                    <div class="form-group">
                                        <label>Data Entry Level :</label>
                                    </div>
                                </div>
                                <div class="col-xl-2">
                                    <div class="form-group">
                                        <input type="text" id="data_entry_level_start" value="{{ old('data_entry_level_start', (isset($stages)  && !empty($stages->data_entry_level_start)) ? Carbon::Parse($stages->data_entry_level_start)->format('Y-m-d') : "" ) }}" name="data_entry_level_start"  class="form-control datepicker checkVal disbleTxt">
                                    </div>
                                </div>
                                <div class="col-xl-3">
                                    <div class="form-group">
                                        <textarea id="data_entry_level_start_text" name="data_entry_level_start_text"  class="form-control checkVal disbleTxt" maxlength="250">{{ (isset($stages) ? $stages->data_entry_level_start_text : "" ) }}</textarea>
                                    </div>
                                </div>
                            </div> --}}
                          

                            <div class="row auto-increment" id="text_item_">
                                <div class="col-xl-6">
                                    <div class="form-group">
                                        <label> {{ __('message.report_start')}} :</label>
                                    </div>
                                </div>
                                <div class="col-xl-2">
                                    <div class="form-group">
                                        <input type="text" id="report_startdate" value="{{ old('report_startdate', (isset($stages) && !empty($stages->report_startdate)) ? Carbon::Parse($stages->report_startdate)->format('Y-m-d') : "" ) }}" name="report_startdate"  class="form-control datepicker checkVal disbleTxt">
                                    </div>
                                </div>
                                <div class="col-xl-3">
                                    <div class="form-group">
                                        <textarea id="report_text" name="report_text"  class="pattern form-control checkVal disbleTxt" maxlength="250">{{ (isset($stages) ? $stages->report_text : "" ) }}</textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="row auto-increment" id="text_item_">
                                <div class="col-xl-6">
                                    <div class="form-group"> 
                                        <label> {{ __('message.report_end')}} :</label>
                                    </div>
                                </div>
                                <div class="col-xl-2">
                                    <div class="form-group"> 
                                        <input type="text" id="report_enddate" value="{{ old('report_enddate', (isset($stages) && !empty($stages->report_enddate)) ? Carbon::Parse($stages->report_enddate)->format('Y-m-d') : "" ) }}" name="report_enddate"  class="form-control datepicker pattern checkVal disbleTxt">
                                    </div>
                                </div>
                                <div class="col-xl-3">
                                    <div class="form-group">
                                        <textarea id="report_end_text" name="report_end_text"  class="form-control checkVal disbleTxt pattern" maxlength="250">{{ (isset($stages) ? $stages->report_end_text : "" ) }}</textarea>
                                    </div>
                                </div>
                            </div>
                            
                             <div class="row auto-increment" id="text_item_">
                                <div class="col-xl-6">
                                    <div class="form-group">
                                        <label> {{ __('message.draft_report')}} :</label>
                                    </div>
                                </div>
                                <div class="col-xl-2">
                                    <div class="form-group">
                                        <input type="text" id="report_sent_hod_date" value="{{ old('report_sent_hod_date', (isset($stages) && !empty($stages->report_sent_hod_date)) ? Carbon::Parse($stages->report_sent_hod_date)->format('Y-m-d') : "" ) }}" name="report_sent_hod_date"  class="form-control datepicker checkVal disbleTxt">
                                    </div>
                                </div>
                                <div class="col-xl-3">
                                    <div class="form-group">
                                        <textarea  id="report_sent_text" name="report_sent_text"  class="form-control checkVal disbleTxt pattern" maxlength="250">{{ (isset($stages) ? $stages->report_sent_text : "" ) }}</textarea>
                                    </div>
                                </div>
                            </div> 
            
                            <div class="row auto-increment" id="text_item_">
                                <div class="col-xl-6">
                                    <div class="form-group">
                                        <label> {{ __('message.input_draft_report')}} :</label>
                                    </div>
                                </div>
                                <div class="col-xl-2">
                                    <div class="form-group">
                                        <input type="text" id="report_draft_hod_date" value="{{ old('report_draft_hod_date', (isset($stages) && !empty($stages->report_draft_hod_date)) ? Carbon::Parse($stages->report_draft_hod_date)->format('Y-m-d') : "" ) }}" name="report_draft_hod_date"  class="form-control datepicker checkVal disbleTxt">
                                    </div>
                                </div>
                                <div class="col-xl-3">
                                    <div class="form-group">
                                        <textarea  id="report_draft_hod_text" name="report_draft_hod_text"  class="form-control checkVal disbleTxt pattern" maxlength="250">{{ isset($stages) ? $stages->report_draft_hod_text : ""  }}</textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="row auto-increment" id="text_item_">
                                <div class="col-xl-6">
                                    <div class="form-group">
                                        <label> {{ __('message.draft_report_send')}} :</label>
                                    </div>
                                </div>
                                <div class="col-xl-2">
                                    <div class="form-group">
                                        <input type="text" id="report_draft_sent_hod_date" value="{{ old('report_draft_sent_hod_date', (isset($stages) && !empty($stages->report_draft_sent_hod_date)) ? Carbon::Parse($stages->report_draft_sent_hod_date)->format('Y-m-d') : "" ) }}" name="report_draft_sent_hod_date"  class="form-control datepicker checkVal disbleTxt">
                                    </div>
                                </div>
                                <div class="col-xl-3">
                                    <div class="form-group">
                                        <textarea  id="report_draft_sent_hod_text"  name="report_draft_sent_hod_text"  class="form-control checkVal disbleTxt pattern" maxlength="250">{{ isset($stages) ? $stages->report_draft_sent_hod_text : ""  }}</textarea>
                                    </div>
                                </div>
                            </div> 
            
                            <div class="row auto-increment" id="text_item_">
                                <div class="col-xl-6">
                                    <div class="form-group">
                                        <label> {{ __('message.date_department')}} :</label>
                                    </div>
                                </div>
                                <div class="col-xl-2">
                                    <div class="form-group">
                                        <input type="text" id="dept_eval_committee_datetime" name="dept_eval_committee_datetime" class="form-control datepicker checkVal disbleTxt" 
                                        value="{{ old('dept_eval_committee_datetime', (isset($stages)  && !empty($stages->dept_eval_committee_datetime)) ? Carbon::Parse($stages->dept_eval_committee_datetime)->format('Y-m-d') : '') }}">                                  
                                    </div>
                                </div>
                                <div class="col-xl-3">
                                    <div class="form-group">
                                        <textarea  id="dept_eval_committee_text"  name="dept_eval_committee_text"  class="form-control checkVal disbleTxt pattern" maxlength="250">{{ isset($stages) ? $stages->dept_eval_committee_text : ""  }}</textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="row auto-increment" id="text_item_">
                                <div class="col-xl-6">
                                    <div class="form-group">
                                        <label> {{ __('message.draft_report_send_eval')}}:</label>
                                    </div>
                                </div>
                                <div class="col-xl-2">
                                    <div class="form-group">
                                        <input type="text" id="draft_sent_eval_committee_date" value="{{ old('draft_sent_eval_committee_date', (isset($stages)  && !empty($stages->draft_sent_eval_committee_date)) ? Carbon::Parse($stages->draft_sent_eval_committee_date)->format('Y-m-d') : "" ) }}" name="draft_sent_eval_committee_date"  class="form-control datepicker checkVal disbleTxt">
                                    </div>
                                </div>
                                <div class="col-xl-3">
                                    <div class="form-group">
                                        <textarea  id="draft_sent_eval_committee_text"  name="draft_sent_eval_committee_text"  class="form-control checkVal disbleTxt pattern" maxlength="250">{{ isset($stages) ? $stages->draft_sent_eval_committee_text : ""  }}</textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="row auto-increment" id="text_item_">
                                <div class="col-xl-6">
                                    <div class="form-group">
                                        <label> {{ __('message.committee')}} :</label>
                                    </div>
                                </div>
                                <div class="col-xl-2">
                                    <div class="form-group">
                                        <input type="datetime-local" id="eval_cor_date" name="eval_cor_date" class="form-control checkVal disbleTxt" 
                                        value="{{ old('eval_cor_date', (isset($stages) && !empty($stages->eval_cor_date)) ? Carbon::Parse($stages->eval_cor_date)->format('Y-m-d\TH:i') : '') }}">
                                    </div>
                                </div>
                                <div class="col-xl-3">
                                    <div class="form-group">
                                        <textarea  id="eval_cor_text"  name="eval_cor_text"  class="pattern form-control checkVal disbleTxt" maxlength="250">{{ isset($stages) ? $stages->eval_cor_text : ""  }}</textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="row auto-increment" id="text_item_">
                                <div class="col-xl-6">
                                    <div class="form-group">
                                        <label> {{ __('message.final_report')}} :</label>
                                    </div>
                                </div>
                                <div class="col-xl-2">
                                    <div class="form-group">
                                        <input type="text" id="final_report" value="{{ old('final_report', (isset($stages) && !empty($stages->final_report))? Carbon::Parse($stages->final_report)->format('Y-m-d') : "" ) }}" name="final_report"  class="form-control datepicker checkVal disbleTxt">
                                    </div>
                                </div>
                                <div class="col-xl-3">
                                    <div class="form-group">
                                        <input type="file" name="document" id="document" class="document checkVal disbleTxt" maxlength="250" accept=".pdf,.xlsx,.docx">                                        
                                    </div>
                                </div>
                            </div>

                            <div class="row auto-increment" id="text_item_">
                                <div class="col-xl-6">
                                    <div class="form-group">
                                        <label> {{ __('message.dropped')}} :</label>
                                    </div>
                                </div>
                                <div class="col-xl-2">
                                    <div class="form-group">
                                        <input type="text" id="dropped" value="{{ old('dropped', (isset($stages) && !empty($stages->dropped))? Carbon::Parse($stages->dropped)->format('Y-m-d') : "" ) }}" name="dropped"  class="form-control datepicker checkVal disbleTxt">
                                    </div>
                                </div>
                                <div class="col-xl-3">
                                    <div class="form-group">
                                        <textarea  id="dropped_text"  name="dropped_text"  class="pattern form-control checkVal disbleTxt" maxlength="250">{{ isset($stages) ? $stages->dropped_text : ""  }}</textarea>
                                    </div>
                                </div>
                            </div>
                                           
                            <div class="submitBtn">
                                <input type="submit" class="btn btn-info" value=" {{ __('message.submit')}}" /> 
                            </div>
                            </form>
                                <a href="{{route('evaldd.dashboard')}}" class="btn btn-info"> {{ __('message.back')}}   {{ __('message.dashboard')}}</a>
                                @if(isset($stages))
                                    <a href="{{route('stages.downalod',$stages->id)}}" class="btn btn-info"> {{ __('message.stage_report_download')}}</a>
                                @endif
                          </div>
                        </div>
                      </div>
                  </div>
                
                </div>
                <!--end::Container-->
              </div>
              <!--end::Entry-->
            </div>
            <!--end::Content--> 
         
<!-- /.modal-dialog -->
{{-- <div class="modal fade" id="field_survey" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content"> <!-- style="width: 290%;margin-left: -90%;"> -->
            <div class="modal-header">
                <h4 class="modal-title">Field Survey Form</h4>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="container-fluid" >
                    <div class="row">
                        <div class="col-xl-6">
                            <div class="form-group">
                                <label>Scheme Name</label>
                            </div>
                        </div>
                        <div class="col-xl-6">
                            <div class="form-group">
                            {{ $data->scheme_name}}
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xl-6">
                            <div class="form-group">
                                <label>Department Name</label>
                            </div>
                        </div>
                        <div class="col-xl-6">
                            <div class="form-group">
                            {{department_name($data->dept_id)}}
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xl-6">
                            <div class="form-group">
                                <label>Implementing office / HOD Name</label>
                            </div>
                        </div>
                        <div class="col-xl-6">
                            <div class="form-group">
                            {{$data->hod_name}}
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xl-4">
                            <div class="form-group">
                                <label>Nodal Name</label>
                                {{$data->nodal_officer_name}}
                            </div>
                        </div>
                        <div class="col-xl-4">
                            <div class="form-group">
                            <label>Designation </label>
                            {{$data->nodal_officer_designation}}
                            </div>
                        </div>
                        
                    </div>
                    
                        <h3>SURVEY SCHEDULES</h3>
                        <div class="row">
                            <div class="col-md-1"></div>
                            <div class="col-md-10">
                                <div class="form-group">
                                    <form name="schemeData" id="schemeData">

                                            <input type="hidden" class="form-control" id="" name="dept_id" value="{{ $data->dept_id }}" />
                                            <input type="hidden" class="form-control" id="" name="scheme_id" value="{{ $data->scheme_id }}" />
                                            <input type="hidden" class="form-control" id="" name="draft_id" value="{{ $data->draft_id }}" />

                                            <table class="table table-bordered table-hover" id="dynamic_field">
                                                <tr>
                                                    <td><input type="text" name="scheme_name[]" placeholder="Enter your Name" class="form-control" /></td>
                                                    <td><input type="number" name="total_scheme[]" placeholder="Total No. of scheme" class="form-control"/></td>
                                                    <td><button type="button" name="add" id="add" class="btn btn-info">Add More</button></td>  
                                                </tr>
                                            </table>
                                            <input type="submit" class="btn btn-success" name="submit" id="submit" value="Submit">
                                    </form>
                                </div>
                            </div>
                             <div class="col-md-1"></div>
                        </div>
                    
                </div>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
</div> --}}
<!-- /.modal-dialog -->
              
<script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
{{-- <script type="text/javascript" src="{{asset('js/jquery-1.11.3.min.js')}}"></script> --}}
<script src="{{asset('bootstrap/js/bootstrap.min.js')}}"></script>
<script src="{{asset('js/jquery-ui.js')}}"></script>
<script>
    var login_user = false;
    var admin = "{{Auth()->user()->role}}";
    if(admin == 25){
        login_user = true;
    }
  
    jQuery( document ).ready(function( $ ) {

        $( ".datepicker" ).datepicker({
                format: 'dd/mm/yyyy', 
                changeMonth: true,
                changeYear: true,
                beforeShow: function (input, inst) {
                    $(input).attr("placeholder", "dd/mm/yyyy");
                }
        });
   
        
        var i = 1;
        var length;
        var addamount = 10; 

        $("#add").click(function(){
            addamount += 10;
            i++;
            $('#dynamic_field').append('<tr id="row'+i+'"><td><input type="text" name="scheme_name[]" placeholder="Enter your Scheme Name" class="form-control name_list"/></td><td><input type="number" name="total_scheme[]" placeholder="Total No. of scheme" class="form-control name_email"/></td><td><button type="button" name="remove" id="'+i+'" class="btn btn-danger btn_remove">X</button></td></tr>');          
        });

        $(document).on('click', '.btn_remove', function(){  
            addamount -= 10;
            var button_id = $(this).attr("id");     
            $('#row'+button_id+'').remove();  
        });
	
       
        var currentId = 1;
        var lastNonBlankElement;

        $('.auto-increment').each(function (index) {
            var id = $(this).closest('.row').attr('id');
            var blankVal = $('#' + id).find('.disbleTxt').val();
          
            if (blankVal !== "") {
                lastNonBlankElement = this; // Update the last non-blank element
                $(this).show();
                if(login_user == true){
                    $('#' + id).find('.disbleTxt').prop("disabled", false);
                }else{
                    $('#' + id).find('.disbleTxt').prop("disabled", true);

                }
            } else {
                $(this).hide();
                $('#' + id).find('.disbleTxt').prop("disabled", false);
            }
            
            // Assuming currentId is defined elsewhere in your code
            $(this).attr('id', 'text_item_' + (currentId + index));
        });
            
        if (lastNonBlankElement) {
            $('.hide_span').hide();
            $(lastNonBlankElement).append('<div class="col-xl-1 hide_span"><input type="text"  class="btn show-all-rows btn-info" value="Add" style="width: 55%; height: 46%;margin-top: 12px;"></div>');
        } else {
            console.log('Please enter some value.')
        }

      
        $(document).on('click','.show-all-rows',function () { 
            var id = $(this).closest('.row').attr('id');
            var checkVal = $('#' + id).find('.disbleTxt').val();
            var today = new Date(checkVal);
            var tomorrow = new Date(checkVal);
           
            if(login_user == true){
                tomorrow.setDate(today.getDate());
            }else{
                tomorrow.setDate(today.getDate() + 1);
            }
            // Destroy any existing datepicker before reinitializing
            $(".datepicker").datepicker("destroy");

            $(".datepicker").datepicker({
                format: 'dd/mm/yyyy',
                minDate: tomorrow,
                changeMonth: true,
                changeYear: true,
                beforeShow: function (input, inst) {
                    $(input).attr("placeholder", "dd/mm/yyyy");
                }
            });

            // Hide the plus icon for the clicked element
            $(this).hide();

            if (checkVal !== "") {
                let text = $(this).closest('.row').attr('id');
                const myArray = String(text).split("_");
                let word = parseInt(myArray[2]) + 1;
                $('#text_item_' + word).show();

                // Append the new plus icon
                $('#text_item_' + word).last().append('<div class="col-xl-1 hide_span"><input type="text"  class="btn show-all-rows btn-info" value="Add" style="width: 55%; height: 46%;margin-top: 12px;"></div>');
            }else{
                $(this).show();
            }
        });

       
        
        // $('#field_survey_startdate').on('click',function(){
        //     if($(this).val() !== ""){
        //         $('#field_survey').modal('show');
        //         $('#field_survey').css("opacity", "1");
        //         $('#field_survey').css("background", "rgba(0,0,0,0.8)");
        //         $('#field_survey').find('.modal-content').css("margin-top", "15%");
        //     }
            
        // });

        $('#dynamic_field tbody tr').each(function () {
                var schemeName = $(this).find('input[name="scheme_name[]"]').val();
                var totalScheme = $(this).find('input[name="total_scheme[]"]').val();

                if (schemeName !== "" && totalScheme !== "") {
                    $(this).find('input[name="scheme_name[]"]').prop("disabled", true);
                    $(this).find('input[name="total_scheme[]"]').prop("disabled", true);
                } else {
                    $(this).find('input[name="scheme_name[]"]').prop("disabled", false);
                    $(this).find('input[name="total_scheme[]"]').prop("disabled", false);
                }
            });
    });

   
</script>
                        
@endsection
