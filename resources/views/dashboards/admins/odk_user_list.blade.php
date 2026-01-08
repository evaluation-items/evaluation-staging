@extends('dashboards.admins.layouts.admin-dash-layout')
@section('title','Dashboard')
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
                     ODK User List                	            
                    </h5>
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
                      <div class="row">
                        <div class="card-body">
                      <div class="col-lg-12">
                        <div class="row">
                          <div class="col-lg-3">
                            <button type="buton" style="margin:10px" class="btn btn-primary" onclick="fn_show_add_user()">Add Odk User</button>
                          </div>
                          <div class="col-lg-9">
                            @if(Session::has('email_err'))
                              <h4 style="color:red">{{ Session::get('email_err') }}</h4>
                            @endif
                          </div>
                        </div>
                        <table class="table table-bordered table-hover show-datatable">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Email</th>
                                    <th>Password</th>
                                    <th>Status</th>
                                    <th>Delete</th>
                                </tr>
                                <tbody>
                                  @if ($users_list ->count() > 0) 
                                    @foreach ( $users_list as $user)  
                                    @php
                                        $btn_on_off = $user->status ? 'data-on-label="Active"' : 'data-off-label="Inactive"';
                                    @endphp
                                    <tr>
                                      <td>{{$user->id}}</td>
                                      <td>{{$user->email}}</td>
                                      <td>{{$user->password}}</td>
                                      <td>
                                        <input type="checkbox" data-id="{{$user->id}}" class="status_item" id="switch{{$loop->iteration}}" switch="bool" {{ $user->status ? 'checked' : '' }}>
                                        <label for="switch{{$loop->iteration}}" {!! $btn_on_off !!}></label>
                                      </td>
                                      <td>
                                        <a href="#myModal" class="btn btn-xs btn-danger trigger-btn" data-id= "{{$user->id}}" data-bs-toggle="modal">Delete</a>
                                      </td>
                                  </tr>
                                  @endforeach
                                @endif
                                </tbody>
                            </thead>
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

<div class="modal fade" id="add_user" style="display: none;" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Add User</h4>
        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="container">
          <form id="Odkusers" method="post" enctype="multipart/form-data" action="{{ route('admin.add_odk_user') }}" autocomplete="off">
            @csrf
          
            <div class="row">
                <label for="email">Email *:</label>
                <input type="email" name="email" id="email" value="" class="form-control" maxlength="150" autocomplete="off">
            </div>
            <div class="row">
              <label for="password">Password *:</label>
              <input type="password" name="password" id="password" value="" class="form-control" autocomplete="off" maxlength="20">
            </div>
            <div class="row">
              <label for="user_pass">Confirm Password *:</label>
              <input type="password" name="confirm_password" id="user_confirm_pass" value="" class="form-control" autocomplete="off" maxlength="20">
            </div>
            <div class="row">
							<div class="col-xl-12" style="text-align: right;margin-top:10px;">
								<button type="submit" class="btn btn-primary">Submit</button>
								<button type="button" class="btn btn-default" data-bs-dismiss="modal">Close</button>
							</div>
						</div>
            {{-- <div class="row">
          
              <div class="col-xl-12">
                <button type="submit" class="btn btn-primary">Submit</button>
                <button type="button" class="btn btn-default" data-bs-dismiss="modal">Close</button>
              </div>
            </div> --}}
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
				<h4 class="modal-title w-100">Are you sure?</h4>	
                <button type="button" class="close" data-bs-dismiss="modal" aria-hidden="true">&times;</button>
			</div>
			<div class="modal-body">
				<p>Do you really want to delete these records? This process cannot be undone.</p>
			</div>
			<div class="modal-footer justify-content-center">
				<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
				<button type="button" class="btn btn-danger deleteBtn" data-user-id="">Delete</button>
			</div>
		</div>
	</div>
</div> 
@endsection
{{-- <script src="{{asset('js/3.2.1.jquery.min.js')}}"></script> --}}
<script src="{{asset('js/jquery.min.js')}}"></script>
<script type="text/javascript">

  $(document).ready(function(){
    var ktcontent = $("#kt_content").height();
    // $(".content-wrapper").css('min-height',ktcontent);

    $('.status_item').on('change',function(){
        var status = this.checked;
        if(status){
          var id = $(this).data('id');
            const url = "{{ route('admin.status', ':id') }}".replace(':id', id);
            $.ajax({
              type: 'POST',
              dataType: 'json',
              data: {status:status},
              url: url,
                success: function (response) {
                    alert(response.success);
                    location.reload();
                },
                error: function () {
                    console.log('Error: Unable to delete department.');
                }
            });
        }else{
          alert("Something went wrong");
        }
       
    });

    $('.trigger-btn').on('click', function () {
        var user_id = $(this).data('id');
        console.log(user_id);
        var deleteBtn = $('.deleteBtn');
        var deleteId = deleteBtn.data('user-id');
        deleteBtn.data('user-id', user_id);
    });

  });

  function fn_show_add_user() {
    $('form #user_name').val('');
    $('form #user_pass').val('');
    $('#add_user').modal('show');
  }
 
</script>