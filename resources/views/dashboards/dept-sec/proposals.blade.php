@extends('dashboards.dept-sec.layouts.deptsec-dash-layout')
@section('title','Proposals - Dept Sec')

@section('content')
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
                    <h5 class="text-dark font-weight-bold my-1 mr-5">Proposal List</h5>
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
                      <h5>Returned Proposals</h5>
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
                      <table class="table table-bordered table-hover show-datatable" id="kt_datatable" style="margin-top: 13px !important">
                      <thead>
                        <tr>
                          <th>Sr No.</th>
                          <th>Scheme Name</th>
                          <th>Scheme Overview</th>
                          <th>Scheme Objective</th>
                          <th>Sub Scheme</th>
                          <!-- <th>Financial Adviser</th> -->
                          <th>Actions</th>
                        </tr>
                      </thead>
                      <tbody>
                        @php $i=1; @endphp
                      @foreach($proposals_returned as $prop)
                        @if($prop->status_id == $status_id_return or $prop->status_id == '26')
                        <tr>
                          <td>{{ $i }}</td>
                          <td>{{ $prop->scheme_name }}</td> 
                          <td style="text-align:justify;line-height:1.5">{{ $prop->scheme_overview }}</td> 
                          <td style="text-align:justify;line-height:1.5">{{ $prop->scheme_objective }}</td>
                          <td>{{ $prop->sub_scheme }}</td> 
                          <td width="23%">
                            <a href="{{ route('deptsec.proposal_detail',[$prop->draft_id,$prop->id]) }}" class="btn btn-xs btn-info" style="display: inline-block">View</a>
                            <a href="{{ route('deptsec.proposal_edit',$prop->draft_id) }}" class="btn btn-xs btn-primary">Edit</a>
                            <button type="button" class="btn btn-xs btn-success" onclick="fn_forward_modal('{{$prop->draft_id}}','{{$prop->id}}')">Forward</button>
                            <!-- <button type="button" class="btn btn-xs btn-danger" onclick="fn_backward_modal('{{--$prop->draft_id--}}','{{--$prop->id--}}')">Backwrd</button> -->
                          </td>
                        </tr>
                        @endif
                        @php $i++; @endphp
                        @endforeach
                      </tbody>
                    </table> 
                    <!--end: Datatable-->
                  </div>
                </div>
                <!--end::Card-->

                <!--begin::Card-->
                <div class="card card-custom gutter-b">
                  <div class="card-header flex-wrap py-3">
                    <div class="card-toolbar">
                      <h5>Forwarded Proposals</h5>
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
                     <table class="table table-bordered table-hover show-datatable" id="kt_datatable" style="margin-top: 13px !important">
                      <thead>
                        <tr>
                          <th>Sr No.</th>
                          <th>Scheme Name</th>
                          <th>Scheme Overview</th>
                          <th>Scheme Objective</th>
                          <th>Sub Scheme</th>
                          <!-- <th>Financial Adviser</th> -->
                          <th>Actions</th>
                        </tr>
                      </thead>
                      <tbody>
                        @php $i=1; @endphp
                      @foreach($proposals_forwarded as $prop)
                        @if($prop->status_id == $the_status_id || $prop->status_id == '26')
                        <tr>
                          <td>{{ $i }} </td>
                          <td>{{ $prop->scheme_name }}</td> 
                          <td style="text-align:justify;line-height:1.5">{{ $prop->scheme_overview }}</td> 
                          <td style="text-align:justify;line-height:1.5">{{ $prop->scheme_objective }}</td>
                          <td style="text-align:justify;line-height:1.5">{{ $prop->sub_scheme }}</td> 
                          <td width="23%">
                            <a href="{{ route('deptsec.proposal_detail',[$prop->draft_id,$prop->id]) }}" class="btn btn-xs btn-info" style="display: inline-block">View</a>
                          </td>
                        </tr>
                        @endif
                        @php $i++; @endphp
                        @endforeach
                      </tbody>
                    </table> 
                    <!--end: Datatable-->
                  </div>
                </div>
                <!--end::Card-->

                <!--begin::Card-->
                <div class="card card-custom gutter-b">
                  <div class="card-header flex-wrap py-3">
                    <div class="card-toolbar">
                      <h5>New Proposals</h5>
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
                     <table class="table table-bordered table-hover show-datatable" id="kt_datatable" style="margin-top: 13px !important">
                      <thead>
                        <tr>
                          <th>Sr No.</th>
                          <th>Scheme Name</th>
                          <th>Scheme Overview</th>
                          <th>Scheme Objective</th>
                          <th>Sub Scheme</th>
                          <!-- <th>Financial Adviser</th> -->
                          <th>Actions</th>
                        </tr>
                      </thead>
                      <tbody>
                        @php $i=1; @endphp
                      @foreach($proposals_new as $prop)
                        <tr>
                          <td>{{ $i }} </td>
                          <td>{{ $prop->scheme_name }}</td>
                          <td style="text-align:justify;line-height:1.5">{{ $prop->scheme_overview }}</td>
                          <td style="text-align:justify;line-height:1.5">{{ $prop->scheme_objective }}</td>
                          <td>{{ $prop->sub_scheme }}</td>
                          <td width="23%">
                            <a href="{{ route('deptsec.newproposal_detail',$prop->draft_id) }}" class="btn btn-xs btn-info" style="display: inline-block">View</a>
                            <a href="{{ route('deptsec.proposal_edit',$prop->draft_id) }}" class="btn btn-xs btn-primary">Edit</a>
                            <button type="button" class="btn btn-xs btn-success test" onclick="fn_new_forward_modal('{{$prop->draft_id}}')">Forward</button>
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
        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="container">
          <form method="post" action="{{ route('deptsec.frwdtogad') }}" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="draft_id" id="forwd_draft_id">
            <input type="hidden" name="send_id" id="forwd_send_id">
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
          <form method="post" action="{{ route('deptsec.returntoia') }}" enctype="multipart/form-data">
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

@endsection
{{-- <script src="{{asset('js/3.2.1.jquery.min.js')}}"></script> --}}
<script src="{{asset('js/jquery.min.js')}}"></script>
<script>
  function frwdgad(val,uid,uname,sid,did,stid){
    alert(stid);
    $.ajax({
      type: "post",
      url: "{{ url('gad') }}",
      data: {
        "_token": "{{ csrf_token() }}",
        user_id:uid,
        dept_id:did,
        draft_id:sid,
        created_by:uname,
        status_id:stid
      },
      success: function(response)
      {
          alert("Successfull Data"); 
          location.reload();
      }
      
    });
  }

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
  function fn_new_forward_modal(draft_id) {
    $("#forward_proposal #forwd_draft_id").val(draft_id);
    $("#forward_proposal").modal('show');
  }

</script>

