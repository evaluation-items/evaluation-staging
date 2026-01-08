@extends('dashboards.gad-sec.layouts.gadsec-dash-layout')
@section('title','Proposal - GAD Sec.')

@section('content')
<!--begin::Content-->
<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
<!--begin::Subheader-->
	<div class="subheader py-2 py-lg-6 subheader-solid " id="kt_subheader">
		<div class=" container-fluid d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
		<!--begin::Info-->
			<div class="d-flex align-items-center flex-wrap mr-1">
			<!--begin::Page Heading-->
				<div class="d-flex align-items-baseline flex-wrap mr-5">
				<!--begin::Page Title-->
					<h5 class="text-dark font-weight-bold my-1 mr-5">Proposal's List</h5>
				<!--end::Page Title-->
				</div>
			<!--end::Page Heading-->
			</div>
		<!--end::Info-->
		</div>
	</div>
	<!--end::Subheader-->

	<!--begin::Entry-->
	<div class="d-flex flex-column-fluid" id="new_proposals_container">
		<!--begin::Container-->
			<div class=" container ">
			<!--begin::Card-->
				<div class="card card-custom gutter-b">
					<div class="card-header flex-wrap py-3">
						<div class="card-toolbar">
							<h5>New Proposals Received</h5>
						</div>
					</div>
					<div class="card-body">
						<div class="row">
							@if(Session::has('forward_to_gad_success'))
							  <div class="text-red">
								  
							  </div>
							@endif
						  </div>
					<!--begin: Datatable-->
						<table class="table table-bordered table-hover show-datatable" id="kt_datatable" style="margin-top: 13px !important">
							<thead>
								<tr>
									<th>Sr No.</th>
									<th>Scheme Name</th>
									<th>Department Name</th>
									<th>Scheme Objective</th>
									<th>Received Date</th>
									<th>Actions</th>
								</tr>
							</thead>
							<tbody>
								@php $i=1; @endphp
								@foreach($new_proposals as $prop)
								@if($prop->status_id == 23)
								<tr>
									<td>{{ $i }}</td>
									<td>{{ $prop->scheme_name }}</td>
									<td>{{ $prop->dept_name }}</td>
									<td style="text-align:justify;line-height:1.5">{{ $prop->scheme_objective }}</td>
									<td  width="20%">{{date('M d Y',strtotime( $prop->created_at))}}</td>
									<td width="23%">
									<a href="{{ route('gadsec.proposal_detail',$prop->draft_id) }}" class="btn btn-xs btn-info">View</a>
									<?php //dd($user_id); ?>
									
										@if($prop->prop_status_id == 23 and $prop->forward_btn_show == 1)
										<button type="submit" class="btn btn-xs btn-success" onclick="fn_forward_modal('{{$prop->draft_id}}','{{$prop->id}}')">Approve</button>
										<button type="submit" class="btn btn-xs btn-danger" onclick="fn_backward_modal('{{$prop->draft_id}}','{{$prop->id}}')">Return</button>
										{{--
										@elseif($prop->prop_status_id == 24)
											Returned
										@else
											Forwarded
										--}}
										@endif
									
									</td>
								</tr>
								@php $i++; @endphp
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

	<!--begin::Entry-->
	<div class="d-flex flex-column-fluid" id="ongoing_proposals_container">
		<!--begin::Container-->
			<div class=" container ">
			<!--begin::Card-->
				<div class="card card-custom gutter-b">
					<div class="card-header flex-wrap py-3">
						<div class="card-toolbar">
							<h5>Ongoing Evaluation Studies </h5>
						</div>
					</div>
					<div class="card-body">
					<!--begin: Datatable-->
					<table class="table table-bordered table-hover show-datatable" id="kt_datatable" style="margin-top: 13px !important">
						<thead>
								<tr>
									<th>Sr No.</th>
									<th>Scheme Name</th>
									<th>Created Date</th>
									<th>Actions</th>
								</tr>
							</thead>
						<tbody>
							@php $i=1; @endphp
							@foreach($ongoing_proposal as $prop)
							
							  <tr>
								<td>{{ $i }} </td>
								<td>{{ SchemeName($prop->scheme_id) }}</td> 
								<td>{{ !empty($prop->created_at) ?  date('M d Y',strtotime($prop->created_at)) : '-'}}</td> 
								<td width="23%">
								  {{-- <a href="{{ route('schemes.proposal_detail',[$prop->draft_id,$prop->id]) }}" class="btn btn-xs btn-info" style="display: inline-block">View Scheme</a> --}}
								 @if(App\Models\Stage::where('scheme_id',$prop->scheme_id)->latest()->first())
								  <a href="{{ route('stages.show', ['stage' => $prop->scheme_id]) }}" class="btn btn-xs btn-info" style="display: inline-block">View Stage</a>
								  @endif
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
		
		<!--begin::Entry-->
	<div class="d-flex flex-column-fluid" id="returned_proposals_container">
		<!--begin::Container-->
			<div class=" container ">
			<!--begin::Card-->
				<div class="card card-custom gutter-b">
					<div class="card-header flex-wrap py-3">
						<div class="card-toolbar">
							<h5>Returned Proposals</h5>
						</div>
					</div>
					<div class="card-body">
					<!--begin: Datatable-->
						<table class="table table-bordered table-hover show-datatable" id="kt_datatable" style="margin-top: 13px !important">
							<thead>
								<tr>
									<th>Sr No.</th>
									<th>Scheme Name</th>
									<th>Department Name</th>
									{{-- <th>Scheme Objective</th> --}}
									<th>Returned By</th>
									<th>Received Date</th>
									<th>Remarks</th>
									<th>Actions</th>
								</tr>
							</thead>
							<tbody>
								@php $i=1;
								
								@endphp
								@foreach($returned_proposals as $prop)
								@if($prop->status_id == 24 or $prop->status_id == 26 or $prop->status_id == 27)
								<tr>
									<td>{{ $i }}</td>
									<td>{{ $prop->scheme_name }}</td>
									<td>{{ $prop->dept_name }}</td>
									{{-- <td style="text-align:justify;line-height:1.5">{{ $prop->scheme_objective }}</td> --}}
									<td>{{ $prop->created_by }}</td>
									<td  width="15%">{{date('M d Y',strtotime( $prop->created_at))}}</td>
									<td>{{ $prop->remarks}}</td>
									<td width="20%">
									<a href="{{ route('gadsec.proposal_detail',$prop->draft_id) }}" class="btn btn-xs btn-info">View</a>
									
									{{-- @if(auth()->user()->role == 5) --}}
										@if($prop->prop_status_id == 27  and $prop->forward_btn_show == 1)
										<button type="submit" class="btn btn-xs btn-danger" onclick="fn_backward_modal_eval('{{$prop->draft_id}}','{{$prop->id}}')">Forward for Evaluation</button>
										{{--
										@elseif($prop->prop_status_id == 24)
											Returned
										@else
											Forwarded
										--}}
										@endif
									{{-- @endif --}}
									</td>
								</tr>
								@php $i++; @endphp
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

	<!--begin::Entry-->
	<div class="d-flex flex-column-fluid" id="approved_proposals_container">
	<!--begin::Container-->
		<div class="container">
		<!--begin::Card-->
			<div class="card card-custom gutter-b">
				<div class="card-header flex-wrap py-3">
					<div class="card-toolbar">
						<h5>Approved Proposals</h5>
					</div>
				</div>
				<div class="card-body">
				<!--begin: Datatable-->
					<table class="table table-bordered table-hover show-datatable approved_proposals_table" id="kt_datatable" style="margin-top: 13px !important">
						<thead>
							<tr>
								<th>Sr No.</th>
								<th>Scheme Name</th>
								<th>Department Name</th>
								<th>Scheme Objective</th>
								<th>Approved Date</th>
								<th>Actions</th>
							</tr>
						</thead>
					<tbody>
						@php $i=1; @endphp
						@foreach($approved_proposals as $prop)
						@if($prop->status_id == 25)
						<tr>
							<td>{{ $i }}</td>
							<td>{{ $prop->scheme_name }}</td>
							<td>{{ $prop->dept_name }}</td>
							<td style="text-align:justify;line-height:1.5">{{ $prop->scheme_objective }}</td>
							<td  width="20%">{{date('M d Y',strtotime( $prop->created_at))}}</td>
							<td width="23%">
							<a href="{{ route('gadsec.proposal_detail',$prop->draft_id) }}" class="btn btn-xs btn-info">View</a>
							<?php //dd($user_id); ?>
							
							</td>
						</tr>
						@php $i++; @endphp
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

	<!--begin::Entry-->
	<div class="d-flex flex-column-fluid" id="complete_proposal">
		<!--begin::Container-->
			<div class="container">
			<!--begin::Card-->
				<div class="card card-custom gutter-b">
					<div class="card-header flex-wrap py-3">
						<div class="card-toolbar">
							<h5>Completed Evaluation Studies (PDFs)</h5>
						</div>
					</div>
					<div class="card-body">
					<!--begin: Datatable-->
						<table class="table table-bordered table-hover show-datatable approved_proposals_table" id="kt_datatable" style="margin-top: 13px !important">
							<thead>
								<tr>
									<th>Sr No.</th>
									<th>Scheme Name</th>
									<th>Completed Date</th>
									<th>Actions</th>
								</tr>
							</thead>
							<tbody>
									@php $i=1; @endphp
									@foreach($complted_proposal as $complete)
									<tr>
										<td>{{ $i }}</td>
										<td>{{ SchemeName($complete->scheme_id) }}</td> 
										<td  width="20%">{{date('M d Y',strtotime( $complete->updated_at))}}</td>
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



