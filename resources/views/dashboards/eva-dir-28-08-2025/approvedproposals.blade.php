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
                    <h5 class="text-dark font-weight-bold my-1 mr-5 text-center">Approved Proposals</h5>
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
                    <!--begin: Datatable-->
                     <table class="table table-bordered table-hover show-datatable" id="kt_datatable" style="margin-top: 13px !important">
                      <thead>
                        <tr>
                          <th>Sr No.</th>
                          <th>Name of the Evaluation Study</th>
                          <th>Objectives</th>
                          <th>Date of Submission</th>
                          <th>Date of Approve</th>
                        </tr>
                      </thead>
                      <tbody>
                        @php $i=1; @endphp
                        @foreach($proposals as $prop)
                            <tr>
                              <td width="3%">{{ $i++ }} </td>
                              <td width="20%">{{ $prop->scheme_name }}</td> 
                              <td width="20%">{{ $prop->scheme_objective }}</td> 
                              <td width="20%">{{ date('d M, Y',strtotime($prop->submission_date)) }}</td>
                              <td width="20%">{{ date('d M, Y',strtotime($prop->approved_date)) }}</td>
                                {{--
                              <td width="15%">
                                <a href="{{ route('evaldir.proposal_detail',$prop->draft_id) }}" style="display:inline-block;" class="btn btn-xs btn-info">View</a>
                                @if(in_array($prop->draft_id,$approved_proposals))
                                  <a href="javascript:void(0)" style="display:inline-block;" class="btn btn-xs btn-primary approved_anchor_tag" onclick="show_prop_status({{$prop->draft_id}})" >Approved</a>
                                @else 
                                  <a href="javascript:void(0)" style="display:inline-block" class="btn btn-xs btn-warning approvenow_anchor_tag" onclick="fn_approve({{ $prop->draft_id }})">Approve</a>
                                @endif
                              </td>
                                --}}
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
@endsection
{{-- <script src="{{asset('js/3.2.1.jquery.min.js')}}"></script> --}}
<script src="{{asset('js/jquery.min.js')}}"></script>
<!-- <script type="text/javascript">
  var ktcontent = $("#kt_content").height();
  $(".content-wrapper").css('min-height',ktcontent);
</script> -->
<script>
  $(document).ready(function () {
    $('.numberonly').keypress(function (e) {
      var charCode = (e.which) ? e.which : event.keyCode;
      if (String.fromCharCode(charCode).match(/[^0-9]/g))
        return false;
      }); 
      $('.dataTable').DataTable({
          columnDefs: [
              { type: 'date', targets: [3,4] } 
          ]
      });   
  });
   
</script>

