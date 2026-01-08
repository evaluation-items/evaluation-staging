@extends('dashboards.eva-dd.layouts.evaldd-dash-layout')
@section('title','New Proposals')
<link rel="stylesheet" href="{{asset('css/choices.min.css')}}">

@section('content')
<style>
	.content-wrapper{
	  background-color: #8ddce9;
	}
  .disabled {
        pointer-events: none;
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
                          <th>{{ __('message.no') }}</th>
                          <th>{{ __('message.evaluation_study') }}</th>
                          {{-- <th>{{ __('message.evaluation_study') }}</th> --}}
                          <th>{{ __('message.hod_name') }}</th>
                          <th>{{ __('message.received_date') }}</th>
                          <th>{{ __('message.actions') }}</th>
                        </tr>
                      </thead>
                      <tbody>
                       
                          @php $i = 1; @endphp
                          @foreach($proposals as $propkey => $prop)
                            @php
                                $user_ids = [];
                                if (!is_null($prop->team_member_dd)) {
                                    $role = explode(',', $prop->team_member_dd);
                                    $user_ids = App\Models\User::whereIn('role', $role)->pluck('id')->toArray();
                                }
                            @endphp
                            @if (empty($user_ids) || in_array(Auth::user()->id, $user_ids))
                                  <tr>
                                      <td>{{ $i++ }}</td>
                                      <td>{{ $prop->scheme_name }}</td>
                                      <td>{{ hod_name($prop->draft_id) }}</td>
                                      <td class="text-center">{{ date('d-M-y', strtotime($prop->created_at)) }}</td>
                                      <td width="20%">
                                          <a href="{{ route('schemes.newproposal_detail', Crypt::encrypt($prop->draft_id)) }}" class="btn btn-xs btn-info">{{ __('message.view') }}</a>
                                          @if (Auth::check() && Auth::user()->role_manage == 4 && Auth::user()->role == 38)
                                              @if (!empty($user_ids) || in_array(Auth::user()->id, $user_ids))
                                                <a href="{{ route('stages.create', ['draft_id' => $prop->draft_id]) }}" class="btn btn-xs btn-info {{ $prop->approved == 1 ? '' : 'disabled' }}" >{{ __('message.add_stage') }}</a>
                                              @else
                                                <button type="button" class="btn btn-xs btn-success text-wrap" onclick="add_team('{{ Crypt::encrypt($prop->draft_id) }}', '{{ Crypt::encrypt($prop->id) }}', '{{ Crypt::encrypt($prop->dept_id) }}')" style="display:inline-block">{{ __('message.assign_to_branch') }}</button>
                                              @endif
                                          @else
                                              <a href="{{ route('stages.create', ['draft_id' => $prop->draft_id]) }}" class="btn btn-xs btn-info">{{ __('message.add_stage') }}</a>
                                          @endif
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

<!-- Assign to Branch -->
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
              <form id="assignDDFrm" method="post" action="{{ route('evaldd.sendschemetodd') }}" enctype="multipart/form-data">
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
                <!-- <label for="team_members" class="col-xl-12">Team Member DD</label> -->
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
@endsection

{{-- <script src="{{asset('js/3.2.1.jquery.min.js')}}"></script> --}}
<script src="{{asset('js/jquery.min.js')}}"></script>
<script src="{{asset('js/choices.min.js')}}"></script>

<script type="text/javascript">
	$(document).ready(function () {
		
      $('.dataTable').DataTable({
          columnDefs: [
              { type: 'date', targets: 3 } // Target the first column (Assigned Date) for date sorting
          ]
      });
  
		$('.numberonly').keypress(function (e) {
			var charCode = (e.which) ? e.which : event.keyCode;
			if (String.fromCharCode(charCode).match(/[^0-9]/g))
				return false;
		});
		
		setTimeout(function() {
			var winheight = Number($("#complete_proposal").height()) +  Number($("#approved_proposals_container").height()) + Number($("#returned_proposals_container").height()) + Number($("#new_proposals_container").height())  +  Number($("#ongoing_proposals_container").height())  + Number($('#kt_subheader').height()) + 40;
			$(".content-wrapper").removeAttr('style');
		    $(".content-wrapper").css('min-height',winheight);
			$('#kt_datatable_length select').change(function() {
				var sizeofdatatable = $(this).val();
				alert($("#approved_proposals_table tbody tr").height());
			});
		},500);
	});
	
	//Assign Branch
	function add_team(back_draft_id,back_send_id,dept_id) {
		$('.choices').width('100%');
		$("#add_team").modal('show');
		$("#add_team #return_draft_id").val(back_draft_id);
		$("#add_team #return_send_id").val(back_send_id);
		$("#add_team #return_dept_id").val(dept_id);
	}
	$(window).on('load',function() {
		$(".choices").css('width','100% !important');
		$(".choices__inner").css('background-color','#FFFFFF');
	});
</script>
