@extends('dashboards.admins.layouts.admin-dash-layout')
@section('title','New Proposals')
<link rel="stylesheet" href="{{asset('css/choices.min.css')}}">
<link href="{{asset('css/sweetalert2.min.css')}}" rel="stylesheet">
<script src="{{asset('js/choices.min.js')}}"></script>
@section('content')
<style>
  .content-wrapper{
    background-color: #8ddce9;
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
                  <div class="d-flex justify-content-center align-items-center">
                    <!--begin::Page Title-->
                    <h5 class="text-dark font-weight-bold">{{ __('message.new_proposals') }}</h5>
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
                      <h5>{{ __('message.gad_desc') }}</h5>
                    </div>
                  </div>
                  <div class="row">
                      @if(Session::has('forward_to_gad_success'))
                        <div class="text-red">
                          
                        </div>
                      @endif
                  </div>
                  <div class="card-body">
                    <!--begin: Datatable-->
                    <table class="table table-bordered table-striped dataTable dtr-inline" id="example1">
                      <thead>
                        <tr>
                          <th width="8%">{{ __('message.no') }}</th>
                          <th>{{ __('message.scheme_name') }}</th>
                          <th>{{ __('message.department_name') }}</th>
                          <th>{{ __('message.hod_name') }}</th>
                          <th>{{ __('message.received_date_from_gad') }}</th>
                          <th>{{ __('message.actions') }}</th>

                          {{-- <th width="8%">Sr No.</th>
                          <th>Scheme Name</th>
                          <th>Department Name</th>
                          <th>Name of HOD</th>
                          <th>Received Date From GAD</th>
                          <th>Actions</th> --}}
                        </tr>
                      </thead>
                      <tbody>
                        @php $i=1; @endphp
                        @foreach($new_proposals as $prop)
                          <tr>
                          <td>{{ $i++ }}</td>
                          <td>{{ proposal_name($prop->draft_id) }}</td>
                          <td>{{ department_name($prop->dept_id) }}</td>
                          <td>{{ hod_name($prop->draft_id) }}</td>
                          <td width="20%" class="text-center">{{!is_null($prop->evaluation_sent_date) ? date('d-M-y',strtotime($prop->evaluation_sent_date)) :  date('d-M-y',strtotime($prop->created_at)) }}</td>
                          <td width="28%">
                            <a href="{{ route('schemes.proposal_detail',[Crypt::encrypt($prop->draft_id),Crypt::encrypt($prop->id)]) }}" class="btn btn-xs btn-info">{{ __('message.view') }}</a>
                            <button type="button" class="btn btn-xs btn-success text-wrap approved_btn" data-id="{{Crypt::encrypt($prop->id)}}" data-draft-id="{{Crypt::encrypt($prop->draft_id)}}" data-dept-id="{{ Crypt::encrypt($prop->dept_id)}}" style="display:inline-block">{{ __('message.branch_send_to_jd')}}</button>
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
{{-- <script src="{{asset('js/3.2.1.jquery.min.js')}}"></script> --}}
<script src="{{asset('js/jquery.min.js')}}"></script>
<script src="{{asset('js/sweetalert2.min.js')}}"></script>
<script type="text/javascript">

$(document).ready(function(){ 
  $('.dataTable').DataTable({
          columnDefs: [
              { type: 'date', targets: 4 } 
          ]
      });
    // Initial table update
    $('.approved_btn').on('click',function(){
      var id = $(this).data('id');
      var draft_id = $(this).data('draft-id');
      var dept_id = $(this).data('dept-id');
     
      Swal.fire({
        title: "Are you sure?",
        text: "You will sent this branch to junior Director!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, Sent it!"
      }).then((result) => {
        if (result.isConfirmed) {
          if(id != "" && draft_id != "" && dept_id != ""){
                $.ajax({
                url: "{{route('admin.approve_scheme')}}",
                method: 'POST',
                data: {
                    id: id,
                    draft_id: draft_id,
                    dept_id: dept_id,
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                  if(response.success == true){
                      Swal.fire({
                      title: "Aprroved!",
                      text: "Your branch has sent successfull.",
                      icon: "success"
                    }).then(okay => {
                        if (okay) {
                          window.location.reload();
                        }
                    });
                  }
                 
                },
                error: function(error) {
                    console.log(error);
                }
            });
          }else{
            alert('Something went wrong');
          }
        }
      });
    });
});

</script>
@endsection
