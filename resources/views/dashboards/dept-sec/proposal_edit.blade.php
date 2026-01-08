@extends('dashboards.dept-sec.layouts.deptsec-dash-layout')
@section('title','Prposal Edit')
<style>
  .borderless {
    border:0px !important;
  }
</style>
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
                      Proposal Evaluation Request
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
                    <div class="card-body p-0">
                      <!--begin: Wizard-->
                      <div class="wizard wizard-4" id="kt_wizard_v4" data-wizard-state="step-first" data-wizard-clickable="true">
                        <div class="card card-custom card-shadowless rounded-top-0">
                          <div class="card-body p-10">
                            @if($message=Session::get('success'))
                            <div class="alert alert-custom alert-notice alert-light-success fade show" role="alert">
                                <div class="alert-icon"><i class="flaticon-like"></i></div>
                                <div class="alert-text">{{ $message }}</div>
                                <div class="alert-close">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true"><i class="ki ki-close"></i></span>
                                    </button>
                                </div>
                              </div>
                              @endif
                              @if($message=Session::get('error'))
                                <div class="alert alert-custom alert-notice alert-light-danger fade show" role="alert">
                                  <div class="alert-icon"><i class="flaticon-warning"></i></div>
                                  <div class="alert-text">{{ $message }}</div>
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
                                <!--begin: Wizard Form-->
                                  <!-- <form class="form mt-0 mt-lg-10 form_scheme_to_submit" id="kt_form" method="POST" action="{{-- route('schemes.store') --}}" enctype="multipart/form-data"> -->
                                  @csrf
                                  <!--begin: Wizard Step 1-->
                                  <div class="pb-5" data-wizard-type="step-content" data-wizard-state="current">
                                    <!--begin::Input-->
                                    @if($proposal->isNotEmpty())
                                    @foreach($proposal as $key => $val)
                                    <div class="first_slide otherslides" style="display:block">
                                        <input type="hidden" name="draft_id" id="next_draft_id" value="{{ $val->draft_id }}">
                                        <input type="hidden" name="scheme_id" id="next_scheme_id" value="{{ $scheme_id }}">
                                    <div class="row">
                                        <div class="col-xl-12">
                                            <div class="form-group">
                                                <label> Department Name * :</label>
                                                <input type="text" id="next_dept_name" value="{{$dept[0]->dept_name}}" name="dept_name" readonly class="form-control pattern @error('dept_id') is-invalid @enderror">
                                                <input type="hidden" class="form-control" id="next_dept_id" name="dept_id" value="{{ $dept[0]->dept_id }}" />
                                                @error('dept_id')
                                                    <div class="alert alert-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <!--end::Input-->
                                    <div class="row">
                                      <div class="col-xl-12">
                                          <div class="form-group">
                                              <label> Convener Name * :</label>
                                              <input type="text" name="convener_name" class="form-control pattern @error('convener_name') is-invalid @enderror" maxlength="100" id="con_id" value="{{ $val->convener_name }}">
                                                @error('convener_name')
                                                    <div class="text-danger">* {{ $message }}</div>
                                                @enderror

                                          </div>
                                      </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-xl-12">
                                        <div class="form-group con">
                                            
                                           
                                        </div>
                                    </div>
                                </div>
                                    <!--begin::Input-->
                                    <div class="row">
                                      <div class="col-xl-12">
                                          <div class="form-group">
                                              <label> Scheme Name * :</label>
                                              <input type="text" id="form_scheme_name" class="form-control pattern @error('scheme_name') is-invalid @enderror" name="scheme_name" value="{{ $val->scheme_name }}" />
                                              @error('scheme_name')
                                                <div class="text-danger">* {{ $message }}</div>
                                              @enderror
                                          </div>
                                      </div>
                                    </div>
                                        <div class="row">
                                            <div class="col-xl-12">
                                                <div class="form-group">
                                                    <label> Reference Year for which the evaluation study to be done * :</label>
                                                <div class="row">
                                                    <div class="col-xl-2">
                                                        <select class="form-control" id="next_reference_year" name="reference_year">
                                                            <option value="">Select Year</option>
                                                            @foreach($financial_years as $fy)
                                                                <option value="{{ $fy }}" @if($fy == $val->reference_year) selected @endif>{{ $fy }}</option>
                                                            @endforeach
                                                        </select>
                                                        @error('reference_year')
                                                            <div class="text-danger">* {{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                    <div class="col-xl-1" style="text-align:center;font-size: 20px;">To</div>
                                                    <div class="col-xl-2">
                                                        <select class="form-control" id="next_reference_year2" name="reference_year2">
                                                            <option value="">Select Year</option>
                                                            @foreach($financial_years as $fy)
                                                                <option value="{{ $fy }}" @if($fy == $val->reference_year2) selected @endif>{{ $fy }}</option>
                                                            @endforeach
                                                        </select>
                                                        @error('reference_year2')
                                                            <div class="text-danger">* {{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                    <div class="col-xl-7"></div>
                                                </div>
                                                </div>
                                            </div>
                                        </div>
                                    <!-- </div> -->
                                        </div>
                                        @endforeach
                                        @endif
                                        <!-- first slide -->
                                    <div class="second_slide otherslides" style="display:none;">
                                    <div class="row">  
                                        <div class="col-xl-12">
                                            <div class="form-group major_objective_parent_div">
                                                <label> Major Objective:</label><br>
                                                @php $major_bene = json_decode($val->major_objective) @endphp
                                                @foreach($major_bene as $kbene => $vbene)
                                                <div class="room_fields_{{$kbene}}">
                                                    <!-- <label>Objective 1: </label> -->
                                                    <input id="next_major_objective" class="form-control next_major_objectives" type="text" name="major_objective[{{$kbene}}][major_objective]" value="{{ $vbene->major_objective }}"/>
                                                    <br>
                                                </div>
                                                @endforeach
                                                <button type="button" class="btn btn-primary" id="btn_add_objective">
                                                    <!--begin::Svg Icon | path: icons/duotune/arrows/arr075.svg-->
                                                    <span class="svg-icon svg-icon-2">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                        <rect opacity="0.5" x="11.364" y="20.364" width="16" height="2" rx="1" transform="rotate(-90 11.364 20.364)" fill="white"></rect>
                                                        <rect x="4.36396" y="11.364" width="16" height="2" rx="1" fill="white"></rect>
                                                        </svg>
                                                    </span>
                                                Add Objective</button>
                                            </div>
                                        </div>
                                        <!-- Modal Start -->
<!--                                         <div class="modal fade" id="add-objective" tabindex="-1" role="dialog" aria-labelledby="exampleModalSizeXl" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                                            <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Nodal Details</h5>
                                                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                                <i aria-hidden="true" class="ki ki-close"></i>
                                                </button>
                                            </div>
                                            <div class="modal-body">

                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-light-primary font-weight-bold" data-bs-dismiss="modal">Close</button> 
                                            </div>
                                            </div>
                                        </div>
                                        </div> -->
                                        <!-- end::Modal-->
                                    </div>
                                    </div>
                                    <!-- secnod slide close -->
                                    <div class="third_slide otherslides" style="display:none;">
                                    <div class="row">  
                                        <div class="col-xl-12">
                                        <div class="form-group major_indicator_parent_div">
                                            <label>Scheme Monitoring Indicators:</label><br>
                                            @php $major_indi = json_decode($val->major_indicator) @endphp
                                            @foreach($major_indi as $kindi => $vindi)
                                            <div class="indicator_fields_{{$kindi}}">
                                                <!-- <label>Indicator: 1 </label> -->
                                                <input id="next_major_indicator" class="form-control next_major_indicators pattern" type="text" name="major_indicator[{{$kindi}}][major_indicator]" value="{{ $vindi->major_indicator }}"/>
                                                <br>
                                            </div>
                                            @endforeach
                                        </div>
                                            <button type="button" class="btn btn-primary" id="btn_add_indicator">
                                                <span class="svg-icon svg-icon-2">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                    <rect opacity="0.5" x="11.364" y="20.364" width="16" height="2" rx="1" transform="rotate(-90 11.364 20.364)" fill="white"></rect>
                                                    <rect x="4.36396" y="11.364" width="16" height="2" rx="1" fill="white"></rect>
                                                    </svg>
                                                </span>
                                            Add Indicator</button>
                                        </div>
                                    </div>
                                    </div>

                                  </div>
                                  <!--end: Wizard Step 1-->
                                  <div class="fourth_slide otherslides" style="display:none;">
                                  <!--begin: Wizard Step 2-->
                                  <div class="pb-0" data-wizard-type="step-content">
                                    
                                    <div class="row">
                                      <div class="col-xl-6">
                                        <div class="form-group">
                                          <label> Implementation Office Name * :</label>
                                          {{--
<!--                                           <select name="im_id" class="form-control @error('im_id') is-invalid @enderror" id="im_id">
                                            <option value="">Select</option>
                                            @foreach($implementations as $implementation)
                                                <option value="{{ $implementation->id }}" @if($val->im_id == $implementation->id) selected @endif>{{ $implementation->name }}</option>
                                            @endforeach
                                            </select> -->
                                            --}}
                                            <input type="text" name="implementing_office" class="form-control" id="implementing_office" value="{{ $dept[0]->dept_name }}" readonly>
                                            @error('implementing_office')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                      </div>
                                      <div class="col-xl-6">
                                        <div class="form-group">
                                          <label>Nodal Officer Name * :</label>
<!--                                             <select name="nodal_id" class="form-control" id="nodal_id">
                                              <option value="">Select</option>
                                          </select> -->
                                          <input type="text" name="nodal_officer_name" class="form-control" maxlength="100" id="nodal_id" value="{{ $val->nodal_officer_name }}">
                                        </div>
                                      </div>
                                    </div>
                                    <div class="row">
                                      <div class="col-xl-12">
                                        <div class="form-group">
                                          <label> Financial Adviser Name* :</label>
                                          {{--
                                          <select name="adviser_id" class="form-control form-control" id="adviser_id">
                                            <option value="">Select</option>
                                            @foreach ($advisers as $adviser)
                                              <option value="{{ $adviser->adviser_id }}">
                                              {{ $adviser->adviser_name }}</option>
                                            @endforeach
                                            </select>
                                            --}}
                                            <input type="text" name="financial_adviser" class="form-control" id="adviser_id" maxlength="100" value="{{$val->financial_adviser_name}}">
                                        </div>
                                      </div>
                                    </div>
                                    <div class="row" id="the_ratios">
                                      <div class="col-xl-6">
                                        <div class="form-group">
                                          <label>Fund Flow (in %) :</label><br>
                                          <div class="row">
                                              <div class="col-xl-6">
                                                  <label>State Govt. (%)</label>
                                                  <input type="text" name="state_ratio" class="form-control numberonly" placeholder="Percentage Sponsered by state govt" id="state_ratio" value="{{$val->state_ratio}}">
                                              </div>
                                              <div class="col-xl-6">
                                                  <label>Central Govt. (%)</label>
                                                  <input type="text" name="center_ratio" class="form-control numberonly" placeholder="Percentage Sponsered by central govt" id="central_ratio" value="{{$val->center_ratio}}">
                                              </div>
                                          </div>
                                          @if(Session::has('state_center_ratio_error'))
                                            <div class="row">
                                                <div class="col-xl-12">
                                                    <p class="text-red">{{ Session::get('state_center_ratio_error') }}</p>
                                                </div>
                                            </div>
                                          @endif

                                          <!-- <div class="radio-inline"> -->
                                        </div>
                                      </div>
                                       <!--start: state center ratio-->
                                      <div class="col-xl-6 ratio">
                                      </div>
                                       <!--end: state center ratio-->
                                    </div>

                                    <div class="row">
                                      <div class="col-xl-12">
                                        <label> Scheme Overview *: <small><b>Max 3000 characters</b></small></label>
                                        <textarea class="form-control" id="next_scheme_overview" name="scheme_overview" maxlength="3000">{{$val->scheme_overview}}</textarea>
                                      </div>
                                    </div>
                                    <!--begin::Input-->
                                    <div class="form-group">
                                      <label>Objectives of Scheme * : <small><b>Max 3000 characters</b></small></label>
                                      <textarea class="form-control" id="next_scheme_objective" name="scheme_objective" maxlength="3000">{{$val->scheme_objective}}</textarea>
                                    </div>
                                    <!--end::Input-->
                                    <!--begin::Input-->
                                    <div class="form-group">
                                      <label>Name of Schemes/Components * : <small><b>Max 3000 characters</b></small></label>
                                      <textarea class="form-control" id="next_scheme_components" name="sub_scheme" maxlength="3000">{{$val->sub_scheme}}</textarea>
                                    </div>
                                    <!--end::Input-->
                                    </div>
                                </div>
                                <!-- fourth_slide close -->
                                <div class="fifth_slide otherslides" style="display:none">
                                <div class="pb-5" data-wizard-type="step-content">
                                    <div class="row">
                                      <div class="col-xl-4 col-sm-4">
                                        <!--begin::Input-->
                                        <div class="form-group">
                                          <label>Year of actual Commencement of Scheme * :</label>
                                          <input class="form-control" onkeyup="fin_year(this.value)" type="text" name="commencement_year" id="commencement_year" value="{{$val->commencement_year}}" />
                                          <span id="fin_year_span" style="color:red"></span>
                                        </div>
                                        <!--end::Input-->
                                      </div>
                                      <div class="col-xl-1 col-sm-1"></div>
                                      <div class="col-xl-7 col-sm-7">
                                        <!--begin::Input-->
                                        <div class="form-group">
                                          <label>Present status with coverage of scheme * :</label>
                                          <div class="radio-inline">
                                            <label class="radio radio-rounded">
                                                <input type="radio" name="scheme_status" value="Y" checked />
                                                <span></span>
                                                Operational
                                            </label>
                                            <label class="radio radio-rounded">
                                                <input type="radio" name="scheme_status" value="N"/>
                                                <span></span>
                                                Non-Operational
                                            </label>
                                          </div>
                                        </div>
                                        <!--end::Input-->
                                      </div>
                                  </div>
                                  <div class="row">
                                      <div class="col-xl-12 col-sm-12">
                                        <!--begin::Input-->
                                        <div class="form-group">
                                            <label>Sustainable Development Goals (SDG) Served ? * :</label>
                                            <div class="row">
                                                @php $issdg = array(); $issdg = json_decode($val->is_sdg); @endphp
                                                @foreach($goals as $k => $g)
                                                <div class="col-xl-4">
                                                    <div class="form-group form-check">
                                                        @if(!empty($issdg) and in_array($g->goal_id,$issdg))
                                                            <input type="checkbox" checked name="sustainable_goals[]" class="form-check-input" id="goal1" value="{{ $g->goal_id }}">
                                                        @else
                                                            <input type="checkbox" name="sustainable_goals[]" class="form-check-input" id="goal1" value="{{ $g->goal_id }}">
                                                        @endif
                                                        <label class="form-check-label" for="goal1">{{ $g->goal_name }}</label>
                                                    </div>
                                                </div>
                                                @endforeach
                                            </div>
                                        </div>
                                        <!--end::Input-->
                                      </div>
                                    </div>
                                    <!-- row -->
                                </div>
                            </div>
                            <!-- fifth_slide close -->

                            <div class="sixth_slide otherslides" style="display:none">
                                <div class="pb-5" data-wizard-type="step-content">
                                    <div class="row">
                                      <div class="col-xl-12">
                                        <!--begin::Input-->
                                        <div class="form-group">
                                          <label>Beneficiary selection Criteria * : <small><b>Max 500 characters</b></small></label>
                                        </div>
                                        @php $bene_cri = json_decode($val->scheme_beneficiary_selection_criteria); @endphp
                                        @if(!empty($bene_cri))
                                          @foreach($bene_cri as $kcri => $vcri)
                                          <div class="form-group" id="beneficiary_selection_div_{{$kcri}}">
                                            <label>Beneficiary Criteria {{$kcri + 1}}</label>
                                            <textarea class="form-control next_beneficiary_selection_criterias" id="next_beneficiary_selection_criteria" name="beneficiary_selection_criteria[{{$kcri}}][beneficiary_selection_criteria]" rows="2" maxlength="500">{{ $vcri->beneficiary_selection_criteria }}</textarea>
                                          </div>
                                          @endforeach
                                        @else
                                          <div class="form-group" id="beneficiary_selection_div_0">
                                            <label>Beneficiary Criteria 1</label>
                                            <textarea class="form-control next_beneficiary_selection_criterias" id="next_beneficiary_selection_criteria" name="beneficiary_selection_criteria[0][beneficiary_selection_criteria]" rows="2" maxlength="500"></textarea>
                                          </div>
                                        @endif
                                        <!--end::Input-->
                                          <button type="button" class="btn btn-xs btn-primary" id="btn_add_beneficiary_sel_criteria" style="margin-top:0px">
                                              <!--begin::Svg Icon | path: icons/duotune/arrows/arr075.svg-->
                                              <span class="svg-icon svg-icon-2">
                                                  <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                  <rect opacity="0.5" x="11.364" y="20.364" width="16" height="2" rx="1" transform="rotate(-90 11.364 20.364)" fill="white"></rect>
                                                  <rect x="4.36396" y="11.364" width="16" height="2" rx="1" fill="white"></rect>
                                                  </svg>
                                              </span>
                                          Add Selection Criteria</button>
                                      </div>
                                    </div>
                                </div>
                            </div>
                            <!-- sixth_slide close -->
                                <div class="seventh_slide otherslides" style="display:none">
                                    <form method="post" id="seventh_slide_form" enctype="multipart/form-data">
                                    @csrf
                                    <input type="hidden" name="slide" value="seventh">
                                    <div class="row" style="margin-top:10px">
                                      <div class="col-xl-12">
                                        <!--begin::Input-->
                                        <div class="form-group">
                                          <label>Expected Major Benefits Derived from the Scheme * :  </label>
                                        </div>
                                        <!--end::Input-->
                                      </div>
                                    </div>

                                    <div class="row">
                                      <div class="col-xl-12">
                                        @php $major_benefits = json_decode($val->major_benefits_text); @endphp
                                        @if(!empty($major_benefits))
                                          @foreach($major_benefits as $kmaj => $vmaj)
                                          <div class="form-group" id="major_benefits_div_{{$kmaj}}">
                                            <label>Major Benefit {{ $kmaj + 1 }} * :  </label>
                                            <div>
                                              <textarea class="form-control major_benefit_textareas" name="major_benefits_text[{{$kmaj}}][major_benefits_text]" id="major_benefit_textarea_{{$kmaj}}" rows="2">{{ $vmaj->major_benefits_text }}</textarea>
                                            </div>
                                          </div>
                                          @endforeach
                                        @else
                                          <div class="form-group" id="major_benefits_div_0">
                                            <label>Major Benefit 1 * :  </label>
                                            <div>
                                              <textarea class="form-control major_benefit_textareas" name="major_benefits_text[0][major_benefits_text]" id="major_benefit_textarea_0" rows="2"></textarea>
                                            </div>
                                          </div>
                                        @endif
                                      </div>
                                      <div class="col-xl-12">
                                          <button type="button" class="btn btn-xs btn-primary" id="btn_add_major_benefit" style="margin-top:20px">
                                              <!--begin::Svg Icon | path: icons/duotune/arrows/arr075.svg-->
                                              <span class="svg-icon svg-icon-2">
                                                  <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                  <rect opacity="0.5" x="11.364" y="20.364" width="16" height="2" rx="1" transform="rotate(-90 11.364 20.364)" fill="white"></rect>
                                                  <rect x="4.36396" y="11.364" width="16" height="2" rx="1" fill="white"></rect>
                                                  </svg>
                                              </span>
                                          Add Major Benefit</button>
                                      </div>
                                    </div>

                                    <div style="margin-top:20px"></div>

                                    <div class="row">
                                      <div class="col-xl-12">
                                        <div class="form-group">
                                          <div class="custom-file">
                                            <input type="file" class="custom-file-input next_major_benefits_file" name="major_benefits" id="customFile" accept=".pdf,.docx,.xlsx" />
                                            <label class="custom-file-label" for="customFile">Choose file</label>
                                          </div>
                                        </div>
                                        <div class="form-group">
                                          @if($val->benefit_to_file)
                                            <a href="{{ $replace_url }}/get_the_file/{{ $scheme_id }}/_major_benefits_" target="_blank"><i class="fas fa-file-pdf fa-2x" style="color:red;"></i></a>
                                          @endif
                                        </div>
                                      </div>
                                    </div>
                                    <button type="submit" id="btn_seventh_slide_submit" style="visibility: hidden;"></button>
                                    </form>
                                  </div>
                                  <!-- seventh_slide close -->

                                <div class="eighth_slide otherslides" style="display:none;">
                                <form method="post" enctype="multipart/form-data" id="eighth_slide_form">
                                @csrf
                                <input type="hidden" name="slide" value="eighth">
                                  <div class="pb-5" data-wizard-type="step-content">
                                    <div class="row">
                                      <div class="col-xl-12">
                                        <!--begin::Input-->
                                        <div class="form-group">
                                          <label>Implementing procedure of the Scheme & Getting Benefit * : <small><b>Max 500 characters</b></small></label>
                                          <textarea class="form-control" id="next_scheme_implementing_procedure" name="scheme_implementing_procedure" maxlength="500">{{ $val->scheme_implementing_procedure }}</textarea>
                                        </div>
                                        <!--end::Input-->
                                      </div>
                                    </div> 

                                    <div class="row">
                                      <div class="col-xl-12">
                                        <label>Administrative set up for the Implementation of the scheme <br>From state to beneficiaries Geographical Coverage</label>
                                        <select name="beneficiariesGeoLocal" class="form-control" id="beneficiariesGeoLocal" onchange="fngetdist(this.value)">
                                            <option value="">Select Coverage Area</option>
                                            @foreach($beneficiariesGeoLocal as $key=>$value)
                                                <option value="{{$key}}" @if($key == $val->beneficiariesGeoLocal) selected @endif>{{$value}}</option>
                                            @endforeach
                                        </select>
                                        <div id="load_gif_img"></div>
                                        <label style="margin-top:20px">Remarks</label>
                                        <!-- <input type="text" name="otherbeneficiariesGeoLocal" placeholder="other Geographical beneficiaries coverage" class="form-control"> -->
                                        <textarea name="otherbeneficiariesGeoLocal" id="next_otherbeneficiariesGeoLocal" placeholder="other Geographical beneficiaries coverage areas or Remarks" class="form-control" rows="2">{{$val->otherbeneficiariesGeoLocal}}</textarea>
                                        <div></div>
                                          <div class="custom-file" style="margin:20px 0px">
                                            <input type="file" class="custom-file-input" name="geographical_coverage" id="geographical_coverage" accept=".pdf,.docx,.xlsx"/>
                                            <label class="custom-file-label" for="customFile">Choose file</label>
                                          </div>
                                          @if($val->geographical_coverage)
                                            <a href="{{ $replace_url }}/get_the_file/{{ $scheme_id }}/_geographical_coverage" target="_blank"><i class="fas fa-file-pdf fa-2x" style="color:red;"></i></a>
                                          @endif
                                      </div>
                                    </div>
                                  </div>
                                    <button type="submit" id="btn_eighth_slide_submit" style="visibility: hidden;"></button>
                                </form>
                                </div>
                                <!-- eighth_slide close -->
                                <div class="nineth_slide otherslides" style="display:none;">
                                <form method="post" enctype="multipart/form-data" id="nineth_slide_form">
                                @csrf
                                <input type="hidden" name="slide" value="nineth">
                                    <div class="row">  
                                      <div class="col-xl-12">
                                        <label>Scheme coverage since inception of scheme</label>
                                      </div>
                                    </div>
                                    <div class="row">
                                      <div class="col-xl-12">
                                        <div class="form-group">
                                          <label>Coverage of Beneficiaries * : </label>
                                          <div></div>
                                          <div class="custom-file">
                                            <textarea name="coverage_beneficiaries_remarks" id="next_coverage_beneficiaries_remarks" class="form-control" rows="2">{{$val->coverage_beneficiaries_remarks}}</textarea>
                                          </div>
                                        </div>
                                      </div>
                                    </div>
                                    <div style="margin-top: 10px"></div>
                                    <div class="row">
                                      <div class="col-xl-12">
                                        <!--begin::Input-->
                                        <div class="form-group">
                                          <label>Coverage of Beneficiaries : </label>
                                          <div></div>
                                          <div class="custom-file">
                                            <input type="file" class="custom-file-input" name="beneficiaries_coverage" id="beneficiaries_coverage" accept=".pdf,.docx,.xlsx"/>
                                            <label class="custom-file-label" for="customFile">Choose file</label>
                                          </div>
                                        </div>
                                        @if($val->geographical_coverage)
                                        <div class="form-group">
                                            <a href="{{ $replace_url }}/get_the_file/{{ $scheme_id }}/_beneficiaries_coverage" target="_blank"><i class="fas fa-file-pdf fa-2x" style="color:red;"></i></a>
                                        </div>
                                        @endif
                                        <!--end::Input-->
                                      </div>
                                    </div>
                                    <div style="margin-top: 10px"></div>
                                    <div class="row">
                                      <div class="col-xl-12">
                                        <div class="form-group">
                                          <label>Training/Capacity Building program * : </label>
                                          <div></div>
                                          <div class="custom-file">
                                            <textarea name="training_capacity_remarks" id="next_training_capacity_remarks" class="form-control" rows="2">{{ $val->training_capacity_remarks }}</textarea>
                                          </div>
                                        </div>
                                      </div>
                                    </div>
                                    <div style="margin-top: 20px"></div>
                                    <div class="row">
                                      <div class="col-xl-12">
                                        <div class="form-group">
                                          <!-- <label>Training/Capacity building of facilitators : </label> -->
                                          <div></div>
                                          <div class="custom-file">
                                            <input type="file" class="custom-file-input" name="training" id="training" accept=".pdf,.docx,.xlsx"/>
                                            <label class="custom-file-label" for="customFile">Choose file</label>
                                          </div>
                                        </div>
                                        @if($val->training)
                                        <div class="form-group">
                                            <a href="{{ $replace_url }}/get_the_file/{{ $scheme_id }}/_training" target="_blank"><i class="fas fa-file-pdf fa-2x" style="color:red;"></i></a>
                                        </div>
                                        @endif
                                      </div>
                                    </div>
                                    <div style="margin-top: 10px"></div>
                                    <div class="row">
                                      <div class="col-xl-12">
                                        <div class="form-group">
                                          <label>IEC Activities </small> : </label>
                                          <div></div>
                                          <div class="custom-file">
                                            <textarea name="iec_activities_remarks" id="next_iec_activities_remarks" class="form-control" rows="2">{{ $val->iec_activities_remarks }}</textarea>
                                          </div>
                                        </div>
                                      </div>
                                    </div>
                                    <div style="margin-top: 20px"></div>
                                    <div class="row">
                                      <div class="col-xl-12">
                                        <!--begin::Input-->
                                        <div class="form-group">
                                          <!-- <label>IEC activities : </label> -->
                                          <div></div>
                                          <div class="custom-file">
                                            <input type="file" class="custom-file-input" name="iec_file" id="iec" accept=".pdf,.docx,.xlsx"/>
                                            <label class="custom-file-label" for="customFile">Choose file</label>
                                          </div>
                                        </div>
                                        @if($val->iec)
                                        <div class="form-group">
                                            <a href="{{ $replace_url }}/get_the_file/{{ $scheme_id }}/_iec" target="_blank"><i class="fas fa-file-pdf fa-2x" style="color:red;"></i></a>
                                        </div>
                                        @endif
                                        <!--end::Input-->
                                      </div>
                                    </div>
                                    <button type="submit" id="btn_nineth_slide_submit" style="visibility: hidden;"></button>
                                </form>
                                </div>
                                <!-- nineth_slide close -->

                                <div class="tenth_slide otherslides" style="display:none">
                                    <div class="row">
                                      <div class="col-xl-12">
                                        <label>Asset/Service creation & its maintenance plan if any</label> 
                                      </div>
                                    </div>
                                    <div class="row">
                                      <div class="col-xl-6">
                                        <!--begin::Input-->
                                        <div class="form-group">
                                          <label>Beneficiary Group * : </label>
                                          <select name="benefit_to" id="next_benefit_to" class="form-control">
                                              <option value="">Select Beneficiary Group</option>
                                              <option value="Individual" @if($val->benefit_to == 'Individual') selected @endif >Individual</option>
                                              <option value="Community" @if($val->benefit_to == 'Community') selected @endif>Community</option>
                                              <option value="Both" @if($val->benefit_to == 'Both') selected @endif>Both</option>
                                          </select>
                                        </div>
                                        <!--end::Input-->
                                      </div>
                                    </div>
                                    <input type="hidden" name="convergencewithotherscheme" value="Own_Department">
                                    @php $convergences = json_decode($val->all_convergence); @endphp
                                    @if(!empty($convergences))
                                        <label class="col-xl-12">Convergence with other scheme</label>
                                      @foreach($convergences as $kconv => $vconv)
                                      <div class="row countallconvergence" id="convergence_row_{{$kconv}}">
                                        <div class="col-xl-5">
                                          <label></label>
                                          <select name="convergence_dept_ids[]" id="next_convergence_dept_id" class="form-control">
                                            <option value="">Select Department</option>
                                            @foreach($departments as $depts)
                                              <option value="{{ $depts->dept_id }}" @if($depts->dept_id == $vconv->dept_id) selected @endif>{{ $depts->dept_name }}</option>
                                            @endforeach
                                          </select>
                                        </div>
                                        <div class="col-xl-6">
                                          <label></label>
                                          <textarea placeholder="Remarks" name="convergence_text[]" id="next_convergence_text" rows="1" class="form-control">{{ $vconv->dept_remarks }}</textarea>
                                        </div>
                                        <div class="col-xl-1">
                                            @if($kconv > 0)
                                                <p></p>
                                                <button type="button" id="1" class="btn btn-sm btn-primary convergence_removeal_class" style="font-weight:bolder;font-size:30px" onclick="fn_remove_the_convergence_div({{$kconv}})">-</button>
                                            @endif
                                        </div>
                                      </div>
                                      @endforeach
                                    @else
                                      <div class="row countallconvergence" id="convergence_row_0">
                                        <label class="col-xl-12">Convergence with other scheme</label>
                                        <div class="col-xl-5">
                                          <label></label>
                                          <select name="convergence_dept_ids[]" id="next_convergence_dept_id" class="form-control">
                                            <option value="">Select Department</option>
                                            @foreach($departments as $depts)
                                              <option value="{{ $depts->dept_id }}">{{ $depts->dept_name }}</option>
                                            @endforeach
                                          </select>
                                        </div>
                                        <div class="col-xl-6">
                                          <label></label>
                                          <textarea placeholder="Remarks" name="convergence_text[]" id="next_convergence_text" rows="1" class="form-control"></textarea>
                                        </div>
                                        <div class="col-xl-1"></div>
                                      </div>
                                    @endif
                                    <div id="removeallotherdepts"></div>
                                    <div class="row" id="convergence_btn_row_0" style="margin-bottom:10px">
                                      <div class="col-xl-12">
                                        <p></p>
                                        <button type="button" class="btn btn-xs btn-primary" id="the_convergence_btn">+ Add other department</button>
                                      </div>
                                    </div>
                                </div>
                                <!-- tenth_slide close -->


                                <div class="eleventh_slide otherslides" style="display:none">
                                <form method="post" enctype="multipart/form-data" id="eleventh_slide_form">
                                @csrf
                                <input type="hidden" name="slide" value="eleventh">
                                    <div class="row">
                                      <div class="col-xl-12">
                                        <label>Scheme Related all relevant Document</label> 
                                      </div>
                                    </div>
                                    <div class="row">
                                      <div class="col-xl-4">
                                        <!--begin::Input-->
                                        <div class="form-group">
                                          <label>GR : </label>
                                          <div></div>
                                          <div class="custom-file">
                                            <input type="file" class="custom-file-input next_gr_files" id="gr" name="gr[]" multiple accept=".pdf,.docx,.xlsx"/>
                                            <label class="custom-file-label" for="customFile">Choose file</label>
                                          </div>
                                        </div>
                                        @if($gr_files->count())
                                        <div class="form-group">
                                        @foreach($gr_files as $kgr => $vgr)
                                            <a href="{{ $replace_url }}/get_the_file/{{ $scheme_id }}/_gr_{{++$kgr}}" target="_blank"><i class="fas fa-file-pdf fa-2x" style="color:red;"></i></a>
                                        @endforeach
                                        </div>
                                        @endif
                                        <!--end::Input-->
                                      </div>
                                      <div class="col-xl-4">
                                        <!--begin::Input-->
                                        <div class="form-group">
                                          <label>Notification  : </label>
                                          <div></div>
                                          <div class="custom-file">
                                            <input type="file" class="custom-file-input next_notification_files" id="notification" name="notification[]" multiple accept=".pdf,.docx,.xlsx"/>
                                            <label class="custom-file-label" for="customFile">Choose file</label>
                                          </div>
                                        </div>
                                        @if($notifications->count())
                                        <div class="form-group">
                                        @foreach($notifications as $kgr => $vgr)
                                            <a href="{{ $replace_url }}/get_the_file/{{ $scheme_id }}/_notification_{{++$kgr}}" target="_blank"><i class="fas fa-file-pdf fa-2x" style="color:red;"></i></a>
                                        @endforeach
                                        </div>
                                        @endif
                                        <!--end::Input-->
                                      </div>
                                      <div class="col-xl-4">
                                        <!--begin::Input-->
                                        <div class="form-group">
                                          <label>Brochure : </label>
                                          <div></div>
                                          <div class="custom-file">
                                            <input type="file" class="custom-file-input next_brochure_files" id="brochure" name="brochure[]" multiple accept=".pdf,.docx,.xlsx"/>
                                            <label class="custom-file-label" for="customFile">Choose file</label>
                                          </div>
                                        </div>
                                        @if($brochures->count())
                                        <div class="form-group">
                                        @foreach($brochures as $kgr => $vgr)
                                            <a href="{{ $replace_url }}/get_the_file/{{ $scheme_id }}/_brochure_{{++$kgr}}" target="_blank"><i class="fas fa-file-pdf fa-2x" style="color:red;"></i></a>
                                        @endforeach
                                        </div>
                                        @endif
                                        <!--end::Input-->
                                      </div>
                                    </div>
                                    <div class="row">
                                      <div class="col-xl-6">
                                        <!--begin::Input-->
                                        <div class="form-group">
                                          <label>Pamphlets : </label>
                                          <div></div>
                                          <div class="custom-file">
                                            <input type="file" class="custom-file-input next_pamphlets_files" id="pamphlets" name="pamphlets[]" multiple accept=".pdf,.docx,.xlsx"/>
                                            <label class="custom-file-label" for="customFile">Choose file</label>
                                          </div>
                                        </div>
                                        @if($pamphlets->count())
                                        <div class="form-group">
                                        @foreach($pamphlets as $kgr => $vgr)
                                            <a href="{{ $replace_url }}/get_the_file/{{ $scheme_id }}/_pamphlets_{{++$kgr}}" target="_blank"><i class="fas fa-file-pdf fa-2x" style="color:red;"></i></a>
                                        @endforeach
                                        </div>
                                        @endif
                                        <!--end::Input-->
                                      </div>
                                      <div class="col-xl-6">
                                        <!--begin::Input-->
                                        <div class="form-group">
                                          <label>Other Details of Schemes (Central - State Separate) : </label>
                                          <div></div>
                                          <div class="custom-file">
                                            <input type="file" class="custom-file-input next_otherdetailscenterstate" id="other_details_center_state" name="otherdetailscenterstate[]" multiple accept=".pdf,.docx,.xlsx"/>
                                            <label class="custom-file-label" for="customFile">Choose file</label>
                                          </div>
                                        </div>
                                        @if($center_state->count())
                                        <div class="form-group">
                                        @foreach($center_state as $kgr => $vgr)
                                            <a href="{{ $replace_url }}/get_the_file/{{ $scheme_id }}/_center_state_{{++$kgr}}" target="_blank"><i class="fas fa-file-pdf fa-2x" style="color:red;"></i></a>
                                        @endforeach
                                        </div>
                                        @endif
                                        <!--end::Input-->
                                      </div>
                                    </div>
                                    <button type="submit" id="btn_eleventh_slide_submit" style="visibility: hidden;"></button>
                                </form>
                                </div>
                                <!-- eleventh_slide close -->

                                    <div class="twelth_slide otherslides" style="display:none;">
                                    <div class="row">  
                                      <div class="col-xl-12">
                                        <label>Major Monitoring Indicator at HoD Level (Other than Secretariat Level)</label> 
                                      </div>
                                    </div>
                                    <div class="row">  
                                      <table class="table" id="indicator_table">
                                        <tbody>
                                        @php $indicator_hods = json_decode($val->major_indicator_hod); @endphp
                                        @if(empty($indicator_hods))
                                          <tr>
                                            <th class="borderless"><label>Indicator 1 </label></th>
                                          </tr>
                                          <tr>
                                            <td class="borderless major_hod_indicator_td" width="95%"><input class="form-control getindicator_hod" id="indicator_hod_id_0" type="text" name="major_indicator_hod[0][major_indicator_hod]" /></td>
                                            <td class="borderless" width="5%"><button type="button" class="btn btn-primary" id="addnewindicatorbtn" style="padding:2px;width:20px;height:auto;font-weight:bolder">+</button></td>
                                          </tr>
                                        @else
                                        @foreach($indicator_hods as $khod => $vhod)
                                        @if($khod > 0)
                                          <tr class="indicator_tr_{{$khod}}">
                                        @else
                                          <tr>
                                        @endif
                                            <th class="borderless"><label>Indicator {{++$khod}} </label></th>
                                          </tr>
                                        @if($khod > 0)
                                          <tr class="indicator_tr_{{--$khod--}}">
                                        @else
                                          <tr>
                                        @endif                                            
                                            <td class="borderless major_hod_indicator_td" width="95%"><input class="form-control getindicator_hod" id="indicator_hod_id_{{$khod}}" type="text" name="major_indicator_hod[{{$khod}}][major_indicator_hod]" value="{{ $vhod->major_indicator_hod }}" /></td>
                                            <td class="borderless" width="5%">
                                                @if($khod == 0)
                                                    <button type="button" class="btn btn-primary" id="addnewindicatorbtn" style="padding:2px;width:20px;height:auto;font-weight:bolder">+</button>
                                                @else
                                                    <button type="button" class="btn btn-primary" value="{{$khod}}" id="removeindicatorbtn" style="padding:2px;width:20px;height:auto;font-weight:bolder" onclick="removeindicatorrow(this.value)">-</button>
                                                @endif
                                            </td>
                                          </tr>
                                          @endforeach
                                          @endif
                                        </tbody>
                                      </table>
                                    </div>
                                    </div>
                                    <!-- twelth_slide close -->

                                    <div class="thirteenth_slide otherslides" style="display:none">
                                    <div class="row">
                                      <div class="col-xl-12">
                                        <label>Physical & Financial Progress (component wise) Last Five Year</label>
                                      </div>
                                    </div>
                                    <div class="row">
                                      <div class="col-xl-12">
                                        <table class="table table-bordered" id="kt_datatable" style="margin-top: 13px !important">
                                          <thead>
                                            <tr>
                                                <th rowspan="2" style="font-size: 16px;">Financial Year</th>
                                                <!-- <th rowspan="2" style="font-size: 16px;">Unit</th> -->
                                                <th colspan="3" class="text-center">Physical</th>
                                                <th colspan="2" class="text-center">Financial <small>(Rs. lakh)</small></th>
                                            </tr>
                                            <tr>
                                              <th style="font-size: 16px;">Units</th>
                                              <th style="font-size: 16px;">Target</th>
                                              <th style="font-size: 16px;">Achivement</th>
                                              <th style="font-size: 16px;">Fund</th>
                                              <th style="font-size: 16px;">Expenditure</th>
                                              <th style="font-size: 16px;"></th>
                                            </tr>
                                          </thead>
                                          <tbody id="thisistbody">
                                            @if($financial_progress->count())
                                            @foreach($financial_progress as $i => $vfp)
                                            <tr class="finprogresstr_{{$i}}">
                                              <td class="finprogresstd_{{$i}}"><select style="padding:2px" class="form-control next_financial_progress_year next_fin_year_{{$i}}" name="financial_progress[{{ $i }}][financial_year]"><option value="">Year</option>@foreach($financial_years as $year) <option value="{{ $year }}" @if($year == $vfp->financial_year) selected @endif>{{ $year }}</option> @endforeach </select></td>
                                              <td class="finprogresstd_{{$i}}"><input type="text" class="form-control next_financial_progress_units next_fin_units_{{$i}}" name="financial_progress[{{ $i }}][units]" maxlength="20" value="{{ $vfp->units }}" /></td>
                                              <td class="finprogresstd_{{$i}}"><input type="text" class="form-control allowonly2decimal next_financial_progress_target next_fin_target_{{$i}}" name="financial_progress[{{ $i }}][target]" value="{{$vfp->target}}" /></td>
                                              <td class="finprogresstd_{{$i}}"><input type="text" class="form-control allowonly2decimal next_financial_progress_achivement next_fin_achivement_{{$i}}" name="financial_progress[{{ $i }}][achivement]" value="{{$vfp->achievement}}" /></td>
                                              <td class="finprogresstd_{{$i}}"><input type="text" class="form-control allowonly2decimal next_financial_progress_allocation next_fin_allocation_{{$i}}" name="financial_progress[{{ $i }}][allocation]" value="{{ $vfp->allocation }}" /></td>
                                              <td class="finprogresstd_{{$i}}"><input type="text" class="form-control allowonly2decimal next_financial_progress_expenditure next_fin_expenditure_{{$i}}" name="financial_progress[{{ $i }}][expenditure]" value="{{ $vfp->expenditure }}" /></td>
                                              <td class="finprogresstd_{{$i}}">
                                                @if($i <= 0)
                                                <button type="button" class="btn btn-primary finprogressbtn" value="{{$i}}" style="padding:2px;width:20px;height:auto;font-weight:bolder;">+</button>
                                                @else
                                                <button type="button" class="btn btn-primary finprogressbtnremove" onclick="remove_financial_year(this.value)" value="{{$i}}" style="padding:2px;width:20px;height:auto;font-weight:bolder;">-</button>
                                                @endif
                                            </td>
                                            </tr>
                                            @endforeach
                                            @else
                                            @php $i = 0; @endphp
                                            <tr class="finprogresstr_{{$i}}">
                                              <td class="finprogresstd_{{$i}}"><select style="padding:2px" class="form-control next_financial_progress_year next_fin_year_{{$i}}" name="financial_progress[{{ $i }}][financial_year]"><option value="">Year</option>@foreach($financial_years as $year) <option value="{{ $year }}">{{ $year }}</option> @endforeach </select></td>
                                              <td class="finprogresstd_{{$i}}"><input type="text" class="form-control next_financial_progress_units next_fin_units_{{$i}}" name="financial_progress[{{ $i }}][units]" maxlength="20" /></td>
                                              <td class="finprogresstd_{{$i}}"><input type="text" class="form-control allowonly2decimal next_financial_progress_target next_fin_target_{{$i}}" name="financial_progress[{{ $i }}][target]"/></td>
                                              <td class="finprogresstd_{{$i}}"><input type="text" class="form-control allowonly2decimal next_financial_progress_achivement next_fin_achivement_{{$i}}" name="financial_progress[{{ $i }}][achivement]"/></td>
                                              <td class="finprogresstd_{{$i}}"><input type="text" class="form-control allowonly2decimal next_financial_progress_allocation next_fin_allocation_{{$i}}" name="financial_progress[{{ $i }}][allocation]" /></td>
                                              <td class="finprogresstd_{{$i}}"><input type="text" class="form-control allowonly2decimal next_financial_progress_expenditure next_fin_expenditure_{{$i}}" name="financial_progress[{{ $i }}][expenditure]" /></td>
                                              <td class="finprogresstd_{{$i}}">
                                                @if($i <= 0)
                                                <button type="button" class="btn btn-primary finprogressbtn" value="{{$i}}" style="padding:2px;width:20px;height:auto;font-weight:bolder;">+</button>
                                                @else
                                                <button type="button" class="btn btn-primary finprogressbtnremove" onclick="remove_financial_year(this.value)" value="{{$i}}" style="padding:2px;width:20px;height:auto;font-weight:bolder;">-</button>
                                                @endif
                                            </td>
                                            </tr>
                                            @endif
                                          </tbody>
                                        </table>
                                      </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xl-12">
                                            <div class="form-group">
                                                <label>Financial and Physical Progress Remarks</label>
                                                <textarea rows="2" name="financial_progress_remarks" class="form-control" id="financial_progress_remarks" maxlength="3000">{{ $val->fin_progress_remarks }}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                    </div>
                                    <!-- thirteenth_slide close -->

                                    <div class="fourteenth_slide otherslides" style="display:none">
                                    <form method="post" id="fourteenth_slide_form" enctype="multipart/form-data">
                                    @csrf
                                    <input type="hidden" name="slide" value="fourteenth">
                                    <div class="row">
                                      <div class="col-xl-12">
                                        <div class="form-group">
                                          <label>10. Is Evaluation of this scheme already Performed ? * :</label>
                                          <div></div>
                                          <div class="radio-inline">
                                            <label class="radio radio-rounded">
                                                <input type="radio" name="is_evaluation" id="is_evaluation_yes" value="Y" class="is_evaluation" onclick="fn_show_if_eval(this.value)" @if($val->is_evaluation == 'Y') checked @endif />
                                                <span></span>
                                                Yes
                                            </label>
                                            <label class="radio radio-rounded">
                                                <input type="radio" name="is_evaluation" id="is_evaluation_no" value="N" class="is_evaluation" onclick="fn_show_if_eval(this.value)" @if($val->is_evaluation == 'N') checked @endif />
                                                <span></span>
                                                No
                                            </label>
                                          </div>
                                        </div>
                                      </div>                
                                    </div>
                                    <div class="row" id="if_eval_yes_div" style="display:none">
                                        <div class="col-xl-12">
                                            <div class="form-group">
                                                <label>By Whom ?</label>
                                                <input type="text" name="eval_by_whom" id="eval_by_whom" class="form-control" value="{{$val->eval_scheme_bywhom}}">
                                            </div>
                                        </div>
                                        <div class="col-xl-12">
                                            <div class="form-group">
                                                <label>When ?</label>
                                                <input type="date" name="eval_when" id="eval_when" class="form-control" value="{{ $val->eval_scheme_when }}">
                                            </div>
                                        </div>
                                        <div class="col-xl-12">
                                            <div class="form-group">
                                                <label>Geographical coverage of beneficiaries</label>
                                                <input type="text" name="eval_geographical_coverage_beneficiaries" class="form-control" id="eval_geographical_coverage_beneficiaries" value="{{ $val->eval_scheme_geo_cov_bene }}">
                                            </div>
                                        </div>
                                        <div class="col-xl-12">
                                            <div class="form-group">
                                                <label>No. of beneficiaries in sample <small>( greater than 10 )</small> </label>
                                                <input type="text" name="eval_number_of_beneficiaries" class="form-control numberonly" id="eval_number_of_beneficiaries" maxlength="90" value="{{ $val->eval_scheme_no_of_bene }}">
                                            </div>
                                        </div>
                                        <div class="col-xl-12">
                                            <div class="form-group">
                                                <label>Major recommendation</label>
                                                <input type="text" name="eval_major_recommendation" class="form-control" id="eval_major_recommendation" value="{{ $val->eval_scheme_major_recommendation }}">
                                            </div>
                                        </div>
                                        <div class="col-xl-12">
                                            <div class="form-group">
                                                <label>Upload Report</label>
                                                <div></div>
                                                <div class="custom-file">
                                                    <input type="file" class="custom-file-input" name="eval_upload_report" id="eval_if_yes_upload_file" accept=".pdf,.xlsx,.docx">
                                                    <label class="custom-file-label" for="eval_if_yes_upload_file">Choose File</label>
                                                </div>
                                            </div>
                                            @if($val->eval_scheme_report)
                                                <div class="form-group">
                                                    <a href="{{ $replace_url }}/get_the_file/{{ $scheme_id }}/_eval_report" target="_blank"><i class="fas fa-file-pdf fa-2x" style="color:red;"></i></a>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xl-5"></div>
                                        <div class="col-xl-7">
                                            <!-- <button type="submit" class="btn btn-success font-weight-bold text-uppercase px-9 py-4" data-wizard-type="action-submit" id="submit">Submit</button> -->
                                        </div>
                                    </div>
                                    <button type="submit" style="visibility: hidden;" id="btn_fourteenth_slide_submit"></button>
                                </form>
                                </div>
                                <!-- fourteenth_slide close -->
<!-- 
                                <div class="fifteenth_slide otherslides" style="display:none">
                                    <div class="row by_whome"  style="display: none;">
                                      <div class="col-xl-12">
                                        <label>10.1 By Whome evaluation was done :</label>
                                        <input type="text" class="form-control form-control-lg" name="before_eval_name" maxlength="4" />
                                      </div>
                                    </div>
                                    <div class="by_when" style="display: none;">
                                      <div class="col-xl-3">
                                        <div class="form-group">
                                            <label>10.2 Which Year evaluation was done  :</label>
                                            <input class="form-control numberonly" type="text"  id="before_eval_year" name="before_eval_year"/>
                                        </div>
                                      </div>
                                      <div class="col-xl-12">
                                        <div class="form-group">
                                          <label>10.3 Report of Past Evaluation (No. of beneficiary covered geographical area Major recommendation):</label>
                                          <div></div>
                                          <div class="custom-file">
                                            <input type="file" class="custom-file-input" id="past_eval" name="past_eval" accept=".pdf,.docx,.xlsx" />
                                            <label class="custom-file-label" for="past_eval">Choose file</label>
                                          </div>
                                        </div>
                                      </div>
                                    </div>
                                  </div>
 -->                                  
                                  <!--end: Wizard Step 4-->

                                  <!--begin: Wizard Actions-->
                                  <div class="row d-flex justify-content-between border-top mt-5 pt-10" style="width:100%">
                                    <div class="col-xl-11">


<!--                                       <button type="button" class="btn btn-light-primary font-weight-bold text-uppercase px-9 py-4" data-wizard-type="action-prev">
                                      Previous
                                      </button> -->


                                      <button type="button" class="btn btn-primary font-weight-bold text-uppercase px-9 py-4" data-wizard-type="action-prev" value="1" onclick="getPrevSlide(this.value)" style="display:none;" id="previous_btn">
                                      Previous
                                      </button>
                                    </div>
                                    <div class="col-xl-1" id="div_next_btn">
                                      
<!--                                       <button type="submit" class="btn btn-success font-weight-bold text-uppercase px-9 py-4" data-wizard-type="action-submit" id="submit">
                                      Submit
                                      </button> -->

                                      <button type="button" class="btn btn-primary font-weight-bold text-uppercase px-9 py-4" data-wizard-type="action-next" value="1" onclick="getNextSlide(this.value)" id="next_btn">
                                      Next
                                      </button>
                                    </div>
                                  </div>
                                  <!--end: Wizard Actions-->
                                <!-- </form> -->
                                <!--end: Wizard Form-->
                              </div>
                            </div>
                          </div>
                        </div>
                        <!--end: Wizard Bpdy-->
                      </div>
                      <!--end: Wizard-->
                    </div>
                  </div>
                  <!--begin::Modal-->
                  <div class="modal fade" id="exampleModalSizeXl" tabindex="-1" role="dialog" aria-labelledby="exampleModalSizeXl" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h5 class="modal-title" id="exampleModalLabel">Modal Title</h5>
                          <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                          <i aria-hidden="true" class="ki ki-close"></i>
                          </button>
                        </div>
                        <div class="modal-body">
                          <p>
                            Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s,
                            when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting,
                            remaining essentially unchanged.
                          </p>
                          <p>
                            Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s,
                            when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting,
                            remaining essentially unchanged.
                          </p>
                          <p>
                            Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s,
                            when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting,
                            remaining essentially unchanged.
                          </p>
                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn btn-light-primary font-weight-bold" data-bs-dismiss="modal">Close</button>
                          <button type="button" class="btn btn-primary font-weight-bold">Save changes</button>
                        </div>
                      </div>
                    </div>
                  </div>
                  <!--end::Modal-->
                </div>
                <!--end::Container-->
              </div>
              <!--end::Entry-->
            </div>
            <!--end::Content-->    
            @endsection
{{-- <script src="{{asset('js/3.2.1.jquery.min.js')}}"></script> --}}
<script src="{{asset('js/jquery.min.js')}}"></script>

<script type="text/javascript">
window.onload = function() {
  var room = 1;
  var indicator_room = 1;
  var beneficiary_selection = 0;
  var convergencewithotherscheme_iterate = 0;
  var major_benefit = 0;
}

var room = 0;
var indicator_room = 0;
var beneficiary_selection = 0;
var convergencewithotherscheme_iterate = 0;
var major_benefit = 0;
</script>

<script>

$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': "{{ csrf_token() }}"
    }
});

//   $('#add-objective').modal('show');
$(document).ready(function() {

  // $('#im_id').change(function() {
  //   var imid = $("#im_id").val();
  //   $("#nodal_id").html('');
  //   $.ajax({
  //     url: "{{ route('get-nodal-by-office') }}",
  //     type: "POST",
  //     data: {
  //       imid: imid,
  //       _token: '{{ csrf_token() }}'
  //     },
  //     dataType: 'json',
  //     beforeSend:function() {
  //       $("#nodal_id").empty();
  //       $("#nodal_id").append('<option value=""> Select </option>');
  //     },
  //     success: function(result) {
  //       $.each(result.nodals, function(key, value) {
  //         $("#nodal_id").append('<option value="' + value.nodalid + '">' + value.nodal_name + '</option>');
  //       });
  //     }
  //   });
  // });

  $('.is_evaluation').on('click', function() {
    var eval = $(".is_evaluation:checked").val();
    if(eval == 'Y'){
      $(".by_whome").css("display", "block");
      $(".by_when").css("display", "block");
    }
    else{
      $(".by_whome").css("display", "none");
      $(".by_when").css("display", "none");
    }
  });

  // $('#con_id').on('change', function() {
  //   var conid = $("#con_id").val();
  //   $("#con_details").html('');
  //   $.ajax({
  //     url: "{{ route('get-convener') }}",
  //     type: "POST",
  //     data: {
  //       conid: conid,
  //       _token: '{{ csrf_token() }}'
  //     },
  //     dataType: 'json',
  //     success: function(result) {
  //       $.each(result.conveners, function(key, value) {
  //         $(".con").append('<input type = "text" class="form-control form-control-lg" value="'+value.convener_name+'  />');
  //       });
  //     }
  //   });
  // });
});

$(document).ready(function() {
  $("#btn_add_objective").click(function(){

    var ktcontent = $("#kt_content").height();
    $(".content-wrapper").css('min-height',ktcontent+50);
    var room = $(".next_major_objectives").length - 1;
    var after_room = room+1;
    var major_objective = "major_objective";
    if(room < 100) {
      $(".room_fields_"+room).after('<div class="room_fields_'+after_room+'"><input class="form-control next_major_objectives" type="text" name="major_objective['+after_room+']['+major_objective+']" /><br></div>');
      room++;
    }
  });
});

$(document).ready(function() {
  $("#btn_add_indicator").click(function(){

    var ktcontent = $("#kt_content").height();
    $(".content-wrapper").css('min-height',ktcontent+50);

    var indicator_room = $(".next_major_indicators").length - 1;
    var after_indicator_room = indicator_room+1;
    var major_indicator = "major_indicator";
    if(indicator_room < 100) {
      $(".indicator_fields_"+indicator_room).after('<div class="form-group indicator_fields_'+after_indicator_room+'"><input class="form-control next_major_indicators" type="text" name="major_indicator['+after_indicator_room+']['+major_indicator+']" /><br></div>');
      indicator_room++;
    }
  });
});

$(document).ready(function() {
  $("#btn_add_beneficiary_sel_criteria").click(function() {

    var ktcontent = $("#kt_content").height();
    $(".content-wrapper").css('min-height',ktcontent+50);

    var beneficiary_selection = $(".next_beneficiary_selection_criterias").length - 1;
    var after_beneficiary_selection = beneficiary_selection+1;
    var beneficiary_selection_criteria = "beneficiary_selection_criteria";
    if(beneficiary_selection < 4) {
      if($("#beneficiary_selection_div_"+beneficiary_selection+" textarea").val() == '') {
        var alert_ben_sec = new String("You missed beneficiary criteria above");
        alert(alert_ben_sec);
      } else {
          $("#beneficiary_selection_div_"+beneficiary_selection).after('<div id="beneficiary_selection_div_'+after_beneficiary_selection+'"><label>Beneficiary Criteria '+(after_beneficiary_selection+1)+': </label><textarea class="form-control next_beneficiary_selection_criterias" type="text" name="beneficiary_selection_criteria['+after_beneficiary_selection+']['+beneficiary_selection_criteria+']" /></textarea><br></div>');
          beneficiary_selection++;
      }
    }
  });
});

$(document).ready(function() {
  $("#btn_add_major_benefit").click(function(){

    var ktcontent = $("#kt_content").height();
    $(".content-wrapper").css('min-height',ktcontent+20);
    var major_benefit = $(".major_benefit_textareas").length - 1;
    var after_major_benefit = major_benefit+1;
    // console.log(after_major_benefit);
    var major_benefits_text = "major_benefits_text";
    if(major_benefit < 4) {
      if($("#major_benefit_textarea_"+major_benefit).val() == '') {
        var alert_maj_ben = new String("You missed major benefit above");
        alert(alert_maj_ben);
      } else {
          $("#major_benefits_div_"+major_benefit).after('<div class="form-group" id="major_benefits_div_'+after_major_benefit+'"><label>Major benefit '+(after_major_benefit+1)+': </label><textarea id="major_benefit_textarea_'+after_major_benefit+'" class="form-control major_benefit_textareas" type="text" name="major_benefits_text['+after_major_benefit+']['+major_benefits_text+']" /></textarea></div>');
          major_benefit++;
      }
    }
  });
});


function fn_convergencewithotherscheme(value) {
  if(value == 'Other_Department') {
    $("#convergence_row_0").show();
    $("#convergence_btn_row_0").show();
  } else {
    $("#convergence_row_0").hide();
    $("#convergence_btn_row_0").hide();
    convergencewithotherscheme_iterate = 0;
    $("#removeallotherdepts").empty();
  }
}

$(document).ready(function(){
  $('#the_convergence_btn').click(function(){
    var ktcontent = $("#kt_content").height();
    $(".content-wrapper").css('min-height',ktcontent+50);
    var after_convergencewithotherscheme_iterate = convergencewithotherscheme_iterate+1;
    $.ajax({
      type:'post',
      dataType:'json',
      url:"{{ route('schemes.department_list') }}",
      data:{'_token':"{{ csrf_token() }}",'dept_id':'1'},
      success:function(response) {
        $.each(response,function(reskey,resval){
          $("#convergence_dept_id_"+after_convergencewithotherscheme_iterate).append('<option value="'+resval.dept_id+'">'+resval.dept_name+'</option>');
        });
      },
      error:function(){
        console.log('deptlist error');
      }
    });
    var add_in_convergence = '<div class="row countallconvergence" id="convergence_row_'+after_convergencewithotherscheme_iterate+'"><div class="col-xl-5"><label></label><select id="convergence_dept_id_'+after_convergencewithotherscheme_iterate+'" name="convergence_dept_id[]" class="form-control"><option value="">Select Department</option></select></div><div class="col-xl-6"><label></label><textarea placeholder="Remarks" name="convergence_text[]" rows="1" class="form-control"></textarea></div><div class="col-xl-1"><p></p><button type="button" id="'+after_convergencewithotherscheme_iterate+'" class="btn btn-sm btn-primary convergence_removeal_class" style="font-weight:bolder;font-size:30px" onclick="fn_remove_the_convergence_div('+after_convergencewithotherscheme_iterate+')">-</button</div></div>';
      $("#removeallotherdepts").append(add_in_convergence);
      convergencewithotherscheme_iterate++;
  });
});

function fn_remove_the_convergence_div(after_conv_id) {
    $("#convergence_row_"+after_conv_id).remove();
    var ktcontent = $("#kt_content").height();
    $(".content-wrapper").css('min-height',ktcontent);
}

$(document).ready(function(){
  $(".finprogressbtn").click(function(){
    var rownumber = $('#thisistbody tr').length;
    var target = 'target';
    var fiyear = 'financial_year';
    var achivement = 'achivement';
    var allocation = 'allocation';
    var expenditure = 'expenditure';
    var units = 'units';
    var nextrownumberzero = 0; //rownumber + 1;

    $(document).ready(function(){
        $('.allowonly2decimal').keypress(function (e) {
            var character = String.fromCharCode(e.keyCode)
            var newValue = this.value + character;
            if (isNaN(newValue) || hasDecimalPlace(newValue, 3)) {
                e.preventDefault();
                return false;
            }
        });
    });

    function hasDecimalPlace(value, x) {
        var pointIndex = value.indexOf('.');
        return  pointIndex >= 0 && pointIndex < value.length - x;
    }

    var count_thisistbody_tr = $("#thisistbody tr").length - 1;
    var entered_finyear = $('#thisistbody .next_financial_progress_year').eq(count_thisistbody_tr).val();
    var entered_units = $('#thisistbody .next_financial_progress_units').eq(count_thisistbody_tr).val();
    var entered_target = $('#thisistbody .next_financial_progress_target').eq(count_thisistbody_tr).val();
    var entered_achievement = $('#thisistbody .next_financial_progress_achivement').eq(count_thisistbody_tr).val();
    var entered_fund = $('#thisistbody .next_financial_progress_allocation').eq(count_thisistbody_tr).val();
    var entered_expenditure = $('#thisistbody .next_financial_progress_expenditure').eq(count_thisistbody_tr).val();

    if(rownumber >= 1) {
      var addtr = '<tr class="finprogresstr_'+rownumber+'"><td class="finprogresstd_'+rownumber+'"><select style="padding:2px" class="form-control next_financial_progress_year next_fin_year_'+rownumber+'" name="financial_progress['+rownumber+']['+fiyear+']"><option value="">Year</option>@foreach($financial_years as $year) <option value="{{ $year }}">{{ $year }}</option> @endforeach</select></td><td class="finprogresstd_'+rownumber+'"><input type="text" class="form-control next_financial_progress_units next_fin_units_'+rownumber+'" name="financial_progress['+rownumber+']['+units+']" value="'+entered_units+'" maxlength="20" /></td><td class="finprogresstd_'+rownumber+'"><input type="text" class="form-control next_financial_progress_target allowonly2decimal next_fin_target_'+rownumber+'" name="financial_progress['+rownumber+']['+target+']" value="'+entered_target+'" /></td><td class="finprogresstd_'+rownumber+'"><input type="text" class="form-control next_financial_progress_achivement allowonly2decimal next_fin_achivement_'+rownumber+'" name="financial_progress['+rownumber+']['+achivement+']" value="'+entered_achievement+'" /></td><td class="finprogresstd_'+rownumber+'"><input type="text" class="form-control next_financial_progress_allocation allowonly2decimal next_fin_allocation_'+rownumber+'" name="financial_progress['+rownumber+']['+allocation+']" value="'+entered_fund+'" /></td><td class="finprogresstd_'+rownumber+'"><input type="text" class="form-control next_financial_progress_expenditure allowonly2decimal next_fin_expenditure_'+rownumber+'" name="financial_progress['+rownumber+']['+expenditure+']" value="'+entered_expenditure+'" /></td><td class="finprogresstd_'+rownumber+'"><button type="button" class="btn btn-primary finprogressbtnremove" onclick="remove_financial_year(this.value)" value="'+rownumber+'" style="padding:2px;width:20px;height:auto;font-weight:bolder;">-</button></td></tr>';
      // $(".finprogresstr_"+rownumber).after(addtr);
      $("#thisistbody tr:last").after(addtr);
    } else {
        // console.log(entered_finyear+' = entered_finyear');
        // @foreach($financial_years as $year) if("{{$year}}" == entered_finyear) { console.log("{{$year}}"); } @endforeach
        // if(entered_finyear == '2015-16') { console.log('hello') }
      var addtr = '<tr class="finprogresstr_'+nextrownumberzero+'"><td class="finprogresstd_'+nextrownumberzero+'"><select style="padding:2px" class="form-control next_financial_progress_year next_fin_year_'+nextrownumberzero+'" name="financial_progress['+nextrownumberzero+']['+fiyear+']"><option value="">Year</option>@foreach($financial_years as $year) <option value="{{ $year }}">{{ $year }}</option> @endforeach</select></td><td class="finprogresstd_'+nextrownumberzero+'"><input type="text" class="form-control next_financial_progress_units next_fin_units_'+nextrownumberzero+'" name="financial_progress['+nextrownumberzero+']['+units+']" maxlength="20"  value="'+entered_units+'"/></td><td class="finprogresstd_'+nextrownumberzero+'"><input type="text" class="form-control next_financial_progress_target allowonly2decimal next_progress_year_'+nextrownumberzero+' next_fin_target_'+nextrownumberzero+'" name="financial_progress['+nextrownumberzero+']['+target+']"  value="'+entered_target+'"/></td><td class="finprogresstd_'+nextrownumberzero+'"><input type="text" class="form-control next_financial_progress_achivement allowonly2decimal next_fin_achivement_'+nextrownumberzero+'" name="financial_progress['+nextrownumberzero+']['+achivement+']"  value="'+entered_achievement+'"/></td><td class="finprogresstd_'+nextrownumberzero+'"><input type="text" class="form-control next_financial_progress_allocation allowonly2decimal next_fin_allocation_'+nextrownumberzero+'" name="financial_progress['+nextrownumberzero+']['+allocation+']"  value="'+entered_fund+'"/></td><td class="finprogresstd_'+nextrownumberzero+'"><input type="text" class="form-control next_financial_progress_expenditure allowonly2decimal next_fin_expenditure_'+nextrownumberzero+'" name="financial_progress['+nextrownumberzero+']['+expenditure+']" value="'+entered_expenditure+'"/></td><td class="finprogresstd_'+nextrownumberzero+'"><button type="button" class="btn btn-primary finprogressbtnremove" onclick="remove_financial_year(this.value)" value="'+nextrownumberzero+'" style="padding:2px;width:20px;height:auto;font-weight:bolder;">-</button></td></tr>';
      // $(".finprogresstr_"+nextrownumberzero).after(addtr);
      $("#thisistbody tr:last").after(addtr);
    }

    var ktcontent = $("#kt_content").height();
    $(".content-wrapper").css('min-height',ktcontent);

  });
});

function remove_financial_year(row) {
  $("table #thisistbody .finprogresstr_"+row).remove();
    var ktcontent = $("#kt_content").height();
    // var ktcontent_remove = ktcontent;
    $(".content-wrapper").css('min-height',ktcontent);
}

$(document).ready(function(){
  $("#addnewindicatorbtn").click(function(){
    var indicator_array_num = $(".getindicator_hod").length;

    var ktcontent = $("#kt_content").height();
    var ktcontent_long = Number(ktcontent) + 100;
    $(".content-wrapper").css('min-height',ktcontent_long);

    indicator_array_num++;
    var major_indicator_hod = 'major_indicator_hod';
    var addindicator = '<tr class="indicator_tr_'+indicator_array_num+'"><th class="borderless" width="100%" id="indicator_id_'+indicator_array_num+'">Indicator '+(indicator_array_num)+'</th></tr><tr class="indicator_tr_'+indicator_array_num+'"><td class="borderless" width="95"><input class="form-control getindicator_hod" id="indicator_hod_id_'+indicator_array_num+'" type="text" name="major_indicator_hod['+indicator_array_num+']['+major_indicator_hod+']" /></td><td class="borderless" width="5%"><button type="button" class="btn btn-primary" value="'+indicator_array_num+'" id="removeindicatorbtn" style="padding:2px;width:20px;height:auto;font-weight:bolder" onclick="removeindicatorrow(this.value)">-</button></td></tr>';
    $("#indicator_table tbody tr:last").after(addindicator);
  });
});

function removeindicatorrow(row) {
    $("#indicator_table tbody .indicator_tr_"+row).remove();
    // var indicator_array_num = $(".getindicator_hod").length;
    // for(var i=0;i<indicator_array_num;i++) {
    //     var rowplus = row + 1;
    //     var ss = $("#indicator_table tbody tr #indicator_id_"+rowplus).eq(0).text(); //text("Indicator "+row-1);
    //     console.log(ss+" = ss = "+i);
    // }
    var ktcontent = $("#kt_content").height();
    $(".content-wrapper").css('min-height',ktcontent);
}

    $(document).ready(function(){
        var ktcontent = $("#kt_content").height();
        $(".content-wrapper").css('min-height',ktcontent);
    });

    $(document).ready(function () {
        $('.numberonly').keypress(function (e) {
            var charCode = (e.which) ? e.which : event.keyCode;
            if(String.fromCharCode(charCode).match(/[^0-9]/g))
            return false;
        });    
    });
</script>

<script type="text/javascript">
    function fin_year(year) {
        if(/^[\d]{4}[\-][\d]{2}$/.test(year)) {
            // console.log('true');
            $("#fin_year_span").text('');
            return true;
        } else {
            // console.log('false');
            $("#fin_year_span").text('Please type Financial Year. e.g. 2020-21');
            $("#commencement_year").focus();
            return false;
        }
    }
    $(document).ready(function(){
        $(".form_scheme_to_submit").submit(function(){
            var count_total_msg = 0;
            var major_obj = $(".major_objective_parent_div div").length;
            // major_obj should be minimum 5
            if(major_obj < 5) {
                $(".maj_obj_error").remove();
                $('.major_objective_parent_div').after("<div class='row maj_obj_error text-red' style='font-size:16px;margin-left:20px'>Please add minimum 5 major Objective</div>");
                var major_obj_after = Number(major_obj)-1;
                // $(".room_fields_"+major_obj_after+' input').focus();
                count_total_msg = 1;
                // return false;
                alert('Warning : Major Objectives are less than 5 !!!!!!!');
            } else {
                $(".maj_obj_error").remove();
                count_total_msg = 0;
            }
            var major_ind = $(".major_indicator_parent_div div").length;
            // major_ind should be minimum 5
            if(major_ind < 5) {
                // $(".maj_ind_error").remove();
                // $("#major_indicator_parent_div").after("<div class='row maj_ind_error text-red' style='font-size:16px;margin-left:20px'>Please add minimum 5 major indicator</div>");
                var major_ind_after = Number(major_ind)-1;
                // $(".indicator_fields_"+major_ind_after+" input").focus();
                count_total_msg = 1;
                // return false;
                alert('Warning : Major Indicators are less than 5 !!!!!!!');
            } else {
                $(".maj_ind_error").remove();
                count_total_msg = 0;
            }
            var major_hod_indicator = $(".indicator_tr_4").length;
            // major_hod_indicator must be 1
            if(major_hod_indicator < 1) {
                // $(".maj_ind_hod_error").remove();
                // $("#indicator_table").after("<div class='row maj_ind_hod_error text-red' style='font-size:16px;margin-left:20px'>Please add minimum 5 major monitoring indicator</div>");
                // $('#indicator_table tr:last td input').focus();
                count_total_msg = 1;
                // return false;
                alert('Warning : Major Monitoring Indicators are less than 5 !!!!!!!');
            } else {
                // $(".maj_ind_hod_error").remove();
                count_total_msg = 0;
            }
            var finprogresstr = $("#kt_datatable #thisistbody tr").length;
            // finprogresstr must be 5
            if(finprogresstr < 5) {
                // $(".finprogresstr_error").remove();
                // $("#kt_datatable").after("<div class='row finprogresstr_error text-red' style='font-size:16px;margin-left:20px'>Please add minimum 5 financial year's data</div>");
                var finprogresstr_after = Number(finprogresstr)-1;
                // $(".finprogresstd_"+finprogresstr_after+" input").focus();
                count_total_msg = 1;
                // return false;
                alert('Warning : There are less than 5 financial year\'s data !!!!!!!');
            } else {
                // $(".finprogresstr_error").remove();
                count_total_msg = 0;
            }
            // alert(count_total_msg);
            return true;
        });
    });

    function fngetdist(theval) {
        $(".thedistrictlist").remove();
        $("#beneficiariesGeoLocal_img").remove();
        $.ajax({
            type:'post',
            dataType:'json',
            url:"{{ route('districts') }}",
            data:{'_token':"{{ csrf_token() }}",'district':theval},
            beforeSend:function() {
                $("#load_gif_img").html('<img id="beneficiariesGeoLocal_img" src="eval/public/loading.gif" style="max-width:200px;max-height:200px">');
            },
            complete:function() {
                $("#beneficiariesGeoLocal_img").remove();
                var ktcontent = $("#kt_content").height();
                $(".content-wrapper").css('min-height',ktcontent);
            },
            success:function(response) {
                $(".thedistrictlist").remove();
                $("#beneficiariesGeoLocal").after("<div class='row thedistrictlist' style='margin:20px;font-size:20px'></div>");
                if(response.districts != '') {

                    var ktcontent = $("#kt_content").height();
                    $(".content-wrapper").css('min-height',ktcontent);

                    $.each(response.districts,function(reskey,resval){
                        $(".thedistrictlist").append("<div class='col-xl-3'><input class='district_length' type='checkbox' style='margin:3px' value='"+resval.dcode+"' name='district_name[]'>"+resval.name_e+"</div>");
                    });
                }
                if(response.talukas != '') {

                    var ktcontent = $("#kt_content").height();
                    $(".content-wrapper").css('min-height',ktcontent);

                    $.each(response.talukas,function(reskey,resval){
                        $(".thedistrictlist").append("<div class='col-xl-3'><input class='taluka_length' type='checkbox' style='margin:3px' value='"+resval.tcode+"' name='taluka_name[]'>"+resval.tname_e+"</div>");
                    });
                }
            },
            error:function() {
                console.log('districts ajax error');
            }
        });
    }

    $(document).ready(function(){
        $('.allowonly2decimal').keypress(function (e) {
            var character = String.fromCharCode(e.keyCode)
            var newValue = this.value + character;
            if (isNaN(newValue) || hasDecimalPlace(newValue, 3)) {
                e.preventDefault();
                return false;
            }
        });
    });

    function hasDecimalPlace(value, x) {
        var pointIndex = value.indexOf('.');
        return  pointIndex >= 0 && pointIndex < value.length - x;
    }

    function fn_show_if_eval(value_val) {
        if(value_val == 'Y') {
            $("#if_eval_yes_div").show();
            var ktcontent = $("#kt_content").height();
            $(".content-wrapper").css('min-height',ktcontent);
        } else {
            $("#if_eval_yes_div").hide();
            var ktcontent = $("#kt_content").height();
            $(".content-wrapper").css('min-height',ktcontent);
        }
    }

    // function checkPosition() {
    $(window).on('load',function() {
        if (window.matchMedia('(max-width: 767px)').matches) {
            // alert('767');
        } else if (window.matchMedia('(max-width: 1280px)').matches) {
            // alert($(window).width());
        } else if (window.matchMedia('(max-width: 1366px)').matches) {
            // alert('1366');
        } else if (window.matchMedia('(max-width: 1600px)').matches) {
            // alert('1600');
        }

        $.ajax({
            type:'post',
            dataType:'json',
            url:"{{ route('schemes.onreload') }}",
            data:{'_token':"{{ csrf_token() }}",'scheme_id':'change','proposal_id':'change'},
            success:function(response) {
                // console.log(response);
            },
            error:function() {
                console.log('ajax error onreload');
            }
        });

    });
    // }

    function getNextSlide(slideid) {
        if(slideid == 1) {
            var draft_id = $("#next_draft_id").val();
            var scheme_id = $("#next_scheme_id").val();
            var next_dept_id = $("#next_dept_id").val();
            var the_convener = $("#con_id").val();
            var form_scheme_name = $("#form_scheme_name").val();
            var next_reference_year = $('#next_reference_year').val();
            var next_reference_year2 = $('#next_reference_year2').val();
            if(next_dept_id != '' && the_convener != '' && form_scheme_name != '' && next_reference_year != '') {
                $("#the_error_html").remove();

                $.ajax({
                    type:'post',
                    dataType:'json',
                    url:"{{ route('deptsec.proposal_update') }}",
                    data:{'_token':"{{ csrf_token() }}",'slide':'first','draft_id':draft_id,'scheme_id':scheme_id,'dept_id':next_dept_id,'convener_name':the_convener,'scheme_name':form_scheme_name,'reference_year':next_reference_year,'reference_year2':next_reference_year2},
                    success:function(response) {
                        console.log(response);
                        $(".otherslides").hide();
                        $(".second_slide").show();
                        $("#previous_btn").val(2).show();
                        $("#next_btn").val(2).show();
                    },
                    error:function() {
                        console.log('add_scheme ajax error');
                    }
                });

            } else {
                $("#the_error_html").remove();
                var the_html = '<div class="row" id="the_error_html"><div class="col-xl-12" style="color:red;font-size:20px">* All Fields are required</div></div>';
                $(".first_slide").append(the_html);
            }
        } else if(slideid == 2) {
            var count_next_major_objectives = $(".next_major_objectives").length;
            var count_values_of_major_objectives = 0;
            for(var i=0;i<count_next_major_objectives;i++) {
                if($(".next_major_objectives").eq(i).val() != '') {
                    count_values_of_major_objectives = i+1;
                }
            }
            var next_major_objective = $("#next_major_objective").val();
            // var second_value_next_major_objectives = $(".next_major_objectives").eq(1).val();
            if(next_major_objective != '' && count_next_major_objectives >= 2 && count_values_of_major_objectives >= 2) {
                $("#the_error_html").remove();
                if(count_next_major_objectives < 5) {
                    alert('There should be atleast 5 Major Objectives');
                }
                var major_objs = [];
                var j = 0;
                for(var i=0;i<count_next_major_objectives;i++) {
                    if($('.room_fields_'+i+' .next_major_objectives').val() != '') {
                        major_objs[j] = {"major_objective":$('.room_fields_'+i+' .next_major_objectives').val()};
                        j++;
                    }
                }

                // [{"major_objective":"major obj 1"},{"major_objective":"major obj 2"}]

                // console.log(major_objs);
                // return false;

                $.ajax({
                    type:'post',
                    dataType:'json',
                    url:"{{ route('deptsec.proposal_update') }}",
                    data:{'_token':"{{ csrf_token() }}",'slide':'second','major_objective':major_objs},
                    success:function(response) {
                        // console.log(response);
                        $(".otherslides").hide();
                        $(".third_slide").show();
                        $("#previous_btn").val(3).show();
                        $("#next_btn").val(3).show();
                    },
                    error:function() {
                        console.log('add_scheme ajax error');
                    }
                });

            } else {
                if(count_values_of_major_objectives < 2) {
                    $("#the_error_html").remove();
                    var the_html = '<div class="row" id="the_error_html"><div class="col-xl-12" style="color:red;font-size:20px">* There are atleast 2 objectives required</div></div>';
                    $(".second_slide").append(the_html);
                } else {
                    $("#the_error_html").remove();
                    var the_html = '<div class="row" id="the_error_html"><div class="col-xl-12" style="color:red;font-size:20px">* All Fields are required</div></div>';
                    $(".second_slide").append(the_html);
                }
            }
        } else if(slideid == 3) {
            var next_major_indicator = $('#next_major_indicator').val();
            var count_next_major_indicators = $(".next_major_indicators").length;
            var count_values_of_major_indicators = 0;
            for(var i=0;i<count_next_major_indicators;i++) {
                if($(".next_major_indicators").eq(i).val() != '') {
                    count_values_of_major_indicators = i+1;
                }
            }
            if(next_major_indicator != '' && count_next_major_indicators >= 2 && count_values_of_major_indicators >= 2) {
                $("#the_error_html").remove();
                if(count_values_of_major_indicators < 5) {
                    alert('There should be atleast 5 Major Indicators');
                }
                var major_indis = [];
                var k = 0;
                for(var i=0;i<count_next_major_indicators;i++) {
                    if($('.indicator_fields_'+i+' .next_major_indicators').val()) {
                        major_indis[k] = {"major_indicator":$('.indicator_fields_'+i+' .next_major_indicators').val()};
                        k++;
                    }
                }

                $.ajax({
                    type:'post',
                    dataType:'json',
                    url:"{{ route('deptsec.proposal_update') }}",
                    data:{'_token':"{{ csrf_token() }}",'slide':'third','major_indicator':major_indis},
                    success:function(response) {
                        // console.log(response);
                        $(".otherslides").hide();
                        $(".fourth_slide").show();
                        $("#previous_btn").val(4).show();
                        $("#next_btn").val(4).show();
                    },
                    error:function() {
                        console.log('add_scheme ajax error');
                    }
                });

            } else {
                if(count_values_of_major_indicators < 2) {
                    $("#the_error_html").remove();
                    var the_html = '<div class="row" id="the_error_html"><div class="col-xl-12" style="color:red;font-size:20px">* There are atleast 2 major indicators required</div></div>';
                    $(".third_slide").append(the_html);
                } else {
                    $("#the_error_html").remove();
                    var the_html = '<div class="row" id="the_error_html"><div class="col-xl-12" style="color:red;font-size:20px">* All Fields are required</div></div>';
                    $(".third_slide").append(the_html);
                }
            }
        } else if(slideid == 4) {
            var implementing_office = $("#implementing_office").val();
            var nodal_id = $('#nodal_id').val();
            var adviser_id = $("#adviser_id").val();
            var state_ratio = $("#state_ratio").val();
            var central_ratio = $("#central_ratio").val();
            var next_scheme_overview = $("#next_scheme_overview").val();
            var next_scheme_objective = $("#next_scheme_objective").val();
            var next_scheme_components = $('#next_scheme_components').val();
            if(Number(state_ratio) + Number(central_ratio) != '100') {
                $("#the_error_html").remove();
                var the_html = '<div class="row" id="the_error_html"><div class="col-xl-12" style="color:red;font-size:20px">* Fund Flow is required </div></div>';
                $(".fourth_slide").append(the_html);
                return false;
            }
            if(implementing_office != '' && nodal_id != '' && adviser_id != '' && state_ratio != '' && central_ratio != '' && next_scheme_overview != '' && next_scheme_objective != '' && next_scheme_components != '') {
                $("#the_error_html").remove();

                $.ajax({
                    type:'post',
                    dataType:'json',
                    url:"{{ route('deptsec.proposal_update') }}",
                    data:{'_token':"{{ csrf_token() }}",'slide':'fourth','implementing_office':implementing_office,'nodal_officer_name':nodal_id,'financial_adviser_name':adviser_id,'state_ratio':state_ratio,'center_ratio':central_ratio,'scheme_overview':next_scheme_overview,'scheme_objective':next_scheme_objective,'sub_scheme':next_scheme_components},
                    success:function(response) {
                        // console.log(response);
                        $(".otherslides").hide();
                        $(".fifth_slide").show();
                        $("#previous_btn").val(5).show();
                        $("#next_btn").val(5).show();
                    },
                    error:function() {
                        console.log('add_scheme ajax error');
                    }
                });

            } else {
                $("#the_error_html").remove();
                var the_html = '<div class="row" id="the_error_html"><div class="col-xl-12" style="color:red;font-size:20px">* All Fields are required</div></div>';
                $(".fourth_slide").append(the_html);
            }
        } else if(slideid == 5) {
            var commencement_year = $('#commencement_year').val();
            var scheme_status = $("input[name='scheme_status']").val();
            var is_sdg = $('input[name="sustainable_goals[]"]:checked').length;
            if(commencement_year != '' && scheme_status != '' && is_sdg > 0) {
                $("#the_error_html").remove();
                var checked_scheme_status = [];
                var i=0;
                $('input[name="sustainable_goals[]"]:checked').each(function() {
                    checked_scheme_status[i] = this.value;
                    i++;
                });

                $.ajax({
                    type:'post',
                    dataType:'json',
                    url:"{{ route('deptsec.proposal_update') }}",
                    data:{'_token':"{{ csrf_token() }}",'slide':'fifth','commencement_year':commencement_year,'scheme_status':scheme_status,'is_sdg':checked_scheme_status},
                    success:function(response) {
                        // console.log(response);
                        $(".otherslides").hide();
                        $(".sixth_slide").show();
                        $("#previous_btn").val(6).show();
                        $("#next_btn").val(6).show();
                    },
                    error:function() {
                        console.log('add_scheme ajax error');
                    }
                });

            } else {
                $("#the_error_html").remove();
                var the_html = '<div class="row" id="the_error_html"><div class="col-xl-12" style="color:red;font-size:20px">* All Fields are required</div></div>';
                $(".fifth_slide").append(the_html);
            }
        } else if(slideid == 6) {
            var next_beneficiary_selection_criteria = $("#next_beneficiary_selection_criteria").val();
            if(next_beneficiary_selection_criteria != '') {
                $("#the_error_html").remove();
                var next_beneficiary_selection_criterias = $(".next_beneficiary_selection_criterias").length;
                var beneficiaries = [];
                var j=0;
                for(var i=0;i<next_beneficiary_selection_criterias;i++) {
                    beneficiaries[j] = {'beneficiary_selection_criteria':$("#beneficiary_selection_div_"+i+" .next_beneficiary_selection_criterias").val()};
                    j++;
                }

                // console.log(beneficiaries);

                $.ajax({
                    type:'post',
                    dataType:'json',
                    url:"{{ route('deptsec.proposal_update') }}",
                    data:{'_token':"{{ csrf_token() }}",'slide':'sixth','scheme_beneficiary_selection_criteria':beneficiaries},
                    success:function(response) {
                        // console.log(response);
                        $(".otherslides").hide();
                        $(".seventh_slide").show();
                        $("#previous_btn").val(7).show();
                        $("#next_btn").val(7).show();
                    },
                    error:function() {
                        console.log('add_scheme ajax error');
                    }
                });

            } else {
                $("#the_error_html").remove();
                var the_html = '<div class="row" id="the_error_html"><div class="col-xl-12" style="color:red;font-size:20px">* All Fields are required</div></div>';
                $(".sixth_slide").append(the_html);
            }
        } else if(slideid == 7) {
            var major_benefit_textarea = $.trim($('#major_benefit_textarea_0').val());
            var major_benefit_length = $(".major_benefit_textareas").length;
            var count_values_of_major_benefit_textareas = 0;
            for(var i=0;i<major_benefit_length;i++) {
                if($(".major_benefit_textareas").eq(i).val() != '') {
                    count_values_of_major_benefit_textareas = i+1;
                }
            }
            if(major_benefit_textarea_0 != '' && major_benefit_length >= 2 && count_values_of_major_benefit_textareas >= 2) {
                $("#the_error_html").remove();

                if(major_benefit_length < 5 || count_values_of_major_benefit_textareas < 5) {
                    alert('There should be 5 major benefits');
                }

                $("#btn_seventh_slide_submit").click();
                return false;

                var major_length = $(".major_benefit_textareas").length;
                var major_text = [];
                for(var i=0;i<major_length;i++) {
                    major_text[i] = {'major_benefits_text':$("#major_benefits_div_"+i+" .major_benefit_textareas").val()};
                }

                // var benefit_to_file = $('.next_major_benefits_file')[0].files;
                // var the_file = benefit_to_file[0];
                // console.log(benefit_to_file);

                $.ajax({
                    type:'post',
                    dataType:'json',
                    url:"{{ route('deptsec.proposal_update') }}",
                    data:{'_token':"{{ csrf_token() }}",'slide':'seventh','major_benefits_text':major_text},
                    success:function(response) {
                        // console.log(response);
                        $(".otherslides").hide();
                        $(".eighth_slide").show();
                        $("#previous_btn").val(8).show();
                        $("#next_btn").val(8).show();
                    },
                    error:function() {
                        console.log('add_scheme ajax error');
                    }
                });

            } else {
                if(major_benefit_length > 1) {
                    $("#the_error_html").remove();
                    var the_html = '<div class="row" id="the_error_html"><div class="col-xl-12" style="color:red;font-size:20px"> * There are atleast 2 Major Benefits required </div></div>';
                    $(".seventh_slide").append(the_html);
                } else {
                    $("#the_error_html").remove();
                    var the_html = '<div class="row" id="the_error_html"><div class="col-xl-12" style="color:red;font-size:20px">* There are atleast 2 Major Benefits required</div></div>';
                    $(".seventh_slide").append(the_html);
                }
            }
        } else if(slideid == 8) {
            var next_scheme_implementing_procedure = $("#next_scheme_implementing_procedure").val();
            var beneficiariesGeoLocal = $('#beneficiariesGeoLocal').val();
            var thedistrictlist = $(".thedistrictlist").length;
            var talukas = [];
            var districts = [];
            if(thedistrictlist > 0) {
                if(beneficiariesGeoLocal == 7) {
                    // var talukas_length = $(".taluka_length").length;
                    var i = 0;
                    $("input[name='taluka_name[]']:checked").each(function() {
                        var ss_taluka = this.value;
                        talukas[i] = ss_taluka.replace(/"/g,'');
                        i++;
                    });
                } else {
                    // var district_length = $(".district_length").length;
                    var i = 0;
                    $("input[name='district_name[]']:checked").each(function() {
                        var ss_district = this.value;
                        districts[i] = ss_district.replace(/"/g,'');
                        i++;
                    });
                }
            }
            var next_otherbeneficiariesGeoLocal = $("#next_otherbeneficiariesGeoLocal").val();
            var geographical_coverage = $("#geographical_coverage")[0].files.length;
            if(next_scheme_implementing_procedure != '' && beneficiariesGeoLocal != '') {
                $("#the_error_html").remove();

                $("#btn_eighth_slide_submit").click();
                return false;

                $.ajax({
                    type:'post',
                    dataType:'json',
                    url:"{{ route('deptsec.proposal_update') }}",
                    data:{'_token':"{{ csrf_token() }}", 'slide':'eighth', 'scheme_implementing_procedure':next_scheme_implementing_procedure, 'beneficiariesGeoLocal':beneficiariesGeoLocal, 'districts':districts,'talukas':talukas, 'otherbeneficiariesGeoLocal':next_otherbeneficiariesGeoLocal},
                    success:function(response) {
                        // console.log(response);
                        $(".otherslides").hide();
                        $(".nineth_slide").show();
                        $("#previous_btn").val(9).show();
                        $("#next_btn").val(9).show();
                    },
                    error:function() {
                        console.log('add_scheme ajax error');
                    }
                });

            } else {
                $("#the_error_html").remove();
                var the_html = '<div class="row" id="the_error_html"><div class="col-xl-12" style="color:red;font-size:20px">* All Fields are required</div></div>';
                $(".eighth_slide").append(the_html);
            }
        } else if(slideid == 9) {
            var next_coverage_beneficiaries_remarks = $("#next_coverage_beneficiaries_remarks").val();
            var beneficiaries_coverage = $("#beneficiaries_coverage")[0].files.length;
            // var implementation_year = $("#implementation_year").val();
            var next_training_capacity_remarks = $("#next_training_capacity_remarks").val();
            var training = $("#training")[0].files.length;
            var next_iec_activities_remarks = $("#next_iec_activities_remarks").val();
            var next_iec = $("#iec")[0].files.length;
            if(next_coverage_beneficiaries_remarks != '' && next_training_capacity_remarks != '' && next_iec_activities_remarks != '') {
                $("#the_error_html").remove();

                $("#btn_nineth_slide_submit").click();
                return false;

                $.ajax({
                    type:'post',
                    dataType:'json',
                    url:"{{ route('deptsec.proposal_update') }}",
                    data:{'_token':"{{ csrf_token() }}", 'slide':'nineth', 'coverage_beneficiaries_remarks':next_coverage_beneficiaries_remarks, 'training_capacity_remarks':next_training_capacity_remarks, 'iec_activities_remarks':next_iec_activities_remarks},
                    success:function(response) {
                        // console.log(response);
                        $(".otherslides").hide();
                        $(".tenth_slide").show();
                        $("#previous_btn").val(10).show();
                        $("#next_btn").val(10).show();
                    },
                    error:function() {
                        console.log('add_scheme ajax error');
                    }
                });

            } else {
                $("#the_error_html").remove();
                var the_html = '<div class="row" id="the_error_html"><div class="col-xl-12" style="color:red;font-size:20px">* All Fields are required</div></div>';
                $(".nineth_slide").append(the_html);
            }
        } else if(slideid == 10) {
            var next_benefit_to = $("#next_benefit_to").val();
            var countallconvergence = $(".countallconvergence").length;
            // var convergence_dept_ids = [];
            // var convergence_text = [];
            var all_convergence = [];
            for(var i=0;i<countallconvergence;i++) {
                // convergence_dept_ids[i] = $('#convergence_row_'+i+' select').val();
                // convergence_text[i] = $("#convergence_row_"+i+' textarea').val();
                if($('#convergence_row_'+i+' select').val() != '') { 
                    all_convergence[i] = {'dept_id':$('#convergence_row_'+i+' select').val(),'dept_remarks':$("#convergence_row_"+i+' textarea').val()};
                }
            }

            // console.log(convergence_dept_ids+" = convergence_dept_ids");
            // console.log(JSON.stringify(all_convergence)+" = all_convergence");
            // return false;
            // var next_convergence_dept_id = $("#next_convergence_dept_id").val();
            // var next_convergence_text = $("#next_convergence_text").val();
            if(next_benefit_to != '') {
                $("#the_error_html").remove();

                $.ajax({
                    type:'post',
                    dataType:'json',
                    url:"{{ route('deptsec.proposal_update') }}",
                    data:{'_token':"{{ csrf_token() }}", 'slide':'tenth', 'benefit_to':next_benefit_to,'all_convergence':all_convergence},
                    success:function(response) {
                        // console.log(response);
                        $(".otherslides").hide();
                        $(".eleventh_slide").show();
                        $("#previous_btn").val(11).show();
                        $("#next_btn").val(11).show();
                    },
                    error:function() {
                        console.log('add_scheme ajax error');
                    }
                });

            } else {
                $("#the_error_html").remove();
                var the_html = '<div class="row" id="the_error_html"><div class="col-xl-12" style="color:red;font-size:20px">* All Fields are required</div></div>';
                $(".tenth_slide").append(the_html);
            }
        } else if(slideid == 11) {
            // var next_gr_files = $(".next_gr_files")[0].files.length;
            // var next_notification_files = $('.next_notification_files')[0].files.length;
            // var next_brochure_files = $(".next_brochure_files")[0].files.length;
            // var next_pamphlets_files = $('.next_pamphlets_files')[0].files.length;
            // var next_otherdetailscenterstate = $(".next_otherdetailscenterstate")[0].files.length;
            // if(next_gr_files > 0 || next_notification_files > 0) {
            //     $("#the_error_html").remove();

                $("#btn_eleventh_slide_submit").click();

                $(".otherslides").hide();
                $(".twelth_slide").show();
                $("#previous_btn").val(12).show();
                $("#next_btn").val(12).show();
            // } else {
            //     $("#the_error_html").remove();
            //     var the_html = '<div class="row" id="the_error_html"><div class="col-xl-12" style="color:red;font-size:20px">* GR or Notification documents are required</div></div>';
            //     $(".eleventh_slide").append(the_html);
            // }
        } else if(slideid == 12) {
            var getindicator_hod1 = $(".getindicator_hod").eq(0).val();
            var getindicator_hod2 = $(".getindicator_hod").eq(1).val();
            var indicator_length = $(".getindicator_hod").length;
            var count_values_of_indicators = 0;
            for(var j=0;j<indicator_length;j++) {
                if($(".getindicator_hod").eq(j).val() != '') {
                    count_values_of_indicators = j+1;
                }
            }
            if(getindicator_hod1 != '' && getindicator_hod2 != '' && indicator_length >= 2 && count_values_of_indicators >= 2) {
                $("#the_error_html").remove();

                var countindicators = $(".getindicator_hod").length;
                var indicator_values = [];
                var j = 0;
                for(var i=0;i<countindicators;i++) {
                    if($("#indicator_hod_id_"+i).val() != '') {
                        // indicator_values[j] = {'major_indicator_hod':$("#indicator_hod_id_"+i).val()};
                        indicator_values[j] = {'major_indicator_hod':$(".getindicator_hod").eq(i).val()};
                        j++;
                    }
                }

                if(count_values_of_indicators < 5) {
                    alert('There should be atleast 5 Major Monitoring Indicators');
                }

                // console.log(JSON.stringify(indicator_values)+' = indicator_values');

                $.ajax({
                    type:'post',
                    dataType:'json',
                    url:"{{ route('deptsec.proposal_update') }}",
                    data:{'_token':"{{ csrf_token() }}", 'slide':'twelth', 'major_indicator_hod':indicator_values},
                    success:function(response) {
                        // console.log(response);
                        $(".otherslides").hide();
                        $(".thirteenth_slide").show();
                        $("#previous_btn").val(13).show();
                        $("#next_btn").val(13).show();
                    },
                    error:function() {
                        console.log('add_scheme ajax error');
                    }
                });

            } else {
                if(indicator_length < 2) {
                    $("#the_error_html").remove();
                    var the_html = '<div class="row" id="the_error_html"><div class="col-xl-12" style="color:red;font-size:20px">* There are atleast 2 indicators required</div></div>';
                    $(".twelth_slide").append(the_html);
                } else {
                    $("#the_error_html").remove();
                    var the_html = '<div class="row" id="the_error_html"><div class="col-xl-12" style="color:red;font-size:20px">* Indicator 1 and indicator 2 are required</div></div>';
                    $(".twelth_slide").append(the_html);
                }
            }
        } else if(slideid == 13) {
            var next_financial_progress_year = $(".next_financial_progress_year").val();
            var next_financial_progress_units = $(".next_financial_progress_units").val();
            var next_financial_progress_target = $(".next_financial_progress_target").val();
            var next_financial_progress_achivement = $(".next_financial_progress_achivement").val();
            var next_financial_progress_allocation = $(".next_financial_progress_allocation").val();
            var next_financial_progress_expenditure = $(".next_financial_progress_expenditure").val();
            var count_tr = $("#thisistbody tr").length;
            if(next_financial_progress_year != '' && next_financial_progress_units != '' && next_financial_progress_target != '' && next_financial_progress_achivement != '' && next_financial_progress_allocation != '' && next_financial_progress_expenditure != '') {

                $("#the_error_html").remove();
                var tr_array = [];
                var count_blank_fields = 0;
                for(var i=0;i<count_tr;i++) {
                    var the_year = $(".next_fin_year_"+i).val();
                    var the_target = $(".next_fin_target_"+i).val();
                    var the_achievement = $(".next_fin_achivement_"+i).val();
                    var the_allocation = $(".next_fin_allocation_"+i).val();
                    var the_expenditure = $(".next_fin_expenditure_"+i).val();
                    var the_units = $(".next_fin_units_"+i).val();
                    if(the_year != '' && the_target != '' && the_achievement != '' && the_allocation != '' && the_expenditure != '' && the_units != '') {
                        tr_array[i] = {'financial_year':$(".next_fin_year_"+i).val(), 'target':$(".next_fin_target_"+i).val(), 'achievement':$(".next_fin_achivement_"+i).val(), 'allocation':$(".next_fin_allocation_"+i).val(), 'expenditure':$(".next_fin_expenditure_"+i).val(), 'units':$(".next_fin_units_"+i).val()};
                    } else {
                        count_blank_fields++;
                    }
                }

                if(count_blank_fields > 0) {
                    $("#the_error_html").remove();
                    var the_html = '<div class="row" id="the_error_html"><div class="col-xl-12" style="color:red;font-size:20px">* Fill all the blank fields</div></div>';
                    $(".thirteenth_slide").append(the_html);
                } else {
                    var confsure = confirm('Are you sure Financial Progress is entered correctly ?');
                    if(confsure == true) {
                        var financial_progress_remarks = $("#financial_progress_remarks").val();
                        $.ajax({
                            type:'post',
                            dataType:'json',
                            url:"{{ route('deptsec.proposal_update') }}",
                            data:{'_token':"{{ csrf_token() }}", 'slide':'thirteenth','tr_array':tr_array, 'financial_progress_remarks':financial_progress_remarks},
                            success:function(response) {
                                $(".otherslides").hide();
                                $(".fourteenth_slide").show();
                                $("#previous_btn").val(14).show();
                                $("#next_btn").val(14).show();
                                $("#next_btn").hide();
                                $('.last_btn').show();

                                var isevaluation = $("input[name='is_evaluation']:checked").attr('id');
                                $("#"+isevaluation).click();

                                var the_html_btn = '<button type="button" class="btn btn-success font-weight-bold text-uppercase px-9 py-4 last_btn" data-wizard-type="action-next" value="1" onclick="finishSlides(13)" id="next_btn"> Finish </button>';
                                $("#div_next_btn").html(the_html_btn);
                            },
                            error:function() {
                                console.log('add_scheme ajax error');
                            }
                        });
                    }
                }
            } else {
                $("#the_error_html").remove();
                var the_html = '<div class="row" id="the_error_html"><div class="col-xl-12" style="color:red;font-size:20px">* All Fields are required</div></div>';
                $(".thirteenth_slide").append(the_html);
            }
        // } else if(slideid == 14) {
        //     $(".otherslides").hide();
        //     $(".fifteenth_slide").show();
        //     $("#previous_btn").val(14).show();
        //     $("#next_btn").val(14).show();
        //     $("#next_btn").hide();
        }
    }

    function getPrevSlide(prevslide) {
        if(prevslide == 2) {
            $(".otherslides").hide();
            $(".first_slide").show();
            $("#previous_btn").val(1).hide();
            $("#next_btn").val(1).show();
        } else if(prevslide == 3) {
            $(".otherslides").hide();
            $(".second_slide").show();
            $("#previous_btn").val(2).show();
            $("#next_btn").val(2).show();
        } else if(prevslide == 4) {
            $(".otherslides").hide();
            $(".third_slide").show();
            $("#previous_btn").val(3).show();
            $("#next_btn").val(3).show();
        } else if(prevslide == 5) {
            $(".otherslides").hide();
            $(".fourth_slide").show();
            $("#previous_btn").val(4).show();
            $("#next_btn").val(4).show();
        } else if(prevslide == 6) {
            $(".otherslides").hide();
            $(".fifth_slide").show();
            $("#previous_btn").val(5).show();
            $("#next_btn").val(5).show();
        } else if(prevslide == 7) {
            $(".otherslides").hide();
            $(".sixth_slide").show();
            $("#previous_btn").val(6).show();
            $("#next_btn").val(6).show();
        } else if(prevslide == 8) {
            $(".otherslides").hide();
            $(".seventh_slide").show();
            $("#previous_btn").val(7).show();
            $("#next_btn").val(7).show();
        } else if(prevslide == 9) {
            $(".otherslides").hide();
            $(".eighth_slide").show();
            $("#previous_btn").val(8).show();
            $("#next_btn").val(8).show();
        } else if(prevslide == 10) {
            $(".otherslides").hide();
            $(".nineth_slide").show();
            $("#previous_btn").val(9).show();
            $("#next_btn").val(9).show();
        } else if(prevslide == 11) {
            $(".otherslides").hide();
            $(".tenth_slide").show();
            $("#previous_btn").val(10).show();
            $("#next_btn").val(10).show();
        } else if(prevslide == 12) {
            $(".otherslides").hide();
            $(".eleventh_slide").show();
            $("#previous_btn").val(11).show();
            $("#next_btn").val(11).show();
        } else if(prevslide == 13) {
            $(".otherslides").hide();
            $(".twelth_slide").show();
            $("#previous_btn").val(12).show();
            $("#next_btn").val(12).show();
        } else if(prevslide == 14) {
            $(".otherslides").hide();
            $(".thirteenth_slide").show();
            $("#previous_btn").val(13).show();
            $("#the_error_html").remove();
            $("#div_next_btn").empty();
            var next_btn = '<button type="button" class="btn btn-primary font-weight-bold text-uppercase px-9 py-4" data-wizard-type="action-next" value="13" onclick="getNextSlide(this.value)" id="next_btn">Next</button>';
            $("#div_next_btn").html(next_btn);
        }
    }

    function finishSlides(fin) {
        if(fin == 13) {

            var is_evaluation = $(".is_evaluation").val();
            var eval_by_whom = $("#eval_by_whom").val();
            var eval_when = $("#eval_when").val();
            var eval_geographical_coverage_beneficiaries = $("#eval_geographical_coverage_beneficiaries").val();
            var eval_number_of_beneficiaries = $("#eval_number_of_beneficiaries").val();
            var eval_major_recommendation = $("#eval_major_recommendation").val();
            
            var eval_if_yes_upload_file = $("#eval_if_yes_upload_file")[0].files;

            if(is_evaluation != '' && eval_by_whom != '' && eval_when != '' && eval_geographical_coverage_beneficiaries != '' && eval_number_of_beneficiaries != '' && eval_major_recommendation != '') {

                $("#btn_fourteenth_slide_submit").click();
                // $.ajax({
                //     type:'post',
                //     dataType:'json',
                //     url:"{{ route('schemes.add_scheme') }}",
                //     data:{'_token':"{{ csrf_token() }}", 'slide':'fourteenth', 'is_evaluation':is_evaluation, 'eval_by_whom':eval_by_whom, 'eval_when':eval_when, 'eval_geographical_coverage_beneficiaries':eval_geographical_coverage_beneficiaries, 'eval_number_of_beneficiaries':eval_number_of_beneficiaries, 'eval_major_recommendation':eval_major_recommendation},
                //     success:function(response) {
                //         $(".otherslides").hide();
                //     },
                //     error:function() {
                //         console.log('add_scheme ajax error');
                //     }
                // });

            }

            // var get_url = "{{ URL('schemes') }}";
            // window.location.href = get_url;
        }
    }

    // function seventh_slide_submit(event,thisval) {
    $(document).ready(function(){

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            }
        });

        $("#seventh_slide_form").submit(function(e){
            event.preventDefault();
            let formData = new FormData(this);
            $.ajax({
                type:'post',
                dataType:'json',
                url:"{{ route('deptsec.proposal_update') }}",
                data:formData,
                contentType:false,
                processData:false,
                success:function(response) {
                    console.log(response);
                    // slideid_seventh_data_submit(response);
                    $(".otherslides").hide();
                    $(".eighth_slide").show();
                    $("#previous_btn").val(8).show();
                    $("#next_btn").val(8).show();
                },
                error:function() {
                    console.log('add_scheme ajax error');
                    // slideid_seventh_data_submit('error');
                }
            });
        });

        $("#eighth_slide_form").submit(function(e){
            e.preventDefault();
            let formDataEighth = new FormData(this);
            $.ajax({
                type:'post',
                dataType:'json',
                url:"{{ route('deptsec.proposal_update') }}",
                data:formDataEighth,
                contentType:false,
                processData:false,
                success:function(response) {
                    console.log(response);
                    // slideid_seventh_data_submit(response);
                    $(".otherslides").hide();
                    $(".nineth_slide").show();
                    $("#previous_btn").val(9).show();
                    $("#next_btn").val(9).show();
                },
                error:function() {
                    console.log('add_scheme ajax error');
                    // slideid_seventh_data_submit('error');
                }
            });
        });

        $("#nineth_slide_form").submit(function(e) {
            e.preventDefault();
            let formDataNineth = new FormData(this);
            $.ajax({
                type:'post',
                dataType:'json',
                url:"{{ route('deptsec.proposal_update') }}",
                data:formDataNineth,
                contentType:false,
                processData:false,
                success:function(response) {
                    $(".otherslides").hide();
                    $(".tenth_slide").show();
                    $("#previous_btn").val(10).show();
                    $("#next_btn").val(10).show();
                },
                error:function() {
                    console.log('add_scheme ajax error');
                }
            });
        });

        $("#eleventh_slide_form").submit(function(e) {
            e.preventDefault();
            let formDataEleventh = new FormData(this);
            $.ajax({
                type:'post',
                dataType:'json',
                url:"{{ route('deptsec.proposal_update') }}",
                data:formDataEleventh,
                contentType:false,
                processData:false,
                success:function(response) {
                    $(".otherslides").hide();
                    $(".twelth_slide").show();
                    $("#previous_btn").val(12).show();
                    $("#next_btn").val(12).show();
                },
                error:function() {
                    console.log('add_scheme ajax error');
                }
            });
        });

        $("#fourteenth_slide_form").submit(function(e) {
            e.preventDefault();
            let formDataFourteenth = new FormData(this);
            $.ajax({
                type:'post',
                dataType:'json',
                url:"{{ route('deptsec.proposal_update') }}",
                data:formDataFourteenth,
                contentType:false,
                processData:false,
                success:function(response) {
                    // $(".otherslides").hide();
                    // $(".twelth_slide").show();
                    // $("#previous_btn").val(12).show();
                    // $("#next_btn").val(12).show();

                    var get_url = "{{ route('deptsec.proposal') }}";
                    window.location.href = get_url;
                },
                error:function() {
                    console.log('add_scheme ajax error');
                }
            });
        });

    });
    // }


</script>


