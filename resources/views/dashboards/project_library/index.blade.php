@extends('dashboards.admins.layouts.admin-dash-layout')
@section('title',' Evaluation Report Library List')
@section('content')

        <div class="content  d-flex flex-column flex-column-fluid" id="kt_content">
            <!--begin::Subheader-->
            <div class="subheader py-2 py-lg-6  subheader-solid " id="kt_subheader">
              <div class=" container-fluid  d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
                <!--begin::Info-->
                <div class="d-flex align-items-center flex-wrap mr-1">
                  <!--begin::Page Heading-->
                  <div class="d-flex align-items-baseline flex-wrap mr-5">
                    <!--begin::Page Title-->
                    <h5 class="text-dark font-weight-bold my-1 mr-5">
                       {{ __('message.evaluation_report_library')}}  {{ __('message.list')}}                	            
                    </h5>               
                  </div>
                  <!--end::Page Heading-->
                </div>
                <!--end::Info-->
              </div>
            </div>
            <!--end::Subheader-->
            @if(session()->has('success'))
            <div class="alert alert-success" role="alert">
              {{  session()->get('success') }}
            </div>
            @endif
            @if(session()->has('error'))
                <div class="alert alert-custom alert-notice alert-danger fade show" role="alert">
                  <div class="alert-icon"><i class="flaticon-warning"></i></div>
                  <div class="alert-text"> {{ session()->get('error') }}</div>
                  <div class="alert-close">
                      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                          <span aria-hidden="true"><i class="ki ki-close"></i></span>
                      </button>
                  </div>
              </div>
            @endif
            @if ($errors->any())
              <div class="alert alert-danger">
                  <ul>
                      @foreach ($errors->all() as $error)
                          <li>{{ $error }}</li>
                      @endforeach
                  </ul>
              </div>
            @endif
            <!--begin::Entry-->
              <div class="d-flex flex-column-fluid">
                <!--begin::Container-->
                <div class=" container ">  
                  <!--begin::Card-->
                  <div class="card card-custom gutter-b">
                      <div class="card-header flex-wrap py-3">
                        <div class="card-toolbar">
                          <a href="{{route('digital_project_libraries.create')}}" style="margin:10px" class="btn btn-primary"> {{ __('message.add')}}  {{ __('message.project')}}</a>
                        </div>
                      </div>
                    <div class="row">
                      <div class="card-body">
                        <div class="col-lg-12">
                          <table class="table table-bordered table-striped dataTable dtr-inline" id="example1">
                            <thead>
                                  <tr>
                                      <th> {{ __('message.no')}}</th>
                                      <th> {{ __('message.name')}}</th>
                                      <th> {{ __('message.year')}}</th>
                                      <th> {{ __('message.department_name')}}</th>
                                      <th> {{ __('message.organization_name')}}</th>
                                      <th>{{ __('message.actions')}}</th>
                                  </tr>
                              <tbody>
                                @php $i =1; @endphp
                                @if ($project_list->count() > 0) 
                                @foreach ($project_list as  $key => $items) 
                                @php
                                  $file =  explode(',', $items->upload_file);
                                  $rand_val = explode(',', $items->rand_val);
                                @endphp
                                <tr>
                                    <td>{{$i++}}</td>
                                    <td>{{$items->study_name}}</td>
                                    <td width="20%" class="text-center">{{$items->year}}</td>
                                    <td width="23%">{{department_name($items->dept_id)}}</td>
                                    <td>{{$items->org_name}}</td>
                                    <td width="23%" class="text-center">
                                    <a href="{{ route('digital_project_libraries.edit',$items->id) }}" class="btn btn-xs btn-primary">{{ __('message.edit')}}</a>
                                      @if(!empty($file) && count($file) > 0)
                                      @foreach($file as $kgrs => $files)
                                          @if(isset($rand_val[$kgrs]))
                                              {{-- <a href="{{ route('get_the_document', [Crypt::encrypt($rand_val[$kgrs]), $file]) }}" target="_blank" title="{{ $file }}">
                                                  <i class="fas fa-file-pdf fa-2x" style="color:red;"></i>
                                              </a> --}}
                                              
                                              <a class="btn btn-xs btn-info" href="{{ route('get_the_document',[Crypt::encrypt($rand_val[$kgrs]),$files]) }}" target="_blank" title="{{ $files }}">{{ __('message.view_document')}}</a>

                                          @endif
                                      @endforeach
                                      @endif
                                      <a data-bs-target="#myModal" class="btn btn-xs btn-danger trigger-btn" data-id= "{{$items->id}}" data-bs-toggle="modal">{{ __('message.delete')}}</a>
                                    </td>
                                </tr>
                                @endforeach
                              @endif
                              </tbody>
                          </table>
                        </div>
                      </div>
                    </div>
                  </div>
                  <!--end::Container-->
                </div>
                <!--end::Entry-->
              </div>
        </div>

<div id="myModal" class="modal fade">
  <div class="modal-dialog modal-confirm">
    <div class="modal-content">
      <div class="modal-header flex-column">
        <div class="icon-box">
          <i class="material-icons">&#xE5CD;</i>
        </div>						
        <h4 class="modal-title w-100">{{ __('message.are_you_sure')}}</h4>	
            <button type="button" class="close" data-bs-dismiss="modal" aria-hidden="true">&times;</button>
      </div>
      <div class="modal-body">
        <p>{{ __('message.delete_msg')}}</p>
      </div>
      <div class="modal-footer justify-content-center">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('message.cancel')}}</button>
        <button type="button" class="btn btn-danger deleteBtn" data-dept-id="">{{ __('message.delete')}}</button>
      </div>
    </div>
  </div>
</div>

<script src="{{asset('js/jquery.min.js')}}"></script>

<script type="text/javascript">


    $('.trigger-btn').on('click', function () {
        var id = $(this).data('id');
        console.log(id);
        var deleteBtn = $('.deleteBtn');
        var deleteId = deleteBtn.data('dept-id');
        deleteBtn.data('dept-id', id);
    });
    $('.deleteBtn').on('click', function () {
      $('#myModal').modal('hide');
      const id = btoa($(this).data('dept-id'));
          if (id !== "") {
            const url = "{{ route('digital_project_libraries_destroy.destroy', ':id') }}".replace(':id', id);
              $.ajax({
                type: 'POST',
                dataType: 'json',
                url: url,
                headers: {
                  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                  success: function (response) {
                     alert(response.message);
                     location.reload();
                  },
                  error: function () {
                      console.log('Error: Unable to delete department.');
                  }
              });
          }
      });

</script>
@endsection
