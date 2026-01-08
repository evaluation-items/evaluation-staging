@extends('dashboards.eva-dir.layouts.evaldir-dash-layout')
@section('title','Studies -  Dir. Of Evaluation')

<style type="text/css">
  @media screen and (max-width: 2560px) {
    #kt_datatable th, #kt_datatable td, #kt_datatable td .btn {
      font-size: 30px !important;
    }
    #kt_datatable td .btn {
      margin: 20px 0px;
    }
    .dataTables_wrapper .dataTables_paginate .paginate_button {
        padding : 0px !important;
    }
    .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
        border: 0px !important;
    }
    #pendingremarks .modal-content {
      width: 2200px !important;
      margin-left: -650px !important;
    }
    #pendingremarksLabel, #remarks_text {
      font-size: 30px !important;
    }
    #pendingremarks .modal-header button {
      font-size: 60px !important;
      padding: 10px 30px !important;
    }
    #pendingremarks form button {
      font-size: 30px !important;
    }
  }
  @media screen and (max-width: 1920px) {
    #kt_datatable th, #kt_datatable td, #kt_datatable td .btn {
      font-size: 24px !important;
    }
    #kt_datatable td .btn {
      margin: 20px 0px;
    }
    .dataTables_wrapper .dataTables_paginate .paginate_button {
        padding : 0px !important;
    }
    .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
        border: 0px !important;
    }
    #pendingremarks .modal-content {
      width: 1700px !important;
      margin-left: -450px !important;
    }
    #pendingremarksLabel, #remarks_text {
      font-size: 24px !important;
    }
    #pendingremarks .modal-header button {
      font-size: 40px !important;
      padding: 10px 30px !important;
    }
    #pendingremarks form button {
      font-size: 24px !important;
    }
  }
  @media screen and (max-width: 1600px) {
    #kt_datatable th, #kt_datatable td, #kt_datatable td .btn {
      font-size: 20px !important;
    }
    #kt_datatable td .btn {
      margin: 5px 0px;
    }
    .dataTables_wrapper .dataTables_paginate .paginate_button {
        padding : 0px !important;
    }
    .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
        border: 0px !important;
    }
    #pendingremarks .modal-content {
      width: 1400px !important;
      margin-left: -250px !important;
    }
    #pendingremarksLabel, #remarks_text {
      font-size: 20px !important;
    }
    #pendingremarks .modal-header button {
      font-size: 40px !important;
      padding: 10px 30px !important;
    }
    #pendingremarks form button {
      font-size: 20px !important;
    }
  }
  @media screen and (max-width: 1366px) {
    #kt_datatable th, #kt_datatable td, #kt_datatable td .btn {
      font-size: 18px !important;
    }
    #kt_datatable td .btn {
      margin: 5px 0px;
    }
    .dataTables_wrapper .dataTables_paginate .paginate_button {
        padding : 0px !important;
    }
    .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
        border: 0px !important;
    }
    #pendingremarks .modal-content {
      width: 1200px !important;
      margin-left: -150px !important;
    }
    #pendingremarksLabel, #remarks_text {
      font-size: 18px !important;
    }
    #pendingremarks .modal-header button {
      font-size: 40px !important;
      padding: 10px 30px !important;
    }
    #pendingremarks form button {
      font-size: 18px !important;
    }
  }
  @media screen and (max-width: 1280px) {
    #kt_datatable th, #kt_datatable td, #kt_datatable td .btn {
      font-size: 16px !important;
    }
    #kt_datatable td .btn {
      margin: 5px 0px;
    }
    .dataTables_wrapper .dataTables_paginate .paginate_button {
        padding : 0px !important;
    }
    .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
        border: 0px !important;
    }
    #pendingremarks .modal-content {
      width: 1000px !important;
      margin-left: -100px !important;
    }
    #pendingremarksLabel, #remarks_text {
      font-size: 16px !important;
    }
    #pendingremarks .modal-header button {
      font-size: 30px !important;
      padding: 10px 30px !important;
    }
    #pendingremarks form button {
      font-size: 16px !important;
    }
  }
  @media screen and (max-width: 500px) {
    #kt_datatable th, #kt_datatable td, #kt_datatable td .btn {
      font-size: 14px !important;
    }
    #kt_datatable td .btn {
      margin: 5px 0px;
    }
    .dataTables_wrapper .dataTables_paginate .paginate_button {
        padding : 0px !important;
    }
    .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
        border: 0px !important;
    }
    #pendingremarks .modal-content {
      width: 400px !important;
      margin-left: 0px !important;
    }
    #pendingremarksLabel, #remarks_text {
      font-size: 14px !important;
    }
    #pendingremarks .modal-header button {
      font-size: 20px !important;
      padding: 10px 20px !important;
    }
    #pendingremarks form button {
      font-size: 14px !important;
    }
  }
  @media screen and (max-width: 375px) {
    #kt_datatable th, #kt_datatable td, #kt_datatable td .btn {
      font-size: 12px !important;
    }
    #kt_datatable td .btn {
      margin: 5px 0px;
    }
    .dataTables_wrapper .dataTables_paginate .paginate_button {
        padding : 0px !important;
    }
    .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
        border: 0px !important;
    }
    #pendingremarks .modal-content {
      width: 250px !important;
      margin-left: 50px !important;
    }
    #pendingremarksLabel, #remarks_text {
      font-size: 12px !important;
    }
    #pendingremarks .modal-header button {
      font-size: 20px !important;
      padding: 10px 20px !important;
    }
    #pendingremarks form button {
      font-size: 12px !important;
    }
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
                    <h5 class="text-dark font-weight-bold my-1 mr-5 text-center">Studies Pending for Approval</h5>
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
              <div class="container" style="min-width:90%">
                <!--begin::Card-->
                <div class="card card-custom gutter-b">
