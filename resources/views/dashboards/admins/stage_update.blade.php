@extends('dashboards.admins.layouts.admin-dash-layout')
@section('title','Update Stage')
@section('content')
<style>
  .content-wrapper{
    background: #ffffba;
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
                  <div class="d-flex text-center flex-wrap mr-5">
                    <!--begin::Page Title-->
                    <h5 class="text-dark font-weight-bold my-1 mr-5">{{ __('message.update_stages')}}</h5>
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
                          <h5>{{ __('message.update_stages')}}</h5>
                        </div>
                        <a href="#myModal" class="btn btn-primary  float-right" data-bs-toggle="modal" width="200px">{{ __('message.summary_report')}}</a>
                        <a href="{{route('stage.export')}}" class="btn btn-primary float-right btn-download">{{ __('message.download_word')}}</a>
                        <a href="{{route('stage.export.pdf')}}" class="btn btn-primary float-right btn-download">{{ __('message.download_pdf')}}</a>
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
                                <th width="8%">{{ __('message.no')}}</th>
                                <th>{{ __('message.scheme_name')}}</th>
                                <th>{{ __('message.assigned_date')}}</th>
                                <th>{{ __('message.branch_name')}}</th>
                                <th>{{ __('message.current_stage')}}</th>
                                <th>{{ __('message.actions')}}</th>
                              </tr>
                            </thead>
                            <tbody>
                              @php $i=1; @endphp
                              @foreach($ongoing_proposal as $prop)
                                  <tr>
                                      <td>{{ $i++ }}</td>
                                      <td>{{ SchemeName($prop->scheme_id) }}</td>
                                      <td>{{ !empty($prop->created_at) ? date('d-M-y',strtotime($prop->created_at)) : '-' }}</td>
                                      <td>{{ isset($prop->schemeSend->team_member_dd) ? branch_list($prop->schemeSend->team_member_dd) : '-' }}</td>
                                      <td>{{ current_stages($prop->id) }}</td>
                                      <td width="23%" class="text-center">
                                        <a href="{{ route('stages.create', ['draft_id' => $prop->draft_id]) }}" class="btn btn-xs btn-info">{{ __('message.edit')}} </a>
                                      </td>
                                  </tr>
                              @endforeach
                          </tbody>
                          
                        </table>
                        <!--end: Datatable-->
                      </div>
                    </div>
                </div>
            <!--end::Container-->
            </div>
            <!--end::Entry-->
            </div>
            <!--end::Content-->
            <div id="myModal" class="modal fade">
              <div class="modal-dialog" style="">
                <div class="modal-content">
                  <div class="modal-header flex-column" style="width: auto;height: 62px;">
                    <h4 class="modal-title">{{ __('message.summary_report')}}</h4>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close" style="padding-top: 0%;">
                      <span aria-hidden="true">×</span>
                    </button>
                  </div>
                  <div class="modal-body">
                    <div class="form-group">
                      <label for="stages_filtter">{{ __('message.stages_filtter')}}</label>
                      <select class="form-control" name="stages_filtter" id="stages_filtter">
                        <option value="">{{ __('message.select')}} {{ __('message.stages_filtter')}}</option>
                        {{-- <option value="1">All</option> --}}
                        <option value="2">{{ __('message.scheme_name')}}</option>
                        <option value="3">{{ __('message.assigned_date')}}</option>
                        <option value="4">{{ __('message.branchess')}}</option>
                        <option value="5">{{ __('message.current_stage')}}</option>
                      </select>
                    </div>
                   
                    <div class="form-group selected_item" style="display: none;">
                      <label for="stages_filtter">{{ __('message.select')}} {{ __('message.stages_filtter')}}</label>
                      <select class="form-control" name="selected_item" id="selected_stages_filtter">
                        
                      </select>
                    </div>

                    <div class="summary-data">

                    </div>
                  </div>
                  {{-- <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary">Done</button>
                  </div> --}}
                </div>
              </div>
            </div>
@endsection
{{-- <script src="{{asset('js/3.2.1.jquery.min.js')}}"></script> --}}
<script src="{{asset('js/jquery.min.js')}}"></script>
<script>
  $(document).ready(function(){
    $('.dataTable').DataTable({
          columnDefs: [
              { type: 'date', targets: 2 } 
          ]
      });
  })


$(document).on('change', '#stages_filtter', function () {
  var stages_filtter = $(this).val();
    
    const url = "{{ route('stage-filter-item') }}";
          $.ajax({
            type: 'POST',
            dataType: 'json',
            url: url,
            data:{stages_filtter:stages_filtter},
            headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
              success: function (response) {
                $('#stages_filtter').attr('disabled','disabled');
                $('.selected_item').css('display','block');
                var select = $('#selected_stages_filtter').append('<option>Select Option</option>');
                $.each(response.data, function (i, value) {
                    var roleIds = response.team_member_dd[i]; // e.g., "28,34"
                    select.append('<option value="' + roleIds + '">' + value + '</option>');
                });
                $('.modal-dialog').css('max-width','990px');
              },
              error: function () {
                  console.log('Error: Unable to delete department.');
              }
          });
});
$(document).on('change', '#selected_stages_filtter', function () {
  var selected = $(this).val();
    const url = "{{ route('stage-item') }}";
          $.ajax({
            type: 'POST',
            //dataType: 'json',
            url: url,
            data:{selected:selected},
            headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (response) {
              $('.summary-data').append(response);
              $('#selected_stages_filtter').attr('disabled','disabled');
              $('.modal-dialog').css('max-width','990px');
            },
            error: function () {
                console.log('Error: Unable to delete department.');
            }
          });
});



</script>