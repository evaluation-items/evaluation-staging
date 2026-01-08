@extends('dashboards.eva-dir.layouts.evaldir-dash-layout')
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
                  <div class="card-body table-responsive">
                    <!--begin: Datatable-->
                    <table class="table table-bordered table-striped  dataTable dtr-inline" id="example1">
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
                        <td width="20%" class="text-center">{{!is_null($prop->evaluation_sent_date) ? date('d-M-y',strtotime($prop->evaluation_sent_date)) : date('d-M-y',strtotime($prop->created_at)) }}</td>
                        <td width="28%">
                          <a href="{{ route('schemes.newproposal_detail',Crypt::encrypt($prop->draft_id)) }}" class="btn btn-xs btn-info">{{ __('message.view') }}</a>
                          <button type="button" class="btn btn-xs btn-success text-wrap approved_btn" data-id="{{Crypt::encrypt($prop->id)}}" data-draft-id="{{Crypt::encrypt($prop->draft_id)}}" data-dept-id="{{ Crypt::encrypt($prop->dept_id)}}" style="display:inline-block">{{ __('message.approve_branch') }}</button>
                          {{-- <button type="button" class="btn btn-xs btn-success text-wrap" onclick="add_team('{{ $prop->draft_id }}','{{ $prop->id }}','{{ $prop->dept_id }}')"  style="display:inline-block">Assign to Branch</button> --}}
                          <button type="button" class="btn btn-xs btn-danger" onclick="fn_backward_modal('{{ Crypt::encrypt($prop->draft_id) }}','{{ Crypt::encrypt($prop->id) }}','{{ Crypt::encrypt($prop->dept_id) }}')" style="display:inline-block">{{ __('message.return_to_gad') }}</button>
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
<div class="modal fade" id="return_proposal" style="display: none;" aria-hidden="true" onsubmit="$('.submit_btn').hide()">
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
            <form id="returnScheme" method="post" action="{{ route('evaldir.returntogad') }}" enctype="multipart/form-data">
              @csrf
              <input type="hidden" name="draft_id" id="return_draft_id">
              <input type="hidden" name="send_id" id="return_send_id">
              <input type="hidden" name="dept_id" id="return_dept_id">
              <div class="row form-group">
                <textarea name="remarks" class="form-control remarks" placeholder="Add Remarks" maxlength="500"></textarea>
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


  <div class="modal fade" id="add_team" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title text-center">{{ __('message.proposal_assign_to_branch') }}</h4>
          <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="container">
            {{-- <form method="post" action="{" enctype="multipart/form-data" id="evaldir_addteam">
              @csrf --}}
              <form id="assignDDFrm" method="post" action="{{ route('evaldir.sendschemetodd') }}" enctype="multipart/form-data">
                @csrf
              <div id="first_dd" style="display:none;"></div>
              <div id="first_ro" style="display:none;"></div>
              <input type="hidden" name="draft_id" id="return_draft_id">
              <input type="hidden" name="send_id" id="return_send_id">
              <input type="hidden" name="dept_id" id="return_dept_id">
              <div class="row form-group">
                <label for="teamname">{{ __('message.add_remarks') }} <span class="required_filed"> * </span></label>
                  <textarea name="remarks" class="form-control remarks" placeholder="Add Remarks" maxlength="500"></textarea>
              </div>
              <div class="row form-group">
                <!-- <label for="team_members" class="col-xl-12">{{ __('message.team_member_dd') }}</label> -->
                <!-- <div class="col-xl-12" id="add_team_members_dd_div"> -->
                <label for="choices-multiple-remove-button" class="col-xl-12">{{ __('message.team_member_dd') }} <span class="required_filed"> * </span> </label>
                <select id="choices-multiple-remove-button" name="branch" class="form-control" placeholder="Add Branch" style="min-width:100% !important">
                  <option value="">{{ __('message.select_branch') }}</option>
                  @foreach($branch_list as $attkey => $branch)
                    <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                  @endforeach
                </select>
                <!-- </div> -->
              </div>
              <div class="row" id="the_second_last_div">
               
                <div class="col-xl-12" style="text-align: right;">
                  <!-- <button type="submit" class="btn btn-primary add_submit_btn_meeting" style="display:none">Submit</button> -->
                  <button type="submit" class="btn btn-primary add_submit_btn_meeting">{{ __('message.submit') }}</button>
                  <button type="button" class="btn btn-default add_close_btn_meeting" data-bs-dismiss="modal">{{ __('message.close') }}</button>
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

  {{-- <script src="{{asset('js/3.2.1.jquery.min.js')}}"></script> --}}
  <script src="{{asset('js/jquery.min.js')}}"></script>
<script src="{{asset('js/sweetalert2.min.js')}}"></script>
<script type="text/javascript">
 $(document).ready(function() {
      $('.dataTable').DataTable({
          columnDefs: [
              { type: 'date', targets: 4 } // Target the first column (Assigned Date) for date sorting
          ]
      });
  });
function fn_backward_modal(back_draft_id,back_send_id,dept_id) {
      $("#return_proposal #return_draft_id").val(back_draft_id);
      $("#return_proposal #return_send_id").val(back_send_id);
      $("#return_proposal #return_dept_id").val(dept_id);
      $("#return_proposal").modal('show');
}
function assign_to_gad(back_draft_id,back_send_id,dept_id) {
    $("#assign_to_gad #return_draft_id").val(back_draft_id);
    $("#assign_to_gad #return_send_id").val(back_send_id);
    $("#assign_to_gad #return_dept_id").val(dept_id);
}

$(document).ready(function(){ 
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    var multipleCancelButton = new Choices('#choices-multiple-remove-button', {
        removeItemButton: true,
        maxItemCount:100,
        searchResultLimit:500,
        renderChoiceLimit:100
    });
  var ktcontent = $("#example1").height();
  $('.numberonly').keypress(function (e) {
    var charCode = (e.which) ? e.which : event.keyCode;
    if (String.fromCharCode(charCode).match(/[^0-9]/g))
    return false;
  });   
  $("#add_remarks").click(function(){
    var theid = $(this).val();
    $("#get_the_id").val(theid);
    $("#pendingremarks").modal('show');
  }); 

    $('.approved_btn').on('click',function(){
      var id = $(this).data('id');
      var draft_id = $(this).data('draft-id');
      var dept_id = $(this).data('dept-id');
     
      Swal.fire({
        title: "Are you sure?",
        text: "You will approve this branch!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, Aprrove it!"
      }).then((result) => {
        if (result.isConfirmed) {
          if(id != "" && draft_id != "" && dept_id != ""){
                $.ajax({
                url: "{{route('evaldir.approve_proposal')}}",
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
                      text: "Your branch has been approved.",
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
