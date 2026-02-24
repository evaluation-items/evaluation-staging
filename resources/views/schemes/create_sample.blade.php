{{-- @extends('dashboards.implementations.layouts.ia-dash-layout') --}}
@extends(Auth::user()->role == 20  ? 'dashboards.gad-sec.layouts.gadsec-dash-layout'  : 'dashboards.proposal.layouts.sidebar')
{{-- @extends('dashboards.proposal.layouts.sidebar') --}}
@section('title','Create Scheme')
<style>
  .borderless {
    border:0px !important;
  }
  .font-17{
    font-family: ui-monospace !important;
  }
  .font-17{
    font-size:17px;
  }
@media screen and (min-width: 1200px) {
  .container {
    /* max-width: 1370px !important; */
      max-width: 1250px !important; 
  }
}
@media screen and (min-width: 1600px) {
  .container {
    /* max-width: 1570px !important; */
    max-width: 1250px !important; 
  }
}
@media screen and (min-width: 1900px) {
  .container {
    /* max-width: 1870px !important; */
    max-width: 1250px !important; 
  }
}
 .step-container {
        width: 100%;
        display: flex;
        font-weight: bold;
        text-align: center;
        color: #fff;
        font-size: 15px;
    }
    .step-box {
        flex: 1;
        position: relative;
        padding: 12px 0;
        background: #c34a36;
    }
    .step-box.active {
        background: #1c304a;
    }
    .step-box:after {
        content: "";
        position: absolute;
        top: 0;
        right: -35px;
        width: 0;
        height: 0;
        border-top: 25px solid transparent;
        border-bottom: 25px solid transparent;
        border-left: 35px solid #c34a36;
        z-index: 1;
    }
    .step-box.active:after {
        border-left: 35px solid #1c304a;
    }
    .step-box:last-child:after {
        border: none;
    }

    .otherslides { display: none; }
    .active-slide { display: block !important; }
    /* ✅ Checkbox style inside select2 */

/* ✅ Make Select2 box full width */ .select2-container { width: 100% !important; } /* ✅ Selected item text size */ .select2-selection__rendered { font-size: 14px !important; line-height: 28px !important; } /* ✅ Font size in dropdown options */ .select2-results__option { font-size: 14px !important; padding: 8px 12px !important; } /* ✅ Increase checkbox spacing */ .select2-results__option:before { font-size: 14px !important; content: "☐"; position: absolute; left: 8px; } .select2-results__option { padding-left: 30px !important; position: relative; } .select2-results__option[aria-selected=true]:before { content: "☑"; } .select2-container--default .select2-selection--multiple .select2-selection__choice { background-color: #007bff !important; border: 1px solid #000 !important; } .select2-container--default .select2-selection--multiple .select2-selection__choice__remove { color: #fff !important; margin-right: 5px !important; } /* ✅ Selected item background (dropdown list) */ .select2-results__option--selected { background-color: #007bff !important; /* Blue */ color: #fff !important; font-weight: 500; } /* ✅ Remove default purple highlight when active */ .select2-container--default .select2-results__option--highlighted.select2-results__option--selectable { background-color: #0056b3 !important; /* Darker Blue on hover */ color: #fff !important; } /* ✅ Hover effect */ .select2-results__option--highlighted.select2-results__option--selectable { background-color: #0056b3 !important; color: #fff !important; } /* ✅ Checkbox alignment */ .select2-results__option .select2-checkbox { margin-right: 10px; vertical-align: middle; } /* ✅ Tags on top when selected */ .select2-selection__choice { background-color: #007bff !important; color: #fff !important; border-radius: 4px !important; border: none !important; font-size: 14px !important; padding: 3px 6px !important; } /* ✅ Increase selection box height */ .select2-selection--multiple { min-height: 42px !important; padding: 6px !important; border: 1px solid #ced4da !important; } /* ✅ Placeholder style */ .select2-selection__placeholder { font-size: 14px !important; } /* ✅ When dropdown opens - increase area width visually */ .select2-dropdown { min-width: 400px !important; }
.content-wrapper{
  background-color:#e0eaed !important;
}
#the_ratios .form-group label {
    display: block;
    font-weight: 600;
    margin-bottom: 8px;
    font-size: 14px;
}

#the_ratios .form-control {
    height: 38px; /* Standard Bootstrap input height */
    border: 1px solid #ced4da;
    border-radius: 4px;
}

