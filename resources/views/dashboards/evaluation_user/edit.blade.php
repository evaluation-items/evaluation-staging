@extends('dashboards.admins.layouts.admin-dash-layout')
@section('title','Evalution User Edit')
 
@section('content')

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
             {{ __('message.edit')}}  {{ __('message.evaluation')}} {{ __('message.user')}} 
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
                         
                            <form action="{{ route('evaluation_user.update',[$user->id]) }}" method="POST" id="">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="role_manage" value="{{$user->role_manage}}" class="role_manage">

                                <div class="row form-group">
                                    <label for="dept_name">{{ __('message.user')}}  {{ __('message.name')}} :</label>
                                    <input type="text" name="name" class="form-control pattern" value="{{$user->name}}" id="name" maxlength="100" autocomplete="off">
                                </div>
                                <div class="row form-group">
                                    <label for="dept_name">{{ __('message.user')}}  {{ __('message.email')}} : <span class="required_filed"> * </span></label>
                                    <input type="text" name="email" class="form-control pattern" value="{{$user->email}}" id="email" maxlength="100" autocomplete="off">
                                </div>
                                <div class="row">
                                     <label for="dept_id">{{ __('message.department')}}  <span class="required_filed"> * </span></label>
                                        <select name="dept_id" class="form-control">
                                            <option value="">{{ __('message.select_department')}} </option>
                                            <option value="0" {{($user->dept_id == 0) ? 'selected' : ''}} >{{ __('message.evaluation')}}  {{ __('message.department')}} </option>
                                        </select>
                                </div>
                                <div class="row">
                                    <label for="role">{{ __('message.designation')}}  <span class="required_filed"> * </span></label>
                                    <select name="role" class="form-control" id="role_items">
                                        <option value="">{{ __('message.select')}}  {{ __('message.role')}} </option>
                                        @forelse($role as $key => $role_item)
                                            <option value="{{ $role_item->id }}"  {{($user->role == $role_item->id) ? 'selected' : ''}}>{{ $role_item->rolename }}</option>
                                        @empty
                                            <option value="" disabled>No roles found</option>
                                        @endforelse
                                    </select>
                                </div>
                                <div class="row">
                                    <label for="user_pass">{{ __('message.password')}} </label>
                                    <input type="hidden" name="user_password" value="{{$user->password}}" class="form-control">

                                    <input type="password" name="password" id="user_pass" value="" class="form-control" autocomplete="off" maxlength="20">
                                  </div>
                                  <div class="row">
                                    <label for="user_pass">{{ __('message.confirm_new_password')}}</label>
                                    <input type="password" name="confirm_password" id="user_confirm_pass" value="" class="form-control" autocomplete="off" maxlength="20">
                                  </div>
                                <div class="row">
                                    <div class="col-xl-12" style="text-align: right; margin-top:15px;">
                                        <button type="submit" class="btn btn-primary submit_btn_meeting">{{ __('message.submit')}} </button>
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
<script src="{{asset('js/jquery-laest-version.min.js')}}"></script>

    <script>
        $(document).ready(function() {
            // Attach change event to the select element
            $('#role_items').change(function() {
                // Get the selected value
                var selectedValue = $(this).val();
    
                // Determine the corresponding value for role_manage
                var roleManageValue = determineRoleManageValue(selectedValue);
    
                // Set the value for the hidden input
                $('.role_manage').val(roleManageValue);
            });
    
            // Function to determine role_manage value based on the selected value
            function determineRoleManageValue(selectedValue) {
                // Use a switch statement or if-else conditions to map selected values to role_manage values
                switch (selectedValue) {
                    case '23':
                    case '24':
                        return '2';
                    case '25':
                    case '26':
                    case '27':
                    case '28':
                    case '29':
                    case '30':
                    case '31':
                        return '3';
                    case '32':
                    case '33':
                    case '34':
                    case '35':
                    case '36':
                    case '37':
                    case '38':
                    case '39':
                    case '40':
                        return '4';
                    default:
                        return '';
                }
            }
        });
    </script>