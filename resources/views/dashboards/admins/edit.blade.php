@extends('dashboards.admins.layouts.admin-dash-layout')
@section('title','User Edit')
 
@section('content')
@php
  use Illuminate\Support\Facades\Crypt;
@endphp
<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
    <!--begin::Subheader-->
    <div class="subheader py-2 py-lg-6  subheader-solid " id="kt_subheader">
      <div class=" container-fluid  d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
        <!--begin::Info-->
        <div class="d-flex align-items-center flex-wrap mr-1">
          <!--begin::Page Heading-->
          <div class="d-flex align-items-baseline flex-wrap mr-5">
            <!--begin::Page Title-->
            <h5 class="text-dark font-weight-bold my-1 mr-5">
              {{ __('message.user') }} {{ __('message.edit') }}    
            </h5>
            <!--end::Page Title-->                  
          </div>
          <!--end::Page Heading-->
        </div>
        <!--end::Info-->
      </div>
    </div>
    <div class="d-flex flex-column-fluid">
        <!--begin::Container-->
        <div class=" container ">
            <div class="card card-custom card-transparent">
                <div class="card-body p-10">
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

                    <div class="row">
                        <div class="col-xl-12 col-xxl-12">
                            @if($errors->any())
                            @foreach($errors->all() as $key=>$value)
                                <ul>
                                <li style="text-danger">
                                    {{ $value }}
                                </li>
                                </ul>
                            @endforeach
                            @endif
                         
                            <form action="{{ route('user.update',[Crypt::encrypt($user->id)]) }}" method="POST" id="frmUser">
                                @csrf
                                @method('PUT')
                                <div class="row form-group">
                                    <label for="dept_name">{{ __('message.user') }} {{ __('message.name') }}:</label>
                                    <input type="text" name="name" class="form-control pattern" value="{{$user->name}}" id="name" maxlength="100" autocomplete="off">
                                </div>
                                <div class="row form-group">
                                    <label for="dept_name">{{ __('message.user') }} {{ __('message.email') }}: <span class="required_filed"> * </span></label>
                                    <input type="text" name="email" class="form-control pattern" value="{{$user->email}}" id="email" maxlength="100" autocomplete="off">
                                </div>
                                <div class="row">
                                     <label for="user_dept">{{ __('message.department') }} <span class="required_filed"> * </span></label>
                                    <div class="custom-dropdown">
                                        <div class="selected-option">{{ __('message.select_department') }}</div>
                                        <input type="hidden" name="selected_dept_id" id="selectedDeptId" value="{{ $user->dept_id }}">
                                        <ul class="options-list" id="departmentDropdown">
                                          @foreach($department as $dkey=>$dval)
                                              <li class="option" data-value="{{ $dval->dept_id }}" {{($dval->dept_id == $user->dept_id) ? 'selected' : ''}}>{{ $dval->dept_name }}</li>
                                          @endforeach
                                          <li class="option evaluation" data-value="0" {{($user->dept_id == 0) ? 'selected' : ''}}>{{ __('message.evaluation') }} {{ __('message.department') }}</li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="row">
                                    <label for="user_dept">{{ __('message.designation')}} <span class="required_filed"> * </span></label>
                                    <input type="hidden" name="role" id="roleId" value="{{ $user->role }}">
                                    <select name="role" class="form-control" id="role_list">
                                        <option value="">{{ __('message.select') }} {{ __('message.role') }}</option>
                                      </select>
                                </div>
                                <div class="row">
                                    <label for="user_pass">{{ __('message.password') }}</label>
                                    <input type="hidden" name="user_password" value="{{$user->password}}" class="form-control">

                                    <input type="password" name="password" id="user_pass" value="" class="form-control" autocomplete="off" maxlength="20">
                                  </div>
                                  <div class="row">
                                    <label for="user_pass">{{ __('message.confirm_new_password') }}</label>
                                    <input type="password" name="confirm_password" id="user_confirm_pass" value="" class="form-control" autocomplete="off" maxlength="20">
                                  </div>
                                <div class="row">
                                    <div class="col-xl-12" style="text-align: right; margin-top:15px;">
                                        <button type="submit" class="btn btn-primary submit_btn_meeting">{{ __('message.submit') }}</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
{{-- <script src="{{asset('js/3.2.1.jquery.min.js')}}"></script> --}}
<script src="{{asset('js/jquery.min.js')}}"></script>
<script type="text/javascript">

  $(document).ready(function(){
    role();
    var selectedDeptId = $("#selectedDeptId").val();
    var selectedDeptName = $("#departmentDropdown li[data-value='" + selectedDeptId + "']").text();
    if(selectedDeptName != ""){
      $(".selected-option").text(selectedDeptName);
    }
    $('#departmentDropdown').on('click', '.option', function () {
        var selectedDeptId = $(this).data('value');
        $('#selectedDeptId').val(selectedDeptId);
    });

    $(".selected-option").click(function() {
      $(".options-list").toggle();
    });

    $(".option").click(function() {
        
      var value = btoa($(this).data("value"));
      $(".selected-option").text($(this).text());
      $(".options-list").hide();

     if(value !== ""){
            const url = "{{ route('user.role') }}";
            $.ajax({
              type: 'GET',
              // dataType: 'json',
              data: {id:value,param:'edit'},
              url: url,
              success: function (response) {
              
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


  });
function role(){
      const url = "{{ route('user.role') }}";
      const value = btoa($('#roleId').val());
      const dept_id =  $("#selectedDeptId").val();
        $.ajax({
            type: 'GET',
            data: { id: value ,dept_id:dept_id},
            url: url,
            success: function (response) {
                $("#role_list").empty(); // Clear existing options before appending new ones
                $("#role_list").append('<option value="">Select Role</option>');

                $.each(response.role, function (index, role) {
                  var selectedAttribute = (role.id == $('#roleId').val()) ? 'selected' : '';
                  $("#role_list").append('<option value="' + role.id + '" ' + selectedAttribute + '>' + role.rolename + '</option>');
                });
            },
            error: function () {
                console.log('Error: Unable to fetch roles.');
            }
        });

}

</script>