/* Make textarea look like an input for single line UI */
#the_ratios textarea.form-control {
    overflow: hidden;
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
                <div class="container">
                    <!-- ✅ PROGRESS BAR -->
                    <div class="step-container mb-4">
                        <div class="step-box active" id="step1tab">Details of Implementing Offices</div>
                        <div class="step-box" id="step2tab">Directorate of Evaluation (DOE) – Scheme-related</div>
                    </div>

                  {{-- <div class="card card-custom card-transparent">
                    <div class="card-body p-0"> --}}
                      <!--begin: Wizard-->
                      <div>
                        <div class="card card-custom card-shadowless rounded-top-0 font-17">
                          <div class="card-body">
                            @if(session()->has('success'))
                            <div class="alert alert-custom alert-notice alert-light-success fade show" role="alert">
                                <div class="alert-icon"><i class="flaticon-like"></i></div>
                                <div class="alert-text">{{  session()->get('success') }}</div>
                                <div class="alert-close">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true"><i class="ki ki-close"></i></span>
                                    </button>
                                </div>
                              </div>
                              @endif
                              @if(session()->has('state_center_ratio_error'))
                              <div class="alert alert-custom alert-notice alert-light-danger fade show" role="alert">
                                <div class="alert-icon"><i class="flaticon-warning"></i></div>
                                <div class="alert-text"> {{ session()->get('state_center_ratio_error') }}</div>
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
                                <div>
                                  <div class="first_slide otherslides col-xl-12 active-slide" style="display:block;">
                                      <input type="hidden" id="fourteenth_slide_form_csrf_token" value="{{ csrf_token() }}">
                                      <input type="hidden" id="slide" value="first">

                                      <div class="row">
                                          <div class="col-xl-12">
                                              <div class="form-group">
                                                  <label>Whether evaluation of this scheme already done in past?
                                                      (આ યોજનાનું મૂલ્યાંકન અગાઉ થઈ ચૂકેલ છે?) <span class="required_filed">*</span> :
                                                  </label>
                                                  <div class="radio-inline">
                                                      <label class="radio radio-rounded">
                                                          <input type="radio" name="is_evaluation" value="Y" class="is_evaluation" 
                                                              onclick="fn_show_if_eval(this.value)"  {{ old('is_evaluation') === 'Y' ? 'checked' : '' }} />
                                                          <span></span> Yes (હા)
                                                      </label>
                                                      <label class="radio radio-rounded">
                                                          <input type="radio" name="is_evaluation" value="N" class="is_evaluation"
                                                              onclick="fn_show_if_eval(this.value)"  {{ old('is_evaluation') === 'N' ? 'checked' : '' }} />
                                                          <span></span> No (ના)
                                                      </label>
                                                  </div>
                                              </div>
                                          </div>
                                      </div>

                                      <div class="form_eval_yes_div">
                                          <div class="row" id="if_eval_yes_div" style="display:none">
                                              <div class="col-xl-12">
                                                  <div class="form-group">
                                                      <label>By Whom? (કોના દ્વારા?) <span class="required_filed"> * </span> :</label>
                                                      <input type="text" id="eval_by_whom" name="eval_scheme_bywhom" class="form-control pattern" value="{{ old('eval_scheme_bywhom') }}">
                                                  </div>
                                              </div>
                                              <div class="col-xl-12">
                                                  <div class="form-group">
                                                      <label>When? (ક્યારે?) <span class="required_filed"> * </span> :</label>
                                                      <input type="text" id="eval_when" name="eval_scheme_when" class="form-control datepicker" autocomplete="off" placeholder="dd/mm/yyyy" value="{{ old('eval_scheme_when') }}">
                                                  </div>
                                              </div>
                                              <div class="col-xl-12">
                                                  <div class="form-group">
                                                      <label>Geographical coverage of Beneficiaries (સમાવિષ્ટ કરેલ લાભાર્થીઓનો ભૌગોલિક વિસ્તાર) <span class="required_filed"> * </span> :</label>
                                                      <textarea name="eval_scheme_geo_cov_bene" class="form-control word-limit" rows="5" data-max-count="200"  data-warning-count="180"  data-hard-count="230" id="eval_geographical_coverage_beneficiaries">{{ old('eval_scheme_geo_cov_bene') }}</textarea>
                                                       <small class="word-message text-muted"></small>

                                                      {{-- <input type="text" id="eval_geographical_coverage_beneficiaries" name="eval_scheme_geo_cov_bene" class="form-control pattern" value="{{ old('eval_scheme_geo_cov_bene') }}"> --}}
                                                  </div>
                                              </div>
                                              <div class="col-xl-12">
                                                  <div class="form-group">
                                                      <label>No. of beneficiaries in sample (નિદર્શમાં સમાવિષ્ટ લાભાર્થીઓની સંખ્યા)<span class="required_filed"> * </span> :</label>
                                                      <input type="text" id="eval_number_of_beneficiaries" name="eval_scheme_no_of_bene" class="form-control numberonly pattern"  value="{{ old('eval_scheme_no_of_bene') }}">
                                                  </div>
                                              </div>
                                              <div class="col-xl-12">
                                                  <div class="form-group">
                                                      <label>Major recommendations (મુખ્ય ભલામણો.) <span class="required_filed"> * </span> :</label>
                                                      <textarea name="eval_scheme_major_recommendation" class="form-control word-limit" rows="5" data-max-count="200"  data-warning-count="180"  data-hard-count="230" id="eval_major_recommendation">{{ old('eval_scheme_major_recommendation') }}</textarea>
                                                      <small class="word-message text-muted"></small>
                                                      {{-- <input type="text" id="eval_major_recommendation" name="eval_scheme_major_recommendation" class="form-control pattern" value="{{ old('eval_scheme_major_recommendation') }}"> --}}
                                                  </div>
                                              </div>
                                              <div class="col-xl-12">
                                                  <div class="form-group">
                                                      <label>Upload report (અહેવાલ અપલોડ કરવો.) <span class="required_filed"> * </span> :</label>
                                                      <div class="custom-file">
                                                          <input type="file" class="custom-file-input file_type_name" id="eval_if_yes_upload_file" accept=".pdf,.docx,.xls">
                                                          <label class="custom-file-label" for="eval_if_yes_upload_file">Choose File</label>
                                                      </div>
                                                  </div>
                                              </div>
                                          </div>
                                      </div>
                                  </div>

                                    <!--begin::Input-->
                                    <div class="second_slide otherslides" style="display:none;">
                                      <input type="hidden" class="form-control" id="next_dept_id" name="dept_id" value="{{ Auth::user()->dept_id }}" />
                                      {{-- <div class="row ">
                                          <div class="col-xl-12">
                                              <div class="form-group">
                                                  <label>Name of the Department (વિભાગનું નામ) <span class="required_filed"> * </span> : </label>
                                                  <input type="text" id="next_dept_name" value="{{department_name(Auth::user()->dept_id)}}" name="dept_name" readonly class="form-control pattern @error('dept_id') is-invalid @enderror">
                                                  <input type="hidden" class="form-control" id="next_dept_id" name="dept_id" value="{{ Auth::user()->dept_id }}" />
                                              </div>
                                          </div>
                                      </div> --}}
                                      <!--end::Input-->
                                      <div class="row">
                                        <div class="col-xl-4">
                                            <div class="form-group">
                                                <label>Name of the Nodal Officer <br>(નોડલ અધિકારીનું નામ) <span class="required_filed"> * </span> :</label>
                                                 <input type="text" name="convener_name" class="form-control only-text pattern @error('convener_name') is-invalid @enderror" maxlength="100" id="con_id" value="{{ old('convener_name') }}">
                                                  @error('convener_name')
                                                      <div class="text-danger">* {{ $message }}</div>
                                                  @enderror
                                            </div>
                                        </div>
                                        <div class="col-xl-4">
                                          <div class="form-group">
                                            <label>Designation of the Nodal Officer <br>(નોડલ અધિકારીનો હોદ્દો) <span class="required_filed"> * </span> :</label>
                                            <select class="form-control" id="convener_designation" name="convener_designation">
                                              <option value="">Select Designation</option>
                                                @foreach($designations as $designation)
                                                    <option value="{{ $designation }}" {{ old('convener_designation') == $designation ? 'selected' : '' }}>{{ $designation }}</option>
                                                @endforeach
                                            </select>
                                            {{-- <input type="text" name="convener_designation" class="form-control pattern @error('convener_designation') is-invalid @enderror" maxlength="100" id="convener_designation" value=""> --}}
                                                @error('convener_designation')
                                                    <div class="text-danger">* {{ $message }}</div>
                                                @enderror
                                          </div>
                                        </div>
                                        <div class="col-xl-4">
                                          <div class="form-group">
                                            <label style="font-size: 15.8px;">Contact Number of the Nodal Officer <br>(નોડલ અધિકારીનો સંપર્ક નંબર) <span class="required_filed"> * </span> :</label>
                                             <input type="text" name="convener_phone" class="form-control landline pattern @error('convener_phone') is-invalid @enderror"  id="convener_phone" maxlength="11" value="{{ old('convener_phone') }}">
                                                @error('convener_phone')
                                                    <div class="text-danger">* {{ $message }}</div>
                                                @enderror
                                          </div>
                                        </div>
                                      </div>
                                      <div class="row">
                                        <div class="col-xl-6">
                                            <div class="form-group">
                                                <label>Mobile Number of the Nodal Officer (નોડલ અધિકારીનો મોબાઇલ નંબર) <span class="required_filed"> * </span> :</label>
                                                <input type="text" id="convener_mobile" class="form-control mobile_number pattern @error('convener_mobile') is-invalid @enderror" name="convener_mobile" value="{{ old('convener_mobile') }}" maxlength="10" />
                                                @error('convener_mobile')
                                                  <div class="text-danger">* {{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                           <div class="col-xl-6">
                                            <div class="form-group">
                                                <label>Email Address of the Nodal Officer (નોડલ અધિકારીનું ઇમેઇલ એડ્રેસ) <span class="required_filed"> * </span> :</label>
                                                <input type="email" id="convener_email" class="form-control email-input pattern @error('convener_email') is-invalid @enderror" name="convener_email" value="{{ old('convener_email') }}" />
                                                @error('convener_email')
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
                                        <div class="col-xl-6">
                                            <div class="form-group">
                                                <label>Name of the scheme/ Programme to be evaluated <br> (કરવાના થતા મૂલ્યાંકન અભ્યાસ માટેના યોજના/કાર્યક્રમનું નામ) <span class="required_filed"> * </span> :</label>
                                                <input type="text" id="form_scheme_name" class="form-control pattern @error('scheme_name') is-invalid @enderror" name="scheme_name" value="{{ old('scheme_name') }}" />
                                                @error('scheme_name')
                                                  <div class="text-danger">* {{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-xl-6">
                                            <div class="form-group">
                                              <label>Short Name of the scheme/ Programme to be evaluated <br> (મૂલ્યાંકન કરવાની યોજના/કાર્યક્રમનું ટૂંકું નામ)  :</label>
                                              <input type="text" id="form_scheme_short_name" class="form-control pattern" name="scheme_short_name" value="{{ old('scheme_short_name') }}" />
                                                
                                            </div>
                                        </div>
                                      </div>
                                       
                                        <div class="row">
                                          <div class="col-xl-6">
                                            <div class="form-group">
                                              <label>Name of the Financial Adviser <br>(નાણાકીય સલાહકારનું નામ) <span class="required_filed"> * </span> :</label>
                                             <input type="text" name="financial_adviser_name" class="form-control only-text pattern" id="financial_adviser_name" maxlength="100" value="{{old('financial_adviser_name')}}">
                                            </div>
                                         </div>
                                          {{-- <div class="col-xl-4">
                                            <div class="form-group">
                                              <label>Designation of the Financial Adviser <br>(નાણાકીય સલાહકાર નો હોદ્દો) <span class="required_filed"> * </span> :</label>
                                              <input type="text" name="financial_adviser_designation" class="form-control only-text pattern" value="{{old('financial_adviser_designation')}}" id="financial_adviser_designation" maxlength="100">
                                            </div>
                                          </div> --}}
                                          <div class="col-xl-6">
                                            <div class="form-group">
                                              <label>Contact Number of the Financial Adviser <br>(નાણાકીય સલાહકારનો સંપર્ક નંબર) <span class="required_filed"> * </span> :</label>
                                                <input type="text" name="financial_adviser_phone" class="form-control landline" value="{{ old('financial_adviser_phone') }}" id="financial_adviser_phone" maxlength="11">
                                            </div>
                                          </div>
                                        </div>
                                        <div class="row">
                                          <div class="col-xl-6">
                                              <div class="form-group">
                                                  <label>Mobile Number of the Financial Adviser <br>(નાણાકીય સલાહકારનો મોબાઇલ નંબર) <span class="required_filed"> * </span> :</label>
                                                 <input type="text" id="financial_adviser_mobile" class="form-control mobile_number pattern @error('financial_adviser_mobile') is-invalid @enderror" name="financial_adviser_mobile" value="{{ old('financial_adviser_mobile') }}" maxlength="10" />
                                                  @error('financial_adviser_mobile')
                                                    <div class="text-danger">* {{ $message }}</div>
                                                  @enderror
                                              </div>
                                          </div>
                                           <div class="col-xl-6">
                                            <div class="form-group">
                                                <label>Email Address of the Financial Adviser <br>(નાણાકીય સલાહકારનું ઇમેઇલ એડ્રેસ) <span class="required_filed"> * </span> :</label>
                                                <input type="email" id="financial_adviser_email" class="form-control email-input pattern @error('financial_adviser_email') is-invalid @enderror" name="financial_adviser_email" value="{{ old('financial_adviser_email') }}" />
                                                @error('financial_adviser_email')
                                                  <div class="text-danger">* {{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                      </div>
                                      <div class="row">
                                        <div class="col-xl-12">
                                            <div class="form-group">
                                                <label>The Reference year for which the Evaluation study to be done (મૂલ્યાંકન અભ્યાસ માટેનું સંદર્ભ વર્ષ) <span class="required_filed"> * </span> :</label>
                                                <div class="row">
                                                    <div class="col-xl-2">
                                                        <select class="form-control" id="next_reference_year" name="reference_year">
                                                            <option value="">Select Year</option>
                                                            @foreach($financial_years as $fy)
                                                                <option value="{{ $fy }}"
                                                                    {{ old('reference_year') == $fy ? 'selected' : '' }}>
                                                                    {{ $fy }}
                                                                </option>
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
                                                               <option value="{{ $fy }}"
                                                                    {{ old('reference_year2') == $fy ? 'selected' : '' }}>
                                                                    {{ $fy }}
                                                                </option>
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

                                    </div> 
                                        <!-- first slide -->
                                    <div class="third_slide otherslides" style="display: none;">
                                        <div class="row">  
                                            <div class="col-xl-12">
                                                <div class="form-group major_objective_parent_div">
                                                    <label> Major Objective of the Evaluation study (મૂલ્યાંકન અભ્યાસના મુખ્ય હેતુઓ) <span class="required_filed"> * </span> :  <small><b>Maximum 3000 words (વધુમાં વધુ 3000 શબ્દોમાં)</b></small></label><br>
                                                    <div class="room_fields_0">
                                                     <textarea class="form-control word-limit next_major_objectives @error('major_objective') is-invalid @enderror"
                                                          id="next_major_objective_textarea"
                                                          name="major_objective"
                                                          rows="8"
                                                          data-max-count="3000"
                                                          data-warning-count="2800"
                                                          data-hard-count="3200"
                                                      >{{ old('major_objective') }}</textarea>

                                                      <small class="word-message text-muted"></small>

                                                      @error('major_objective')
                                                          <div class="text-danger">{{ $message }}</div>
                                                      @enderror
                                                    </div>
                                                    
                                                </div>
                                            </div>
                                           <div class="col-xl-12">
                                              <!--begin::Input-->
                                              <div class="form-group">
                                                <div class="custom-file">
                                                  <input type="file" class="custom-file-input file_type_name" name="major_objective_file" id="major_objective_file" accept=".pdf,.docx,.xlsx"/>
                                                  <label class="custom-file-label" for="customFile">Choose file</label>
                                                </div>
                                              </div>
                                              <!--end::Input-->
                                            </div>
                                        </div>
                                    </div>
                                    <!-- secnod slide close -->
                                    <div class="fourth_slide otherslides" style="display:none;">
                                        <div class="row">  
                                            <div class="col-xl-12">
                                              <div class="form-group major_indicator_parent_div">
                                                  <label>Major Monitoring Indicators for scheme to be evaluated (મૂલ્યાંકન હાથ ધરવાની થતી યોજનાની  સમીક્ષાના મુખ્ય માપદંડો) <span class="required_filed"> * </span>:</label><br>
                                                  <div class="indicator_fields_0">
                                                      <textarea class="form-control word-limit next_major_indicators @error('major_indicator') is-invalid @enderror"
                                                            id="next_major_indicator_textarea"  name="major_indicator"  rows="8"  data-max-count="3000" data-warning-count="2800"  data-hard-count="3200" >{{ old('major_indicator') }}</textarea>
                                                        <small class="word-message text-muted"></small>
                                                        @error('major_indicator')
                                                            <div class="text-danger">{{ $message }}</div>
                                                        @enderror
                                                      <br>
                                                  </div>
                                              </div>
                                            </div>
                                              <div class="col-xl-12">
                                              <!--begin::Input-->
                                              <div class="form-group">
                                                <div class="custom-file">
                                                  <input type="file" class="custom-file-input file_type_name" name="major_indicator_file" id="major_indicator_file" accept=".pdf,.docx,.xlsx"/>
                                                  <label class="custom-file-label" for="customFile">Choose file</label>
                                                </div>
                                              </div>
                                              <!--end::Input-->
                                            </div>
                                        </div>
                                    </div>
                                     <!--end: Wizard Step 1-->
                                      <div class="fifth_slide otherslides" style="display:none;">
                                        <!--begin: Wizard Step 2-->
                                        {{-- <div class="pb-0" data-wizard-type="step-content"> --}}
                                         
                                            <div class="row">
                                                <div class="col-xl-12">
                                                  <div class="form-group" style="margin-top: 32px;">
                                                    <label>Select of the HOD/Branch. (કચેરી/શાખાનું નામ)<span class="required_filed"> * </span> :</label>
                                                    <select name="implementing_office[]" class="form-control implementing_office" id="implementing_office" multiple="multiple">
                                                        <option value="">Select HOD</option>
                                                        @foreach (department_hod_name(Auth::user()->dept_id) as $key => $item)
                                                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                                                        @endforeach
                                                        <option value="other">Other</option>
                                                    </select>
                                                    <div class="other_val" style="display: none;">
                                                      <label for="name">Name: <span class="required_filed"> * </span></label>
                                                        <div class="d-flex">
                                                          <div class="form-outline" style="width: 100%">
                                                            <input type="text" name="name" class="form-control pattern" value="" id="name" maxlength="100" autocomplete="off">
                                                          </div>
                                                          <button class="btn btn-sm btn-primary add_hod" type="button" style="margin-left: 5px;">ADD</button>
                                                        </div>
                                                   </div>
                                                  </div>
                                                </div>
                                            </div>
                                            <div class="row mt-3">
                                                <div class="col-xl-12">
                                                    <table class="table table-bordered" id="hodTable" style=" display: none;">
                                                        <thead class="bg-light">
                                                            <tr>
                                                                <th>Sr No</th>
                                                                <th>Name</th>
                                                                <th>Email</th>
                                                                <th>Contact No</th>
                                                                <th>Mobile No</th>
                                                                <th width="60">Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody></tbody>
                                                    </table>
                                                </div>
                                            </div>

                                            <div class="row">
                                              <div class="col-xl-6">
                                                  <div class="form-group">
                                                    <label>Name of the Nodal Officer (HOD) (નોડલ અધિકારીનું નામ)<span class="required_filed"> * </span> :</label>
                                                    <input type="text" name="nodal_officer_name" class="form-control only-text pattern" maxlength="100" id="nodal_id" value="{{old('nodal_officer_name')}}">
                                                  </div>
                                              </div>
                                              <div class="col-xl-6">
                                                  <div class="form-group">
                                                    <label>Designation of Nodal Officer(HOD)  (નોડલ અધિકારીનો હોદ્દો)<span class="required_filed"> * </span> </label>
                                                    <input type="text" name="nodal_officer_designation" class="form-control only-text pattern" maxlength="100" id="nodal_designation" value="{{old('nodal_officer_designation')}}">
                                                  </div>
                                              </div>
                                            </div>
                                            <div class="row">
                                              <div class="col-xl-3">
                                                  <div class="form-group">
                                                    <label>Contact Number of the Nodal Officer (HOD) (નોડલ અધિકારીનો સંપર્ક નંબર)<span class="required_filed"> * </span> :</label>
                                                    <input type="text" name="nodal_officer_contact" class="form-control pattern landline" maxlength="11" id="nodal_contact" value="{{old('nodal_officer_contact')}}">
                                                  </div>
                                              </div>
                                               <div class="col-xl-3">
                                                  <div class="form-group">
                                                    <label>Mobile Number of the Nodal Officer (HOD) (નોડલ અધિકારીનો મોબાઇલ નંબર)<span class="required_filed"> * </span> :</label>
                                                     <input type="text" name="nodal_officer_mobile" class="form-control mobile_number pattern" maxlength="10" id="nodal_mobile" value="{{old('nodal_officer_mobile')}}">
                                                  </div>
                                              </div>
                                              <div class="col-xl-6" style="margin-top: 4%;">
                                                  <div class="form-group">
                                                    <label>Email of Nodal Officer(HOD)  (નોડલ અધિકારીનું ઇમેઇલ એડ્રેસ)<span class="required_filed"> * </span> </label>
                                                     <input type="text" name="nodal_officer_email" class="form-control email-input pattern" maxlength="100" id="nodal_email" value="{{old('nodal_officer_email')}}">
                                                  </div>
                                              </div>
                                            </div>
                                             <div class="row" id="the_ratios">
                                                <div class="col-xl-12 d-flex justify-content-between">
                                                    <label>Fund Flow (in %) (યોજના માટેનો નાણાકીય સ્ત્રોત્ર)<span class="required_filed"> * </span> :</label>
                                                </div>
                                                  <div class="col-xl-3 col-md-3">
                                                      <div class="form-group mb-0">
                                                          <label>Central Govt.(%) (કેન્દ્ર: %)</label>
                                                          <input type="text" id="center_ratio" class="form-control" value="{{old('center_ratio')}}">
                                                      </div>
                                                  </div>

                                                  <div class="col-xl-3 col-md-3">
                                                      <div class="form-group mb-0">
                                                          <label>State Govt.(%) (રાજ્ય: %)</label>
                                                          <input type="text" id="state_ratio" class="form-control" value="{{old('state_ratio')}}">
                                                      </div>
                                                  </div>

                                                  <div class="col-xl-3 col-md-3">
                                                      <div class="form-group mb-0">
                                                          <label>Other Govt.(%) (અન્ય: %)</label>
                                                          <input type="text" name="other_ratio" id="other_ratio" class="form-control bg-light" value="{{old('other_ratio')}}" readonly>
                                                      </div>
                                                  </div>
                                                  <div class="col-xl-3 col-md-3">
                                                      <div class="form-group">
                                                          <label>Remarks</label>
                                                          <textarea name="both_ration" class="form-control pattern" id="both_ration" maxlength="200" style="height: 38px; resize: vertical;">{{old('both_ration')}}</textarea>
                                                      </div>
                                                  </div>
                                              </div>
                                            {{-- <div class="row" id="the_ratios">
                                              <div class="col-xl-12"><label>Fund Flow (in %) (યોજના માટેનો નાણાકીય સ્ત્રોત્ર)<span class="required_filed"> * </span> :</label> </div>
                                                    <div class="form-group">
                                                        <div class="row align-items-center">
                                                          <div class="col-xl-6">
                                                                <label>Central Govt.(%) (કેન્દ્ર: %)</label>
                                                                <input type="text" name="center_ratio" class="form-control numberonly state_per pattern" placeholder="Percentage Sponsored by central govt" id="center_ratio" value="{{old('center_ratio')}}">
                                                            </div>
                                                            <div class="col-xl-6">
                                                                <label>State Govt.(%) (રાજ્ય: %)</label>
                                                                <input type="text" name="state_ratio" class="form-control numberonly state_per pattern" placeholder="Percentage Sponsored by state govt" id="state_ratio" value="{{old('state_ratio')}}">
                                                            </div>
                                                          
                                                        </div>
                                                    </div>
                                              
                                                                                  
                                                  <div class="col-xl-6" style="margin-top: -2%;">
                                                      <label>Other:</label>
                                                      <textarea name="both_ration" class="form-control" placeholder="Remarks" id="both_ration">{{old('both_ration')}}</textarea>
                                                  </div>
                                             
                                            </div> --}}

                                          <div class="row">
                                            <div class="col-xl-12">
                                              <label>Overview of the scheme/Background of the scheme (યોજનાની પ્રાથમિક માહિતી/યોજનાનો પરિચય) <span class="required_filed"> * </span> : <small><b>At most 3000 words (વધુમાં વધુ 3000 શબ્દોમાં)</b></small></label>
                                             <textarea class="form-control word-limit pattern" id="next_scheme_overview" name="scheme_overview" rows="8" data-max-count="3000" data-warning-count="2800" data-hard-count="3200">{{ old('scheme_overview') }}</textarea>
                                            <small class="word-message text-muted"></small>
                                            </div>
                                          </div>
                                          <div class="row" style="margin-top:20px;">
                                            <div class="col-xl-12">
                                              <div class="form-group">
                                                <div class="custom-file">
                                                  <input type="file" class="custom-file-input next_scheme_overview_file max_file_size file_type_name" name="next_scheme_overview_file" id="next_scheme_overview_file" accept=".pdf" />
                                                  <label class="custom-file-label" for="next_scheme_overview_file">Choose file (max 5 MB)</label>
                                                </div>
                                              </div>
                                            </div>
                                          </div>
                                          <!--begin::Input-->
                                          <div class="row">
                                              <br>
                                              <div class="col-xl-12">
                                                <label>Objectives of the scheme (યોજનાના હેતુઓ) <span class="required_filed"> * </span> : <small><b>Maximum 3000 words (વધુમાં વધુ 3000 શબ્દોમાં)</b></small></label>
                                                 <textarea class="form-control word-limit pattern" id="next_scheme_objective" name="scheme_objective" rows="8" data-max-count="3000" data-warning-count="2800" data-hard-count="3200">{{ old('scheme_objective') }}</textarea> 
                                                  <small class="word-message text-muted"></small>
                                            </div>
                                          </div>
                                          <div class="row" style="margin-top:20px;">
                                            <div class="col-xl-12">
                                              <div class="form-group">
                                                <div class="custom-file">
                                                  <input type="file" class="custom-file-input scheme_objective_file max_file_size file_type_name" name="scheme_objective_file" id="scheme_objective_file" accept=".pdf" />
                                                  <label class="custom-file-label" for="scheme_objective_file">Choose file (max 5 MB)</label>
                                                </div>
                                              </div>
                                            </div>
                                          </div>
                                        <!--end::Input-->
                                        <!--begin::Input-->
                                        <div class="row">
                                            <br>
                                            <div class="col-xl-12">
                                              <label>Name of Sub-schemes/components (પેટા યોજનાનું નામ અને ઘટકો) <span class="required_filed"> * </span> : <small><b>Maximum 3000 words (વધુમાં વધુ 3000 શબ્દોમાં)</b></small></label>
                                              <textarea class="form-control word-limit pattern" id="next_scheme_components" name="sub_scheme" rows="8" data-max-count="3000" data-warning-count="2800" data-hard-count="3200">{{ old('sub_scheme') }}</textarea>
                                               <small class="word-message text-muted"></small>
                                          </div>
                                        </div>
                                        <div class="row" style="margin-top:20px;">
                                          <div class="col-xl-12">
                                            <div class="form-group">
                                              <div class="custom-file">
                                                <input type="file" class="custom-file-input next_scheme_components_file max_file_size file_type_name" name="next_scheme_components_file" id="next_scheme_components_file" accept=".pdf" />
                                                <label class="custom-file-label" for="next_scheme_components_file">Choose file (max 5 MB)</label>
                                              </div>
                                            </div>
                                          </div>
                                        </div>
                                        <!--end::Input-->
                                        {{-- </div> --}}
                                      </div>
                                        <!-- fourth_slide close -->
                                      <div class="sixth_slide otherslides" style="display:none">
                                            {{-- <div class="pb-5" data-wizard-type="step-content"> --}}
                                                <div class="row ">
                                                  <div class="col-xl-4 col-sm-4">
                                                    <!--begin::Input-->
                                                    <div class="form-group">
                                                      <label>Year of actual commencement of the scheme (યોજનાનું ખરેખર અમલીકરણ શરૂ કર્યા વર્ષ) <span class="required_filed"> * </span> :</label>
                                                      <select name="commencement_year" class="form-control" id="commencement_year">
                                                        <option>Select year</option>
                                                        @foreach ($financial_years as $year_item)
                                                         <option value="{{$year_item}}">{{$year_item}}</option>
                                                        @endforeach
                                                    </select>
                                                      {{-- <input class="form-control" onkeyup="fin_year(this.value)" type="text" name="commencement_year" id="commencement_year" placeholder="0000-00 year"/> --}}
                                                      <span id="fin_year_span" style="color:red"></span>
                                                    </div>
                                                    <!--end::Input-->
                                                  </div>
                                                  <div class="col-xl-1 col-sm-1"></div>
                                                  <div class="col-xl-7 col-sm-7">
                                                    <!--begin::Input-->
                                                    <div class="form-group">
                                                      <label>Present status with coverage of scheme (યોજનાના અમલની વર્તમાન સ્થિતિ)<span class="required_filed"> * </span> :</label>
                                                      <div class="radio-inline">
                                                        <label class="radio radio-rounded">
                                                          <input type="radio" name="scheme_status" value="Y" checked />
                                                            <span></span>
                                                            Operational (કાર્યરત)
                                                        </label>
                                                        <label class="radio radio-rounded">
                                                            <input type="radio" name="scheme_status" value="N" />
                                                            <span></span>
                                                            Non-operational (બિન-કાર્યરત)
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
                                                      <label>Sustainable Development Goals (SDG): Which specific goal(s) does this scheme follow? (સસ્ટેનેબલ ડેવલપમેન્ટ ગોલ (SDG): આ યોજના કયા ચોક્કસ લક્ષ્યાંકોને અનુસરે છે?) <span class="required_filed" style="color: red;"> * </span> : </label>
                                                        {{-- <label>Sustainable Development Goals (SDG) (સસ્ટેનેબલ ડેવલપમેન્ટ ગોલ) <span class="required_filed"> * </span> :</label> --}}
                                                        <div class="row">
                                                            @foreach($goals as $k => $g)
                                                            <div class="col-xl-4">
                                                              <div class="form-group form-check">
                                                                  {{-- Use a unique ID like goal_{{ $g->goal_id }} --}}
                                                                  <input type="checkbox" 
                                                                        name="sustainable_goals[]" 
                                                                        class="form-check-input" 
                                                                        id="goal_{{ $g->goal_id }}" 
                                                                        value="{{ $g->goal_id }}"
                                                                       {{ in_array($g->goal_id, old('sustainable_goals', [])) ? 'checked' : '' }}>
                                                                  
                                                                  {{-- The 'for' attribute must match the input 'id' exactly --}}
                                                                  <label class="form-check-label" for="goal_{{ $g->goal_id }}" style="font-size: 15px;cursor: pointer;">
                                                                      {{ $g->goal_name }} ({{ $g->goal_name_guj }})
                                                                  </label>
                                                              </div>
                                                          </div>
                                                            {{-- <div class="col-xl-4">
                                                              <div class="form-group form-check">
                                                                    <input type="checkbox" name="sustainable_goals[]" class="form-check-input" id="goal_{{ $g->goal_id }}" value="{{ $g->goal_id }}" {{ in_array($g->goal_id, old('sustainable_goals', [])) ? 'checked' : '' }} />
                                                                     <label class="form-check-label" for="goal_{{ $g->goal_id }}" style="font-size: 15px;cursor: pointer;">
                                                                      {{ $g->goal_name }} ({{ $g->goal_name_guj }})
                                                                  </label>
                                                                </div>
                                                                
                                                            </div> --}}
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                    <!--end::Input-->
                                                  </div>
                                                </div>
                                                <!-- row -->
                                            {{-- </div> --}}
                                      </div>
                                    <!-- fifth_slide close -->

                                      <div class="seventh_slide otherslides col-xl-12" style="display:none">
                                          {{-- <div class="pb-5" data-wizard-type="step-content"> --}}
                                              <div class="row ">
                                                <div class="col-xl-12">
                                                  <!--begin::Input-->
                                                  <div class="form-group">
                                                    <label>Beneficiary/Community selection Criteria (લાભાર્થી/સમુદાયની પાત્રતા માટેના માપદંડો) <span class="required_filed"> * </span> :  <small><b>Maximum 3000 words (વધુમાં વધુ 3000 શબ્દોમાં)</b></small></label>
                                                  </div>
                                                  <div class="form-group" id="beneficiary_selection_div_0">
                                                    <textarea class="form-control word-limit next_beneficiary_selection_criterias pattern" id="next_beneficiary_selection_criteria" name="scheme_beneficiary_selection_criteria" rows="8" data-max-count="3000" data-warning-count="2800" data-hard-count="3200">{{old('scheme_beneficiary_selection_criteria')}}</textarea>
                                                    <small class="word-message text-muted"></small>
                                                  </div>
                                                 
                                                </div>
                                              </div>
                                              <div class="row" style="margin-top:20px;">
                                                <div class="col-xl-12">
                                                  <div class="form-group">
                                                    <div class="custom-file">
                                                      <input type="file" class="custom-file-input beneficiary_selection_criteria_file max_file_size file_type_name" name="beneficiary_selection_criteria_file" id="beneficiary_selection_criteria_file" accept=".pdf" />
                                                      <label class="custom-file-label" for="beneficiary_selection_criteria_file">Choose file (max 5 MB)</label>
                                                    </div>
                                                  </div>
                                                </div>
                                              </div>
                                          {{-- </div> --}}
                                      </div>
                                      <!-- sixth_slide close -->
                                        <div class="eighth_slide otherslides col-xl-12" style="display:none">
                                                <div class="row ">
                                                  <div class="col-xl-12">
                                                    <!--begin::Input-->
                                                    <div class="form-group">
                                                      <label>Expected Major Benefits Derived from the Scheme (યોજના ના અપેક્ષિત મુખ્ય લાભો)<span class="required_filed"> * </span> :  <small><b>Maximum 3000 words (વધુમાં વધુ 3000 શબ્દોમાં)</b></small></label>
                                                    </div>
                                                    <!--end::Input-->
                                                    <div class="form-group" id="major_benefits_div_0">
                                                      <div>
                                                        <textarea class="form-control word-limit major_benefit_textareas pattern" name="major_benefits_text" id="major_benefit_textarea_0" rows="8" data-max-count="3000" data-warning-count="2800" data-hard-count="3200">{{old('major_benefits_text')}}</textarea>
                                                        <small class="word-message text-muted"></small>
                                                      </div>
                                                    </div>
                                                  </div>
                                                </div>

                                                <div style="margin-top:20px"></div>
                                                <div class="row">
                                                  <div class="col-xl-12">
                                                    <div class="form-group">
                                                      <div class="custom-file">
                                                        <input type="file" class="custom-file-input next_major_benefits_file file_type_name" name="major_benefits" id="customFile" accept=".pdf,.docx,.xlsx" />
                                                        <label class="custom-file-label" for="customFile">Choose file</label>
                                                      </div>
                                                    </div>
                                                  </div>
                                                </div>
                                                {{-- <button type="submit" id="btn_seventh_slide_submit" style="visibility: hidden;"></button>
                                          </form> --}}
                                        </div>
                                        <!-- seventh_slide close -->

                                          <div class="nineth_slide otherslides col-xl-12" style="display:none;">
                                                <div class="row ">
                                                  <div class="col-xl-12">
                                                    <!--begin::Input-->
                                                    <div class="form-group">
                                                      <label>Implementation procedure of the Scheme (યોજનાની અમલીકરણ માટેની પ્રક્રિયા.)<span class="required_filed"> * </span> : <small><b>Maximum 3000 words (વધુમાં વધુ 3000 શબ્દોમાં)</b></small></label>
                                                      <textarea class="form-control word-limit pattern" id="next_scheme_implementing_procedure" name="scheme_implementing_procedure" rows="8" data-max-count="3000" data-warning-count="2800" data-hard-count="3200">{{old('scheme_implementing_procedure')}}</textarea>
                                                      <small class="word-message text-muted"></small>
                                                    </div>
                                                    <!--end::Input-->
                                                    <div class="custom-file" style="margin:20px 0px">
                                                          <input type="file" class="custom-file-input file_type_name" name="scheme_implement_file" id="scheme_implement_file" accept=".pdf,.docx,.xlsx"/>
                                                          <label class="custom-file-label" for="customFile">Choose file</label>
                                                    </div>
                                                  </div>
                                                </div> 
										
												<div class="row">
                                                    <div class="col-xl-12">
                                                      <!--begin::Input-->
                                                      <div class="form-group">
                                                        <label>Administrative set up for Implementation of the scheme (યોજનાના અમલીકરણ માટેનું વહીવટી માળખું) <span class="required_filed"> * </span> : <small><b>Maximum 3000 words (વધુમાં વધુ 3000 શબ્દોમાં)</b></small></label>
                                                        <textarea class="form-control word-limit pattern" id="implementing_procedure" name="implementing_procedure" rows="8" data-max-count="3000" data-warning-count="2800" data-hard-count="3200">{{old('implementing_procedure')}}</textarea>
                                                        <small class="word-message text-muted"></small>
                                                      </div>
                                                      <!--end::Input-->
                                                        <div class="custom-file" style="margin:20px 0px">
                                                          <input type="file" class="custom-file-input file_type_name" name="implementing_procedure_file" id="implementing_procedure_file" accept=".pdf,.docx,.xlsx"/>
                                                          <label class="custom-file-label" for="customFile">Choose file</label>
                                                        </div>
                                                    </div>
                                                </div> 
										
                                                <div class="row">
                                                  <div class="col-xl-12">
                                                    <label>Geographical Coverage: From State to beneficiaries (રાજ્યકક્ષાથી લઈ લાભાર્થી સુધીનો ભૌગોલિક વ્યાપ) <span class="required_filed"> * </span> : </label>
                                                    <select name="beneficiariesGeoLocal" class="form-control" id="beneficiariesGeoLocal">
                                                        {{-- <option value="">Select Coverage Area</option> --}}
                                                        @foreach($beneficiariesGeoLocal as $key=>$value)
                                                            <option value="{{$value->id}}">{{$value->name}}</option>
                                                        @endforeach
                                                    </select>
                                                    {{-- <select name="taluka_id" class="form-control" id="districtList" style="display: none;">
                                                      <option value="">Select District</option>
                                                    </select>
                                                    <div id="load_gif_img"></div> --}}
                                                    <label style="margin-top:20px">Remarks : </label>
                                                    <!-- <input type="text" name="otherbeneficiariesGeoLocal" placeholder="other Geographical beneficiaries coverage" class="form-control"> -->
                                                    <textarea name="otherbeneficiariesGeoLocal" id="next_otherbeneficiariesGeoLocal" placeholder="other Geographical beneficiaries coverage areas or Remarks" class="form-control" rows="2"></textarea>
                                                    <div></div>
                                                      <div class="custom-file" style="margin-top:20px">
                                                        <input type="file" class="custom-file-input file_type_name" name="geographical_coverage" id="geographical_coverage" accept=".pdf,.docx,.xlsx"/>
                                                        <label class="custom-file-label" for="customFile">Choose file</label>
                                                      </div>
                                                  </div>
                                                </div>
                                              {{-- </div> --}}
                                                {{-- <button type="submit" id="btn_eighth_slide_submit" style="visibility: hidden;"></button> --}}
                                            {{-- </form> --}}
                                          </div>
                                          <!-- eighth_slide close -->
                                          <div class="tenth_slide otherslides col-xl-12" style="display:none;">
                                            <input type="hidden" name="_token" value="{{ csrf_token() }}" id="token">
                                            
                                            <div class="row">  
                                                <div class="col-xl-12">
                                                    <label class="font-weight-bold">Scheme coverage since inception of the scheme (યોજનાની શરૂઆતથી અત્યાર સુધીનો વ્યાપ)</label>
                                                    <hr>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-xl-12">
                                                    <div class="form-group">
                                                        <label>Coverage of Beneficiary/Community (લાભાર્થી/સમુદાયનો સમાવેશ) <span class="required_filed text-danger"> * </span> : <small><b>Maximum 3000 words</b></small></label>
                                                        <textarea name="coverage_beneficiaries_remarks" id="next_coverage_beneficiaries_remarks" class="form-control word-limit pattern" rows="5" data-max-count="3000" data-warning-count="2800" data-hard-count="3200">{{ old('coverage_beneficiaries_remarks') }}</textarea>
                                                        <small class="word-message text-muted"></small>
                                                    </div>
                                                    
                                                    <div class="form-group">
                                                        <div class="custom-file">
                                                            <input type="file" class="custom-file-input file_type_name" name="beneficiaries_coverage" id="beneficiaries_coverage" accept=".pdf,.docx,.xlsx">
                                                            <label class="custom-file-label" for="beneficiaries_coverage">Choose file (Beneficiary Coverage)</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="mt-4"></div>

                                            <div class="row">
                                                <div class="col-xl-12">
                                                    <div class="form-group">
                                                        <label>Training/Capacity building of facilitators (સંબંધિતોની તાલીમ/ક્ષમતા નિર્માણ) <span class="required_filed text-danger"> * </span> : <small><b>Maximum 3000 words</b></small></label>
                                                        <textarea name="training_capacity_remarks" id="next_training_capacity_remarks" class="form-control word-limit pattern" rows="5" data-max-count="3000" data-warning-count="2800" data-hard-count="3200">{{ old('training_capacity_remarks') }}</textarea>
                                                        <small class="word-message text-muted"></small>
                                                    </div>

                                                    <div class="form-group">
                                                        <div class="custom-file">
                                                            <input type="file" class="custom-file-input file_type_name" name="training" id="training" accept=".pdf,.docx,.xlsx">
                                                            <label class="custom-file-label" for="training">Choose file (Training)</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="mt-4"></div>

                                            <div class="row">
                                                <div class="col-xl-12">
                                                    <div class="form-group">
                                                        <label>IEC activities (પ્રચાર પ્રસારની કામગીરી) <span class="required_filed text-danger"> * </span> : <small><b>Maximum 3000 words</b></small></label>
                                                        <textarea name="iec_activities_remarks" id="next_iec_activities_remarks" class="form-control word-limit pattern" rows="5" data-max-count="3000" data-warning-count="2800" data-hard-count="3200">{{ old('iec_activities_remarks') }}</textarea>
                                                        <small class="word-message text-muted"></small>
                                                    </div>

                                                    <div class="form-group">
                                                        <div class="custom-file">
                                                            <input type="file" class="custom-file-input file_type_name" name="iec_file" id="iec" accept=".pdf,.docx,.xlsx">
                                                            <label class="custom-file-label" for="iec">Choose file (IEC File)</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <button type="submit" id="btn_nineth_slide_submit" style="display: none;"></button>
                                        </div>
                                        <!-- nineth_slide close -->

                                          <div class="eleventh_slide otherslides col-xl-12" style="display:none">
                                              <div class="row ">
                                                <div class="col-xl-12">
                                                  <label>Asset/Service creation & its maintenance plan if any (યોજના દ્વારા ઊભી થયેલ સંપત્તિ/સેવા અને તેની જાળવણી, જો હોય તો)</label> 
                                                </div>
                                              </div>
                                              <div class="row">
                                                <div class="col-xl-6">
                                                  <!--begin::Input-->
                                                  <div class="form-group">
                                                    <label>Benefit (લાભ) <span class="required_filed"> * </span> : </label>
                                                    <select name="benefit_to" id="next_benefit_to" class="form-control">
                                                        <option value="">Select Benefit</option>
                                                         <option value="{{ old('benefit_to') == 'Individual' ? 'selected' : '' }}">Individual - વ્યક્તિગત</option>
                                                        <option value="{{ old('benefit_to') == 'Community' ? 'selected' : '' }}">Community - સમુદાય</option>
                                                        <option value="{{ old('benefit_to') == 'Both' ? 'selected' : '' }} ">Both</option>
                                                    </select>
                                                  </div>
                                                  <!--end::Input-->
                                                </div>
                                              </div>
                                              <input type="hidden" name="convergencewithotherscheme" value="Own_Department">
                                              <div class="row countallconvergence" id="convergence_row_0">
                                                <label class="col-xl-12">Convergence with other scheme (અન્ય યોજનાઓ સાથે યોજનાનું જોડાણ)</label>
                                                <div class="col-xl-5">
                                                  <label></label>
                                                  <select name="convergence_dept_id[]" id="next_convergence_dept_id" class="form-control">
                                                    <option value="">Own Department (પોતાના વિભાગ સાથે) </option>
                                                    @foreach($departments as $depts)
                                                      <option value="{{ $depts->dept_id }}">{{ $depts->dept_name }}</option>
                                                    @endforeach
                                                  </select>
                                                </div>
                                                <div class="col-xl-6">
                                                  <label></label>
                                                  <textarea placeholder="Convergence Process" name="convergence_text[]" id="next_convergence_text" rows="1" class="form-control pattern"></textarea>
                                                </div>
                                                <div class="col-xl-1"></div>
                                              </div>
                                              <div id="removeallotherdepts"></div>
                                              <div class="row" id="convergence_btn_row_0" style="margin-bottom:10px">
                                                <div class="col-xl-12">
                                                  <p></p>
                                                  <button type="button" class="btn btn-xs btn-primary" id="the_convergence_btn">+ Add Other Department (અન્ય વિભાગ સાથે)</button>
                                                </div>
                                              </div>
                                          </div>
                                          <!-- tenth_slide close -->


                                          <div class="twelth_slide otherslides col-xl-12" style="display:none">
                                                <div class="row ">
                                                  <div class="col-xl-12">
                                                    <label>Scheme Related all relevant Literature (યોજના સંબંધિત સાહિત્ય)</label>
                                                    <span style="color: #5b6064;margin-left:15px;">You can upload multiple files</span>
                                                  </div>
                                                </div>
                                                <div class="row mt-3">
                                                    <div class="col-xl-10">
                                                        <div class="form-group">
                                                            <label>GR (ઠરાવ) <span class="required_filed"> * </span>:</label>
                                                            <div id="gr_file_wrap">
                                                                <div class="gr-row d-flex align-items-center mb-2">
                                                                    <div class="custom-file">
                                                                      <input type="file" class="custom-file-input file_type_name"  name="gr[]" accept=".pdf,.docx,.xlsx"  />
                                                                      <label class="custom-file-label" for="customFile">Choose file</label>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                        </div>
                                                    </div>

                                                    <div class="col-xl-2">
                                                        <div class="form-group">
                                                            <button type="button" class="btn btn-primary" id="add_gr_file" style="margin-top:40px;">Add</button>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                  <div class="col-xl-6">
                                                    <!--begin::Input-->
                                                    <div class="form-group">
                                                      <label>Notification (જાહેરનામાં) <span class="required_filed"> * </span> : </label>
                                                      <div class="custom-file">
                                                        <input type="file" class="custom-file-input next_notification_files file_type_name" id="notification" name="notification[]" multiple accept=".pdf,.docx,.xlsx"   />
                                                        <label class="custom-file-label" for="customFile">Choose file</label>
                                                      </div>
                                                    </div>
                                                    <!--end::Input-->
                                                  </div>
                                                  <div class="col-xl-6">
                                                    <!--begin::Input-->
                                                    <div class="form-group">
                                                      <label>Brochure (બ્રોશર) : </label>
                                                      <div class="custom-file">
                                                        <input type="file" class="custom-file-input next_brochure_files file_type_name" id="brochure" name="brochure[]" multiple accept=".pdf,.docx,.xlsx"/>
                                                        <label class="custom-file-label" for="customFile">Choose file</label>
                                                      </div>
                                                    </div>
                                                    <!--end::Input-->
                                                  </div>
                                                </div>
                                                <div class="row">
                                                  <div class="col-xl-6">
                                                    <!--begin::Input-->
                                                    <div class="form-group"> <br>
                                                      <label>Pamphlets (પેમ્ફલેટ્સ) : </label>
                                                      <div class="custom-file">
                                                        <input type="file" class="custom-file-input next_pamphlets_files file_type_name" id="pamphlets" name="pamphlets[]" multiple accept=".pdf,.docx,.xlsx"/>
                                                        <label class="custom-file-label" for="customFile">Choose file</label>
                                                      </div>
                                                    </div>
                                                    <!--end::Input-->
                                                  </div>
                                                  <div class="col-xl-6">
                                                    <!--begin::Input-->
                                                    <div class="form-group">
                                                      <label>Other Details of the Scheme (યોજનાને લાગતું અન્ય સાહિત્ય) <br>(Central–State Separate ): </label>
                                                      <div class="custom-file">
                                                        <input type="file" class="custom-file-input next_otherdetailscenterstate file_type_name" id="other_details_center_state" name="otherdetailscenterstate[]" multiple accept=".pdf,.docx,.xlsx"/>
                                                        <label class="custom-file-label" for="customFile">Choose file</label>
                                                      </div>
                                                    </div>
                                                    <!--end::Input-->
                                                  </div>
                                                </div>
                                                <div class="row">
                                                  <div class="col-xl-6">
                                                    <!--begin::Input-->
                                                    <div class="form-group"> <br>
                                                      <label>Beneficiary Filling form( લાભાર્થી ભરવાનું ફોર્મ ) : </label>
                                                      <div class="form-group">
                                                        <div class="row align-items-center">
                                                            <div class="col-xl-3">
                                                                <div class="radio-inline">
                                                                    <label class="radio">
                                                                         <input type="radio" name="beneficiary_filling_form_type" value="0" class="beneficiary_filling_form_type" {{ old('beneficiary_filling_form_type') === '0' ? 'checked' : '' }} />
                                                                        Yes  
                                                                    </label>
                                                                </div>
                                                            </div>
                                                            <div class="col-xl-3">
                                                              <div class="radio-inline">
                                                                  <label class="radio">
                                                                    <input type="radio" name="beneficiary_filling_form_type" value="1" class="beneficiary_filling_form_type" {{ old('beneficiary_filling_form_type') === '1' ? 'checked' : '' }}>
                                                                    No 
                                                                </label>
                                                              </div>
                                                            </div>
                                                        </div>
                                                      </div>
                                                      <div class="custom-file beneficiary_form" style="display: none;">
                                                        <input type="file" class="custom-file-input beneficiary_filling_form file_type_name" id="beneficiary_filling_form" name="beneficiary_filling_form[]" accept=".pdf,.docx,.xlsx"/>
                                                        <label class="custom-file-label" for="customFile">Choose file</label>
                                                      </div>
                                                    </div>
                                                    <!--end::Input-->
                                                  </div>
                                                  
                                                </div>
                                               
                                                {{-- <button type="submit" id="btn_eleventh_slide_submit" style="visibility: hidden;"></button>
                                            </form> --}}
                                          </div>
                                          <!-- eleventh_slide close -->

                                            <div class="thirteenth_slide otherslides col-xl-12" style="display:none;">
                                              <div class="row ">  
                                                <div class="col-xl-12">
                                                  <label>Major Monitoring Indicator at HOD Level (Other than Secretariat Level) (ખાતાના વડાકક્ષાએ મહત્વના ઇન્ડિકેટર નુ મોનીટરીંગ.(સચિવાલય સિવાય)):</label> 
                                                  <textarea name="major_indicator_hod" id="indicator_hod_id_0" class="form-control getindicator_hod word-limit pattern" rows="5" data-max-count="3000" data-warning-count="2800" data-hard-count="3200">{{ old('major_indicator_hod') }}</textarea>
                                                 <small class="word-message text-muted"></small>
                                                </div>
                                              </div>
                                              {{-- <div class="row table-responsive">  
                                                <table class="table" id="indicator_table">
                                                  <tbody>
                                                    <tr><th class="borderless"><label>Indicator</label></th></tr>
                                                     <td class="borderless major_hod_indicator_td" width="95%"><input class="form-control getindicator_hod" id="indicator_hod_id_0" type="text" name="major_indicator_hod" value="{{old('major_indicator_hod')}}" /></td>
                                                      <td class="borderless" width="5%">
                                                        {{-- <button type="button" class="btn btn-primary" id="addnewindicatorbtn" style="padding:2px;width:20px;height:auto;font-weight:bolder">+</button></td> 
                                                  </tbody>
                                                </table>
                                              </div> --}}
                                            </div>
                                            <!-- twelth_slide close -->

                                            <div class="fourteenth_slide  otherslides col-xl-12" style="display:none">
                                                <div class="row ">
                                                  <div class="col-xl-12">
                                                    <label> Financial & Physical Progress  (component wise) of the Last Five Years/Beginning of the Plan (યોજના ની શરૂઆત/છેલ્લા પાંચ વર્ષની વર્ષવાર નાણાકીય અને ભૌતિક પ્રગતિ (કમ્પોનેટ વાઇઝ)) <span class="required_filed"> * </span>:</label>
                                                  </div>
                                                </div>
                                                <div class="row">
                                                  <div class="col-xl-12 table-responsive">
                                                    <table class="table table-bordered" id="kt_datatable" style="margin-top: 13px !important">
                                                      <thead>
                                                        <tr>
                                                          <th rowspan="2" style="font-size: 16px;" class="text-center">Financial Year/નાણાકીય વર્ષ </th>
                                                          <!-- <th rowspan="2" style="font-size: 16px;">Unit</th> -->
                                                          <th colspan="3" class="text-center">Physical/ભૌતિક</th>
                                                          <th colspan="2" class="text-center">Financial/નાણાકીય <small>(Rs in Crores)</small></th>
                                                          {{-- <th colspan="2" class="text-center">Unit of Physical/ભૌતિક</th> --}}
                                                      </tr>
                                                      <tr class="text-center">
                                                          {{-- <th style="font-size: 16px;">Districts/ જિલ્લાઓ</th> --}}
                                                          <th style="font-size: 16px;">Unit - એકમ</th>
                                                          <th style="font-size: 16px;">Target – લક્ષ્યાંક</th>
                                                          <th style="font-size: 16px;">Achievement – સિધ્ધિ</th>
                                                          <th style="font-size: 16px;">Provision– જોગવાઇ</th>
                                                          <th style="font-size: 16px;">Expenditure – ખર્ચ</th>
                                                          
                                                          {{-- <th style="font-size: 16px;">Type - પ્રકાર </th> --}}
                                                          <th style="font-size: 16px;"></th>
                                                      </tr>
                                                      </thead>
                                                      <tbody id="thisistbody">
                                                        @for ($i=0; $i <= 0; $i++)
                                                        <tr class="finprogresstr_{{$i}}">
                                                          <td class="finprogresstd_{{$i}}"><select style="padding:2px" class="form-control next_financial_progress_year next_fin_year_{{$i}}" name="financial_progress[{{ $i }}][financial_year]"><option value="">Year</option>@foreach($financial_years as $year) <option value="{{ $year }}">{{ $year }}</option> @endforeach </select></td>
                                                           <td class="finprogresstd_{{$i}}">
                                                            <select style="padding:2px" class="form-control next_financial_progress_selection next_fin_selection_{{$i}}" id="next_fin_selection_{{$i}}" name="financial_progress[{{ $i }}][selection]">
                                                            <option value="">Select Option</option>
                                                            @foreach($units as $unit_item) 
                                                                <option value="{{ $unit_item->id }}">{{ $unit_item->name }}</option> 
                                                            @endforeach 
                                                            <option value="0">Other</option> 
                                                            </select>
                                                        </td>
                                                          {{-- <td class="finprogresstd_{{$i}}"><input type="text" class="form-control next_financial_progress_units next_fin_units_{{$i}}" name="financial_progress[{{ $i }}][units]" maxlength="20" /></td> --}}
                                                          <td class="finprogresstd_{{$i}}"><input type="text" class="pattern form-control allowonly2decimal next_financial_progress_target next_fin_target_{{$i}}" name="financial_progress[{{ $i }}][target]" /></td>
                                                          <td class="finprogresstd_{{$i}}"><input type="text" class="pattern form-control allowonly2decimal next_financial_progress_achivement next_fin_achivement_{{$i}}" name="financial_progress[{{ $i }}][achivement]" /></td>
                                                          <td class="finprogresstd_{{$i}}"><input type="text" class="pattern form-control allowonly2decimal next_financial_progress_allocation next_fin_allocation_{{$i}}" name="financial_progress[{{ $i }}][allocation]" /></td>
                                                          <td class="finprogresstd_{{$i}}"><input type="text" class="pattern form-control allowonly2decimal next_financial_progress_expenditure next_fin_expenditure_{{$i}}" name="financial_progress[{{ $i }}][expenditure]" /></td>
                                                         
                                                        {{-- <td class="finprogresstd_{{$i}}"><input type="text" class="pattern form-control next_financial_progress_item next_fin_item_{{$i}}" data-id="{{$i}}" name="financial_progress[{{ $i }}][item]"/></td> --}}
                                                          <td class="finprogresstd_{{$i}}"><button type="button" class="btn btn-primary finprogressbtn" value="{{$i}}" style="padding:2px;width:20px;height:auto;font-weight:bolder;">+</button></td>
                                                        </tr>
                                                        @endfor
                                                      </tbody>
                                                    </table>
                                                  </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-xl-12">
                                                        <div class="form-group">
                                                            <label>Physical and Financial Progress Remarks : <small><b> Max 1000 characters </b></small> </label>
                                                           <textarea rows="2" name="financial_progress_remarks" class="form-control" id="financial_progress_remarks" maxlength="1000">{{ old('financial_progress_remarks') }}</textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- thirteenth_slide close -->

                                          <div class="row d-flex justify-content-between border-top mt-5 pt-10" style="width:100%; border-top:1px solid #828385 !important;">
                                            <div class="col-xl-4">
                                              <button type="button" class="btn btn-primary font-weight-bold text-uppercase" data-wizard-type="action-prev" value="1" onclick="getPrevSlide(this.value)" style="display:none;" id="previous_btn">
                                              Previous
                                              </button>
                                            </div>
                                            <div class="col-xl-4">
                                              <label class="nav-item nav-page1 page_no" for="page1-input">
                                                <div>1</div>
                                              </label>
                                            </div>
                                            <div class="col-xl-4" id="div_next_btn" style="text-align: end;">
                                              <button type="button"  class="btn btn-primary font-weight-bold text-uppercase float-right" data-wizard-type="action-next" value="1" 
                                              onclick="getNextSlide(this.value)" id="next_btn">
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
                        </div>
                        <!--end: Wizard Bpdy-->
                        <div class="save_exit_slide">
                          <a class="btn btn-primary font-weight-bold text-uppercase text-center save_item" type="button" data-slide-item="">Save & Exit</a>
                        </div>
                  </div>
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
    const userRole = {{ Auth::user()->role }};
    
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
  $(document).ready(function() {
    // Listen for inputs on Central and State fields specifically
    $('#center_ratio, #state_ratio').on('input', function () {
        var center = parseFloat($('#center_ratio').val()) || 0;
        var state = parseFloat($('#state_ratio').val()) || 0;
        
        // 1. Validate individual input
        var currentInput = parseFloat($(this).val()) || 0;
        if (currentInput < 0 || currentInput > 100) {
            alert('Please enter a valid percentage between 0 and 100.');
            $(this).val(0);
            return;
        }

        // 2. Calculate the sum of the first two
        var subTotal = center + state;

        if (subTotal > 100) {
            alert('The sum of Central and State cannot exceed 100%.');
            $(this).val(0); // Reset the field that broke the rule
            subTotal = (parseFloat($('#center_ratio').val()) || 0) + (parseFloat($('#state_ratio').val()) || 0);
        }

        // 3. Automatically set the remainder in the 'Other' field
        var remaining = 100 - subTotal;
        $('#other_ratio').val(remaining.toFixed(2)); // Use toFixed for clean decimals
        
        updateTotalDisplay();
    });

    // Handle manual changes to 'Other' field
    $('#other_ratio').on('input', function() {
        var center = parseFloat($('#center_ratio').val()) || 0;
        var state = parseFloat($('#state_ratio').val()) || 0;
        var other = parseFloat($(this).val()) || 0;

        if (center + state + other > 100) {
            alert('Total cannot exceed 100%. Adjusting Other to fit.');
            $(this).val(100 - (center + state));
        }
        updateTotalDisplay();
    });

    function updateTotalDisplay() {
        var total = (parseFloat($('#center_ratio').val()) || 0) + 
                    (parseFloat($('#state_ratio').val()) || 0) + 
                    (parseFloat($('#other_ratio').val()) || 0);
        $('#total_display').text(total.toFixed(0));
    }
});

$(document).ready(function() {
  $('.max_file_size').on('change', function () {
        var fileInput = $(this)[0];
        var file = fileInput.files[0];
        var maxSize = 5 * 1024 * 1024; // 5 MB in bytes

        if (file.size > maxSize) {
            alert('File size must be less than 5 MB');
            $(this).val(''); // Clear the file input
        }
  });
    $('#add_gr_file').click(function () {
      let newFileInput = `
          <div class="gr-row d-flex align-items-center mb-2">
              <div class="custom-file">
                  <input type="file" class="custom-file-input file_type_name" name="gr[]" accept=".pdf,.docx,.xlsx" />
                  <label class="custom-file-label">Choose file</label>
              </div>
              <button type="button" class="btn btn-danger btn-sm remove_gr_file ml-2">Remove</button>
          </div>
      `;
      $('#gr_file_wrap').append(newFileInput);
  });

  // Remove file input row
  $(document).on('click', '.remove_gr_file', function () {
    alert('Are you sure you want to remove this file?');
      $(this).closest('.gr-row').remove();
  });
    
      $(document).on('change', '.beneficiary_filling_form_type', function () {
          let selectedValue = $('input[name="beneficiary_filling_form_type"]:checked').val();

          if (selectedValue === '0') { 
              // Yes selected → show file
              $('.beneficiary_form').show();
          } else {
              // No selected → hide and clear file
              $('.beneficiary_form').hide();
              $('.beneficiary_filling_form').val('');
              $('.beneficiary_form .custom-file-label').text('Choose file');
          }
      });

   
      $(document).on('change', '.next_financial_progress_selection', function() {
            // 1. Get the value (e.g., "0" for Other)
            var type = $(this).val();
            
            // 2. Find the row this select belongs to
            var parentRow = $(this).closest('tr');
            
            // 3. Find the 'item' input within the same row
            // Note: Use a class selector instead of searching by ID/Index
            var txtVal = parentRow.find('.next_financial_progress_item'); 

            // 4. Clear value on change
            txtVal.val('');

            // 5. Logic for 'Other' (type == 0)
            if(type == "0") {
                txtVal.removeClass('allowonly2decimal');
            } else {
                txtVal.addClass('allowonly2decimal');
            }
    });
//    $(document).on('change','.next_financial_progress_selection',function() {
//       var classValue = $(this).attr('id');
//       var type = $('#'+classValue).val();
//       var string  = classValue.split("_");
//       var txtVal = $(this).parent('.finprogresstd_'+parseInt(string[3])).next().find('.next_fin_item_'+parseInt(string[3]));
//       txtVal.val('');
//       if(type == 0){
//            if(txtVal.hasClass('allowonly2decimal')){
//               txtVal.removeClass('allowonly2decimal');
//             }
//       }else{
//           txtVal.addClass('allowonly2decimal');
//       }
//    });

     // Add event listener to both input fields
    //  $('.state_per').on('input', function () {
    //   // Get the entered value
    //   var enteredValue = parseFloat($(this).val()) || 0;

    //   // Validate the entered value 
    //   if (enteredValue < 0 || enteredValue > 100) {
    //     alert('Please enter a valid percentage between 0 and 100.');
    //     $(this).val('');
    //     return;
    //   }

    //   // Calculate the remaining percentage
    //   var remainingPercentage = 100 - enteredValue;

    //   // Update the other input field with the remaining percentage
    //   var otherInput = $(this).attr('id') === 'state_ratio' ? $('#central_ratio') : $('#state_ratio');
    //   otherInput.val(remainingPercentage);
    // });

  $.ajaxSetup({
      headers: {
          'X-CSRF-TOKEN': $("meta[name='csrf-token']").attr('content')
      }
  });

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

});

$(document).ready(function() {

$(document).on('change', '.custom-file-input', function () {

         const $input = $(this);
    const files  = this.files;
    if (!files || !files.length) return;

    // ===== CONFIG =====
    const isGR   = $input.attr('name') === 'gr[]';
    const maxMB  = isGR ? 30 : Number($input.data('max')) || 5;
    const allowedExt = ($input.data('ext') || 'pdf,doc,docx')
        .replace(/\s+/g, '')
        .toLowerCase()
        .split(',');

    const badFiles = [];

    // ===== VALIDATION =====
    Array.from(files).forEach(file => {

        const tooBig = file.size > maxMB * 1024 * 1024;

        const lastDot = file.name.lastIndexOf('.');
        const ext = lastDot > -1
            ? file.name.substring(lastDot + 1).toLowerCase()
            : '';

        const singleDot = lastDot > 0 && file.name.indexOf('.') === lastDot;

        if (tooBig || !singleDot || !allowedExt.includes(ext)) {
            badFiles.push(file.name);
        }
    });

    if (badFiles.length) {
        alert(
            `Please choose a valid file (${allowedExt.join(', ')}) up to ${maxMB} MB.\n\nInvalid file(s):\n` +
            badFiles.join('\n')
        );
        $input.val('');
        $input.next('.custom-file-label').text('Choose file');
        return;
    }

    // ===== DISPLAY ORIGINAL NAME (Gujarati safe) =====
    let labelText;
    if (files.length === 1) {
        const originalName = files[0].name;

        // truncate only for UI
        const maxLen = 40;
        labelText = originalName.length > maxLen
            ? originalName.substring(0, 37) + '...'
            : originalName;

        $input.next('.custom-file-label')
            .text(labelText)
            .attr('title', originalName); // full name on hover
    } else {
        labelText = `${files.length} files selected`;
        $input.next('.custom-file-label').text(labelText);
    }
});

  $(document).on('change', '.file_type_name', function () {
      if (this.files && this.files.length > 0) {
          const fileName = this.files[0].name; // ORIGINAL filename
          $(this).next('.custom-file-label').text(fileName);
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
    var maxUploads = 5;
    var currentUploads = 1;

    // $("#btn_add_gr_noti_bro").on("click", function () {
    //   var ktcontent = $("#kt_content").height();
    // $(".content-wrapper").css('min-height',ktcontent+50);
    //     if (currentUploads < maxUploads) {
    //         var clonedRow = $(".file-upload-row:first").clone();
    //         clonedRow.find('input[type="file"]').val(''); // Clear file input value
    //         clonedRow.appendTo("#fileUploadContainer");
    //         currentUploads++;
    //     } else {
    //         alert("You can add a maximum of 5 file upload sections.");
    //     }
    // });

  $('#the_convergence_btn').click(function(){
    // var ktcontent = $("#kt_content").height();
    // $(".content-wrapper").css('min-height',ktcontent+50);
    var after_convergencewithotherscheme_iterate = convergencewithotherscheme_iterate+1;
    $.ajax({
      type:'post',
      dataType:'json',
      url:"{{ route('schemes.department_list') }}",
      data:{'dept_id':'1'},
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
    // var ktcontent = $("#kt_content").height();
    // $(".content-wrapper").css('min-height',ktcontent);
}

// $(document).ready(function(){
//   var nextrownumberzero = 0;
//   $(".finprogressbtn").click(function(){
//     var rownumber = $(this).val();
//     var target = 'target';
//     var fiyear = 'financial_year';
//     var achivement = 'achivement';
//     var allocation = 'allocation';
//     var expenditure = 'expenditure';
//     var units = 'units';
//     var selection = 'selection';
//     var item = 'item';
//     nextrownumberzero++;

//     var count_thisistbody_tr = Number($("#thisistbody tr").length) - 1;
//     var entered_finyear = $('#thisistbody .next_financial_progress_year').eq(count_thisistbody_tr).val();
//    // var entered_units = $('#thisistbody .next_financial_progress_units').eq(count_thisistbody_tr).val();
//     var entered_target = $('#thisistbody .next_financial_progress_target').eq(count_thisistbody_tr).val();
//     var entered_achievement = $('#thisistbody .next_financial_progress_achivement').eq(count_thisistbody_tr).val();
//     var entered_fund = $('#thisistbody .next_financial_progress_allocation').eq(count_thisistbody_tr).val();
//     var entered_expenditure = $('#thisistbody .next_financial_progress_expenditure').eq(count_thisistbody_tr).val();

//     var entered_selection = $('#thisistbody .next_financial_progress_selection').eq(count_thisistbody_tr).val();
//    // var entered_item = $('#thisistbody .next_financial_progress_item').eq(count_thisistbody_tr).val();

//     if(rownumber == 0 && nextrownumberzero == 1) {
    
//          var addtr = '<tr class="finprogresstr_'+nextrownumberzero+'"><td class="finprogresstd_'+nextrownumberzero+'"><select style="padding:2px" class="form-control next_financial_progress_year next_fin_year_'+nextrownumberzero+'" name="financial_progress['+rownumber+']['+fiyear+']"><option value="">Year</option> @foreach($financial_years as $year) <option value="{{ $year }}">{{ $year }}</option>  @endforeach</select></td><td class="finprogresstd_'+nextrownumberzero+'"><select style="padding:2px" class="form-control next_financial_progress_selection next_fin_selection_'+nextrownumberzero+'" id="next_fin_selection_'+nextrownumberzero+'" name="financial_progress['+nextrownumberzero+']['+selection+']"><option value="">Select Option</option>@foreach($units as $unit_item)<option value="{{ $unit_item->id }}">{{ $unit_item->name }}</option> @endforeach<option value="0">Other</option></select></td><td class="finprogresstd_'+nextrownumberzero+'"><input type="text" class="form-control next_financial_progress_target allowonly2decimal next_fin_target_'+nextrownumberzero+'" name="financial_progress['+nextrownumberzero+']['+target+']" value="" /></td><td class="finprogresstd_'+nextrownumberzero+'"><input type="text" class="form-control next_financial_progress_achivement allowonly2decimal next_fin_achivement_'+nextrownumberzero+'" name="financial_progress['+nextrownumberzero+']['+achivement+']" value="" /></td><td class="finprogresstd_'+nextrownumberzero+'"><input type="text" class="form-control next_financial_progress_allocation allowonly2decimal next_fin_allocation_'+nextrownumberzero+'" name="financial_progress['+nextrownumberzero+']['+allocation+']" value="" /></td><td class="finprogresstd_'+nextrownumberzero+'"><input type="text" class="form-control next_financial_progress_expenditure allowonly2decimal next_fin_expenditure_'+nextrownumberzero+'" name="financial_progress['+nextrownumberzero+']['+expenditure+']" value="" /></td><td class="finprogresstd_'+nextrownumberzero+'"><button type="button" class="btn btn-primary finprogressbtnremove" onclick="remove_financial_year(this.value)" value="" style="padding:2px;width:20px;height:auto;font-weight:bolder;">-</button></td></tr>';
//          $("#thisistbody tr:last").after(addtr);
//     } else {
  
//       var addtr = '<tr class="finprogresstr_'+nextrownumberzero+'"><td class="finprogresstd_'+nextrownumberzero+'"><select style="padding:2px" class="form-control next_financial_progress_year next_fin_year_'+nextrownumberzero+'" name="financial_progress['+nextrownumberzero+']['+fiyear+']"><option value="">Year</option>@foreach($financial_years as $year) <option value="{{ $year }}">{{ $year }}</option> @endforeach</select></td><td class="finprogresstd_'+nextrownumberzero+'"><select style="padding:2px" class="form-control next_financial_progress_selection next_fin_selection_'+nextrownumberzero+'" id="next_fin_selection_'+nextrownumberzero+'" name="financial_progress['+nextrownumberzero+']['+selection+']"><option value="">Select Option</option>@foreach($units as $unit_item)<option value="{{ $unit_item->id }}">{{ $unit_item->name }}</option>@endforeach<option value="0">Other</option></select></td><td class="finprogresstd_'+nextrownumberzero+'"><input type="text" class="form-control next_financial_progress_target allowonly2decimal next_progress_year_'+nextrownumberzero+' next_fin_target_'+nextrownumberzero+'" name="financial_progress['+nextrownumberzero+']['+target+']"  value=""/></td><td class="finprogresstd_'+nextrownumberzero+'"><input type="text" class="form-control next_financial_progress_achivement allowonly2decimal next_fin_achivement_'+nextrownumberzero+'" name="financial_progress['+nextrownumberzero+']['+achivement+']"  value=""/></td><td class="finprogresstd_'+nextrownumberzero+'"><input type="text" class="form-control next_financial_progress_allocation allowonly2decimal next_fin_allocation_'+nextrownumberzero+'" name="financial_progress['+nextrownumberzero+']['+allocation+']"  value=""/></td><td class="finprogresstd_'+nextrownumberzero+'"><input type="text" class="form-control next_financial_progress_expenditure allowonly2decimal next_fin_expenditure_'+nextrownumberzero+'" name="financial_progress['+nextrownumberzero+']['+expenditure+']" value=""/></td><td class="finprogresstd_'+nextrownumberzero+'"><button type="button" class="btn btn-primary finprogressbtnremove" onclick="remove_financial_year(this.value)" value="" style="padding:2px;width:20px;height:auto;font-weight:bolder;">-</button></td></tr>';
//       $("#thisistbody tr:last").after(addtr);
//     }

//     var ktcontent = $("#kt_content").height();
//     $(".content-wrapper").css('min-height',ktcontent);

//   });
// });

$(document).ready(function() {
    var nextrownumberzero = 0;

    // Helper to get the starting year from the Reference Year dropdown
    function getReferenceStartYear() {
        var fromYear = $('#next_reference_year').val();
        if (fromYear) {
            return parseInt(fromYear.split('-')[0]);
        }
        return 0;
    }

    // --- FUNCTION: The core filtering logic ---
    function applyYearFilters() {
        var selectedValue = $('#next_reference_year').val();
        if (!selectedValue) return;

        var referenceStart = parseInt(selectedValue.split('-')[0]);

        // 1. Filter Reference Year 2 (To)
        $('#next_reference_year2 option').each(function() {
            var optVal = $(this).val();
            if (optVal != "") {
                var optYear = parseInt(optVal.split('-')[0]);
                if (optYear < referenceStart) {
                    $(this).hide();
                } else {
                    $(this).show();
                }
            }
        });

        // 2. Filter the Financial Progress Table Rows
        $('.next_financial_progress_year').each(function() {
            var dropdown = $(this);
            dropdown.find('option').each(function() {
                var optVal = $(this).val();
                if (optVal != "") {
                    var optStart = parseInt(optVal.split('-')[0]);
                    if (optStart < referenceStart) {
                        $(this).prop('disabled', true).hide();
                    } else {
                        $(this).prop('disabled', false).show();
                    }
                }
            });

            // Reset if current selection is invalid
            var tableSelected = dropdown.val();
            if (tableSelected && parseInt(tableSelected.split('-')[0]) < referenceStart) {
                dropdown.val('');
            }
        });
    }

    // --- EVENT: Run when Reference Year changes ---
    $(document).on('change', '#next_reference_year', function() {
        applyYearFilters();
    });

    // --- INITIALIZE: Run immediately on page load (Create/Edit mode) ---
    applyYearFilters();

    // --- EVENT: Add Row (+) Button ---
    $(document).on('click', ".finprogressbtn", function() {
        var referenceStart = getReferenceStartYear();
        var allYears = @json($financial_years); 
        nextrownumberzero++;

        // Build Year Options for new row
        var yearOptions = '<option value="">Year</option>';
        allYears.forEach(function(year) {
            var yearStart = parseInt(year.split('-')[0]);
            if (yearStart >= referenceStart) {
                yearOptions += '<option value="' + year + '">' + year + '</option>';
            }
        });

        var unitOptions = '<option value="">Select Option</option>@foreach($units as $unit_item)<option value="{{ $unit_item->id }}">{{ $unit_item->name }}</option>@endforeach<option value="0">Other</option>';

        var addtr = `
            <tr class="finprogresstr_${nextrownumberzero}">
                <td><select class="form-control next_financial_progress_year" name="financial_progress[${nextrownumberzero}][financial_year]">${yearOptions}</select></td>
                <td><select class="form-control next_financial_progress_selection" name="financial_progress[${nextrownumberzero}][selection]">${unitOptions}</select></td>
                <td><input type="text" class="form-control next_financial_progress_target  allowonly2decimal" name="financial_progress[${nextrownumberzero}][target]" /></td>
                <td><input type="text" class="form-control next_financial_progress_achivement allowonly2decimal" name="financial_progress[${nextrownumberzero}][achivement]" /></td>
                <td><input type="text" class="form-control next_financial_progress_allocation allowonly2decimal" name="financial_progress[${nextrownumberzero}][allocation]" /></td>
                <td><input type="text" class="form-control next_financial_progress_expenditure allowonly2decimal" name="financial_progress[${nextrownumberzero}][expenditure]" /></td>
                <td><button type="button" class="btn btn-primary finprogressbtnremove" onclick="remove_financial_year(${nextrownumberzero})">-</button></td>
            </tr>`;

        $("#thisistbody tr:last").after(addtr);
    });
});

function hasDecimalPlace(value, x) {
    var pointIndex = value.indexOf('.');
    return  pointIndex >= 0 && pointIndex < value.length - x;
}

function remove_financial_year(row) {
  $("table #thisistbody .finprogresstr_"+row).remove();
    // var ktcontent = $("#kt_content").height();
    // $(".content-wrapper").css('min-height',ktcontent);
}


    $(document).ready(function(){
    
        // var ktcontent = $("#kt_content").height();
        // $(".content-wrapper").css('min-height',ktcontent);
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
            $("#fin_year_span").text('');
            return true;
        } else {
            $("#fin_year_span").text('Please type Financial Year. e.g. 2020-21');
            $("#commencement_year").focus();
            return false;
        }
    }
    $(document).ready(function(){
        var defaultVal = $("#beneficiariesGeoLocal").val();
        console.log(defaultVal);
        if(defaultVal) {
            fngetdist(defaultVal);
        }
         $(".form_scheme_to_submit").submit(function(){
            var count_total_msg = 0;
     
            var finprogresstr = $("#kt_datatable #thisistbody tr").length;
            // finprogresstr must be 5
            if(finprogresstr < 2) {
                var finprogresstr_after = Number(finprogresstr)-1;
                count_total_msg = 1;
                // return false;
                alert('Warning : There are less than 2 financial year\'s data !!!!!!!');
            } else {
                count_total_msg = 0;
            }
            return true;
        });
    });

    function fnSelectAll(checked) {
        $('.district_length').prop('checked', checked);
    }
    // listener to uncheck "Select All" if one district is manually unchecked
        $(document).on('change', '.district_length', function() {
            if ($('.district_length:checked').length === $('.district_length').length) {
                $('#selectAllCheckbox').prop('checked', true);
            } else {
                $('#selectAllCheckbox').prop('checked', false);
            }
        });
    function fngetdist(theval) {
       // 1. Clear previous content immediately
        $(".thedistrictlist").remove();
        $("#beneficiariesGeoLocal_img").remove();
        $('#districtList').hide();
        $.ajax({
            type:'post',
            dataType:'json',
            url:"{{ route('districts') }}",
            data:{'district':theval},
            beforeSend: function() {
                $("#load_gif_img").html('<img id="beneficiariesGeoLocal_img" src="{{ asset("eval/public/loading.gif") }}" style="max-width:200px;max-height:200px">');
            },
            complete: function() {
                $("#beneficiariesGeoLocal_img").remove();
                //$(".content-wrapper").css('min-height', $("#kt_content").height());
            },
            success:function(response) {
                $(".thedistrictlist").remove();
            
                // 3. Create container
                $("#beneficiariesGeoLocal").after("<div class='row thedistrictlist' style='margin:20px; font-size:18px'></div>");

                    if (response.districts && response.districts.length > 0) {
                        
                        // 4. Append Select All
                        $(".thedistrictlist").append(
                            "<div class='col-xl-12 mb-3'>" +
                                "<input type='checkbox' id='selectAllCheckbox' onchange='fnSelectAll(this.checked)'> " +
                                "<label for='selectAllCheckbox' style='cursor:pointer; margin-left:5px'><strong>All</strong></label>" +
                            "</div>"
                        );

                        // 5. Append individual districts
                        $.each(response.districts, function(key, district) {
                            $(".thedistrictlist").append(
                                "<div class='col-xl-3'>" +
                                    "<input class='district_length' type='checkbox' id='dist_" + district.dcode + "' style='margin:3px' value='" + district.dcode + "' name='district_name[]'>" +
                                    "<label for='dist_" + district.dcode + "' style='cursor:pointer;'> " + district.name_e + "</label>" +
                                "</div>"
                            );
                        });

                        // 6. Handle the 'all' case AFTER checkboxes are created
                        if (theval === 'all') {
                            $('#selectAllCheckbox').prop('checked', true);
                            fnSelectAll(true);
                        }
                    }
        
                // if(response.state != '' && response.state != undefined){
                //     $('#districtList').hide();
                //     // Add "All" for states and make it checked
                //     $(".thedistrictlist").append("<div class='col-xl-3'><input type='checkbox' id='selectAllCheckbox' checked onchange='fnSelectAll(this.checked)'> <strong>All</strong></div>");
                    
                //     $.each(response.state, function(reskey, resval){
                //         $(".thedistrictlist").append("<div class='col-xl-3'><input class='state_length' type='checkbox' checked style='margin:3px' value='"+resval.id+"' name='state_name[]'>"+resval.name+"</div>");
                //     });
                // }

              
              //   $(".thedistrictlist").remove();
              //   $("#beneficiariesGeoLocal").after("<div class='row thedistrictlist' style='margin:20px;font-size:20px'></div>");

              //   if(response.districts != '' && response.districts != undefined) {
              //     $('#districtList').css('display','none');
              //     $(".thedistrictlist").append("<div class='col-xl-3'><input type='checkbox' id='selectAllCheckbox' onchange='fnSelectAll(this.checked)'> All</div>");
              //       // Add an "All" checkbox at the beginning of the list
              //       $.each(response.districts, function(reskey, resval){
              //           $(".thedistrictlist").append("<div class='col-xl-3'><input class='district_length' type='checkbox' style='margin:3px' value='"+resval.dcode+"' name='district_name[]'>"+resval.name_e+"</div>");
              //       });
              //   }
              //     if(response.district_list != '' && response.district_list != undefined) {
              //         $('#districtList').css('display','block');
              //         $('#districtList').empty();
              //         if (!$.isEmptyObject(response.district_list)) {
              //             $.each(response.district_list, function(key, value) {   
              //                 $('#districtList').append($("<option></option>").attr("value", value).text(key)); 
              //             });
              //         } else {
              //             $('#districtList').append($("<option></option>").text('Select District'));
              //         }
                    
              //       }
                // if(response.talukas != '') {

                //     var ktcontent = $("#kt_content").height();
                //     $(".content-wrapper").css('min-height',ktcontent);

                //     $.each(response.talukas,function(reskey,resval){
                //         $(".thedistrictlist").append("<div class='col-xl-3'><input class='taluka_length' type='checkbox' style='margin:3px' value='"+resval.tcode+"' name='taluka_name[]'>"+resval.tname_e+"</div>");
                //     });
                // }
                // if(response.state != '' && response.state != undefined){
                //       //console.log('state');
                //       $('#districtList').css('display','none');
                //       $.each(response.state,function(reskey,resval){
                //        $(".thedistrictlist").append("<div class='col-xl-3'><input class='state_length' type='checkbox' style='margin:3px' value='"+resval.id+"' name='state_name[]'>"+resval.name+"</div>");
                //     });
                //   }
              
            },
            error: function() {
                console.log('districts ajax error');
            }
        });
    }

   

    $(document).ready(function(){
        
        $('.allowonly2decimal__test').keypress(function (e) {
            var character = String.fromCharCode(e.keyCode)
            var newValue = this.value + character;
            if (isNaN(newValue) || hasDecimalPlace(newValue, 3)) {
                e.preventDefault();
                return false;
            }
        });

         //fetch taluka
          // $('#districtList').on('change',function(){
          //     var district_code = $(this).val();
          //     if(district_code != ""){
          //       $.ajax({
          //         type:'POST',
          //         dataType:'json',
          //         url:"{{ route('get_taluka') }}",
          //         data:{'_token':"{{ csrf_token() }}",'taluka_dcode':district_code},
          //         beforeSend:function() {
          //             $("#load_gif_img").html('<img id="beneficiariesGeoLocal_img" src="loading.gif" style="max-width:200px;max-height:200px">');
          //         },
          //         complete:function() {
          //             $("#beneficiariesGeoLocal_img").remove();
          //             var ktcontent = $("#kt_content").height();
          //             $(".content-wrapper").css('min-height',ktcontent);
          //         },
          //         success:function(response) {
          //           $(".thedistrictlist").remove();
          //           $('#beneficiariesGeoLocal').after("<div class='row thedistrictlist' style='margin:20px;font-size:20px'></div>");
          //           $(".thedistrictlist").append("<div class='col-xl-3 all_item'><input type='checkbox' id='selectAllCheckbox' onchange='fnSelectAll(this.checked)'> All</div>");

          //           if(response.error != '' && response.error != undefined){
          //               alert(response.error);
          //           }else{
          //               $.each(response.talukas,function(reskey,resval){
          //                   $(".thedistrictlist").append("<div class='col-xl-3'><input class='taluka_length' type='checkbox' style='margin:3px' value='"+resval.tcode+"' name='taluka_name[]'>"+resval.tname_e+"</div>");
          //               });
          //           }
          //         // console.log(response);
          //         },
          //         error:function() {
          //             console.log('districts ajax error');
          //         }
          //       })
          //     }else{
          //       alert('Something went wrong');
          //     }
          // });

          $('.save_item').on('click',function(){
            var save_item = $(this).attr('data-slide-item');
           // console.log(save_item);
              if(save_item != ""){
                $.ajax({
                  type:'POST',
                  url:"{{ route('save_last_item') }}",
                  data:{'_token':"{{ csrf_token() }}",save_item:save_item},
                  success:function(response) {
                   if(response !="" && response.redirectUrl !== undefined){
                       window.location.replace(response.redirectUrl);

                   }else{
                    alert(response.error);
                   }
                  // console.log(response);
                  },
                  error:function() {
                      console.log('districts ajax error');
                  }
                })
              }else{
                alert('Something went wrong');
              }
          })

      // ✅ Initialize Select2 with checkboxes
          $('#implementing_office').select2({
              placeholder: "Select HOD / Office",
              allowClear: true,
              closeOnSelect: false
          });
           // ✅ Show text field when "Other" is selected
         $('#implementing_office').on('change', function () {
            let selected = $(this).val() || [];

            // Show / Hide "Other"
            if (selected.includes("other")) {
                $('.other_val').show();
            } else {
                $('.other_val').hide();
                $('input[name="name"]').val('');
            }

            // Show/Hide Table
            if (selected.length > 0 && !(selected.length === 1 && selected.includes("other"))) {
                $('#hodTable').show();
            } else {
                $('#hodTable').hide();
                $('#hodTable tbody').empty();
                return;
            }

            // Clear previous rows
            $('#hodTable tbody').empty();

            let srNo = 1; // 🔑 Sr No counter

            selected.forEach(function (val) {
                if (val !== "other") {
                    let row_id = "row_" + val.replace(/\s+/g, '_');

                    $('#hodTable tbody').append(`
                        <tr id="${row_id}">
                            <td>
                                <input type="text" class="form-control text-center" value="${srNo}" readonly>
                            </td>
                            <td>
                                <input type="text" name="hod_officer_name[]" class="form-control only-text hod_officer_name" required>
                            </td>
                            <td>
                                <input type="email" name="hod_email[]" class="form-control email-input-td hod_email" required>
                            </td>
                            <td>
                                <input type="text" name="implementing_office_contact[]" class="form-control implementing_office_contact" maxlength="12" required>
                            </td>
                            <td>
                                <input type="text" name="hod_mobile[]" class="form-control hod_mobile" maxlength="10" required>
                            </td>
                            <td>
                                <button type="button" class="btn btn-danger btn-sm removeRow">X</button>
                            </td>
                        </tr>
                    `);

                    srNo++; // 🔼 increment
                }
            });
        });


          // ✅ Remove row + remove from dropdown too
          $(document).on('click', '.removeRow', function () {
              let row = $(this).closest('tr');
              let name = row.find('td:first input').val();

              row.remove();

              let selected = $('#implementing_office').val();
              selected = selected.filter(v => v !== name);
              $('#implementing_office').val(selected).trigger('change');
          });


          $('.add_hod').on('click',function(e){
            e.preventDefault();
            var Name = $('#name').val();
            var dept_id = "{{Auth::user()->dept_id}}";
            if(Name != ""){
                const url = "{{ route('department_hod_param.store', ':param') }}".replace(':param', 1);
              $.ajax({
                type: 'POST',
                url: url,
                data:{'name':Name,'dept_id':dept_id},
                headers: {
                  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (response) {
                  if(response.success == true){
                  let newOption = new Option(Name, Name, true, true);

                          $('#implementing_office')
                              .append(newOption)
                              .trigger('change.select2'); // ✅ Refresh UI with checkboxes

                          $('#name').val('');  // Clear input
                          $('.other_val').hide(); // Hide Other input box


                    // $('.implementing_office').append($("<option></option>").attr("value", Name).text(Name).attr("selected", "selected"));
                    // $('.other_val').css('display','none');
                  }else{
                    alert(response.error);
                  }
                },
                error: function () {
                    console.log('Error: Unable to delete department.');
                }
              });
    }
  })
    });

    function hasDecimalPlace(value, x) {
        var pointIndex = value.indexOf('.');
        return  pointIndex >= 0 && pointIndex < value.length - x;
    }

    function fn_show_if_eval(value_val) {
        if(value_val == 'Y') {
            $("#if_eval_yes_div").show();
            var eval_yes_div_length = $("#fourteenth_slide_form #if_eval_yes_div").length;
            if(eval_yes_div_length == 0) {
                $("#fourteenth_slide_form .form_eval_yes_div").html('');
                var eval_yes_data = $("#send_eval_yes_div").html();
                $("#fourteenth_slide_form .form_eval_yes_div").append(eval_yes_data);
            }
            // var ktcontent = $("#kt_content").height();
            // $(".content-wrapper").css('min-height',ktcontent);
        } else {
            $("#if_eval_yes_div").hide();
            $("#send_eval_yes_div").html('');
            var eval_yes_div = $(".form_eval_yes_div").html();
            $("#send_eval_yes_div").html(eval_yes_div).hide();
            $("#fourteenth_slide_form .form_eval_yes_div").html('');
            // var ktcontent = $("#kt_content").height();
            // $(".content-wrapper").css('min-height',ktcontent);
        }
    }

    // function checkPosition() {
    $(window).on('load',function() {
        if (window.matchMedia('(max-width: 767px)').matches) {
        } else if (window.matchMedia('(max-width: 1280px)').matches) {
        } else if (window.matchMedia('(max-width: 1366px)').matches) {
        } else if (window.matchMedia('(max-width: 1600px)').matches) {
        }
		
          $.ajaxSetup({
            headers:{
              'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
            }
          });

        $.ajax({
            type:'post',
            dataType:'json',
            url:"{{ route('schemes.onreload') }}",
            data:{'scheme_id':'change','proposal_id':'change'},
            success:function(response) {
            },
            error:function() {
                console.log('ajax error onreload');
            }
        });

    });
    // }


var count = 0;
function countIncrease(slideid){
  let nextSlide = parseInt(slideid) + 1;
  count = $('.page_no div').html(nextSlide);
  $('.save_item').attr('data-slide-item',nextSlide);
      return nextSlide;
}
  function updateStepTitle(slideNo) {
      let titles = {
          3: 'Major Objectives Details',
          4: 'Major Indicator Details',
          5: 'HOD / Branch Details',
          6: 'Commencement of the Scheme Details',
          7: 'Beneficiary / Community Criteria Details',
          8: 'Major Benefits Derived From the Scheme',
          9: 'Implementation Procedures of the Scheme',
          10: 'Scheme Coverage Details',
          11: 'Asset / Service Creation Details',
          12: 'Relevant Literature Details',
          13: 'Major Monitoring Indicators at HOD Level',
          14: 'Financial and Physical Progress of Last Five Years'
      };

      if (titles[slideNo]) {
          // This will result in: "3. Major Objectives Details"
          $('#step2tab').html(slideNo + '. ' + titles[slideNo]);
      } else {
          $('#step2tab').html('Directorate of Evaluation (DOE) – Scheme-related');
      }
  }

    function getNextSlide(slideid) {
      var draft_id = $("#next_draft_id").val();
      var scheme_id = $("#next_scheme_id").val();
      if (slideid == 1) {
         let nextSlide = countIncrease(slideid);

        updateStepTitle(nextSlide);
          // 🔹 Slide 1 fields
          var is_evaluation = $("input[name='is_evaluation']:checked").val();
          var eval_by_whom = $("#eval_by_whom").val();
          var eval_when = $("#eval_when").val();
          var eval_geo = $("#eval_geographical_coverage_beneficiaries").val();
          var eval_number = $("#eval_number_of_beneficiaries").val();
          var eval_major = $("#eval_major_recommendation").val();
          var eval_file = $("#eval_if_yes_upload_file")[0].files[0];

          // 🔹 Validation for slide 1
          if (!is_evaluation) {
              showError("* Please select whether evaluation is done or not");
              return;
          }

          if (is_evaluation === 'Y') {
              if (eval_by_whom === '' || eval_when === '' || eval_geo === '' || eval_number === '' || eval_major === '' || !eval_file) {
                  showError("* All evaluation fields are required");
                  return;
              }
          }
          // ✅ Move to Slide 2
          $(".otherslides").hide();
          $(".second_slide").show();
          $("#previous_btn").val(2).show();
          $("#next_btn").val(2).show();
          $('.page_no div').html(2);

          $('.first_slide').removeClass("active-slide");
          $('.second_slide').addClass("active-slide");

          $("#step1tab").removeClass("active");
          $("#step2tab").addClass("active");
        

      } else if (slideid == 2) {
         let nextSlide = countIncrease(slideid);
          updateStepTitle(nextSlide);
            // 🔹 Slide 1 fields (again for combined submission)
            var is_evaluation = $("input[name='is_evaluation']:checked").val();
            var eval_by_whom = $("#eval_by_whom").val();
            var eval_when = $("#eval_when").val();
            var eval_geo = $("#eval_geographical_coverage_beneficiaries").val();
            var eval_number = $("#eval_number_of_beneficiaries").val();
            var eval_major = $("#eval_major_recommendation").val();
            var eval_file = $("#eval_if_yes_upload_file")[0].files[0];

            // 🔹 Slide 2 fields
            var next_dept_id = $("#next_dept_id").val();
            var the_convener = $("#con_id").val();
            var convener_designation = $('#convener_designation').val();
            var convener_phone = $('#convener_phone').val();
            var convener_mobile = $('#convener_mobile').val();
            var convener_email = $('#convener_email').val();
            var form_scheme_name = $("#form_scheme_name").val();
            var form_scheme_short_name = $("#form_scheme_short_name").val();
            var next_reference_year = $('#next_reference_year').val();
            var next_reference_year2 = $('#next_reference_year2').val();
            var financial_adviser_name = $("#financial_adviser_name").val();
           // var financial_adviser_designation = $("#financial_adviser_designation").val();
            var financial_adviser_phone = $('#financial_adviser_phone').val();
            var financial_adviser_email = $('#financial_adviser_email').val();
            var financial_adviser_mobile = $('#financial_adviser_mobile').val();

            // 🔹 Validation for Slide 2
            if (next_dept_id === '' || the_convener === '' || form_scheme_name === '' || next_reference_year === '' || financial_adviser_name === '' ||  financial_adviser_phone === '' || convener_mobile === '' || convener_designation === '' || convener_phone === '' || convener_email === '' || financial_adviser_email === '' || financial_adviser_mobile === '') {
                showError("* All fields are required");
                return;
            }

            // ✅ Prepare FormData (Slide 1 + Slide 2 together)
            var formData = new FormData();
            formData.append("_token", $("meta[name='csrf-token']").attr('content'));
            formData.append("slide", "first");
            formData.append("is_evaluation", is_evaluation);
            formData.append("eval_by_whom", eval_by_whom);
            formData.append("eval_when", eval_when);
            formData.append("eval_geographical_coverage_beneficiaries", eval_geo);
            formData.append("eval_number_of_beneficiaries", eval_number);
            formData.append("eval_major_recommendation", eval_major);
            if (eval_file) formData.append("eval_upload_report", eval_file);

            // Slide 2 data
            formData.append("dept_id", next_dept_id);
            formData.append("convener_name", the_convener);
            formData.append("convener_designation", convener_designation);
            formData.append("convener_phone", convener_phone);
            formData.append("convener_mobile", convener_mobile);
            formData.append("convener_email", convener_email);
            formData.append("scheme_name", form_scheme_name);
            formData.append("scheme_short_name", form_scheme_short_name);
            formData.append("reference_year", next_reference_year);
            formData.append("reference_year2", next_reference_year2);
            formData.append("financial_adviser_name", financial_adviser_name);
            //formData.append("financial_adviser_designation", financial_adviser_designation);
            formData.append("financial_adviser_phone", financial_adviser_phone);
            formData.append("financial_adviser_email", financial_adviser_email);
            formData.append("financial_adviser_mobile", financial_adviser_mobile);

            // ✅ AJAX Submit both slides
           $.ajax({
            type: 'POST',
            url: "{{ route('schemes.add_scheme') }}",
            data: formData,
            contentType: false,  // ✅ must be false for FormData
            processData: false,  // ✅ must be false for FormData
            dataType: 'json',
            success: function (response) {
              //  if (response.success) {
                    $(".otherslides").hide();
                    $(".third_slide").show();
                    $("#previous_btn").val(3).show();
                    $("#next_btn").val(3).show();

                    $('.second_slide').removeClass("active-slide");
                    $('.third_slide').addClass("active-slide");

                    $("#step1tab").removeClass("active");
                    $("#step2tab").addClass("active");
                // } else {
                //     alert(response.message || "* Something went wrong, please try again");
                // }
            },
            error: function (xhr) {
                    let message = 'Something went wrong';

                    if (xhr.responseJSON) {
                        message =
                            xhr.responseJSON.message ||
                            xhr.responseJSON.error ||
                            JSON.stringify(xhr.responseJSON);
                    } else if (xhr.responseText) {
                        message = xhr.responseText;
                    }

                    alert(message);
              }
        });
      } else if (slideid == 3){

              var next_major_objective = $('#next_major_objective_textarea').val();
              var major_objective_file = $("#major_objective_file")[0].files[0]; // get file object

              if (next_major_objective != '') {
                 let nextSlide = countIncrease(slideid);

                  updateStepTitle(nextSlide);
                  
                  $("#the_error_html").remove();

                  // Create FormData object
                  var formData = new FormData();
                  formData.append('slide', 'third');
                  formData.append('major_objective', next_major_objective);
                  if (major_objective_file) {
                      formData.append('major_objective_file', major_objective_file);
                  }

                  // Important: Add CSRF token if using Laravel
                  formData.append('_token', "{{ csrf_token() }}");

                  $.ajax({
                      url: "{{ route('schemes.add_scheme') }}",
                      type: 'POST',
                      data: formData,
                      processData: false, // Don't process data
                      contentType: false, // Don't set content type
                      dataType: 'json',
                      success: function(response) {
                          $(".otherslides").hide();
                          $(".fourth_slide").show();
                          $("#previous_btn").val(4).show();
                          $("#next_btn").val(4).show();
                          $('.third_slide').removeClass("active-slide");
                          $('.fourth_slide').addClass("active-slide");
                      },
                      error: function (xhr) {
                          let message = 'Something went wrong';

                          if (xhr.responseJSON) {
                              message =
                                  xhr.responseJSON.message ||
                                  xhr.responseJSON.error ||
                                  JSON.stringify(xhr.responseJSON);
                          } else if (xhr.responseText) {
                              message = xhr.responseText;
                          }

                          alert(message);
                    }
                  });

              } else {
                  $("#the_error_html").remove();
                  var the_html = '<div class="row" id="the_error_html"><div class="col-xl-12" style="color:red;font-size:20px">* Major objectives scheme required</div></div>';
                  $(".third_slide").append(the_html);
              }

      } else if (slideid == 4){
              var next_major_indicator = $('#next_major_indicator_textarea').val();
              var major_indicator_file = $("#major_indicator_file")[0]?.files[0]; // optional file input

              if (next_major_indicator != '') {
                 let nextSlide = countIncrease(slideid);

                  updateStepTitle(nextSlide);
                  $("#the_error_html").remove();

                  // Create FormData object
                  var formData = new FormData();
                  formData.append('slide', 'fourth');
                  formData.append('major_indicator', next_major_indicator);

                  if (major_indicator_file) {
                      formData.append('major_indicator_file', major_indicator_file);
                  }

                  // Add Laravel CSRF token
                  formData.append('_token', "{{ csrf_token() }}");

                  $.ajax({
                      url: "{{ route('schemes.add_scheme') }}",
                      type: 'POST',
                      data: formData,
                      processData: false,
                      contentType: false,
                      dataType: 'json',
                      success: function(response) {
                          $(".otherslides").hide();
                          $(".fifth_slide").show();
                          $("#previous_btn").val(5).show();
                          $("#next_btn").val(5).show();
                          $('.fourth_slide').removeClass("active-slide");
                          $('.fifth_slide').addClass("active-slide");
                      },
                      error: function (xhr) {
                          let message = 'Something went wrong';

                          if (xhr.responseJSON) {
                              message =
                                  xhr.responseJSON.message ||
                                  xhr.responseJSON.error ||
                                  JSON.stringify(xhr.responseJSON);
                          } else if (xhr.responseText) {
                              message = xhr.responseText;
                          }

                          alert(message);
                    }
                  });

              } else {
                  $("#the_error_html").remove();
                  var the_html = `
                      <div class="row" id="the_error_html">
                          <div class="col-xl-12" style="color:red;font-size:20px">
                              * Major indicators scheme required
                          </div>
                      </div>`;
                  $(".fourth_slide").append(the_html);
              }

      } else if (slideid == 5){

        var implementing_office = $("#implementing_office").val() || [];
        var both_ration = $("#both_ration").val();
        var other_ratio = $("#other_ratio").val();
        var nodal_id = $('#nodal_id').val();
        var nodal_designation = $("#nodal_designation").val();
        var nodal_contact = $("#nodal_contact").val();
        var nodal_mobile = $("#nodal_mobile").val();
        var nodal_email = $("#nodal_email").val();
        var state_ratio = $("#state_ratio").val();
        var center_ratio = $("#center_ratio").val();
        var next_scheme_overview = $("#next_scheme_overview").val();
        var next_scheme_objective = $("#next_scheme_objective").val();
        var next_scheme_components = $('#next_scheme_components').val();
        var state_perValue = parseFloat($('#state_ratio').val()) || 0;

        // ✅ Collect HOD Table Data
        var hod_office = [];
        $('#hodTable tbody tr').each(function() {
            hod_office.push({
                office_name: $(this).find('td:eq(0) input').val(),
                hod_officer_name: $(this).find('input[name="hod_officer_name[]"]').val(),
                hod_email: $(this).find('input[name="hod_email[]"]').val(),
                implementing_office_contact: $(this).find('input[name="implementing_office_contact[]"]').val(),
                hod_mobile: $(this).find('input[name="hod_mobile[]"]').val()
            });
        });

        // ✅ File inputs
        var overview_file = $("#next_scheme_overview_file")[0]?.files[0];
        var objective_file = $("#scheme_objective_file")[0]?.files[0];
        var component_file = $("#next_scheme_components_file")[0]?.files[0];

        // ✅ Basic validation
        if (implementing_office.length === 0 || !nodal_id.trim() || !nodal_designation.trim() || !nodal_contact.trim() || !nodal_mobile.trim() || !nodal_email.trim() || state_ratio === "" || center_ratio === "" || !next_scheme_overview.trim() || !next_scheme_objective.trim() || !next_scheme_components.trim()) {
                        $("#the_error_html").remove();
                        $(".fifth_slide").append(`
                            <div class="row" id="the_error_html">
                                <div class="col-xl-12" style="color:red;font-size:20px">
                                    * All required fields must be filled
                                </div>
                            </div>
                        `);
                        return false;
          }
       let nextSlide = countIncrease(slideid);

        updateStepTitle(nextSlide);
        if (state_perValue > 100) {
            $("#the_error_html").remove();
            $(".fifth_slide").append(`
                <div class="row" id="the_error_html">
                    <div class="col-xl-12" style="color:red;font-size:20px">
                        * Fund Flow percentage cannot exceed 100%
                    </div>
                </div>
            `);
            return false;
        }

        // ✅ Remove any old error message
        $("#the_error_html").remove();

        // ✅ Prepare FormData
        var formData = new FormData();
        formData.append('slide', 'fifth');
        formData.append('_token', "{{ csrf_token() }}");

        implementing_office.forEach(o => formData.append('implementing_office[]', o));
        formData.append('hod_office', JSON.stringify(hod_office));
        formData.append('both_ration', both_ration);
        formData.append('other_ratio', other_ratio);
        formData.append('nodal_officer_name', nodal_id);
        formData.append('nodal_officer_designation', nodal_designation);
        formData.append('nodal_officer_contact', nodal_contact);
        formData.append('nodal_officer_mobile', nodal_mobile);
        formData.append('nodal_officer_email', nodal_email);
        formData.append('state_ratio', state_ratio);
        formData.append('center_ratio', center_ratio);
        formData.append('scheme_overview', next_scheme_overview);
        formData.append('scheme_objective', next_scheme_objective);
        formData.append('sub_scheme', next_scheme_components);

        // ✅ Append files if selected
        if (overview_file) formData.append('next_scheme_overview_file', overview_file);
        if (objective_file) formData.append('scheme_objective_file', objective_file);
        if (component_file) formData.append('next_scheme_components_file', component_file);

        // ✅ AJAX request
        $.ajax({
            url: "{{ route('schemes.add_scheme') }}",
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json',
            success: function(response) {
              
                $(".otherslides").hide();
                $(".sixth_slide").show();
                $("#previous_btn").val(6).show();
                $("#next_btn").val(6).show();
                $('.fifth_slide').removeClass("active-slide");
                $('.sixth_slide').addClass("active-slide");
            },
            error: function (xhr) {
                    let message = 'Something went wrong';

                    if (xhr.responseJSON) {
                        message =
                            xhr.responseJSON.message ||
                            xhr.responseJSON.error ||
                            JSON.stringify(xhr.responseJSON);
                    } else if (xhr.responseText) {
                        message = xhr.responseText;
                    }

                    alert(message);
              }
        });


      }else if (slideid == 6){
             var commencement_year = $('#commencement_year').val();
            var scheme_status = $("input[name='scheme_status']:checked").val();
            var is_sdg = $('input[name="sustainable_goals[]"]:checked').length;
            console.log(is_sdg);
            if(commencement_year != '' && scheme_status != '' && is_sdg > 0) {
              let nextSlide = countIncrease(slideid);

              updateStepTitle(nextSlide);
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
                    url:"{{ route('schemes.add_scheme') }}",
                    data:{'slide':'sixth','commencement_year':commencement_year,'scheme_status':scheme_status,'is_sdg':checked_scheme_status},
                    success:function(response) {
                        $(".otherslides").hide();
                        $(".seventh_slide").show();
                        $("#previous_btn").val(7).show();
                        $("#next_btn").val(7).show();
                        $('.sixth_slide').removeClass("active-slide");
                        $('.seventh_slide').addClass("active-slide");
                        
                    },
                     error: function (xhr) {
                      let message = 'Something went wrong';

                      if (xhr.responseJSON) {
                          message =
                              xhr.responseJSON.message ||
                              xhr.responseJSON.error ||
                              JSON.stringify(xhr.responseJSON);
                      } else if (xhr.responseText) {
                          message = xhr.responseText;
                      }

                      alert(message);
                }
                });

            } else {
                $("#the_error_html").remove();
                var the_html = '<div class="row" id="the_error_html"><div class="col-xl-12" style="color:red;font-size:20px">* sdsdsdAll Fields are required</div></div>';
                $(".sixth_slide").append(the_html);
            }
      } else if (slideid == 7){
        var beneficiaries = [];
            jQuery('.next_beneficiary_selection_criterias').each(function() {
              var currentElement = $(this);
              var value = currentElement.val();
              beneficiaries.push(value);
          });
            var beneficiaryFile = $('#beneficiary_selection_criteria_file')[0].files[0];
            if(beneficiaries != '') {
               let nextSlide = countIncrease(slideid);

            updateStepTitle(nextSlide);
              $("#the_error_html").remove();
                var next_beneficiary_selection_criterias = $(".next_beneficiary_selection_criterias").length;
                // var beneficiaries = [];
                // var j = 0;
                // for(var i=0;i<next_beneficiary_selection_criterias;i++) {
                //     beneficiaries[j] = {'beneficiary_selection_criteria':$("#beneficiary_selection_div_"+i+" .next_beneficiary_selection_criterias").val()};
                //     j++;
                // }

                var formData = new FormData();
                // Append token and other data
              //  formData.append('_token', "{{ csrf_token() }}");
                formData.append('slide', 'seventh');
                formData.append('scheme_beneficiary_selection_criteria', beneficiaries);
                // formData.append('draft_id', draft_id);
                // formData.append('scheme_id', scheme_id);
                
                // Append file input
                if (beneficiaryFile) {
                    formData.append('beneficiary_selection_criteria_file', beneficiaryFile);
                }
                $.ajax({
                    type:'post',
                    dataType:'json',
                    url:"{{ route('schemes.add_scheme') }}",
                    data:formData,
                    contentType: false,
                    processData: false,
                    //data:{'slide':'sixth','scheme_beneficiary_selection_criteria':beneficiaries},
                    success:function(response) {
                        $(".otherslides").hide();
                        $(".eighth_slide").show();
                        $("#previous_btn").val(8).show();
                        $("#next_btn").val(8).show();
                        $('.seventh_slide').removeClass("active-slide");
                        $('.eighth_slide').addClass("active-slide");
                    },
                     error: function (xhr) {
                          let message = 'Something went wrong';

                          if (xhr.responseJSON) {
                              message =
                                  xhr.responseJSON.message ||
                                  xhr.responseJSON.error ||
                                  JSON.stringify(xhr.responseJSON);
                          } else if (xhr.responseText) {
                              message = xhr.responseText;
                          }

                          alert(message);
                    }
                });

            } else {
                $("#the_error_html").remove();
                var the_html = '<div class="row" id="the_error_html"><div class="col-xl-12" style="color:red;font-size:20px">* All Fields are required</div></div>';
                $(".seventh_slide").append(the_html);
            }
      }else if (slideid == 8){
        var major_text = $(".major_benefit_textareas").val();
            if(major_text != '') {
              let nextSlide = countIncrease(slideid);

            updateStepTitle(nextSlide); 
              
              $("#the_error_html").remove();

                $.ajax({
                    type:'post',
                    dataType:'json',
                    url:"{{ route('schemes.add_scheme') }}",
                    data:{'slide':'eighth','major_benefits_text':major_text},
                    success:function(response) {
                        $(".otherslides").hide();
                        $(".nineth_slide").show();
                        $("#previous_btn").val(9).show();
                        $("#next_btn").val(9).show();
                         $('.eighth_slide').removeClass("active-slide");
                        $('.nineth_slide').addClass("active-slide");
                    },
                    error: function (xhr) {
                        let message = 'Something went wrong';

                        if (xhr.responseJSON) {
                            message =
                                xhr.responseJSON.message ||
                                xhr.responseJSON.error ||
                                JSON.stringify(xhr.responseJSON);
                        } else if (xhr.responseText) {
                            message = xhr.responseText;
                        }

                        alert(message);
                  }
                });

            } else {

                  $("#the_error_html").remove();
                  var the_html = '<div class="row" id="the_error_html"><div class="col-xl-12" style="color:red;font-size:20px">* There are atleast 2 Major Benefits required</div></div>';
                  $(".eighth_slide").append(the_html);
              
            }
      }else if(slideid == 9){
		   var next_scheme_implementing_procedure = $("#next_scheme_implementing_procedure").val();
            var implementing_procedure = $("#implementing_procedure").val();
            var beneficiariesGeoLocal = $('#beneficiariesGeoLocal').val();
            var next_otherbeneficiariesGeoLocal = $('#next_otherbeneficiariesGeoLocal').val();
            var taluka_id = $('#taluka_id').val();

            var talukas = [];
            var districts = [];
            var states = [];

            var thedistrictlist = $(".thedistrictlist").length;

            if (thedistrictlist > 0) {
                if (beneficiariesGeoLocal == 1) { // State
                    $("input[name='state_name[]']:checked").each(function () {
                        states.push(this.value.replace(/"/g, ''));
                    });
                } 
                else if (beneficiariesGeoLocal == 3 || beneficiariesGeoLocal == 7) { // Taluka
                    $("input[name='taluka_name[]']:checked").each(function () {
                        talukas.push(this.value.replace(/"/g, ''));
                    });
                } 
                else { // District
                    $("input[name='district_name[]']:checked").each(function () {
                        districts.push(this.value.replace(/"/g, ''));
                    });
                }
            }

            /* ================= VALIDATION ================= */
            if (
                next_scheme_implementing_procedure === '' ||
                implementing_procedure === '' ||
                beneficiariesGeoLocal === ''
            ) {
                $("#the_error_html").remove();
                $(".nineth_slide").append(`
                    <div class="row" id="the_error_html">
                        <div class="col-xl-12" style="color:red;font-size:20px">
                            * All Fields are required
                        </div>
                    </div>
                `);
                return;
            }
			let nextSlide = countIncrease(slideid);

            updateStepTitle(nextSlide); 
            /* ================= FORM DATA ================= */
            let formData = new FormData();

            formData.append('_token', "{{ csrf_token() }}");
            formData.append('slide', 'nineth');
            formData.append('scheme_implementing_procedure', next_scheme_implementing_procedure);
            formData.append('implementing_procedure', implementing_procedure);
            formData.append('beneficiariesGeoLocal', beneficiariesGeoLocal);
            formData.append('otherbeneficiariesGeoLocal', next_otherbeneficiariesGeoLocal);
            formData.append('taluka_id', taluka_id);

            // Arrays
            states.forEach(v => formData.append('state_name[]', v));
            districts.forEach(v => formData.append('district_name[]', v));
            talukas.forEach(v => formData.append('taluka_name[]', v));

            // FILES 🔥
            let implementingFile = $("#implementing_procedure_file")[0]?.files[0];
            let schemeImplementFile = $("#scheme_implement_file")[0]?.files[0];
            let geoFile = $("#geographical_coverage")[0]?.files[0];

            if (implementingFile) {
                formData.append('implementing_procedure_file', implementingFile);
            }
            if (schemeImplementFile) {
                formData.append('scheme_implement_file', schemeImplementFile);
            }

            if (geoFile) {
                formData.append('geographical_coverage', geoFile);
            }

            // var next_scheme_implementing_procedure = $("#next_scheme_implementing_procedure").val();
            // var beneficiariesGeoLocal = $('#beneficiariesGeoLocal').val();
            // var thedistrictlist = $(".thedistrictlist").length;
            // var talukas = [];
            // var districts = [];
            // var states = [];
            // var districtList = $('#districtList').val();
            // if(thedistrictlist > 0) {
              // if(beneficiariesGeoLocal == 1) { //state
                    // var i = 0;
                    // $("input[name='state_name[]']:checked").each(function() {
                        // var ss_state = this.value;
                        // states[i] = ss_state.replace(/"/g,'');
                        // i++;
                    // });
                // }else if(beneficiariesGeoLocal == 3 || beneficiariesGeoLocal == 7) { //Developing Taluka
                    // var i = 0;
                    // $("input[name='taluka_name[]']:checked").each(function() {
                        // var ss_taluka = this.value;
                        // talukas[i] = ss_taluka.replace(/"/g,'');
                        // i++;
                    // });
                // } else { // District
                    // var i = 0;
                    // $("input[name='district_name[]']:checked").each(function() {
                        // var ss_district = this.value;
                        // districts[i] = ss_district.replace(/"/g,'');
                        // i++;
                    // });
                // }
            // }
          // var next_scheme_implementing_procedure = $("#next_scheme_implementing_procedure").val();
          // var beneficiariesGeoLocal = $('#beneficiariesGeoLocal').val();
          // var next_otherbeneficiariesGeoLocal = $('#next_otherbeneficiariesGeoLocal').val();

          // if (next_scheme_implementing_procedure !== '' && beneficiariesGeoLocal !== '') {
              // $("#the_error_html").remove();
           // let nextSlide = countIncrease(slideid);

            // updateStepTitle(nextSlide); 
              $.ajax({
                type: 'POST',
                url: "{{ route('schemes.add_scheme') }}",
                data: formData,
                processData: false,
                contentType: false,
                dataType: 'json',
                success: function (response) {
                    $(".otherslides").hide();
                    $(".tenth_slide").show();
                    $("#previous_btn").val(10).show();
                    $("#next_btn").val(10).show();
                    $('.nineth_slide').removeClass("active-slide");
                    $('.tenth_slide').addClass("active-slide");
                },error: function (xhr) {
                      let message = 'Something went wrong';

                      if (xhr.responseJSON) {
                          message =
                              xhr.responseJSON.message ||
                              xhr.responseJSON.error ||
                              JSON.stringify(xhr.responseJSON);
                      } else if (xhr.responseText) {
                          message = xhr.responseText;
                      }

                      alert(message);
                }
              });
          // } else {
              // $("#the_error_html").remove();
              // $(".nineth_slide").append(`
                  // <div class="row" id="the_error_html">
                      // <div class="col-xl-12" style="color:red;font-size:20px">
                          // * All Fields are required
                      // </div>
                  // </div>
              // `);
          // }

      }else if (slideid == 10){
      
               // ✅ Get all field values
                var next_coverage_beneficiaries_remarks = $("#next_coverage_beneficiaries_remarks").val();
                var beneficiaries_coverage = $("#beneficiaries_coverage")[0].files.length;
                var next_training_capacity_remarks = $("#next_training_capacity_remarks").val();
                var training = $("#training")[0].files.length;
                var next_iec_activities_remarks = $("#next_iec_activities_remarks").val();
                var next_iec = $("#iec")[0].files.length;

                // ✅ Basic validation
                if (
                    next_coverage_beneficiaries_remarks !== '' &&
                    next_training_capacity_remarks !== '' &&
                    next_iec_activities_remarks !== ''
                ) {
                    $("#the_error_html").remove();
                 let nextSlide = countIncrease(slideid);

                    updateStepTitle(nextSlide); 
                    // ✅ Build FormData manually (works with file uploads)
                    var formDataNineth = new FormData();
                    formDataNineth.append('_token', "{{ csrf_token() }}");
                    formDataNineth.append('slide', 'tenth');
                    formDataNineth.append('coverage_beneficiaries_remarks', next_coverage_beneficiaries_remarks);
                    formDataNineth.append('training_capacity_remarks', next_training_capacity_remarks);
                    formDataNineth.append('iec_activities_remarks', next_iec_activities_remarks);

                    // ✅ Attach files if selected
                    if (beneficiaries_coverage > 0) {
                        formDataNineth.append('beneficiaries_coverage', $('#beneficiaries_coverage')[0].files[0]);
                    }
                    if (training > 0) {
                        formDataNineth.append('training', $('#training')[0].files[0]);
                    }
                    if (next_iec > 0) {
                        formDataNineth.append('iec_file', $('#iec')[0].files[0]);
                    }

                    // ✅ Send via AJAX
                    $.ajax({
                        type: 'POST',
                        url: "{{ route('schemes.add_scheme') }}",
                        data: formDataNineth,
                        processData: false,
                        contentType: false,
                        success: function (response) {
                            // ✅ Go to next slide
                            $(".otherslides").hide();
                            $(".eleventh_slide").show();
                            $("#previous_btn").val(11).show();
                            $("#next_btn").val(11).show();
                            $('.tenth_slide').removeClass("active-slide");
                            $('.eleventh_slide').addClass("active-slide");
                        },
                         error: function (xhr) {
                            let message = 'Something went wrong';

                            if (xhr.responseJSON) {
                                message =
                                    xhr.responseJSON.message ||
                                    xhr.responseJSON.error ||
                                    JSON.stringify(xhr.responseJSON);
                            } else if (xhr.responseText) {
                                message = xhr.responseText;
                            }

                            alert(message);
                      }
                    });
                } else {
                    // ✅ Validation error
                    $("#the_error_html").remove();
                    var the_html =
                        '<div class="row" id="the_error_html">' +
                        '<div class="col-xl-12" style="color:red;font-size:20px">* All Fields are required</div>' +
                        '</div>';
                    $(".tenth_slide").append(the_html);
                }
    
      }else if(slideid == 11){
            var next_benefit_to = $("#next_benefit_to").val();
            console.log(next_benefit_to);
            var countallconvergence = $(".countallconvergence").length;

            var all_convergence = [];
            for (var i = 0; i < countallconvergence; i++) {
                if ($('#convergence_row_' + i + ' select').val() != '') {
                
                    all_convergence[i] = {
                        'dept_id': $('#convergence_row_' + i + ' select').val(),
                        'dept_remarks': $("#convergence_row_" + i + ' textarea').val()
                    };
                }
            }

      //  if (next_benefit_to != '') {
            let nextSlide = countIncrease(slideid);
            updateStepTitle(nextSlide);  
            $("#the_error_html").remove();

            $.ajax({
                type: 'POST',
                url: "{{ route('schemes.add_scheme') }}",
                data: {
                    '_token': "{{ csrf_token() }}",
                    'slide': 'eleventh',
                    'benefit_to':next_benefit_to,
                    'all_convergence': all_convergence
                },
                dataType: 'json',
                success: function (response) {
                    $(".otherslides").hide();
                    $(".twelth_slide").show();
                    $("#previous_btn").val(12).show();
                    $("#next_btn").val(12).show();
                    $('.eleventh_slide').removeClass("active-slide");
                    $('.twelth_slide').addClass("active-slide");
                },
                 error: function (xhr) {
                      let message = 'Something went wrong';

                      if (xhr.responseJSON) {
                          message =
                              xhr.responseJSON.message ||
                              xhr.responseJSON.error ||
                              JSON.stringify(xhr.responseJSON);
                      } else if (xhr.responseText) {
                          message = xhr.responseText;
                      }

                      alert(message);
                }
            });

        // } else {
        //     $("#the_error_html").remove();
        //     var the_html = '<div class="row" id="the_error_html"><div class="col-xl-12" style="color:red;font-size:20px">* All Fields are required</div></div>';
        //     $(".eleventh_slide").append(the_html);
        // }

      }else if(slideid == 12){
            let nextSlide = countIncrease(slideid);
            updateStepTitle(nextSlide); 
            var tokenis = $("meta[name='csrf-token']").attr('content');
            var formData = new FormData();
            formData.append('_token', tokenis);
            formData.append('slide', 'twelth');

            // Helper function to append multiple files safely
            function appendFiles(selector, paramName) {
                let input = $(selector)[0];
                if (input && input.files.length > 0) {
                    for (let i = 0; i < input.files.length; i++) {
                        formData.append(paramName, input.files[i]);
                    }
                }
            }

            // Append all categories
            appendFiles('input[name="gr[]"]', 'gr[]'); // Note: your loop used name selector here
            appendFiles('#notification', 'notification[]');
            appendFiles('#brochure', 'brochure[]');
            appendFiles('#pamphlets', 'pamphlets[]');
            appendFiles('#other_details_center_state', 'otherdetailscenterstate[]');

            // Beneficiary Form
            let fillingType = $('input[name="beneficiary_filling_form_type"]:checked').val();
            formData.append('beneficiary_filling_form_type', fillingType ?? '');
            if (fillingType === '0') {
                let benInput = $('#beneficiary_filling_form')[0];
                if (benInput && benInput.files.length > 0) {
                    formData.append('beneficiary_filling_form', benInput.files[0]);
                }
            }

            $.ajax({
                type: 'POST',
                url: "{{ route('schemes.add_scheme') }}",
                data: formData,
                processData: false,
                contentType: false,
                beforeSend: function() {
                    // Clear previous errors and show loader
                    $("#the_error_html").remove();
                    $("#next_btn").prop('disabled', true).text('Processing...');
                },
                success: function (response) {
                    $("#next_btn").prop('disabled', false).text('Next');
                    $(".otherslides").hide();
                    $(".thirteenth_slide").show();
                    $("#previous_btn").val(13).show();
                    $("#next_btn").val(13).show();
                    $('.twelth_slide').removeClass("active-slide");
                    $('.thirteenth_slide').addClass("active-slide");
                },
                error: function (xhr) {
                    $("#next_btn").prop('disabled', false).text('Next');
                    let message = 'Something went wrong';
                    if (xhr.responseJSON) {
                        message = xhr.responseJSON.error || xhr.responseJSON.message || "An error occurred";
                    }

                    // Inject the error message into the slide instead of just an alert
                    var errorHtml = '<div class="row" id="the_error_html">' +
                                    '<div class="col-12" style="color:red; font-weight:bold; margin-top:10px;">' + 
                                    message + 
                                    '</div></div>';
                    
                    $(".twelth_slide").append(errorHtml);
                    
                    // Also keep alert as a fallback if you prefer
                    alert(message);
                }
            });
        }else if(slideid == 13){
            var indicator_values = $(".getindicator_hod").val();
           // if(indicator_values != '') {
               let nextSlide = countIncrease(slideid);

                updateStepTitle(nextSlide);
                $("#the_error_html").remove();
                
                $.ajax({
                    type:'post',
                    dataType:'json',
                    url:"{{ route('schemes.add_scheme') }}",
                    data:{'slide':'thirteenth', 'major_indicator_hod':indicator_values},
                    success:function(response) {
                              $(".otherslides").hide();
                              $(".fourteenth_slide").show();
                              $("#previous_btn").val(14).show();
                              $("#next_btn").val(14).show();
                              $("#next_btn").text("Finish").removeClass('btn-primary').addClass('btn-success');
                              $('.thirteenth_slide').removeClass("active-slide");
                              $('.fourteenth_slide').addClass("active-slide");
                    },
                    error:function() {
                        console.log('add_scheme ajax error');
                    }
                });

            // } else {
                
            //       $("#the_error_html").remove();
            //       var the_html = '<div class="row" id="the_error_html"><div class="col-xl-12" style="color:red;font-size:20px">* The indicators required</div></div>';
            //       $(".twelth_slide").append(the_html);
                
            // }
        }else if (slideid == 14){
            var next_financial_progress_year = $(".next_financial_progress_year").val();
            // var next_financial_progress_units = $(".next_financial_progress_units").val();
                var next_financial_progress_target = $(".next_financial_progress_target").val();
                var next_financial_progress_achivement = $(".next_financial_progress_achivement").val();
                var next_financial_progress_allocation = $(".next_financial_progress_allocation").val();
                var next_financial_progress_expenditure = $(".next_financial_progress_expenditure").val();
                var next_financial_progress_selection = $(".next_financial_progress_selection").val();
                var next_financial_progress_item  = $(".next_financial_progress_item").val();
                var count_tr = $("#thisistbody tr").length;
                if(next_financial_progress_year != ''  && next_financial_progress_target != '' && next_financial_progress_achivement != '' && next_financial_progress_allocation != '' && next_financial_progress_expenditure != '' && next_financial_progress_item  != '' && next_financial_progress_selection != '') {
                //countIncrease(slideid);
                
                   $("#the_error_html").remove();
                    var tr_array = [];
                    var count_blank_fields = 0;

                    // 1. Loop through every row inside the table body
                    $("#thisistbody tr").each(function(index, tr) {
                        var row = $(tr);
                        
                        // Find values relative to this specific row using classes
                        var the_year = row.find(".next_financial_progress_year").val();
                        var the_selection = row.find(".next_financial_progress_selection").val();
                        var the_target = row.find(".next_financial_progress_target").val();
                        var the_achievement = row.find(".next_financial_progress_achivement").val();
                        var the_allocation = row.find(".next_financial_progress_allocation").val();
                        var the_expenditure = row.find(".next_financial_progress_expenditure").val();

                        // 2. Validation Check for this row
                        if(the_selection != '' && the_year != '' && the_target != '' && the_achievement != '' && the_allocation != '' && the_expenditure != '') {
                            tr_array.push({
                                'financial_year': the_year,
                                'target': the_target,
                                'achievement': the_achievement,
                                'allocation': the_allocation,
                                'expenditure': the_expenditure,
                                'selection': the_selection
                            });
                        } else {
                            count_blank_fields++;
                        }
                    });

                    // 3. Final Validation and AJAX
                    if(count_blank_fields > 0) {
                        var the_html = '<div class="row" id="the_error_html"><div class="col-xl-12" style="color:red;font-size:20px">* Fill all the blank fields</div></div>';
                        $(".fourteenth_slide").append(the_html);
                    } else if (tr_array.length === 0) {
                        alert("Please add at least one financial record.");
                    } else {
                        var confsure = confirm('Are you sure Financial Progress is entered correctly?');
                        if(confsure == true) {
                            var financial_progress_remarks = $("#financial_progress_remarks").val();
                            $.ajax({
                                type: 'post',
                                dataType: 'json',
                                url: "{{ route('schemes.add_scheme') }}",
                                data: {
                                    'slide': 'fourteenth',
                                    'tr_array': tr_array, 
                                    'financial_progress_remarks': financial_progress_remarks
                                },
                                success: function(response) {
                                    $(".otherslides").hide();
                                    finishSlides();
                                },
                                error: function() {
                                    console.log('add_scheme ajax error');
                                }
                            });
                        }
                    }
                } else {
                    $("#the_error_html").remove();
                    var the_html = '<div class="row" id="the_error_html"><div class="col-xl-12" style="color:red;font-size:20px">* All Fields are required</div></div>';
                    $(".fourteenth_slide").append(the_html);
                }
        }
    }
// ✅ Reusable Error Display Function
function showError(msg) {
    var the_html = `
        <div class="row" id="the_error_html">
            <div class="col-xl-12" style="color:red;font-size:20px">${msg}</div>
        </div>`;
    $(".active-slide").append(the_html);
}
var preCount = 0;
function countPrevious(prevslide){
  let previousSlide = parseInt(prevslide) - 1;
  preCount = $('.page_no div').html(previousSlide);
  $('.save_item').attr('data-slide-item',previousSlide);
      return previousSlide;
}
function finishSlides() {

    let message = "Your Proposal has been completed.";
    let isRole20 = false;

    if (userRole == 20) {
        message = "Your Proposal has been completed. This Proposal is sent to Evaluation department";
        isRole20 = true;
    }

    Swal.fire({
        title: "Completed!",
        text: message,
        icon: "success",
        confirmButtonText: "OK"
    }).then((result) => {

        if (result.isConfirmed) {

            // Role 20 → AJAX forward
            if (isRole20) {
                $.ajax({
                    url: "{{ route('gadsec.gad-scheme-to-eval') }}",
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                       // draft_id: draft_id
                    },
                    success: function(response) {
                        Swal.fire({
                            title: "Success!",
                            text: "Proposal forwarded to Evaluation Department successfully.",
                            icon: "success"
                        }).then(() => {
                            location.reload();
                        });
                    },
                    error: function() {
                        Swal.fire("Error", "Something went wrong while forwarding.", "error");
                    }
                });

            } 
            // Other roles → Normal redirect
            else {
                var get_url = "{{ route('proposals', ['param' => 'new']) }}";
                window.location.href = get_url;
            }
        }
    });
}
// function finishSlides() {
//           Swal.fire({
//           title: "Completed!",
//           text: "Your Proposal has been completed.",
//           icon: "success"
//         }).then(okay => {
//             if (okay) {
//               var get_url = "{{ route('proposals', ['param' => 'new']) }}";
//               window.location.href = get_url;
//             }
//         });
// }
  function getPrevSlide(prevslide) {
     // countPrevious(prevslide);
      let prevSlide = countPrevious(prevslide);
      updateStepTitle(prevSlide);
        if(prevslide == 2) {
          console.log('prevslide 2');
          $('.second_slide').removeClass("active-slide");
          $('.first_slide').addClass("active-slide");
          $("#step2tab").removeClass("active");
          $("#step1tab").addClass("active");
          $(".otherslides").hide();
          $(".first_slide").show();
          $("#previous_btn").val(1).hide();
          $("#next_btn").val(1).show();
        } else if(prevslide == 3) {
            console.log('prevslide 3');
            $('.third_slide').removeClass("active-slide");
            $('.second_slide').addClass("active-slide");
            $("#step3tab").removeClass("active");
            $("#step2tab").addClass("active");
            $(".otherslides").hide();
            $(".second_slide").show();
            $("#previous_btn").val(2).show();
            $("#next_btn").val(2).show();
        } else if(prevslide == 4) {
            console.log('prevslide 4');
            $('.fourth_slide').removeClass("active-slide");
            $('.third_slide').addClass("active-slide"); 
          
            $(".otherslides").hide();
            $(".third_slide").show();
            $("#previous_btn").val(3).show();
            $("#next_btn").val(3).show();
        } else if(prevslide == 5) {
            console.log('prevslide 5');
            $('.fifth_slide').removeClass("active-slide");
            $('.fourth_slide').addClass("active-slide"); 
            $(".otherslides").hide();
            $(".fourth_slide").show();
            $("#previous_btn").val(4).show();
            $("#next_btn").val(4).show();
        } else if(prevslide == 6) {
            console.log('prevslide 6');
             $('.sixth_slide').removeClass("active-slide");
            $('.fifth_slide').addClass("active-slide"); 
            $(".otherslides").hide();
            $(".fifth_slide").show();
            $("#previous_btn").val(5).show();
            $("#next_btn").val(5).show();
        }else if(prevslide == 7) {
          $('.seventh_slide').removeClass("active-slide");
          $('.sixth_slide').addClass("active-slide"); 
          $(".otherslides").hide();
          $(".sixth_slide").show();
          $("#previous_btn").val(6).show();
          $("#next_btn").val(6).show();
      } else if(prevslide == 8) {
          console.log('prevslide 8');
          $('.eighth_slide').removeClass("active-slide");
          $('.seventh_slide').addClass("active-slide"); 
          $(".otherslides").hide();
          $(".seventh_slide").show();
          $("#previous_btn").val(7).show();
          $("#next_btn").val(7).show();
      } else if(prevslide == 9) {
         $('.nineth_slide').removeClass("active-slide");
          $('.eighth_slide').addClass("active-slide"); 
          $(".otherslides").hide();
          $(".eighth_slide").show();
          $("#previous_btn").val(8).show();
          $("#next_btn").val(8).show();
      } else if(prevslide == 10) {
          $('.tenth_slide').removeClass("active-slide");
          $('.nineth_slide').addClass("active-slide"); 
          $(".otherslides").hide();
          $(".nineth_slide").show();
          $("#previous_btn").val(9).show();
          $("#next_btn").val(9).show();
      } else if(prevslide == 11) {
          $('.eleventh_slide').removeClass("active-slide");
          $('.tenth_slide').addClass("active-slide");
          $(".otherslides").hide();
          $(".tenth_slide").show();
          $("#previous_btn").val(10).show();
          $("#next_btn").val(10).show();
      } else if(prevslide == 12) {
          console.log('prevslide 12');
          $('.twelth_slide').removeClass("active-slide");
          $('.eleventh_slide').addClass("active-slide");
          $(".otherslides").hide();
          $(".eleventh_slide").show();
          $("#previous_btn").val(11).show();
          $("#next_btn").val(11).show();
      } else if(prevslide == 13) {
          console.log('prevslide 13');
          $('.thirteenth_slide').removeClass("active-slide");
          $('.twelth_slide').addClass("active-slide");
          $(".otherslides").hide();
          $(".twelth_slide").show();
          $("#previous_btn").val(12).show();
          $("#next_btn").val(12).show();
      } else if(prevslide == 14) {
          console.log('prevslide 14');
          $('.fourteenth_slide').removeClass("active-slide");
          $('.thirteenth_slide').addClass("active-slide");
          $(".otherslides").hide();
          $(".thirteenth_slide").show();
          $("#previous_btn").val(13).show();
          $("#the_error_html").remove();
         $("#next_btn").text("Next").removeClass('btn-success').addClass('btn-primary');
        //   $("#div_next_btn").empty();
        //   var next_btn = '<button type="button" class="btn btn-primary font-weight-bold text-uppercase" data-wizard-type="action-next" value="13" onclick="getNextSlide(this.value)" id="next_btn">Next</button>';
        //   $("#div_next_btn").html(next_btn);
      }
    }

  
    $(document).ready(function(){
    //   $('#next_reference_year').change(function(){
    //     var selectedValue = $(this).val();        
    //     var selectedYear = parseInt(selectedValue.split('-')[0]);

    //     $('#next_reference_year2 option').each(function(){
    //         var year = parseInt($(this).val().split('-')[0]);
    //         if(year < selectedYear){
    //             $(this).hide();
    //         } else {
    //             $(this).show();
    //         }
    //     });
    // });

//     $(document).on('change', '#next_reference_year', function() {
//     var selectedValue = $(this).val();
//     if (!selectedValue) return; // Exit if "Select Year" is picked

//     var referenceStart = parseInt(selectedValue.split('-')[0]);

//     // --- LOGIC 1: Filter Reference Year 2 (To) ---
//     $('#next_reference_year2 option').each(function() {
//         var optVal = $(this).val();
//         if (optVal != "") {
//             var optYear = parseInt(optVal.split('-')[0]);
//             if (optYear < referenceStart) {
//                 $(this).hide();
//             } else {
//                 $(this).show();
//             }
//         }
//     });

//     // Reset Reference Year 2 if it's now invalid
//     var currentRef2 = $('#next_reference_year2').val();
//     if (currentRef2 && parseInt(currentRef2.split('-')[0]) < referenceStart) {
//         $('#next_reference_year2').val('');
//     }

//     // --- LOGIC 2: Filter the Financial Progress Table Rows ---
//     $('.next_financial_progress_year').each(function() {
//         var dropdown = $(this);
        
//         dropdown.find('option').each(function() {
//             var optVal = $(this).val();
//             if (optVal != "") {
//                 var optStart = parseInt(optVal.split('-')[0]);
//                 if (optStart < referenceStart) {
//                     $(this).prop('disabled', true).hide();
//                 } else {
//                     $(this).prop('disabled', false).show();
//                 }
//             }
//         });

//         // Reset the table dropdown if the selected value is now invalid
//         var tableSelected = dropdown.val();
//         if (tableSelected && parseInt(tableSelected.split('-')[0]) < referenceStart) {
//             dropdown.val('');
//         }
//     });
// });

    $( ".datepicker" ).datepicker({
          format: 'dd/mm/yyyy', 
          changeMonth: true,
          changeYear: true,
        //  maxDate: new Date(),
          yearRange: "-100:+0",
          autoclose: true
      });
});

function toggleNextButton() {
    if ($('.is-invalid').length > 0) {
        $('#next_btn').prop('disabled', true);
    } else {
        $('#next_btn').prop('disabled', false);
    }
}

$(document).on('input', '.mobile_number', function () {

    let value = this.value;

    // Convert Gujarati digits to English
    value = value.replace(/[૦-૯]/g, function(d) {
        return '૦૧૨૩૪૫૬૭૮૯'.indexOf(d);
    });

    // Remove non-numeric
    value = value.replace(/[^0-9]/g, '');

    this.value = value.slice(0, 10);

    let $this = $(this);
    let $group = $this.closest('.form-group');
    $group.find('.mobile-error').remove();

    if (value.length > 0) {

        if (!/^[6-9]/.test(value)) {
            $this.addClass('is-invalid');
            $this.after('<div class="text-danger mobile-error">Mobile number must start with 6, 7, 8, or 9</div>');
        }
        else if (value.length < 10) {
            $this.addClass('is-invalid');
            $this.after('<div class="text-danger mobile-error">Please enter 10 digit mobile number</div>');
        }
        else {
            $this.removeClass('is-invalid');
        }
    } else {
        $this.removeClass('is-invalid');
    }

    toggleNextButton();
});


$(document).on('input', '.landline', function () {
    let value = this.value.replace(/[^0-9]/g, '');
    this.value = value.slice(0, 11);

    let $this = $(this);
    let $group = $this.closest('.form-group');
    $group.find('.landline-error').remove();

    if (value.length > 0 && value.length < 11) {
        $this.addClass('is-invalid');
        $this.after('<div class="text-danger landline-error">Please enter valid landline number</div>');
    } else {
        $this.removeClass('is-invalid');
    }

    toggleNextButton(); // 👈 ADD THIS
});

$(document).on('input', '.email-input', function () {

    let value = this.value.trim().toLowerCase();
    let $this = $(this);
    let $group = $this.closest('.form-group');

    $group.find('.email-error').remove();

    let emailRegex = /^[a-z0-9._%+-]+@([a-z0-9-]+\.)*gujarat\.gov\.in$/;

    if (value.length > 0 && !emailRegex.test(value)) {

        $this.addClass('is-invalid');
        $this.after('<div class="text-danger email-error">Only gujarat.gov.in email is allowed</div>');

    } else {

        $this.removeClass('is-invalid');
    }

    toggleNextButton();
});
document.addEventListener("input", function (e) {
     if (e.target.classList.contains("only-text")) {
        e.target.value = e.target.value.replace(/[^\p{L}.\s]/gu, '');
    }
});
document.addEventListener('DOMContentLoaded', function () {
    const emailInputs = document.querySelectorAll('.email-input');

    emailInputs.forEach(function (input) {
        input.addEventListener('input', function () {
            this.value = this.value.toLowerCase();
        });

        input.addEventListener('paste', function () {
            setTimeout(() => {
                this.value = this.value.toLowerCase();
            }, 0);
        });
    });
});
$(document).on('blur', '.email-input-td', function () {
    let value = this.value.trim();
    let $this = $(this);
    let $td = $this.closest('td');

    // remove old error
    $td.find('.email-error').remove();

    let emailRegex = /^[a-z0-9._%+-]+@([a-z0-9-]+\.)*gujarat\.gov\.in$/;

    if (value !== '' && !emailRegex.test(value)) {
        $this.addClass('is-invalid');

        $this.after(
            '<div class="text-danger email-error">Only gujarat.gov.in email is allowed</div>'
        );
    } else {
        $this.removeClass('is-invalid');
    }

    toggleNextButton();
});
$(document).on('input', '.word-limit', function () {

    let text = $(this).val().trim();
    let words = text ? text.match(/\b\S+\b/g).length : 0;

    let maxWords = parseInt($(this).data('max-count'));
    let warningLimit = parseInt($(this).data('warning-count'));
    let hardLimit = parseInt($(this).data('hard-count'));

    let messageBox = $(this).next('.word-message');
    messageBox.removeClass('text-danger text-warning text-muted');

    if (words <= warningLimit) {

        messageBox.addClass('text-muted')
                  .text(words + " / " + maxWords + " words");

    } 
    // else if (words <= maxWords) {

    //     messageBox.addClass('text-warning')
    //               .text(words + " / " + maxWords + " words (Approaching limit)");

    // } 
    else if (words <= hardLimit) {

        let extra = words - maxWords;

        messageBox.addClass('text-danger')
                  .text("Exceeded by " + extra + " words. Please reduce.");

    } else {

        let extra = words - hardLimit;

        messageBox.addClass('text-danger')
                  .text("Hard limit exceeded. Remove " + extra + " words immediately.");
    }

    // ✅ MULTIPLE FIELD SAFE BUTTON LOGIC
    let hasError = false;

    $('.word-limit').each(function () {

      let text = $(this).val().trim();
      let wordsArr = text.match(/\b\S+\b/g);
      let words = wordsArr ? wordsArr.length : 0;

      let maxWords = parseInt($(this).data('max-count'));

      if (words > maxWords) {
          hasError = true;
          return false;
      }
  });

    $('#next_btn').prop('disabled', hasError);

});
</script>


