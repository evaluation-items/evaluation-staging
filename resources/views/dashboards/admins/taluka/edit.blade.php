@extends('dashboards.admins.layouts.admin-dash-layout')
@section('title','Taluka Edit')
 
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
               {{ __('message.taluka') }} {{ __('message.edit') }}
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
                            <form action="{{ route('taluka.update', ['taluka' => $taluka->tid]) }}" method="POST" id="talukaFrm">
                                @csrf
                                @method('PUT')
                                <div class="row">
                                    <label for="name">{{ __('message.state') }} {{ __('message.name') }} <span class="required_filed"> * </span> : </label>
                                    <select class="state form-control" name="state_id">
                                      <option value="">{{ __('message.select') }} {{ __('message.state') }}</option>
                                      @foreach ($state_list as $key => $state)
                                         <option value="{{ $state->state_code }}" {{ ($state_code == $state->state_code) ? 'selected' : ''}}>{{ $state->name }}</option>
                                      @endforeach
                                    </select>
                                  </div>
                                  <div class="row">
                                    <label for="name">{{ __('message.district') }} {{ __('message.name') }} <span class="required_filed"> * </span> : </label>
                                    <select class="district form-control" name="dcode">
                                      <option value="">{{ __('message.select') }} {{ __('message.district') }}</option>
                                      <option value="{{ $taluka->dcode }}" selected>{{ $district_name }}</option>
                                    </select>
                                  </div>
                                  <div class="row">
                                      <label for="tname_e">{{ __('message.name') }} <span class="required_filed"> * </span> : </label>
                                      <input type="text" name="tname_e" id="tname_e" value="{{$taluka->tname_e}}" class="form-control pattern" autocomplete="off">
                                  </div>
                                  <div class="row">
                                      <div class="col-xl-12" style="text-align: right;margin-top:10px;">
                                          <button type="submit" class="btn btn-primary">{{ __('message.submit') }}</button>
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
<script>
    $(document).ready(function(){
          //State name
    $('.state').on('change',function(){
      var state_code = $(this).val();
      const url = "{{ route('get_district') }}";
      if(state_code != ""){
        $.ajax({
                type: 'GET',
                url: url,
                data:{'state_code':state_code},
                headers: {
                  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (response) {
                  $('.district').empty();
                  if (!$.isEmptyObject(response)) {
                      $.each(response, function(key, value) {   
                          $('.district').append($("<option></option>").attr("value", value).text(key)); 
                      });
                  } else {
                      $('.district').append($("<option></option>").text('Select District'));
                  }
                },
                error: function () {
                    console.log('Error: Unable to delete department.');
                }
              });
      }
    });
    });
</script>