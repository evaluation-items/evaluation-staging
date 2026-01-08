@extends('dashboards.admins.layouts.admin-dash-layout')
@section('title','Publication Branch Edit')
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
                Publication Branch Edit
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
                         
                            <form action="{{ route('publication.update',[Crypt::encrypt($publication->id)]) }}" method="POST" id="frmPublication" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                
                                <div class="row form-group">
                                    <label for="dept_id">Select Department <span class="required_filed"> * </span> :</label>
                                    <select name="dept_id" id="dept_id" class="form-control">
                                        <option value="">Select Department</option>
                                        @foreach($departments as $dkey=>$dval)
                                            <option value="{{ $dval->dept_id }}" {{ ($dval->dept_id == $publication->dept_id) ? 'selected' : '' }}>
                                                {{ $dval->dept_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            
                                <div class="row form-group">
                                    <label for="branch_name">Branch Name :</label>
                                    <input type="text" name="branch_name" class="form-control pattern" value="{{ $publication->branch_name }}" id="branch_name" maxlength="1000" autocomplete="off">
                                </div>
                            
                                <div class="row form-group">
                                    <label for="year">Year<span class="required_filed"> * </span> :</label>
                                    <input type="text" name="year" class="form-control pattern" value="{{ $publication->year }}" id="year" maxlength="1000" autocomplete="off">
                                </div>
                            
                                <div class="row form-group">
                                    <label>Upload Document <span class="required_filed"> * </span>:</label>
                                    <div class="custom-file">
                                        <input type="file" name="pdf_document" class="custom-file-input file_type_name" id="pdf_document" autocomplete="off">
                                        <label class="custom-file-label" for="pdf_document">Choose file</label>
                                    </div>
                                    <input type="hidden" id="existing_file" value="{{ $publication->pdf_document }}">
                            
                                    @if (!empty($publication->pdf_document))
                                        <a href="{{ route('get_the_publication_document',[ $publication->pdf_document]) }}" target="_blank" title="{{ $publication->pdf_document }}">
                                            <i class="fas fa-file-pdf fa-2x" style="color:red;"></i>
                                        </a>
                                    @endif
                                </div>
                            
                                <div class="row">
                                    <div class="col-xl-12" style="text-align: right; margin-top:15px;">
                                        <button type="submit" class="btn btn-primary submit_btn_meeting">Submit</button>
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