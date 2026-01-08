@extends('dashboards.eva-dir.layouts.evaldir-dash-layout')
@section('title','Studies -  Dir. Of Evaluation')

<style type="text/css">
  @media screen and (max-width: 375px) {
    #kt_datatable th, #kt_datatable td {
      font-size: 12px;
    }
    .dataTables_wrapper .dataTables_paginate .paginate_button {
        padding : 0px !important;
    }
    .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
        border: 0px !important;
    }
  }
  @media screen and (max-width: 1600px) {
    
  }

</style>

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
                    <h5 class="text-dark font-weight-bold my-1 mr-5">List of Studies</h5>
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
                    </div>
                  </div>
                  <div class="card-body">
                    <div class="table-responsive">
                     <table class="table table-bordered table-hover show-datatable" id="kt_datatable" style="margin-top: 13px !important">
                      <thead>
                        <tr>
                          <th>Sr No.</th>
                          <th>Study Name</th>
                          <th>Study Objective</th>
                          <th>Department Name</th>
                          <th>Actions</th>
                        </tr>
                      </thead>
                      <tbody>
                        @php $i=1; @endphp
                        @foreach($proposals as $prop)
                            @if($prop->status_id == 23 || $prop->status_id == 25)
                            <tr>
                              <td>{{ $i }}</td>
                              <td>{{ $prop->scheme_name }}</td> 
                              <td style="text-align:justify;line-height:1.5">{{ $prop->scheme_objective }}</td> 
                              <td>{{ $prop->dept_name }}</td> 
                              <!-- <td> -->
                                <!-- <a href="{{-- route('evaldir.proposal_detail',$prop->draft_id) --}}" class="btn btn-xs btn-info">View</a> -->
                                {{--
                                @if(auth()->user()->role == 2)
                                  @if($prop->status_id == 25)
                                    <button type="button" class="btn btn-xs btn-success" onClick="frwdgad(this.value,
                                                '{{ $prop->draft_id }}', '{{ $prop->dept_id }}','23')">Return</button>
                                  @endif
                                @endif
                                --}}
                              <!-- </td> -->
                              <td> <a href="{{ route('evaldir.report_module',$prop->draft_id) }}" class="btn btn-xs btn-info">Report Module</a> </td>
                            </tr>
                            @endif
                          @php $i++; @endphp
                        @endforeach
                      </tbody>
                    </table> 
                  </div>
                  </div>
                </div>
                <!--end::Card-->
              </div>
              <!--end::Container-->
            </div>
            <!--end::Entry-->
    </div>
          <!--end::Content-->
@endsection
<script type="text/javascript" src="{{ asset('plugins/jquery/jquery.js') }}"></script>
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

  $(document).ready(function() {
    if(window.matchMedia('(max-width: 375px)').matches) {
      var t = $('.show-datatable').DataTable({
        'lengthChange':false
      });
    } else if(window.matchMedia('(max-width:1600px)').matches) {
      var t = $('.show-datatable').DataTable();
    }
  });

</script>

