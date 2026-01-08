@extends('dashboards.admins.layouts.admin-dash-layout')
@section('title','Sub-Departments Edit')
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
             {{ __('message.sub') }} {{ __('message.department') }}
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
                            <form action="{{ route('subdepartments.update',[$subdepartment->id]) }}" method="POST" id="frmSubdept">
                                @csrf
                                @method('PUT')
                                <div class="row">
                                    <label for="user_dept">{{ __('message.department') }} <span class="required_filed"> * </span></label>
                                    <select name="dept_id" class="form-control">
                                      <option value="">Select {{ __('message.department') }}</option>
                                      @foreach($departments as $dkey=>$dval)
                                        <option value="{{ $dval->dept_id }}" {{($dval->dept_id == $subdepartment->dept_id) ? 'selected' : ''}}>{{ $dval->dept_name }}</option>
                                      @endforeach
                                    </select>
                                </div>
                                <div class="row form-group">
                                <label for="dept_name">{{ __('message.department_name') }}: <span class="required_filed"> * </span></label>
                                <input type="text" name="name" class="form-control pattern" value="{{$subdepartment->name}}" id="name" maxlength="100" autocomplete="off">
                                </div>
                                <div class="row">
                                <div class="col-xl-12" style="text-align: right; margin-top:15px;">
                                    <button type="submit" class="btn btn-primary submit_btn_meeting">{{ __('message.submit') }}</button>
                                    <button type="button" class="btn btn-default" data-bs-dismiss="modal">{{ __('message.close') }}</button>
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