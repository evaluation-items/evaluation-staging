@extends(Auth::user()->role_manage == 1  ? 'dashboards.gad-sec.layouts.gadsec-dash-layout'  : (Auth::user()->role_manage == 2  ? 'dashboards.eva-dir.layouts.evaldir-dash-layout'  : (Auth::user()->role_manage == 3 || Auth::user()->role_manage == 4   ? 'dashboards.eva-dd.layouts.evaldd-dash-layout' : 'dashboards.proposal.layouts.sidebar')))
@section('title','Proposals - Detail')
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
          Proposal Detail
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
        {{-- <div class="d-flex justify-content-end mb-3">
            <a
                href="{{ route('proposal.final-report.pdf', Crypt::encrypt($proposal_list[0]->draft_id)) }}"
                class="btn btn-danger"
                target="_blank"
            >
                <i class="fas fa-file-pdf"></i>
                Download Final Report (PDF)
            </a>
        </div> --}}

          <div class="wizard wizard-4" id="kt_wizard_v4" data-wizard-state="step-first" data-wizard-clickable="true">
            <div class="card card-custom card-shadowless rounded-top-0">
              <div class="card-body p-10">
                <div class="row table-responsive">
                  <table width="100%" class="table table-bordered table-hover table-stripped">
                    <tr>
                      <th width="30%">Name of the Department (વિભાગનું નામ)</th>
                      <td width="70%">{{ $dept_name }}</td>
                    </tr>
                    @foreach($proposal_list as $pkey=>$pval)
                      <tr>
                        <th>Whether evaluation of this scheme already done in past? (આ યોજનાનું મૂલ્યાંકન અગાઉ થઈ ચૂકેલ છે?)</th>
                        <td>{{($pval->is_evaluation == 'Y' ? 'Yes' : 'No')}}</td>
                      </tr>
                      @if($pval->is_evaluation == 'Y')
                      <tr>
                        <th>By Whom? (કોના દ્વારા?)</th>
                        <td>{{$pval->eval_scheme_bywhom}}</td>
                      </tr>
                      <tr>
                        <th>When? (ક્યારે?)</th>
                        <td>{{$pval->eval_scheme_when}}</td>
                      </tr>
                      <tr>
                        <th>Geographical coverage of Beneficiaries (સમાવિષ્ટ કરેલ લાભાર્થીઓનો ભૌગોલિક વિસ્તાર)</th>
                        <td>{{$pval->eval_geographical_coverage_beneficiaries}}</td>
                      </tr>
                      <tr>
                        <th>No. of beneficiaries in sample (નિદર્શમાં સમાવિષ્ટ લાભાર્થીઓની સંખ્યા)</th>
                        <td>{{$pval->eval_scheme_major_recommendation}}</td>
                      </tr>
                      <tr>
                        <th>Upload report (અહેવાલ અપલોડ કરવો.)</th>
                        <td> 
                          @if($pval->eval_upload_report == '')
                            No File
                          @else
                            @php  
                              $extension = pathinfo($pval->eval_upload_report, PATHINFO_EXTENSION);
                            @endphp
                            @if($extension == 'pdf')
                                <a href="{{ route('schemes.get_the_file', [Crypt::encrypt($pval->scheme_id), $pval->eval_upload_report]) }}" target="_blank" title="{{ $pval->eval_upload_report }}"><i class="fas fa-file-pdf fa-2x" style="color:red;"></i></a>
                              @elseif($extension == 'doc')
                              <a href="{{ route('schemes.get_the_file', [Crypt::encrypt($pval->scheme_id), $pval->eval_upload_report]) }}" download="{{ $pval->eval_upload_report }}"><i class="fas fa-download fa-2x" style="color:#007bff;"></i></a>
                                @else
                                <a href="{{ route('schemes.get_the_file', [Crypt::encrypt($pval->scheme_id), $pval->eval_upload_report]) }}" download="{{ $pval->eval_upload_report }}"><i class="fas fa-download fa-2x" style="color:green;"></i></a>
                            @endif
                          @endif
                        </td>
                      </tr>
                      @endif
                      <tr>
                        <th>Name of the Nodal Officer (નોડલ અધિકારીનું નામ)</th>
                        <td>
                            {{ $pval->convener_name }}
                        </td>
                      </tr>
                    
                      <tr>
                        <th>Designation of the Nodal Officer (નોડલ અધિકારી નો હોદ્દો)</th>
                        @php
                        if($pval->convener_designation == 'ds'){
                          $name = 'Deputy Secretary';
                        }elseif($pval->convener_designation == 'js'){
                          $name = 'Joint Secretary';
                        }elseif($pval->convener_designation == 'as'){
                          $name = 'Additional Secretary';   
                        }else{
                          $name = '';
                        }                  
                        @endphp
                        <td>
                          {{ $name }}
                        </td>
                      </tr>
                      <tr>
                        <th>Contact Number of the Nodal Officer (નોડલ અધિકારીના સંપર્ક નંબર)</th>
                        <td>
                          {{ $pval->convener_phone }}
                        </td>
                      </tr>
                      <tr>
                        <th>Mobile Number of the Nodal Officer (નોડલ અધિકારીના મોબાઇલ નંબર)</th>
                        <td>
                          {{ $pval->convener_mobile }}
                        </td>
                      </tr>
                      <tr>
                        <th>Email Address of the Nodal Officer (નોડલ અધિકારીનું ઇમેઇલ સરનામું)</th>
                        <td>
                          {{ $pval->convener_email }}
                        </td>
                      </tr>
                      <tr>
                        <th>Name of the scheme/ Programme to be evaluated (કરવાના થતા મૂલ્યાંકન અભ્યાસ માટેના યોજના/કાર્યક્રમનું નામ)</th>
                        <td>
                          {{ $pval->scheme_name }}
                        </td>
                      </tr>
                      <tr>
                        <th>Short Name of the scheme/ Programme to be evaluated (મૂલ્યાંકન કરવાની યોજના/કાર્યક્રમનું ટૂંકું નામ):</th>
                        <td>
                          {{ $pval->scheme_short_name }}
                        </td>
                      </tr>
                      <tr>
                        <th>Name of the Financial Adviser (નાણાકીય સલાહકાર નું નામ):</th>
                        <td>
                          {{ $pval->financial_adviser_name }}
                        </td>
                      </tr>
                      <tr>
                        <th>Designation of the Financial Adviser (નાણાકીય સલાહકાર નો હોદ્દો)</th>
                        <td>
                          {{ $pval->financial_adviser_designation }}
                        </td>
                      </tr>
                      <tr>
                        <th>Contact Number of the Financial Adviser (નાણાકીય સલાહકાર  ના સંપર્ક નંબર)</th>
                        <td>
                          {{ $pval->financial_adviser_phone }}
                        </td>
                      </tr>
                      <tr>
                        <th>Mobile Number of the Financial Adviser (નાણાકીય સલાહકાર ના મોબાઇલ નંબર) </th>
                        <td>
                          {{ $pval->financial_adviser_mobile }}
                        </td>
                      </tr>
                      <tr>
                        <th>Email Address of the Financial Adviser (નાણાકીય સલાહકારનું ઇમેઇલ સરનામું) </th>
                        <td>
                          {{ $pval->financial_adviser_email }}
                        </td>
                      </tr>
                      <tr>
                      <th>The Reference year for which the Evaluation study to be done (મૂલ્યાંકન અભ્યાસ માટેનું સંદર્ભ વર્ષ)</th>
                        <td>From: {{ $pval->reference_year }} <br> To: {{$pval->reference_year2}}</td>  
                      </tr>
                      <tr>
                      <th>Major Objective of the Evaluation study (મૂલ્યાંકન અભ્યાસના મુખ્ય હેતુઓ)</th>
                      <td>
                          {{ $pval->major_objective }}
                          {!! '<br>' !!}
                      </td>
                    </tr>
                     <tr>
                        <th>Major Objective of the Evaluation study (અહેવાલ અપલોડ કરવો.)</th>
                        <td> 
                          @if($pval->major_objective_file == '')
                            No File
                          @else
                            @php  
                              $extension = pathinfo($pval->major_objective_file, PATHINFO_EXTENSION);
                            @endphp
                            @if($extension == 'pdf')
                                <a href="{{ route('schemes.get_the_file', [Crypt::encrypt($pval->scheme_id), $pval->major_objective_file]) }}" target="_blank" title="{{ $pval->major_objective_file }}"><i class="fas fa-file-pdf fa-2x" style="color:red;"></i></a>
                              @elseif($extension == 'doc')
                              <a href="{{ route('schemes.get_the_file', [Crypt::encrypt($pval->scheme_id), $pval->major_objective_file]) }}" download="{{ $pval->major_objective_file }}"><i class="fas fa-download fa-2x" style="color:#007bff;"></i></a>
                                @else
                                <a href="{{ route('schemes.get_the_file', [Crypt::encrypt($pval->scheme_id), $pval->major_objective_file]) }}" download="{{ $pval->major_objective_file }}"><i class="fas fa-download fa-2x" style="color:green;"></i></a>
                            @endif
                          @endif  
                        </td>
                      </tr>
                      <tr>
                        <th>Major Monitoring Indicators for scheme to be evaluated (મૂલ્યાંકન હાથ ધરવાની થતી યોજનાની સમીક્ષાના મુખ્ય માપદંડૉ)</th>
                        <td>
                            {{ $pval->major_indicator }}
                            {!! '<br>' !!}
                        </td>
                      </tr>
                      <tr>
                        <th>Major Monitoring Indicators for scheme to be evaluated File (મૂલ્યાંકન હાથ ધરવાની થતી યોજનાની સમીક્ષાના મુખ્ય માપદંડૉ અહેવાલ.)</th>
                        <td> 
                          @if($pval->major_indicator_file == '')
                            No File
                          @else
                            @php  
                              $extension = pathinfo($pval->major_indicator_file, PATHINFO_EXTENSION);
                            @endphp
                            @if($extension == 'pdf')
                                <a href="{{ route('schemes.get_the_file', [Crypt::encrypt($pval->scheme_id), $pval->major_indicator_file]) }}" target="_blank" title="{{ $pval->major_indicator_file }}"><i class="fas fa-file-pdf fa-2x" style="color:red;"></i></a>
                              @elseif($extension == 'doc')
                              <a href="{{ route('schemes.get_the_file', [Crypt::encrypt($pval->scheme_id), $pval->major_indicator_file]) }}" download="{{ $pval->major_indicator_file }}"><i class="fas fa-download fa-2x" style="color:#007bff;"></i></a>
                                @else
                                <a href="{{ route('schemes.get_the_file', [Crypt::encrypt($pval->scheme_id), $pval->major_indicator_file]) }}" download="{{ $pval->major_indicator_file }}"><i class="fas fa-download fa-2x" style="color:green;"></i></a>
                            @endif
                          @endif
                        </td>
                      </tr>
                      @php
                        $names    = array_map('trim', explode(',', $pval->hod_officer_name ?? ''));
                        $emails   = array_map('trim', explode(',', $pval->hod_email ?? ''));
                        $contacts = array_map('trim', explode(',', $pval->implementing_office_contact ?? ''));
                        $hod_mobile = array_map('trim', explode(',', $pval->hod_mobile ?? ''));
                      @endphp
                      <tr>
                          <th>Name of the HOD / Branch (કચેરીનું નામ)</th>
                          <td>{{ hod_name($pval->draft_id) }}</td>
                      </tr>

                      @foreach ($names as $i => $name)
                      <tr>
                          <th>Name</th>
                          <td>{{ $name ?: '-' }}</td>
                      </tr>
                      <tr>
                          <th>Email</th>
                          <td>{{ $emails[$i] ?? '-' }}</td>
                      </tr>
                      <tr>
                          <th>Contact No</th>
                          <td>{{ $contacts[$i] ?? '-' }}</td>
                      </tr>
                      <tr>
                          <th>Mobile No</th>
                          <td>{{ $hod_mobile[$i] ?? '-' }}</td>
                      </tr>
                     
                      @endforeach
                      <tr>
                        <th>Name of the Nodal Officer (HOD) (નોડલ અધિકારી નું નામ)</th>
                        <td>
                          {{ $pval->nodal_officer_name }}
                        </td>
                      </tr>
                      <tr>
                        <th>Designation of Nodal Officer(HOD) (નોડલ અધિકારી નો હોદ્દો)</th>
                        <td>
                          {{ $pval->nodal_officer_designation }}
                        </td>
                      </tr>
                      <tr>
                        <th>Contact Number of the Nodal Officer (HOD) (નોડલ અધિકારી ના સંપર્ક નંબર)</th>
                        <td>
                          {{ $pval->nodal_officer_contact }}
                        </td>
                      </tr>
                      <tr>
                        <th>Mobile Number of the Nodal Officer (HOD) (નોડલ અધિકારી ના મોબાઇલ નંબર)</th>
                        <td>
                          {{ $pval->nodal_officer_mobile }}
                        </td>
                      </tr>
                      <tr>
                        <th>Email of Nodal Officer(HOD) (નોડલ અધિકારી નું ઇમેઇલ)</th>
                        <td>
                          {{ $pval->nodal_officer_email }}
                        </td>
                      </tr>
                      <tr>
                        <th>Fund Flow Central Govt. (યોજના માટેનો નાણાકીય સ્ત્રોત્ર કેદ્ર: __%)</th>
                        <td>
                          {{ $pval->center_ratio }} %
                        </td>
                      </tr>
                      <tr>
                        <th>Fund Flow State Govt. (યોજના માટેનો નાણાકીય સ્ત્રોત્ર રાજ્ય: __%)</th>
                        <td>
                          {{ $pval->state_ratio }} %
                        </td>
                      </tr>
                      <tr>
                        <th>Overview of the scheme/Background of the scheme (યોજનાની પ્રાથમિક માહિતી/યોજનાનો પરિચય)</th>
                        <td>
                          {{ $pval->scheme_overview }}
                        </td>
                      </tr>
                      <tr>
                        <th>Overview of the scheme/Background of the scheme File (યોજનાની પ્રાથમિક માહિતી/યોજનાનો પરિચય અહેવાલ.)</th>
                        <td> 
                          @if($pval->next_scheme_overview_file == '')
                            No File
                          @else
                            @php  
                              $extension = pathinfo($pval->next_scheme_overview_file, PATHINFO_EXTENSION);
                            @endphp
                            @if($extension == 'pdf')
                                <a href="{{ route('schemes.get_the_file', [Crypt::encrypt($pval->scheme_id), $pval->next_scheme_overview_file]) }}" target="_blank" title="{{ $pval->next_scheme_overview_file }}"><i class="fas fa-file-pdf fa-2x" style="color:red;"></i></a>
                              @elseif($extension == 'doc')
                              <a href="{{ route('schemes.get_the_file', [Crypt::encrypt($pval->scheme_id), $pval->next_scheme_overview_file]) }}" download="{{ $pval->next_scheme_overview_file }}"><i class="fas fa-download fa-2x" style="color:#007bff;"></i></a>
                                @else
                                <a href="{{ route('schemes.get_the_file', [Crypt::encrypt($pval->scheme_id), $pval->next_scheme_overview_file]) }}" download="{{ $pval->next_scheme_overview_file }}"><i class="fas fa-download fa-2x" style="color:green;"></i></a>
                            @endif
                          @endif
                        </td>
                      </tr>
                    <tr>
                      <th>Objectives of the scheme (યોજનાના હેતુઓ)</th>
                      <td>
                        {{ $pval->scheme_objective }}
                      </td>
                    </tr>
                    <tr>
                      <th>Objectives of the scheme File (યોજનાના હેતુઓ અહેવાલ.)</th>
                      <td>
                         @if($pval->scheme_objective_file == '')
                            No File
                          @else
                            <x-file-link
                              :file="$pval->scheme_objective_file"
                              :scheme-id="$pval->scheme_id"
                          />

                         @endif
                      </td>
                    </tr>
                    <tr>
                      <th>Name of Sub-schemes/components (પેટા યોજનાનું નામ અને ઘટકો)</th>
                      <td>
                        {{ $pval->sub_scheme }}
                      </td>
                    </tr>
                    <tr>
                      <th>Name of Sub-schemes/components File (પેટા યોજનાનું નામ અને ઘટકો અહેવાલ.)</th>
                      <td>
                         @if($pval->next_scheme_components_file == '')
                            No File
                          @else
                            @php  
                              $extension = pathinfo($pval->next_scheme_components_file, PATHINFO_EXTENSION);
                            @endphp
                            @if($extension == 'pdf')
                                <a href="{{ route('schemes.get_the_file', [Crypt::encrypt($pval->scheme_id), $pval->next_scheme_components_file]) }}" target="_blank" title="{{ $pval->next_scheme_components_file }}"><i class="fas fa-file-pdf fa-2x" style="color:red;"></i></a>
                              @elseif($extension == 'doc')
                              <a href="{{ route('schemes.get_the_file', [Crypt::encrypt($pval->scheme_id), $pval->next_scheme_components_file]) }}" download="{{ $pval->next_scheme_components_file }}"><i class="fas fa-download fa-2x" style="color:#007bff;"></i></a>
                                @else
                                <a href="{{ route('schemes.get_the_file', [Crypt::encrypt($pval->scheme_id), $pval->next_scheme_components_file]) }}" download="{{ $pval->next_scheme_components_file }}"><i class="fas fa-download fa-2x" style="color:green;"></i></a>
                            @endif
                           @endif
                      </td>
                    </tr>
                    <tr>
                      <th>Year of actual commencement of the scheme (યોજનાનું ખરેખર અમલીકરણ શરૂ કર્યા વર્ષ)</th>
                      <td>
                        {{ $pval->commencement_year }}
                      </td>
                    </tr>
                     <tr>
                      <th>Present status with coverage of scheme (યોજનાના અમલની વર્તમાન સ્થિતિ)</th>
                      <td>
                        {{ $pval->scheme_status }}
                      </td>
                    </tr>
                      <tr>
                      <th>Sustainable Development Goals (SDG) (સસ્ટેનેબલ ડેવલપમેન્ટ ગોલ)</th>
                      <td>
                      @if($entered_goals != null)
                        @foreach($goals as $k => $g)
                          @if(in_array($g->goal_id,$entered_goals))
                            <p>{{ $g->goal_name }}</p>
                          @endif
                        @endforeach
                      @endif
                      </td>
                    </tr>
                    <tr>
                      <th>Beneficiary/Community selection Criteria (લાભાર્થી/સમુદાયની પાત્રતા માટેના માપદંડો)</th>
                      <td>
                          @if($pval->scheme_beneficiary_selection_criteria)
                          <p>{{ $pval->scheme_beneficiary_selection_criteria ?? '' }}</p>
                         @endif
                      </td>
                    </tr>
                    <tr>
                      <th>Beneficiary/Community selection Criteria File (લાભાર્થી/સમુદાયની પાત્રતા માટેના માપદંડો અહેવાલ.)</th>
                      <td>
                         @if($pval->beneficiary_selection_criteria_file == '')
                            No File
                          @else
                            @php  
                              $extension = pathinfo($pval->beneficiary_selection_criteria_file, PATHINFO_EXTENSION);
                            @endphp
                            @if($extension == 'pdf')
                                <a href="{{ route('schemes.get_the_file', [Crypt::encrypt($pval->scheme_id), $pval->beneficiary_selection_criteria_file]) }}" target="_blank" title="{{ $pval->beneficiary_selection_criteria_file }}"><i class="fas fa-file-pdf fa-2x" style="color:red;"></i></a>
                              @elseif($extension == 'doc')
                              <a href="{{ route('schemes.get_the_file', [Crypt::encrypt($pval->scheme_id), $pval->beneficiary_selection_criteria_file]) }}" download="{{ $pval->beneficiary_selection_criteria_file }}"><i class="fas fa-download fa-2x" style="color:#007bff;"></i></a>
                                @else
                                <a href="{{ route('schemes.get_the_file', [Crypt::encrypt($pval->scheme_id), $pval->beneficiary_selection_criteria_file]) }}" download="{{ $pval->beneficiary_selection_criteria_file }}"><i class="fas fa-download fa-2x" style="color:green;"></i></a>
                            @endif
                          @endif
                      </td>
                    </tr>
                    <tr>
                      <th>Expected Major Benefits Derived from the Scheme (યોજના ના અપેક્ષિત મુખ્ય લાભો) <br> Major Benefit</th>
                      <td>
                        {{ $pval->major_benefits_text }}
                      </td>
                    </tr>
                    <tr>
                      <th>Expected Major Benefits Derived from the Scheme File (યોજના ના અપેક્ષિત મુખ્ય લાભો અહેવાલ.) <br> Major Benefit File</th>
                      <td>
                         @if($pval->major_benefits == '')
                            No File
                          @else
                            @php  
                              $extension = pathinfo($pval->major_benefits, PATHINFO_EXTENSION);
                            @endphp
                            @if($extension == 'pdf')
                                <a href="{{ route('schemes.get_the_file', [Crypt::encrypt($pval->scheme_id), $pval->major_benefits]) }}" target="_blank" title="{{ $pval->major_benefits }}"><i class="fas fa-file-pdf fa-2x" style="color:red;"></i></a>
                              @elseif($extension == 'doc')
                              <a href="{{ route('schemes.get_the_file', [Crypt::encrypt($pval->scheme_id), $pval->major_benefits]) }}" download="{{ $pval->major_benefits }}"><i class="fas fa-download fa-2x" style="color:#007bff;"></i></a>
                                @else
                                <a href="{{ route('schemes.get_the_file', [Crypt::encrypt($pval->scheme_id), $pval->major_benefits]) }}" download="{{ $pval->major_benefits }}"><i class="fas fa-download fa-2x" style="color:green;"></i></a>
                            @endif
                          @endif
                      </td>
                    </tr>
                    <tr>
                      <th>Implementing procedure of the Scheme (યોજનાની અમલીકરણ માટેની પ્રક્રિયા.)</th>
                      <td>
                        {{ $pval->scheme_implementing_procedure }}
                      </td>
                    </tr>
                    <tr>
                      <th>Administrative set up for Implementation of the scheme (યોજનાના અમલીકરણ માટેનું વહીવટી માળખું) <br><small>Geographical Coverage: From State to beneficiaries (રાજ્યકક્ષાથી લઈ લાભાર્થી સુધીનો ભૌગોલિક વ્યાપ)</small></th>
                      <td>
                        @foreach($beneficiariesGeoLocal as $benkey => $benval)
                          @if($pval->beneficiariesGeoLocal == $benval->id) {{ $benval->name }} @endif
                        @endforeach
                         <hr>
                        {{-- Dynamic items will load here --}}
                        <div class="row districtList"></div>
                      </td>
                      
                    </tr>
                     <tr>
                      <th>Scheme coverage since inception of the scheme (યોજનાની શરૂઆતથી અત્યાર સુધીનો વ્યાપ) <br>Coverage of Beneficiary/Community (લાભાર્થી/સમુદાયનો સમાવેશ)</th>
                      <td>
                          {{$pval->coverage_beneficiaries_remarks}}
                      </td>
                    </tr>
                    <tr>
                      <th>Coverage of Beneficiary/Community (લાભાર્થી/સમુદાયનો સમાવેશ)</th>
                      <td>
                        @if($pval->beneficiaries_coverage == '')
                          No File
                        @else
                          @php  
                            $extension = pathinfo($pval->beneficiaries_coverage, PATHINFO_EXTENSION);
                          @endphp
                          @if($extension == 'pdf')
                              <a href="{{ route('schemes.get_the_file', [Crypt::encrypt($pval->scheme_id), $pval->beneficiaries_coverage]) }}" target="_blank" title="{{ $pval->beneficiaries_coverage }}"><i class="fas fa-file-pdf fa-2x" style="color:red;"></i></a>
                             @elseif($extension == 'doc')
                             <a href="{{ route('schemes.get_the_file', [Crypt::encrypt($pval->scheme_id), $pval->beneficiaries_coverage]) }}" download="{{ $pval->beneficiaries_coverage }}"><i class="fas fa-download fa-2x" style="color:#007bff;"></i></a>
                              @else
                              <a href="{{ route('schemes.get_the_file', [Crypt::encrypt($pval->scheme_id), $pval->beneficiaries_coverage]) }}" download="{{ $pval->beneficiaries_coverage }}"><i class="fas fa-download fa-2x" style="color:green;"></i></a>
                          @endif
                       
                        @endif
                      </td>
                    </tr> 
                    <tr>
                      <th>Training/Capacity building of facilitators (સંબંધિતોની તાલીમ/ક્ષમતા નિર્માણ માટેની કામગીરી)</th>
                      <td>
                          {{$pval->training_capacity_remarks}}
                      </td>
                    </tr>    
                    <tr>
                      <th>Training/Capacity building of facilitators (સંબંધિતોની તાલીમ/ક્ષમતા નિર્માણ માટેની કામગીરી)</th>
                      <td>
                        @if($trainingfile == 'no data')
                          No File
                        @else
                        @php
                          $extension = pathinfo($pval->training, PATHINFO_EXTENSION);
                        @endphp
                        @if($extension == 'pdf')
                            <a href="{{ route('schemes.get_the_file', [Crypt::encrypt($pval->scheme_id), $pval->training]) }}" target="_blank" title="{{ $pval->training }}"><i class="fas fa-file-pdf fa-2x" style="color:red;"></i></a>
                            @elseif ($extension == 'doc')
                            <a href="{{ route('schemes.get_the_file', [Crypt::encrypt($pval->scheme_id), $pval->training]) }}" download="{{ $pval->training }}"><i class="fas fa-download fa-2x" style="color:#007bff;"></i></a>

                            @else
                            <a href="{{ route('schemes.get_the_file', [Crypt::encrypt($pval->scheme_id), $pval->training]) }}" download="{{ $pval->training }}"><i class="fas fa-download fa-2x" style="color:green;"></i></a>
                        @endif
                        {{-- <a href="{{ $replace_url }}/get_the_file/{{ $$pval->scheme_id }}/_training" target="_blank">
                          <i class="fas fa-file-pdf fa-2x" style="color:red;"></i>
                        </a> --}}
                        @endif
                      </td>
                    </tr>
                    <tr>
                      <th>IEC activities (પ્રચાર પ્રસારની કામગીરી)</th>
                      <td>
                          {{$pval->iec_activities_remarks}}
                      </td>
                    </tr>
                    <tr>
                      <th>IEC activities (પ્રચાર પ્રસારની કામગીરી)</th>
                      <td>
                        @if($iecfile == 'no data')
                          No File
                        @else
                        @php
                          $extension = pathinfo($pval->iec, PATHINFO_EXTENSION);
                        @endphp
                        @if($extension == 'pdf')
                            <a href="{{ route('schemes.get_the_file', [Crypt::encrypt($pval->scheme_id), $pval->iec]) }}" target="_blank" title="{{ $pval->iec }}"><i class="fas fa-file-pdf fa-2x" style="color:red;"></i></a>
                            @elseif ($extension == 'doc')
                            <a href="{{ route('schemes.get_the_file', [Crypt::encrypt($pval->scheme_id), $pval->iec]) }}" download="{{ $pval->iec }}"><i class="fas fa-download fa-2x" style="color:#007bff;"></i></a>

                            @else
                            <a href="{{ route('schemes.get_the_file', [Crypt::encrypt($pval->scheme_id), $pval->iec]) }}" download="{{ $pval->iec }}"><i class="fas fa-download fa-2x" style="color:green;"></i></a>
                        @endif
                        {{-- <a href="{{ $replace_url }}/get_the_file/{{ $$pval->scheme_id }}/_iec" target="_blank">
                          <i class="fas fa-file-pdf fa-2x" style="color:red;"></i>
                        </a> --}}
                        @endif
                      </td>
                    </tr>
                    <tr>
                      <th>Asset/Service creation & its maintenance plan if any (યોજના દ્વારા ઊભી થયેલ સંપત્તિ/સેવા અને તેની જાળવણી, જો હોય તો)</th>
                      <td>
                          {{$pval->benefit_to}}
                      </td>
                    </tr>
                  @endforeach
                  </table>
                    <table width="100%" class="table table-bordered table-hover table-stripped" style="margin-top:-20px" id="convergence_table">
                     @foreach($proposal_list as $pkey=>$pval)
                          @if($pval->all_convergence)
                            @foreach($the_convergence as $kc => $vc)
                              <tr>
                                <th>Department Name and Remarks</th>
                                <td>
                                  <p>{{$vc['dept_name']}}</p>
                                  <p>{{$vc['remarks']}}</p>
                                </td>
                              </tr>
                            @endforeach
                          @endif
                    
                        <tr>
                          <th>GR (ઠરાવો)</th>
                          <td>
                            @if($gr_files == 'no data')
                              No File
                            @else
                            @if($pval->gr_file->count() > 0)
                              @foreach($pval->gr_file as $kgrs => $vgrs)
                                @php
                                  $extension = pathinfo($vgrs->file_name, PATHINFO_EXTENSION);
                                @endphp
                                @if($extension == 'pdf')
                                    <a href="{{ route('schemes.get_the_file', [Crypt::encrypt($pval->scheme_id), $vgrs->file_name]) }}" target="_blank" title="{{ $vgrs->file_name }}"><i class="fas fa-file-pdf fa-2x" style="color:red;"></i></a>
                                @elseif ($extension == 'doc')
                                  <a href="{{ route('schemes.get_the_file', [Crypt::encrypt($pval->scheme_id), $vgrs->file_name]) }}" download="{{ $vgrs->file_name }}"><i class="fas fa-download fa-2x" style="color:#007bff;"></i></a>

                                @else
                                    <a href="{{ route('schemes.get_the_file', [Crypt::encrypt($pval->scheme_id), $vgrs->file_name]) }}" download="{{ $vgrs->file_name }}"><i class="fas fa-download fa-2x" style="color:green;"></i></a>
                                @endif
                              {{-- <a href="{{ $replace_url }}/get_the_file/{{ $pval->scheme_id }}/_gr_{{++$kgrs}}" target="_blank">
                                <i class="fas fa-file-pdf fa-2x" style="color:red;"></i>
                              </a> --}}
                              @endforeach
                              @endif
                            @endif
                          </td>
                        </tr>
                        <tr>
                          <th>Notification (જાહેરનામાં)</th>
                          <td>
                            @if($notification_files == 'no data')
                              No File
                            @else
                            @if ($pval->notification_files->count() > 0)
                              @foreach($pval->notification_files as $kgrs => $items)
                                @php
                                  $extension = pathinfo($items->file_name, PATHINFO_EXTENSION);
                                @endphp
                                @if($extension == 'pdf')
                                    <a href="{{ route('schemes.get_the_file', [Crypt::encrypt($pval->scheme_id), $items->file_name]) }}" target="_blank" title="{{ $items->file_name }}"><i class="fas fa-file-pdf fa-2x" style="color:red;"></i></a>
                                    @elseif ($extension == 'doc')
                                    <a href="{{ route('schemes.get_the_file', [Crypt::encrypt($pval->scheme_id), $items->file_name]) }}" download="{{ $items->file_name }}"><i class="fas fa-download fa-2x" style="color:#007bff;"></i></a>

                                    @else
                                    <a href="{{ route('schemes.get_the_file', [Crypt::encrypt($pval->scheme_id), $items->file_name]) }}" download="{{ $items->file_name }}"><i class="fas fa-download fa-2x" style="color:green;"></i></a>
                                @endif
                              {{-- <a href="{{ $replace_url }}/get_the_file/{{ $pval->scheme_id }}/_notification_{{++$kgrs}}" target="_blank">
                                <i class="fas fa-file-pdf fa-2x" style="color:red;"></i>
                              </a> --}}
                              @endforeach
                            @endif
                            @endif
                          </td>
                        </tr>
                        <tr>
                          <th>Brochure (બ્રોશર)</th>
                          <td>
                            @if($brochure_files == 'no data')
                              No File
                            @else
                            @if($pval->brochure_files->count() > 0)
                              @foreach($pval->brochure_files as $kgrs => $file)
                              @php
                                $extension = pathinfo($file->file_name, PATHINFO_EXTENSION);
                              @endphp
                              @if($extension == 'pdf')
                                  <a href="{{ route('schemes.get_the_file', [Crypt::encrypt($pval->scheme_id), $file->file_name]) }}" target="_blank" title="{{ $file->file_name }}"><i class="fas fa-file-pdf fa-2x" style="color:red;"></i></a>
                              @elseif ($extension == 'doc')
                              <a href="{{ route('schemes.get_the_file', [Crypt::encrypt($pval->scheme_id), $file->file_name]) }}" download="{{ $file->file_name }}"><i class="fas fa-download fa-2x" style="color:#007bff;"></i></a>

                              @else
                                  <a href="{{ route('schemes.get_the_file', [Crypt::encrypt($pval->scheme_id), $file->file_name]) }}" download="{{ $file->file_name }}"><i class="fas fa-download fa-2x" style="color:green;"></i></a>
                              @endif
                              {{-- <a href="{{ $replace_url }}/get_the_file/{{ $pval->scheme_id }}/_brochure_{{++$kgrs}}" target="_blank">
                                <i class="fas fa-file-pdf fa-2x" style="color:red;"></i>
                              </a> --}}
                              @endforeach
                            @endif
                            @endif
                          </td>
                        </tr>
                        <tr>
                          <th>Pamphlets (પેમ્ફલેટ્સ)</th>
                          <td>
                            @if($pamphlets_files == 'no data')
                              No File
                            @else
                              @if($pval->pamphlets_files->count() > 0)
                                @foreach($pval->pamphlets_files as $kgrs => $pam_file)
                                  @php
                                    $extension = pathinfo($pam_file->file_name, PATHINFO_EXTENSION);
                                  @endphp
                                  @if($extension == 'pdf')
                                      <a href="{{ route('schemes.get_the_file', [Crypt::encrypt($pval->scheme_id), $pam_file->file_name]) }}" target="_blank" title="{{ $pam_file->file_name }}"><i class="fas fa-file-pdf fa-2x" style="color:red;"></i></a>
                                      @elseif ($extension == 'doc')
                                      <a href="{{ route('schemes.get_the_file', [Crypt::encrypt($pval->scheme_id), $pam_file->file_name]) }}" download="{{ $pam_file->file_name }}"><i class="fas fa-download fa-2x" style="color:#007bff;"></i></a>

                                      @else
                                      <a href="{{ route('schemes.get_the_file', [Crypt::encrypt($pval->scheme_id), $pam_file->file_name]) }}" download="{{ $pam_file->file_name }}"><i class="fas fa-download fa-2x" style="color:green;"></i></a>
                                  @endif
                                {{-- <a href="{{ $replace_url }}/get_the_file/{{ $pval->scheme_id }}/_pamphlets_{{++$kgrs}}" target="_blank">
                                  <i class="fas fa-file-pdf fa-2x" style="color:red;"></i>
                                </a> --}}
                                @endforeach
                              @endif
                            @endif
                          </td>
                        </tr>
                        <tr>
                          <th>Other Details of the Scheme (યોજનાને લાગતું અન્ય સાહિત્ય) ( Central–State Separate )</th>
                          <td>
                            @if($otherdetailscenterstate_files == 'no data')
                              No File
                            @else
                              @if($pval->otherdetailscenterstate_files->count() > 0)
                                @foreach($pval->otherdetailscenterstate_files as $kgrs => $other_file)
                                  @php
                                    $extension = pathinfo($other_file->file_name, PATHINFO_EXTENSION);
                                  @endphp
                                  @if($extension == 'pdf')
                                      <a href="{{ route('schemes.get_the_file', [Crypt::encrypt($pval->scheme_id), $other_file->file_name]) }}" target="_blank" title="{{ $other_file->file_name }}"><i class="fas fa-file-pdf fa-2x" style="color:red;"></i></a>
                                      @elseif ($extension == 'doc')
                                      <a href="{{ route('schemes.get_the_file', [Crypt::encrypt($pval->scheme_id), $other_file->file_name]) }}" download="{{ $other_file->file_name }}"><i class="fas fa-download fa-2x" style="color:#007bff;"></i></a>

                                      @else
                                      <a href="{{ route('schemes.get_the_file', [Crypt::encrypt($pval->scheme_id), $other_file->file_name]) }}" download="{{ $other_file->file_name }}"><i class="fas fa-download fa-2x" style="color:green;"></i></a>
                                  @endif
                                {{-- <a href="{{ $replace_url }}/get_the_file/{{ $pval->scheme_id }}/otherdetailscenterstate_{{++$kgrs}}" target="_blank">
                                  <i class="fas fa-file-pdf fa-2x" style="color:red;"></i>
                                </a> --}}
                                @endforeach
                              @endif
                            @endif
                          </td>
                        </tr>
                      <tr>
                      <th>Beneficiary Filling form( લાભાર્થી ભરવાનું ફોર્મ)</th>
                      <td>
                          {{($pval->beneficiary_filling_form_type == 0) ? 'Yes' : 'No'}}
                      </td>
                    </tr>
                    <tr>
                      <th>Beneficiary Filling form File( લાભાર્થી ભરવાનું ફોર્મ)</th>
                      <td>
                        @if($pval->beneficiary_filling_form == '')
                          No File
                        @else
                            @php
                              $extension = pathinfo($pval->beneficiary_filling_form, PATHINFO_EXTENSION);
                            @endphp
                            @if($extension == 'pdf')
                                <a href="{{ route('schemes.get_the_file', [Crypt::encrypt($pval->scheme_id), $pval->beneficiary_filling_form]) }}" target="_blank" title="{{ $pval->beneficiary_filling_form }}"><i class="fas fa-file-pdf fa-2x" style="color:red;"></i></a>
                                @elseif ($extension == 'doc')
                                <a href="{{ route('schemes.get_the_file', [Crypt::encrypt($pval->scheme_id), $pval->beneficiary_filling_form]) }}" download="{{ $pval->beneficiary_filling_form }}"><i class="fas fa-download fa-2x" style="color:#007bff;"></i></a>

                                @else
                                <a href="{{ route('schemes.get_the_file', [Crypt::encrypt($pval->scheme_id), $pval->beneficiary_filling_form]) }}" download="{{ $pval->beneficiary_filling_form }}"><i class="fas fa-download fa-2x" style="color:green;"></i></a>
                            @endif
                        @endif
                      </td>
                    </tr>
                    <tr>
                      <th>Major Monitoring Indicator at HOD Level (Other than Secretariat Level) (ખાતાના વડાકક્ષાએ મહત્વના ઇન્ડિકેટર નુ મોનીટરીંગ.(સચિવાલય સિવાય))</th>
                      <td>{{$pval->major_indicator_hod}}</td>
                    </tr>
                    @endforeach
                  </table>


                  <table class="table table-bordered table-hover table-stripped table-responsive" style="margin-top:-20px;">
                    <tr>
                      <th rowspan="{{ count($financial_progress)+2 }}">Financial & Physical Progress  (component wise) of the Last Five Years/Beginning of the Plan (યોજના ની શરૂઆત/છેલ્લા પાંચ વર્ષની વર્ષવાર નાણાકીય અને ભૌતિક પ્રગતિ (કમ્પોનેટ વાઇઝ)) :</th>
                     
                        <th rowspan="2" style="font-size: 16px;" class="text-center">Financial Year/નાણાકીય વર્ષ </th>
                        <th colspan="3" class="text-center">Physical/ભૌતિક</th>
                        <th colspan="2" class="text-center">Financial/નાણાકીય <small>(Rs in Crores)</small></th>
                    </tr>
                    <tr>
                     
                      <th style="font-size: 16px;">Unit - એકમ</th>
                      <th style="font-size: 16px;">Target – લક્ષ્યાંક</th>
                      <th style="font-size: 16px;">Achievement – સિધ્ધિ</th>
                      <th style="font-size: 16px;">Provision– જોગવાઇ</th>
                      <th style="font-size: 16px;">Expenditure – ખર્ચ</th>
                      
                    </tr>
                    @if($financial_progress->count())
                      @foreach($financial_progress as $fpk => $fpv)
                      <tr>
                        <td class="text-center">{{ $fpv->financial_year }}</td>
                        {{-- <td>{{ $fpv->units }}</td> --}}
                        <td class="text-center">{{ units($fpv->selection) }}</td>
                        <td class="text-center">{{ $fpv->target }}</td>
                        <td class="text-center">{{ $fpv->achievement }}</td>
                        <td class="text-center">{{ $fpv->allocation }}</td>
                        <td class="text-center">{{ $fpv->expenditure }}</td>
                        {{-- <td class="text-center">{{ $fpv->items }}</td> --}}
                      </tr>
                      @endforeach
                    @endif
                  </table>
                  
                </div>
              </div>
            </div>
          </div>
        {{-- </div>
      </div> --}}
    </div>
  </div>
