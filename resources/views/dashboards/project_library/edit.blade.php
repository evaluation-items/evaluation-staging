@extends('dashboards.admins.layouts.admin-dash-layout')
@section('title','Edit Project Detail')
 
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
                {{ __('message.edit')}} {{ __('message.project')}} {{ __('message.detail')}}            
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
                         
                            <form action="{{ route('digital_project_libraries.update',[$digitalProjectLibrary->id]) }}" method="POST" id="frmProject" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')

                                <div class="row">
                                    <label for="study_name">{{ __('message.name')}} <span class="required_filed"> * </span> :</label>
                                    <input type="text" name="study_name" class="form-control pattern" value="{{$digitalProjectLibrary->study_name}}" id="study_name" maxlength="1000" autocomplete="off">
                                </div>
                                <div class="row">
                                    <label for="dept_id">{{ __('message.department')}} <span class="required_filed"> * </span> :</label>
                                    <select name="dept_id" id="dept_id" class="form-control">
                                    <option value="">{{ __('message.select_department')}}</option>
                                    @foreach($departments as $dkey=>$dval)
                                    <option value="{{ $dval->dept_id }}" {{($digitalProjectLibrary->dept_id == $dval->dept_id) ? 'selected' : ''}}>{{ $dval->dept_name }}</option>
                                    @endforeach

                                    </select>
                                </div>
                                <div class="row">
                                    <label for="year">{{ __('message.year')}} <span class="required_filed"> * </span> :</label>
                                    <select class="form-control" id="year" name="year">
                                        <option value="">{{ __('message.select_year')}}</option>
                                        @foreach($financial_years as $fy)
                                            <option value="{{ $fy }}" {{($digitalProjectLibrary->year == $fy) ? 'selected' : ''}}>{{ $fy }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="row">
                                    <label for="org_name">{{ __('message.organization_name')}} <span class="required_filed"> * </span> :</label>
                                    <input type="text" name="org_name" class="form-control pattern" value="{{$digitalProjectLibrary->org_name}}" id="org_name" maxlength="1000" autocomplete="off">
                                </div>
                                <div class="row">
                                    <label>{{__('message.upload_file')}} <span class="required_filed"> * </span>:</label><br> <span style="color: #5b6064;margin-left: 8px;">{{__('message.upload_multi_file')}}</span>
                                    <div class="custom-file">
                                      <input type="file" class="custom-file-input file_type_name" id="upload_file" name="upload_file[]" multiple accept=".pdf"/>
                                      <label class="custom-file-label" for="customFile">{{ __('message.choose_file')}}</label>
                                    </div>
                                    <input type="hidden" id="existing_file" value="{{ $digitalProjectLibrary->upload_file }}">

                                    <div class="form-group">                                      
                                        @php
                                           $items =  explode(',', $digitalProjectLibrary->upload_file);
                                           $rand_val = explode(',', $digitalProjectLibrary->rand_val);
                                        @endphp
                                        @if(!empty($items) && count($items) > 0)
                                        @foreach($items as $kgrs => $file)
                                            @if(isset($rand_val[$kgrs]))
                                                <a href="{{ route('get_the_document', [Crypt::encrypt($rand_val[$kgrs]), $file]) }}" target="_blank" title="{{ $file }}">
                                                    <i class="fas fa-file-pdf fa-2x" style="color:red;"></i>
                                                </a>
                                            @endif
                                        @endforeach
                                        @endif
                                   
                                    </div>
                                 
                                </div>
                                <div class="row">
                                    <label>{{ __('message.upload_picture')}} :</label>
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input file_type_name" id="upload_picture" name="upload_picture[]" multiple accept=".jpg,.jpeg,.png"/>
                                        <label class="custom-file-label" for="customFile">{{ __('message.choose_file')}}</label>
                                    </div>
                                    <div class="form-group">                                      
                                        @php
                                            $items = array_filter(explode(',', $digitalProjectLibrary->upload_picture));
                                        @endphp
                                        @if(!empty($items))
                                            @foreach($items as $kgrs => $file)
                                              <img src="{{route('get_images',[$file])}}" width="100px" height="100px">
                                            @endforeach
                                        @endif
                                    </div>
                                </div>
                               
                                <div class="row" style="margin-top: 44px;margin-left: 93%;">
                                    <div class="col-xl-2">
                                        <button type="submit" class="btn btn-primary submit_btn_meeting">{{ __('message.submit')}}</button>
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
        $('.file_type_name').on('change', function () {
        // Get the selected file name
        var fileName = $(this).val().split('\\').pop();
        // Update the custom file label with the selected file name
        $(this).next('.custom-file-label').html(fileName);
        });
    });
</script>