@extends('dashboards.eva-dd.layouts.evaldd-dash-layout')
@section('title','Scheme Detail - Eval. D.D. ')
<style>
  .borderless {
    border:0px !important;
  }
</style>
@section('content')
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
                    <tr>
                      <th>Convener Name</th>
                      <td>
                        @foreach($convener as $kcon=>$vcon)
                          {{ $vcon->convener_name }}
                        @endforeach
                      </td>
                    </tr>
                    <tr>
                      <th>Convener Designation</th>
                      <td>
                        @foreach($convener as $kcon=>$vcon)
                          {{ $vcon->convener_designation }}
                        @endforeach
                      </td>
                    </tr>
                    <tr>
                      <th>Convener Phone Number</th>
                      <td>
                        @foreach($convener as $kcon=>$vcon)
                          {{ $vcon->convener_phone_no }}
                        @endforeach
                      </td>
                    </tr>
                    <tr>
                      <th>Convener Mobile Nubmer</th>
                      <td>
                        @foreach($convener as $kcon=>$vcon)
                          {{ $vcon->convener_mobile_no }}
                        @endforeach
                      </td>
                    </tr>
                    <tr>
                      <th>Convener Email</th>
                      <td>
                        @foreach($convener as $kcon=>$vcon)
                          {{ $vcon->convener_email }}
                        @endforeach
                      </td>
                    </tr>
                    @foreach($schemes as $pkey=>$pval)
                    <tr>
                      <th>Scheme Name</th>
                      <td>
                        {{ $pval->scheme_name }}
                      </td>
                    </tr>
                    <tr>
                      <th>Priority</th>
                      <td>
                        @if($pval->priority == 1)
                          Top Most
                        @elseif($pval->priority == 2)
                          Top
                        @else
                          Moderate
                        @endif
                      </td>
                    </tr>
                    <tr>
                      <th>Time Duration</th>
                      <td>
                        {{ $pval->time_duration }}
                      </td>
                    </tr>
                    <tr>
                      <th>Reference Year</th>
                      <td>{{ $pval->reference_year }}</td>
                    </tr>
                    <tr>
                      <th>Major Objectives</th>
                      <td>
                        @foreach($major_objectives as $mkey=>$mval)
                          {{ $mval['major_objective'] }}
                          {!! '<br>' !!}
                        @endforeach
                      </td>
                    </tr>
                    <tr>
                      <th>Major Indicators</th>
                      <td>
                        @foreach($major_indicators as $indkey => $indval)
                          {{ $indval['major_indicator'] }}
                          {!! '<br>' !!}
                        @endforeach
                      </td>
                    </tr>
                    <tr>
                      <th>Implementation Department Name</th>
                      <td>
                        {{ $pval->implementing_office }}
                      </td>
                    </tr>
                    <tr>
                      <th>Implementation Department Email</th>
                      <td>
                        @if(count($imdept) > 0)
                          @foreach($imdept as $imk=>$imv)
                            {{ $imv->email }}
                          @endforeach
                        @endif
                      </td>
                    </tr>
                    <tr>
                      <th>Nodal ID</th>
                      <td>
                        {{ $pval->nodal_id }}
                      </td>
                    </tr>
                    <tr>
                      <th>Adviser Name</th>
                      <td>
                      @if(count($adviseris) > 0)
                        @foreach($adviseris as $kadv => $vadv)
                          {{ $vadv->adviser_name }}
                        @endforeach
                      @endif
                      </td>
                    </tr>
                    <tr>
                      <th>Adviser Mobile No.</th>
                      <td>
                      @if(count($adviseris) > 0)
                        @foreach($adviseris as $kadv => $vadv)
                          {{ $vadv->adviser_mobile }}
                        @endforeach
                      @endif
                      </td>
                    </tr>
                    <tr>
                      <th>Adviser Email</th>
                      <td>
                      @if(count($adviseris) > 0)
                        @foreach($adviseris as $kadv => $vadv)
                          {{ $vadv->adviser_email }}
                        @endforeach
                      @endif
                      </td>
                    </tr>
                    <tr>
                      <th>Center Ratio</th>
                      <td>
                        {{ $pval->center_ratio }} %
                      </td>
                    </tr>
                    <tr>
                      <th>Center Ratio</th>
                      <td>
                        {{ $pval->state_ratio }} %
                      </td>
                    </tr>
                    <tr>
                      <th>Scheme Overview</th>
                      <td>
                        {{ $pval->scheme_overview }}
                      </td>
                    </tr>
                    <tr>
                      <th>Scheme Objective</th>
                      <td>
                        {{ $pval->scheme_objective }}
                      </td>
                    </tr>
                    <tr>
                      <th>Sub Scheme</th>
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
                      <th>Scheme Beneficiary Selection Criteria</th>
                      <td>
                        {{ $pval->scheme_beneficiary_selection_criteria }}
                      </td>
                    </tr>
                    <tr>
                      <th>Scheme Implementing Procedure</th>
                      <td>
                        {{ $pval->scheme_implementing_procedure }}
                      </td>
                    </tr>
                    <tr>
                      <th>Major Monitoring Indicator at HOD Level (Other than Secretariat Level)</th>
                      <td>
                        @if(count($major_indicator_hod) > 0)
                          @foreach($major_indicator_hod as $mihk=>$mihv)
                            {{ $mihv['major_indicator_hod'] }}
                            {!! '<br>' !!}
                          @endforeach
                        @endif
                      </td>
                    </tr>
                    @endforeach
                  </table>
                  <table class="table table-bordered table-hover table-stripped" style="margin-top:-20px;">
                    <tr>
                      <th rowspan="{{ count($financial_progress)+2 }}">Physical & Financial Progress <br> (component wise) Last Five Year</th>
                      <th>Financial year</th>
                      <th>Districts</th>
                      <th colspan="2">Physical</th>
                      <th colspan="2">Financial</th>
                    </tr>
                    <tr>
                      <th>-</th>
                      <th>-</th>
                      <th>Allocation</th>
                      <th>Expenditure</th>
                      <th>Target</th>
                      <th>Achievement</th>
                    </tr>
                    @foreach($financial_progress as $fpk => $fpv)
                    <tr>
                      <td>{{ $fpv->financial_year }}</td>
                      <td></td>
                      <td>{{ $fpv->target }}</td>
                      <td>{{ $fpv->achievement }}</td>
                      <td>{{ $fpv->allocation }}</td>
                      <td>{{ $fpv->expenditure }}</td>
                    </tr>
                    @endforeach
                  </table>
                  <table width="100%" class="table table-bordered table-hover table-stripped" style="margin-top:-20px">
                    @foreach($schemes as $pkey=>$pval)
                    <tr>
                      <th width="30%">Is Evaluation of this scheme already Performed ?</th>
                      <td width="70%">@if($pval->is_evaluation == 'Y') Yes @else No @endif</td>
                    </tr>
                    <tr>
                      <th>Convergence with other scheme</th>
                      <td>{{ str_replace('_',' ',$pval->convergencewithotherscheme) }}</td>
                    </tr>
                    <tr>
                      <th><small>Administrative set up for the Implementation of the scheme</small> <br>From state to beneficiaries Geographical Coverage</th>
                      <td>
                        @foreach($beneficiariesGeoLocal as $benkey => $benval)
                          @if($benkey == $pval->beneficiariesGeoLocal) {{ $benval->name }} @endif
                        @endforeach
                      </td>
                    </tr>
                    <tr>
                      <th>Districts</th>
                      <td>
                        @if($pval->districts)
                          @foreach($district_names as $dkey=>$dval)
                            {{ $dval }}
                            {!! '<br>' !!}
                          @endforeach
                        @endif
                      </td>
                    </tr>
                    <tr>
                      <th>If other Geographical beneficiaries coverage</th>
                      <td>{{ $pval->otherbeneficiariesGeoLocal }}</td>
                    </tr>
                    <tr>
                      <th>Scheme coverage since inception of scheme</th>
                      <td>
                        @if($bencovfile == 'no data') 
                          No File
                        @else
                          <a href="{{ $replace_url }}/eval/get_the_file/{{ $scheme_id }}/_beneficiaries_coverage" target="_blank">
                            <i class="fas fa-regular fa-file-pdf fa-2x" style="color:red;"></i>
                          </a>
                        @endif
