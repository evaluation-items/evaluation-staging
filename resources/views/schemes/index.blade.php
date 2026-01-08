@extends('dashboards.implementations.layouts.ia-dash-layout')
@section('title','Scheme')

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
                      Scheme List                               
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
              <div class=" container ">
                <!--begin::Card-->
                <div class="card card-custom gutter-b">
                  <div class="card-header flex-wrap py-3">
                    <div class="card-toolbar">
                      <!--begin::Dropdown-->
                      <!-- <a class="btn btn-primary" href="{{-- route('schemes.create') --}}"> Add New Scheme</a> -->
                    </div>
                  </div>
                  <div class="card-body">
                    <!--begin: Datatable-->
                     <table class="table table-bordered table-hover show-datatable" id="kt_datatable" style="margin-top: 13px !important">
                      <thead>
                        <tr>
                          <th>Sr No.</th>
                          <th>Scheme Name</th>
                          <th>Objective</th>
                          <th>Nodal Officer Name</th>
                          <th>Actions</th>
                        </tr>
                      </thead>
                      <tbody>
                      @foreach($schemelist as $schemekey => $scheme)
                        <tr>
                          <td> {{ ++$schemekey }} </td>
                          <td>{{ $scheme->scheme_name }}</td> 
                          <td style="text-align: justify;">{{ $scheme->scheme_objective }}</td> 
                          <td>{{ $scheme->convener_name }}</td> 
                          <td>
                            <a href="{{ route('schemes.scheme_detail',$scheme->scheme_id) }}" class="btn btn-xs btn-info">View</a>
                          </td>
                        </tr>
                        @endforeach
                      </tbody>
                    </table> 
                    <!--end: Datatable-->
                    <div class="d-flex justify-content-center" style="margin-top: 20px;">
                      {{-- $schemes->links() --}}
                    </div>
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
