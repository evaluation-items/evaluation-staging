   
@extends('dashboards.gad-sec.layouts.gadsec-dash-layout')

   @section('title','Forwarded Proposals')
   
   @section('content')
   <style>
    .content-wrapper{
      background: #baffc9;
    }
   </style>
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
                       <h5 class="text-dark font-weight-bold my-1 mr-5">{{ __('message.forwarded_proposals') }}</h5>
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
                          <h5>{{ __('message.forwarded_proposals') }}</h5>
                        </div>
                      </div>
                      <div class="card-body">
                        <div class="row">
                          @if(Session::has('frwd_success'))
                            <div class="text-red">
                                
                            </div>
                          @endif
                        </div>
                        <!--begin: Datatable-->
                        <table class="table table-bordered table-striped dataTable dtr-inline" id="example1">
                          <thead>
                            <tr>
                               <th>{{ __('message.no') }}</th>
                              <th>{{ __('message.scheme_name') }}</th>
                              <th>{{ __('message.scheme_overview') }}</th>
                              <th>{{ __('message.scheme_objective') }}</th>
                              <th>{{ __('message.remarks') }}</th>
                              <th>{{ __('message.actions') }}</th>
                            </tr>
                          </thead>
                          <tbody>
                            @php 
                              $i=1;
                            // dd($the_status_id);
                            @endphp
                          @foreach($proposals_forwarded as $prop)
                            @if($prop->status_id == '23'|| $prop->status_id == '26')
                            <tr>
                              <td>{{ $i++ }} </td>
                              <td>{{ $prop->scheme_name }}</td> 
                              <td style="text-align:justify;line-height:1.5">{{ $prop->scheme_overview }}</td> 
                              <td style="text-align:justify;line-height:1.5">{{ $prop->scheme_objective }}</td>
                              <td width="23%">{{ $prop->remarks }}</td>
                              <td width="23%">
                                <a href="{{ route('schemes.newproposal_detail',Crypt::encrypt($prop->draft_id)) }}" class="btn btn-xs btn-info" style="display: inline-block">{{ __('message.view') }}</a>
                              </td>
                            </tr>
                            @endif
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


