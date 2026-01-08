@extends('dashboards.admins.layouts.admin-dash-layout')
@section('title','Advertisements List')

@section('content')
<style>


.btn-toggle {
  margin: 0 4rem;
  padding: 0;
  position: relative;
  border: none;
  height: 1.5rem;
  width: 3rem;
  border-radius: 1.5rem;
  color: #6b7381;
  background: #bdc1c8;
}
.btn-toggle:focus,
.btn-toggle.focus,
.btn-toggle:focus.active,
.btn-toggle.focus.active {
  outline: none;
}
.btn-toggle:before,
.btn-toggle:after {
  line-height: 1.5rem;
  width: 4rem;
  text-align: center;
  font-weight: 600;
  font-size: 0.75rem;
  text-transform: uppercase;
  letter-spacing: 2px;
  position: absolute;
  bottom: 0;
  transition: opacity .25s;
}
.btn-toggle:before {
  content: 'Off';
  left: -4rem;
}
.btn-toggle:after {
  content: 'On';
  right: -4rem;
  opacity: .5;
}
.btn-toggle > .handle {
  position: absolute;
  top: 0.1875rem;
  left: 0.1875rem;
  width: 1.125rem;
  height: 1.125rem;
  border-radius: 1.125rem;
  background: #fff;
  transition: left .25s;
}
.btn-toggle.active {
  transition: background-color 0.25s;
}
.btn-toggle.active > .handle {
  left: 1.6875rem;
  transition: left .25s;
}
.btn-toggle.active:before {
  opacity: .5;
}
.btn-toggle.active:after {
  opacity: 1;
}

.btn-toggle:before,
.btn-toggle:after {
  color: #6b7381;
}
.btn-toggle.active {
  background-color: #29b5a8;
}

.btn-toggle.btn-sm {
  margin: 0 0.5rem;
  padding: 0;
  position: relative;
  border: none;
  height: 1.5rem;
  width: 3rem;
  border-radius: 1.5rem;
}
.btn-toggle.btn-sm:focus,
.btn-toggle.btn-sm.focus,
.btn-toggle.btn-sm:focus.active,
.btn-toggle.btn-sm.focus.active {
  outline: none;
}
.btn-toggle.btn-sm:before,
.btn-toggle.btn-sm:after {
  line-height: 1.5rem;
  width: 0.5rem;
  text-align: center;
  font-weight: 600;
  font-size: 0.55rem;
  text-transform: uppercase;
  letter-spacing: 2px;
  position: absolute;
  bottom: 0;
  transition: opacity .25s;
}
.btn-toggle.btn-sm:before {
  content: 'Off';
  left: -0.5rem;
}
.btn-toggle.btn-sm:after {
  content: 'On';
  right: -0.5rem;
  opacity: .5;
}
.btn-toggle.btn-sm > .handle {
  position: absolute;
  top: 0.1875rem;
  left: 0.1875rem;
  width: 1.125rem;
  height: 1.125rem;
  border-radius: 1.125rem;
  background: #fff;
  transition: left .25s;
}
.btn-toggle.btn-sm.active {
  transition: background-color 0.25s;
}
.btn-toggle.btn-sm.active > .handle {
  left: 1.6875rem;
  transition: left .25s;
}
.btn-toggle.btn-sm.active:before {
  opacity: .5;
}
.btn-toggle.btn-sm.active:after {
  opacity: 1;
}
.btn-toggle.btn-sm.btn-sm:before,
.btn-toggle.btn-sm.btn-sm:after {
  line-height: -0.5rem;
  color: #fff;
  letter-spacing: .75px;
  left: 0.4125rem;
  width: 2.325rem;
}
.btn-toggle.btn-sm.btn-sm:before {
  text-align: right;
}
.btn-toggle.btn-sm.btn-sm:after {
  text-align: left;
  opacity: 0;
}
.btn-toggle.btn-sm.btn-sm.active:before {
  opacity: 0;
}
.btn-toggle.btn-sm.btn-sm.active:after {
  opacity: 1;
}
.btn-toggle.btn-sm.btn-xs:before,
.btn-toggle.btn-sm.btn-xs:after {
  display: none;
}

