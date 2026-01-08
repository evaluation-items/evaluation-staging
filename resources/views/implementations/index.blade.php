@extends('dashboards.implementations.layouts.ia-dash-layout')
@section('title','Implementation Details')
 
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
                      <a class="btn btn-primary" href="{{ route('schemes.create') }}"> Add New Scheme</a>
                    </div>
                  </div>
                  <div class="card-body">
                    <!--begin: Datatable-->
                     <table class="table table-bordered table-hover" id="kt_datatable" style="margin-top: 13px !important">
                      <thead>
                        <tr>
                          <th>Sr No.</th>
                          <th>Implemention Office</th>
                          <th>Email</th>
                          <!-- <th>Scheme Name</th> -->
                          <th>Actions</th>
                        </tr>
                      </thead>
                      <tbody>
                      @foreach ($implementations as $implementation)
                        <tr>
                          <td>{{ $implementation->id}}</td>
                          <td>{{ $implementation->name }}</td> 
                          <td>{{ $implementation->email }}</td> 
                          <!-- <td>{{ $implementation->scheme_objective }}</td>  -->
                          <!-- <td></td>  -->
                          <td><a href="<?php // URL('scheme_list/'.$scheme->draft_id) ?>" class="btn btn-primary">View</a></td> 
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