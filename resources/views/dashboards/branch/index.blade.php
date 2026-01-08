@extends('dashboards.admins.layouts.admin-dash-layout')
@section('title','Branch')
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
                    {{ __('message.branch')}} {{ __('message.list')}}   	            
                    </h5>
                    <!--end::Page Title-->                  
                  </div>
                  <!--end::Page Heading-->
                </div>
                <!--end::Info-->
              </div>
            </div>
                <!--end::Subheader-->
                  @if(session()->has('success'))
                  <div class="alert alert-success alert-dismissible" role="alert">
                      <button type="button" class="close" data-dismiss="alert" aria-label="Close" style="color: white;"><span aria-hidden="true">&times;</span></button>
                      {{ session()->get('success') }}
                  </div>  
                  @endif
                  @if(session()->has('error'))
                  <div class="alert alert-danger" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close" style="color: white;"><span aria-hidden="true">&times;</span></button>
                    {{ session()->get('error') }}
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
                              <a href="{{route('branch.create')}}" style="margin:10px" class="btn btn-primary">{{ __('message.add')}} {{ __('message.branch')}}</a>
                            </div>
                          </div>
                         
                            <div class="row">
                              <div class="card-body">
                                <div class="col-lg-12">
                                    <table class="table table-bordered table-striped dataTable dtr-inline" id="example1">
                                      <thead>
                                            <tr>
                                                <th>{{ __('message.no')}}</th>
                                                <th>{{ __('message.branch')}} {{ __('message.name')}}</th>
                                                <th>{{ __('message.user_list')}}</th>
                                                <th>{{ __('message.actions')}}</th>
                                            </tr>
                                        <tbody>
                                          @php
                                            $i = 1;
                                          @endphp
                                        @if ($branch_list->count() > 0)
                                          @php $uniqueBranches = $branch_list->unique('name'); @endphp
                                          @foreach ($uniqueBranches as $branch)
                                              <tr>
                                                  <td>{{$i++}}</td>
                                                  <td>{{$branch->name}}</td>
                                                  <td>
                                                    @php $roleNames = $branch_list->where('name', $branch->name)->pluck('roles')->flatten()->pluck('rolename')->toArray(); @endphp
                                                    {{ implode(', ', $roleNames) }}
                                                  </td>
                                                  <td>
                                                      <a href="{{ route('branch.edit', $branch->id) }}" class="btn btn-xs btn-primary">{{ __('message.edit')}}</a>
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

        <div class="modal fade" id="add_role" style="display: none;" aria-hidden="true">
          <div class="modal-dialog modal-lg">
            <div class="modal-content">
              <div class="modal-header">
                <h4 class="modal-title">{{ __('message.add')}} {{ __('message.user')}}</h4>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">×</span>
                </button>
              </div>
              <div class="modal-body">
                <div class="container">
                  <form id="userRole" method="post" enctype="multipart/form-data" action="{{ route('admin.add_role') }}" autocomplete="off">
                    @csrf
                    <div class="row form-group">
                      <label for="rolename">{{ __('message.role')}} {{ __('message.name')}} <span class="required_filed">*</span></label>
                      <input type="text" name="rolename" class="form-control pattern" value="" id="rolename" maxlength="100" autocomplete="off">
                    </div>
                    <div class="row">
                      <div class="col-xl-12" style="margin-top:10px;text-align:right">
                        <button type="submit" class="btn btn-primary submit_btn_meeting">{{ __('message.submit')}}</button>
                        <button type="button" class="btn btn-default" data-bs-dismiss="modal">{{ __('message.close')}}</button>
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

  $(document).ready(function(){
    var ktcontent = $("#kt_content").height();
    $(".content-wrapper").css('min-height',ktcontent);
  });
function fn_show_add_role(){
    $('form #rolename').val('');
    $('#add_role').modal('show');
}
  
</script>
@endsection