.btn-toggle.btn-secondary {
  color: #6b7381;
  background: #bdc1c8;
}
.btn-toggle.btn-secondary:before,
.btn-toggle.btn-secondary:after {
  color: #6b7381;
}
.btn-toggle.btn-secondary.active {
  background-color: #ff8300;
}
</style>

@php
  use Illuminate\Support\Facades\Crypt;
@endphp
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
                     {{ __('message.advertiesment')}} {{ __('message.list')}}                	            
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
                <div class=" container ">  
                    <!--begin::Card-->
                  <div class="card card-custom gutter-b">
                    <div class="col-lg-9">
                      @if(Session::has('email_err'))
                        <h4 style="color:red">{{ Session::get('email_err') }}</h4>
                      @endif
                    </div>
                    <div class="card-header flex-wrap py-3">
                      <div class="card-toolbar">
                        <button type="buton" style="margin:10px" class="btn btn-primary" onclick="fn_show_add_advertisement()">{{ __('message.add')}} {{ __('message.advertiesment')}}</button>
                      </div>
                    </div>
                    
                    <div class="row">
                      <div class="card-body">
                        <div class="col-lg-12">
                      
                          <table class="table table-bordered table-striped dataTable dtr-inline" id="example1">
                            <thead>
                                  <tr>
                                    <th>{{ __('message.no')}}</th>
                                    <th>{{ __('message.name')}}</th>
                                    <th>{{ __('message.status')}}</th>
                                    <th>{{ __('message.new')}} {{ __('message.advertiesment')}}</th>
                                    <th>{{ __('message.actions')}}</th>
                                  </tr>
                              <tbody>
                                @if ($add_list->count() > 0) 
                                @foreach ( $add_list as $add ) 
                                @php
                                  $active = ($add->active == '1') ? 'active' : '';
                                  $true = ($add->active == '1') ? 'true' : 'false';

                                  $new_adverties_active = ($add->is_adverties == '1') ? 'active' : '';
                                  $new_adverties_true = ($add->is_adverties == '1') ? 'true' : 'false';
                                @endphp
                                <tr>
                                    <td>{{$i++}}</td>
                                    <td>{{(!is_null($add->name)) ? $add->name : '-'}}</td>
                                    <td class="text-center">
                                      <button type="button" data-id={{$add->id}} class="btn btn-sm btn-primary btn-toggle btn-toggle-status {{ $active}}"data-bs-toggle="button" aria-pressed="{{ $true }}" autocomplete="off">
                                        <div class="handle"></div>
                                      </button>
                                    </td>
                                    <td class="text-center">
                                      <button type="button" data-id={{$add->id}} class="btn btn-sm btn-primary btn-toggle btn-toggle-adverties {{ $new_adverties_active}}"data-bs-toggle="button" aria-pressed="{{ $new_adverties_true }}" autocomplete="off">
                                        <div class="handle"></div>
                                      </button>
                                    </td>
                                    <td>
                                      <a href="{{ route('advertisement.edit',Crypt::encrypt($add->id)) }}" class="btn btn-xs btn-primary">{{ __('message.edit')}}</a>
                                      <a href="#myModal" class="btn btn-xs btn-danger trigger-btn" data-id= "{{$add->id}}"data-bs-toggle="modal">{{ __('message.delete')}}</a>
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

<div class="modal fade" id="add_advertisement" style="display: none;" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">{{ __('message.add')}} {{ __('message.advertiesment')}}</h4>
        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="container">
          <form method="post" id="frmAdvertiesment" enctype="multipart/form-data" action="{{ route('admin.add_advertisement') }}" autocomplete="off" >
            @csrf
            <div class="row form-group">
              <label for="name">{{ __('message.name')}} <span class="required_filed"> * </span> :</label>
              <input type="text" name="name" class="form-control pattern" value="" id="name" maxlength="1000" autocomplete="off">
            </div>
           
            <div class="row" style="margin-top:10px">
              <div class="col-xl-9"></div>
              <div class="col-xl-1">
                <button type="submit" class="btn btn-primary submit_btn_meeting">{{ __('message.submit')}}</button>
              </div>
              <div class="col-xl-1">
                <button type="button" class="btn btn-default" data-bs-dismiss="modal">{{ __('message.close')}}</button>
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
				<h4 class="modal-title w-100">{{ __('message.are_you_sure')}}</h4>	
                <button type="button" class="close" data-bs-dismiss="modal" aria-hidden="true">&times;</button>
			</div>
			<div class="modal-body">
				<p>{{ __('message.delete_msg')}}</p>
			</div>
			<div class="modal-footer justify-content-center">
				<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('message.cancel')}}</button>
				<button type="button" class="btn btn-danger deleteBtn" data-dept-id="">{{ __('message.delete')}}</button>
			</div>
		</div>
	</div>
