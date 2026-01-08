@extends('dashboards.gad-sec.layouts.gadsec-dash-layout')

@section('title','Approved Proposals')

@section('content')
<style>
    .content-wrapper{
        background-color: #ffb3ba;
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
                    <h5 class="text-dark font-weight-bold my-1 mr-5">{{ __('message.approved_proposal_list')}}</h5>
                    <!--end::Page Title-->                  
                  </div>
                  <!--end::Page Heading-->
                </div>
                <!--end::Info-->
              </div>
            </div>
            <!--end::Subheader-->
            <!--begin::Entry-->
                    	<!--begin::Entry-->
	<div class="d-flex flex-column-fluid" id="approved_proposals_container">
        <!--begin::Container-->
            <div class="container">
            <!--begin::Card-->
                <div class="card card-custom gutter-b">
                    <div class="card-header flex-wrap py-3">
                        <div class="card-toolbar">
                            <h5>{{ __('message.approved_proposals')}}</h5>
                        </div>
                    </div>
                    <div class="card-body">
                    <!--begin: Datatable-->
                    <table class="table table-bordered table-striped dataTable dtr-inline" id="example1">
                        <thead>
                                <tr>
                                    <th>{{ __('message.no') }}</th>
                                    <th>{{ __('message.scheme_name') }}</th>
                                    <th>{{ __('message.department_name') }}</th>
                                    <th>{{ __('message.hod_name') }}</th>
                                    <th>{{ __('message.approved_date') }}</th>
                                    <th>{{ __('message.actions') }}</th>
                                    {{-- <th>Sr No.</th>
                                    <th>Scheme Name</th>
                                    <th>Department Name</th>
                                    <th>Name of HOD</th>
                                    <th>Approved Date</th>
                                    <th>Actions</th> --}}
                                </tr>
                            </thead>
                        <tbody>
                            @php $i=1; @endphp
                            @foreach($approved_proposals as $prop)
                            @if($prop->status_id == 25)
                            <tr>
                                <td>{{ $i++ }}</td>
                                <td>{{ $prop->scheme_name }}</td>
                                <td>{{ $prop->dept_name }}</td>
                                <td>{{ hod_name($prop->draft_id) }}</td>
                                <td  width="20%">{{date('d-M-y',strtotime( $prop->created_at))}}</td>
                                <td width="23%">
                                    <a href="{{ route('schemes.newproposal_detail',Crypt::encrypt($prop->draft_id)) }}" class="btn btn-xs btn-info">{{ __('message.view') }}</a>
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
            <!--end::Entry-->
      </div>
      <!--end::Content-->
@endsection
{{-- <script src="{{asset('js/3.2.1.jquery.min.js')}}"></script> --}}
<script src="{{asset('js/jquery.min.js')}}"></script><script>
  $(document).ready(function(){
    $('.dataTable').DataTable({
          columnDefs: [
              { type: 'date', targets: 4 } 
          ]
      });
  })
</script>