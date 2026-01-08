@extends('dashboards.admins.layouts.admin-dash-layout')
@section('title','District')
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
                     {{ __('message.district') }} {{ __('message.list') }}                	            
                    </h5>
                    <!--end::Page Title-->                  
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
                <div class=" container ">  
                    <!--begin::Card-->
                    <div class="card card-custom gutter-b">
                      <div class="row">
                        <div class="card-body">
                      <div class="col-lg-12">
                        <div class="row">
                          <div class="col-lg-3">
                            <button type="buton" id="addDistrict" style="margin:10px" class="btn btn-primary">{{ __('message.add') }} {{ __('message.district') }}</button>
                          </div>
                          <div class="col-lg-9">
                            @if(Session::has('email_err'))
                              <h4 style="color:red">{{ Session::get('email_err') }}</h4>
                            @endif
                          </div>
                        </div>
                        <table class="table table-bordered table-striped dataTable dtr-inline" id="example1" style="text-align: center;">
                            <thead>
                                <tr>
                                  <th width="5%">{{ __('message.no') }}</th>
                                  <th width="10%">{{ __('message.state') }} {{ __('message.name') }}</th>
                                  <th width="10%">{{ __('message.name') }}</th>
                                  <th width="10%">{{ __('message.actions') }}</th>
                                </tr>
                              <tbody>
                                @php  $i= 1; @endphp
                                @if ($district_list->count() > 0) 
                                  @foreach ($district_list as $district)
							  
                                  <tr>
                                      <td>{{ $i++ }}</td>
                                      <td>{{ $district->state->name }}</td>
                                      <td>{{ $district->name_e }}</td>
                                      <td>
                                        <a href="{{ route('district.show',$district->id ) }}" class="btn btn-xs btn-info" style="display:inline-block">{{ __('message.view') }}</a>
                                        <a href="{{ route('district.edit',$district->id ) }}" class="btn btn-xs btn-primary">{{ __('message.edit') }}</a>
                                       <a href="#myModal" class="btn btn-xs btn-danger trigger-btn" data-id= "{{$district->id}}"data-bs-toggle="modal">{{ __('message.delete') }}</a> 

                                         <form id="delete-form-{{$district->id }}" action="{{ route('district.destroy',$district->id ) }}" method="POST" style="display: none;">
                                            @csrf
                                            @method('DELETE')
                                        </form> 
                                      </td>
                                  </tr>
                                  @endforeach
                                @endif
                              </tbody>
                        </table>
                      </div>
                    </div>
                  </div>
                  </div>
                <!--end::Container-->
              </div>
            <!--end::Entry-->
            </div>
        </div>

<div class="modal fade" id="add_district" style="display: none;" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">{{ __('message.add') }} {{ __('message.district') }}</h4>
        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="container">
          <form id="Districtfrm" method="post" enctype="multipart/form-data" action="{{ route('district.store') }}" autocomplete="off">
            @csrf
            <div class="row">
              <label for="name">{{ __('message.state') }} {{ __('message.name') }} <span class="required_filed"> * </span> : </label>
              <select class="stateId form-control" name="state_id">
                <option value="">{{ __('message.select') }} {{ __('message.state') }}</option>
                @foreach ($state_list as $key => $state)
                  <option value="{{ $state->state_code }}">{{ $state->name }}</option>
                @endforeach
              </select>
            </div>
            <div class="row">
              <label for="dcode">{{ __('message.district') }} {{ __('message.code') }} <span class="required_filed"> * </span> : </label>
              <input type="text" name="dcode" id="dcode" value="" class="form-control pattern" autocomplete="off">
            </div>
            <div class="row">
                <label for="name_e">{{ __('message.name') }} <span class="required_filed"> * </span> : </label>
                <input type="text" name="name_e" id="name_e" value="" class="form-control pattern" autocomplete="off">
            </div>
            <div class="row">
                <div class="col-xl-12" style="text-align: right;margin-top:10px;">
                    <button type="submit" class="btn btn-primary">{{ __('message.submit') }}</button>
                    <button type="button" class="btn btn-default" data-bs-dismiss="modal">{{ __('message.close') }}</button>
                </div>
			</div>
          </form>
        </div>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>

<div id="myModal" class="modal fade">
	<div class="modal-dialog modal-confirm">
		<div class="modal-content">
			<div class="modal-header flex-column">
				<div class="icon-box">
					<i class="material-icons">&#xE5CD;</i>
				</div>						
				<h4 class="modal-title w-100">{{ __('message.are_you_sure') }}</h4>	
                <button type="button" class="close" data-bs-dismiss="modal" aria-hidden="true">&times;</button>
			</div>
			<div class="modal-body">
				<p>{{ __('message.delete_msg') }}</p>
			</div>
			<div class="modal-footer justify-content-center">
				<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('message.cancel') }}</button>
				<button type="button" class="btn btn-danger deleteBtn" data-district-id="">{{ __('message.delete') }}</button>
			</div>
		</div>
	</div>
</div> 
@endsection

{{-- <script src="{{asset('js/3.2.1.jquery.min.js')}}"></script> --}}
<script src="{{asset('js/jquery.min.js')}}"></script>
<script type="text/javascript">

  $(document).ready(function(){

    $('.trigger-btn').on('click', function () {
        var district_id = $(this).data('id');
        
        var deleteBtn = $('.deleteBtn');
        var deleteId = deleteBtn.data('district-id');
        deleteBtn.data('district-id',district_id);
        
    });
    $('.deleteBtn').on('click', function () {
        const id = $(this).data('district-id');
        $('#delete-form-' + id).submit();
        $('#myModal').modal('hide');
      });
    $('#addDistrict').on('click',function(){
         $('#add_district').modal('show');
    });
  });
</script>