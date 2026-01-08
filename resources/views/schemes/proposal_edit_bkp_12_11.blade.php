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
}
</style>
@section('content')
@php
  use Illuminate\Support\Facades\Crypt;
@endphp
<!--begin::Content-->
<div class="content d-flex flex-column flex-column-fluid" id="kt_content" style="background-color:#a1ccd9;">
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
                      <!--begin: Wizard-->
                      <div class="wizard wizard-4" id="kt_wizard_v4" data-wizard-state="step-first" data-wizard-clickable="true">
                        <div class="card card-custom card-shadowless rounded-top-0 font-17" style="background: #dfc89e;">
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
                                  <div class="first_slide otherslides" style="{{(empty($val->save_last_item) || ($val->save_last_item  == 1)) ? 'display:block;' : 'display:none;' }}">
                                          <input type="hidden" name="draft_id" id="next_draft_id" value="{{ $val->draft_id }}">
                                          <input type="hidden" name="scheme_id" id="next_scheme_id" value="{{ $scheme_id }}">
                                          <div class="row ">
                                              <div class="col-xl-12">
                                                  <div class="form-group">
                                                    <label>Name of the Department (વિભાગનું નામ) <span class="required_filed"> * </span> : </label>
                                                    <input type="text" id="next_dept_name" value="{{$dept[0]->dept_name}}" name="dept_name" readonly class="form-control pattern @error('dept_id') is-invalid @enderror">
                                                      <input type="hidden" class="form-control" id="next_dept_id" name="dept_id" value="{{ $dept[0]->dept_id }}" />
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
                                                    <label>Name of the Convener Department <br>(વિભાગ તરફથી સંકલન કરનાર અધિકારીનું નામ) <span class="required_filed"> * </span> :</label>
                                                    <input type="text" name="convener_name" class="form-control pattern @error('convener_name') is-invalid @enderror" maxlength="100" id="con_id" value="{{ $val->convener_name }}">
                                                        @error('convener_name')
                                                            <div class="text-danger">* {{ $message }}</div>
                                                        @enderror
                                                  </div>
                                              </div>
                                              <div class="col-xl-4">
                                                <div class="form-group">
                                                  <label>Designation of the Convener Department <br>(વિભાગ તરફથી સંકલન કરનાર અધિકારી નો હોદ્દો) <span class="required_filed"> * </span> :</label>
                                                  <input type="text" name="convener_designation" class="form-control pattern @error('convener_designation') is-invalid @enderror" maxlength="100" id="convener_designation" value="{{ $val->convener_designation }}">
                                                      @error('convener_designation')
                                                          <div class="text-danger">* {{ $message }}</div>
                                                      @enderror
                                                </div>
                                              </div>
                                              <div class="col-xl-4">
                                                <div class="form-group">
                                                  <label style="font-size: 15.8px;">Contact Number of the Convener Department <br>(વિભાગ તરફથી સંકલન કરનાર અધિકારીના સંપર્ક નંબર) <span class="required_filed"> * </span> :</label>
                                                  <input type="text" name="convener_phone" class="form-control phoneNumber @error('convener_phone') is-invalid @enderror" maxlength="100" id="convener_phone" value="{{ $val->convener_phone }}">
                                                      @error('convener_phone')
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
                                                      <label>Name of the scheme/ Programme to be evaluated (કરવાના થતા મૂલ્યાંકન અભ્યાસ માટેના યોજના/કાર્યક્રમનું નામ) <span class="required_filed"> * </span> :</label>
                                                      <input type="text" id="form_scheme_name" class="form-control pattern @error('scheme_name') is-invalid @enderror" name="scheme_name" value="{{ $val->scheme_name }}" />
                                                        @error('scheme_name')
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
                                                      <input type="text" name="financial_adviser_phone" class="form-control phoneNumber" value="{{$val->financial_adviser_phone}}" id="financial_adviser_phone">
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
                                  <div class="second_slide otherslides" style="{{(!empty($val->save_last_item)) && ($val->save_last_item  == 2) ? 'display:block;' : 'display:none;' }}">
                                    <div class="row ">  
                                          <div class="col-xl-12">
                                              <div class="form-group major_objective_parent_div">
                                                <label> Major Objective of the Evaluation study (મૂલ્યાંકન અભ્યાસના મુખ્ય હેતુઓ) <span class="required_filed"> * </span> :</label><br>
                                                @if(!is_null($val->major_objective) && $val->major_objective != '')
                                                  @php $major_bene = json_decode($val->major_objective); @endphp
                                                  @foreach($major_bene as $kbene => $vbene)
                                                  <div class="room_fields_{{$kbene}}">
                                                      <!-- <label>Objective 1: </label> -->
                                                      <input id="next_major_objective" class="form-control next_major_objectives pattern" type="text" name="major_objective[{{$kbene}}][major_objective]" value="{{ $vbene->major_objective }}"/>
                                                      <br>
                                                  </div>
                                                  @endforeach
                                                  @else
                                                  <div class="room_fields_0">
                                                    <!-- <label>Objective 1: </label> -->
                                                    <input id="next_major_objective" class="form-control pattern next_major_objectives @error('major_objective.0.major_objective') is-invalid @enderror" type="text" name="major_objective[0][major_objective]" value="{{ old('major_objective.0.major_objective') }}"/>
                                                    @error('major_objective.0.major_objective')
                                                        <div class="text-danger">{{ $message }}</div>
                                                    @enderror
                                                    <br>
                                                </div>
                                                  @endif
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
                                      </div>
                                    </div>
                                  <!-- secnod slide close -->
                                  <div class="third_slide otherslides" style="{{(!empty($val->save_last_item)) && ($val->save_last_item  == 3) ? 'display:block;' : 'display:none;' }}">
                                    <div class="row ">  
                                        <div class="col-xl-12">
                                        <div class="form-group major_indicator_parent_div">
                                          <label>Major Monitoring Indicators for scheme to be evaluated (મૂલ્યાંકન હાથ ધરવાની થતી યોજનાની  સમીક્ષાના મુખ્ય માપદંડો) <span class="required_filed"> * </span>:</label><br>
                                          @if(!is_null($val->major_indicator) && $val->major_indicator != '')
                                            @php $major_indi = json_decode($val->major_indicator) @endphp
                                            @foreach($major_indi as $kindi => $vindi)
                                            <div class="indicator_fields_{{$kindi}}">
                                                <!-- <label>Indicator: 1 </label> -->
                                                <input id="next_major_indicator" class="form-control next_major_indicators pattern" type="text" name="major_indicator[{{$kindi}}][major_indicator]" value="{{ $vindi->major_indicator }}"/>
                                                <br>
                                            </div>
                                            @endforeach
                                            @else
                                            <div class="indicator_fields_0">
                                                <!-- <label>Indicator: 1 </label> -->
                                                <input id="next_major_indicator" class="form-control pattern next_major_indicators @error('major_indicator.0.major_indicator') is-invalid @enderror" type="text" name="major_indicator[0][major_indicator]" value="{{ old('major_indicator.0.major_indicator') }}" />
                                                @error('major_indicator.0.major_indicator')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                                <br>
                                            </div>
                                            @endif
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

                                    <!--end: Wizard Step 1-->
                                  <div class="fourth_slide otherslides" style="{{(!empty($val->save_last_item)) && ($val->save_last_item  == 4) ? 'display:block;' : 'display:none;' }}">
                                    <!--begin: Wizard Step 2-->
                                    {{-- <div class="pb-0" data-wizard-type="step-content"> --}}
                                      
                                      <div class="row ">
                                        <div class="col-xl-6">
                                          <div class="form-group" style="margin-top: 32px;">
                                            <label>Name of the HOD/ Branch. (કચેરીનું નામ)<span class="required_filed"> * </span> :</label>
                                            <select name="implementing_office" class="form-control implementing_office" id="implementing_office"> 
                                              <option value="">Select HOD</option>
                                              @foreach (department_hod_name(Auth::user()->dept_id) as $item)
                                                  <option value="{{ $item}}" {{ ($val->implementing_office ==  $item) ? 'selected' : ''}}>{{ $item}}</option>
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
                                        <div class="col-xl-6">
                                          {{-- <div class="form-group"> --}}
                                            <label>HOD/ Branch Contact No. (કચેરી ના સંપર્ક નંબર):</label>
                                            <div class="form-group">
                                              <div class="row align-items-center">
                                                  <div class="col-xl-3">
                                                      <div class="radio-inline">
                                                          <label class="radio">
                                                              <input type="radio" name="implementing_office_contact_type" value="0" class="implementing_office_contact_type" {{($val->implementing_office_contact_type == '0') ? 'checked' : ''}}>
                                                              LandLine  
                                                          </label>
                                                      </div>
                                                  </div>
                                                  <div class="col-xl-3">
                                                    <div class="radio-inline">
                                                        <label class="radio">
                                                          <input type="radio" name="implementing_office_contact_type" value="1" class="implementing_office_contact_type" {{($val->implementing_office_contact_type == '1') ? 'checked' : '' }}>
                                                          Mobile No 
                                                      </label>
                                                    </div>
                                                  </div>
                                                  <input type="text" name="implementing_office_contact" class="form-control pattern" id="implementing_office_contact" value="{{ $val->implementing_office_contact }}">

                                              </div>
                                            </div>
                                          {{-- </div> --}}
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
                                     
                                      <div class="row" id="the_ratios">
                                        <div class="col-xl-12"><label>Fund Flow (in %) (યોજના માટેનો નાણાકીય સ્ત્રોત્ર)<span class="required_filed"> * </span> :</label></div>
                                        <div class="col-xl-6">
                                          <div class="form-group">
                                              <div class="row align-items-center">
                                                  <div class="col-xl-3">
                                                      <label>State Govt.(%) (રાજ્ય: %)</label>
                                                      <input type="text" name="state_ratio" class="form-control numberonly state_per" placeholder="Percentage Sponsored by state govt" id="state_ratio" value="{{$val->state_ratio}}">
                                                  </div>
                                                  <div class="col-xl-3">
                                                      <label>Central Govt.(%) (કેન્દ્ર: %)</label>
                                                      <input type="text" name="center_ratio" class="form-control numberonly state_per" placeholder="Percentage Sponsored by central govt" id="center_ratio" value="{{$val->center_ratio}}">
                                                  </div>
                                                  <div class="col-xl-5">
                                                      <label>Other:</label>
                                                      {{-- <div class="radio-inline">
                                                          <label class="radio">
                                                              <input type="radio" name="both_ratio_type" value="0" class="both_ratio_type" {{($val->both_ratio_type == '0') ? 'checked' : ''}}>
                                                              RS 
                                                          </label>
                                                          <label class="radio">
                                                              <input type="radio" name="both_ratio_type" value="1" class="both_ratio_type" {{($val->both_ratio_type == '1') ? 'checked' : '' }}>
                                                              Percentage 
                                                          </label>
                                                      </div> --}}
                                                      <textarea name="both_ratio" class="form-control pattern" placeholder="Remarks" id="both_ration">{{$val->both_ration}}</textarea>
                                                      {{-- <input type="text" name="both_ratio" class="form-control" placeholder="Remarks" id="both_ration" value="{{$val->both_ration}}"> --}}
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
                                      
                                        <div class="col-xl-6" style="margin-top: 27px;">
                                          <label>Name of Implementing office (અમલીકરણ ઓફિસ નું નામ)<span class="required_filed"> * </span> :</label>
                                          <input type="text" name="hod_name" class="form-control pattern" id="hod_name" value="{{ $val->hod_name }}">
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
                                  <div class="fifth_slide otherslides" style="{{(!empty($val->save_last_item)) && ($val->save_last_item  == 5) ? 'display:block;' : 'display:none;' }}">
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
                                    {{-- </div> --}}
                                  </div>
                                
                                  <!-- fifth_slide close -->
                                  <div class="sixth_slide otherslides" style="{{(!empty($val->save_last_item)) && ($val->save_last_item  == 6) ? 'display:block;' : 'display:none;' }}">
                                    {{-- <div class="pb-5" data-wizard-type="step-content"> --}}
                                          <div class="row ">
                                            <div class="col-xl-12">
                                              <!--begin::Input-->
                                              <div class="form-group">
                                                <label>Beneficiary/Community selection Criteria (લાભાર્થી/સમુદાયની પાત્રતા માટેના માપદંડો) <span class="required_filed"> * </span> : </label>
                                              </div>
                                              @php 
                                                $bene_cri = explode(',',$val->scheme_beneficiary_selection_criteria); 
                                              @endphp
                                              //json_decode($val->scheme_beneficiary_selection_criteria); @endphp
                                              @if(!empty($bene_cri))
                                                @foreach($bene_cri as $kcri => $vcri)
                                                <div class="form-group" id="beneficiary_selection_div_{{$kcri}}">
                                                  <label>Beneficiary Criteria {{($kcri == 0) ?  1 : ''}} :<small><b>Max 3000 characters</b></small></label>
                                                  <textarea class="form-control next_beneficiary_selection_criterias pattern" id="next_beneficiary_selection_criteria" name="beneficiary_selection_criteria[{{$kcri}}][beneficiary_selection_criteria]" rows="2" maxlength="3000">{{ $vcri }}</textarea>
                                                </div>
                                                @endforeach
                                              @else
                                                <div class="form-group" id="beneficiary_selection_div_0">
                                                  <label>Beneficiary Criteria 1 : <small><b>Max 3000 characters</b></small></label></label>
                                                  <textarea class="form-control next_beneficiary_selection_criterias pattern" id="next_beneficiary_selection_criteria_0" name="beneficiary_selection_criteria[0][beneficiary_selection_criteria]" rows="2" maxlength="3000"></textarea>
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
                                  <div class="seventh_slide otherslides" style="{{(!empty($val->save_last_item)) && ($val->save_last_item  == 7) ? 'display:block;' : 'display:none;' }}">
                                    <form method="post" id="seventh_slide_form" enctype="multipart/form-data">
                                            <input type="hidden" name="draft_id" id="next_draft_id" value="{{ $val->draft_id }}">
                                            <input type="hidden" name="scheme_id" id="next_scheme_id" value="{{ $scheme_id }}">
                                          @csrf
                                          <input type="hidden" name="slide" value="seventh">
                                          <div class="row ">
                                            <div class="col-xl-12">
                                              <!--begin::Input-->
                                              <div class="form-group">
                                                <label>Expected Major Benefits Derived from the Scheme (યોજનાથી અપેક્ષિત મુખ્ય લાભો)<span class="required_filed"> * </span> :  </label>
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
                                                  <label>Major Benefit {{ $kmaj + 1 }} <span class="required_filed"> * </span> : <small><b> Max 3000 characters </b></small> </label>
                                                  <div>
                                                    <textarea class="form-control major_benefit_textareas pattern" name="major_benefits_text[{{$kmaj}}][major_benefits_text]" id="major_benefit_textarea_{{$kmaj}}" rows="2" maxlength="3000">{{ $vmaj->major_benefits_text }}</textarea>
                                                  </div>
                                                </div>
                                                @endforeach
                                              @else
                                                <div class="form-group" id="major_benefits_div_0">
                                                  <label>Major Benefit 1 <span class="required_filed"> * </span> :<small><b> Max 3000 characters </b></small> </label>
                                                  <div>
                                                    <textarea class="form-control major_benefit_textareas pattern" name="major_benefits_text[0][major_benefits_text]" id="major_benefit_textarea_0" rows="2" maxlength="3000"></textarea>
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
                                          <button type="submit" id="btn_seventh_slide_submit" style="visibility: hidden;"></button>
                                      </form>
                                  </div>

                                  <!-- seventh_slide close -->
                                  <div class="eighth_slide otherslides" style="{{(!empty($val->save_last_item)) && ($val->save_last_item  == 8) ? 'display:block;' : 'display:none;' }}">
                                    <form method="post" enctype="multipart/form-data" id="eighth_slide_form">
                                      <input type="hidden" name="draft_id" id="next_draft_id" value="{{ $val->draft_id }}">
                                      <input type="hidden" name="scheme_id" id="next_scheme_id" value="{{ $scheme_id }}">
                                        @csrf
                                        <input type="hidden" name="slide" value="eighth">
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
                                            <select name="taluka_id" class="form-control" id="districtList" style="display: none;">
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
                                        <button type="submit" id="btn_eighth_slide_submit" style="visibility: hidden;"></button>
                                    </form>
                                  </div>
                                 
                                  <!-- eighth_slide close -->
                                  <div class="nineth_slide otherslides" style="{{(!empty($val->save_last_item)) && ($val->save_last_item  == 9) ? 'display:block;' : 'display:none;' }}">
                                     <form method="post" enctype="multipart/form-data" id="nineth_slide_form">
                                             <input type="hidden" name="draft_id" id="next_draft_id" value="{{ $val->draft_id }}">
                                            <input type="hidden" name="scheme_id" id="next_scheme_id" value="{{ $scheme_id }}">
                                          @csrf
                                          <input type="hidden" name="slide" value="nineth">
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
                                              <button type="submit" id="btn_nineth_slide_submit" style="visibility: hidden;"></button>
                                          </form>
                                  </div>

                                    <!-- nineth_slide close -->
                                    <div class="tenth_slide otherslides" style="{{(!empty($val->save_last_item)) && ($val->save_last_item  == 10) ? 'display:block;' : 'display:none;' }}">
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
                                    <div class="eleventh_slide otherslides" style="{{(!empty($val->save_last_item)) && ($val->save_last_item  == 11) ? 'display:block;' : 'display:none;' }}">
                                       <form method="post" enctype="multipart/form-data" id="eleventh_slide_form">
                                          <input type="hidden" name="draft_id" id="next_draft_id" value="{{ $val->draft_id }}">
                                          <input type="hidden" name="scheme_id" id="next_scheme_id" value="{{ $scheme_id }}">
                                           @csrf
                                           <input type="hidden" name="slide" value="eleventh">
                                            <div class="row ">
                                              <div class="col-xl-12">
                                                <label>Scheme Related all relevant Literature (યોજના સંબંધિત સાહિત્ય)</label> 
                                              </div>
                                            </div>
                                            <div class="row mt-3">
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
                                                        {{-- <a href="{{ $replace_url }}/get_the_file/{{ $pval->scheme_id }}/_gr_{{++$kgrs}}" target="_blank">
                                                          <i class="fas fa-file-pdf fa-2x" style="color:red;"></i>
                                                        </a> --}}
                                                        @endforeach
                                                     @endif
                                                </div>
                                                @endif
                                                <!--end::Input-->
                                              </div>
                                              @php
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
                                            
                                            </div>
                                            <div class="row">
                                              <div class="col-xl-6">
                                                <!--begin::Input-->
                                                <div class="form-group">
                                                  <label>Notification  (જાહેરનામાં)<span class="required_filed"> * </span> : </label><span style="color: #5b6064; margin-left:15px;">You can uploaded multiple files</span>
                                                  
                                                  <div class="custom-file">
                                                    <input type="file" class="custom-file-input next_notification_files file_type_name" id="notification" name="notification[]" multiple accept=".pdf,.docx,.xlsx" disabled />
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
                                            <button type="submit" id="btn_eleventh_slide_submit" style="visibility: hidden;"></button>
                                        </form>
                                    </div>
                                    
                                    <!-- eleventh_slide close -->
                                    <div class="twelth_slide otherslides" style="{{(!empty($val->save_last_item)) && ($val->save_last_item  == 12) ? 'display:block;' : 'display:none;' }}">
                                          <div class="row ">  
                                            <div class="col-xl-12">
                                              <label>Major Monitoring Indicator at HOD Level (Other than Secretariat Level) (ખાતાના વડાકક્ષાએ મહત્વના ઇન્ડિકેટર નુ મોનીટરીંગ.(સચિવાલય સિવાય)) : </label> 
                                            </div>
                                          </div>
                                          <div class="row">  
                                            <table class="table" id="indicator_table">
                                              <tbody>
                                              @php 
                                                  $indicator_hods = json_decode($val->major_indicator_hod); 
                                                  $indicator_hod_count = (!empty($indicator_hods)) ? count($indicator_hods) : 0;
                                              @endphp
                                              @if(empty($indicator_hods))
                                                <tr>
                                                  <th class="borderless"><label>Indicator 1 </label></th>
                                                  <td class="borderless major_hod_indicator_td" width="95%">
                                                    <input class="form-control getindicator_hod" id="indicator_hod_id_0" type="text" name="major_indicator_hod[0][major_indicator_hod]" />
                                                  </td>
                                                  <td class="borderless" width="5%">
                                                    <button type="button" class="btn btn-primary" id="addnewindicatorbtn" style="padding:2px;width:20px;height:auto;font-weight:bolder">+</button>
                                                  </td>
                                                </tr>
                                              @else
                                           
                                                   @foreach($indicator_hods as $khod => $vhod)
                                                    @if($indicator_hod_count >= 0)
                                                      <tr class="indicator_tr_{{++$khod}}">
                                                          <th class="borderless col-md-1"><label>Indicator {{$khod}} </label></th>
                                                          <td class="borderless major_hod_indicator_td" width="95%">
                                                          <input class="form-control getindicator_hod" id="indicator_hod_id_{{$khod}}" type="text" name="major_indicator_hod[{{$khod}}][major_indicator_hod]" value="{{ $vhod->major_indicator_hod }}" /></td>
                                                          <td class="borderless" width="5%">
                                                              @if($loop->last)
                                                                  <button type="button" class="btn btn-primary" id="addnewindicatorbtn" style="padding:2px;width:20px;height:auto;font-weight:bolder">+</button>
                                                              @else
                                                                  <button type="button" class="btn btn-primary" value="{{$khod}}" id="removeindicatorbtn" style="padding:2px;width:20px;height:auto;font-weight:bolder" onclick="removeindicatorrow(this.value)">-</button>
                                                              @endif
                                                          </td>
                                                      </tr>
                                                      @endif 
                                                    @endforeach 
                                                @endif
                                              </tbody>
                                            </table>
                                          </div>
                                    </div>
                                    
                                    <!-- twelth_slide close -->
                                    <div class="thirteenth_slide otherslides" style="{{(!empty($val->save_last_item)) && ($val->save_last_item  == 13) ? 'display:block;' : 'display:none;' }}">
                                        <div class="row ">
                                          <div class="col-xl-12">
                                            <label> Financial & Physical Progress (component wise) of Last Five Year (છેલ્લા પાંચ વર્ષની વર્ષવાર નાણાકીય અને ભૌતિક પ્રગતિ (કમ્પોનેટ વાઇઝ))  <span class="required_filed"> * </span>:</label>
                                          </div>
                                        </div>
                                        <div class="row">
                                          <div class="col-xl-12">
                                            <table class="table table-bordered" id="kt_datatable" style="margin-top: 13px !important">
                                              <thead>
                                                <tr>
                                                  <th rowspan="2" style="font-size: 16px;" class="text-center">Financial Year/નાણાકીય વર્ષ </th>
                                                  <!-- <th rowspan="2" style="font-size: 16px;">Unit</th> -->
                                                  <th colspan="2" class="text-center">Physical/ભૌતિક</th>
                                                  <th colspan="2" class="text-center">Financial/નાણાકીય <small>(Rs)</small></th>
                                                  <th colspan="2" class="text-center">Unit of Physical/ભૌતિક</th>
                                              <tr class="text-center">
                                                  {{-- <th style="font-size: 16px;">Districts/ જિલ્લાઓ</th> --}}
                                                  <th style="font-size: 16px;">Target – લક્ષ્યાંક</th>
                                                  <th style="font-size: 16px;">Achievement – સિધ્ધિ</th>
                                                  <th style="font-size: 16px;">Provision– જોગવાઇ</th>
                                                  <th style="font-size: 16px;">Expenditure – ખર્ચ</th>
                                                  <th style="font-size: 16px;">Benefit - લાભ</th>
                                                  <th style="font-size: 16px;">Type - પ્રકાર </th>
                                                  <th style="font-size: 16px;"></th>
                                              </tr>
                                              </thead>
                                              
                                              <tbody id="thisistbody">
                                               
                                                @if($financial_progress->count() > 0)
                                                @foreach($financial_progress as $i => $vfp)
                                                
                                                <tr class="finprogresstr_{{$i}}">
                                                  <td class="finprogresstd_{{$i}}"><select style="padding:2px" class="form-control next_financial_progress_year next_fin_year_{{$i}}" name="financial_progress[{{ $i }}][financial_year]"><option value="">Year</option>@foreach($financial_years as $year) <option value="{{ $year }}" @if($year == $vfp->financial_year) selected @endif>{{ $year }}</option> @endforeach </select></td>
                                                  {{-- <td class="finprogresstd_{{$i}}"><input type="text" class="form-control next_financial_progress_units next_fin_units_{{$i}}" name="financial_progress[{{ $i }}][units]" maxlength="20" value="{{ $vfp->units }}" /></td> --}}
                                                  <td class="finprogresstd_{{$i}}"><input type="text" class="pattern form-control allowonly2decimal next_financial_progress_target next_fin_target_{{$i}}" name="financial_progress[{{ $i }}][target]" value="{{$vfp->target}}" /></td>
                                                  <td class="finprogresstd_{{$i}}"><input type="text" class="pattern form-control allowonly2decimal next_financial_progress_achivement next_fin_achivement_{{$i}}" name="financial_progress[{{ $i }}][achivement]" value="{{$vfp->achievement}}" /></td>
                                                  <td class="finprogresstd_{{$i}}"><input type="text" class="pattern form-control allowonly2decimal next_financial_progress_allocation next_fin_allocation_{{$i}}" name="financial_progress[{{ $i }}][allocation]" value="{{ $vfp->allocation }}" /></td>
                                                  <td class="finprogresstd_{{$i}}"><input type="text" class="pattern form-control allowonly2decimal next_financial_progress_expenditure next_fin_expenditure_{{$i}}" name="financial_progress[{{ $i }}][expenditure]" value="{{ $vfp->expenditure }}" /></td>
                                                  <td class="finprogresstd_{{$i}}">
                                                    <select style="padding:2px" class="form-control next_financial_progress_selection next_fin_selection_{{$i}}" id="next_fin_selection_{{$i}}" name="financial_progress[{{ $i }}][selection]">
                                                    <option value="">Select Option</option>
                                                    @foreach($units as $unit_item) 
                                                        <option value="{{ $unit_item->id }}" @if($unit_item->id == $vfp->selection) selected @endif>{{ $unit_item->name }}</option> 
                                                    @endforeach 
                                                    <option value="0">Other</option> 
                                                    </select>
                                                  </td>
                                                  <td class="finprogresstd_{{$i}}"><input type="text" class="pattern form-control next_financial_progress_item next_fin_item_{{$i}}" data-id="{{$i}}" name="financial_progress[{{ $i }}][item]" value="{{ $vfp->items }}" /></td>
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
                                                  {{-- <td class="finprogresstd_{{$i}}"><input type="text" class="form-control next_financial_progress_units next_fin_units_{{$i}}" name="financial_progress[{{ $i }}][units]" maxlength="20" /></td> --}}
                                                  <td class="finprogresstd_{{$i}}"><input type="text" class="pattern form-control allowonly2decimal next_financial_progress_target next_fin_target_{{$i}}" name="financial_progress[{{ $i }}][target]"/></td>
                                                  <td class="finprogresstd_{{$i}}"><input type="text" class="pattern form-control allowonly2decimal next_financial_progress_achivement next_fin_achivement_{{$i}}" name="financial_progress[{{ $i }}][achivement]"/></td>
                                                  <td class="finprogresstd_{{$i}}"><input type="text" class="pattern form-control allowonly2decimal next_financial_progress_allocation next_fin_allocation_{{$i}}" name="financial_progress[{{ $i }}][allocation]" /></td>
                                                  <td class="finprogresstd_{{$i}}"><input type="text" class="pattern form-control allowonly2decimal next_financial_progress_expenditure next_fin_expenditure_{{$i}}" name="financial_progress[{{ $i }}][expenditure]" /></td>
                                                  <td class="finprogresstd_{{$i}}">
                                                    <select style="padding:2px" class="form-control next_financial_progress_selection next_fin_selection_{{$i}}" id="next_fin_selection_{{$i}}" name="financial_progress[{{ $i }}][selection]">
                                                    <option value="">Select Option</option>
                                                    @foreach($units as $unit_item) 
                                                        <option value="{{ $unit_item->id }}">{{ $unit_item->name }}</option> 
                                                    @endforeach 
                                                    <option value="0">Other</option> 
                                                    </select>
                                                </td>
                                                  <td class="finprogresstd_{{$i}}">
                                                    <input type="text" class="pattern form-control next_financial_progress_item next_fin_item_{{$i}}"  data-id="{{$i}}" name="financial_progress[{{ $i }}][item]" />
                                                </td>
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
                                    <div class="fourteenth_slide otherslides" style="{{(!empty($val->save_last_item)) && ($val->save_last_item  == 14) ? 'display:block;' : 'display:none;' }}">
                                          <form method="post" id="fourteenth_slide_form" enctype="multipart/form-data">
                                            <input type="hidden" name="draft_id" id="next_draft_id" value="{{ $val->draft_id }}">
                                            <input type="hidden" name="scheme_id" id="next_scheme_id" value="{{ $scheme_id }}">
                                              @csrf
                                              <input type="hidden" name="slide" value="fourteenth">
                                              <div class="row ">
                                                <div class="col-xl-12">
                                                  <div class="form-group">
                                                    <label>Whether evaluation of this scheme already done in past? (આ યોજનાનું મૂલ્યાંકન અગાઉ થઈ ચૂકેલ છે?) <span class="required_filed"> * </span> :</label>
                                                    <div></div>
                                                    <div class="radio-inline">
                                                      <label class="radio radio-rounded">
                                                          <input type="radio" name="is_evaluation" id="is_evaluation_yes" value="Y" class="is_evaluation" onclick="fn_show_if_eval(this.value)" @if($val->is_evaluation == 'Y') checked @endif />
                                                          <span></span>
                                                          Yes (હા)
                                                      </label>
                                                      <label class="radio radio-rounded">
                                                          <input type="radio" name="is_evaluation" id="is_evaluation_no" value="N" class="is_evaluation" onclick="fn_show_if_eval(this.value)" @if($val->is_evaluation == 'N') checked @endif />
                                                          <span></span>
                                                          No (ના)
                                                      </label>
                                                    </div>
                                                  </div>
                                                </div>                
                                              </div>
                                              <div class="form_eval_yes_div">
                                              <div class="row" id="if_eval_yes_div" style="display:none">
                                                  <div class="col-xl-12">
                                                      <div class="form-group">
                                                        <label>By Whom? (કોના દ્વારા?)</label>
                                                        <input type="text" name="eval_by_whom" id="eval_by_whom" class="form-control pattern" value="{{$val->eval_scheme_bywhom}}">
                                                      </div>
                                                  </div>
                                                  <div class="col-xl-12">
                                                      <div class="form-group">
                                                        <label>When? (ક્યારે?)</label>
                                                        <input type="date" name="eval_when" id="eval_when" class="form-control" value="{{ $val->eval_scheme_when }}">
                                                      </div>
                                                  </div>
                                                  <div class="col-xl-12">
                                                      <div class="form-group">
                                                        <label>Geographical coverage of Beneficiaries (સમાવિષ્ટ કરેલ લાભાર્થીઓનો ભૌગોલિક વિસ્તાર) </label>
                                                        <input type="text" name="eval_geographical_coverage_beneficiaries" class="form-control pattern" id="eval_geographical_coverage_beneficiaries" value="{{ $val->eval_scheme_geo_cov_bene }}">
                                                      </div>
                                                  </div>
                                                  <div class="col-xl-12">
                                                      <div class="form-group">
                                                        <label>No. of beneficiaries in sample (નિદર્શમાં સમાવિષ્ટ લાભાર્થીઓની સંખ્યા) <small>( greater than 10 )</small> </label>
                                                        <input type="text" name="eval_number_of_beneficiaries" class="form-control numberonly pattern" id="eval_number_of_beneficiaries" maxlength="90" value="{{ $val->eval_scheme_no_of_bene }}">
                                                      </div>
                                                  </div>
                                                  <div class="col-xl-12">
                                                      <div class="form-group">
                                                        <label>Major recommendations (મુખ્ય ભલામણો.)</label>
                                                        <input type="text" name="eval_major_recommendation" class="form-control pattern" id="eval_major_recommendation" value="{{ $val->eval_scheme_major_recommendation }}">
                                                      </div>
                                                  </div>
                                                  <div class="col-xl-12">
                                                      <div class="form-group">
                                                        <label>Upload report (અહેવાલ અપલોડ કરવો.) </label>
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
                                              <div class="row">
                                                  <div class="col-xl-5"></div>
                                                  <div class="col-xl-7">
                                                      <!-- <button type="submit" class="btn btn-success font-weight-bold text-uppercase px-9 py-4" data-wizard-type="action-submit" id="submit">Submit</button> -->
                                                  </div>
                                              </div>
                                            <button type="submit" style="visibility: hidden;" id="btn_fourteenth_slide_submit"></button>
                                          </form>
                                      <div id="send_eval_yes_div" style="display:none"></div>
                                    </div>
                              
                            
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
  count = $('.page_no div').html(parseInt(slideid) + 1);
  $('.save_item').attr('data-slide-item',parseInt(slideid) + 1);
}

