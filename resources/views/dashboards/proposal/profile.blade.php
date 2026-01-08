@extends('dashboards.proposal.layouts.sidebar')
@section('title','Profile')
<style>
   .content-wrapper{
      background-image: url("{{asset('img/2.jpg')}}");
    }
</style>
@section('content')


    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h1>Profile</h1>
            </div>
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{ __('message.home') }}</a></li>
                <li class="breadcrumb-item active">{{ __('message.concern_department') }} {{ __('message.profile') }}</li>
              </ol>
            </div>
          </div>
        </div><!-- /.container-fluid -->
      </section>
  
      <!-- Main content -->
      <section class="content">
        <div class="container-fluid">
          <div class="row">
            <div class="col-md-3">
  
              <!-- Profile Image -->
              <div class="card card-primary card-outline">
                <div class="card-body box-profile">
                  <div class="text-center">
                    <img class="profile-user-img img-fluid img-circle admin_picture" src="{{ Auth::user()->picture }}" alt="User profile picture">
                  </div>
                  <h3 class="profile-username text-center admin_name">{{Auth::user()->name}}</h3>
                  <p class="text-muted text-center">{{ __('message.concern_department') }}</p>
                  <input type="file" name="admin_image" id="admin_image" style="opacity: 0;height:1px;display:none">
                  <a href="javascript:void(0)" class="btn btn-primary btn-block" id="change_picture_btn"><b>{{ __('message.change_picture') }}</b></a>
                  
                </div>
                <!-- /.card-body -->
              </div>
              <!-- /.card -->
  
          
            </div>
            <!-- /.col -->
            <div class="col-md-9">
              <div class="card">
                <div class="card-header p-2">
                  <ul class="nav nav-pills">
                    <li class="nav-item"><a class="nav-link active" href="#personal_info"data-bs-toggle="tab">{{ __('message.personal_information') }}</a></li>
                    <li class="nav-item"><a class="nav-link" href="#change_password"data-bs-toggle="tab">{{ __('message.change_password') }}</a></li>
                  </ul>
                </div><!-- /.card-header -->
                <div class="card-body">
                  <div class="tab-content">
                    <div class="active tab-pane" id="personal_info">
                      <form class="form-horizontal" method="POST" action="{{ route('UpdateInfo') }}" id="AdminInfoForm">
                        <div class="form-group row">
                          <label for="inputName" class="col-sm-2 col-form-label">{{ __('message.name') }}</label>
                          <div class="col-sm-10">
                            <input type="text" class="form-control pattern" id="inputName" placeholder="Name" value="{{ Auth::user()->name }}" name="name">
                            <span class="text-danger error-text name_error"></span>
                          </div>
                        </div>
                        <div class="form-group row">
                          <label for="inputEmail" class="col-sm-2 col-form-label">{{ __('message.email') }}</label>
                          <div class="col-sm-10"> 
                            <input type="text" class="form-control pattern" id="inputEmail" placeholder="Email" value="{{ Auth::user()->email }}" name="email">
                            <span class="text-danger error-text email_error"></span>
                          </div>
                        </div>
                        <div class="form-group row">
                          <label for="phoneNumber" class="col-sm-2 col-form-label">{{ __('message.phone_number') }}</label>
                          <div class="col-sm-10">
                            <input type="text" class="form-control pattern" id="phoneNumber" placeholder="Enter Phone Number" value="{{ Auth::user()->phone }}" name="phone">
                            <span class="text-danger error-text phone_error"></span>
                          </div>
                        </div>
                        <div class="form-group row">
                          <div class="offset-sm-2 col-sm-10">
                            <button type="submit" class="btn btn-success">{{ __('message.save_changes') }}</button>
                          </div>
                        </div>
                      </form>
                    </div>
                    <!-- /.tab-pane -->
                    <div class="tab-pane" id="change_password">
                        <form class="form-horizontal" action="{{ route('ChangePassword') }}" method="POST" id="changePasswordAdminForm">
                          <div class="form-group row">
                            <label for="oldpassword" class="col-sm-2 col-form-label">{{ __('message.old_password') }}</label>
                            <div class="col-sm-10">
                              <input type="password" class="form-control" id="oldpassword" placeholder="Enter current password" name="oldpassword">
                              <span class="text-danger error-text oldpassword_error"></span>
                            </div>
                          </div>
                          <div class="form-group row">
                            <label for="newpassword" class="col-sm-2 col-form-label">{{ __('message.new_password') }}</label>
                            <div class="col-sm-10">
                              <input type="password" class="form-control" id="newpassword" placeholder="Enter new password" name="newpassword">
                              <span class="text-danger error-text newpassword_error"></span>
                            </div>
                          </div>
                          <div class="form-group row">
                            <label for="cnewpassword" class="col-sm-2 col-form-label">{{ __('message.confirm_new_password') }}</label>
                            <div class="col-sm-10">
                              <input type="password" class="form-control" id="cnewpassword" placeholder="ReEnter new password" name="cnewpassword">
                              <span class="text-danger error-text cnewpassword_error"></span>
                            </div>
                          </div>
                          <div class="form-group row">
                            <div class="offset-sm-2 col-sm-10">
                              <button type="submit" class="btn btn-success">{{ __('message.update_password') }}</button>
                            </div>
                          </div>
                        </form>
                      </div>
                  </div>
                  <!-- /.tab-content -->
                </div><!-- /.card-body -->
              </div>
              <!-- /.card -->
            </div>
            <!-- /.col -->
          </div>
          <!-- /.row -->
        </div><!-- /.container-fluid -->
      </section>
      <!-- /.content -->

@endsection