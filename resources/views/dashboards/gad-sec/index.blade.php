
@extends('dashboards.gad-sec.layouts.gadsec-dash-layout')
@section('title','Dashboard - GAD Dashboard')

@section('content')

@php 
  $user_dept_id = Auth::user()->dept_id; 
  $dept_name =  department_name($user_dept_id);
@endphp
<style>
/* Dashboard Info Box FIX */
.info-box {
    min-height: 120px !important;
    display: flex !important;
    align-items: stretch !important;
    background-color: bisque;
}

.info-box-icon {
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
}

.info-box-content {
    display: flex !important;
    flex-direction: column !important;
    justify-content: center !important;
}

.info-box-text {
    white-space: normal !important;
    word-break: break-word !important;
    line-height: 1.3 !important;
    font-size: 15px !important;
    max-height: 42px !important;
    overflow: hidden !important;
}

.info-box-number {
    margin-top: 6px !important;
    font-size: 20px !important;
    font-weight: 600 !important;
}


    /* .info-box{
        background-color: bisque;
        font-size: 18px;
    } */
    .content-wrapper{
      background-image: url("{{asset('img/2.jpg')}}");
    }
</style>
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-12">
                <h1 class="m-0 text-center font-weight-bold"><U>{{$dept_name}} (Planning) | <?php echo strtoupper(__('message.dashboard')); ?></U></h1>
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
                        <a href="{{ route('gadsec.proposal', ['param' => 'new']) }}" > <span class="info-box-text">{{ __('message.no_of_new_proposals')}} Receive from Concern Department</span></a>
                        <span class="info-box-number">
                            {{$new_count}}
                        </span>
                    </div>

                </div>

            </div>
            
            {{-- <div class="col-12 col-sm-6 col-md-3">
                <div class="info-box">
                    <span class="info-box-icon bg-success elevation-1"><i class="fas fa-fast-forward"></i></span>
                    <div class="info-box-content">
                        <a href="{{ route('gadsec.proposal', ['param' => 'forward']) }}" > <span class="info-box-text">{{ __('message.no_of_forwraded_proposals')}}</span></a>
                        <span class="info-box-number">
                            {{$forward_count}}
                        </span>
                    </div>

                </div>
            </div> --}}
            
            <div class="col-12 col-sm-6 col-md-3">
                <div class="info-box mb-3">
                    <span class="info-box-icon bg-orange elevation-1"><i class="fas fa-fast-backward"></i></span>
                    <div class="info-box-content">
                        <a href="{{ route('gadsec.proposal', ['param' => 'return']) }}" > <span class="info-box-text" >{{ __('message.no_of_returned_proposals')}} to Concern Department</span></a>
                        <span class="info-box-number">{{$return_count}}</span>
                    </div>
                </div>
            </div>
            

            <div class="clearfix hidden-md-up"></div>
            <div class="col-12 col-sm-6 col-md-3">
                <div class="info-box mb-3">
                    <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-tasks"></i></span>
                    <div class="info-box-content">
                    <a href="{{ route('gadsec.proposal', ['param' => 'on_going']) }}" ><span class="info-box-text">{{ __('message.no_of_on-going_studies')}} at DoE</span></a>
                        <span class="info-box-number">{{$ongoing_count}}</span>
                    </div>

                </div>
            </div>

            <div class="col-12 col-sm-6 col-md-3">
                <div class="info-box">
                    <span class="info-box-icon bg-purple elevation-1"><i class="fas fa-check-circle"></i></span>
                    <div class="info-box-content">
                    <a href="{{ route('gadsec.proposal', ['param' => 'approved']) }}" ><span class="info-box-text">{{ __('message.no_of_approved_proposals')}}</span></a>
                        <span class="info-box-number">
                            {{$approved_count}}   
                        </span>
                    </div>
                </div>
            </div>

           
         <!-- <div class="col-12 col-sm-6 col-md-3">
                <div class="info-box">
                    <span class="info-box-icon bg-info elevation-1"><i class="fas fa-exchange-alt"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Transfer</span>
                        <span class="info-box-number">
                            10
                                <small>%</small>
                            </span>
                    </div>

                </div>

            </div>
            <div class="col-12 col-sm-6 col-md-3">
                <div class="info-box">
                    <span class="info-box-icon bg-info elevation-1"><i class="fas fa-check-circle"></i></span>
                    <div class="info-box-content">
                    <a href="#" ><span class="info-box-text">No. of Approved Proposals</span></a>
                        <span class="info-box-number">
                            10  
                        </span>
                    </div>

                </div>

            </div>-->

            <div class="col-12 col-sm-6 col-md-3">
                <div class="info-box mb-3">
                    <span class="info-box-icon bg-secondary elevation-1"><i class="fas fa-list"></i></span>
                    <div class="info-box-content">
                        <a href="{{ route('gadsec.proposal', ['param' => 'completed']) }}" ><span class="info-box-text">{{ __('message.no_of_complete_studies')}}</span></a>
                        <span class="info-box-number">{{$completed_count}}</span>
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
                        <h3>{{$new_count}}</h3>
                        <p>No. of New Proposals</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-person-add"></i>
                    </div>
                    <a href="{{ route('gadsec.proposal', ['param' => 'new']) }}" class="small-box-footer">View List <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>


            <div class="col-lg-3 col-6">
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3>{{$forward_count}}</h3>
                        <p>No. of Forwraded Proposals</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-stats-bars"></i>
                    </div>
                    <a href="{{ route('gadsec.proposal', ['param' => 'forward']) }}" class="small-box-footer">View List <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-orange">
                    <div class="inner">
                        <h3>{{$return_count}}</h3>
                        <p>No. of Returned Proposals</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-stats-bars"></i>
                    </div>
                    <a href="{{ route('gadsec.proposal', ['param' => 'return']) }}" class="small-box-footer">View List <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            

            <div class="col-lg-3 col-6">
                <div class="small-box bg-warning">
                    <div class="inner">
                        <h3>{{$ongoing_count}}</h3>
                        <p>No. of On-going Studies</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-person-add"></i>
                    </div>
                    <a href="{{ route('gadsec.proposal', ['param' => 'on_going']) }}" class="small-box-footer">View List <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-purple">
                    <div class="inner">
                      <h3>{{$approved_count}}</h3>
                        <p>No. of Approved Proposals</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-person-add"></i>
                    </div>
                    <a href="{{ route('gadsec.proposal', ['param' => 'approved']) }}" class="small-box-footer">View List <i class="fas fa-arrow-circle-right"></i></a>
                  </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box  bg-secondary">
                    <div class="inner">
                        <h3>{{$completed_count}}</h3>
                        <p>No. of Complete  Studies</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-pie-graph"></i>
                    </div>
                    <a href="{{  route('gadsec.proposal', ['param' => 'completed']) }}" class="small-box-footer">View List <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
        </div>
    </div>
</section> --}}

@endsection


