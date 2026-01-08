@extends('dashboards.admins.layouts.admin-dash-layout')
@section('title','Departments Detail')

@section('content')

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
            {{ __('message.department') }} {{ __('message.detail') }}
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
                        <th width="30%">{{ __('message.department') }} {{ __('message.name') }}</th>
                        <td width="70%">{{ $department->dept_name }}</td>
                      </tr>
                    </table>
                  </div>
                  <a href="{{ route('departments.index') }}" class="btn btn-xs btn-info" style="display:inline-block">{{ __('message.back') }}</a>
                </div>
              </div>
            </div>
          {{-- </div>
        </div> --}}
      </div>
    </div>
  </div>
  

@endsection