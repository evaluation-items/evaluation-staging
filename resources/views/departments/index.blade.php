@extends('dashboards.admins.layouts.admin-dash-layout')
@section('title','Departments')
@section('content')
<div class="content  d-flex flex-column flex-column-fluid" >
      <!--begin::Subheader-->
      <div class="subheader py-2 py-lg-6  subheader-solid " id="kt_subheader">
        <div class=" container-fluid  d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
          <!--begin::Info-->
          <div class="d-flex align-items-center flex-wrap mr-1">
            <!--begin::Page Heading-->
            <div class="d-flex align-items-baseline flex-wrap mr-5">
              <!--begin::Page Title-->
              <h5 class="text-dark font-weight-bold my-1 mr-5">
                {{ __('message.department') }} {{ __('message.list') }}	                	            
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
                  <button type="buton" style="margin:10px" class="btn btn-primary" onclick="fn_show_add_department()">{{ __('message.add') }} {{ __('message.department') }}
                  </button>
                </div>
              </div>
              <div class="row">
                <div class="card-body">
                  <div class="col-lg-12">
                      <table class="table table-bordered table-striped dataTable dtr-inline" id="example1">
                          <thead>
                              <tr>
                                <th>{{ __('message.no') }}</th>
                                <th>{{ __('message.name') }}</th>
                                <th>{{ __('message.actions') }}</th>
                              </tr>
                            <tbody>
                              @php  $i= 1; @endphp
                              @if ($departments->count() > 0) 
                                @foreach ($departments as $department)
                                <tr>
                                    <td>{{ $i++ }}</td>
                                    <td>{{ $department->dept_name }}</td>
                                    <td>
                                      <a href="{{ route('departments.show',$department->dept_id) }}" class="btn btn-xs btn-info" style="display:inline-block">{{ __('message.view') }}</a>
                                      <a href="{{ route('departments.edit',$department->dept_id) }}" class="btn btn-xs btn-primary">{{ __('message.edit') }}</a>
                                      @if($i > 27)
                                        <a href="{{ route('departments.destroy',$department->dept_id) }}" class="btn btn-xs btn-danger trigger-btn"  onclick="event.preventDefault(); document.getElementById('delete-form-{{ $department->dept_id }}').submit();">
                                          {{ __('message.delete') }}
                                        </a>
                                      
                                        <form id="delete-form-{{$department->dept_id }}" action="{{ route('departments.destroy', $department->dept_id) }}" method="POST" style="display: none;">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                      @endif
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

        
<div class="modal fade" id="add_department" style="display: none;" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">{{ __('message.add') }} {{ __('message.department') }}</h4>
        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="container">
          <form id="frmDept" method="post" enctype="multipart/form-data" action="{{ route('departments.store') }}" autocomplete="off">
            @csrf
            <div class="row form-group">
              <label for="dept_name">{{ __('message.department') }} {{ __('message.name') }}: <span class="required_filed"> * </span></label>
              <input type="text" name="dept_name" class="form-control pattern" value="" id="dept_name" maxlength="100" autocomplete="off">
            </div>
            {{-- <div class="row">
                <label for="user_dept">Department</label>
                <select name="department" class="form-control">
                  <option value="">Select Department</option>
                  @foreach($departments as $dkey=>$dval)
                  <option value="{{ $dval->dept_id }}">{{ $dval->dept_name }}</option>
                  @endforeach
                </select>
            </div> --}}
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
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>


<!-- End sub Department -->
<!-- Modal HTML -->
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
				<button type="button" class="btn btn-danger deleteBtn" data-dept-id="">{{ __('message.delete') }}</button>
			</div>
		</div>
	</div>
</div> 

<script src="{{asset('js/jquery.min.js')}}"></script>
<script type="text/javascript">

  $(document).ready(function(){
      // var ktcontent = $("#kt_content").height();
      // $(".content-wrapper").css('min-height',ktcontent);

      $('.trigger-btn').on('click', function () {
        var dept_id = $(this).data('id');
        console.log(dept_id);
        var deleteBtn = $('.deleteBtn');
        var deleteId = deleteBtn.data('dept-id');

        // if (deleteId !== "") {
        //     deleteBtn.data('dept-id', "");
        // } else {
            deleteBtn.data('dept-id', dept_id);
        //}
    });
    
    });
    function fn_show_add_department() {

      $('#add_department').modal('show');
    }

</script>
@endsection