</div>
 <script src="{{asset('js/jquery.min.js')}}"></script>
<script type="text/javascript">
 $(document).ready(function () {

    var beneficiariesGeoLocal = "{{ $pval->beneficiariesGeoLocal }}";

    if (beneficiariesGeoLocal) {

        var scheme_id = "{{ $scheme_id }}";

        $.ajax({
            type: 'GET',
            dataType: 'json',
            url: "{{ route('schemes.beneficiariesDetail') }}",
            data: {
                beneficiariesGeoLocal: beneficiariesGeoLocal,
                scheme_id: scheme_id
            },
            beforeSend: function () {
                $('.districtList').html(
                    '<div class="col-12 text-center">' +
                    '<img src="loading.gif" style="max-width:80px">' +
                    '</div>'
                );
            },
            success: function (response) {

                $('.districtList').empty();

                // ✅ DISTRICTS
                if (response.districts && response.districts.length > 0) {
                    $.each(response.districts, function (i, item) {
                        $('.districtList').append(
                            `<div class="col-xl-3 col-md-4 col-sm-6 mb-2">
                                • ${item.name_e}
                            </div>`
                        );
                    });
                }

                // ✅ STATES
                else if (response.states && response.states.length > 0) {
                    $.each(response.states, function (i, item) {
                        $('.districtList').append(
                            `<div class="col-xl-3 col-md-4 col-sm-6 mb-2">
                                • ${item.name}
                            </div>`
                        );
                    });
                }

                // ✅ TALUKAS
                else if (response.talukas && response.talukas.length > 0) {
                    $.each(response.talukas, function (i, item) {
                        $('.districtList').append(
                            `<div class="col-xl-3 col-md-4 col-sm-6 mb-2">
                                • ${item.tname_e}
                            </div>`
                        );
                    });
                }

                else {
                    $('.districtList').html(
                        '<div class="col-12 text-muted">No data available</div>'
                    );
                }
            },
            error: function () {
                console.log('districts ajax error');
            }
        });
    }
});

</script> 
@endsection
