@extends('dashboards.admins.layouts.admin-dash-layout')
@section('title','Evaluation User List')
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
                     {{ __('message.user_list')}}                	            
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
                <div class="container">  
                    <!--begin::Card-->
                    <div class="card card-custom gutter-b">
                        <div class="card-body">
                            <form method="post" id="frmaddUser" enctype="multipart/form-data" action="{{ route('evaluation_user.store') }}" autocomplete="off" >
                                @csrf
                                <input type="hidden" name="role_manage" value="" class="role_manage">
                                <div class="row form-group">
                                <label for="user_name">{{ __('message.name')}} </label>
                                <input type="text" name="name" class="form-control pattern" value="" id="user_name" maxlength="100" autocomplete="off">
                                </div>
                                <div class="row">
                                    <label for="user_email">{{ __('message.email')}}  <span class="required_filed"> * </span></label>
                                    <input type="email" name="email" id="user_email" value="" class="form-control" maxlength="150" autocomplete="off">
                                </div>
                                <div class="row">
                                    <label for="dept_id">{{ __('message.department')}}  <span class="required_filed"> * </span></label>
                                    <select name="dept_id" class="form-control">
                                        <option value="">{{ __('message.select_department')}} </option>
                                        <option value="0">{{ __('message.evaluation')}}  {{ __('message.department')}} </option>
                                    </select>
                                </div>
                                <div class="row">
                                    <label for="role">{{ __('message.designation')}}  <span class="required_filed"> * </span></label>
                                    <select name="role" class="form-control" id="role_items">
                                        <option value="">{{ __('message.select')}}  {{ __('message.role')}} </option>
                                        @forelse($role as $key => $role_item)
                                            <option value="{{ $role_item->id }}">{{ $role_item->rolename }}</option>
                                        @empty
                                            <option value="" disabled>No roles found</option>
                                        @endforelse
                                    </select>
                                </div>
                                <div class="row">
                                        <label for="user_pass">{{ __('message.password')}}  <span class="required_filed"> * </span></label>
                                        <input type="password" name="password" id="password" value="" class="form-control" autocomplete="off" maxlength="20">
                                </div>
                                <div class="row">
                                        <label for="user_pass">{{ __('message.confirm_new_password')}}  <span class="required_filed"> * </span></label>
                                        <input type="password" name="confirm_password" id="confirm_password" value="" class="form-control" autocomplete="off" maxlength="20">
                                </div>
                                <div class="row" style="margin-top:10px">
                               
                                <div class="col-xl-2">
                                    <button type="submit" class="btn btn-primary submit_btn_meeting">{{ __('message.submit')}} </button>
                                </div>
                                </div>
                            </form>
                        </div>
                </div>
            <!--end::Container-->
          </div>
        <!--end::Entry-->
        </div>
    </div>
    @endsection
    {{-- <script src="{{asset('js/3.2.1.jquery.min.js')}}"></script> --}}
<script src="{{asset('js/jquery.min.js')}}"></script>

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