<div class="modal fade" id="forward_proposal" style="display: none;" aria-hidden="true" onsubmit="$('.submit_btn').hide()">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">Add Remarks</h4>
				<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
			</div>
			<div class="modal-body">
				<div class="container">
					<form id="forwardScheme" method="post" action="{{ route('gadsec.gadtoeval') }}" enctype="multipart/form-data">
					@csrf
						<input type="hidden" name="draft_id" id="forwd_draft_id" value="">
						<input type="hidden" name="send_id" id="forwd_send_id" value="">
						<div class="row form-group">
							<textarea name="remarks" class="form-control" placeholder="Add Remarks" maxlength="500"></textarea>
						</div>
						<div class="row">
						
							<div class="col-xl-12" style="text-align: right;">
								<button type="submit" class="btn btn-primary submit_btn_meeting">Submit</button>
								<button type="button" class="btn btn-default" data-bs-dismiss="modal">Close</button>
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

<div class="modal fade" id="forward_proposal_eval" style="display: none;" aria-hidden="true" onsubmit="$('.submit_btn').hide()">
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
					<form id="forwardSchemeEvl" method="post" action="{{ route('gadsec.forwardtoeval') }}" enctype="multipart/form-data">
						@csrf
						<input type="hidden" name="draft_id" id="return_draft_id">
						<input type="hidden" name="send_id" id="return_send_id">
						<div class="row form-group">
							<textarea name="remarks" class="form-control" placeholder="Add Remarks" maxlength="500"></textarea>
						</div>
						<div class="row">
							<div class="col-xl-12" style="text-align: right;">
								<button type="submit" class="btn btn-primary submit_btn_meeting">Submit</button>
								<button type="button" class="btn btn-default" data-bs-dismiss="modal">Close</button>
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
					<form id="returnScheme" method="post" action="{{ route('gadsec.returntodept') }}" enctype="multipart/form-data">
						@csrf
						<input type="hidden" name="draft_id" id="return_draft_id">
						<input type="hidden" name="send_id" id="return_send_id">
						<div class="row form-group">
							<textarea name="remarks" class="form-control" placeholder="Add Remarks" maxlength="500"></textarea>
						</div>
						<div class="row">
							<div class="col-xl-9"></div>
							
							<div class="col-xl-12" style="text-align: right;">
								<button type="submit" class="btn btn-primary submit_btn_meeting">Submit</button>
								<button type="button" class="btn btn-default" data-bs-dismiss="modal">Close</button>
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
<script>
function frwdeval(val,uid,uname,sid,did,stid) {
	$.ajax({
		type: "post",
		url: "{{ url('eval') }}",
		data: {
			"_token": "{{ csrf_token() }}",
			user_id:uid,
			dept_id:did,
			draft_id:sid,
			created_by:uname,
			status_id:stid
		},
		success: function(response) {
			alert("Successfull Data");
			location.reload();
		}
	});
}
</script>
<script type="text/javascript">
	$(document).ready(function () {
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
	function fn_forward_modal(draft_id,send_id) {
	//	alert(draft_id);
		$("#forward_proposal #forwd_draft_id").val(draft_id);
		$("#forward_proposal #forwd_send_id").val(send_id);
		$("#forward_proposal").modal('show');
	}
	function fn_backward_modal(back_draft_id,back_send_id) {
		$("#return_proposal #return_draft_id").val(back_draft_id);
		$("#return_proposal #return_send_id").val(back_send_id);
		$("#return_proposal").modal('show');
	}
	function fn_backward_modal_eval(back_draft_id,back_send_id) {
		$("#forward_proposal_eval #return_draft_id").val(back_draft_id);
		$("#forward_proposal_eval #return_send_id").val(back_send_id);
		$("#forward_proposal_eval").modal('show');
	}
</script>

