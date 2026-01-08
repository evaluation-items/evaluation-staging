@extends('dashboards.gad-sec.layouts.gadsec-dash-layout')

@section('title','Returned Proposals')

@section('content')
<style>
  .content-wrapper{
    background: #ffdfba;
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
                  <div class="d-flex align-items-center flex-wrap mr-5">
                    <!--begin::Page Title-->
                    <h5 class="text-dark font-weight-bold my-1 mr-5">{{ __('message.proposals_returned') }} to Concern Department</h5>
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
              <div class="container">
                    <!--begin::Card-->
                    <div class="card card-custom gutter-b">
                        <div class="card-header flex-wrap py-3">
                          <div class="card-toolbar">
                            <h5>{{ __('message.gad_return_desc') }}</h5>
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
                                <th>{{ __('message.department_name') }}</th>
                                <th>{{ __('message.hod_name') }}</th>
                                <th>{{ __('message.returned_by') }}</th>
                                <th>{{ __('message.received_date') }}</th>
                                <th>{{ __('message.remarks') }}</th>
                                <th>{{ __('message.actions') }}</th>

                                {{-- <th>Sr No.</th>
                                <th>Scheme Name</th>
                                <th>Department Name</th>
                                <th>Name of HOD</th>
                                <th>Returned By</th>
                                <th>Received Date</th>
                                <th>Remarks</th>
                                <th>Actions</th> --}}
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
                                <td>{{ department_name($prop->dept_id) }}</td>
                                <td>{{ hod_name($prop->draft_id) }}</td>
                                <td>{{ $prop->created_by }}</td>
                                <td  width="15%">{{date('d-M-y',strtotime( $prop->created_at))}}</td>
                                <td>{{ $prop->remarks}}</td>
                                <td width="20%">
                                <a href="{{ route('schemes.newproposal_detail',Crypt::encrypt($prop->draft_id)) }}" class="btn btn-xs btn-info">View</a>
                                  @if($prop->prop_status_id == 27  and $prop->forward_btn_show == 1)
                                  <button type="submit" class="btn btn-xs btn-danger" onclick="fn_backward_modal_eval('{{Crypt::encrypt($prop->draft_id)}}','{{Crypt::encrypt($prop->id)}}')">{{ __('message.forward_for_evaluation') }}</button>
                                
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
            </div>
            <!--end::Content-->
           
            
<div class="modal fade" id="forward_proposal_eval" style="display: none;" aria-hidden="true">
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
          <form id="forwardSchemeEvl" method="post" action="{{ route('gadsec.forwardtoeval') }}" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="draft_id" id="return_draft_id">
            <input type="hidden" name="send_id" id="return_send_id">
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
            
{{-- <script src="{{asset('js/3.2.1.jquery.min.js')}}"></script> --}}
<script src="{{asset('js/jquery.min.js')}}"></script>
<script type="text/javascript">
  function fn_backward_modal_eval(back_draft_id,back_send_id) {
    $("#forward_proposal_eval #return_draft_id").val(back_draft_id);
    $("#forward_proposal_eval #return_send_id").val(back_send_id);
    $("#forward_proposal_eval").modal('show');
  }
</script>
<script>
  $(document).ready(function(){
    $('.dataTable').DataTable({
          columnDefs: [
              { type: 'date', targets: 5 } // Target the first column (Assigned Date) for date sorting
          ]
      });
  })
</script>
@endsection
