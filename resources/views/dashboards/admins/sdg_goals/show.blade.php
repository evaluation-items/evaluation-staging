@extends('dashboards.admins.layouts.admin-dash-layout')
@section('title','SDG Goals Detail')

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
          SDG Goals {{ __('message.detail')}}
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
            <!--begin: Wizard-->
            <div class="wizard wizard-4" id="kt_wizard_v4" data-wizard-state="step-first" data-wizard-clickable="true">
              <div class="card card-custom card-shadowless rounded-top-0">
                <div class="card-body p-10">
                  <div class="row table-responsive">
                    <table class="table table-bordered table-hover table-stripped">
                      <tr>
                        <th>Gujarati {{ __('message.name')}}</th>
                        <td>{{ $sdggoal->goal_name_guj }}</td>
                      </tr>
                      <tr>
                        <th>{{ __('message.name')}}</th>
                        <td>{{ $sdggoal->goal_name }}</td>
                      </tr>
                      
                    </table>
                  </div>
                  <a href="{{ route('sdg-goals.index') }}" class="btn btn-xs btn-info" style="display:inline-block">{{ __('message.back')}}</a>
                </div>
              </div>
            </div>
         
      </div>
    </div>
  </div>
@endsection