<!--                         <a href="javascript:void(0)" onclick="fndownloadthefile('{{-- $scheme_id --}}','_beneficiaries_coverage')">
                          <img src="{{-- Storage::url('pdflogo.png') --}}">
                        </a> -->
<!--                         <a href="javascript:void(0)" onclick="fndownloadfile('beneficiaries_coverage','{{ $pval->scheme_id }}')">
                          <img src="{{ Storage::url('pdflogo.png') }}">
                        </a> -->
                      </td>
                    </tr>
                    <tr>
                      <th>Training/Capacity building of facilitators</th>
                      <td>
                        @if($trainingfile == 'no data')
                          No File
                        @else
                          <a href="{{ $replace_url }}/eval/get_the_file/{{ $scheme_id }}/_training" target="_blank">
                            <i class="fas fa-regular fa-file-pdf fa-2x" style="color:red;"></i>
                          </a>
                        @endif

<!--                         <a href="{{-- Storage::url('schemes/training/'.$pval->training) --}}" download="training">
                          <img src="{{-- Storage::url('pdflogo.png') --}}">
                        </a> -->
                      </td>
                    </tr>
                    <tr>
                      <th>IEC activities</th>
                      <td>
                        @if($iecfile == 'no data')
                          No File
                        @else
                        <a href="{{ $replace_url }}/eval/get_the_file/{{ $scheme_id }}/_iec_file" target="_blank">
                            <i class="fas fa-regular fa-file-pdf fa-2x" style="color:red;"></i>
                        </a>
                        @endif
                      </td>
                    </tr>
                    <tr>
                      <th>Benefits To</th>
                      <td>{{ $pval->benefit_to }}</td>
                    </tr>
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