@extends('dashboards.eva-dir.layouts.evaldir-dash-layout')
@section('title','Studies -  Dir. Of Evaluation')

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
            <div class="d-flex flex-column-fluid">
              <!--begin::Container-->
              <div class=" container ">
                <!--begin::Card-->
                <div class="card card-custom gutter-b">
                  <div class="card-header flex-wrap py-3">
                    <div class="card-toolbar">
                      <!--begin::Dropdown-->
                      <!-- <a class="btn btn-primary" href="{{-- route('schemes.create') --}}"> New Proposal</a> -->
                      {{-- <div class="dropdown dropdown-inline mr-2">
                        <button type="button" class="btn btn-light-primary font-weight-bolder dropdown-toggle"data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                          <span class="svg-icon svg-icon-md">
                            <!--begin::Svg Icon | path:assets/media/svg/icons/Design/PenAndRuller.svg-->
                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                              <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                <rect x="0" y="0" width="24" height="24"/>
                                <path d="M3,16 L5,16 C5.55228475,16 6,15.5522847 6,15 C6,14.4477153 5.55228475,14 5,14 L3,14 L3,12 L5,12 C5.55228475,12 6,11.5522847 6,11 C6,10.4477153 5.55228475,10 5,10 L3,10 L3,8 L5,8 C5.55228475,8 6,7.55228475 6,7 C6,6.44771525 5.55228475,6 5,6 L3,6 L3,4 C3,3.44771525 3.44771525,3 4,3 L10,3 C10.5522847,3 11,3.44771525 11,4 L11,19 C11,19.5522847 10.5522847,20 10,20 L4,20 C3.44771525,20 3,19.5522847 3,19 L3,16 Z" fill="#000000" opacity="0.3"/>
                                <path d="M16,3 L19,3 C20.1045695,3 21,3.8954305 21,5 L21,15.2485298 C21,15.7329761 20.8241635,16.200956 20.5051534,16.565539 L17.8762883,19.5699562 C17.6944473,19.7777745 17.378566,19.7988332 17.1707477,19.6169922 C17.1540423,19.602375 17.1383289,19.5866616 17.1237117,19.5699562 L14.4948466,16.565539 C14.1758365,16.200956 14,15.7329761 14,15.2485298 L14,5 C14,3.8954305 14.8954305,3 16,3 Z" fill="#000000"/>
                              </g>
                            </svg>
                            <!--end::Svg Icon-->
                          </span>
                          Export
                        </button>
                        <!--begin::Dropdown Menu-->
                        <div class="dropdown-menu dropdown-menu-sm dropdown-menu-right">
                          <!--begin::Navigation-->
                          <ul class="navi flex-column navi-hover py-2">
                            <li class="navi-header font-weight-bolder text-uppercase font-size-sm text-primary pb-2">
                              Choose an option:
                            </li>
                            <li class="navi-item">
                              <a href="#" class="navi-link">
                              <span class="navi-text">Print</span>
                              </a>
                            </li>
                            <li class="navi-item">
                              <a href="#" class="navi-link">
                              <span class="navi-text">Excel</span>
                              </a>
                            </li>
                            <li class="navi-item">
                              <a href="#" class="navi-link">
                              <span class="navi-text">PDF</span>
                              </a>
                            </li>
                          </ul>
                          <!--end::Navigation-->
                        </div>
                        <!--end::Dropdown Menu-->
                      </div> --}}
                      <!--end::Dropdown-->
                    </div>
                  </div>
                  <div class="card-body table-responsive">
                    <!--begin: Datatable-->
                     <table class="table table-bordered table-hover" id="kt_datatable" style="margin-top: 13px !important">
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
                        @foreach($proposals as $prop)
                            @if($prop->status_id = 23)
                            <tr>
                              <td>{{ $i }}</td>
                              <td>{{ $prop->scheme_name }}</td> 
                              <td>{{ $prop->scheme_overview }}</td> 
                              <td>{{ $prop->scheme_objective }}</td> 
                              <td>{{ $prop->sub_scheme }}</td> 
                              <td width="18%">
                                <a href="{{ route('evaldir.proposal_detail',$prop->draft_id) }}" class="btn btn-xs btn-info" style="display:inline-block">View</a>

                                @if(auth()->user()->role == 2)
                                {{--
                                  @if($prop->status_id == 25)
                                  <form method="post" action="#" style="width:100%">
                                        @csrf
                                        <input type="hidden" name="status_id" value="23">
                                        <button type="button" class="btn btn-success" onClick="frwdgad(this.value,
                                                '{{ $prop->draft_id }}', '{{ $prop->dept_id }}','23')">Forward</button>
                                  @endif
                                  --}}
                                  <button type="button" class="btn btn-xs btn-danger" onclick="fn_backward_modal('{{ $prop->draft_id }}','{{ $prop->id }}')" style="display:inline-block">Backwrd</button>
                                @endif
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
        <h4 class="modal-title">Add Remarks</h4>
        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="container">
          <form method="post" action="{{ route('evaldir.returntogad') }}" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="draft_id" id="return_draft_id">
            <input type="hidden" name="send_id" id="return_send_id">
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
<!-- <script type="text/javascript">
  var ktcontent = $("#kt_content").height();
  $(".content-wrapper").css('min-height',ktcontent);
</script> -->
<script>
  function frwdgad(val,uid,uname,sid,did,stid){
    // alert(val'+---+'uid'+----+'uanme'+-----+'sid'+-----+'did'+-----+'stid);
    $.ajax({
      type: "post",
      url: "{{ url('g') }}",
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
</script>
<script>
  $(document).ready(function () {
    $('.numberonly').keypress(function (e) {
      var charCode = (e.which) ? e.which : event.keyCode;
      if (String.fromCharCode(charCode).match(/[^0-9]/g))
        return false;
      });    
  });
  function fn_backward_modal(back_draft_id,back_send_id) {
    $("#return_proposal #return_draft_id").val(back_draft_id);
    $("#return_proposal #return_send_id").val(back_send_id);
    $("#return_proposal").modal('show');
  }

</script>
