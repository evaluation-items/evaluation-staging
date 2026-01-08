@extends('dashboards.admins.layouts.admin-dash-layout')
@section('title','Village Edit')
 
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
                {{ __('message.village') }} {{ __('message.edit') }} 
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
                            <form action="{{ route('village.update', ['village' => $village->id]) }}" method="POST" id="villageFrm">
                                @csrf
                                @method('PUT')
                                <div class="row">
                                    <label for="name">{{ __('message.district') }} {{ __('message.name') }} <span class="required_filed"> * </span> : </label>
                                    <select class="district form-control" name="dcode">
                                      <option value="">{{ __('message.select') }} {{ __('message.district') }}</option>
                                      @foreach ($district_list as $key => $district)
                                      <option value="{{$key}}" {{ ($village->dcode == $key) ? 'selected' : ''}}>{{$district}}</option>
                                       @endforeach
                                    </select>
                                  </div>
                                  <div class="row">
                                    <label for="name">{{ __('message.taluka') }} {{ __('message.name') }} <span class="required_filed"> * </span> : </label>
                                    <select class="talukaList form-control" name="tcode">
                                      <option value="">{{ __('message.select') }} {{ __('message.district') }}</option>
                                         <option value="{{ $village->tcode }}" selected>{{ $taluka_name }}</option>
                                    </select>
                                  </div>
                                  <div class="row">
                                      <label for="vname_e">{{ __('message.name') }} <span class="required_filed"> * </span> : </label>
                                      <input type="text" name="vname_e" id="vname_e" value="{{$village->vname_e}}" class="form-control pattern" autocomplete="off">
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
   //Get Taluka
    $('.district').on('change',function(){
          var district_code = $(this).val();
          if(district_code != ""){
            $.ajax({
              type:'POST',
              dataType:'json',
              url:"{{ route('get_taluka') }}",
              data:{'_token':"{{ csrf_token() }}",'taluka_dcode':district_code},
              
              success:function(response) {
                  $(".talukaList").html('');
                if(response.error != '' && response.error != undefined){
                    alert(response.error);
                }else{
                    $.each(response.talukas,function(reskey,resval){
                      $('.talukaList').append($("<option></option>").attr("value", resval.tcode) .text(resval.tname_e)); 
                    });
                }
              },
              error:function() {
                  console.log('districts ajax error');
              }
            })
          }else{
            alert('Something went wrong');
          }
      });
    });
</script>