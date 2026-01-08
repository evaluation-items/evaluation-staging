@extends('dashboards.eva-dd.layouts.evaldd-dash-layout')
@section('title','Transfer Proposals')
@section('content')
<style>
  .content-wrapper{
    background: #baffc9;
  }
 </style>
  <!--begin::Content-->
    <div class="content  d-flex flex-column flex-column-fluid">
            <!--begin::Subheader-->
            <div class="subheader py-2 py-lg-6  subheader-solid " id="kt_subheader">
              <div class=" container-fluid  d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
                <!--begin::Info-->
                <div class="d-flex align-items-center flex-wrap mr-1">
                  <!--begin::Page Heading-->
                  <div class="d-flex justify-content-center align-items-center">
                    <!--begin::Page Title-->
                    <h5 class="text-dark font-weight-bold">{{ __('message.transfred_proposals') }}</h5>
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
                  @if(session()->has('success'))
                  <div class="alert alert-success alert-dismissible" role="alert">
                      <button type="button" class="close" data-dismiss="alert" aria-label="Close" style="color: white;"><span aria-hidden="true">&times;</span></button>
                      {{ session()->get('success') }}
                  </div>  
                  @endif
              
                  @if(session()->has('error'))
                      <div class="alert alert-custom alert-notice alert-light-danger fade show" role="alert">
                      <div class="alert-icon"><i class="flaticon-warning"></i></div>
                      <div class="alert-text"> {{ session()->get('error') }}</div>
                      <div class="alert-close">
                          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                              <span aria-hidden="true"><i class="ki ki-close"></i></span>
                          </button>
                      </div>
                  </div>
                  @endif
                  </div>
                 <!--begin::Card-->
                 <div class="card card-custom gutter-b">
                  <div class="card-header flex-wrap py-3">
                    <div class="card-toolbar">
                      <h5>T{{ __('message.transfred_proposals') }}</h5>
                    </div>
                  </div>
                 
                  <div class="card-body">
                    <!--begin: Datatable-->
                    <table class="table table-bordered table-striped dataTable dtr-inline" id="example1">
                      <thead>
                          <tr>
                              <th>{{ __('message.no') }}</th>
                              <th>{{ __('message.scheme_name') }}</th>
                              <th>{{ __('message.department_name') }}</th>
                              <th>{{ __('message.transfer_date') }}</th>
                              <th>{{ __('message.actions') }}</th>
                            {{-- <th>Sr No.</th>
                            <th>Scheme Name</th>
                            <th>Department Name</th>
                            <th>Transfer Date</th>
                            <th>Actions</th> --}}
                          </tr>
                        </thead>
                          <tbody>
                            @php $i=1; @endphp
                            @foreach($transfer_proposal as $prop)
                            <tr>
                            <td>{{ $i++ }}</td>
                            <td>{{ proposal_name($prop->draft_id) }}</td>
                            <td>{{ department_name($prop->dept_id) }}</td>
                            <td  width="20%">{{date('d-M-y',strtotime( $prop->created_at))}}</td>
                            <td width="23%">
                              <button type="button" class="btn btn-xs btn-success text-center" data-role-id="{{ Crypt::encrypt($prop->team_member_dd)}}" onclick="add_team(this, '{{ Crypt::encrypt($prop->id) }}')" style="display:inline-block">{{ __('message.transfer') }}</button>
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



<div class="modal fade" id="add_team" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title text-center">{{ __('message.proposal_assign_to_branch')}}</h4>
          <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="container">
            {{-- <form method="post" action="{" enctype="multipart/form-data" id="evaldir_addteam">
              @csrf --}}
              <form id="" method="post" action="{{ route('evaldd.update_branch') }}" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="id" value="" id="id">
                <div class="row form-group">
                  <select name="branch" class="form-control select_item" style="min-width:100% !important">
                    <option value="">{{ __('message.select_branch')}}</option>
                    @foreach($branch_list as $attkey => $branch)
                      <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                    @endforeach
                  </select>
                  {{-- <label for="choices-multiple-remove-button" class="col-xl-12">Team Member DD <span class="required_filed"> * </span> </label>
                  <select id="choices-multiple-remove-button" name="branch" class="form-control"  style="min-width:100% !important">
                    <option value="">Select Branch</option>
                    @foreach($branch_list as $attkey => $branch)
                      <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                    @endforeach
                  </select> --}}
                </div>
                <div class="col-xl-12" style="text-align: right;">
                  <button type="submit" class="btn btn-primary add_submit_btn_meeting">{{ __('message.submit')}}</button>
                  <button type="button" class="btn btn-default add_close_btn_meeting" data-bs-dismiss="modal">{{ __('message.close')}}</button>
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

$(document).ready(function () {
  $('.dataTable').DataTable({
          columnDefs: [
              { type: 'date', targets: 3 } 
          ]
      });

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

    var ktcontent = $("#kt_datatable").height();
    $(".content-wrapper").css('min-height',ktcontent);

  
});


function add_team(button, id) {
  //  $('.choices').width('100%');
    const role_id = $(button).data('role-id'); // Use the button argument

    if(role_id != ""){
        $.ajax({
            url: "{{route('evaldd.get_branch')}}",
            method: 'POST',
            data: {
                role_id:role_id,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                $("#add_team").modal('show');
                 if (response.branch_id !== "") {
                    
                  //multipleCancelButton.setChoiceByValue(response.branch_id);
                  var text = '';
                  
                   $('.select_item option').each(function () {
                          var itemId = $(this).val();
                          if (itemId == response.branch_id) {
                             // $(this).addClass('is-highlighted');
                             $(this).attr("selected","selected");
                              // text = $(this).text();
                              // console.log(text);
                          
                          }
                      });
                      // $('.choices__item--selectable').each(function () {
                      //     var itemId = $(this).data('id');
                         
                      //     if (itemId == response.branch_id) {
                      //         $(this).addClass('is-highlighted');
                      //         $(this).attr('aria-selected', true);
                      //         text = $(this).text();
                      //         console.log(text);
                          
                      //     }else{
                      //         $(this).removeClass('is-highlighted');
                      //         $(this).attr('aria-selected',false);
                      //     }
                      // });
                    $('#id').val(id);
                  // $('.choices__list--single').find('.choices__item--selectable').addClass('custom-text');
                  // $('.choices__list--single').find('.choices__item--selectable.custom-text').attr('data-id', response.branch_id).html(text);
  
                 }   
            },
            error: function(error) {
                console.log(error);
            }
        });
    }
}

</script>
