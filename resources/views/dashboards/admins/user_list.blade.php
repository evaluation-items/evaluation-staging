@extends('dashboards.admins.layouts.admin-dash-layout')
@section('title','User List')
@section('content')
@php
  use Illuminate\Support\Facades\Crypt;
@endphp
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
                     {{ __('message.user') }} {{ __('message.list') }}                	            
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
                    <div class="col-lg-9">
                      @if(Session::has('email_err'))
                        <h4 style="color:red">{{ Session::get('email_err') }}</h4>
                      @endif
                    </div>
                    <div class="card-header flex-wrap py-3">
                      <div class="card-toolbar">
                        <button type="buton" style="margin:10px" class="btn btn-primary" onclick="fn_show_add_user()">{{ __('message.add') }} {{ __('message.user') }}</button>
                      </div>
                    </div>
                    
                    <div class="row">
                      <div class="card-body">
                        <div class="col-lg-12">
                          <table class="table table-bordered table-striped dataTable dtr-inline" id="example1">
                            <thead>
                                  <tr>
                                      <th>{{ __('message.no') }}</th>
                                      <th>{{ __('message.name') }}</th>
                                      <th>{{ __('message.email') }}</th>
                                      <th>{{ __('message.designation')}}</th>
                                      <th>{{ __('message.actions') }}</th>
                                  </tr>
                              <tbody>
                                @if ($user_list->count() > 0) 
                                @foreach ( $user_list as $user ) 
                                <tr>
                                    <td>{{$i++}}</td>
                                    <td>{{(!is_null($user->name)) ? $user->name : '-'}}</td>
                                    <td>{{$user->email}}</td>
                                    @if(!is_null($user->roles))
                                    <td>
                                      @foreach ($user->roles as $role)
                                        {{$role->rolename}}
                                      @endforeach
                                    </td>
                                    @endif
                                    <td>
                                      <a href="{{ route('user.show',Crypt::encrypt($user->id)) }}" class="btn btn-xs btn-info" style="display:inline-block">{{ __('message.view') }}</a>
                                      <a href="{{ route('user.edit',Crypt::encrypt($user->id)) }}" class="btn btn-xs btn-primary">{{ __('message.edit') }} {{ __('message.user') }}</a>
                                      {{-- <a href="{{ route('user.destroy',$user->id) }}" class="btn btn-xs btn-danger trigger-btn"  onclick="event.preventDefault(); document.getElementById('delete-form-{{ $user->id }}').submit();">
                                        Delete
                                    </a>
                                    
                                    <form id="delete-form-{{$user->id }}" action="{{ route('user.destroy', $user->id) }}" method="POST" style="display: none;">
                                        @csrf
                                        @method('DELETE')
                                    </form> --}}
                                      {{-- <a href="#myModal" class="btn btn-xs btn-danger trigger-btn" data-id= "{{$user->id}}"data-bs-toggle="modal">Delete</a> --}}
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

<div class="modal fade" id="add_user" style="display: none;" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">{{ __('message.add') }} {{ __('message.user') }}</h4>
        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="container">
          <form method="post" id="frmaddUser" enctype="multipart/form-data" action="{{ route('admin.add_user') }}" autocomplete="off" >
            @csrf
            <div class="row form-group">
              <label for="user_name">{{ __('message.name') }}</label>
              <input type="text" name="name" class="form-control pattern" value="" id="user_name" maxlength="100" autocomplete="off">
            </div>
            <div class="row">
                <label for="user_email">{{ __('message.email') }} <span class="required_filed"> * </span></label>
                <input type="email" name="email" id="user_email" value="" class="form-control" maxlength="150" autocomplete="off">
            </div>
            <div class="row departments">
                <label for="dept_id">{{ __('message.department') }} <span class="required_filed"> * </span></label>
                <select name="dept_id" id="dept_id" class="form-control">
                  <option value="">{{ __('message.select_department') }}</option>
                  @foreach($departments as $dkey=>$dval)
                  <option value="{{ $dval->dept_id }}">{{ $dval->dept_name }}</option>
                  @endforeach
                </select>
              
            </div>
            <div class="row">
                <label for="user_role">{{ __('message.designation')}} <span class="required_filed"> * </span></label>
                <select name="role" class="form-control" id="role_list">
                  <option value="">{{ __('message.select') }} {{ __('message.role') }}</option>
                  @foreach($user_roles as $dkey=>$dval)
                  <option value="{{ $dval->id }}">{{ $dval->rolename }}</option>
                  @endforeach
                </select>
            </div>
            <div class="row">
              <label for="user_pass">{{ __('message.password') }} <span class="required_filed"> * </span></label>
              <input type="password" name="password" id="password" value="" class="form-control" autocomplete="off" maxlength="20">
            </div>
            <div class="row">
              <label for="user_pass">{{ __('message.confirm_new_password') }} <span class="required_filed"> * </span></label>
              <input type="password" name="confirm_password" id="confirm_password" value="" class="form-control" autocomplete="off" maxlength="20">
            </div>
            <div class="row" style="margin-top:10px">
              <div class="col-xl-9"></div>
              <div class="col-xl-1">
                <button type="button" class="btn btn-default" data-bs-dismiss="modal">{{ __('message.close') }}</button>
              </div>
              <div class="col-xl-2">
                <button type="submit" class="btn btn-primary submit_btn_meeting">{{ __('message.submit') }}</button>
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

<div id="myModal" class="modal fade">
	<div class="modal-dialog modal-confirm">
		<div class="modal-content">
			<div class="modal-header flex-column">
				<div class="icon-box">
					<i class="material-icons">&#xE5CD;</i>
				</div>						
				<h4 class="modal-title w-100">{{ __('message.are_you_sure') }}</h4>	
                <button type="button" class="close" data-bs-dismiss="modal" aria-hidden="true">&times;</button>
			</div>
			<div class="modal-body">
				<p>{{ __('message.delete_msg') }}</p>
			</div>
			<div class="modal-footer justify-content-center">
				<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('message.cancle') }}</button>
				<button type="button" class="btn btn-danger deleteBtn" data-dept-id="">{{ __('message.delete') }}</button>
			</div>
		</div>
	</div>
</div> 

{{-- <script src="{{asset('js/3.2.1.jquery.min.js')}}"></script> --}}
<script src="{{asset('js/jquery.min.js')}}"></script>

<script type="text/javascript">

  $(document).ready(function(){

    $(".selected-option").click(function() {
      $(".options-list").toggle();
    });

    $(".option").click(function() {
      var value = $(this).data("value");
      $(".selected-option").text($(this).text());
      $(".options-list").hide();
      $('#selectedDeptId').val(value);
    
     if(value !== ""){
            const url = "{{ route('user.role') }}";
            $.ajax({
              type: 'GET',
              // dataType: 'json',
              data: {id:value},
              url: url,
              success: function (response) {
                console.log(response);
                  $("#role_list").empty(); // Clear existing options before appending new ones
                  $("#role_list").append('<option value="">Select Role</option>');

                  $.each(response.role, function (index, role) {
                      $("#role_list").append('<option value="' + role.id + '">' + role.rolename + '</option>');
                  });
              },
                error: function () {
                    console.log('Error: Unable to delete department.');
                }
            });
     }
    });

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
            const url = "{{ route('user.destroy', ':id') }}".replace(':id', id);
              $.ajax({
                type: 'DELETE',
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
  });

  function fn_show_add_user() {
    $('form #user_name').val('');
    $('form #user_pass').val('');
    $('#add_user').modal('show');
  }
</script>
@endsection