var preCount = 0;
function countPrevious(prevslide){
  preCount = $('.page_no div').html(parseInt(prevslide) - 1);
  $('.save_item').attr('data-slide-item',parseInt(prevslide) - 1);
}
</script>

<script>

$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': "{{ csrf_token() }}"
    }
});
$(document).ready(function() {

  $( ".datepicker" ).datepicker({
          format: 'dd/mm/yyyy', 
          changeMonth: true,
          changeYear: true,
          maxDate: new Date(),
          beforeShow: function (input, inst) {
              $(input).attr("placeholder", "dd/mm/yyyy");
          },
          onSelect: function (dateText, inst) {
            // Enable the file input only after a date is selected
            $('#notification').prop('disabled', false);
        }
  });

  $('.max_file_size').on('change', function () {
        var fileInput = $(this)[0];
        var file = fileInput.files[0];
        var maxSize = 5 * 1024 * 1024; // 5 MB in bytes

        if (file.size > maxSize) {
            alert('File size must be less than 5 MB');
            $(this).val(''); // Clear the file input
        }
  });

  $('.implementing_office').on('change',function(){
    var otherVal = $(this).val();
    if(otherVal == 'other'){
      $('.other_val').css('display','block');
    }else{
      $('.other_val').css('display','none');
    }
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

  $('#implementing_office_contact').on('input', function () {
    var implementing_office_contact_type = $('input[name="implementing_office_contact_type"]:checked').val();
      
    if (implementing_office_contact_type == 0) {
      $(this).inputmask("(999) 9999")
    }else if (implementing_office_contact_type == 1) {
      $(this).inputmask("(999) 999-9999")
    }
  });


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

  $("#btn_add_objective").click(function(){
    var ktcontent = $("#kt_content").height();
    $(".content-wrapper").css('min-height',ktcontent+50);
    var room = $(".next_major_objectives").length - 1;
    var after_room = room+1;
    var major_objective = "major_objective";
    if(room < 10) {
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
    if(indicator_room < 10) {
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
    if(beneficiary_selection < 10) {
      if($("#beneficiary_selection_div_"+beneficiary_selection+" textarea").val() == '') {
        var alert_ben_sec = new String("You missed beneficiary criteria above");
        alert(alert_ben_sec);
      } else {
          $("#beneficiary_selection_div_"+beneficiary_selection).after('<div id="beneficiary_selection_div_'+after_beneficiary_selection+'"><label>Beneficiary Criteria : <small><b>Max 3000 characters</b></small></label><textarea class="form-control next_beneficiary_selection_criterias" type="text" name="beneficiary_selection_criteria['+after_beneficiary_selection+']['+beneficiary_selection_criteria+']" maxlength="3000"/></textarea><br></div>');
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
    //var units = 'units';
    var selection = 'selection';
    var item = 'item';
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
    // var entered_units = $('#thisistbody .next_financial_progress_units').eq(count_thisistbody_tr).val();
    var entered_target = $('#thisistbody .next_financial_progress_target').eq(count_thisistbody_tr).val();
    var entered_achievement = $('#thisistbody .next_financial_progress_achivement').eq(count_thisistbody_tr).val();
    var entered_fund = $('#thisistbody .next_financial_progress_allocation').eq(count_thisistbody_tr).val();
    var entered_expenditure = $('#thisistbody .next_financial_progress_expenditure').eq(count_thisistbody_tr).val();

    var entered_selection = $('#thisistbody .next_financial_progress_selection').eq(count_thisistbody_tr).val();
    var entered_item = $('#thisistbody .next_financial_progress_item').eq(count_thisistbody_tr).val();

    var split_finyear = entered_finyear.split('-');
    var add_one = Number(split_finyear[0]) + 1;
    var add_two = Number(split_finyear[1]) + 1;
    var entered_finyear = add_one+'-'+add_two;
    if(rownumber >= 1) {
      var addtr = '<tr class="finprogresstr_'+rownumber+'"><td class="finprogresstd_'+rownumber+'"><select style="padding:2px" class="form-control next_financial_progress_year next_fin_year_'+rownumber+'" name="financial_progress['+rownumber+']['+fiyear+']"><option value="">Year</option>@foreach($financial_years as $year) <option value="{{ $year }}" @if('+entered_finyear+' == $year) selected @endif>{{ $year }}</option> @endforeach</select></td><td class="finprogresstd_'+rownumber+'"><input type="text" class="form-control next_financial_progress_target allowonly2decimal next_fin_target_'+rownumber+'" name="financial_progress['+rownumber+']['+target+']" value="'+entered_target+'" /></td><td class="finprogresstd_'+rownumber+'"><input type="text" class="form-control next_financial_progress_achivement allowonly2decimal next_fin_achivement_'+rownumber+'" name="financial_progress['+rownumber+']['+achivement+']" value="'+entered_achievement+'" /></td><td class="finprogresstd_'+rownumber+'"><input type="text" class="form-control next_financial_progress_allocation allowonly2decimal next_fin_allocation_'+rownumber+'" name="financial_progress['+rownumber+']['+allocation+']" value="'+entered_fund+'" /></td><td class="finprogresstd_'+rownumber+'"><input type="text" class="form-control next_financial_progress_expenditure allowonly2decimal next_fin_expenditure_'+rownumber+'" name="financial_progress['+rownumber+']['+expenditure+']" value="'+entered_expenditure+'" /></td><td class="finprogresstd_'+rownumber+'"><select style="padding:2px" class="form-control next_financial_progress_selection next_fin_selection_'+rownumber+'" id="next_fin_selection_'+rownumber+'" name="financial_progress['+rownumber+']['+selection+']"><option value="">Select Option</option>@foreach($units as $unit_item)<option value="{{ $unit_item->id }}" @if($unit_item->id == '+entered_finyear+') selected @endif>{{ $unit_item->name }}</option>@endforeach<option value="0">Other</option></select></td><td class="finprogresstd_'+rownumber+'"><input type="text" class="form-control  next_financial_progress_item next_fin_item_'+rownumber+'" data-id="'+rownumber+'" name="financial_progress['+rownumber+']['+item+']" value="'+entered_item+'" /></td><td class="finprogresstd_'+rownumber+'"><button type="button" class="btn btn-primary finprogressbtnremove" onclick="remove_financial_year(this.value)" value="'+rownumber+'" style="padding:2px;width:20px;height:auto;font-weight:bolder;">-</button></td></tr>';
      $("#thisistbody tr:last").after(addtr);
    } else {
    var addtr = '<tr class="finprogresstr_'+nextrownumberzero+'"><td class="finprogresstd_'+nextrownumberzero+'"><select style="padding:2px" class="form-control next_financial_progress_year next_fin_year_'+nextrownumberzero+'" name="financial_progress['+nextrownumberzero+']['+fiyear+']"><option value="">Year</option>@foreach($financial_years as $year) <option value="{{ $year }}" @if('+entered_finyear+' == $year) selected @endif>{{ $year }}</option> @endforeach</select></td><td class="finprogresstd_'+nextrownumberzero+'"><input type="text" class="form-control next_financial_progress_target allowonly2decimal next_progress_year_'+nextrownumberzero+' next_fin_target_'+nextrownumberzero+'" name="financial_progress['+nextrownumberzero+']['+target+']"  value="'+entered_target+'"/></td><td class="finprogresstd_'+nextrownumberzero+'"><input type="text" class="form-control next_financial_progress_achivement allowonly2decimal next_fin_achivement_'+nextrownumberzero+'" name="financial_progress['+nextrownumberzero+']['+achivement+']"  value="'+entered_achievement+'"/></td><td class="finprogresstd_'+nextrownumberzero+'"><input type="text" class="form-control next_financial_progress_allocation allowonly2decimal next_fin_allocation_'+nextrownumberzero+'" name="financial_progress['+nextrownumberzero+']['+allocation+']"  value="'+entered_fund+'"/></td><td class="finprogresstd_'+nextrownumberzero+'"><input type="text" class="form-control next_financial_progress_expenditure allowonly2decimal next_fin_expenditure_'+nextrownumberzero+'" name="financial_progress['+nextrownumberzero+']['+expenditure+']" value="'+entered_expenditure+'"/></td> <td class="finprogresstd_'+nextrownumberzero+'"><select style="padding:2px" class="form-control next_financial_progress_selection next_fin_selection_'+nextrownumberzero+'" id="next_fin_selection_'+nextrownumberzero+'" name="financial_progress['+nextrownumberzero+']['+selection+']"><option value="">Select Option</option>@foreach($units as $unit_item)<option value="{{ $unit_item->id }}" @if($unit_item->id == '+entered_selection+') selected @endif>{{ $unit_item->name }}</option>@endforeach<option value="0">Other</option></select></td><td class="finprogresstd_'+nextrownumberzero+'"><input type="text" class="form-control  next_financial_progress_item next_fin_item_'+nextrownumberzero+'"  data-id="'+nextrownumberzero+'" name="financial_progress['+nextrownumberzero+']['+item+']" value="'+entered_item+'" /></td><td class="finprogresstd_'+nextrownumberzero+'"><button type="button" class="btn btn-primary finprogressbtnremove" onclick="remove_financial_year(this.value)" value="'+nextrownumberzero+'" style="padding:2px;width:20px;height:auto;font-weight:bolder;">-</button></td></tr>';
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

    // var ktcontent = $("#kt_content").height();
    // var ktcontent_long = Number(ktcontent) + 100;
   // $(".content-wrapper").css('min-height',ktcontent_long);

    indicator_array_num++;
    var major_indicator_hod = 'major_indicator_hod';
    var addindicator = '<tr class="indicator_tr_'+indicator_array_num+'"><th class="borderless col-md-1" width="100%" id="indicator_id_'+indicator_array_num+'">Indicator '+(indicator_array_num)+'</th><td class="borderless" width="95"><input class="form-control getindicator_hod" id="indicator_hod_id_'+indicator_array_num+'" type="text" name="major_indicator_hod['+indicator_array_num+']['+major_indicator_hod+']" /></td><td class="borderless" width="5%"><button type="button" class="btn btn-primary" value="'+indicator_array_num+'" id="removeindicatorbtn" style="padding:2px;width:20px;height:auto;font-weight:bolder" onclick="removeindicatorrow(this.value)">-</button></td></tr>';
    console.log($("#indicator_table tbody tr:last"));
    $("#indicator_table tbody tr:last").after(addindicator);
  });
});

function removeindicatorrow(row) {
    $("#indicator_table tbody .indicator_tr_"+row).remove();
   
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
            if(major_obj < 2) {
                $(".maj_obj_error").remove();
                $('.major_objective_parent_div').after("<div class='row maj_obj_error text-red' style='font-size:16px;margin-left:20px'>Please add minimum 2 major Objectives Scheme</div>");
                var major_obj_after = Number(major_obj)-1;
                count_total_msg = 1;
                // return false;
                alert('Warning : Major Objectives are less than 2 !!!!!!!');
            } else {
                $(".maj_obj_error").remove();
                count_total_msg = 0;
            }
            var major_ind = $(".major_indicator_parent_div div").length;
            // major_ind should be minimum 5
            if(major_ind < 2) {
                var major_ind_after = Number(major_ind)-1;
                count_total_msg = 1;
                // return false;
                alert('Warning : Major Indicators are less than 2 !!!!!!!');
            } else {
                $(".maj_ind_error").remove();
                count_total_msg = 0;
            }
            var major_hod_indicator = $(".indicator_tr_4").length;
            // major_hod_indicator must be 1
            if(major_hod_indicator < 1) {
                count_total_msg = 1;
                // return false;
                alert('Warning : Major Monitoring Indicators are less than 2 !!!!!!!');
            } else {
                count_total_msg = 0;
            }
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
        $('#districtList').hide();
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
                    if(response.districts != '' && response.districts != undefined) {
                    //  console.log('district');
                      $('#districtList').css('display','none');
                        $(".thedistrictlist").append("<div class='col-xl-3 all_item'><input type='checkbox' id='selectAllCheckbox' onchange='fnSelectAll(this.checked)'> All</div>");
                        $.each(response.districts, function(reskey, resval){
                            $(".thedistrictlist").append("<div class='col-xl-3'><input class='district_length' type='checkbox' style='margin:3px' value='"+resval.dcode+"' name='district_name[]'>"+resval.name_e+"</div>");
                        });
                    }
                    if(response.district_list != '' && response.district_list != undefined) {
                   //   console.log('district_list');
                      $('#districtList').css('display','block');
                      $('#districtList').empty();
                      if (!$.isEmptyObject(response.district_list)) {
                          $.each(response.district_list, function(key, value) {   
                              $('#districtList').append($("<option></option>").attr("value", value).text(key)); 
                          });
                      } else {
                          $('#districtList').append($("<option></option>").text('Select District'));
                      }
                    
                    }
                    if(response.state != '' && response.state != undefined){
                      //console.log('state');
                      $('#districtList').css('display','none');
                      $.each(response.state,function(reskey,resval){
                          $(".thedistrictlist").append("<div class='col-xl-3'><input class='state_length' type='checkbox' style='margin:3px' value='"+resval.id+"' name='state_name[]'>"+resval.name+"</div>");
                      });
                   }
            },
            error:function() {
                console.log('districts ajax error');
            }
        });
    });

    //fetch taluka
    $('#districtList').on('change',function(){
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
            $(".thedistrictlist").remove();
            $("#beneficiariesGeoLocal").after("<div class='row thedistrictlist' style='margin:20px;font-size:20px'></div>");
            var districtList = $(".thedistrictlist");

            if (response.districts && response.districts.length > 0) {
                $('#districtList').css('display', 'none');

                districtList.append("<div class='col-xl-3'><input type='checkbox' id='selectAllCheckbox' onchange='fnSelectAll(this.checked)'> All</div>");

                $.each(response.districts, function (reskey, resval) {
                    var thedcode = resval.dcode;
                    var checkbox = $("<div class='col-xl-3'><input class='district_length' type='checkbox' style='margin:3px' value='" + thedcode + "' name='district_name[]'>" + resval.name_e + "</div>");
                    districtList.append(checkbox);

                    // Check the checkbox if it exists in entered_values
                    if (response.entered_values.includes(String(thedcode))) {
                        checkbox.find('input[type="checkbox"]').prop('checked', true);
                    }
                });

            } else if (response.states && response.states.length > 0) {
                $('#districtList').css('display', 'none');

                $.each(response.states, function (key, val) {
                    var thedcode = val.id;
                    var checkbox = $("<div class='col-xl-3'><input class='state_length' type='checkbox' style='margin:3px' value='" + val.id + "' name='state_name[]'>" + val.name + "</div>");
                    districtList.append(checkbox);

                    if (response.entered_item.includes(String(thedcode))) {
                        checkbox.find('input[type="checkbox"]').prop('checked', true);
                    }
                });

            } else if (response.talukas && response.talukas.length > 0 && response.taluka_id) {
                $('#districtList').css('display', 'block');
                districtList.append("<div class='col-xl-3'><input type='checkbox' id='selectAllCheckbox' onchange='fnSelectAll(this.checked)'> All</div>");

                $.each(response.talukas, function (reskey, resval) {
                    var thedcode = resval.tcode;
                    var checkbox = $("<div class='col-xl-3'><input class='taluka_length' type='checkbox' style='margin:3px' value='" + resval.tcode + "' name='taluka_name[]'>" + resval.tname_e + "</div>");
                    districtList.append(checkbox);

                    if (response.entered_values.includes(String(thedcode))) {
                        checkbox.find('input[type="checkbox"]').prop('checked', true);
                    }
                });

                if (!$.isEmptyObject(response.district_list)) {
                    $.each(response.district_list, function (key, value) {
                        var option = $("<option></option>").attr("value", value.dcode).text(value.name_e);
                        if (value.dcode == response.taluka_id) {
                            option.attr("selected", "selected");
                        }
                        $('#districtList').append(option);
                    });
                } else {
                    $('#districtList').append($("<option></option>").text('Select District'));
                }
            }

            var ktcontent = $("#kt_content").height();
            $(".content-wrapper").css('min-height', ktcontent);
},

        error: function () {
            console.log('districts ajax error');
        }
    });
}
$(document).on('change', '.custom-file-input', function () {

      const $input      = $(this);
      const files       = this.files;
      if (!files.length) return;                       // user cancelled

      /* ---------- pull per‑input rules ---------- */
      const maxMB       = Number($input.data('max')) || 5;          // default 5 MB
      const allowedExt  = ($input.data('ext') || 'pdf,doc,docx')
                            .replace(/\s+/g, '')                    // trim spaces
                            .toLowerCase()
                            .split(',');

      /* ---------- validate every selected file ---------- */
      const badFiles = [];

      [...files].forEach(f => {
          const tooBig  = f.size > maxMB * 1024 * 1024;

          const parts   = f.name.split('.');
          const ext     = parts.pop().toLowerCase();
          const singleDot = parts.length === 1;       // stops “trick.php.pdf”

          const bad     = tooBig || !singleDot || !allowedExt.includes(ext);
          if (bad) badFiles.push(f.name);
      });

      /* ---------- outcome ---------- */
      if (badFiles.length) {
          alert(
              `Please choose a ${allowedExt.join(', ')} file ` +
              `(≤ ${maxMB} MB).\n\nInvalid selection:\n` + badFiles.join('\n')
          );
          $input.val('');                           // clear field
          $input.next('.custom-file-label').text('Choose file');
          return;
      }

      // show file name(s) in the Bootstrap label
      const label = files.length === 1 ? files[0].name : `${files.length} files`;
      $input.next('.custom-file-label').text(label);
  });

    $(document).ready(function(){
        $('.allowonly2decimal').keypress(function (e) {
            var character = String.fromCharCode(e.keyCode)
            var newValue = this.value + character;
            if (isNaN(newValue) || hasDecimalPlace(newValue, 3)) {
                e.preventDefault();
                return false;
            }
        });

        //
        $('.file_type_name').on('change', function () {
          // Get the selected file name

          var fileName = $(this).val().split('\\').pop();
          console.log(fileName);
          // Update the custom file label with the selected file name
          $(this).next('.custom-file-label').html(fileName);
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

    function getNextSlide(slideid) {
          var draft_id = $("#next_draft_id").val();
          var scheme_id = $("#next_scheme_id").val();
        if(slideid == 1) {
            var next_dept_id = $("#next_dept_id").val();
            var the_convener = $("#con_id").val();
            var convener_designation = $('#convener_designation').val();
            var convener_phone =  $('#convener_phone').val();
            var form_scheme_name = $("#form_scheme_name").val();
            var next_reference_year = $('#next_reference_year').val();
            var next_reference_year2 = $('#next_reference_year2').val();
            var financial_adviser_name = $("#financial_adviser_name").val();
            var financial_adviser_designation = $("#financial_adviser_designation").val();
            var financial_adviser_phone = $('#financial_adviser_phone').val();
            if(next_dept_id != '' && the_convener != '' && form_scheme_name != '' && next_reference_year != '' && financial_adviser_name != "" && financial_adviser_designation != "" && financial_adviser_phone != '') {
              countIncrease(slideid);
                $("#the_error_html").remove();
                $.ajax({
                    type:'post',
                    dataType:'json',
                    url:"{{ route('schemes.update_scheme') }}",
                    data:{'_token':"{{ csrf_token() }}",'slide':'first','convener_designation':convener_designation,'convener_phone':convener_phone,'financial_adviser_name':financial_adviser_name,'financial_adviser_designation':financial_adviser_designation,'financial_adviser_phone' : financial_adviser_phone,'draft_id':draft_id,'scheme_id':scheme_id,'dept_id':next_dept_id,'convener_name':the_convener,'scheme_name':form_scheme_name,'reference_year':next_reference_year,'reference_year2':next_reference_year2},
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
            if(next_major_objective != '' && count_next_major_objectives >= 2 && count_values_of_major_objectives >= 2) {
              countIncrease(slideid);
                $("#the_error_html").remove();
                if(count_next_major_objectives < 2) {
                    alert('There should be atleast 2 Major Objectives');
                }
                var major_objs = [];
                var j = 0;
                for(var i=0;i<count_next_major_objectives;i++) {
                    if($('.room_fields_'+i+' .next_major_objectives').val() != '') {
                        major_objs[j] = {"major_objective":$('.room_fields_'+i+' .next_major_objectives').val()};
                        j++;
                    }
                }

                $.ajax({
                    type:'post',
                    dataType:'json',
                    url:"{{ route('schemes.update_scheme') }}",
                    data:{'_token':"{{ csrf_token() }}",'slide':'second','major_objective':major_objs,'draft_id':draft_id,'scheme_id':scheme_id},
                    success:function(response) {
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
              countIncrease(slideid);
                $("#the_error_html").remove();
                if(count_values_of_major_indicators < 2) {
                    alert('There should be atleast 2 Major Indicators');
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
                    url:"{{ route('schemes.update_scheme') }}",
                    data:{'_token':"{{ csrf_token() }}",'slide':'third','major_indicator':major_indis,'draft_id':draft_id,'scheme_id':scheme_id},
                    success:function(response) {
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
            var implementing_office_contact = $("#implementing_office_contact").val();
            var both_ration = $("#both_ration").val();
          //  var both_ratio_type = $('.both_ratio_type').val();
            var nodal_id = $('#nodal_id').val();
            var nodal_officer_designation = $("#nodal_designation").val();
            var implementing_office_contact_type = $('.implementing_office_contact_type').val();
            var state_ratio = $("#state_ratio").val();
            var center_ratio = $("#center_ratio").val();
            var hod_name = $("#hod_name").val();
            var next_scheme_overview = $("#next_scheme_overview").val();
            var existing_next_scheme_overview_file = $(".existing_next_scheme_overview_file").val();
            // var fileInput = $("#next_scheme_overview_file")[0].files[0];
            var next_scheme_objective = $("#next_scheme_objective").val();
            var next_scheme_components = $('#next_scheme_components').val();

            var FileType = $('#scheme_objective_file')[0].files[0];
            var existing_scheme_objective_file = $(".existing_scheme_objective_file").val();
            var Filecomponent = $('#next_scheme_components_file')[0].files[0];
            var existing_next_scheme_components_file = $(".existing_next_scheme_components_file").val();

            if(Number(state_ratio) + Number(center_ratio) != '100') {
                $("#the_error_html").remove();
                var the_html = '<div class="row" id="the_error_html"><div class="col-xl-12" style="color:red;font-size:20px">* Fund Flow is required </div></div>';
                $(".fourth_slide").append(the_html);
                return false;
            }
            if(implementing_office != '' && nodal_id != '' && nodal_officer_designation != ''  && state_ratio != '' && center_ratio != '' && next_scheme_overview != '' && next_scheme_objective != '' && next_scheme_components != '') {
              countIncrease(slideid);  
              $("#the_error_html").remove();
              var formData = new FormData();
              // Append token and other data
              formData.append('_token', "{{ csrf_token() }}");
              formData.append('slide', 'fourth');
              formData.append('implementing_office_contact', implementing_office_contact);
              formData.append('implementing_office_contact_type', implementing_office_contact_type);
              formData.append('both_ration', both_ration);
              formData.append('implementing_office', implementing_office);
              formData.append('nodal_officer_name', nodal_id);
              formData.append('nodal_officer_designation', nodal_officer_designation);
              formData.append('state_ratio', state_ratio);
              formData.append('center_ratio', center_ratio);
              formData.append('hod_name', hod_name);
              formData.append('scheme_overview', next_scheme_overview);
              formData.append('scheme_objective', next_scheme_objective);
              formData.append('sub_scheme', next_scheme_components);
              formData.append('draft_id', draft_id);
              formData.append('scheme_id', scheme_id);
              formData.append('existing_next_scheme_overview_file', existing_next_scheme_overview_file);
              formData.append('existing_scheme_objective_file', existing_scheme_objective_file);
              formData.append('existing_next_scheme_components_file', existing_next_scheme_components_file);
              
              // Append file input
              var fileInput = $("#next_scheme_overview_file")[0].files[0];
              if (fileInput) {
                  formData.append('next_scheme_overview_file', fileInput);
              }
              if(FileType){
                formData.append('scheme_objective_file', FileType);
              }
              if(Filecomponent){
                formData.append('next_scheme_components_file', Filecomponent);
              }
                $.ajax({
                    type:'post',
                    dataType:'json',
                    url:"{{ route('schemes.update_scheme') }}",
                    data:formData,
                    contentType: false, // Prevent jQuery from setting the content type
                    processData: false,
                    success:function(response) {
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
              countIncrease(slideid);
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
                    url:"{{ route('schemes.update_scheme') }}",
                    data:{'_token':"{{ csrf_token() }}",'slide':'fifth','draft_id':draft_id,'scheme_id':scheme_id,'commencement_year':commencement_year,'scheme_status':scheme_status,'is_sdg':checked_scheme_status},
                    success:function(response) {
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

          var beneficiaries = [];
          jQuery('.next_beneficiary_selection_criterias').each(function() {
            var currentElement = $(this);

            var value = currentElement.val();
            beneficiaries.push(value);
         });
        
      
            // var next_beneficiary_selection_criteria = $.trim($('#next_beneficiary_selection_criteria_0').val());
            
             var beneficiaryFile = $('#beneficiary_selection_criteria_file')[0].files[0];
             var beneficiary_selection_criteria_length = beneficiaries.length;
             var existing_beneficiary_selection_criteria_file = $(".existing_beneficiary_selection_criteria_file").val();
             var count_values_of_next_beneficiary_selection_criterias = 0;
            for(var i=0;i<beneficiary_selection_criteria_length;i++) {
                if($(".next_beneficiary_selection_criterias").eq(i).val() != '') {
                    count_values_of_next_beneficiary_selection_criterias = i+1;
                }
            }
            if(beneficiary_selection_criteria_length >= 2 && count_values_of_next_beneficiary_selection_criterias >= 2) {
              countIncrease(slideid);
                $("#the_error_html").remove();
               
                if(beneficiary_selection_criteria_length < 2 || count_values_of_next_beneficiary_selection_criterias < 2) {
                    alert('There should be 2 major benefits');
                }

                // var beneficiaries = [];
                // for(var i=0;i<beneficiary_selection_criteria_length;i++) {
                //     beneficiaries[i] = {'beneficiary_selection_criteria':$("#beneficiary_selection_div_"+i+" .next_beneficiary_selection_criterias").val()};
                //   //  j++;
                // }
               
                var formData = new FormData();
                // Append token and other data
                formData.append('_token', "{{ csrf_token() }}");
                formData.append('slide', 'sixth');
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
            if(major_benefit_textarea != '' && major_benefit_length >= 2 && count_values_of_major_benefit_textareas >= 2) {
              countIncrease(slideid);
                $("#the_error_html").remove();

                if(major_benefit_length < 2 || count_values_of_major_benefit_textareas < 2) {
                    alert('There should be 2 major benefits');
                }

                $("#btn_seventh_slide_submit").click();
                return false;
                var major_length = $(".major_benefit_textareas").length;
                var major_text = [];
                for(var i=0;i<major_length;i++) {
                    major_text[i] = {'major_benefits_text':$("#major_benefits_div_"+i+" .major_benefit_textareas").val()};
                }
                $.ajax({
                    type:'post',
                    dataType:'json',
                    url:"{{ route('schemes.update_scheme') }}",
                    data:{'_token':"{{ csrf_token() }}",'slide':'seventh','major_benefits_text':major_text,'draft_id':draft_id,'scheme_id':scheme_id},
                    success:function(response) {
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
            var states = [];
            if(thedistrictlist > 0) {
              if(beneficiariesGeoLocal == 1) { //state
                    var i = 0;
                    $("input[name='state_name[]']:checked").each(function() {
                        var ss_state = this.value;
                        states[i] = ss_state.replace(/"/g,'');
                        i++;
                    });
                }else if(beneficiariesGeoLocal == 7) { //Developing Taluka
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
            var next_otherbeneficiariesGeoLocal = $("#next_otherbeneficiariesGeoLocal").val();
            var geographical_coverage = $("#geographical_coverage")[0].files.length;
            if(next_scheme_implementing_procedure != '' && beneficiariesGeoLocal != '') {
              countIncrease(slideid);
                $("#the_error_html").remove();

                $("#btn_eighth_slide_submit").click();
                return false;

                $.ajax({
                    type:'post',
                    dataType:'json',
                    url:"{{ route('schemes.update_scheme') }}",
                    data:{'_token':"{{ csrf_token() }}", 'slide':'eighth', 
                    'scheme_implementing_procedure':next_scheme_implementing_procedure, 
                    'beneficiariesGeoLocal':beneficiariesGeoLocal, 
                    'states':states, 
                    'districts':districts,
                    'talukas':talukas, 
                    'otherbeneficiariesGeoLocal':next_otherbeneficiariesGeoLocal,'draft_id':draft_id,'scheme_id':scheme_id
                  },
                    success:function(response) {
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
              countIncrease(slideid);  
              $("#the_error_html").remove();

                $("#btn_nineth_slide_submit").click();
                return false;

                $.ajax({
                    type:'post',
                    dataType:'json',
                    url:"{{ route('schemes.update_scheme') }}",
                    data:{'_token':"{{ csrf_token() }}", 'slide':'nineth','draft_id':draft_id,'scheme_id':scheme_id, 'coverage_beneficiaries_remarks':next_coverage_beneficiaries_remarks, 'training_capacity_remarks':next_training_capacity_remarks, 'iec_activities_remarks':next_iec_activities_remarks},
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
              countIncrease(slideid);
              $("#the_error_html").remove();

                $.ajax({
                    type:'post',
                    dataType:'json',
                    url:"{{ route('schemes.update_scheme') }}",
                    data:{'_token':"{{ csrf_token() }}", 'slide':'tenth','draft_id':draft_id,'scheme_id':scheme_id, 'benefit_to':next_benefit_to,'all_convergence':all_convergence},
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
            var existing_gr_files = {{ $gr_files->count() ?? 0}};
            var existing_notification_files = {{ $notifications->count() ?? 0}};

            var next_gr_files = $(".next_gr_files")[0].files.length;
            var next_notification_files = $('.next_notification_files')[0].files.length;
            var next_brochure_files = $(".next_brochure_files")[0].files.length;
            var next_pamphlets_files = $('.next_pamphlets_files')[0].files.length;
            var next_otherdetailscenterstate = $(".next_otherdetailscenterstate")[0].files.length;
            if ((next_gr_files > 0 || next_notification_files > 0) || (existing_gr_files > 0 && next_gr_files === 0) || (existing_notification_files > 0 && next_notification_files === 0)) {
              countIncrease(slideid);
                $("#the_error_html").remove();
                $("#btn_eleventh_slide_submit").click();
                $(".otherslides").hide();
                $(".twelth_slide").show();
                $("#previous_btn").val(12).show();
                $("#next_btn").val(12).show();
            } else {
                $("#the_error_html").remove();
                var the_html = '<div class="row" id="the_error_html"><div class="col-xl-12" style="color:red;font-size:20px">* GR or Notification documents are required</div></div>';
                $(".eleventh_slide").append(the_html);
            }

            // var next_gr_files = $(".next_gr_files")[0].files.length;
            // var next_notification_files = $('.next_notification_files')[0].files.length;
            // var next_brochure_files = $(".next_brochure_files")[0].files.length;
            // var next_pamphlets_files = $('.next_pamphlets_files')[0].files.length;
            // var next_otherdetailscenterstate = $(".next_otherdetailscenterstate")[0].files.length;
            // if(next_gr_files > 0 || next_notification_files > 0) {
            //     $("#the_error_html").remove();

                // $("#btn_eleventh_slide_submit").click();

                // $(".otherslides").hide();
                // $(".twelth_slide").show();
                // $("#previous_btn").val(12).show();
                // $("#next_btn").val(12).show();
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
              countIncrease(slideid);
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

                if(count_values_of_indicators < 2) {
                    alert('There should be atleast 2 Major Monitoring Indicators');
                }


                $.ajax({
                    type:'post',
                    dataType:'json',
                    url:"{{ route('schemes.update_scheme') }}",
                    data:{'_token':"{{ csrf_token() }}", 'slide':'twelth','draft_id':draft_id,'scheme_id':scheme_id, 'major_indicator_hod':indicator_values},
                    success:function(response) {
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
          //  var next_financial_progress_units = $(".next_financial_progress_units").val();
            var next_financial_progress_target = $(".next_financial_progress_target").val();
            var next_financial_progress_achivement = $(".next_financial_progress_achivement").val();
            var next_financial_progress_allocation = $(".next_financial_progress_allocation").val();
            var next_financial_progress_expenditure = $(".next_financial_progress_expenditure").val();

            var next_financial_progress_selection = $(".next_financial_progress_selection").val();
            var next_financial_progress_item  = $(".next_financial_progress_item").val();

            var count_tr = $("#thisistbody tr").length;
            if(next_financial_progress_item  != '' && next_financial_progress_selection != "" && next_financial_progress_year != ''  && next_financial_progress_target != '' && next_financial_progress_achivement != '' && next_financial_progress_allocation != '' && next_financial_progress_expenditure != '') {
              countIncrease(slideid);
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
                    var the_items = $(".next_fin_item_"+i).val();

                    if(the_selection != '' && the_items != '' && the_year != '' && the_target != '' && the_achievement != '' && the_allocation != '' && the_expenditure != '') {
                        tr_array[i] = {'financial_year':$(".next_fin_year_"+i).val(), 'target':$(".next_fin_target_"+i).val(), 'achievement':$(".next_fin_achivement_"+i).val(), 'allocation':$(".next_fin_allocation_"+i).val(), 'expenditure':$(".next_fin_expenditure_"+i).val(), 'selection': $(".next_fin_selection_"+i).val(),'items': $(".next_fin_item_"+i).val()};
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
                            data:{'_token':"{{ csrf_token() }}", 'slide':'thirteenth','draft_id':draft_id,'scheme_id':scheme_id,'tr_array':tr_array, 'financial_progress_remarks':financial_progress_remarks},
                            success:function(response) {
                                $(".otherslides").hide();
                                $(".fourteenth_slide").show();
                                $("#previous_btn").val(14).show();
                                $("#next_btn").val(14).show();
                                $("#next_btn").hide();
                                $('.last_btn').show();

                                var isevaluation = $("input[name='is_evaluation']:checked").attr('id');
                                $("#"+isevaluation).click();

                                var the_html_btn = '<button type="button" class="btn btn-success font-weight-bold text-uppercase last_btn" data-wizard-type="action-next" value="1" onclick="finishSlides(13)" id="next_btn"> Finish </button>';
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
          $("#next_btn").hide();
        }
    }

    function getPrevSlide(prevslide) {
      countPrevious(prevslide);
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

            var is_evaluation = $("#fourteenth_slide_form input[name='is_evaluation']:checked").val();
            var eval_by_whom = $("#eval_by_whom").val();
            var eval_when = $("#eval_when").val();
            var eval_geographical_coverage_beneficiaries = $("#eval_geographical_coverage_beneficiaries").val();
            var eval_number_of_beneficiaries = $("#eval_number_of_beneficiaries").val();
            var eval_major_recommendation = $("#eval_major_recommendation").val();
            
            var eval_if_yes_upload_file = $("#eval_if_yes_upload_file")[0].files;

            if(is_evaluation == 'N') {
                $("#btn_fourteenth_slide_submit").click();
            } else if(is_evaluation == 'Y') {
                if(eval_by_whom != '' && eval_when != '' && eval_geographical_coverage_beneficiaries != '' && eval_number_of_beneficiaries != '' && eval_major_recommendation != '') {
                    $("#btn_fourteenth_slide_submit").click();
                } else {
                    $("#the_error_html").remove();
                    var the_html = '<div class="row" id="the_error_html"><div class="col-xl-12" style="color:red;font-size:20px">* All Fields are required</div></div>';
                    $(".fourteenth_slide").append(the_html);
                }
            } else {
                $("#the_error_html").remove();
                var the_html = '<div class="row" id="the_error_html"><div class="col-xl-12" style="color:red;font-size:20px">* All Fields are required</div></div>';
                $(".fourteenth_slide").append(the_html);
            }
        }
    }

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
                url:"{{ route('schemes.update_scheme') }}",
                data:formData,
                contentType:false,
                processData:false,
                success:function(response) {
                    $(".otherslides").hide();
                    $(".eighth_slide").show();
                    $("#previous_btn").val(8).show();
                    $("#next_btn").val(8).show();
                },
                error:function() {
                    console.log('add_scheme ajax error');
                }
            });
        });

        $("#eighth_slide_form").submit(function(e){
            e.preventDefault();
            let formDataEighth = new FormData(this);
            $.ajax({
                type:'post',
                dataType:'json',
                url:"{{ route('schemes.update_scheme') }}",
                data:formDataEighth,
                contentType:false,
                processData:false,
                success:function(response) {
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
                url:"{{ route('schemes.update_scheme') }}",
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
                url:"{{ route('schemes.update_scheme') }}",
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
                url:"{{ route('schemes.update_scheme') }}",
                data:formDataFourteenth,
                contentType:false,
                processData:false,
                success:function(response) {
                   var get_url = "{{ route('proposals', ['param' => 'new']) }}";
                    window.location.href = get_url;
                },
                error:function() {
                    console.log('add_scheme ajax error');
                }
            });
        });

    });
    // }
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
});


</script>


