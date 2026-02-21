@extends('dashboards.proposal.layouts.sidebar')
@section('title','New Proposals')
@section('content')
@php
  use Illuminate\Support\Facades\Crypt;
@endphp
  <!--begin::Content-->
    <div class="content d-flex flex-column flex-column-fluid">
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
            <div class="row justify-content-center">
              <!--begin::Container-->
              <div class="col-md-10">
                <div class="row">
                  @session('forward_to_dept_success')
                    <div class="alert alert-success" role="alert" style="display: block;width: -webkit-fill-available;text-align: -webkit-center;font-size: 19px;">
                      {{ $value }}
                    </div>
                  @endsession
                  {{-- @if(session()->has('forward_to_dept_success'))
                    <div class="alert alert-success" role="alert">
                      {{  session()->get('forward_to_dept_success') }}
                    </div>
                    @endif --}}
                </div>
                
                 <!--begin::Card-->
                 <div class="card card-custom gutter-b" style="border: 1px solid #000;">
                  <div class="card-header flex-wrap py-3">
                    <div class="card-toolbar">
                      <h5>{{ __('message.evaluation_proposals_under_preparations') }}</h5>
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
                          <th>{{ __('message.proposal_created_date') }}</th>
                          <th>{{ __('message.actions') }}</th>
                        </tr>
                      </thead>
                      <tbody>
                        @php $i=1;@endphp
                      @foreach($proposals_new as $prop)
                        <tr>
                          <td>{{ $i }}</td>
                          <td>{{ $prop->scheme_name }}</td>
                          <td>{{department_name($prop->dept_id)}}</td>
                          <td>{{ hod_name($prop->draft_id) }}</td>
                          <td width="20%" class="text-center">{{date('d-M-y',strtotime($prop->created_at))}}</td>
                          <td width="23%">
                            <a href="{{ route('schemes.newproposal_detail',Crypt::encrypt($prop->draft_id)) }}" class="btn btn-xs btn-info" style="display:inline-block">{{ __('message.view') }}</a>
                            <a href="{{ route('schemes.proposal_edit',Crypt::encrypt($prop->draft_id)) }}" class="btn btn-xs btn-primary">{{ __('message.edit') }}</a>
                            <button type="button" class="btn btn-xs btn-success" onclick="fn_new_forward_modal('{{Crypt::encrypt($prop->draft_id)}}','{{Crypt::encrypt($prop->id)}}')">{{ __('message.forward') }}</button>
                            <a href="#myModal" class="btn btn-xs btn-danger trigger-btn" data-id= "{{$prop->draft_id}}"data-bs-toggle="modal">{{ __('message.delete') }}</a>

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

<div class="modal fade" id="forward_proposal" style="display: none;" aria-hidden="true" onsubmit="$('.submit_btn').hide()">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">{{ __('message.add_remarks') }}</h4>
        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="container">
          <form id="returnScheme" method="post" action="{{ route('proposals.forwardtodept') }}" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="draft_id" id="forwd_draft_id">
            <input type="hidden" name="send_id" id="forwd_send_id">
            <div class="row form-group">
              <textarea name="remarks" class="form-control" placeholder="Add Remarks" maxlength="500"></textarea>
            </div>
            <div class="row">
                <div class="col-xl-12" style="text-align: right;">
                  <button type="submit" class="btn btn-primary submit_btn_meeting">{{ __('message.submit') }}</button>
                  <button type="button" class="btn btn-default" data-bs-dismiss="modal">{{ __('message.close') }}</button>
                </div>
              
            </div>
          </form>
        </div>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>


<div class="modal fade" id="return_proposal" style="display: none;" aria-hidden="true" onsubmit="$('.submit_btn').hide()">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Add Remarks</h4>
        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="container">
          <form id="forwardScheme" method="post" action="{{ route('deptsec.returntoia') }}" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="draft_id" id="return_draft_id">
            <!-- <input type="hidden" name="send_id" id="return_send_id"> -->
            <div class="row form-group">
              <textarea name="remarks" class="form-control" placeholder="Add Remarks" maxlength="500"></textarea>
            </div>
            <div class="row">
              <div class="col-xl-8"></div>
              <div class="col-xl-2">
                <button type="button" class="btn btn-default" data-bs-dismiss="modal">Close</button>
              </div>
              <div class="col-xl-2">
                <button type="submit" class="btn btn-primary submit_btn_meeting">Submit</button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>

<div id="myModal" class="modal fade">
	<div class="modal-dialog modal-confirm">
		<div class="modal-content">
			<div class="modal-header flex-column">
				<div class="icon-box">
					<i class="material-icons">&#xE5CD;</i>
				</div>						
				<h4 class="modal-title w-100">Are you sure?</h4>	
                <button type="button" class="close" data-bs-dismiss="modal" aria-hidden="true">&times;</button>
			</div>
			<div class="modal-body">
				<p>Do you really want to delete these records? This process cannot be undone.</p>
			</div>
			<div class="modal-footer justify-content-center">
				<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
				<button type="button" class="btn btn-danger deleteBtn" data-dept-id="">Delete</button>
			</div>
		</div>
	</div>
</div> 
{{-- <script src="{{asset('js/3.2.1.jquery.min.js')}}"></script> --}}
<script src="{{asset('js/jquery.min.js')}}"></script>

<script>

    $(document).ready(function(){

      $('.dataTable').DataTable({
          columnDefs: [
              { type: 'date', targets: 4 } 
          ]
      });

    $('.trigger-btn').on('click', function () {
      $('#myModal').modal('show');
            var id = $(this).data('id');
            var deleteBtn = $('.deleteBtn');
            var deleteId = deleteBtn.data('dept-id');
            deleteBtn.data('dept-id', id);
        });
    
    $('.deleteBtn').on('click', function () {
      $('#myModal').modal('hide');
           var id = $(this).data('dept-id');
           var encryptedDeptId = btoa(id); // Encrypt the dynamic department ID
          if (encryptedDeptId !== "") {
            const url = "{{ route('proposals.destroy', ':draft_id') }}".replace(':draft_id', encryptedDeptId); // Replace placeholder with encrypted ID
              $.ajax({
                type: 'POST',
                dataType: 'json',
                url: url,
                  headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                  },
                  success: function (response) {
                    if(response.success == true){
                      alert(response.message);
                    }else{
                        alert(response.message);
                    }
                     location.reload();
                  },
                  error: function () {
                      console.log('Error: Unable to delete department.');
                  }
              });
          }
      });
    });
 

  function fn_forward_modal(draft_id,send_id) {
    $("#forward_proposal #forwd_draft_id").val(draft_id);
    $("#forward_proposal #forwd_send_id").val(send_id);
    $("#forward_proposal").modal('show');
  }
  function fn_backward_modal(draft_id) {
    $("#return_proposal #return_draft_id").val(draft_id);
    // $("#return_proposal #return_draft_id").val(draft_id);
    $("#return_proposal").modal('show');
  }
  function fn_new_forward_modal(draft_id,send_id) {
    $("#forward_proposal #forwd_draft_id").val(draft_id);
    $("#forward_proposal #forwd_send_id").val(send_id);
    $("#forward_proposal").modal('show');
  }


</script>

@endsection