</div> 

{{-- <script src="{{asset('js/3.2.1.jquery.min.js')}}"></script> --}}
<script src="{{asset('js/jquery.min.js')}}"></script>

<script type="text/javascript">

    $(document).ready(function(){
    
      $('.btn-toggle-adverties').on('click',function(e){
        e.preventDefault();
        const id = btoa($(this).data('id'));
            if (id !== "") {
              const url = "{{ route('advertisement.is_adverties', ':id') }}".replace(':id', id);
                $.ajax({
                  type: 'POST',
                  dataType: 'json',
                  url: url,
                  headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                  },
                  success: function (response) {
                    alert(response.message);
                    location.reload();
                  },
                  error: function (jqXHR, exception) {
                    var msg = '';
                    if (jqXHR.status === 0) {
                        msg = 'Not connect.\n Verify Network.';
                    } else if (jqXHR.status == 404) {
                        msg = 'Requested page not found. [404]';
                    } else if (jqXHR.status == 500) {
                        msg = 'Internal Server Error [500].';
                    } else if (exception === 'parsererror') {
                        msg = 'Requested JSON parse failed.';
                    } else if (exception === 'timeout') {
                        msg = 'Time out error.';
                    } else if (exception === 'abort') {
                        msg = 'Ajax request aborted.';
                    } else {
                        msg = 'Uncaught Error.\n' + jqXHR.responseText;
                    }
                    console.log(msg)
                },
                });
            }
      })


      $('.btn-toggle-status').on('click',function(e){
        e.preventDefault();
        const id = btoa($(this).data('id'));
            if (id !== "") {
              const url = "{{ route('advertisement.status', ':id') }}".replace(':id', id);
                $.ajax({
                  type: 'POST',
                  dataType: 'json',
                  url: url,
                  headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                  },
                  success: function (response) {
                    alert(response.message);
                    location.reload();
                  },
                  error: function (jqXHR, exception) {
                    var msg = '';
                    if (jqXHR.status === 0) {
                        msg = 'Not connect.\n Verify Network.';
                    } else if (jqXHR.status == 404) {
                        msg = 'Requested page not found. [404]';
                    } else if (jqXHR.status == 500) {
                        msg = 'Internal Server Error [500].';
                    } else if (exception === 'parsererror') {
                        msg = 'Requested JSON parse failed.';
                    } else if (exception === 'timeout') {
                        msg = 'Time out error.';
                    } else if (exception === 'abort') {
                        msg = 'Ajax request aborted.';
                    } else {
                        msg = 'Uncaught Error.\n' + jqXHR.responseText;
                    }
                    console.log(msg)
                },
                });
            }
      })
        $('.trigger-btn').on('click', function () {
          var id = $(this).data('id');
          console.log(id);
          var deleteBtn = $('.deleteBtn');
          var deleteId = deleteBtn.data('dept-id');
          deleteBtn.data('dept-id', id);
      });
      $('.deleteBtn').on('click', function () {
        $('#myModal').modal('hide');
            const id = btoa($(this).data('dept-id'));
            if (id !== "") {
              const url = "{{ route('advertisement.destroy', ':id') }}".replace(':id', id);
                $.ajax({
                  type: 'POST',
                  dataType: 'json',
                  url: url,
                  headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                  },
                    success: function (response) {
                      alert(response.success);
                      location.reload();
                    },
                    error: function () {
                        console.log('Error: Unable to delete department.');
                    }
                });
            }
        });

    });
  function fn_show_add_advertisement() {
    $('#add_advertisement').modal('show');
  }
</script>
@endsection
