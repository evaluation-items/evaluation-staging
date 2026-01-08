@extends('dashboards.eva-dd.layouts.evaldd-dash-layout')
@section('title','Proposal Detail - Evalution Deputy Dir.')
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
                      <th>Name of the scheme/ Programme to be evaluated (કરવાના થતા મૂલ્યાંકન અભ્યાસ માટેના યોજના/કાર્યક્રમનું નામ)</th>
                      <td>
                        {{ $pval->scheme_name }}
                      </td>
                    </tr>
                    <tr>
                      <th>Major Objective of the Evaluation study (મૂલ્યાંકન અભ્યાસના મુખ્ય હેતુઓ)</th>
                      <td>
                        @if(!empty($major_objectives))
                        @foreach($major_objectives as $mkey=>$mval)
                          {{ $mval->major_objective }}
                          {!! '<br>' !!}
                        @endforeach
                        @endif
                      </td>
                    </tr>
                    <tr>
                      <th>Major Monitoring Indicators for scheme to be evaluated (મૂલ્યાંકન હાથ ધરવાની થતી યોજનાની  સમીક્ષાના મુખ્ય માપદંડૉ)</th>
                      <td>
                        @if(!empty($major_indicators))
                        @foreach($major_indicators as $indkey => $indval)
                          {{ $indval->major_indicator }}
                          {!! '<br>' !!}
                        @endforeach
                        @endif
                      </td>
                    </tr>
                    <tr>
                      <th>Name of the HoD/ Branch Contact No. (અમલીકરણ કચેરીનું નામ સંપર્ક નંબર)</th>
                      <td>
                       {{ $pval->hod_name}}
                      </td>
                    </tr>
                    <tr>
                      <th>Details of the Nodal Officer (HoD) Name (અમલીકરણ કચેરીના નોડલ નામની વિગતો)</th>
                      <td>
                        {{ $pval->nodal_officer_name }}
                      </td>
                    </tr>
                    <tr>
                      <th>Details of the Financial Adviser Name (નાણાકીય સલાહકારની નામની વિગતો)</th>
                      <td>
                        {{ $pval->financial_adviser_name }}
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
                      <th>Objectives of the scheme (યોજનાના હેતુઓ) </th>
                      <td>
                        {{ $pval->scheme_objective }}
                      </td>
                    </tr>
                    <tr>
                      <th>Name of Sub-schemes/components (પેટા યોજનાનું નામ અને ઘટકો)</th>
                      <td>
                        {{ $pval->sub_scheme }}
                      </td>
                    </tr>
                    <tr>
                      <th>Year of actual commencement of the scheme (યોજનાનું ખરેખર અમલીકરણ શરૂ કર્યા વર્ષ)</th>
                      <td>
                        {{ $pval->commencement_year }}
                      </td>
                    </tr>
                    <tr>
                      <th>The Reference year for which the Evaluation study to be done (મૂલ્યાંકન અભ્યાસ માટેનું સંદર્ભ વર્ષ)</th>
                      <td>{{ $pval->reference_year }}</td>
                    </tr>
                    <tr>
                      <th>Beneficiary/Community selection Criteria (લાભાર્થી/સમુદાયની પાત્રતા માટેના માપદંડો)</th>
                      <td>
                        @if($pval->scheme_beneficiary_selection_criteria)
                          @php
                            $criteria_sel = explode(',',$pval->scheme_beneficiary_selection_criteria);
                          @endphp
                          @foreach($criteria_sel as $selkey => $selval)
                            <p>{{ ++$selkey }}) {{ $selval }}</p>
                          @endforeach
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
                      <th>Major Monitoring Indicator at HOD Level (Other than Secretariat Level)</th>
                      <td>
                        @if(!empty($major_indicator_hod))
                          @foreach($major_indicator_hod as $mihk=>$mihv)
                            {{ $mihv->major_indicator_hod }}
                            {!! '<br>' !!}
                          @endforeach
                        @endif
                      </td>
                    </tr>
                    <tr>
                      <th width="30%">Whether evaluation of this scheme already done in past? (આ યોજનાનું મૂલ્યાંકન અગાઉ થઈ ચૂકેલ છે?) </th>
                      <td width="70%">@if($pval->is_evaluation == 'Y') Yes (હા) @else No (ના) @endif</td>
                    </tr>
                    @endforeach
                  </table>
                  <table class="table table-bordered table-hover table-stripped" style="margin-top:-20px;">
                    <tr>
                      <th rowspan="{{ count($financial_progress)+2 }}">Financial & Physical Progress (component wise) of Last Five Year (છેલ્લા પાંચ વર્ષની વર્ષવાર નાણાકીય અને ભૌતિક પ્રગતિ (કમ્પોનેટ વાઇઝ)) :</th>
                      <th>Financial year</th>
                      <th colspan="3" style="text-align: center;">Physical/ભૌતિક</th>
                      <th colspan="2" style="text-align: center;">Financial/નાણાકીય</th>
                    </tr>
                    <tr>
                      {{-- <th>-</th>
                      <th>Units/Types of beneficiar</th>
                      <th>Allocation</th>
                      <th>Expenditure</th>
                      <th>Target</th>
                      <th>Achievement</th> --}}
                      <th>-</th>
                      <th>Districts/ જિલ્લાઓ</th>
                      <th>Target – લક્ષ્યાંક</th>
                      <th>Achievement – સિધ્ધિ</th>
                      <th>Provision– જોગવાઇ</th>
                      <th>Expenditure – ખર્ચ</th>
                     
                    </tr>
                    @if($financial_progress->count())
                    @foreach($financial_progress as $fpk => $fpv)
                    <tr>
                      <td class="text-center">{{ $fpv->financial_year }}</td>
                      <td>{{ $fpv->units }}</td>
                      <td class="text-center">{{ $fpv->target }}</td>
                      <td class="text-center">{{ $fpv->achievement }}</td>
                      <td class="text-center">{{ $fpv->allocation }}</td>
                      <td class="text-center">{{ $fpv->expenditure }}</td>
                    </tr>
                    @endforeach
                    @endif
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
                      <th><small>Administrative set up for Implementation of the scheme (યોજનાના અમલીકરણ માટેનું વહીવટી માળખું)</small> <br>Geographical Coverage: From State to beneficiaries (રાજ્યકક્ષાથી લઈ લાભાર્થી સુધીનો ભૌગોલિક વ્યાપ)  </th>
                      <td>
                        @foreach($beneficiariesGeoLocal as $benkey=>$benval)
                          @if($pval->beneficiariesGeoLocal == $benkey) {{ $benval->name }} @endif
                        @endforeach
                      </td>
                    </tr>
                    <tr>
                      <th>If other Geographical beneficiaries coverage</th>
                      <td>{{ $pval->otherbeneficiariesGeoLocal }}</td>
                    </tr>
                    <tr>
                      <th>Scheme coverage since inception of the scheme (યોજનાની શરૂઆતથી અત્યાર સુધીનો વ્યાપ)</th>
                      <td>
                        @if($bencovfile == 'no data')
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
                        {{-- <a href="{{ $replace_url }}/get_the_file/{{ $$pval->scheme_id }}/_beneficiaries_coverage" target="_blank">
                          <i class="fas fa-file-pdf fa-2x" style="color:red;"></i>
                        </a> --}}
                        @endif
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
                      <th>Benefit (લાભ)</th>
                      <td>{{ $pval->benefit_to }}</td>
                    </tr>
                    <tr>
                      <th>Goal No. (ગોલ ક્રમાંક)</th>
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
                    @if($pval->is_evaluation == 'Y')
                    <tr>
                      <th> By Whom? (કોના દ્વારા?)
                         </th>
                      <td>
                        {{ $pval->eval_scheme_bywhom }}
                      </td>
                    </tr>
                    <tr>
                      <th> When? (ક્યારે?) </th>
                      <td>
                        {{ date('d M, Y',strtotime($pval->eval_scheme_when)) }}
                      </td>
                    </tr>
                    <tr>
                      <th> Geographical coverage of Beneficiaries (સમાવિષ્ટ કરેલ લાભાર્થીઓનો ભૌગોલિક વિસ્તાર)</th>
                      <td>
                        {{ $pval->eval_scheme_geo_cov_bene }}
                      </td>
                    </tr>
                    <tr>
                      <th> No. of beneficiaries in sample (નિદર્શમાં સમાવિષ્ટ લાભાર્થીઓની સંખ્યા) </th>
                      <td>
                        {{ $pval->eval_scheme_no_of_bene }}
                      </td>
                    </tr>
                    <tr>
                      <th> Major recommendation (મુખ્ય ભલામણો.) </th>
                      <td>
                        {{ $pval->eval_scheme_major_recommendation }}
                      </td>
                    </tr>
                    <tr>
                      <th> Upload report (અહેવાલ અપલોડ કરવો.)</th>
                      <td>
                        @if($eval_report == 'no data')
                          No File
                        @else
                        @php
                          $extension = pathinfo($pval->eval_scheme_report, PATHINFO_EXTENSION);
                        @endphp
                        @if($extension == 'pdf')
                            <a href="{{ route('schemes.get_the_file', [Crypt::encrypt($pval->scheme_id), $pval->eval_scheme_report]) }}" target="_blank" title="{{ $pval->eval_scheme_report }}"><i class="fas fa-file-pdf fa-2x" style="color:red;"></i></a>
                            @elseif ($extension == 'doc')
                            <a href="{{ route('schemes.get_the_file', [Crypt::encrypt($pval->scheme_id), $pval->eval_scheme_report]) }}" download="{{ $pval->eval_scheme_report }}"><i class="fas fa-download fa-2x" style="color:#007bff;"></i></a>

                            @else
                            <a href="{{ route('schemes.get_the_file', [Crypt::encrypt($pval->scheme_id), $pval->eval_scheme_report]) }}" download="{{ $pval->eval_scheme_report }}"><i class="fas fa-download fa-2x" style="color:green;"></i></a>
                        @endif
                        
                        {{-- <a href="{{ $replace_url }}/get_the_file/{{ $$pval->scheme_id }}/_eval_report_" target="_blank">
                            <i class="fas fa-regular fa-file-pdf fa-2x" style="color:red;"></i>
                        </a> --}}
                        @endif
                      </td>
                    </tr>
                    @endif
                    @endforeach
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
@endsection

