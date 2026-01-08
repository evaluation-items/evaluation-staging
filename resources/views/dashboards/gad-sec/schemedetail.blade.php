@extends('dashboards.gad-sec.layouts.gadsec-dash-layout')
@section('title','Proposal Detail')
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
<!--begin::Content-->
<div class="content  d-flex flex-column flex-column-fluid" id="kt_content">
  <!--begin::Subheader-->
  <div class="subheader py-2 py-lg-6  subheader-solid " id="kt_subheader">
    <div class=" container-fluid  d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
    <!--begin::Info-->
    <div class="d-flex align-items-center flex-wrap mr-1" >
      <!--begin::Page Heading-->
      <div class="d-flex align-items-baseline flex-wrap mr-5">
        <!--begin::Page Title-->
        <h5 class="text-dark font-weight-bold my-1 mr-5">
          Scheme Detail
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
      <div class="card card-custom card-transparent">
          <div class="card-body p-0">
          <!--begin: Wizard-->
          <div class="wizard wizard-4" id="kt_wizard_v4" data-wizard-state="step-first" data-wizard-clickable="true">
            <div class="card card-custom card-shadowless rounded-top-0">
              <div class="card-body p-10">
                <div class="row table-responsive">
                  <table width="100%" class="table table-bordered table-hover table-stripped">
                    <tr>
                      <th width="30%">Department Name</th>
                      <td width="70%">{{ $dept_name }}</td>
                    </tr>
                    @foreach($schemes as $pkey=>$pval)
                    <tr>
                      <th>Convener Name</th>
                      <td>
                        {{ $pval->convener_name }}
                      </td>
                    </tr>
                    <tr>
                      <th>Scheme Name</th>
                      <td>
                        {{ $pval->scheme_name }}
                      </td>
                    </tr>
                    <tr>
                      <th>Major Objectives</th>
                      <td>
                        @if(!empty($major_objectives))
                        @foreach($major_objectives as $mkey=>$mval)
                          {{ $mval['major_objective'] }}
                          {!! '<br>' !!}
                        @endforeach
                        @endif
                      </td>
                    </tr>
                    <tr>
                      <th>Major Indicators</th>
                      <td>
                        @if(!empty($major_indicators))
                        @foreach($major_indicators as $indkey => $indval)
                          {{ $indval['major_indicator'] }}
                          {!! '<br>' !!}
                        @endforeach
                        @endif
                      </td>
                    </tr>
                    <tr>
                      <th>Implementing Office</th>
                      <td>
                        {{ $pval->implementing_office }}
                      </td>
                    </tr>
                    <tr>
                      <th>Nodal Officer Name</th>
                      <td>
                        {{ $pval->nodal_officer_name }}
                      </td>
                    </tr>
                    <tr>
                      <th>Financial Adviser Name</th>
                      <td>
                        {{ $pval->financial_adviser_name }}
                      </td>
                    </tr>
                    <tr>
                      <th>Fund Ratio Center</th>
                      <td>
                        {{ $pval->center_ratio }} %
                      </td>
                    </tr>
                    <tr>
                      <th>Fund Ratio State</th>
                      <td>
                        {{ $pval->state_ratio }} %
                      </td>
                    </tr>
                    <tr>
                      <th>Scheme Overview</th>
                      <td style="text-align:justify;line-height: 1.5;">
                        {{ $pval->scheme_overview }}
                      </td>
                    </tr>
                    <tr>
                      <th>Scheme Objective</th>
                      <td style="text-align:justify;line-height: 1.5;">
                        {{ $pval->scheme_objective }}
                      </td>
                    </tr>
                    <tr>
                      <th>Scheme Components</th>
                      <td>
                        {{ $pval->sub_scheme }}
                      </td>
                    </tr>
                    <tr>
                      <th>Commencement Year</th>
                      <td>
                        {{ $pval->commencement_year }}
                      </td>
                    </tr>
                    <tr>
                      <th>Reference Year</th>
                      <td>{{ $pval->reference_year }}</td>
                    </tr>
                    <tr>
                      <th>Scheme Beneficiary Selection Criteria</th>
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
                      <th>Scheme Implementing Procedure</th>
                      <td>
                        {{ $pval->scheme_implementing_procedure }}
                      </td>
                    </tr>
                    <tr>
                      <th>Major Monitoring Indicator ate HoD Level (Other than Secretariat Level)</th>
                      <td>
                        @if(!empty($major_indicator_hod))
                          @foreach($major_indicator_hod as $mihk=>$mihv)
                            {{ $mihv['major_indicator_hod'] }}
                            {!! '<br>' !!}
                          @endforeach
                        @endif
                      </td>
                    </tr>
                    <tr>
                      <th width="30%">Is Evaluation of this Scheme already done ?</th>
                      <td width="70%">@if($pval->is_evaluation == 'Y') Yes @else No @endif</td>
                    </tr>
                    @endforeach
                  </table>
                  <table class="table table-bordered table-hover table-stripped" style="margin-top:-20px;">
                    <tr>
                      <th rowspan="{{ count($financial_progress)+2 }}">Physical & Financial Progress <br> (component wise) Last Five Year</th>
                      <th style="text-align: center;">Financial year</th>
                      <th style="text-align: center;" colspan="3">Physical</th>
                      <th style="text-align: center;" colspan="2">Financial</th>
                    </tr>
                    <tr>
                      <th>-</th>
                      <th>Units</th>
                      <th>Target</th>
                      <th>Achievement</th>
                      <th>Allocation</th>
                      <th>Expenditure</th>
                    </tr>
                    @foreach($financial_progress as $fpk => $fpv)
                    <tr>
                      <td style="text-align: right;">{{ $fpv->financial_year }}</td>
                      <td>{{ $fpv->units }}</td>
                      <td style="text-align: right;">{{ $fpv->target }}</td>
                      <td style="text-align: right;">{{ $fpv->achievement }}</td>
                      <td style="text-align: right;">{{ $fpv->allocation }}</td>
                      <td style="text-align: right;">{{ $fpv->expenditure }}</td>
                    </tr>
                    @endforeach
                  </table>
                  <table width="100%" class="table table-bordered table-hover table-stripped" id="convergence_table" style="margin-top:-20px">
                    @foreach($schemes as $pkey=>$pval)
                    @if($pval->convergencewithotherscheme)
                    <tr>
                      <th>Convergence with other Scheme</th>
                      <td>{{ str_replace('_',' ',$pval->convergencewithotherscheme) }}</td>
                    </tr>
                    @endif
                    @if($pval->all_convergence)
                      @php 
                        $all_convergence = json_decode($pval->all_convergence);
                      @endphp
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
                      <th><small>Administrative set up for the Implementation of the Scheme</small> <br>From state to beneficiaries Geographical Coverage</th>
                      <td>
                        @foreach($beneficiariesGeoLocal as $benkey => $benval)
                          @if($benkey == $pval->beneficiariesGeoLocal) {{ $benval->name }} @endif
                        @endforeach
                      </td>
                    </tr>
                    @if($pval->districts != 'null')
                    <tr>
                      <th>Districts</th>
                      <td>
                          @foreach($district_names as $dkey=>$dval)
                            <p style="padding:5px 0px;margin:0px">{{ $dval }}</p>
                          @endforeach
                      </td>
                    </tr>
                    @endif
                    @if($pval->talukas != 'null')
                    <tr>
                      <th>Talukas</th>
                      <td>
                          @foreach($taluka_names as $dkey=>$dval)
                            <p style="padding:5px 0px;margin:0px">{{ $dval }}</p>
                          @endforeach
                      </td>
                    </tr>
                    @endif
                    <tr>
                      <th>If other Geographical beneficiaries coverage</th>
                      <td>{{ $pval->otherbeneficiariesGeoLocal }}</td>
                    </tr>
                    <tr>
                      <th>Scheme coverage since inception of Scheme</th>
                      <td>
                        <p>{{ $pval->coverage_beneficiaries_remarks }}</p>
                        @if($bencovfile == 'no data') 
                          No File
                        @else
                          <a href="{{ $replace_url }}/get_the_file/{{ $scheme_id }}/_beneficiaries_coverage" target="_blank">
                            <i class="fas fa-regular fa-file-pdf fa-2x" style="color:red;"></i>
                          </a>
                        @endif
                      </td>
                    </tr>
                    <tr>
                      <th>Training/CapacityBuilding of facilitators</th>
                      <td>
                        <p>{{ $pval->training_capacity_remarks }}</p>
                        @if($trainingfile == 'no data')
                          No File
                        @else
                          <a href="{{ $replace_url }}/get_the_file/{{ $scheme_id }}/_training" target="_blank">
                            <i class="fas fa-regular fa-file-pdf fa-2x" style="color:red;"></i>
                          </a>
                        @endif
                      </td>
                    </tr>
                    <tr>
                      <th>IEC activities</th>
                      <td>
                        <p>{{ $pval->coverage_beneficiaries_remarks }}</p>
                        @if($iecfile == 'no data')
                          No File
                        @else
                        <a href="{{ $replace_url }}/get_the_file/{{ $scheme_id }}/_iec" target="_blank">
                            <i class="fas fa-regular fa-file-pdf fa-2x" style="color:red;"></i>
                        </a>
                        @endif
                      </td>
                    </tr>
                    <tr>
                      <th>GR</th>
                      <td>
                        @if($gr_files == 'no data')
                          No File
                        @else
                        @foreach($gr_files as $kgrs => $vgrs)
                        <a href="{{ $replace_url }}/get_the_file/{{ $scheme_id }}/_gr_{{++$kgrs}}" target="_blank">
                          <i class="fas fa-file-pdf fa-2x" style="color:red;"></i>
                        </a>
                        @endforeach
                        @endif
                      </td>
                    </tr>
                    <tr>
                      <th>Notification</th>
                      <td>
                        @if($notification_files == 'no data')
                          No File
                        @else
                        @foreach($notification_files as $kgrs => $vgrs)
                        <a href="{{ $replace_url }}/get_the_file/{{ $scheme_id }}/_notification_{{++$kgrs}}" target="_blank">
                          <i class="fas fa-file-pdf fa-2x" style="color:red;"></i>
                        </a>
                        @endforeach
                        @endif
                      </td>
                    </tr>
                    <tr>
                      <th>Brochure</th>
                      <td>
                        @if($brochure_files == 'no data')
                          No File
                        @else
                        @foreach($brochure_files as $kgrs => $vgrs)
                        <a href="{{ $replace_url }}/get_the_file/{{ $scheme_id }}/_brochure_{{++$kgrs}}" target="_blank">
                          <i class="fas fa-file-pdf fa-2x" style="color:red;"></i>
                        </a>
                        @endforeach
                        @endif
                      </td>
                    </tr>
                    <tr>
                      <th>Pamphlets</th>
                      <td>
                        @if($pamphlets_files == 'no data')
                          No File
                        @else
                        @foreach($pamphlets_files as $kgrs => $vgrs)
                        <a href="{{ $replace_url }}/get_the_file/{{ $scheme_id }}/_pamphlets_{{++$kgrs}}" target="_blank">
                          <i class="fas fa-file-pdf fa-2x" style="color:red;"></i>
                        </a>
                        @endforeach
                        @endif
                      </td>
                    </tr>
                    <tr>
                      <th>Other Details of Scheme</th>
                      <td>
                        @if($otherdetailscenterstate_files == 'no data')
                          No File
                        @else
                        @foreach($otherdetailscenterstate_files as $kgrs => $vgrs)
                        <a href="{{ $replace_url }}/get_the_file/{{ $scheme_id }}/otherdetailscenterstate_{{++$kgrs}}" target="_blank">
                          <i class="fas fa-file-pdf fa-2x" style="color:red;"></i>
                        </a>
                        @endforeach
                        @endif
                      </td>
                    </tr>
                    <tr>
                      <th>Beneficiary Group</th>
                      <td>{{ $pval->benefit_to }}</td>
                    </tr>
                    <tr>
                      <th>Goals</th>
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
                      <th> By Whom </th>
                      <td>
                        {{ $pval->eval_scheme_bywhom }}
                      </td>
                    </tr>
                    <tr>
                      <th> When </th>
                      <td>
                        {{ date('d M, Y',strtotime($pval->eval_scheme_when)) }}
                      </td>
                    </tr>
                    <tr>
                      <th> Geographical Coverage Benefit </th>
                      <td>
                        {{ $pval->eval_scheme_geo_cov_bene }}
                      </td>
                    </tr>
                    <tr>
                      <th> No. of beneficiaries </th>
                      <td>
                        {{ $pval->eval_scheme_no_of_bene }}
                      </td>
                    </tr>
                    <tr>
                      <th> Major Recommendation </th>
                      <td>
                        {{ $pval->eval_scheme_major_recommendation }}
                      </td>
                    </tr>
                    <tr>
                      <th> Report </th>
                      <td>
                        @if($eval_report == 'no data')
                          No File
                        @else
                        <a href="{{ $replace_url }}/get_the_file/{{ $scheme_id }}/_eval_report_" target="_blank">
                            <i class="fas fa-regular fa-file-pdf fa-2x" style="color:red;"></i>
                        </a>
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
        </div>
      </div>
    </div>
  </div>
</div>
@endsection



