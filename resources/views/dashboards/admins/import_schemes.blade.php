@extends('dashboards.admins.layouts.admin-dash-layout')
@section('title','Import Scheme')
@section('content')
<div class="content  d-flex flex-column flex-column-fluid">
    <!--begin::Subheader-->
    <div class="subheader py-2 py-lg-6  subheader-solid " id="kt_subheader">
      <div class=" container-fluid  d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
        <!--begin::Info-->
        <div class="d-flex align-items-center flex-wrap mr-1">
          <!--begin::Page Heading-->
          <div class="d-flex align-items-baseline flex-wrap mr-5">
            <!--begin::Page Title-->
            <h5 class="text-dark font-weight-bold my-1 mr-5">
             Upload Scheme Data                	            
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
 
    @if(Session::has('error'))
      <div class="alert alert-danger">
          @if(is_array(Session::get('error')))
              <ul>
                  @foreach(Session::get('error') as $error)
                      <li>{{ $error }}</li>
                  @endforeach
              </ul>
          @else
              {{ Session::get('error') }}
          @endif
      </div>
  @endif

    <!--begin::Entry-->
    <div class="d-flex flex-column-fluid">
        <!--begin::Container-->
        <div class=" container ">  
            <!--begin::Card-->
          <div class="card card-custom gutter-b">
           
            <div class="row">
              <div class="card-body">
                <div class="col-lg-12">
                    {{-- <form action="{{ route('import') }}" method="POST" enctype="multipart/form-data"> --}}
                      <form action="{{ route('department_hod.import') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group mb-4">
                            <div class="custom-file text-left">
                                <input type="file" name="file" class="custom-file-input" id="customFile">
                                <label class="custom-file-label" for="customFile">Choose file</label>
                            </div>
                        </div>
                        <button class="btn btn-primary">Import</button>
                        {{-- <a class="btn btn-success" href="{{ route('export') }}">Sample file</a> --}}
                    </form>
                </div>
              </div>
            </div>
          </div>
        <!--end::Container-->
      </div>
    <!--end::Entry-->
    </div>
</div>

@endsection