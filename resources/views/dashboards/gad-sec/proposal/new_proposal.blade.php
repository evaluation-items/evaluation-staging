@extends('dashboards.gad-sec.layouts.gadsec-dash-layout')

@section('title','New Proposals')

@section('content')
<style>
	.content-wrapper{
	  background-color: #8ddce9;
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
                    <h5 class="text-dark font-weight-bold">{{ __('message.new_proposals') }} Receive from Concern Department</h5>
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
				<div class="row">
					@if(session()->has('forward_to_dept_success'))
					  <div class="alert alert-success" role="alert">
						{{  session()->get('forward_to_dept_success') }}
					  </div>
					  @endif
				  </div>
                 <!--begin::Card-->
                 <div class="card card-custom gutter-b">
                  <div class="card-header flex-wrap py-3">
                    <div class="card-toolbar">
						<h5>{{ __('message.gad_desc') }}</h5>
                    </div>
                  </div>
                  
                  <div class="card-body">
                    <!--begin: Datatable-->
                    <table class="table table-bordered table-striped dataTable dtr-inline" id="example1">
						<thead>
                        <tr>
							<th  width="8%">{{ __('message.no') }}</th>
							<th>{{ __('message.scheme_name') }}</th>
							<th>{{ __('message.department_name') }}</th>
							<th>{{ __('message.hod_name') }}</th>
							<th>{{ __('message.received_date') }}</th>
							<th>{{ __('message.actions') }}</th>
                        </tr>
                      </thead>
                      <tbody>
                        @php $i=1; @endphp
                        @foreach($new_proposals as $prop)
                        @if($prop->status_id == 23)
                        <tr>
                          <td>{{ $i++}}</td>
						  <td>{{ proposal_name($prop->draft_id) }}</td>
						  <td>{{ department_name($prop->dept_id) }}</td>
						  <td>{{ hod_name($prop->draft_id) }}</td>
						  <td width="20%" class="text-center">{{date('d-M-y',strtotime($prop->created_at))}}</td>
                          <td width="23%">
                          	<a href="{{ route('schemes.newproposal_detail',Crypt::encrypt($prop->draft_id)) }}" class="btn btn-xs btn-info">{{ __('message.view') }}</a>
                            @if($prop->prop_status_id == 23 and $prop->forward_btn_show == 1)
                            <button type="submit" class="btn btn-xs btn-success" style="margin-top:49px;margin-bottom:49px;" onclick="fn_forward_modal('{{Crypt::encrypt($prop->draft_id)}}','{{Crypt::encrypt($prop->id)}}')">GAD sent scheme to Evaluation Office</button>
                            <button type="submit" class="btn btn-xs btn-danger" onclick="fn_backward_modal('{{Crypt::encrypt($prop->draft_id)}}','{{Crypt::encrypt($prop->id)}}')">{{ __('message.return') }}</button>
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



<div class="modal fade" id="forward_proposal" style="display: none;" aria-hidden="true" onsubmit="$('.submit_btn').hide()">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">{{ __('message.add_remarks') }}</h4>
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
				<h4 class="modal-title">{{ __('message.add_remarks') }}</h4>
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

@endsection

{{-- <script src="{{asset('js/3.2.1.jquery.min.js')}}"></script> --}}
<script src="{{asset('js/jquery.min.js')}}"></script>

<script type="text/javascript">
	$(document).ready(function () {			
	$('.dataTable').DataTable({
          columnDefs: [
              { type: 'date', targets: 4 } // Target the first column (Assigned Date) for date sorting
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

</script>