<!--                   <div class="card-header flex-wrap py-3">
                    <div class="card-toolbar">
                    </div>
                  </div> -->
                  <div class="card-body">
                    <div class="table-responsive">
                      <table class="table table-bordered table-hover show-datatable" id="kt_datatable" style="margin-top: 13px !important">
                        <thead>
                          <tr>
                            <th>Sr No.</th>
                            <th>Study Name</th>
                            <th>Study Overview</th>
                            <th>Study Objective</th>
                            <th>Remarks</th>
                            <th>Actions</th>
                          </tr>
                        </thead>
                        <tbody>
                          @php $i=1; @endphp
                          @foreach($pending as $prop)
                              <tr>
                                <td width="3%">{{ $i }} </td>
                                <td width="20%">{{ $prop->scheme_name }}</td> 
                                <td width="30%">{{ $prop->scheme_overview }}</td> 
                                <td width="20%">{{ $prop->scheme_objective }}</td> 
                                <td width="10%">{{ $prop->remarks }}</td>
                                <td width="10%">
                                  <a href="{{ route('evaldir.proposal_detail',$prop->draft_id) }}" style="display:inline-block;" class="btn btn-xs btn-info">View</a>
                                  <button type="button" class="btn btn-xs btn-success" value="{{ $prop->draft_id }}" id="add_remarks">Remarks</button>
                                </td>
                              </tr>
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

<div class="modal fade" id="pendingremarks" tabindex="-1" role="dialog" aria-labelledby="pendingremarksLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="pendingremarksLabel">Add Remarks</h5>
        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form method="post" action="{{ route('evaldir.pending_proposal_remarks') }}">
          @csrf
          <input type="hidden" name="theid" id="get_the_id">
          <div class="form-group">
            <textarea rows="3" style="font-size:25px" name="remarks" class="form-control" id="remarks_text"></textarea>
          </div>
          <div class="form-group" style="text-align:right">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="margin:0px 10px">Close</button>
            <button type="submit" class="btn btn-primary">Save</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>


  <!--end::Content-->
@endsection
{{-- <script src="{{asset('js/3.2.1.jquery.min.js')}}"></script> --}}
<script src="{{asset('js/jquery.min.js')}}"></script>
<script>
  $(document).ready(function () {

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

    if(window.matchMedia('(max-width: 375px)').matches) {
      var t = $('.show-datatable').DataTable({
        'lengthChange':false
      });
    } else if(window.matchMedia('(max-width: 412px)').matches) {
      var t = $('.show-datatable').DataTable({
        'lengthChange':false
      });
    } else if(window.matchMedia('(max-width:1360px)').matches) {
      var t = $('.show-datatable').DataTable();
    } else if(window.matchMedia('(max-width:1280px)').matches) {
      var t = $('.show-datatable').DataTable();
    } else if(window.matchMedia('(max-width:1600px)').matches) {
      var t = $('.show-datatable').DataTable();
    } else if(window.matchMedia('(max-width:1920px)').matches) {
      var t = $('.show-datatable').DataTable();
    } else if(window.matchMedia('(max-width:2560px)').matches) {
      var t = $('.show-datatable').DataTable();
    }

  });
</script>


