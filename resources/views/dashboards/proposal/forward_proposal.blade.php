   
@extends('dashboards.proposal.layouts.sidebar')
@section('title','Forwarded Proposals')
@section('content')
@php
use Illuminate\Support\Facades\Crypt;
@endphp
    <!--begin::Content-->
    <div class="content  d-flex flex-column flex-column-fluid">
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
               <div class="row justify-content-center">
                 <!--begin::Container-->
                 <div class="col-md-10">
                    <!--begin::Card-->
                    <div class="card card-custom gutter-b" style="border: 1px solid #000;">
                      <div class="card-header flex-wrap py-3">
                        <div class="card-toolbar">
                          <h5>{{ __('message.forward_proposal_desc') }}</h5>
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
                              <th>{{ __('message.hod_name') }}</th>
                              <th>{{ __('message.proposal_sent_to_gad') }}</th>
                              <th>{{ __('message.remarks') }}</th>
                              <th>{{ __('message.actions') }}</th>
                            </tr>
                          </thead>
                          <tbody>
                            @php 
                              $i=1;
                            @endphp
                            @foreach($proposals_forwarded as $prop)
                              @if($prop->status_id == '23'|| $prop->status_id == '26')
                              <tr>
                                <td>{{ $i++}} </td>
                                <td>{{ $prop->scheme_name }}</td> 
                                <td>{{ hod_name($prop->draft_id) }}</td>
                                <td width="20%" class="text-center">{{date('d-M-y',strtotime($prop->created_at))}}</td>
                                <td width="23%">{{ $prop->remarks }}</td>
                                <td width="23%" class="text-center">
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
{{-- <script src="{{asset('js/3.2.1.jquery.min.js')}}"></script> --}}
<script src="{{asset('js/jquery.min.js')}}"></script><script>
  $(document).ready(function(){
    $('.dataTable').DataTable({
          columnDefs: [
              { type: 'date', targets: 3 } 
          ]
      });
  })
</script>



