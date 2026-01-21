{{-- @extends('dashboards.implementations.layouts.ia-dash-layout') --}}
@extends('dashboards.proposal.layouts.sidebar')
@section('title','Scheme')
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
    max-width: 1250px !important;
  }
}
@media screen and (min-width: 1600px) {
  .container {
    max-width: 1250px !important;
  }
}
@media screen and (min-width: 1900px) {
  .container {
    max-width: 1250px !important;
  }
} .step-container {
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
</style>
@section('content')
@php
  use Illuminate\Support\Facades\Crypt;
@endphp
<!--begin::Content-->
<div class="content d-flex flex-column flex-column-fluid" id="kt_content" style="">
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
             <div class="flex-column-fluid">
                <!--begin::Container-->
                <div class="container">

                    <div class="step-container mb-4">
                        <div class="step-box active" id="step1tab">Details of Implementing Offices</div>
                        <div class="step-box" id="step2tab">Directorate of Evaluation (DOE) – Scheme-related</div>
                    </div>
                      <!--begin: Wizard-->
                      <div>
                        <div class="card card-custom card-shadowless rounded-top-0 font-17">
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
                                <div>
                                    @php
                                        $currentSlide = $val->save_last_item ?? 1; // default to 1 if null
                                        
                                    @endphp
                                    <div class="first_slide col-xl-12 {{ ($currentSlide  == 1) ? 'active-slide' : '' }}  otherslides" style="{{  ($currentSlide  == 1) ? 'display:block;' : 'display:none;' }}">
                                          {{-- <form method="post" id="fourteenth_slide_form" enctype="multipart/form-data"> --}}
                                            <input type="hidden" name="draft_id" id="next_draft_id" value="{{ $val->draft_id }}">
                                            <input type="hidden" name="scheme_id" id="next_scheme_id" value="{{ $scheme_id }}">
                                              {{-- @csrf --}}
                                              <input type="hidden" name="slide" value="first">
                                              <div class="row ">
                                                <div class="col-xl-12">
                                                  <div class="form-group">
                                                    <label>Whether evaluation of this scheme already done in past? (આ યોજનાનું મૂલ્યાંકન અગાઉ થઈ ચૂકેલ છે?) <span class="required_filed"> * </span> :</label>
                                                    <div></div>
                                                    <div class="radio-inline">
                                                      <label class="radio radio-rounded">
                                                          <input type="radio" name="is_evaluation" id="is_evaluation_yes" value="Y" class="is_evaluation" onclick="fn_show_if_eval(this.value)" {{ old('is_evaluation', $val->is_evaluation) === 'Y' ? 'checked' : '' }}  />
                                                          <span></span>
                                                          Yes (હા)
                                                      </label>
                                                      <label class="radio radio-rounded">
                                                          <input type="radio" name="is_evaluation" id="is_evaluation_no" value="N" class="is_evaluation" onclick="fn_show_if_eval(this.value)" {{ old('is_evaluation', $val->is_evaluation) === 'N' ? 'checked' : '' }} />
                                                          <span></span>
                                                          No (ના)
                                                      </label>
                                                    </div>
                                                  </div>
                                                </div>                
                                              </div>
                                              <div class="form_eval_yes_div">
                                              <div class="row" id="if_eval_yes_div" style="{{ $val->is_evaluation == 'Y' ? 'display:block;' : 'display:none;' }}">
                                                  <div class="col-xl-12">
                                                      <div class="form-group">
                                                        <label>By Whom? (કોના દ્વારા?) <span class="required_filed"> * </span> :</label>
                                                      <input type="text" name="eval_scheme_bywhom" id="eval_by_whom" class="form-control pattern" value="{{ old('eval_scheme_bywhom', $val->eval_scheme_bywhom) }}">
                                                      </div>
                                                  </div>
                                                  <div class="col-xl-12">
                                                      <div class="form-group">
                                                        <label>When? (ક્યારે?) <span class="required_filed"> * </span> :</label>
                                                        <input type="text" id="eval_when" name="eval_scheme_when" class="form-control datepicker" autocomplete="off" placeholder="dd/mm/yyyy" value="{{ old('eval_scheme_when', $val->eval_scheme_when) }}">
                                                      </div>
                                                  </div>
                                                  <div class="col-xl-12">
                                                      <div class="form-group">
                                                        <label>Geographical coverage of Beneficiaries (સમાવિષ્ટ કરેલ લાભાર્થીઓનો ભૌગોલિક વિસ્તાર) <span class="required_filed"> * </span> :</label>
                                                        <input type="text" name="eval_scheme_geo_cov_bene" class="form-control pattern" id="eval_geographical_coverage_beneficiaries" value="{{ old('eval_scheme_geo_cov_bene', $val->eval_scheme_geo_cov_bene) }}">
                                                      </div>
                                                  </div>
                                                  <div class="col-xl-12">
                                                      <div class="form-group">
                                                        <label>No. of beneficiaries in sample (નિદર્શમાં સમાવિષ્ટ લાભાર્થીઓની સંખ્યા) <small>( greater than 10 )</small> <span class="required_filed"> * </span> :</label>
                                                        <input type="text" name="eval_scheme_no_of_bene" class="form-control numberonly pattern" id="eval_number_of_beneficiaries" maxlength="90" value="{{ old('eval_scheme_no_of_bene', $val->eval_scheme_no_of_bene) }}">
                                                      </div>
                                                  </div>
                                                  <div class="col-xl-12">
                                                      <div class="form-group">
                                                        <label>Major recommendations (મુખ્ય ભલામણો.) <span class="required_filed"> * </span> :</label>
                                                        <input type="text" name="eval_scheme_major_recommendation" class="form-control pattern" id="eval_major_recommendation" value="{{ old('eval_scheme_major_recommendation', $val->eval_scheme_major_recommendation) }}">
                                                      </div>
                                                  </div>
                                                  <div class="col-xl-12">
                                                      <div class="form-group">
                                                        <label>Upload report (અહેવાલ અપલોડ કરવો.) <span class="required_filed"> * </span> :</label>
                                                        <div></div>
                                                          <div class="custom-file">
                                                              <input type="file" class="custom-file-input file_type_name" name="eval_upload_report" id="eval_if_yes_upload_file" accept=".pdf,.xlsx,.docx">
                                                              <label class="custom-file-label" for="eval_if_yes_upload_file">Choose File</label>
                                                          </div>
                                                      </div>
                                                      @if($val->eval_scheme_report)
                                                          <div class="form-group">
                                                            @php
                                                              $extension = pathinfo($val->eval_scheme_report, PATHINFO_EXTENSION);
                                                            @endphp
                                                            @if($extension == 'pdf')
                                                                <a href="{{ route('schemes.get_the_file', [Crypt::encrypt($scheme_id), $val->eval_scheme_report]) }}" target="_blank" title="{{ $val->eval_scheme_report }}"><i class="fas fa-file-pdf fa-2x" style="color:red;"></i></a>
                                                            @elseif ($extension == 'doc')
                                                                <a href="{{ route('schemes.get_the_file', [Crypt::encrypt($scheme_id), $val->eval_scheme_report]) }}" download="{{ $val->eval_scheme_report }}"><i class="fas fa-download fa-2x" style="color:#007bff;"></i></a>
                                                                @else
                                                                <a href="{{ route('schemes.get_the_file', [Crypt::encrypt($scheme_id), $val->eval_scheme_report]) }}" download="{{ $val->eval_scheme_report }}"><i class="fas fa-download fa-2x" style="color:green;"></i></a>
                                                            @endif
                                                              {{-- <a href="{{ $replace_url }}/get_the_file/{{ $scheme_id }}/_eval_report" target="_blank"><i class="fas fa-file-pdf fa-2x" style="color:red;"></i></a> --}}
                                                          </div>
                                                      @endif
                                                  </div>
                                              </div>
                                              </div>
                                              
                                            {{-- <button type="submit" style="visibility: hidden;" id="btn_fourteenth_slide_submit"></button>
                                          </form>
                                      <div id="send_eval_yes_div" style="display:none"></div> --}}
                                    </div>

                                  <!--begin::Input-->
                                  <div class="second_slide {{  ($currentSlide  == 2) ? 'active-slide' : '' }} otherslides" style="{{($currentSlide  == 2) ? 'display:block;' : 'display:none;' }}">
                                          <input type="hidden" name="draft_id" id="next_draft_id" value="{{ $val->draft_id }}">
                                          <input type="hidden" name="scheme_id" id="next_scheme_id" value="{{ $scheme_id }}">
                                          <div class="row ">
                                              <div class="col-xl-12">
                                                  <div class="form-group">
                                                    <label>Name of the Department (વિભાગનું નામ) <span class="required_filed"> * </span> : </label>
                                                    <input type="text" id="next_dept_name" value="{{department_name($val->dept_id)}}" name="dept_name" readonly class="form-control pattern @error('dept_id') is-invalid @enderror">
                                                      <input type="hidden" class="form-control" id="next_dept_id" name="dept_id" value="{{ $val->dept_id }}" />
                                                      @error('dept_id')
                                                          <div class="alert alert-danger">{{ $message }}</div>
                                                      @enderror
                                                  </div>
                                              </div>
                                          </div>
                                            <!--end::Input-->
                                            <div class="row" style="margin-top: 15px;">
                                              <div class="col-xl-4">
                                                  <div class="form-group">
                                                    <label>Name of the Nodal Officer <br>(નોડલ અધિકારીનું નામ) <span class="required_filed"> * </span> :</label>
                                                    <input type="text" name="convener_name" class="form-control pattern @error('convener_name') is-invalid @enderror" maxlength="100" id="con_id" value="{{ $val->convener_name }}">
                                                        @error('convener_name')
                                                            <div class="text-danger">* {{ $message }}</div>
                                                        @enderror
                                                  </div>
                                              </div>
                                              <div class="col-xl-4">
                                                <div class="form-group">
                                                  <label>Designation of the Nodal Officer <br>(નોડલ અધિકારી નો હોદ્દો) <span class="required_filed"> * </span> :</label>
                                                   <select class="form-control" id="convener_designation" name="convener_designation">
                                                    <option value="">Select Designation</option>
                                                    <option value="as" {{ $val->convener_designation == 'as' ? 'selected' : '' }}>Additional Secretary</option>
                                                    <option value="ds" {{ $val->convener_designation == 'ds' ? 'selected' : '' }}>Deputy Secretary</option>
                                                    <option value="js" {{ $val->convener_designation == 'js' ? 'selected' : '' }}>Joint Secretary</option>
                                                  </select>
                                                  {{-- <input type="text" name="convener_designation" class="form-control pattern @error('convener_designation') is-invalid @enderror" maxlength="100" id="convener_designation" value="{{ $val->convener_designation }}"> --}}
                                                      @error('convener_designation')
                                                          <div class="text-danger">* {{ $message }}</div>
                                                      @enderror
                                                </div>
                                              </div>
                                              <div class="col-xl-4">
                                                <div class="form-group">
                                                  <label style="font-size: 15.8px;">Contact Number of the Nodal Officer <br>(નોડલ અધિકારીના સંપર્ક નંબર) <span class="required_filed"> * </span> :</label>
                                                  <input type="text" name="convener_phone" class="landline form-control @error('convener_phone') is-invalid @enderror" maxlength="11" id="convener_phone" value="{{ $val->convener_phone }}">
                                                      @error('convener_phone')
                                                          <div class="text-danger">* {{ $message }}</div>
                                                      @enderror
                                                </div>
                                              </div>
                                            </div>
                                             <div class="row">
                                              <div class="col-xl-6">
                                                  <div class="form-group">
                                                      <label>Mobile Number of the Nodal Officer <br> (નોડલ અધિકારીના મોબાઇલ નંબર) <span class="required_filed"> * </span> :</label>
                                                      <input type="text" id="convener_mobile" class="form-control mobile_number  pattern @error('convener_mobile') is-invalid @enderror" name="convener_mobile"  value="{{ $val->convener_mobile }}" maxlength="10"  />
                                                      @error('convener_mobile')
                                                        <div class="text-danger">* {{ $message }}</div>
                                                      @enderror
                                                  </div>
                                              </div>
                                                <div class="col-xl-6">
                                                  <div class="form-group">
                                                      <label>Email Address of the Nodal Officer <br> (નોડલ અધિકારીનું ઇમેઇલ સરનામું) <span class="required_filed"> * </span> :</label>
                                                      <input type="email" id="convener_email" class="form-control email-input pattern @error('convener_email') is-invalid @enderror" name="convener_email"  value="{{ $val->convener_email }}" />
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
                                                      <input type="text" id="form_scheme_name" class="form-control pattern @error('scheme_name') is-invalid @enderror" name="scheme_name" value="{{ $val->scheme_name }}" />
                                                        @error('scheme_name')
                                                          <div class="text-danger">* {{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-xl-6">
                                                    <div class="form-group">
                                                      <label>Short Name of the scheme/ Programme to be evaluated <br> (મૂલ્યાંકન કરવાની યોજના/કાર્યક્રમનું ટૂંકું નામ)  :</label>
                                                      <input type="text" id="form_scheme_short_name" class="form-control pattern @error('scheme_short_name') is-invalid @enderror" name="scheme_short_name" value="{{ $val->scheme_short_name }}" />
                                                        @error('scheme_short_name')
                                                          <div class="text-danger">* {{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                              </div>
                                              <div class="row">
                                                  <div class="col-xl-4">
                                                    <div class="form-group">
                                                      <label>Name of the Financial Adviser <br>(નાણાકીય સલાહકાર નું નામ) <span class="required_filed"> * </span> :</label>
                                                      <input type="text" name="financial_adviser_name" class="form-control pattern" id="financial_adviser_name" maxlength="100" value="{{$val->financial_adviser_name}}">
                                                    </div>
                                                 </div>
                                                  <div class="col-xl-4">
                                                    <div class="form-group">
                                                      <label>Designation of the Financial Adviser <br>(નાણાકીય સલાહકાર નો હોદ્દો) <span class="required_filed"> * </span> :</label>
                                                      <input type="text" name="financial_adviser_designation" class="form-control pattern"  value="{{$val->financial_adviser_designation}}" id="financial_adviser_designation" maxlength="100">
                                                    </div>
                                                  </div>
                                                  <div class="col-xl-4">
                                                    <div class="form-group">
                                                      <label>Contact Number of the Financial Adviser <br>(નાણાકીય સલાહકાર ના સંપર્ક નંબર) <span class="required_filed"> * </span> :</label>
                                                      <input type="text" name="financial_adviser_phone" class="form-control landline" value="{{$val->financial_adviser_phone}}" id="financial_adviser_phone" maxlength="11">
                                                    </div>
                                                  </div>
                                              </div>
                                               <div class="row">
                                                  <div class="col-xl-6">
                                                      <div class="form-group">
                                                          <label>Mobile Number of the Financial Adviser <br>(નાણાકીય સલાહકાર ના મોબાઇલ નંબર) <span class="required_filed"> * </span> :</label>
                                                          <input type="text" id="financial_adviser_mobile" class="form-control mobile_number pattern @error('financial_adviser_mobile') is-invalid @enderror" name="financial_adviser_mobile"  value="{{$val->financial_adviser_mobile}}" maxlength="10" />
                                                          @error('financial_adviser_mobile')
                                                            <div class="text-danger">* {{ $message }}</div>
                                                          @enderror
                                                      </div>
                                                  </div>
                                                  <div class="col-xl-6">
                                                    <div class="form-group">
                                                        <label>Email Address of the Financial Adviser <br>(નાણાકીય સલાહકારનું ઇમેઇલ સરનામું) <span class="required_filed"> * </span> :</label>
                                                        <input type="email" id="financial_adviser_email" class="form-control email-input pattern @error('financial_adviser_email') is-invalid @enderror" name="financial_adviser_email"  value="{{$val->financial_adviser_email}}" />
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
                                
                                  <!-- first slide -->
                                  <div class="third_slide {{  ($currentSlide  == 3) ? 'active-slide' : '' }}  otherslides" style="{{  ($currentSlide  == 3) ? 'display:block;' : 'display:none;' }}">
                                    <div class="row ">  
                                          <div class="col-xl-12">
                                              <div class="form-group major_objective_parent_div">
                                                <label> Major Objective of the Evaluation study (મૂલ્યાંકન અભ્યાસના મુખ્ય હેતુઓ) <span class="required_filed"> * </span> :</label><br>
                                               
                                                  <div class="room_fields_0">
                                                    <!-- <label>Objective 1: </label> -->
                                                    <textarea class="form-control next_major_objectives @error('major_objective') is-invalid @enderror" id="next_major_objective_textarea" name="major_objective" rows="2">{{  $val->major_objective }}</textarea>
                                                    @error('major_objective')
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
                                                <input type="file" class="custom-file-input file_type_name" name="major_objective_file" id="major_objective_file" accept=".pdf,.docx,.xlsx"/>
                                                <label class="custom-file-label" for="customFile">Choose file</label>
                                              </div>
                                            </div>
                                            @if($val->major_objective_file)
                                                  <div class="form-group">
                                                      {{-- <a href="{{ $replace_url }}/get_the_file/{{ $scheme_id }}/_beneficiaries_coverage" target="_blank"><i class="fas fa-file-pdf fa-2x" style="color:red;"></i></a> --}}
                                                      @php
                                                        $extension = pathinfo($val->major_objective_file, PATHINFO_EXTENSION);
                                                      @endphp
                                                      @if($extension == 'pdf')
                                                          <a href="{{ route('schemes.get_the_file', [Crypt::encrypt($scheme_id), $val->major_objective_file]) }}" target="_blank" title="{{ $val->major_objective_file }}"><i class="fas fa-file-pdf fa-2x" style="color:red;"></i></a>
                                                          @elseif ($extension == 'doc')
                                                          <a href="{{ route('schemes.get_the_file', [Crypt::encrypt($scheme_id), $val->major_objective_file]) }}" download="{{ $val->major_objective_file }}"><i class="fas fa-download fa-2x" style="color:#007bff;"></i></a>

                                                          @else
                                                          <a href="{{ route('schemes.get_the_file', [Crypt::encrypt($scheme_id), $val->major_objective_file]) }}" download="{{ $val->major_objective_file }}"><i class="fas fa-download fa-2x" style="color:green;"></i></a>
                                                      @endif
                                                  </div>
                                                  @else
                                                  No File
                                                  @endif
                                            <!--end::Input-->
                                          </div>
                                      </div>
                                    </div>
                                  <!-- secnod slide close -->
                                  <div class="fourth_slide  {{  ($currentSlide  == 4) ? 'active-slide' : '' }} otherslides" style="{{  ($currentSlide  == 4) ? 'display:block;' : 'display:none;' }}">
                                    <div class="row ">  
                                        <div class="col-xl-12">
                                            <div class="form-group major_indicator_parent_div">
                                              <label>Major Monitoring Indicators for scheme to be evaluated (મૂલ્યાંકન હાથ ધરવાની થતી યોજનાની સમીક્ષાના મુખ્ય માપદંડો) <span class="required_filed"> * </span>:</label><br>
                                              
                                                <div class="indicator_fields_0">
                                                    <!-- <label>Indicator: 1 </label> -->
                                                    <textarea class="form-control next_major_indicators @error('major_indicator') is-invalid @enderror" id="next_major_indicator_textarea" name="major_indicator" rows="2">{{ $val->major_indicator }}</textarea>
                                                    @error('major_indicator')
                                                        <div class="text-danger">{{ $message }}</div>
                                                    @enderror
                                                    <br>
                                                </div>
                                               
                                            </div>
                                            {{-- <button type="button" class="btn btn-primary" id="btn_add_indicator">
                                                <span class="svg-icon svg-icon-2">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                    <rect opacity="0.5" x="11.364" y="20.364" width="16" height="2" rx="1" transform="rotate(-90 11.364 20.364)" fill="white"></rect>
                                                    <rect x="4.36396" y="11.364" width="16" height="2" rx="1" fill="white"></rect>
                                                    </svg>
                                                </span>
                                            Add Indicator</button> --}}
                                        </div>
                                         <div class="col-xl-12">
                                            <!--begin::Input-->
                                            <div class="form-group">
                                              <div class="custom-file">
                                                <input type="file" class="custom-file-input file_type_name" name="major_indicator_file" id="major_indicator_file" accept=".pdf,.docx,.xlsx"/>
                                                <label class="custom-file-label" for="customFile">Choose file</label>
                                              </div>
                                            </div>
                                            @if($val->major_indicator_file)
                                                  <div class="form-group">
                                                      {{-- <a href="{{ $replace_url }}/get_the_file/{{ $scheme_id }}/_beneficiaries_coverage" target="_blank"><i class="fas fa-file-pdf fa-2x" style="color:red;"></i></a> --}}
                                                      @php
                                                        $extension = pathinfo($val->major_indicator_file, PATHINFO_EXTENSION);
                                                      @endphp
                                                      @if($extension == 'pdf')
                                                          <a href="{{ route('schemes.get_the_file', [Crypt::encrypt($scheme_id), $val->major_indicator_file]) }}" target="_blank" title="{{ $val->major_indicator_file }}"><i class="fas fa-file-pdf fa-2x" style="color:red;"></i></a>
                                                          @elseif ($extension == 'doc')
                                                          <a href="{{ route('schemes.get_the_file', [Crypt::encrypt($scheme_id), $val->major_indicator_file]) }}" download="{{ $val->major_indicator_file }}"><i class="fas fa-download fa-2x" style="color:#007bff;"></i></a>

                                                          @else
                                                          <a href="{{ route('schemes.get_the_file', [Crypt::encrypt($scheme_id), $val->major_indicator_file]) }}" download="{{ $val->major_indicator_file }}"><i class="fas fa-download fa-2x" style="color:green;"></i></a>
                                                      @endif
                                                  </div>
                                                  @else
                                                  No File
                                                  @endif
                                            <!--end::Input-->
                                          </div>
                                    </div>
                                  </div>

                                    <!--end: Wizard Step 1-->
                                  <div class="fifth_slide  {{  ($currentSlide  == 5) ? 'active-slide' : '' }} otherslides" style="{{  ($currentSlide  == 5) ? 'display:block;' : 'display:none;' }}">
                                    <!--begin: Wizard Step 2-->
                                    {{-- <div class="pb-0" data-wizard-type="step-content"> --}}
                                     
                                      <div class="row ">
                                        <div class="col-xl-12">
                                          <div class="form-group" style="margin-top: 32px;">
                                            <label>Name of the HOD/ Branch. (કચેરીનું નામ)<span class="required_filed"> * </span> :</label>
                                            <select name="implementing_office[]" class="form-control implementing_office" id="implementing_office" multiple="multiple">
                                              <option value="">Select HOD</option>
                                                @foreach (department_hod_name(Auth::user()->dept_id) as $key => $item)
                                                    <option value="{{ $item->id }}" {{ in_array($item->id, explode(',', $val->implementing_office)) ? 'selected' : '' }}>
                                                        {{ $item->name }}
                                                    </option>
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
                                              @error('implementing_office')
                                                  <div class="text-danger">{{ $message }}</div>
                                              @enderror
                                              
                                          </div>
                                        </div>
                                        
                                      </div>
                                      <div class="row mt-3">
                                            <div class="col-xl-12">
                                               <table class="table table-bordered mt-3 table-responsive" id="hodTable" style="{{ $val->hod_officer_name ? 'display:block;' : 'display:none;' }}">
                                                  <thead class="bg-light">
                                                      <tr>
                                                          <th>SR No</th>
                                                          <th>Name</th>
                                                          <th>Email</th>
                                                          <th>Contact No</th>
                                                          <th>Mobile No</th>
                                                          <th width="60">Action</th>
                                                      </tr>
                                                  </thead>
                                                  <tbody>
                                                      @foreach (explode(',', $val->implementing_office) as $index => $office)
                                                          <tr>
                                                              <td><input type="text" class="form-control" value="{{ $index + 1 }}" disabled></td>
                                                              <td><input type="text" class="form-control hod_officer_name" name="hod_officer_name[]" value="{{ explode(',', $val->hod_officer_name)[$index] ?? '' }}"></td>
                                                              <td><input type="email" class="form-control email-input-td hod_email" name="hod_email[]" value="{{ explode(',', $val->hod_email)[$index] ?? '' }}"></td>
                                                              <td><input type="text" class="form-control implementing_office_contact" name="implementing_office_contact[]" value="{{ explode(',', $val->implementing_office_contact)[$index] ?? '' }}"></td>
                                                              <td><input type="text" class="form-control hod_mobile" name="hod_mobile[]" value="{{ explode(',', $val->hod_mobile)[$index] ?? '' }}"></td>
                                                              <td><button type="button" class="btn btn-danger btn-sm removeRow">X</button></td>
                                                          </tr>
                                                      @endforeach
                                                  </tbody>
                                              </table>
                                              <input type="hidden" name="hod_office" id="hod_office_json">
                                            </div>
                                        </div>
                                      <div class="row">
                                        <div class="col-xl-6">
                                              <div class="form-group">
                                                <label>Name of the Nodal Officer (HOD) (નોડલ અધિકારી નું નામ)<span class="required_filed"> * </span></label>
                                                <input type="text" name="nodal_officer_name" class="form-control pattern" maxlength="100" id="nodal_id" value="{{ $val->nodal_officer_name }}">
                                              </div>
                                        </div>
                                        <div class="col-xl-6">
                                              <div class="form-group">
                                                <label>Designation of Nodal Officer(HOD) (નોડલ અધિકારી હોદ્દો)<span class="required_filed"> * </span> </label>
                                                  <input type="text" name="nodal_officer_designation" class="form-control pattern" value="{{$val->nodal_officer_designation}}" maxlength="100" id="nodal_designation">
                                              </div>
                                        </div>
                                      </div>
                                      <div class="row">
                                        <div class="col-xl-3">
                                            <div class="form-group">
                                              <label>Contact Number of the Nodal Officer (HOD) (નોડલ અધિકારી ના સંપર્ક નંબર)<span class="required_filed"> * </span> :</label>
                                              <input type="text" name="nodal_officer_contact" class="form-control pattern landline" maxlength="11" id="nodal_contact" value="{{$val->nodal_officer_contact}}">
                                            </div>
                                        </div>
                                          <div class="col-xl-3">
                                            <div class="form-group">
                                              <label>Mobile Number of the Nodal Officer (HOD) (નોડલ અધિકારી ના મોબાઇલ નંબર)<span class="required_filed"> * </span> :</label>
                                              <input type="text" name="nodal_officer_mobile" class="form-control mobile_number pattern" maxlength="10" id="nodal_mobile" value="{{$val->nodal_officer_mobile}}">
                                            </div>
                                        </div>
                                        <div class="col-xl-6" style="margin-top: 4%;">
                                            <div class="form-group">
                                              <label>Email of Nodal Officer(HOD)  (નોડલ અધિકારી નું ઇમેઇલ)<span class="required_filed"> * </span> </label>
                                              <input type="text" name="nodal_officer_email" class="form-control pattern" maxlength="100" id="nodal_email" value="{{$val->nodal_officer_email}}">
                                            </div>
                                        </div>
                                      </div>
                                      <div class="row" id="the_ratios">
                                        <div class="col-xl-12"><label>Fund Flow (in %) (યોજના માટેનો નાણાકીય સ્ત્રોત્ર)<span class="required_filed"> * </span> :</label></div>
                                        <div class="col-xl-6">
                                          <div class="form-group">
                                              <div class="row align-items-center">
                                                 <div class="col-xl-6">
                                                      <label>Central Govt.(%) (કેન્દ્ર: %)</label>
                                                      <input type="text" name="center_ratio" class="form-control numberonly state_per" placeholder="Percentage Sponsored by central govt" id="center_ratio" value="{{$val->center_ratio}}">
                                                  </div>
                                                  <div class="col-xl-6">
                                                      <label>State Govt.(%) (રાજ્ય: %)</label>
                                                      <input type="text" name="state_ratio" class="form-control numberonly state_per" placeholder="Percentage Sponsored by state govt" id="state_ratio" value="{{$val->state_ratio}}">
                                                  </div>
                                                
                                              </div>
                                                @if(Session::has('state_center_ratio_error'))
                                              <div class="row">
                                                  <div class="col-xl-12">
                                                      <p class="text-red">{{ Session::get('state_center_ratio_error') }}</p>
                                                  </div>
                                              </div>
                                            @endif
                                          </div>
                                        </div>
                                      
                                        <div class="col-xl-6" style="margin-top: -2%;">
                                           <label>Other:</label>
                                            <textarea name="both_ration" class="form-control pattern" placeholder="Remarks" id="both_ration">{{$val->both_ration}}</textarea>
                                                      {{-- <input type="text" name="both_ratio" class="form-control" placeholder="Remarks" id="both_ration" value="{{$val->both_ration}}"> --}}
                                          {{-- <label>Name of Implementing office (અમલીકરણ ઓફિસ નું નામ)<span class="required_filed"> * </span> :</label>
                                          <input type="text" name="hod_name" class="form-control pattern" id="hod_name" value="{{ $val->hod_name }}"> --}}
                                        </div>
                                      </div>

                                      <div class="row">
                                        <div class="col-xl-12">
                                          <label>Overview of the scheme/Background of the scheme (યોજનાની પ્રાથમિક માહિતી/યોજનાનો પરિચય) <span class="required_filed"> * </span> : <small><b>At most 250 words (વધુમાં વધુ ૨૫૦ શબ્દોમાં)</b></small></label>
                                          <textarea class="form-control pattern" id="next_scheme_overview" name="scheme_overview" maxlength="3000">{{$val->scheme_overview}}</textarea>
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
                                      @if($val->next_scheme_overview_file)
                                      <input type="hidden" class="existing_next_scheme_overview_file" name="existing_next_scheme_overview_file" value="{{$val->next_scheme_overview_file}}" />
                                        <div class="form-group">
                                            @php
                                              $extension = pathinfo($val->next_scheme_overview_file, PATHINFO_EXTENSION);
                                            @endphp
                                            @if($extension == 'pdf')
                                                <a href="{{ route('schemes.get_the_file', [Crypt::encrypt($scheme_id), $val->next_scheme_overview_file]) }}" target="_blank" title="{{ $val->next_scheme_overview_file }}"><i class="fas fa-file-pdf fa-2x" style="color:red;"></i></a>
                                            @endif
                                        </div>
                                      @else
                                        No File
                                      @endif
                                      <!--begin::Input-->
                                     
                                        <div class="form-group">
                                          <label>Objectives of the scheme (યોજનાના હેતુઓ) <span class="required_filed"> * </span> : <small><b>Max 3000 characters</b></small></label>
                                          <textarea class="form-control pattern" id="next_scheme_objective" name="scheme_objective" maxlength="3000">{{$val->scheme_objective}}</textarea>
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
                                      @if($val->scheme_objective_file)
                                      <input type="hidden" class="existing_scheme_objective_file" name="existing_scheme_objective_file" value="{{$val->scheme_objective_file}}" />
                                      <div class="form-group">
                                          @php
                                            $extension = pathinfo($val->scheme_objective_file, PATHINFO_EXTENSION);
                                          @endphp
                                          @if($extension == 'pdf')
                                              <a href="{{ route('schemes.get_the_file', [Crypt::encrypt($scheme_id), $val->scheme_objective_file]) }}" target="_blank" title="{{ $val->scheme_objective_file }}"><i class="fas fa-file-pdf fa-2x" style="color:red;"></i></a>
                                          @endif
                                      </div>
                                      @else
                                        No File
                                      @endif
                                      <!--end::Input-->
                                      <!--begin::Input-->
                                      <div class="form-group">
                                        <label>Name of Sub-schemes/components (પેટા યોજનાનું નામ અને ઘટકો) <span class="required_filed"> * </span> : <small><b>Max 3000 characters</b></small></label>
                                        <textarea class="form-control pattern" id="next_scheme_components" name="sub_scheme" maxlength="3000">{{$val->sub_scheme}}</textarea>
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
                                      @if($val->next_scheme_components_file)
                                      <input type="hidden" class="existing_next_scheme_components_file" name="existing_next_scheme_components_file" value="{{$val->next_scheme_components_file}}" />
                                      <div class="form-group">
                                          @php
                                            $extension = pathinfo($val->next_scheme_components_file, PATHINFO_EXTENSION);
                                          @endphp
                                          @if($extension == 'pdf')
                                              <a href="{{ route('schemes.get_the_file', [Crypt::encrypt($scheme_id), $val->next_scheme_components_file]) }}" target="_blank" title="{{ $val->next_scheme_components_file }}"><i class="fas fa-file-pdf fa-2x" style="color:red;"></i></a>
                                          @endif
                                      </div>
                                      @else
                                        No File
                                      @endif
                                      <!--end::Input-->
                                      {{-- </div> --}}
                                  </div>
                                  <!-- fourth_slide close -->
                                  <div class="sixth_slide {{  ($currentSlide  == 6) ? 'active-slide' : '' }} otherslides" style="{{  ($currentSlide  == 6) ? 'display:block;' : 'display:none;' }}">
                                    {{-- <div class="pb-5" data-wizard-type="step-content"> --}}
                                        <div class="row ">
                                          <div class="col-xl-4 col-sm-4">
                                            <!--begin::Input-->
                                            <div class="form-group">
                                              <label>Year of actual commencement of the scheme (યોજનાનું ખરેખર અમલીકરણ શરૂ કર્યા વર્ષ) <span class="required_filed"> * </span> :</label>
                                              <select name="commencement_year" class="form-control" id="commencement_year">
                                                  <option>Select year</option>
                                                  @foreach ($financial_years as $year_item)
                                                  <option value="{{$year_item}}" {{($val->commencement_year == $year_item) ? 'selected' : ''}}>{{$year_item}}</option>
                                                  @endforeach
                                              </select>
                                              {{-- <input class="form-control" onkeyup="fin_year(this.value)" type="text" name="commencement_year" id="commencement_year" value="{{$val->commencement_year}}" /> --}}
                                              <span id="fin_year_span" style="color:red"></span>
                                            </div>
                                            
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
                                                    <input type="radio" name="scheme_status" value="N"/>
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
                                              <label>Sustainable Development Goals (SDG) (સસ્ટેનેબલ ડેવલપમેન્ટ ગોલ) <span class="required_filed"> * </span> :</label>
                                              <div class="row">
                                                    @php $issdg = array(); 
                                                    $issdg = json_decode($val->is_sdg); 
                                                    @endphp
                                                    @foreach($goals as $k => $g)
                                                    <div class="col-xl-4">
                                                        <div class="form-group form-check">
                                                            @if(!empty($issdg) and in_array($g->goal_id,$issdg))
                                                                <input type="checkbox" checked name="is_sdg[]" class="form-check-input" id="goal1" value="{{ $g->goal_id }}">
                                                            @else
                                                                <input type="checkbox" name="is_sdg[]" class="form-check-input" id="goal1" value="{{ $g->goal_id }}">
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
                                    {{-- </div> --}}
                                  </div>
                                
                                  <!-- fifth_slide close -->
                                  <div class="seventh_slide {{  ($currentSlide  == 7) ? 'active-slide' : '' }} otherslides" style="{{  ($currentSlide  == 7) ? 'display:block;' : 'display:none;' }}">
                                    {{-- <div class="pb-5" data-wizard-type="step-content"> --}}
                                          <div class="row ">
                                            <div class="col-xl-12">
                                              <!--begin::Input-->
                                              <div class="form-group">
                                                <label>Beneficiary/Community selection Criteria (લાભાર્થી/સમુદાયની પાત્રતા માટેના માપદંડો) <span class="required_filed"> * </span> : </label>
                                              </div>
                                              <div class="form-group" id="beneficiary_selection_div_0">
                                                <label>Beneficiary Criteria :<small><b>Max 3000 characters</b></small></label>
                                                <textarea class="form-control next_beneficiary_selection_criterias pattern" id="next_beneficiary_selection_criteria" name="beneficiary_selection_criteria[0][beneficiary_selection_criteria]" rows="2" maxlength="3000">{{ $val->scheme_beneficiary_selection_criteria }}</textarea>
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
                                          @if($val->beneficiary_selection_criteria_file)
                                          <input type="hidden" class="existing_beneficiary_selection_criteria_file" name="existing_beneficiary_selection_criteria_file" value="{{$val->beneficiary_selection_criteria_file}}" />
                                          <div class="form-group">
                                              @php
                                                $extension = pathinfo($val->beneficiary_selection_criteria_file, PATHINFO_EXTENSION);
                                              @endphp
                                              @if($extension == 'pdf')
                                                  <a href="{{ route('schemes.get_the_file', [Crypt::encrypt($scheme_id), $val->beneficiary_selection_criteria_file]) }}" target="_blank" title="{{ $val->beneficiary_selection_criteria_file }}"><i class="fas fa-file-pdf fa-2x" style="color:red;"></i></a>
                                              @endif
                                          </div>
                                          @else
                                            No File
                                          @endif
                                      {{-- </div> --}}
                                  </div>

                                  <!-- sixth_slide close -->
                                  <div class="eighth_slide {{  ($currentSlide  == 8) ? 'active-slide' : '' }} otherslides" style="{{  ($currentSlide  == 8) ? 'display:block;' : 'display:none;' }}">
                                    {{-- <form method="post" id="seventh_slide_form" enctype="multipart/form-data"> --}}
                                            <input type="hidden" name="draft_id" id="next_draft_id" value="{{ $val->draft_id }}">
                                            <input type="hidden" name="scheme_id" id="next_scheme_id" value="{{ $scheme_id }}">
                                          {{-- @csrf
                                          <input type="hidden" name="slide" value="seventh"> --}}
                                          <div class="row ">
                                            <div class="col-xl-12">
                                              <!--begin::Input-->
                                              <div class="form-group">
                                                <label>Expected Major Benefits Derived from the Scheme (યોજના ના અપેક્ષિત મુખ્ય લાભો)<span class="required_filed"> * </span> :  </label>
                                              </div>
                                              <!--end::Input-->
                                            </div>
                                          </div>

                                          <div class="row">
                                            <div class="col-xl-12">

                                                <div class="form-group" id="major_benefits_div_0">
                                                  <label>Major Benefit :<span class="required_filed"> * </span> : <small><b> Max 3000 characters </b></small> </label>
                                                  <div>
                                                    <textarea class="form-control major_benefit_textareas pattern" name="major_benefits_text[0][major_benefits_text]" id="major_benefit_textarea_0" rows="2" maxlength="3000">{{ $val->major_benefits_text }}</textarea>
                                                  </div>
                                                </div>
                                               
                                            </div>
                                            {{-- <div class="col-xl-12">
                                                <button type="button" class="btn btn-xs btn-primary" id="btn_add_major_benefit" style="margin-top:20px">
                                                    <!--begin::Svg Icon | path: icons/duotune/arrows/arr075.svg-->
                                                    <span class="svg-icon svg-icon-2">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                        <rect opacity="0.5" x="11.364" y="20.364" width="16" height="2" rx="1" transform="rotate(-90 11.364 20.364)" fill="white"></rect>
                                                        <rect x="4.36396" y="11.364" width="16" height="2" rx="1" fill="white"></rect>
                                                        </svg>
                                                    </span>
                                                Add Major Benefit</button>
                                            </div> --}}
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
                                              <div class="form-group">
                                                @if($val->benefit_to_file)
                                                @php
                                                  $extension = pathinfo($val->benefit_to_file, PATHINFO_EXTENSION);
                                                @endphp
                                                @if($extension == 'pdf')
                                                    <a href="{{ route('schemes.get_the_file', [Crypt::encrypt($scheme_id), $val->benefit_to_file]) }}" target="_blank" title="{{ $val->benefit_to_file }}"><i class="fas fa-file-pdf fa-2x" style="color:red;"></i></a>
                                                    @elseif ($extension == 'doc')
                                                    <a href="{{ route('schemes.get_the_file', [Crypt::encrypt($scheme_id), $val->benefit_to_file]) }}" download="{{ $val->benefit_to_file }}"><i class="fas fa-download fa-2x" style="color:#007bff;"></i></a>

                                                    @else
                                                    <a href="{{ route('schemes.get_the_file', [Crypt::encrypt($scheme_id), $val->benefit_to_file]) }}" download="{{ $val->benefit_to_file }}"><i class="fas fa-download fa-2x" style="color:green;"></i></a>
                                                @endif
                                                  {{-- <a href="{{ $replace_url }}/get_the_file/{{ $scheme_id }}/_major_benefits" target="_blank"><i class="fas fa-file-pdf fa-2x" style="color:red;"></i></a> --}}
                                                @endif
                                              </div>
                                            </div>
                                          </div>
                                          {{-- <button type="submit" id="btn_seventh_slide_submit" style="visibility: hidden;"></button>
                                      </form> --}}
                                  </div>

                                  <!-- seventh_slide close -->
                                  <div class="nineth_slide {{  ($currentSlide  == 9) ? 'active-slide' : '' }} otherslides" style="{{  ($currentSlide  == 9) ? 'display:block;' : 'display:none;' }}">
                                    {{-- <form method="post" enctype="multipart/form-data" id="eighth_slide_form"> --}}
                                      <input type="hidden" name="draft_id" id="next_draft_id" value="{{ $val->draft_id }}">
                                      <input type="hidden" name="scheme_id" id="next_scheme_id" value="{{ $scheme_id }}">
                                        {{-- @csrf
                                        <input type="hidden" name="slide" value="eighth"> --}}
                                      {{-- <div class="pb-5" data-wizard-type="step-content"> --}}
                                        <div class="row ">
                                          <div class="col-xl-12">
                                            <!--begin::Input-->
                                            <div class="form-group">
                                              <label>Implementing procedure of the Scheme (યોજનાની અમલીકરણ માટેની પ્રક્રિયા.)<span class="required_filed"> * </span> : <small><b>Max 3000 characters</b></small></label>
                                              <textarea class="form-control pattern" id="next_scheme_implementing_procedure" name="scheme_implementing_procedure" maxlength="3000">{{ $val->scheme_implementing_procedure }}</textarea>
                                            </div>
                                            <!--end::Input-->
                                          </div>
                                        </div> 

                                        <div class="row">
                                          <div class="col-xl-12">
                                            <label>Administrative set up for Implementation of the scheme (યોજનાના અમલીકરણ માટેનું વહીવટી માળખું) <br>Geographical Coverage: From State to beneficiaries (રાજ્યકક્ષાથી લઈ લાભાર્થી સુધીનો ભૌગોલિક વ્યાપ) <span class="required_filed"> * </span> : </label>
                                            {{-- <select name="beneficiariesGeoLocal" class="form-control" id="beneficiariesGeoLocal" onchange="fngetdist(this.value)"> --}}
                                              <select name="beneficiariesGeoLocal" class="form-control" id="beneficiariesGeoLocal">

                                              <option value="">Select Coverage Area</option>
                                                @foreach($beneficiariesGeoLocal as $key=> $value)
                                                    <option value="{{$value->id}}" @if($value->id == $val->beneficiariesGeoLocal) selected @endif>{{$value->name}}</option>
                                                @endforeach
                                            </select>
                                            <select name="taluka_id" class="form-control" id="taluka_id" style="display: none;">
                                              <option value="">Select District</option>
                                            </select>
                                            <div id="load_gif_img"></div>
                                            <label style="margin-top:20px">Remarks</label>
                                            <!-- <input type="text" name="otherbeneficiariesGeoLocal" placeholder="other Geographical beneficiaries coverage" class="form-control"> -->
                                            <textarea name="otherbeneficiariesGeoLocal" id="next_otherbeneficiariesGeoLocal" placeholder="other Geographical beneficiaries coverage areas or Remarks" class="form-control pattern" rows="2">{{$val->otherbeneficiariesGeoLocal}}</textarea>
                                            <div></div>
                                              <div class="custom-file" style="margin:20px 0px">
                                                <input type="file" class="custom-file-input file_type_name" name="geographical_coverage" id="geographical_coverage" accept=".pdf,.docx,.xlsx"/>
                                                <label class="custom-file-label" for="customFile">Choose file</label>
                                              </div>
                                              @if($val->geographical_coverage)
                                                  @php
                                                    $extension = pathinfo($val->geographical_coverage, PATHINFO_EXTENSION);
                                                  @endphp
                                                  @if($extension == 'pdf')
                                                      <a href="{{ route('schemes.get_the_file', [Crypt::encrypt($scheme_id), $val->geographical_coverage]) }}" target="_blank" title="{{ $val->geographical_coverage }}"><i class="fas fa-file-pdf fa-2x" style="color:red;"></i></a>
                                                      @elseif ($extension == 'doc')
                                                      <a href="{{ route('schemes.get_the_file', [Crypt::encrypt($scheme_id), $val->geographical_coverage]) }}" download="{{ $val->geographical_coverage }}"><i class="fas fa-download fa-2x" style="color:#007bff;"></i></a>

                                                      @else
                                                      <a href="{{ route('schemes.get_the_file', [Crypt::encrypt($scheme_id), $val->geographical_coverage]) }}" download="{{ $val->geographical_coverage }}"><i class="fas fa-download fa-2x" style="color:green;"></i></a>
                                                  @endif
                                              @endif
                                              {{-- @if($val->geographical_coverage)
                                                <a href="{{ $replace_url }}/get_the_file/{{ $scheme_id }}/_geographical_coverage" target="_blank"><i class="fas fa-file-pdf fa-2x" style="color:red;"></i></a>
                                              @endif --}}
                                          </div>
                                        </div>
                                      {{-- </div> --}}
                                        {{-- <button type="submit" id="btn_eighth_slide_submit" style="visibility: hidden;"></button>
                                    </form> --}}
                                  </div>
                                 
                                  <!-- eighth_slide close -->
                                  <div class="tenth_slide {{  ($currentSlide  == 10) ? 'active-slide' : '' }} otherslides" style="{{  ($currentSlide  == 10) ? 'display:block;' : 'display:none;' }}">
                                     {{-- <form method="post" enctype="multipart/form-data" id="nineth_slide_form"> --}}
                                             <input type="hidden" name="draft_id" id="next_draft_id" value="{{ $val->draft_id }}">
                                            <input type="hidden" name="scheme_id" id="next_scheme_id" value="{{ $scheme_id }}">
                                          {{-- @csrf
                                          <input type="hidden" name="slide" value="nineth"> --}}
                                              <div class="row ">  
                                                <div class="col-xl-12">
                                                  <label>Scheme coverage since inception of the scheme (યોજનાની શરૂઆતથી અત્યાર સુધીનો વ્યાપ)</label>
                                                </div>
                                              </div>
                                              <div class="row">
                                                <div class="col-xl-12">
                                                  <div class="form-group">
                                                    <label>Coverage of Beneficiary/Community (લાભાર્થી/સમુદાયનો સમાવેશ) <span class="required_filed"> * </span> : <small><b>Max 3000 characters</b></small> </label>
                                                    <div></div>
                                                    <div class="custom-file">
                                                      <textarea name="coverage_beneficiaries_remarks" id="next_coverage_beneficiaries_remarks" class="form-control pattern" rows="2" maxlength="3000">{{$val->coverage_beneficiaries_remarks}}</textarea>
                                                    </div>
                                                  </div>
                                                </div>
                                              </div>
                                              <div style="margin-top: 10px"></div>
                                              <div class="row">
                                                <div class="col-xl-12">
                                                  <!--begin::Input-->
                                                  <div class="form-group">
                                                    <label>Coverage of Beneficiary/Community (લાભાર્થી/સમુદાયનો સમાવેશ) : </label>
                                                    <div></div>
                                                    <div class="custom-file">
                                                      <input type="file" class="custom-file-input file_type_name" name="beneficiaries_coverage" id="beneficiaries_coverage" accept=".pdf,.docx,.xlsx"/>
                                                      <label class="custom-file-label" for="customFile">Choose file</label>
                                                    </div>
                                                  </div>
                                                  @if($val->beneficiaries_coverage)
                                                  <div class="form-group">
                                                      {{-- <a href="{{ $replace_url }}/get_the_file/{{ $scheme_id }}/_beneficiaries_coverage" target="_blank"><i class="fas fa-file-pdf fa-2x" style="color:red;"></i></a> --}}
                                                      @php
                                                        $extension = pathinfo($val->beneficiaries_coverage, PATHINFO_EXTENSION);
                                                      @endphp
                                                      @if($extension == 'pdf')
                                                          <a href="{{ route('schemes.get_the_file', [Crypt::encrypt($scheme_id), $val->beneficiaries_coverage]) }}" target="_blank" title="{{ $val->beneficiaries_coverage }}"><i class="fas fa-file-pdf fa-2x" style="color:red;"></i></a>
                                                          @elseif ($extension == 'doc')
                                                          <a href="{{ route('schemes.get_the_file', [Crypt::encrypt($scheme_id), $val->beneficiaries_coverage]) }}" download="{{ $val->beneficiaries_coverage }}"><i class="fas fa-download fa-2x" style="color:#007bff;"></i></a>

                                                          @else
                                                          <a href="{{ route('schemes.get_the_file', [Crypt::encrypt($scheme_id), $val->beneficiaries_coverage]) }}" download="{{ $val->beneficiaries_coverage }}"><i class="fas fa-download fa-2x" style="color:green;"></i></a>
                                                      @endif
                                                  </div>
                                                  @else
                                                  No File
                                                  @endif
                                                  <!--end::Input-->
                                                </div>
                                              </div>
                                              <div style="margin-top: 10px"></div>
                                              <div class="row">
                                                <div class="col-xl-12">
                                                  <div class="form-group">
                                                    <label>Training/Capacity building of facilitators (સંબંધિતોની તાલીમ/ક્ષમતા નિર્માણ માટેની કામગીરી) <span class="required_filed"> * </span> : <small><b>Max 3000 characters</b></small> </label>
                                                    <div></div>
                                                    <div class="custom-file">
                                                      <textarea name="training_capacity_remarks" id="next_training_capacity_remarks" class="form-control pattern" rows="2" maxlength="3000">{{ $val->training_capacity_remarks }}</textarea>
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
                                                      <input type="file" class="custom-file-input file_type_name" name="training" id="training" accept=".pdf,.docx,.xlsx"/>
                                                      <label class="custom-file-label" for="customFile">Choose file</label>
                                                    </div>
                                                  </div>
                                                  @if($val->training)
                                                  <div class="form-group">
                                                      {{-- <a href="{{ $replace_url }}/get_the_file/{{ $scheme_id }}/_training" target="_blank"><i class="fas fa-file-pdf fa-2x" style="color:red;"></i></a> --}}
                                                      @php
                                                      $extension = pathinfo($val->training, PATHINFO_EXTENSION);
                                                      @endphp
                                                      @if($extension == 'pdf')
                                                          <a href="{{ route('schemes.get_the_file', [Crypt::encrypt($scheme_id), $val->training]) }}" target="_blank" title="{{ $val->training }}"><i class="fas fa-file-pdf fa-2x" style="color:red;"></i></a>
                                                      @elseif ($extension == 'doc')
                                                      <a href="{{ route('schemes.get_the_file', [Crypt::encrypt($scheme_id), $val->training]) }}" download="{{ $val->training }}"><i class="fas fa-download fa-2x" style="color:#007bff;"></i></a>

                                                      @else
                                                          <a href="{{ route('schemes.get_the_file', [Crypt::encrypt($scheme_id), $val->training]) }}" download="{{ $val->training }}"><i class="fas fa-download fa-2x" style="color:green;"></i></a>
                                                      @endif
                                                  </div>
                                                  @else
                                                  No File
                                                  @endif
                                                </div>
                                              </div>
                                              <div style="margin-top: 10px"></div>
                                              <div class="row">
                                                <div class="col-xl-12">
                                                  <div class="form-group">
                                                    <label>IEC activities (પ્રચાર પ્રસારની કામગીરી) <span class="required_filed"> * </span> : <small><b>Max 3000 characters</b></small> </label>
                                                    <div></div>
                                                    <div class="custom-file">
                                                      <textarea name="iec_activities_remarks" id="next_iec_activities_remarks" class="form-control pattern" rows="2" maxlength="3000">{{ $val->iec_activities_remarks }}</textarea>
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
                                                      <input type="file" class="custom-file-input file_type_name" name="iec_file" id="iec" accept=".pdf,.docx,.xlsx"/>
                                                      <label class="custom-file-label" for="customFile">Choose file</label>
                                                    </div>
                                                  </div>
                                                  @if($val->iec)
                                                  <div class="form-group">
                                                      @php
                                                          $extension = pathinfo($val->iec, PATHINFO_EXTENSION);
                                                      @endphp
                                                      @if($extension == 'pdf')
                                                          <a href="{{ route('schemes.get_the_file', [Crypt::encrypt($scheme_id), $val->iec]) }}" target="_blank" title="{{ $val->iec }}"><i class="fas fa-file-pdf fa-2x" style="color:red;"></i></a>
                                                      @elseif ($extension == 'doc')
                                                      <a href="{{ route('schemes.get_the_file', [Crypt::encrypt($scheme_id), $val->iec]) }}" download="{{ $val->iec }}"><i class="fas fa-download fa-2x" style="color:#007bff;"></i></a>

                                                      @else
                                                          <a href="{{ route('schemes.get_the_file', [Crypt::encrypt($scheme_id), $val->iec]) }}" download="{{ $val->iec }}"><i class="fas fa-download fa-2x" style="color:green;"></i></a>
                                                      @endif
                                                  </div>
                                                  @else
                                                  No File.
                                                  @endif
                                              
                                                  <!--end::Input-->
                                                </div>
                                              </div>
                                              {{-- <button type="submit" id="btn_nineth_slide_submit" style="visibility: hidden;"></button>
                                          </form> --}}
                                  </div>

                                    <!-- nineth_slide close -->
                                    <div class="eleventh_slide {{  ($currentSlide  == 11) ? 'active-slide' : '' }} otherslides" style="{{  ($currentSlide  == 11) ? 'display:block;' : 'display:none;' }}">
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
                                                  <option value="">Select Beneficiary Group</option>
                                                  <option value="Individual" @if($val->benefit_to == 'Individual') selected @endif >Individual - વ્યક્તિગત</option>
                                                  <option value="Community" @if($val->benefit_to == 'Community') selected @endif>Community - સમુદાય</option>
                                                  <option value="Both" @if($val->benefit_to == 'Both') selected @endif>Both</option>
                                              </select>
                                            </div>
                                            <!--end::Input-->
                                          </div>
                                        </div>
                                        <input type="hidden" name="convergencewithotherscheme" value="Own_Department">
                                        @php $convergences = json_decode($val->all_convergence); @endphp
                                        @if(!empty($convergences))
                                        <label class="col-xl-12">Convergence with other scheme (અન્ય યોજનાઓ સાથે યોજનાનું જોડાણ)</label>
                                        @foreach($convergences as $kconv => $vconv)
                                          <div class="row countallconvergence" id="convergence_row_{{$kconv}}">
                                            <div class="col-xl-6">
                                              <label></label>
                                              <textarea placeholder="Convergence Process" name="convergence_text[]" id="next_convergence_text" rows="1" class="form-control pattern">{{ $vconv->dept_remarks }}</textarea>
                                            </div>
                                            <div class="col-xl-5">
                                              <label></label>
                                              <select name="convergence_dept_ids[]" id="next_convergence_dept_id" class="form-control">
                                                <option value="">Own Department (પોતાના વિભાગ સાથે)</option>
                                                @foreach($departments as $depts)
                                                  <option value="{{ $depts->dept_id }}" @if($depts->dept_id == $vconv->dept_id) selected @endif>{{ $depts->dept_name }}</option>
                                                @endforeach
                                              </select>
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
                                            <label class="col-xl-12">Convergence with other scheme (અન્ય યોજનાઓ સાથે યોજનાનું જોડાણ)</label>
                                            <div class="col-xl-5">
                                              <label></label>
                                              <select name="convergence_dept_ids[]" id="next_convergence_dept_id" class="form-control">
                                                <option value="">Own Department (પોતાના વિભાગ સાથે)</option>
                                                @foreach($departments as $depts)
                                                  <option value="{{ $depts->dept_id }}">{{ $depts->dept_name }}</option>
                                                @endforeach
                                              </select>
                                            </div>
                                            <div class="col-xl-6">
                                              <label></label>
                                              <textarea placeholder="Remarks" name="convergence_text[]" id="next_convergence_text" rows="1" class="form-control pattern"></textarea>
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
                                    <div class="twelth_slide {{  ($currentSlide  == 12) ? 'active-slide' : '' }} otherslides" style="{{  ($currentSlide  == 12) ? 'display:block;' : 'display:none;' }}">
                                       {{-- <form method="post" enctype="multipart/form-data" id="eleventh_slide_form"> --}}
                                          <input type="hidden" name="draft_id" id="next_draft_id" value="{{ $val->draft_id }}">
                                          <input type="hidden" name="scheme_id" id="next_scheme_id" value="{{ $scheme_id }}">
                                           {{-- @csrf
                                           <input type="hidden" name="slide" value="eleventh"> --}}
                                            <div class="row ">
                                              <div class="col-xl-12">
                                                <label>Scheme Related all relevant Literature (યોજના સંબંધિત સાહિત્ય)</label> 
                                              </div>
                                            </div>
                                              <div class="row mt-3">
                                                <div class="col-xl-10">
                                                    <div class="form-group">
                                                        <label>GR (ઠરાવ) <span class="required_filed">*</span>:</label>
                                                        <span style="color: #5b6064; margin-left: 15px;">You can upload multiple files</span>

                                                        <div id="gr_file_wrap">
                                                            {{-- Existing uploaded files --}}
                                                            @if(isset($val->gr_file) && $val->gr_file->count() > 0)
                                                                @foreach($val->gr_file as $file)
                                                                    @php
                                                                        $extension = pathinfo($file->file_name, PATHINFO_EXTENSION);
                                                                    @endphp
                                                                    <div class="existing-file d-flex align-items-center mb-2">
                                                                        @if($extension == 'pdf')
                                                                            <a href="{{ route('schemes.get_the_file', [$val->scheme_id, $file->file_name]) }}" target="_blank" title="{{ $file->file_name }}">
                                                                                <i class="fas fa-file-pdf fa-2x" style="color:red;"></i>
                                                                            </a>
                                                                        @elseif(in_array($extension, ['doc', 'docx']))
                                                                            <a href="{{ route('schemes.get_the_file', [$val->scheme_id, $file->file_name]) }}" download="{{ $file->file_name }}">
                                                                                <i class="fas fa-file-word fa-2x" style="color:#007bff;"></i>
                                                                            </a>
                                                                        @elseif(in_array($extension, ['xls', 'xlsx']))
                                                                            <a href="{{ route('schemes.get_the_file', [$val->scheme_id, $file->file_name]) }}" download="{{ $file->file_name }}">
                                                                                <i class="fas fa-file-excel fa-2x" style="color:green;"></i>
                                                                            </a>
                                                                        @endif
                                                                        <span class="ml-2">{{ $file->file_name }}</span>
                                                                        {{-- Option to delete this file (optional) --}}
                                                                        <button type="button" class="btn btn-danger btn-sm ml-3 remove-existing-file"
                                                                            data-file-id="{{ $file->id }}">
                                                                            Remove
                                                                        </button>
                                                                    </div>
                                                                @endforeach
                                                            @endif

                                                            {{-- New upload inputs (initial one) --}}
                                                            <div class="gr-row d-flex align-items-center mb-2">
                                                                <div class="custom-file">
                                                                    <input type="file" class="custom-file-input file_type_name" name="gr[]" accept=".pdf,.doc,.docx,.xlsx" />
                                                                    <label class="custom-file-label">Choose file</label>
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

                                                
                                            {{-- <div class="row mt-3">
                                              <div class="col-xl-4">
                                                <!--begin::Input-->
                                                <div class="form-group">
                                                  <label>GR (ઠરાવો) <span class="required_filed"> * </span> : </label> <span style="color: #5b6064; margin-left:15px;">You can uploaded multiple files</span>
                                                  <div class="custom-file">
                                                    <input type="file" class="custom-file-input next_gr_files file_type_name" id="gr" name="gr[]" multiple accept=".pdf,.docx,.xlsx"/>
                                                    <label class="custom-file-label" for="customFile">Choose file</label>
                                                  </div>
                                                </div>
                                                @if($gr_files->count())
                                                <div class="form-group">
                                                    @if($val->gr_file->count() > 0)
                                                      @foreach($val->gr_file as $kgrs => $vgrs)
                                                        @php
                                                          $extension = pathinfo($vgrs->file_name, PATHINFO_EXTENSION);
                                                        @endphp
                                                        @if($extension == 'pdf')
                                                            <a href="{{ route('schemes.get_the_file', [$val->scheme_id, $vgrs->file_name]) }}" target="_blank" title="{{ $vgrs->file_name }}"><i class="fas fa-file-pdf fa-2x" style="color:red;"></i></a>
                                                        @elseif ($extension == 'doc')
                                                        <a href="{{ route('schemes.get_the_file', [$val->scheme_id, $vgrs->file_name]) }}" download="{{ $vgrs->file_name }}"><i class="fas fa-download fa-2x" style="color:#007bff;"></i></a>
                                                        @else
                                                            <a href="{{ route('schemes.get_the_file', [$val->scheme_id, $vgrs->file_name]) }}" download="{{ $vgrs->file_name }}"><i class="fas fa-download fa-2x" style="color:green;"></i></a>
                                                        @endif
                                                      
                                                        @endforeach
                                                     @endif
                                                </div>
                                                @endif
                                                <!--end::Input-->
                                              </div>
                                              {{-- @php
                                                $gr_date = null;
                                                $gr_number = null;
                                                if($gr_files->count() > 0){
                                                  $gr_date = $gr_files[0]['gr_date'];
                                                  $gr_number = $gr_files[0]['gr_number'];
                                                }

                                              @endphp
                                              <div class="col-xl-4">
                                                <div class="form-group">
                                                  <label for="gr_date">GR Date(ઠરાવો તારીખ ):</label>
                                                    <input type="text" class="gr_date datepicker form-control pattern" id="gr_date" name="gr_date" value="{{$gr_date}}"/>
                                                </div>
                                              </div>
                                              <div class="col-xl-4">
                                                <div class="form-group">
                                                  <label for="gr_number">GR Number (ઠરાવો નંબર):</label>
                                                    <input type="text" class="gr_number form-control pattern" id="gr_number" name="gr_number" value="{{$gr_number}}"/>
                                                </div>
                                              </div> 
                                            
                                            </div> --}}
                                            <div class="row">
                                              <div class="col-xl-6">
                                                <!--begin::Input-->
                                                <div class="form-group">
                                                  <label>Notification  (જાહેરનામાં)<span class="required_filed"> * </span> : </label><span style="color: #5b6064; margin-left:15px;">You can uploaded multiple files</span>
                                                  
                                                  <div class="custom-file">
                                                    <input type="file" class="custom-file-input next_notification_files file_type_name" id="notification" name="notification[]" multiple accept=".pdf,.docx,.xlsx" />
                                                    <label class="custom-file-label" for="customFile">Choose file</label>
                                                  </div>
                                                </div>
                                                @if($notifications->count())
                                                <div class="form-group">
                                                    @if ($val->notification_files->count() > 0)
                                                      @foreach($val->notification_files as $kgrs => $items)
                                                        @php
                                                          $extension = pathinfo($items->file_name, PATHINFO_EXTENSION);
                                                        @endphp
                                                        @if($extension == 'pdf')
                                                            <a href="{{ route('schemes.get_the_file', [$val->scheme_id, $items->file_name]) }}" target="_blank" title="{{ $items->file_name }}"><i class="fas fa-file-pdf fa-2x" style="color:red;"></i></a>
                                                        @elseif ($extension == 'doc')
                                                            <a href="{{ route('schemes.get_the_file', [$val->scheme_id, $items->file_name]) }}" download="{{ $items->file_name }}"><i class="fas fa-download fa-2x" style="color:#007bff;"></i></a>
                                                        @else
                                                            <a href="{{ route('schemes.get_the_file', [$val->scheme_id, $items->file_name]) }}" download="{{ $items->file_name }}"><i class="fas fa-download fa-2x" style="color:green;"></i></a>
                                                        @endif
                                                     
                                                      @endforeach
                                                    @endif
                                                </div>
                                                @endif
                                                <!--end::Input-->
                                              </div>
                                              <div class="col-xl-6">
                                                <!--begin::Input-->
                                                <div class="form-group">
                                                  <label>Brochure (બ્રોશર) : </label><span style="color: #5b6064; margin-left:15px;">You can uploaded multiple files</span>
                                                  
                                                  <div class="custom-file">
                                                    <input type="file" class="custom-file-input next_brochure_files file_type_name" id="brochure" name="brochure[]" multiple accept=".pdf,.docx,.xlsx"/>
                                                    <label class="custom-file-label" for="customFile">Choose file</label>
                                                  </div>
                                                </div>
                                                @if($brochures->count())
                                                <div class="form-group">
                                                  @if($val->brochure_files->count() > 0)
                                                      @foreach($val->brochure_files as $kgrs => $file)
                                                        @php
                                                          $extension = pathinfo($file->file_name, PATHINFO_EXTENSION);
                                                        @endphp
                                                        @if($extension == 'pdf')
                                                            <a href="{{ route('schemes.get_the_file', [$val->scheme_id, $file->file_name]) }}" target="_blank" title="{{ $file->file_name }}"><i class="fas fa-file-pdf fa-2x" style="color:red;"></i></a>
                                                        @elseif ($extension == 'doc')
                                                        <a href="{{ route('schemes.get_the_file', [$val->scheme_id, $file->file_name]) }}" download="{{ $file->file_name }}"><i class="fas fa-download fa-2x" style="color:#007bff;"></i></a>
                                                        @else
                                                            <a href="{{ route('schemes.get_the_file', [$val->scheme_id, $file->file_name]) }}" download="{{ $file->file_name }}"><i class="fas fa-download fa-2x" style="color:green;"></i></a>
                                                        @endif
                                                      @endforeach
                                                  @endif
                                                </div>
                                                @endif
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
                                                @if($pamphlets->count())
                                                <div class="form-group">
                                               
                                                @if($val->pamphlets_files->count() > 0)
                                                  @foreach($val->pamphlets_files as $kgrs => $pam_file)
                                                    @php
                                                      $extension = pathinfo($pam_file->file_name, PATHINFO_EXTENSION);
                                                    @endphp
                                                    @if($extension == 'pdf')
                                                        <a href="{{ route('schemes.get_the_file', [$val->scheme_id, $pam_file->file_name]) }}" target="_blank" title="{{ $pam_file->file_name }}"><i class="fas fa-file-pdf fa-2x" style="color:red;"></i></a>
                                                        @elseif ($extension == 'doc')
                                                        <a href="{{ route('schemes.get_the_file', [$val->scheme_id, $pam_file->file_name]) }}" download="{{ $pam_file->file_name }}"><i class="fas fa-download fa-2x" style="color:#007bff;"></i></a>
                                                        @else
                                                        <a href="{{ route('schemes.get_the_file', [$val->scheme_id, $pam_file->file_name]) }}" download="{{ $pam_file->file_name }}"><i class="fas fa-download fa-2x" style="color:green;"></i></a>
                                                    @endif
                                              
                                                  @endforeach
                                                @endif
                                                </div>
                                                @endif
                                                <!--end::Input-->
                                              </div>
                                              <div class="col-xl-6">
                                                <!--begin::Input-->
                                                <div class="form-group">
                                                  <label>Other Details of the Scheme (યોજનાને લાગતું અન્ય સાહિત્ય) <br>(Central–State Separate ): </label>
                                                  <div></div>
                                                  <div class="custom-file">
                                                    <input type="file" class="custom-file-input next_otherdetailscenterstate file_type_name" id="other_details_center_state" name="otherdetailscenterstate[]" multiple accept=".pdf,.docx,.xlsx"/>
                                                    <label class="custom-file-label" for="customFile">Choose file</label>
                                                  </div>
                                                </div>
                                                @if($center_state->count())
                                                <div class="form-group">
                                                {{-- @foreach($center_state as $kgr => $vgr)
                                                    <a href="{{ $replace_url }}/get_the_file/{{ $scheme_id }}/_center_state_{{++$kgr}}" target="_blank"><i class="fas fa-file-pdf fa-2x" style="color:red;"></i></a>
                                                @endforeach --}}
                                                @if($val->otherdetailscenterstate_files->count() > 0)
                                                    @foreach($val->otherdetailscenterstate_files as $kgrs => $other_file)
                                                      @php
                                                        $extension = pathinfo($other_file->file_name, PATHINFO_EXTENSION);
                                                      @endphp
                                                      @if($extension == 'pdf')
                                                      <a href="{{ route('schemes.get_the_file', [$val->scheme_id, $other_file->file_name]) }}" target="_blank" title="{{ $other_file->file_name }}"><i class="fas fa-file-pdf fa-2x" style="color:red;"></i></a>
                                                      @elseif ($extension == 'doc')    
                                                        <a href="{{ route('schemes.get_the_file', [$val->scheme_id, $other_file->file_name]) }}" download="{{ $other_file->file_name }}"><i class="fas fa-download fa-2x" style="color:#007bff;"></i></a>
                                                      @else
                                                      <a href="{{ route('schemes.get_the_file', [$val->scheme_id, $other_file->file_name]) }}" download="{{ $other_file->file_name }}"><i class="fas fa-download fa-2x" style="color:green;"></i></a>
                                                      @endif
                                                    {{-- <a href="{{ $replace_url }}/get_the_file/{{ $pval->scheme_id }}/otherdetailscenterstate_{{++$kgrs}}" target="_blank">
                                                      <i class="fas fa-file-pdf fa-2x" style="color:red;"></i>
                                                    </a> --}}
                                                    @endforeach
                                                  @endif
                                                </div>
                                                @endif
                                                <!--end::Input-->
                                              </div>
                                            </div>
                                            {{-- <button type="submit" id="btn_eleventh_slide_submit" style="visibility: hidden;"></button>
                                        </form> --}}
                                        <div class="row">
                                            <div class="col-xl-6">
                                                <div class="form-group"><br>
                                                    <label>Beneficiary Filling form (લાભાર્થી ભરવાનું ફોર્મ):</label>
                                                    <div class="form-group">
                                                        <div class="row align-items-center">
                                                            <div class="col-xl-3">
                                                                <div class="radio-inline">
                                                                    <label class="radio">
                                                                        <input type="radio" name="beneficiary_filling_form_type" value="0" class="beneficiary_filling_form_type"
                                                                            {{ isset($val->beneficiary_filling_form_type) && $val->beneficiary_filling_form_type == 0 ? 'checked' : '' }}>
                                                                        Yes
                                                                    </label>
                                                                </div>
                                                            </div>
                                                            <div class="col-xl-3">
                                                                <div class="radio-inline">
                                                                    <label class="radio">
                                                                        <input type="radio" name="beneficiary_filling_form_type" value="1" class="beneficiary_filling_form_type"
                                                                            {{ isset($val->beneficiary_filling_form_type) && $val->beneficiary_filling_form_type == 1 ? 'checked' : '' }}>
                                                                        No
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    {{-- File Upload Section --}}
                                                    <div class="custom-file beneficiary_form"
                                                        style="{{ isset($val->beneficiary_filling_form_type) && $val->beneficiary_filling_form_type == 0 ? '' : 'display: none;' }}">
                                                        <input type="file"
                                                            class="custom-file-input beneficiary_filling_form file_type_name"
                                                            id="beneficiary_filling_form"
                                                            name="beneficiary_filling_form"
                                                            accept=".pdf,.docx,.xlsx" />
                                                        <label class="custom-file-label" for="beneficiary_filling_form">Choose file</label>

                                                        {{-- Show existing uploaded file if available --}}
                                                        @if(!empty($val->beneficiary_filling_form))
                                                            @php
                                                                $ext = pathinfo($val->beneficiary_filling_form, PATHINFO_EXTENSION);
                                                            @endphp
                                                            <div class="mt-2 existing-beneficiary-file">
                                                                <span>Existing File: </span>
                                                                @if($ext == 'pdf')
                                                                    <a href="{{ route('schemes.get_the_file', [$val->scheme_id, $val->beneficiary_filling_form]) }}" target="_blank" title="{{ $val->beneficiary_filling_form }}">
                                                                        <i class="fas fa-file-pdf fa-2x" style="color:red;"></i>
                                                                    </a>
                                                                @elseif(in_array($ext, ['doc', 'docx']))
                                                                    <a href="{{ route('schemes.get_the_file', [$val->scheme_id, $val->beneficiary_filling_form]) }}" download="{{ $val->beneficiary_filling_form }}">
                                                                        <i class="fas fa-file-word fa-2x" style="color:#007bff;"></i>
                                                                    </a>
                                                                @elseif(in_array($ext, ['xls', 'xlsx']))
                                                                    <a href="{{ route('schemes.get_the_file', [$val->scheme_id, $val->beneficiary_filling_form]) }}" download="{{ $val->beneficiary_filling_form }}">
                                                                        <i class="fas fa-file-excel fa-2x" style="color:green;"></i>
                                                                    </a>
                                                                @endif
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                    
                                    <!-- eleventh_slide close -->
                                    <div class="thirteenth_slide {{  ($currentSlide  == 13) ? 'active-slide' : '' }} otherslides" style="{{  ($currentSlide  == 13) ? 'display:block;' : 'display:none;' }}">
                                          <div class="row ">  
                                            <div class="col-xl-12">
                                              <label>Major Monitoring Indicator at HOD Level (Other than Secretariat Level) (ખાતાના વડાકક્ષાએ મહત્વના ઇન્ડિકેટર નુ મોનીટરીંગ.(સચિવાલય સિવાય)) : </label> 
                                            </div>
                                          </div>
                                          <div class="row table-responsive">  
                                            <table class="table" id="indicator_table">
                                              <tbody>
                                            
                                              @if(empty($val->major_indicator_hod))
                                                <tr>
                                                  <th class="borderless"><label>Indicator :</label></th>
                                                  <td class="borderless major_hod_indicator_td" width="95%">
                                                    <input class="form-control getindicator_hod" id="indicator_hod_id_0" type="text" name="major_indicator_hod[0][major_indicator_hod]" />
                                                  </td>
                                                  {{-- <td class="borderless" width="5%">
                                                    <button type="button" class="btn btn-primary" id="addnewindicatorbtn" style="padding:2px;width:20px;height:auto;font-weight:bolder">+</button>
                                                  </td> --}}
                                                </tr>
                                              @else
                                                    <tr class="indicator_tr_0">
                                                          <th class="borderless col-md-1"><label>Indicator:</label></th>
                                                          <td class="borderless major_hod_indicator_td" width="95%">
                                                          <input class="form-control getindicator_hod" id="indicator_hod_id_0" type="text" name="major_indicator_hod[0][major_indicator_hod]" value="{{ $val->major_indicator_hod }}" /></td>
                                                          {{-- <td class="borderless" width="5%">
                                                              @if($loop->last)
                                                                  <button type="button" class="btn btn-primary" id="addnewindicatorbtn" style="padding:2px;width:20px;height:auto;font-weight:bolder">+</button>
                                                              @else
                                                                  <button type="button" class="btn btn-primary" value="{{$khod}}" id="removeindicatorbtn" style="padding:2px;width:20px;height:auto;font-weight:bolder" onclick="removeindicatorrow(this.value)">-</button>
                                                              @endif
                                                          </td> --}}
                                                      </tr>
                                                @endif
                                              </tbody>
                                            </table>
                                          </div>
                                    </div>
                                    
                                    <!-- twelth_slide close -->
                                    <div class="fourteenth_slide {{  ($currentSlide  == 14) ? 'active-slide' : '' }} otherslides" style="{{  ($currentSlide  == 14) ? 'display:block;' : 'display:none;' }}">
                                        <div class="row ">
                                          <div class="col-xl-12">
                                            {{-- <label> Financial & Physical Progress (component wise) of Last Five Year (છેલ્લા પાંચ વર્ષની વર્ષવાર નાણાકીય અને ભૌતિક પ્રગતિ (કમ્પોનેટ વાઇઝ))  <span class="required_filed"> * </span>:</label> --}}
                                              <label> Financial & Physical Progress  (component wise) of the Last Five Years/Beginning of the Plan (યોજના ની શરૂઆત/છેલ્લા પાંચ વર્ષની વર્ષવાર નાણાકીય અને ભૌતિક પ્રગતિ (કમ્પોનેટ વાઇઝ)) <span class="required_filed"> * </span>:</label>
                                          </div>
                                        </div>
                                        <div class="row">
                                          <div class="col-xl-12">
                                            <table class="table table-bordered table-responsive" id="kt_datatable" style="margin-top: 13px !important">
                                              <thead>
                                                <tr>
                                                  <th rowspan="2" style="font-size: 16px;" class="text-center">Financial Year/નાણાકીય વર્ષ </th>
                                                  <!-- <th rowspan="2" style="font-size: 16px;">Unit</th> -->
                                                  <th colspan="3" class="text-center">Physical/ભૌતિક</th>
                                                  <th colspan="2" class="text-center">Financial/નાણાકીય <small>(Rs in Crores)</small></th>
                                                  {{-- <th colspan="2" class="text-center">Unit of Physical/ભૌતિક</th> --}}
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
                                               
                                                @if($financial_progress->count() > 0)
                                                @foreach($financial_progress as $i => $vfp)
                                                
                                                <tr class="finprogresstr_{{$i}}">
                                                  <td class="finprogresstd_{{$i}}"><select style="padding:2px" class="form-control next_financial_progress_year next_fin_year_{{$i}}" name="financial_progress[{{ $i }}][financial_year]"><option value="">Year</option>@foreach($financial_years as $year) <option value="{{ $year }}" @if($year == $vfp->financial_year) selected @endif>{{ $year }}</option> @endforeach </select></td>
                                                   <td class="finprogresstd_{{$i}}">
                                                    <select style="padding:2px" class="form-control next_financial_progress_selection next_fin_selection_{{$i}}" id="next_fin_selection_{{$i}}" name="financial_progress[{{ $i }}][selection]">
                                                    <option value="">Select Option</option>
                                                    @foreach($units as $unit_item) 
                                                        <option value="{{ $unit_item->id }}" @if($unit_item->id == $vfp->selection) selected @endif>{{ $unit_item->name }}</option> 
                                                    @endforeach 
                                                    <option value="0">Other</option> 
                                                    </select>
                                                  </td>
                                                  {{-- <td class="finprogresstd_{{$i}}"><input type="text" class="form-control next_financial_progress_units next_fin_units_{{$i}}" name="financial_progress[{{ $i }}][units]" maxlength="20" value="{{ $vfp->units }}" /></td> --}}
                                                  <td class="finprogresstd_{{$i}}"><input type="text" class="pattern form-control allowonly2decimal next_financial_progress_target next_fin_target_{{$i}}" name="financial_progress[{{ $i }}][target]" value="{{$vfp->target}}" /></td>
                                                  <td class="finprogresstd_{{$i}}"><input type="text" class="pattern form-control allowonly2decimal next_financial_progress_achivement next_fin_achivement_{{$i}}" name="financial_progress[{{ $i }}][achivement]" value="{{$vfp->achievement}}" /></td>
                                                  <td class="finprogresstd_{{$i}}"><input type="text" class="pattern form-control allowonly2decimal next_financial_progress_allocation next_fin_allocation_{{$i}}" name="financial_progress[{{ $i }}][allocation]" value="{{ $vfp->allocation }}" /></td>
                                                  <td class="finprogresstd_{{$i}}"><input type="text" class="pattern form-control allowonly2decimal next_financial_progress_expenditure next_fin_expenditure_{{$i}}" name="financial_progress[{{ $i }}][expenditure]" value="{{ $vfp->expenditure }}" /></td>
                                                 
                                                  {{-- <td class="finprogresstd_{{$i}}"><input type="text" class="pattern form-control next_financial_progress_item next_fin_item_{{$i}}" data-id="{{$i}}" name="financial_progress[{{ $i }}][item]" value="{{ $vfp->items }}" /></td> --}}
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
                                                  <td class="finprogresstd_{{$i}}"><input type="text" class="pattern form-control allowonly2decimal next_financial_progress_target next_fin_target_{{$i}}" name="financial_progress[{{ $i }}][target]"/></td>
                                                  <td class="finprogresstd_{{$i}}"><input type="text" class="pattern form-control allowonly2decimal next_financial_progress_achivement next_fin_achivement_{{$i}}" name="financial_progress[{{ $i }}][achivement]"/></td>
                                                  <td class="finprogresstd_{{$i}}"><input type="text" class="pattern form-control allowonly2decimal next_financial_progress_allocation next_fin_allocation_{{$i}}" name="financial_progress[{{ $i }}][allocation]" /></td>
                                                  <td class="finprogresstd_{{$i}}"><input type="text" class="pattern form-control allowonly2decimal next_financial_progress_expenditure next_fin_expenditure_{{$i}}" name="financial_progress[{{ $i }}][expenditure]" /></td>
                                                 
                                                  {{-- <td class="finprogresstd_{{$i}}">
                                                    <input type="text" class="pattern form-control next_financial_progress_item next_fin_item_{{$i}}"  data-id="{{$i}}" name="financial_progress[{{ $i }}][item]" />
                                                </td> --}}
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
                                                    <label>Physical and Financial Progress Remarks : <small><b> Max 1000 characters </b></small> </label>
                                                    <textarea rows="2" name="financial_progress_remarks" class="form-control" id="financial_progress_remarks" maxlength="1000">{{ $val->fin_progress_remarks }}</textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- thirteenth_slide close -->
                                 
                              
                                      <!--begin: Wizard Actions-->
                                      <div class="row d-flex justify-content-between border-top mt-5 pt-10" style="width:100%; border-top:1px solid #828385 !important;">
                                        <div class="col-xl-4">
                                          <button type="button" class="btn btn-primary font-weight-bold text-uppercase" data-wizard-type="action-prev" value="{{(!is_null($val->save_last_item)) ? $val->save_last_item : 1}}" onclick="getPrevSlide(this.value)" style="{{(empty($val->save_last_item)) || ($val->save_last_item  == 1) ? 'display:none;' : 'display:block;' }}" id="previous_btn">
                                          Previous
                                          </button>
                                        </div>
                                        <div class="col-xl-4">
                                          <label class="nav-item nav-page1 page_no" for="page1-input">
                                            <div>{{(!is_null($val->save_last_item)) ? $val->save_last_item : 1}}</div>
                                          </label>
                                        </div>
                                        <div class="col-xl-4" id="div_next_btn" style="text-align: end;">
                                          <button type="button" class="btn btn-primary font-weight-bold text-uppercase float-right" data-wizard-type="action-next" value="{{(!is_null($val->save_last_item)) ? $val->save_last_item : 1}}" onclick="getNextSlide(this.value)" id="next_btn">
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
                       <!--end::Modal-->
                      </div>
                    <!--end::Container-->
                </div>
              <div class="save_exit_slide">
                <a class="btn btn-primary font-weight-bold text-uppercase text-center save_item" type="button" data-slide-item="{{$val->save_last_item}}">Save & Exit</a>
              </div>
            </div>
      <!--end::Entry-->
{{-- </div> --}}
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

var count = 0;
function countIncrease(slideid){
  let nextSlide = parseInt(slideid) + 1;
  count = $('.page_no div').html(nextSlide);
  $('.save_item').attr('data-slide-item',nextSlide);
      return nextSlide;
}

var preCount = 0;
function countPrevious(prevslide){
  let previousSlide = parseInt(prevslide) - 1;
  preCount = $('.page_no div').html(previousSlide);
  $('.save_item').attr('data-slide-item',previousSlide);
      return previousSlide;
}
</script>

<script>

$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': "{{ csrf_token() }}"
    }
});
$(document).ready(function() {

  
  // $( ".datepicker" ).datepicker({
  //         format: 'dd/mm/yyyy', 
  //         changeMonth: true,
  //         changeYear: true,
  //         maxDate: new Date(),
  //         beforeShow: function (input, inst) {
  //             $(input).attr("placeholder", "dd/mm/yyyy");
  //         },
  //         onSelect: function (dateText, inst) {
  //           // Enable the file input only after a date is selected
  //           $('#notification').prop('disabled', false);
  //       }
  // });

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
            <div class="custom-file" style="flex: 1;">
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

// Optional: Remove existing file (AJAX delete)
$(document).on('click', '.remove-existing-file', function () {
    let fileId = $(this).data('file-id');
    let row = $(this).closest('.existing-file');

    if (confirm('Are you sure you want to delete this file?')) {
        $.ajax({
            url: '/schemes/delete-gr-file/' + fileId, // route to handle file deletion
            type: 'post',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
            },
            success: function (res) {
                if (res.success) {
                    row.remove();
                } else {
                    alert('Error deleting file.');
                }
            },
            error: function () {
                alert('Something went wrong.');
            },
        });
    }
});

$(document).on('change', '.beneficiary_filling_form_type', function () {
    let selectedValue = $('input[name="beneficiary_filling_form_type"]:checked').val();

    if (selectedValue === '0') {
        // Yes selected → show file input
        $('.beneficiary_form').show();
    } else {
        // No selected → hide and clear file input
        $('.beneficiary_form').hide();
        $('.beneficiary_filling_form').val('');
        $('.custom-file-label').text('Choose file');
    }
});

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
                                <input type="text" name="hod_officer_name[]" class="form-control hod_officer_name" required>
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
                    $('.implementing_office').append($("<option></option>").attr("value", Name).text(Name).attr("selected", "selected"));
                    $('.other_val').css('display','none');
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

  $('.next_financial_progress_item').on('input', function () {
    var checkId = $(this).data('id');
    var selectoptionVal = jQuery('select[name="financial_progress['+parseInt(checkId)+'][selection]"]').find(':selected').val();
    if(selectoptionVal == ""){
      alert('Please select Value');
      $(this).val('');
      return;
    }else{
      
      var value = parseFloat($(this).val().replace(/,/g, '')) || 0;

        if (selectoptionVal == 1) { // RS Value
            var formattedValue = value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
            $(this).val(formattedValue);
        } else if (selectoptionVal == 2 || selectoptionVal == 3) { // Number or Kg
            if (isNaN(value)) {
                alert('Please enter a valid number');
                $(this).val('');
            }
        }
        // } else if (selectoptionVal == 4) { // Meter
            
        // } else if (selectoptionVal == 5) { // Liter
           
        // } else if (selectoptionVal == 0) { // Other
        
        // }
    }
 
  });
   $(document).on('change','.next_financial_progress_selection',function() {
      var classValue = $(this).attr('id');
      var type = $('#'+classValue).val();
      var string  = classValue.split("_");
      var txtVal = $(this).parent('.finprogresstd_'+parseInt(string[3])).next().find('.next_fin_item_'+parseInt(string[3]));
      txtVal.val('');
      if(type == 0){
           if(txtVal.hasClass('allowonly2decimal')){
              txtVal.removeClass('allowonly2decimal');
            }
      }else{
          txtVal.addClass('allowonly2decimal');
      }
   });
  
   $('.save_item').on('click',function(){
        var save_item = $(this).attr('data-slide-item');
        var draft_id = $("#next_draft_id").val();
        console.log(save_item);
          if(save_item != ""){
            $.ajax({
              type:'POST',
              url:"{{ route('save_last_item') }}",
              data:{'_token':"{{ csrf_token() }}",save_item:save_item,draft_id:draft_id},
              success:function(response) {
                // console.log(response);
                if(response !="" && response.redirectUrl !== undefined){
                    window.location.replace(response.redirectUrl);
                }else{
                  alert(response.error);
                }
              },
              error:function() {
                  console.log('Something went wrong items');
              }
            })
          }else{
            alert('Something went wrong');
          }
      })

});


$(document).ready(function() {

  // $('#implementing_office_contact').on('input', function () {
  //   var implementing_office_contact_type = $('input[name="implementing_office_contact_type"]:checked').val();
      
  //   if (implementing_office_contact_type == 0) {
  //     $(this).inputmask("(999) 9999")
  //   }else if (implementing_office_contact_type == 1) {
  //     $(this).inputmask("(999) 999-9999")
  //   }
  // });


  //  $('.both_ration').on('input', function () {
  //      var both_ratio_type = $('input[name="both_ratio_type"]:checked').val();
      
  //      if (both_ratio_type == 0) {
  //       var value = $(this).val().replace(/,/g, ''); // Remove existing commas
  //       var formattedValue = value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
  //       console.log(formattedValue);
  //       $(this).val(formattedValue);
  //   }
  //   else if (both_ratio_type == 1) {
  //      // Get the entered value
  //     var enteredValue = parseFloat($(this).val()) || 0;
  //     // Validate the entered value
  //     if (enteredValue < 0 || enteredValue > 100) {
  //       alert('Please enter a valid percentage between 0 and 100.');
  //       $(this).val('');
  //       return;
  //     }
  //   }

  // });

     // Add event listener to both input fields
   $('.state_per').on('input', function () {
      // Get the entered value
      var enteredValue = parseFloat($(this).val()) || 0;

      // Validate the entered value
      if (enteredValue < 0 || enteredValue > 100) {
        alert('Please enter a valid percentage between 0 and 100.');
        $(this).val('');
        return;
      }

      // Calculate the remaining percentage
      var remainingPercentage = 100 - enteredValue;

      // Update the other input field with the remaining percentage
      var otherInput = $(this).attr('id') === 'state_ratio' ? $('#center_ratio') : $('#state_ratio');
      otherInput.val(remainingPercentage);
    });

 
});

$(document).ready(function() {
  // $("#btn_add_indicator").click(function(){

  //   var ktcontent = $("#kt_content").height();
  //   $(".content-wrapper").css('min-height',ktcontent+50);

  //   var indicator_room = $(".next_major_indicators").length - 1;
  //   var after_indicator_room = indicator_room+1;
  //   var major_indicator = "major_indicator";
  //   if(indicator_room < 10) {
  //     $(".indicator_fields_"+indicator_room).after('<div class="form-group indicator_fields_'+after_indicator_room+'"><input class="form-control next_major_indicators" type="text" name="major_indicator['+after_indicator_room+']['+major_indicator+']" /><br></div>');
  //     indicator_room++;
  //   }
  // });
});

// $(document).ready(function() {
//   $("#btn_add_beneficiary_sel_criteria").click(function() {

//     var ktcontent = $("#kt_content").height();
//     $(".content-wrapper").css('min-height',ktcontent+50);

//     var beneficiary_selection = $(".next_beneficiary_selection_criterias").length - 1;
//     var after_beneficiary_selection = beneficiary_selection+1;
//     var beneficiary_selection_criteria = "beneficiary_selection_criteria";
//     if(beneficiary_selection < 10) {
//       if($("#beneficiary_selection_div_"+beneficiary_selection+" textarea").val() == '') {
//         var alert_ben_sec = new String("You missed beneficiary criteria above");
//         alert(alert_ben_sec);
//       } else {
//           $("#beneficiary_selection_div_"+beneficiary_selection).after('<div id="beneficiary_selection_div_'+after_beneficiary_selection+'"><label>Beneficiary Criteria : <small><b>Max 3000 characters</b></small></label><textarea class="form-control next_beneficiary_selection_criterias" type="text" name="beneficiary_selection_criteria['+after_beneficiary_selection+']['+beneficiary_selection_criteria+']" maxlength="3000"/></textarea><br></div>');
//           beneficiary_selection++;
//       }
//     }
//   });
 
// });

$(document).ready(function() {
  // $("#btn_add_major_benefit").click(function(){

  //   var ktcontent = $("#kt_content").height();
  //   $(".content-wrapper").css('min-height',ktcontent+20);
  //   var major_benefit = $(".major_benefit_textareas").length - 1;
  //   var after_major_benefit = major_benefit+1;
  //   var major_benefits_text = "major_benefits_text";
  //   if(major_benefit < 4) {
  //     if($("#major_benefit_textarea_"+major_benefit).val() == '') {
  //       var alert_maj_ben = new String("You missed major benefit above");
  //       alert(alert_maj_ben);
  //     } else {
  //         $("#major_benefits_div_"+major_benefit).after('<div class="form-group" id="major_benefits_div_'+after_major_benefit+'"><label>Major benefit '+(after_major_benefit+1)+': </label><textarea id="major_benefit_textarea_'+after_major_benefit+'" class="form-control major_benefit_textareas" type="text" name="major_benefits_text['+after_major_benefit+']['+major_benefits_text+']" /></textarea></div>');
  //         major_benefit++;
  //     }
  //   }
  // });
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
    //var units = 'units';
    var selection = 'selection';
  //  var item = 'item';
    var nextrownumberzero = 0; //rownumber + 1;

  
    var count_thisistbody_tr = $("#thisistbody tr").length - 1;
    var entered_finyear = $('#thisistbody .next_financial_progress_year').eq(count_thisistbody_tr).val();
    // var entered_units = $('#thisistbody .next_financial_progress_units').eq(count_thisistbody_tr).val();
    var entered_target = $('#thisistbody .next_financial_progress_target').eq(count_thisistbody_tr).val();
    var entered_achievement = $('#thisistbody .next_financial_progress_achivement').eq(count_thisistbody_tr).val();
    var entered_fund = $('#thisistbody .next_financial_progress_allocation').eq(count_thisistbody_tr).val();
    var entered_expenditure = $('#thisistbody .next_financial_progress_expenditure').eq(count_thisistbody_tr).val();

    var entered_selection = $('#thisistbody .next_financial_progress_selection').eq(count_thisistbody_tr).val();
    //var entered_item = $('#thisistbody .next_financial_progress_item').eq(count_thisistbody_tr).val();

    var split_finyear = entered_finyear.split('-');
    var add_one = Number(split_finyear[0]) + 1;
    var add_two = Number(split_finyear[1]) + 1;
    var entered_finyear = add_one+'-'+add_two;
    if(rownumber >= 1) {
      var addtr = '<tr class="finprogresstr_'+rownumber+'"><td class="finprogresstd_'+rownumber+'"><select style="padding:2px" class="form-control next_financial_progress_year next_fin_year_'+rownumber+'" name="financial_progress['+rownumber+']['+fiyear+']"><option value="">Year</option>@foreach($financial_years as $year) <option value="{{ $year }}" @if('+entered_finyear+' == $year) selected @endif>{{ $year }}</option> @endforeach</select></td><td class="finprogresstd_'+rownumber+'"><select style="padding:2px" class="form-control next_financial_progress_selection next_fin_selection_'+rownumber+'" id="next_fin_selection_'+rownumber+'" name="financial_progress['+rownumber+']['+selection+']"><option value="">Select Option</option>@foreach($units as $unit_item)<option value="{{ $unit_item->id }}" @if($unit_item->id == '+entered_finyear+') selected @endif>{{ $unit_item->name }}</option>@endforeach<option value="0">Other</option></select></td><td class="finprogresstd_'+rownumber+'"><input type="text" class="form-control next_financial_progress_target allowonly2decimal next_fin_target_'+rownumber+'" name="financial_progress['+rownumber+']['+target+']" value="" /></td><td class="finprogresstd_'+rownumber+'"><input type="text" class="form-control next_financial_progress_achivement allowonly2decimal next_fin_achivement_'+rownumber+'" name="financial_progress['+rownumber+']['+achivement+']" value="" /></td><td class="finprogresstd_'+rownumber+'"><input type="text" class="form-control next_financial_progress_allocation allowonly2decimal next_fin_allocation_'+rownumber+'" name="financial_progress['+rownumber+']['+allocation+']" value="" /></td><td class="finprogresstd_'+rownumber+'"><input type="text" class="form-control next_financial_progress_expenditure allowonly2decimal next_fin_expenditure_'+rownumber+'" name="financial_progress['+rownumber+']['+expenditure+']" value="" /></td><td class="finprogresstd_'+rownumber+'"><button type="button" class="btn btn-primary finprogressbtnremove" onclick="remove_financial_year(this.value)" value="'+rownumber+'" style="padding:2px;width:20px;height:auto;font-weight:bolder;">-</button></td></tr>';
      $("#thisistbody tr:last").after(addtr);
    } else {
    var addtr = '<tr class="finprogresstr_'+nextrownumberzero+'"><td class="finprogresstd_'+nextrownumberzero+'"><select style="padding:2px" class="form-control next_financial_progress_year next_fin_year_'+nextrownumberzero+'" name="financial_progress['+nextrownumberzero+']['+fiyear+']"><option value="">Year</option>@foreach($financial_years as $year) <option value="{{ $year }}" @if('+entered_finyear+' == $year) selected @endif>{{ $year }}</option> @endforeach</select></td><td class="finprogresstd_'+nextrownumberzero+'"><select style="padding:2px" class="form-control next_financial_progress_selection next_fin_selection_'+nextrownumberzero+'" id="next_fin_selection_'+nextrownumberzero+'" name="financial_progress['+nextrownumberzero+']['+selection+']"><option value="">Select Option</option>@foreach($units as $unit_item)<option value="{{ $unit_item->id }}" @if($unit_item->id == '+entered_selection+') selected @endif>{{ $unit_item->name }}</option>@endforeach<option value="0">Other</option></select></td><td class="finprogresstd_'+nextrownumberzero+'"><input type="text" class="form-control next_financial_progress_target allowonly2decimal next_progress_year_'+nextrownumberzero+' next_fin_target_'+nextrownumberzero+'" name="financial_progress['+nextrownumberzero+']['+target+']"  value=""/></td><td class="finprogresstd_'+nextrownumberzero+'"><input type="text" class="form-control next_financial_progress_achivement allowonly2decimal next_fin_achivement_'+nextrownumberzero+'" name="financial_progress['+nextrownumberzero+']['+achivement+']"  value=""/></td><td class="finprogresstd_'+nextrownumberzero+'"><input type="text" class="form-control next_financial_progress_allocation allowonly2decimal next_fin_allocation_'+nextrownumberzero+'" name="financial_progress['+nextrownumberzero+']['+allocation+']"  value=""/></td><td class="finprogresstd_'+nextrownumberzero+'"><input type="text" class="form-control next_financial_progress_expenditure allowonly2decimal next_fin_expenditure_'+nextrownumberzero+'" name="financial_progress['+nextrownumberzero+']['+expenditure+']" value=""/></td><td class="finprogresstd_'+nextrownumberzero+'"><button type="button" class="btn btn-primary finprogressbtnremove" onclick="remove_financial_year(this.value)" value="'+nextrownumberzero+'" style="padding:2px;width:20px;height:auto;font-weight:bolder;">-</button></td></tr>';
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

// $(document).ready(function(){
//   $("#addnewindicatorbtn").click(function(){
//     var indicator_array_num = $(".getindicator_hod").length;

//     // var ktcontent = $("#kt_content").height();
//     // var ktcontent_long = Number(ktcontent) + 100;
//    // $(".content-wrapper").css('min-height',ktcontent_long);

//     indicator_array_num++;
//     var major_indicator_hod = 'major_indicator_hod';
//     var addindicator = '<tr class="indicator_tr_'+indicator_array_num+'"><th class="borderless col-md-1" width="100%" id="indicator_id_'+indicator_array_num+'">Indicator '+(indicator_array_num)+'</th><td class="borderless" width="95"><input class="form-control getindicator_hod" id="indicator_hod_id_'+indicator_array_num+'" type="text" name="major_indicator_hod['+indicator_array_num+']['+major_indicator_hod+']" /></td><td class="borderless" width="5%"><button type="button" class="btn btn-primary" value="'+indicator_array_num+'" id="removeindicatorbtn" style="padding:2px;width:20px;height:auto;font-weight:bolder" onclick="removeindicatorrow(this.value)">-</button></td></tr>';
//     console.log($("#indicator_table tbody tr:last"));
//     $("#indicator_table tbody tr:last").after(addindicator);
//   });
// });

// function removeindicatorrow(row) {
//     $("#indicator_table tbody .indicator_tr_"+row).remove();
   
//     var ktcontent = $("#kt_content").height();
//     $(".content-wrapper").css('min-height',ktcontent);
// }

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
            // var major_obj = $(".major_objective_parent_div div").length;
            // // major_obj should be minimum 5
            // if(major_obj < 2) {
            //     $(".maj_obj_error").remove();
            //     $('.major_objective_parent_div').after("<div class='row maj_obj_error text-red' style='font-size:16px;margin-left:20px'>Please add minimum 2 major Objectives Scheme</div>");
            //     var major_obj_after = Number(major_obj)-1;
            //     count_total_msg = 1;
            //     // return false;
            //     alert('Warning : Major Objectives are less than 2 !!!!!!!');
            // } else {
            //     $(".maj_obj_error").remove();
            //     count_total_msg = 0;
            // }
            // var major_ind = $(".major_indicator_parent_div div").length;
            // // major_ind should be minimum 5
            // if(major_ind < 2) {
            //     var major_ind_after = Number(major_ind)-1;
            //     count_total_msg = 1;
            //     // return false;
            //     alert('Warning : Major Indicators are less than 2 !!!!!!!');
            // } else {
            //     $(".maj_ind_error").remove();
            //     count_total_msg = 0;
            // }
            // var major_hod_indicator = $(".indicator_tr_4").length;
            // // major_hod_indicator must be 1
            // if(major_hod_indicator < 1) {
            //     count_total_msg = 1;
            //     // return false;
            //     alert('Warning : Major Monitoring Indicators are less than 2 !!!!!!!');
            // } else {
            //     count_total_msg = 0;
            // }
            var finprogresstr = $("#kt_datatable #thisistbody tr").length;
            // finprogresstr must be 5
            if(finprogresstr < 5) {
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
        $('input[type="checkbox"]').prop('checked', checked);
    }
   

    $(document).ready(function(){
      
      $('#beneficiariesGeoLocal').on('change',function(){
      var theval = $(this).val();

        $(".thedistrictlist").remove();
        $("#beneficiariesGeoLocal_img").remove();
        $('#taluka_id').hide();
        if (theval === 'all') {
            fnSelectAll(true);
            return;
        }
        // if(theval != 2 ){
        //   $('.all_item').hide();
        // }
        $.ajax({
            type:'post',
            dataType:'json',
            url:"{{ route('districts') }}",
            data:{'_token':"{{ csrf_token() }}",'district':theval},
            beforeSend:function() {
                $("#load_gif_img").html('<img id="beneficiariesGeoLocal_img" src="loading.gif" style="max-width:200px;max-height:200px">');
            },
            complete:function() {
                $("#beneficiariesGeoLocal_img").remove();
                var ktcontent = $("#kt_content").height();
                $(".content-wrapper").css('min-height',ktcontent);
            },
            success:function(response) {
              
                var ktcontent = $("#kt_content").height();
                $(".content-wrapper").css('min-height',ktcontent);

                    $(".thedistrictlist").remove();
                    
                     $('#beneficiariesGeoLocal').after("<div class='row thedistrictlist' style='margin:20px;font-size:20px'></div>");
                    // if(response.districts != '' && response.districts != undefined) {
                    // //  console.log('district');
                    //   $('#districtList').css('display','none');
                    //     $(".thedistrictlist").append("<div class='col-xl-3 all_item'><input type='checkbox' id='selectAllCheckbox' onchange='fnSelectAll(this.checked)'> All</div>");
                    //     $.each(response.districts, function(reskey, resval){
                    //         $(".thedistrictlist").append("<div class='col-xl-3'><input class='district_length' type='checkbox' style='margin:3px' value='"+resval.dcode+"' name='district_name[]'>"+resval.name_e+"</div>");
                    //     });
                    // }
                    if (response.districts) {
                        $('#taluka_id').css('display','none');
                        $(".thedistrictlist").append("<div class='col-xl-3 all_item'><input type='checkbox' id='selectAllCheckbox' onchange='fnSelectAll(this.checked)'> All</div>");

                      $.each(response.districts, function (reskey, resval) {
                       
                          var checked = "";
                          if (response.entered_item && response.entered_item.includes(String(resval.dcode))) {
                              checked = "checked";
                          }
                          $(".thedistrictlist").append(`
                              <div class="col-xl-3">
                                  <input class="district_length" type="checkbox" style="margin:3px"
                                      value="${resval.dcode}" name="district_name[]" ${checked}>
                                  ${resval.name_e}
                              </div>
                          `);
                      });
                  }
                    if(response.district_list != '' && response.district_list != undefined) {
                   //   console.log('district_list');
                      $('#taluka_id').css('display','block');
                      $('#taluka_id').empty();
                      if (!$.isEmptyObject(response.district_list)) {
                          $.each(response.district_list, function(key, value) {   
                              $('#taluka_id').append($("<option></option>").attr("value", value).text(key)); 
                          });
                      } else {
                          $('#taluka_id').append($("<option></option>").text('Select District'));
                      }
                    
                    }
                    if (response.state && response.state.length > 0) {
                    $('#taluka_id').css('display','none');

                    $.each(response.state, function (reskey, resval) {
                        // check if current item is in the already selected list
                        var checked = "";
                        if (response.entered_item && response.entered_item.includes(String(resval.id))) {
                            checked = "checked";
                        }

                        $(".thedistrictlist").append(`
                            <div class="col-xl-3">
                                <input class="state_length" type="checkbox" style="margin:3px"
                                    value="${resval.id}" name="state_name[]" ${checked}>
                                ${resval.name}
                            </div>
                        `);
                    });
                }
                  //   if(response.state != '' && response.state != undefined){
                  //     //console.log('state');
                  //     $('#districtList').css('display','none');
                  //     $.each(response.state,function(reskey,resval){
                  //         $(".thedistrictlist").append("<div class='col-xl-3'><input class='state_length' type='checkbox' style='margin:3px' value='"+resval.id+"' name='state_name[]'>"+resval.name+"</div>");
                  //     });
                  //  }
            },
            error:function() {
                console.log('districts ajax error');
            }
        });
    });

    //fetch taluka
    $('#taluka_id').on('change',function(){
        var district_code = $(this).val();
        if(district_code != ""){
          $.ajax({
            type:'POST',
            dataType:'json',
            url:"{{ route('get_taluka') }}",
            data:{'_token':"{{ csrf_token() }}",'taluka_dcode':district_code},
            beforeSend:function() {
                $("#load_gif_img").html('<img id="beneficiariesGeoLocal_img" src="loading.gif" style="max-width:200px;max-height:200px">');
            },
            complete:function() {
                $("#beneficiariesGeoLocal_img").remove();
                var ktcontent = $("#kt_content").height();
                $(".content-wrapper").css('min-height',ktcontent);
            },
            success:function(response) {
              $(".thedistrictlist").remove();
              $('#beneficiariesGeoLocal').after("<div class='row thedistrictlist' style='margin:20px;font-size:20px'></div>");
              $(".thedistrictlist").append("<div class='col-xl-3 all_item'><input type='checkbox' id='selectAllCheckbox' onchange='fnSelectAll(this.checked)'> All</div>");

              if(response.error != '' && response.error != undefined){
                  alert(response.error);
              }else{
                  $.each(response.talukas,function(reskey,resval){
                      $(".thedistrictlist").append("<div class='col-xl-3'><input class='taluka_length' type='checkbox' style='margin:3px' value='"+resval.tcode+"' name='taluka_name[]'>"+resval.tname_e+"</div>");
                  });
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
    });

    DistItem();
   
});
function DistItem(){

  $(".thedistrictlist").remove();
    $("#beneficiariesGeoLocal_img").remove();
    var scheme_id = "{{$scheme_id}}";
    var theval = $("#beneficiariesGeoLocal").val();
    if (theval === 'all') {
        // Handle the case when 'All' is selected
        fnSelectAll(true);
        return;
    }
  
    $.ajax({
        type: 'post',
        dataType: 'json',
        url: "{{ route('schemes.edit_districts') }}",
        data: {'_token': "{{ csrf_token() }}", 'district': theval, 'scheme_id': scheme_id},
        beforeSend: function () {
            $("#load_gif_img").html('<img id="beneficiariesGeoLocal_img" src="loading.gif" style="max-width:200px;max-height:200px">');
        },
        complete: function () {
            $("#beneficiariesGeoLocal_img").remove();
            var ktcontent = $("#kt_content").height();
            $(".content-wrapper").css('min-height', ktcontent);
        },
       success: function (response) {
        console.log(response);
            $(".thedistrictlist").remove();
            $("#beneficiariesGeoLocal").after("<div class='row thedistrictlist' style='margin:20px;font-size:20px'></div>");
            var districtList = $(".thedistrictlist");

            // Normalize all possible entered arrays safely
            let enteredDistricts = Array.isArray(response.entered_values)
                ? response.entered_values
                : (response.entered_values ? String(response.entered_values).split(',') : []);

            let enteredStates = Array.isArray(response.entered_item)
                ? response.entered_item
                : (response.entered_item ? String(response.entered_item).split(',') : []);

            // ✅ District Section
            if (response.districts && response.districts.length > 0) {
                $('#taluka_id').hide();
                districtList.append("<div class='col-xl-3'><input type='checkbox' id='selectAllCheckbox' onchange='fnSelectAll(this.checked)'> All</div>");

                $.each(response.districts, function (reskey, resval) {
                    var thedcode = String(resval.dcode);
                    var checkbox = $("<div class='col-xl-3'><input class='district_length' type='checkbox' style='margin:3px' value='" + thedcode + "' name='district_name[]'>" + resval.name_e + "</div>");
                    districtList.append(checkbox);

                    // ✅ Safe include check
                    if (enteredDistricts.includes(thedcode)) {
                        checkbox.find('input[type="checkbox"]').prop('checked', true);
                    }
                });
            }

            // ✅ State Section
            else if (response.states && response.states.length > 0) {
                $('#taluka_id').hide();

                $.each(response.states, function (key, val) {
                    var thedcode = String(val.id);
                    var checkbox = $("<div class='col-xl-3'><input class='state_length' type='checkbox' style='margin:3px' value='" + thedcode + "' name='state_name[]'>" + val.name + "</div>");
                    districtList.append(checkbox);

                    if (enteredStates.includes(thedcode)) {
                        checkbox.find('input[type="checkbox"]').prop('checked', true);
                    }
                });
            }

            // ✅ Taluka Section
            else if (response.talukas && response.talukas.length > 0 && response.taluka_id) {
                $('#taluka_id').show();
                districtList.append("<div class='col-xl-3'><input type='checkbox' id='selectAllCheckbox' onchange='fnSelectAll(this.checked)'> All</div>");

                $.each(response.talukas, function (reskey, resval) {
                    var thedcode = String(resval.tcode);
                    var checkbox = $("<div class='col-xl-3'><input class='taluka_length' type='checkbox' style='margin:3px' value='" + thedcode + "' name='taluka_name[]'>" + resval.tname_e + "</div>");
                    districtList.append(checkbox);

                    if (enteredDistricts.includes(thedcode)) {
                        checkbox.find('input[type="checkbox"]').prop('checked', true);
                    }
                });

                if (!$.isEmptyObject(response.district_list)) {
                    $.each(response.district_list, function (key, value) {
                        var option = $("<option></option>").attr("value", value.dcode).text(value.name_e);
                        if (value.dcode == response.taluka_id) {
                            option.attr("selected", "selected");
                        }
                        $('#taluka_id').append(option);
                    });
                } else {
                    $('#taluka_id').append($("<option></option>").text('Select District'));
                }
            }
        },

        error: function () {
            console.log('districts ajax error');
        }
    });
}
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

    $(document).ready(function(){
        $('.allowonly2decimal__test').keypress(function (e) {
            var character = String.fromCharCode(e.keyCode)
            var newValue = this.value + character;
            if (isNaN(newValue) || hasDecimalPlace(newValue, 3)) {
                e.preventDefault();
                return false;
            }
        });

        //
        $(document).on('change', '.file_type_name', function () {
          if (this.files && this.files.length > 0) {
              const fileName = this.files[0].name; // ORIGINAL filename
              $(this).next('.custom-file-label').text(fileName);
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
        } else if (window.matchMedia('(max-width: 1280px)').matches) {
        } else if (window.matchMedia('(max-width: 1366px)').matches) {
        } else if (window.matchMedia('(max-width: 1600px)').matches) {
        }

        $.ajax({
            type:'post',
            dataType:'json',
            url:"{{ route('schemes.onreload') }}",
            data:{'_token':"{{ csrf_token() }}",'scheme_id':'change','proposal_id':'change'},
            success:function(response) {
            },
            error:function() {
                console.log('ajax error onreload');
            }
        });

    });
    // }
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
                  if (eval_by_whom === '' || eval_when === '' || eval_geo === '' || eval_number === '' || eval_major === '') {
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
            
          } else if(slideid == 2){
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
            var financial_adviser_designation = $("#financial_adviser_designation").val();
            var financial_adviser_phone = $('#financial_adviser_phone').val();
            var financial_adviser_email = $('#financial_adviser_email').val();
            var financial_adviser_mobile = $('#financial_adviser_mobile').val();

            // 🔹 Validation for Slide 2
            if (next_dept_id === '' || the_convener === '' || form_scheme_name === '' || next_reference_year === '' || financial_adviser_name === '' || financial_adviser_designation === '' || financial_adviser_phone === '' || convener_mobile === '' || convener_designation === '' || convener_phone === '' || convener_email === '' || financial_adviser_email === '' || financial_adviser_mobile === '') {
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
            formData.append("draft_id", draft_id);
            formData.append("scheme_id", scheme_id);
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
            formData.append("financial_adviser_designation", financial_adviser_designation);
            formData.append("financial_adviser_phone", financial_adviser_phone);
            formData.append("financial_adviser_email", financial_adviser_email);
            formData.append("financial_adviser_mobile", financial_adviser_mobile);

            // ✅ AJAX Submit both slides
           $.ajax({
            type: 'POST',
            url: "{{ route('schemes.update_scheme') }}",
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
                alert(xhr.responseText || "* AJAX error occurred, please try again");
            }
        });

            //  var next_dept_id = $("#next_dept_id").val();
            // var the_convener = $("#con_id").val();
            // var convener_designation = $('#convener_designation').val();
            // var convener_phone =  $('#convener_phone').val();
            // var form_scheme_name = $("#form_scheme_name").val();
            // var next_reference_year = $('#next_reference_year').val();
            // var next_reference_year2 = $('#next_reference_year2').val();
            // var financial_adviser_name = $("#financial_adviser_name").val();
            // var financial_adviser_designation = $("#financial_adviser_designation").val();
            // var financial_adviser_phone = $('#financial_adviser_phone').val();
            // if(next_dept_id != '' && the_convener != '' && form_scheme_name != '' && next_reference_year != '' && financial_adviser_name != "" && financial_adviser_designation != "" && financial_adviser_phone != '') {
            //   let nextSlide = countIncrease(slideid);

            //     $("#the_error_html").remove();
            //     $.ajax({
            //         type:'post',
            //         dataType:'json',
            //         url:"{{ route('schemes.update_scheme') }}",
            //         data:{'_token':"{{ csrf_token() }}",'slide':'first','convener_designation':convener_designation,'convener_phone':convener_phone,'financial_adviser_name':financial_adviser_name,'financial_adviser_designation':financial_adviser_designation,'financial_adviser_phone' : financial_adviser_phone,'draft_id':draft_id,'scheme_id':scheme_id,'dept_id':next_dept_id,'convener_name':the_convener,'scheme_name':form_scheme_name,'reference_year':next_reference_year,'reference_year2':next_reference_year2},
            //         success:function(response) {
            //             console.log(response);
            //             $(".otherslides").hide();
            //             $(".second_slide").show();
            //             $("#previous_btn").val(2).show();
            //             $("#next_btn").val(2).show();
            //         },
            //         error:function() {
            //              console.log('update_scheme ajax error');
            //         }
            //     });

            // } else {
            //     $("#the_error_html").remove();
            //     var the_html = '<div class="row" id="the_error_html"><div class="col-xl-12" style="color:red;font-size:20px">* All Fields are required</div></div>';
            //     $(".first_slide").append(the_html);
            // }
          }else if(slideid == 3) {
            var next_major_objective = $('#next_major_objective_textarea').val();
            var major_objective_file = $("#major_objective_file")[0].files[0]; // get file object
            if(next_major_objective != '' ) {
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
                  formData.append('draft_id', draft_id);
                  formData.append('scheme_id', scheme_id);
                $.ajax({
                    type:'post',
                    dataType:'json',
                    url:"{{ route('schemes.update_scheme') }}",
                    data: formData,
                    processData: false,
                    contentType: false,
                    success:function(response) {
                        $(".otherslides").hide();
                        $(".fourth_slide").show();
                        $("#previous_btn").val(4).show();
                        $("#next_btn").val(4).show();

                         $('.third_slide').removeClass("active-slide");
                         $('.fourth_slide').addClass("active-slide");
                    },
                    error:function() {
                         console.log('update_scheme ajax error');
                    }
                });

            } else {
                    $("#the_error_html").remove();
                    var the_html = '<div class="row" id="the_error_html"><div class="col-xl-12" style="color:red;font-size:20px">* All Fields are required</div></div>';
                    $(".third_slide").append(the_html);
            }
        } else if(slideid == 4) {
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

                  formData.append('draft_id', draft_id);
                  formData.append('scheme_id', scheme_id);

                $.ajax({
                    type:'post',
                    dataType:'json',
                    url:"{{ route('schemes.update_scheme') }}",
                    data: formData,
                    processData: false,
                    contentType: false,
                    success:function(response) {
                        $(".otherslides").hide();
                        $(".fifth_slide").show();
                        $("#previous_btn").val(5).show();
                        $("#next_btn").val(5).show();

                         $('.fourth_slide').removeClass("active-slide");
                         $('.fifth_slide').addClass("active-slide");
                    },
                    error:function() {
                         console.log('update_scheme ajax error');
                    }
                });

            } else {
                    $("#the_error_html").remove();
                    var the_html = '<div class="row" id="the_error_html"><div class="col-xl-12" style="color:red;font-size:20px">* All Fields are required</div></div>';
                    $(".fourth_slide").append(the_html);
            }
        } else if(slideid == 5) {
              var implementing_office = $("#implementing_office").val() || [];
              var both_ration = $("#both_ration").val();
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
         
              var existing_next_scheme_overview_file = $(".existing_next_scheme_overview_file").val();
              var existing_scheme_objective_file = $(".existing_scheme_objective_file").val();
              var existing_next_scheme_components_file = $(".existing_next_scheme_components_file").val();
              // ✅ Collect HOD Table Data
               var hodData = [];
              $('#hodTable tbody tr').each(function() {
                var row = $(this);
                hodData.push({
                    implementing_office_contact: row.find('.implementing_office_contact').val(),
                    hod_officer_name: row.find('.hod_officer_name').val(),
                    hod_email: row.find('.hod_email').val(),
                    hod_mobile: row.find('.hod_mobile').val()
                });
              });

              // ✅ File inputs
              var overview_file = $("#next_scheme_overview_file")[0]?.files[0];
              var objective_file = $("#scheme_objective_file")[0]?.files[0];
              var component_file = $("#next_scheme_components_file")[0]?.files[0];

              // ✅ Basic validation
              
                     if (
                        implementing_office.length === 0 ||
                        !nodal_id.trim() || !nodal_designation.trim() || !nodal_contact.trim() ||
                        !nodal_mobile.trim() || !nodal_email.trim() ||
                        state_ratio === "" || center_ratio === "" ||
                        !next_scheme_overview.trim() || !next_scheme_objective.trim() || !next_scheme_components.trim()
                    ) {
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

              formData.append('implementing_office', implementing_office.join(',')); // ✅ comma-separated
              formData.append('hod_office', JSON.stringify(hodData));
              // formData.append('hod_office', $('#hod_office_json').val());
              formData.append('both_ration', both_ration);
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
              formData.append('draft_id', draft_id);
              formData.append('scheme_id', scheme_id);
              formData.append('existing_next_scheme_overview_file', existing_next_scheme_overview_file);
              formData.append('existing_scheme_objective_file', existing_scheme_objective_file);
              formData.append('existing_next_scheme_components_file', existing_next_scheme_components_file);
              // ✅ Append files if selected
              if (overview_file) formData.append('next_scheme_overview_file', overview_file);
              if (objective_file) formData.append('scheme_objective_file', objective_file);
              if (component_file) formData.append('next_scheme_components_file', component_file);
              console.log(formData);
                $.ajax({
                    type:'post',
                    dataType:'json',
                    url:"{{ route('schemes.update_scheme') }}",
                    data:formData,
                    contentType: false, // Prevent jQuery from setting the content type
                    processData: false,
                    success:function(response) {
                        $(".otherslides").hide();
                        $(".sixth_slide").show();
                        $("#previous_btn").val(6).show();
                        $("#next_btn").val(6).show();
                         $('.fifth_slide').removeClass("active-slide");
                         $('.sixth_slide').addClass("active-slide");
                    },
                    error:function() {
                         console.log('update_scheme ajax error');
                    }
                });

             
            
        } else if(slideid == 6) {
            var commencement_year = $('#commencement_year').val();
            var scheme_status = $("input[name='scheme_status']").val();
            var is_sdg = $('input[name="is_sdg[]"]:checked').length;
            if(commencement_year != '' && scheme_status != '' && is_sdg > 0) {
              let nextSlide = countIncrease(slideid);
updateStepTitle(nextSlide); 
                $("#the_error_html").remove();
                var checked_scheme_status = [];
                var i=0;
                $('input[name="is_sdg[]"]:checked').each(function() {
                    checked_scheme_status[i] = this.value;
                    i++;
                });

                $.ajax({
                    type:'post',
                    dataType:'json',
                    url:"{{ route('schemes.update_scheme') }}",
                    data:{'_token':"{{ csrf_token() }}",'slide':'sixth','draft_id':draft_id,'scheme_id':scheme_id,'commencement_year':commencement_year,'scheme_status':scheme_status,'is_sdg':checked_scheme_status},
                    success:function(response) {
                        $(".otherslides").hide();
                        $(".seventh_slide").show();
                        $("#previous_btn").val(7).show();
                        $("#next_btn").val(7).show();
                        $('.sixth_slide').removeClass("active-slide");
                         $('.seventh_slide').addClass("active-slide");
                    },
                    error:function() {
                         console.log('update_scheme ajax error');
                    }
                });

            } else {
                $("#the_error_html").remove();
                var the_html = '<div class="row" id="the_error_html"><div class="col-xl-12" style="color:red;font-size:20px">* All Fields are required</div></div>';
                $(".sixth_slide").append(the_html);
            }
        } else if(slideid == 7) {

          var beneficiaries = [];
            jQuery('.next_beneficiary_selection_criterias').each(function() {
              var currentElement = $(this);
              var value = currentElement.val();
              beneficiaries.push(value);
          });
            var beneficiaryFile = $('#beneficiary_selection_criteria_file')[0].files[0];
            var existing_beneficiary_selection_criteria_file = $(".existing_beneficiary_selection_criteria_file").val();
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
                formData.append('draft_id', draft_id);
                formData.append('scheme_id', scheme_id);
                formData.append('existing_beneficiary_selection_criteria_file', existing_beneficiary_selection_criteria_file);
                // Append file input
                if (beneficiaryFile) {
                    formData.append('beneficiary_selection_criteria_file', beneficiaryFile);
                }

                $.ajax({
                    type:'post',
                    dataType:'json',
                    url:"{{ route('schemes.update_scheme') }}",
                    data:formData,
                    contentType: false,
                    processData: false,
                    success:function(response) {
                        $(".otherslides").hide();
                        $(".eighth_slide").show();
                        $("#previous_btn").val(8).show();
                        $("#next_btn").val(8).show();
                        $('.seventh_slide').removeClass("active-slide");
                        $('.eighth_slide').addClass("active-slide");
                    },
                    error:function() {
                         console.log('update_scheme ajax error');
                    }
                });

            } else {
                $("#the_error_html").remove();
                var the_html = '<div class="row" id="the_error_html"><div class="col-xl-12" style="color:red;font-size:20px">* All Fields are required</div></div>';
                $(".seventh_slide").append(the_html);
            }
        } else if(slideid == 8) {
           var major_text = $(".major_benefit_textareas").val();
            if(major_text != '') {
               let nextSlide = countIncrease(slideid);
                updateStepTitle(nextSlide);  
              
              $("#the_error_html").remove();

                $.ajax({
                    type:'post',
                    dataType:'json',
                    url:"{{ route('schemes.add_scheme') }}",
                    data:{'slide':'eighth','major_benefits_text':major_text,'draft_id':draft_id,'scheme_id':scheme_id,'_token':"{{ csrf_token() }}"},
                    success:function(response) {
                        $(".otherslides").hide();
                        $(".nineth_slide").show();
                        $("#previous_btn").val(9).show();
                        $("#next_btn").val(9).show();
                         $('.eighth_slide').removeClass("active-slide");
                        $('.nineth_slide').addClass("active-slide");
                    },
                    error:function() {
                         console.log('update_scheme ajax error');
                    }
                });

            } else {
                    $("#the_error_html").remove();
                    var the_html = '<div class="row" id="the_error_html"><div class="col-xl-12" style="color:red;font-size:20px">* There are atleast 2 Major Benefits required</div></div>';
                    $(".eighth_slide").append(the_html);
                
            }
        } else if(slideid == 9) {
            var next_scheme_implementing_procedure = $("#next_scheme_implementing_procedure").val();
            var beneficiariesGeoLocal = $('#beneficiariesGeoLocal').val();
            var thedistrictlist = $(".thedistrictlist").length;
            var talukas = [];
            var districts = [];
            var states = [];
            var taluka_id = $('#taluka_id').val();
           console.log($('#taluka_id').val());
            if(thedistrictlist > 0) {
              if(beneficiariesGeoLocal == 1) { //state
                    var i = 0;
                    $("input[name='state_name[]']:checked").each(function() {
                        var ss_state = this.value;
                        states[i] = ss_state.replace(/"/g,'');
                        i++;
                    });
                }else if(beneficiariesGeoLocal == 3 || beneficiariesGeoLocal == 7) { //Developing Taluka

                  var i = 0;
                    $("input[name='taluka_name[]']:checked").each(function() {
                        var ss_taluka = this.value;
                        talukas[i] = ss_taluka.replace(/"/g,'');
                        i++;
                    });
                } else { // District
                    var i = 0;
                    $("input[name='district_name[]']:checked").each(function() {
                        var ss_district = this.value;
                        districts[i] = ss_district.replace(/"/g,'');
                        i++;
                    });
                }
            }
          var next_scheme_implementing_procedure = $("#next_scheme_implementing_procedure").val();
          var beneficiariesGeoLocal = $('#beneficiariesGeoLocal').val();
          var next_otherbeneficiariesGeoLocal = $('#next_otherbeneficiariesGeoLocal').val();

          if (next_scheme_implementing_procedure !== '' && beneficiariesGeoLocal !== '') {
              let nextSlide = countIncrease(slideid);
              updateStepTitle(nextSlide); 
                $("#the_error_html").remove();


                $.ajax({
                    type:'post',
                    dataType:'json',
                    url:"{{ route('schemes.update_scheme') }}",
                    data:{'_token':"{{ csrf_token() }}", 'slide':'nineth', 
                    'scheme_implementing_procedure':next_scheme_implementing_procedure, 
                    'beneficiariesGeoLocal':beneficiariesGeoLocal, 
                    'state_name':states, 
                    'district_name':districts,
                    'taluka_name':talukas, 
                    'taluka_id':taluka_id,
                    'otherbeneficiariesGeoLocal':next_otherbeneficiariesGeoLocal,'draft_id':draft_id,'scheme_id':scheme_id
                  },
                    success:function(response) {
                        $(".otherslides").hide();
                        $(".tenth_slide").show();
                        $("#previous_btn").val(10).show();
                        $("#next_btn").val(10).show();
                        $('.nineth_slide').removeClass("active-slide");
                        $('.tenth_slide').addClass("active-slide");
                    },
                    error:function() {
                         console.log('update_scheme ajax error');
                    }
                });

            } else {
                $("#the_error_html").remove();
                var the_html = '<div class="row" id="the_error_html"><div class="col-xl-12" style="color:red;font-size:20px">* All Fields are required</div></div>';
                $(".nineth_slide").append(the_html);
            }
        } else if(slideid == 10) {
            var next_coverage_beneficiaries_remarks = $("#next_coverage_beneficiaries_remarks").val();
            var beneficiaries_coverage = $("#beneficiaries_coverage")[0].files.length;
            // var implementation_year = $("#implementation_year").val();
            var next_training_capacity_remarks = $("#next_training_capacity_remarks").val();
            var training = $("#training")[0].files.length;
            var next_iec_activities_remarks = $("#next_iec_activities_remarks").val();
            var next_iec = $("#iec")[0].files.length;
            if(next_coverage_beneficiaries_remarks != '' && next_training_capacity_remarks != '' && next_iec_activities_remarks != '') {
              let nextSlide = countIncrease(slideid);
updateStepTitle(nextSlide);   
              $("#the_error_html").remove();
                  var formDataNineth = new FormData();
                    formDataNineth.append('_token', "{{ csrf_token() }}");
                    formDataNineth.append('slide', 'tenth');
                    formDataNineth.append('coverage_beneficiaries_remarks', next_coverage_beneficiaries_remarks);
                    formDataNineth.append('training_capacity_remarks', next_training_capacity_remarks);
                    formDataNineth.append('iec_activities_remarks', next_iec_activities_remarks);
                    formDataNineth.append('draft_id', draft_id);
                    formDataNineth.append('scheme_id', scheme_id);
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

                $.ajax({
                    type:'post',
                    dataType:'json',
                    url:"{{ route('schemes.update_scheme') }}",
                    data:formDataNineth,
                    processData: false,
                    contentType: false,
                    success:function(response) {
                        // console.log(response);
                        $(".otherslides").hide();
                        $(".eleventh_slide").show();
                        $("#previous_btn").val(11).show();
                        $("#next_btn").val(11).show();
                        $('.tenth_slide').removeClass("active-slide");
                        $('.eleventh_slide').addClass("active-slide");
                    },
                    error:function() {
                         console.log('update_scheme ajax error');
                    }
                });

            } else {
                $("#the_error_html").remove();
                var the_html = '<div class="row" id="the_error_html"><div class="col-xl-12" style="color:red;font-size:20px">* All Fields are required</div></div>';
                $(".tenth_slide").append(the_html);
            }
        } else if(slideid == 11) {
            var next_benefit_to = $("#next_benefit_to").val();
            console.log('next_benefit_to', next_benefit_to);
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

          
           // if(next_benefit_to != '') {
              let nextSlide = countIncrease(slideid);
              updateStepTitle(nextSlide); 
              $("#the_error_html").remove();

                $.ajax({
                    type:'post',
                    dataType:'json',
                    url:"{{ route('schemes.update_scheme') }}",
                    data:{'_token':"{{ csrf_token() }}", 'slide':'eleventh','draft_id':draft_id,'scheme_id':scheme_id, 'benefit_to':next_benefit_to,'all_convergence':all_convergence},
                    success:function(response) {
                        // console.log(response);
                        $(".otherslides").hide();
                        $(".twelth_slide").show();
                        $("#previous_btn").val(12).show();
                        $("#next_btn").val(12).show();
                        $('.eleventh_slide').removeClass("active-slide");
                        $('.twelth_slide').addClass("active-slide");
                    },
                    error:function() {
                         console.log('update_scheme ajax error');
                    }
                });

            // } else {
            //     $("#the_error_html").remove();
            //     var the_html = '<div class="row" id="the_error_html"><div class="col-xl-12" style="color:red;font-size:20px">* All Fields are required</div></div>';
            //     $(".eleventh_slide").append(the_html);
            // }
        } else if(slideid == 12) {
              var existing_gr_files = {{ $gr_files->count() ?? 0 }};
              var existing_notification_files = {{ $notifications->count() ?? 0 }};

              var next_gr_files = 0;
                $('input[name="gr[]"]').each(function() {
                    next_gr_files += this.files.length;
                });
              var next_notification_files = $('.next_notification_files')[0]?.files.length || 0;
              var next_brochure_files = $(".next_brochure_files")[0]?.files.length || 0;
              var next_pamphlets_files = $('.next_pamphlets_files')[0]?.files.length || 0;
              var next_otherdetailscenterstate = $(".next_otherdetailscenterstate")[0]?.files.length || 0;

              // Validation: require at least GR or Notification file (new or existing)
              if ((next_gr_files > 0) || (existing_gr_files > 0)) {
                  $("#the_error_html").remove();
                  let nextSlide = countIncrease(slideid);
                  updateStepTitle(nextSlide); 

                  // --- Prepare FormData for upload ---
                  var tokenis = $("meta[name='csrf-token']").attr('content');
                  var formData = new FormData();
                  formData.append('_token', tokenis);
                  formData.append('slide', 'twelth');
                  formData.append('draft_id', draft_id);
                  formData.append('scheme_id', scheme_id);

                  // --- GR multiple files ---
                  $('input[name="gr[]"]').each(function () {
                      let files = this.files;
                      if (files.length > 0) {
                          for (let i = 0; i < files.length; i++) {
                              formData.append('gr[]', files[i]);
                          }
                      }
                  });

                  // --- Notification multiple files ---
                  let notificationFiles = $('#notification')[0]?.files || [];
                  for (let i = 0; i < notificationFiles.length; i++) {
                      formData.append('notification[]', notificationFiles[i]);
                  }

                  // --- Brochure multiple files ---
                  let brochureFiles = $('#brochure')[0]?.files || [];
                  for (let i = 0; i < brochureFiles.length; i++) {
                      formData.append('brochure[]', brochureFiles[i]);
                  }

                  // --- Pamphlets multiple files ---
                  let pamphletsFiles = $('#pamphlets')[0]?.files || [];
                  for (let i = 0; i < pamphletsFiles.length; i++) {
                      formData.append('pamphlets[]', pamphletsFiles[i]);
                  }

                  // --- Other Details multiple files ---
                  let otherDetailsFiles = $('#other_details_center_state')[0]?.files || [];
                  for (let i = 0; i < otherDetailsFiles.length; i++) {
                      formData.append('otherdetailscenterstate[]', otherDetailsFiles[i]);
                  }

                  // --- Beneficiary Form (conditional) ---
                  let fillingType = $('input[name="beneficiary_filling_form_type"]:checked').val();
                  formData.append('beneficiary_filling_form_type', fillingType ?? '');
                  if (fillingType === '0') {
                      let benFile = $('#beneficiary_filling_form')[0]?.files || [];
                      if (benFile.length > 0) {
                          formData.append('beneficiary_filling_form', benFile[0]);
                      }
                  }

                  // --- Perform AJAX upload ---
                  $.ajax({
                      type: 'POST',
                      url: "{{ route('schemes.update_scheme') }}",
                      data: formData,
                      processData: false,
                      contentType: false,
                      success: function (response) {
                          // On success, move to next (thirteenth) slide
                          $(".otherslides").hide();
                          $(".thirteenth_slide").show();
                          $("#previous_btn").val(13).show();
                          $("#next_btn").val(13).show();
                          $('.twelth_slide').removeClass("active-slide");
                          $('.thirteenth_slide').addClass("active-slide");
                      },
                      error: function (xhr) {
                          console.log('File upload error:', xhr.responseText);
                          var errHtml = '<div class="row" id="the_error_html"><div class="col-xl-12" style="color:red;font-size:20px">* Upload failed. Please try again.</div></div>';
                          $(".twelth_slide").append(errHtml);
                      }
                  });
              } else {
                  // Validation error for missing GR/Notification
                  $("#the_error_html").remove();
                  var the_html = '<div class="row" id="the_error_html"><div class="col-xl-12" style="color:red;font-size:20px">* GR  documents are required</div></div>';
                  $(".twelth_slide").append(the_html);
              }
        } else if(slideid == 13) {
             var indicator_values = $(".getindicator_hod").val();
          //  if(indicator_values != '') {
              let nextSlide = countIncrease(slideid);
updateStepTitle(nextSlide); 
              $("#the_error_html").remove();

                $.ajax({
                    type:'post',
                    dataType:'json',
                    url:"{{ route('schemes.update_scheme') }}",
                    data:{'_token':"{{ csrf_token() }}", 'slide':'thirteenth','draft_id':draft_id,'scheme_id':scheme_id, 'major_indicator_hod':indicator_values},
                    success:function(response) {
                        $(".otherslides").hide();
                        $(".fourteenth_slide").show();
                        $("#previous_btn").val(14).show();
                        $("#next_btn").val(14).show();
                        $('.thirteenth_slide').removeClass("active-slide");
                        $('.fourteenth_slide').addClass("active-slide");
                    },
                    error:function() {
                         console.log('update_scheme ajax error');
                    }
                });

            // } else {
                
            //         $("#the_error_html").remove();
            //         var the_html = '<div class="row" id="the_error_html"><div class="col-xl-12" style="color:red;font-size:20px">* Indicator 1 and indicator 2 are required</div></div>';
            //         $(".thirteenth_slide").append(the_html);
                
            // }
        } else if(slideid == 14) {
            var next_financial_progress_year = $(".next_financial_progress_year").val();
          //  var next_financial_progress_units = $(".next_financial_progress_units").val();
            var next_financial_progress_target = $(".next_financial_progress_target").val();
            var next_financial_progress_achivement = $(".next_financial_progress_achivement").val();
            var next_financial_progress_allocation = $(".next_financial_progress_allocation").val();
            var next_financial_progress_expenditure = $(".next_financial_progress_expenditure").val();

            var next_financial_progress_selection = $(".next_financial_progress_selection").val();
           // var next_financial_progress_item  = $(".next_financial_progress_item").val();

            var count_tr = $("#thisistbody tr").length;
            if(next_financial_progress_selection != "" && next_financial_progress_year != ''  && next_financial_progress_target != '' && next_financial_progress_achivement != '' && next_financial_progress_allocation != '' && next_financial_progress_expenditure != '') {
              let nextSlide = countIncrease(slideid);
                updateStepTitle(nextSlide); 
                $("#the_error_html").remove();
                var tr_array = [];
                var count_blank_fields = 0;
                for(var i=0;i<count_tr;i++) {
                    var the_year = $(".next_fin_year_"+i).val();
                    var the_target = $(".next_fin_target_"+i).val();
                    var the_achievement = $(".next_fin_achivement_"+i).val();
                    var the_allocation = $(".next_fin_allocation_"+i).val();
                    var the_expenditure = $(".next_fin_expenditure_"+i).val();
                  //  var the_units = $(".next_fin_units_"+i).val();

                    var the_selection = $(".next_fin_selection_"+i).val();
                 //   var the_items = $(".next_fin_item_"+i).val();

                    if(the_selection != ''  && the_year != '' && the_target != '' && the_achievement != '' && the_allocation != '' && the_expenditure != '') {
                        tr_array[i] = {'financial_year':$(".next_fin_year_"+i).val(), 'target':$(".next_fin_target_"+i).val(), 'achievement':$(".next_fin_achivement_"+i).val(), 'allocation':$(".next_fin_allocation_"+i).val(), 'expenditure':$(".next_fin_expenditure_"+i).val(), 'selection': $(".next_fin_selection_"+i).val()};
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
                            url:"{{ route('schemes.update_scheme') }}",
                            data:{'_token':"{{ csrf_token() }}", 'slide':'fourteenth','draft_id':draft_id,'scheme_id':scheme_id,'tr_array':tr_array, 'financial_progress_remarks':financial_progress_remarks},
                            success:function(response) {


                              $(".otherslides").hide();
                               // $("#next_btn").val(14).show();
                                $("#next_btn").hide();
                                $('.last_btn').show();

                                var the_html_btn = '<button type="button" class="btn btn-success font-weight-bold text-uppercase last_btn" data-wizard-type="action-next" value="1" onclick="finishSlides()" id="next_btn"> Finish </button>';

                                $("#div_next_btn").html(the_html_btn);

                               
                            },
                            error:function() {
                                 console.log('update_scheme ajax error');
                            }
                        });
                    }
                }
            } else {
                $("#the_error_html").remove();
                var the_html = '<div class="row" id="the_error_html"><div class="col-xl-12" style="color:red;font-size:20px">* All Fields are required</div></div>';
                $(".thirteenth_slide").append(the_html);
            }
          $("#next_btn").hide();
        }
    }

   function getPrevSlide(prevslide) {
      let prevSlide = countPrevious(prevslide);
      updateStepTitle(prevSlide);
        if(prevslide == 2) {
          $('.second_slide').removeClass("active-slide");
          $('.first_slide').addClass("active-slide");
          $("#step2tab").removeClass("active");
          $("#step1tab").addClass("active");
          $(".otherslides").hide();
          $(".first_slide").show();
          $("#previous_btn").val(1).hide();
          $("#next_btn").val(1).show();
        } else if(prevslide == 3) {
            $('.third_slide').removeClass("active-slide");
            $('.second_slide').addClass("active-slide");
            $("#step3tab").removeClass("active");
            $("#step2tab").addClass("active");
            $(".otherslides").hide();
            $(".second_slide").show();
            $("#previous_btn").val(2).show();
            $("#next_btn").val(2).show();
        } else if(prevslide == 4) {
            $('.fourth_slide').removeClass("active-slide");
            $('.third_slide').addClass("active-slide"); 
          
            $(".otherslides").hide();
            $(".third_slide").show();
            $("#previous_btn").val(3).show();
            $("#next_btn").val(3).show();
        } else if(prevslide == 5) {
            $('.fifth_slide').removeClass("active-slide");
            $('.fourth_slide').addClass("active-slide"); 
            $(".otherslides").hide();
            $(".fourth_slide").show();
            $("#previous_btn").val(4).show();
            $("#next_btn").val(4).show();
        } else if(prevslide == 6) {
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
          $('.twelth_slide').removeClass("active-slide");
          $('.eleventh_slide').addClass("active-slide");
          $(".otherslides").hide();
          $(".eleventh_slide").show();
          $("#previous_btn").val(11).show();
          $("#next_btn").val(11).show();
      } else if(prevslide == 13) {
          $('.thirteenth_slide').removeClass("active-slide");
          $('.twelth_slide').addClass("active-slide");
          $(".otherslides").hide();
          $(".twelth_slide").show();
          $("#previous_btn").val(12).show();
          $("#next_btn").val(12).show();
      } else if(prevslide == 14) {
          $('.fourteenth_slide').removeClass("active-slide");
          $('.thirteenth_slide').addClass("active-slide");
          $(".otherslides").hide();
          $(".thirteenth_slide").show();
          $("#previous_btn").val(13).show();
          $("#the_error_html").remove();
          $("#div_next_btn").empty();
          var next_btn = '<button type="button" class="btn btn-primary font-weight-bold text-uppercase" data-wizard-type="action-next" value="13" onclick="getNextSlide(this.value)" id="next_btn">Next</button>';
          $("#div_next_btn").html(next_btn);
      }
    }

    function finishSlides() {
        Swal.fire({
          title: "Update Completed!",
          text: "Your Proposal has been completely updated.",
          icon: "success"
        }).then(okay => {
            if (okay) {
              var get_url = "{{ route('proposals', ['param' => 'new']) }}";
              window.location.href = get_url;
            }
        });
    }
    function showError(msg) {
    var the_html = `
        <div class="row" id="the_error_html">
            <div class="col-xl-12" style="color:red;font-size:20px">${msg}</div>
        </div>`;
    $(".active-slide").append(the_html);
}

$(document).ready(function(){
      $('#next_reference_year').change(function(){
        var selectedValue = $(this).val();        
        var selectedYear = parseInt(selectedValue.split('-')[0]);

        $('#next_reference_year2 option').each(function(){
            var year = parseInt($(this).val().split('-')[0]);
            if(year < selectedYear){
                $(this).hide();
            } else {
                $(this).show();
            }
        });
    });
     $( ".datepicker" ).datepicker({
          format: 'dd/mm/yyyy', 
          changeMonth: true,
          changeYear: true,
        //  maxDate: new Date(),
          yearRange: "-100:+0",
          autoclose: true
      });
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
function toggleNextButton() {
    if ($('.is-invalid').length > 0) {
        $('#next_btn').prop('disabled', true);
    } else {
        $('#next_btn').prop('disabled', false);
    }
}

$(document).on('input', '.mobile_number', function () {
    let value = this.value.replace(/[^0-9]/g, '');
    this.value = value.slice(0, 10);

    let $this = $(this);
    let $group = $this.closest('.form-group');
    $group.find('.mobile-error').remove();

    if (value.length > 0 && value.length < 10) {
        $this.addClass('is-invalid');
        $this.after('<div class="text-danger mobile-error">Please enter 10 digit mobile number</div>');
    } else {
        $this.removeClass('is-invalid');
    }

    toggleNextButton(); // 👈 ADD THIS
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
    let value = this.value.trim();
    let $this = $(this);
    let $group = $this.closest('.form-group');

    $group.find('.email-error').remove();

    let emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/;

    if (value.length > 0 && !emailRegex.test(value)) {
        $this.addClass('is-invalid');
        $this.after('<div class="text-danger email-error">Please enter a valid email address</div>');
    } else {
        $this.removeClass('is-invalid');
    }

    toggleNextButton(); // 👈 ADD THIS
});
$(document).on('blur', '.email-input-td', function () {
    let value = this.value.trim();
    let $this = $(this);
    let $td = $this.closest('td');

    // remove old error
    $td.find('.email-error').remove();

    let emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/;

    if (value !== '' && !emailRegex.test(value)) {
        $this.addClass('is-invalid');

        $this.after(
            '<div class="text-danger email-error">Please enter a valid email address</div>'
        );
    } else {
        $this.removeClass('is-invalid');
    }

    toggleNextButton();
});

</script>


