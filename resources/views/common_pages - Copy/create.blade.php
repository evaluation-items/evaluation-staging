@extends('dashboards.admins.layouts.admin-dash-layout')
@section('title',isset($menuItem) ? 'Edit Menu': "Create Menu")
@section('content')
<link rel="stylesheet" href="{{asset('plugins/summernote/summernote-bs4.min.css')}}">

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
                {{ isset($menuItem) ? __('message.edit') . ' ' . __('message.menu') : __('message.create') . ' ' . __('message.menu') }}
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
                           @if (isset($menuItem))
                           <form action="{{ route('menu-item.update',$menuItem) }}" method="POST">
                            @method('PUT')
                           @else
                            <form action="{{ route('menu-item.store') }}" method="POST">

                           @endif
                                @csrf
                                <div class="row">
                                    <div class="form-group">
                                        <div class="col-xl-12">
                                            <label for="name">{{ __('message.menu')}} {{ __('message.name')}}: <span class="required_filed"> * </span></label>
                                            <input type="text" name="name" value="{{isset($menuItem) ? $menuItem->name : ""}}" class="form-control pattern" id="name" maxlength="100" autocomplete="off">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group">
                                        <div class="col-xl-12">
                                            <label for="description">{{ __('message.description')}}: <span class="required_filed"> * </span></label>
                                            <textarea id="summernote">{{isset($menuItem) ? $menuItem->description : ""}}</textarea>  
                                            {{-- <textarea id="summernote" class="description" name="description">{{isset($menuItem) ? $menuItem->description : ""}}</textarea>   --}}
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group">
                                        <div class="col-xl-12">
                                            <button type="submit" class="btn btn-primary submit_btn_meeting">{{ __('message.submit')}}</button>
                                            <button type="button" class="btn btn-default" data-bs-dismiss="modal">{{ __('message.close')}}</button>
                                        </div>
                                    </div>
                                    {{-- <div class="col-xl-12" style="text-align: right; margin-top:15px;">
                                       
                                    </div> --}}
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="{{asset('js/jquery.min.js')}}"></script>
<script src="{{asset('plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<script src="{{asset('plugins/summernote/summernote-bs4.min.js')}}"></script>
{{-- <script src="{{asset('js/ckeditor.js')}}"></script> --}}
<script>
     $('#summernote').summernote();
    // CKEDITOR.replace('description', {
    //   height: 400,
    //   width:1000,
    //   baseFloatZIndex: 10005,
    //   removeButtons: 'PasteFromWord'
    // });
  </script>
@endsection