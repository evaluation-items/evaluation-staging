@extends('dashboards.eva-dd.layouts.evaldd-dash-layout')
@section('title','Completed Proposals')

@section('content')
<style>
  .content-wrapper{
    background: #e3daff;
  }
</style>
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
                        <div class="card card-custom gutter-b">
                          <div class="card-header flex-wrap py-3">
                            <div class="card-toolbar">
                              <h5>{{ __('message.complete_desc') }}</h5>
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
                                      <option value="1">{{ __('message.all') }}</option>
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
                                    <th>{{ __('message.published_date') }}</th>
                                    <th>{{ __('message.actions') }}</th>
                                  {{-- <th>Sr No.</th>
                                  <th>Scheme Name</th>
                                  <th>Department Name</th>
                                  <th>Published Date</th>
                                  <th>Actions</th> --}}
                                </tr>
                              </thead>
                              <tbody>
                                  @php $i=1; @endphp
                                  @foreach($complted_proposal as $complete)
                                  <tr>
                                    <td>{{ $i++ }}</td>
                                    <td>{{ SchemeName($complete->scheme_id) }}</td> 
                                    <td>{{ department_name($complete->dept_id) }}</td>
                                    <td class="text-center">{{ date('d-M-y',strtotime($complete->final_report))}}</td>
                                    <td width="32%">
                                      <button class="btn btn-xs btn-info report_data" data-url-pdf= "{{route('stages.downalod',$complete->id)}}" style="display: inline-block">{{ __('message.stage_report_download') }}</button>
                                      {{-- <a href="" class="btn btn-xs btn-info" style="display: inline-block">{{ __('message.stage_report_download') }}</a> --}}
                                      @if(!empty($complete->document))  
                                        <a class="btn btn-xs btn-info" href="{{ route('stages.get_the_file',[Crypt::encrypt($complete->scheme_id),$complete->document]) }}" target="_blank" title="{{ $complete->document }}">{{ __('message.view_document') }}</a>
                                      @endif
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

      <!-- Download PDF OR EXcel -->
      <div class="modal fade" id="open_modal" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-lg">
          <div class="modal-content" style="height: 180px;">
            <div class="modal-header">
              <h4 class="modal-title text-center" style="margin-left:auto;">{{ __('message.download_stage_detail_report') }}</h4>
              <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span>
              </button>
            </div>
            <div class="modal-body" style="display:flex;">
              <div class="col-md-6" style="border-right: 1px solid #aaafb5;">
                <input type="radio" id="report_item" class="report_item" name="report_item" value="0">
                <label for="report_item">{{ __('message.pdf') }}</label><br>
                <a href="#" class="btn btn-xs btn-info" id="pdfDownload" style="display: none;padding: 6px;margin-top: 10px;">{{ __('message.stage_report_download') }} ({{ __('message.pdf') }})</a>
              </div>
              <div class="col-md-6 radio-btn">
                <input type="radio" id="report_item1" class="report_item" name="report_item" value="1">
                <label for="report_item1">{{ __('message.excel') }}</label><br>
                <a href="#" class="btn btn-xs btn-info" id="excelDownload" style="display: none;padding: 6px;margin-top: 10px;">{{ __('message.stage_report_download') }} ({{ __('message.excel') }})</a>
              </div>  
            </div>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>
@endsection
<script src="{{asset('js/jquery-laest-version.min.js')}}"></script>

<script>
   var config = {
        routes: {
            zone: "{{route('custom_filter_items')}}"
        }
    }; 
   
  
</script>

