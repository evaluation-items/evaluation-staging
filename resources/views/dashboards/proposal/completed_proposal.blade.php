@extends('dashboards.proposal.layouts.sidebar')
@section('title','Completed Proposals')

@section('content')
<style>
  .content-wrapper{
    background: #4f80db;

  }
</style>

    <!--begin::Content-->
    <div class="content  d-flex flex-column flex-column-fluid" id="">
            <!--begin::Subheader-->
            <div class="subheader py-2 py-lg-6  subheader-solid " id="kt_subheader">
              <div class=" container-fluid  d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
                <!--begin::Info-->
                <div class="d-flex align-items-center flex-wrap mr-1">
                  <!--begin::Page Heading-->
                  <div class="d-flex align-items-baseline flex-wrap mr-5">
                    <!--begin::Page Title-->
                    <h5 class="text-dark font-weight-bold my-1 mr-5">{{ __('message.completed_evaluation_studies') }}</h5>
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
                  <!--begin::Card-->
                  <div class="card card-custom gutter-b" style="border: 1px solid #000;">
                    <div class="card-header flex-wrap py-3">
                      <div class="card-toolbar">
                        <h5>{{ __('message.completed_desc') }}</h5>
                      </div>
                    </div>
                    <div class="card-body custom_filter_body">
                      <div class="custom_filter">
                        <div class="col-md-3 year_items">
                          <select class="form-control" id="year_select" aria-label="Default select example">
                            @php
                              $financialYears = getFinancialYear();
                            @endphp
                                <option value="">{{ __('message.select_financial_year') }}</option>
                                <option value="1">All</option>
                              @foreach ($financialYears as $financialYear)
                                  <option value="{{$financialYear}}">{{$financialYear}}</option>
                              @endforeach
                          </select>
                        </div>
                        <div class="col-md-4 department_items">
                          <select id="department_select" class="form-control" aria-label="Default select example">
                            @php
                              $department = App\Models\Department::get();
                            @endphp
                            <option value="">{{ __('message.select_department') }}</option>
                            @foreach ($department as $department_items)
                                <option value="{{$department_items->dept_id}}">{{$department_items->dept_name}}</option>
                            @endforeach
                            
                          </select>
                        </div>
                        <div class="col-md-2 dd_user_items">
                          @php 
                              $branch_list = App\Models\Branch::with('roles')->orderBy('name','Asc')->get();
                          @endphp
                          <select id="deputyDirector_select" class="form-control" aria-label="Default select example">
                              <option value="">{{ __('message.select_branch') }}</option>
                              @foreach ($branch_list as $branch)
                                  @if($branch->roles->count() > 0)
                                      @php
                                          $roleIds = $branch->roles->pluck('id')->implode(',');
                                      @endphp
                                      <option value="{{ $roleIds }}">{{ $branch->name }}</option>
                                  @endif
                              @endforeach
                          </select>
                        </div> 
                      </div>
                      <!--begin: Datatable-->
                      <table class="table table-bordered table-striped dataTable dtr-inline custom_complete_table" id="example1">
                        <thead>
                          <tr>
                            <th>{{ __('message.no') }}</th>
                            <th>{{ __('message.scheme_name') }}</th>
                            <th>{{ __('message.department_name') }}</th>
                            {{-- <th>Name of HOD</th> --}}
                            <th>{{ __('message.report_published_date') }}</th>
                            <th>{{ __('message.actions') }}</th>
                          </tr>
                        </thead>
                        <tbody>
                        @php $i=1; @endphp
                        @foreach($complted_proposal as $complete)
                          <tr>
                            <td>{{ $i++ }}</td>
                            <td>{{ SchemeName($complete->scheme_id) }}</td> 
                            <td>{{ department_name($complete->dept_id) }}</td>
                            {{-- <td>{{ hod_name($complete->draft_id) }}</td> --}}
                            <td>{{date('d-M-y',strtotime($complete->final_report))}}</td>
                            <td width="23%">
                              <a href="{{route('stages.downalod',$complete->id)}}" class="btn btn-xs btn-info" style="display: inline-block">{{ __('message.stage_report_download') }}</a>
                            </td>
                          </tr>
                          @endforeach
                        </tbody>
                      </table> 
                      <!--end: Datatable-->
                    </div>
                  </div>
                  <!--end::Card-->
              </div>
              <!--end::Container-->
            </div>
      <!--end::Entry-->
      </div>
      <!--end::Content-->
@endsection
<script src="{{asset('js/jquery-laest-version.min.js')}}"></script>
<script>
  var config = {
       routes: {
           zone: "{{route('custom_filter_items')}}"
       }
   }; 
</script>
