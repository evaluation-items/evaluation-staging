@extends('dashboards.eva-dir.layouts.evaldir-dash-layout')
@section('title','Upload CsPro')
@section('content')
<style>
  .content-wrapper{
     background-image: url("{{asset('img/2.jpg')}}");
   }
   .error{
    color: #dd3131;
   }
</style>
<div class="content  d-flex flex-column flex-column-fluid" id="kt_content">
    <!--begin::Subheader-->
    <div class="subheader py-2 py-lg-6  subheader-solid " id="kt_subheader">
      <div class=" container-fluid  d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
        <!--begin::Info-->
        <div class="d-flex align-items-center flex-wrap mr-1">
          <!--begin::Page Heading-->
          <div class="d-flex justify-content-center align-items-center">
            <!--begin::Page Title-->
            <h5 class="text-dark font-weight-bold">{{ __('message.cspro_detail_upload') }}</h5>
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
              <h5>{{ __('message.cspro_desc') }}</h5>
            </div>
          </div>
          {{-- <div class="row"> --}}
            @if (session()->has('success'))
							<div class="alert alert-success">
								{{ session()->get('success') }}
							</div>
					@endif
      
          {{-- @session('error')
              <div class="alert alert-danger" role="alert"> 
                  {{ $value }}
              </div>
          @endsession  --}}
            @if($errors->any())
            <div class="alert alert-danger" role="alert"> 
                <h4>{{$errors->first()}}</h4>
            </div>
            @endif
            @if(session('validation_mismatches'))
                <div class="alert alert-danger">
                    <strong>Validation failed. Unexpected columns found:</strong>
                    <ul>
                        @foreach(session('validation_mismatches') as $mismatch)
                            <li>{{ $mismatch }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
          {{-- </div> --}}
          <div class="card-body">
            <form method="POST" action="{{route('evaldir.upload-cspro')}}" id="csproFrm" enctype="multipart/form-data">
                <input type="hidden" name="_token" value="{{csrf_token()}}">
                
            <div class="row">
                <div class="col-sm-4">
                    <div class="form-group">
                        <label for="title">{{ __('message.enter_title') }} <span class="required_filed"> * </span> </label>
                    </div>
                </div>
                <div class="col-sm-8">
                    <div class="form-group">
                          <input type="text"class="form-control pattern" name="title" id="title" placeholder="Enter Title" />
                        @error('title')
                            <div class="text-danger">* {{ $message }}</div>
                         @enderror
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-4">
                  <div class="form-group">
                      <label for="csdb_file">{{ __('message.upload_multiple_csdb_file') }} <span class="required_filed"> * </span> </label>
                  </div>
                </div>
                <div class="col-sm-8">
                    <div class="form-group">
                        <div class="custom-file">
                          <input type="file" class="custom-file-input file_type_name" name="cspro_csdb_file[]" id="cspro_csdb_file" accept=".csdb" multiple />
                          <label class="custom-file-label" for="cspro_csdb_file">{{ __('message.choose_file') }}</label>
                        </div>
                        @error('cspro_csdb_file')
                            <div class="text-danger">* {{ $message }}</div>
                         @enderror
                    </div>
                </div>
               
            </div>
            <div class="row">
              <div class="col-sm-4">
                <div class="form-group">
                    <label for="csdb_file">{{ __('message.please_upload_dcf_file') }} <span class="required_filed"> * </span> </label>
                </div>
              </div>
                <div class="col-sm-8">
                    <div class="form-group">
                        <div class="custom-file">
                          <input type="file" class="custom-file-input file_type_name" name="cspro_dcf_file" id="cspro_dcf_file" accept=".dcf" />
                          <label class="custom-file-label" for="cspro_dcf_file">{{ __('message.choose_file') }}</label>
                        </div>
                        @error('cspro_dcf_file')
                            <div class="text-danger">* {{ $message }}</div>
                         @enderror
                    </div>
                </div>
                
              </div>
              <div class="row"  style="text-align:center;margin-left: 40%;margin-top: 19px;">
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary btn-sm">{{ __('message.upload') }}</button>
                        {{-- <input type="submit" class="btn btn-primary" value="Upload"> --}}
                    </div>
              </div>
            </form>
          </div>
        </div>
        <!--end::Card-->
      </div>
      <!--end::Container-->
    </div>
    <!--end::Entry-->
</div>
<!--end::Content-->
<script src="{{asset('js/jquery.min.js')}}"></script>
<script>
    $(document).ready(function () {
        $('.file_type_name').on('change', function () {
          var fileName = $(this).val().split('\\').pop();
          console.log(fileName);
          $(this).next('.custom-file-label').html(fileName);
        });
        $("#csproFrm").validate({
            rules: {
                title: {
                    required: true,
                    maxlength: 255
                },
                cspro_csdb_file: {
                    required: true,
                    extension: "csdb",
                    filenameMatch: /\.csdb$/i, // Ensure filename ends with .csdb
                    filesize: 5242880 // 5MB
                },
                cspro_dcf_file: {
                    required: true,
                    extension: "dcf",
                    filenameMatch: /\.dcf$/i, // Ensure filename ends with .dcf
                    filesize: 5242880 // 5MB
                }
            },
            messages: {
                title: {
                    required: "Title is required.",
                    maxlength: "Title must be less than 255 characters."
                },
                cspro_csdb_file: {
                    required: "Please upload a CSDB file.",
                    extension: "Only .csdb files are allowed.",
                    filenameMatch: "Invalid file name. It must end with .csdb.",
                    filesize: "CSDB file size must be less than 5MB."
                },
                cspro_dcf_file: {
                    required: "Please upload a DCF file.",
                    extension: "Only .dcf files are allowed.",
                    filenameMatch: "Invalid file name. It must end with .dcf.",
                    filesize: "DCF file size must be less than 5MB."
                }
            },
            submitHandler: function (form) {
                form.submit();
            }
        });
    
        // Custom method for file size validation
        $.validator.addMethod("filesize", function (value, element, param) {
            if (element.files.length > 0) {
                return element.files[0].size <= param;
            }
            return true;
        }, "File size must be less than 5MB.");
    
        // Custom method to validate file name
        $.validator.addMethod("filenameMatch", function (value, element, param) {
            return param.test(value);
        }, "Invalid file name.");
    });
    </script>
    
@endsection