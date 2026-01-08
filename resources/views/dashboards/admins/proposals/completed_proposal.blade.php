@extends('dashboards.eva-dir.layouts.evaldir-dash-layout')
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
                    <h5 class="text-dark font-weight-bold my-1 mr-5">Completed Evaluation Studies</h5>
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
							<h5>Reports of completed evaluation studies.</h5>
						</div>
					</div>
					<div class="card-body custom_filter_body">
              <div class="custom_filter">
                <div class="col-md-3 year_items">
                  <select class="form-control" id="year_select" aria-label="Default select example">
                    @php
                      $financialYears = getFinancialYear();
                    @endphp
                        <option value="">Select Financial Year</option>
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
                    <option value="">Select Department</option>
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
                      <option value="">Select Branch</option>
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
                        <th>Sr No.</th>
                        <th>Scheme Name</th>
                        <th>Department Name</th>
                        <th>Published Date</th>
                        <th>Actions</th>
                      </tr>
                    </thead>
                  <tbody>
                    @php $i=1; @endphp
                    @foreach($complted_proposal as $complete)
                      <tr>
                      <td>{{ $i }}</td>
                      <td>{{ SchemeName($complete->scheme_id) }}</td> 
                      <td>{{ department_name($complete->dept_id) }}</td>
                      <td>{{ date('d-M-y',strtotime($complete->final_report))}}</td>
                      <td width="23%">
                        <a href="{{route('stages.downalod',$complete->id)}}" class="btn btn-xs btn-info" style="display: inline-block">Stage Report Download</a>
                      </td>
                      </tr>
                      @php $i++; @endphp
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
<script src="{{asset('js/jquery-laest-version.min.js')}}"></script>

<script>
  var config = {
        routes: {
            zone: "{{route('custom_filter_items')}}"
        }
    };
</script>
@endsection