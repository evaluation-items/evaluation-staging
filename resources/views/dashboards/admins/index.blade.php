@extends('dashboards.admins.layouts.admin-dash-layout')
@section('title','Admin | Dashboard')
@section('content')
<style>
    .info-box{
        background-color: bisque;
        font-size: 18px;
    }
    .content-wrapper{
      background-image: url("{{asset('img/2.jpg')}}");
    }
</style>
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-12">
                <h1 class="m-0 text-center font-weight-bold"><U>ADMIN | <?php echo strtoupper(__('message.dashboard')); ?></U></h1>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12 col-sm-6 col-md-3">
                <div class="info-box">
                    <span class="info-box-icon bg-info elevation-1"><i class="fas fa-align-justify"></i></span>
                    <div class="info-box-content">
                        <a href="{{ route('admin.proposal', ['param' => 'new']) }}" > <span class="info-box-text">{{ __('message.no_of_new_proposals') }}</span></a>
                        <span class="info-box-number">
                            {{$new_count}}
                        </span>
                    </div>
                </div>
            </div>
            
            <div class="col-12 col-sm-6 col-md-3">
                <div class="info-box">
                    <span class="info-box-icon bg-info elevation-1"><i class="fas fa-users"></i></span>
                    <div class="info-box-content">
                        <a href="{{ route('admin.user_list') }}" > <span class="info-box-text">{{ __('message.no_of_users') }}</span></a>
                        <span class="info-box-number">
                            {{$user_count}}
                        </span>
                    </div>
                </div>
            </div>
            
            <div class="col-12 col-sm-6 col-md-3">
                <div class="info-box">
                    <span class="info-box-icon bg-success elevation-1"><i class="fas fa-fast-forward"></i></span>
                    <div class="info-box-content">
                        <a href="{{ route('departments.index') }}" > <span class="info-box-text">{{ __('message.no_of_departments') }}</span></a>
                        <span class="info-box-number">
                            {{$department_count}}
                        </span>
                    </div>
                </div>
            </div>
            
            <div class="col-12 col-sm-6 col-md-3">
                <div class="info-box mb-3">
                    <span class="info-box-icon bg-orange elevation-1"><i class="fas fa-fast-backward"></i></span>
                    <div class="info-box-content">
                        <a href="{{  route('subdepartments.index') }}" > <span class="info-box-text">{{ __('message.no_of_sub_departments') }}</span></a>
                        <span class="info-box-number">{{$sub_department_count}}</span>
                    </div>
                </div>
            </div>
            

            <div class="clearfix hidden-md-up"></div>
            <div class="col-12 col-sm-6 col-md-3">
                <div class="info-box mb-3">
                    <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-chalkboard-teacher"></i></span>
                    <div class="info-box-content">
                    <a href="{{ route('admin.roles') }}" ><span class="info-box-text">{{ __('message.no_of_designation') }}</span></a>
                        <span class="info-box-number">{{$role_count}}</span>
                    </div>
                </div>
            </div>
            <div class="clearfix hidden-md-up"></div>
            <div class="col-12 col-sm-6 col-md-3">
                <div class="info-box mb-3">
                    <span class="info-box-icon bg-info elevation-1"><i class="fas fa-file"></i></span>
                    <div class="info-box-content">
                    <a href="{{ route('stage_update') }}" ><span class="info-box-text">{{ __('message.update_stages') }}</span></a>
                    </div>
                </div>
            </div>
        </div>
    </div>    
</section>

{{-- <section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-3 col-6">
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3>{{$user_count}}</h3>
                        <p>No. of Users</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-person-add"></i>
                    </div>
                    <a href="{{ route('admin.user_list') }}" class="small-box-footer">View List <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>

            <div class="col-lg-3 col-6">
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3>{{$department_count}}</h3>
                        <p>No. of Departments</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-stats-bars"></i>
                    </div>
                    <a href="{{ route('departments.index') }}" class="small-box-footer">View List <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-secondary">
                    <div class="inner">
                        <h3>{{$sub_department_count}}</h3>
                        <p>No. of SubDepartment</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-stats-bars"></i>
                    </div>
                    <a href="{{ route('subdepartments.index') }}" class="small-box-footer">View List <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            

            <div class="col-lg-3 col-6">
                <div class="small-box bg-warning">
                    <div class="inner">
                        <h3>{{$role_count}}</h3>
                        <p>No. of Designation</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-person-add"></i>
                    </div>
                    <a href="{{ route('admin.roles') }}" class="small-box-footer">View List <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
           
        </div>
    </div>
</section> --}}

@endsection