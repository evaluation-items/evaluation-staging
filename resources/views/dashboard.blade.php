@extends('dashboards.proposal.layouts.sidebar') 
@section('title','Dashboard')
@section('content')
<style>
    .info-box{
        background-color: bisque;
        font-size: 18px;
        min-height: 100% !important;
    }
    .col-md-3 {
        margin-bottom: 15px !important;
    }
    .content-wrapper{
      background-image: url("{{asset('img/2.jpg')}}");
    }
.ribbon {
    position: absolute;
    z-index: 100;
    width: 100px;
    height: 100px;
    overflow: hidden;
}

.ribbon.top-left {
  top: -2.6px;
  left: -5px;
}

.ribbon.top-left.ribbon-primary > small {
  *zoom: 1;
  filter: progid:DXImageTransform.Microsoft.gradient(gradientType=0, startColorstr='#FF428BCA', endColorstr='#FF2A6496');
  background-image: -moz-linear-gradient(top, #428bca 0%, #2a6496 100%);
  background-image: -webkit-linear-gradient(top, #428bca 0%, #2a6496 100%);
  background-image: linear-gradient(to bottom, #428bca 0%, #2a6496 100%);
  position: absolute;
  display: block;
  width: 100%;
  padding: 8px 10px;
  text-align: center;
  text-transform: uppercase;
  font-weight: bold;
  font-size: 65%;
  color: white;
  background-color: #428bca;
  -moz-transform: rotate(-45deg);
  -ms-transform: rotate(-45deg);
  -webkit-transform: rotate(-45deg);
  transform: rotate(-45deg);
  -moz-box-shadow: 0 3px 6px -3px rgba(0, 0, 0, 0.5);
  -webkit-box-shadow: 0 3px 6px -3px rgba(0, 0, 0, 0.5);
  box-shadow: 0 3px 6px -3px rgba(0, 0, 0, 0.5);
  top: 16px;
  left: -27px;
}
.ribbon.top-left.ribbon-primary > small:before, .ribbon.top-left.ribbon-primary > small:after {
  position: absolute;
  content: " ";
}
.ribbon.top-left.ribbon-primary > small:before {
  left: 0;
}
.ribbon.top-left.ribbon-primary > small:after {
  right: 0;
}
.ribbon.top-left.ribbon-primary > small:before, .ribbon.top-left.ribbon-primary > small:after {
  bottom: -3px;
  border-top: 3px solid #0e2132;
  border-left: 3px solid transparent;
  border-right: 3px solid transparent;
}
.welcome-box{border-radius:0;}
.bb-0{border-bottom: 0px transparent;}
.welcome-box-body{padding: 15px 85px 105px;width: 100%;}
.welcome-box-body .modal-title {font-size: 36px;color: #3B444E;letter-spacing: 0;line-height: 40px;margin-bottom: 20px;}
.welcome-box-body p {font-size: 20px;color: #3B444E;line-height: 30px;}
.modal-dialog { top: 20% !important; font-family: ui-monospace;}

</style>
@php 
  $user_dept_id = Auth::user()->dept_id; 
  $dept_name =  department_name($user_dept_id);
  $user = Auth::user();
@endphp
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-12">
                <h1 class="m-0 text-center font-weight-bold"><U>{{$dept_name}} | <?php echo strtoupper(__('message.dashboard')); ?></U></h1>
            </div>
            <div class="col-sm-12">
              @php
                $name = App\Models\Advertisement::active()->get();
              @endphp
             
            </div>
        </div>
    </div>
</div>
    <section class="content">
        <div class="container-fluid">
            <div class="row col-md-12" style="margin-bottom: 0;float:left;"> 
                <div class="col-12 col-sm-6 col-md-4 mb-3">
                    <div class="info-box">
                        <span class="info-box-icon bg-lightblue elevation-1"><i class="fab fa-wpforms"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">{{ __('message.add_requisition')}}</span>
                            <a href="{{ route('schemes.create') }}"> <span class="info-box-number">
                               {{ __('message.add_new_requisition_form')}}
                            </span></a>
                        </div>
                    </div>
                </div>
                
                <div class="col-12 col-sm-6 col-md-4 mb-3">
                    <div class="info-box">
                        <span class="info-box-icon bg-info elevation-1"><i class="fas fa-align-justify"></i></span>
                        <div class="info-box-content">
                            <a href="{{ route('proposals', ['param' => 'new']) }}" > <span class="info-box-text"> {{ __('message.no_of_new_proposals')}}</span></a>
                            <span class="info-box-number">
                                {{$new_count}}
                            </span>
                        </div>
                    </div>
                </div>
                
                <div class="col-12 col-sm-6 col-md-4 mb-3">
                    <div class="info-box">
                        <span class="info-box-icon bg-success elevation-1"><i class="fas fa-fast-forward"></i></span>
                        <div class="info-box-content">
                            <a href="{{ route('proposals', ['param' => 'forward']) }}" > <span class="info-box-text">{{ __('message.no_of_forwraded_proposals')}}</span></a>
                            <span class="info-box-number">
                                {{$forward_count}}
                            </span>
                        </div>
                    </div>
                </div>
                  
                
                  <div class="col-12 col-sm-6 col-md-4 mb-3">
                      <div class="info-box mb-3">
                          <span class="info-box-icon bg-orange elevation-1"><i class="fas fa-fast-backward"></i></span>
                          <div class="info-box-content">
                              <a href="{{ route('proposals', ['param' => 'return']) }}" > <span class="info-box-text">{{ __('message.no_of_returned_proposals')}}</span></a>
                              <span class="info-box-number">{{$return_count}}</span>
                          </div>
                      </div>
                  </div>
                
    
                <div class="clearfix hidden-md-up"></div>
                <div class="col-12 col-sm-6 col-md-4 mb-3">
                    <div class="info-box mb-3">
                        <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-tasks"></i></span>
                        <div class="info-box-content">
                        <a href="{{ route('proposals', ['param' => 'on_going']) }}" ><span class="info-box-text">{{ __('message.no_of_on-going_studies')}}</span></a>
                            <span class="info-box-number">{{$ongoing_count}}</span>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-sm-6 col-md-4 mb-3">
                    <div class="info-box mb-3">
                        <span class="info-box-icon bg-secondary elevation-1"><i class="fas fa-list"></i></span>
                        <div class="info-box-content">
                            <a href="{{ route('proposals', ['param' => 'completed']) }}" ><span class="info-box-text">{{ __('message.no_of_complete_studies')}}</span></a>
                            <span class="info-box-number">{{$completed_count}}</span>
                        </div>
                    </div>
                </div> 
              </div>
              {{-- <div class="row col-md-3" style="height: 216px;">
                <div class="adverties" style="border: 1px solid #000;">
                    @if($name->count() > 0)
                    <marquee  behavior="scroll" direction="up" class="" scrolldelay="500" style="font-size:20px;color:#000; height: 100%;">
                       @foreach ($name as $key => $item)
                          {{++$key}}.  {{$item->name}}  @if($item->is_adverties == '1') <img src="{{asset('img/new.gif')}}" width="30px" height="30px"> @endif <br> 
                       @endforeach
                    </marquee>
                    @endif
                  </div>
              </div> --}}
            
            <div class="row col-md-12"> 
                <div id="accordion" class="col-md-12">
                  <div class="card">
                    <div class="card-header bg-info text-center" id="headingTwo">
                      <h5 class="mb-0">
                        <button class="btn collapsed text-white" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                            {{ __('message.detail_reports')}}
                        </button>
                        @php
                          $draft_id_str = implode(',', $draft_id);
                          $draft_id_str =  Crypt::encryptString($draft_id_str)  ?? '';
                        @endphp
                        <a href="{{route('summary_export',['draft_id' => $draft_id_str])}}" class="btn btn-success float-left">{{ __('message.export_report')}}</a>
                      </h5>
                    </div>
                   
                    <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion">
                      <div class="card-body">
                        <div class="form-group">
                            <label for="scheme_list">{{ __('message.select_scheme')}} <span class="required_filed"> * </span> : </label>
                            <select class="form-control scheme_list" name="scheme" id="scheme_list">
                              {{-- <option>Select Scheme </option> --}}
                              @foreach ($scheme_list as $key => $scheme_item)
                                  <option value="{{ $key}}">{{ $scheme_item}}</option>
                              @endforeach
                            </select>
                        </div>
                        <div class="chartItems" style="display: none;">
                              <div class="card card-success">
                                  <div class="card-header">
                                      <h3 class="card-title">{{ __('message.bar_chart')}}</h3>
                                      <div class="card-tools">
                                          <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                          <i class="fas fa-minus"></i>
                                          </button>
                                          <button type="button" class="btn btn-tool" data-card-widget="remove">
                                          <i class="fas fa-times"></i>
                                          </button>
                                      </div>
                                  </div>
                                  <div class="card-body">
                                      <div class="chart">
                                        <canvas id="barChart" style="min-height: 500px; height:  500px; max-height:  500px; max-width: 100%;"></canvas>
                                      </div>
                                  </div>
                              </div>
                          </div>
                          
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
    </section>
    <div id="welcome-user" class="modal fade" role="dialog">
        <div class="modal-dialog">
          <div class="modal-content welcome-box">
            <div class="modal-header bb-0">
            <span class="ribbon top-left ribbon-primary">
                <small>Hello!</small>
            </span> 
              <button type="button btnClose" class="close" data-bs-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body welcome-box-body">
                <h4 class="modal-title text-center" style="line-height:0px !important;">Welcome</h4>
                <h4 class="modal-title text-center" style="font-size:24px !important;">The Directorate Of Evaluation Portal</h4>
                <p class="text-center">Start your journey with our dashboard by exploring its functionalities.</p>
            </div>
          </div>
        </div>
    </div>
<script src="{{asset('plugins/jquery/jquery.min.js')}}"></script>
<script src="{{asset('plugins/chart.js/Chart.min.js')}}"></script>
<Script>
  
Chart.defaults.global.datasets.bar.categoryPercentage = 0.95;
  var barChart;
  function donutChart(){

    const chartLabels = @json(__('message.chart_labels'));
    const delayLabel = "{{ __('message.delay_stage_sop_count') }}";
    const earlyLabel = "{{ __('message.early_stage_completion_count') }}";
    var areaChartData = {
      labels  : chartLabels,
      datasets: [
        {
          label               : delayLabel, //Delay
          backgroundColor     : 'rgba(60,141,188,0.9)',
          borderColor         : 'rgba(60,141,188,0.8)',
          pointRadius          : false,
          pointColor          : '#3b8bba',
          pointStrokeColor    : 'rgba(60,141,188,1)',
          pointHighlightFill  : '#fff',
          pointHighlightStroke: 'rgba(60,141,188,1)',
        //  data                : [28, 48, 40, 19, 86, 27, 90]
        },
       // {
        //   label               : earlyLabel, //grey
        //   backgroundColor     : 'rgba(210, 214, 222, 1)',
        //   borderColor         : 'rgba(210, 214, 222, 1)',
        //   pointRadius         : false,
        //   pointColor          : 'rgba(210, 214, 222, 1)',
        //   pointStrokeColor    : '#c1c7d1',
        //   pointHighlightFill  : '#fff',
        //   pointHighlightStroke: 'rgba(220,220,220,1)',
       //   data                : [65, 59, 80, 81, 56, 55, 40]
        //},
      ]
    }
   
     //-------------
    //- BAR CHART -
    //-------------
    var barChartCanvas = $('#barChart').get(0).getContext('2d')
    var barChartData = $.extend(true, {}, areaChartData);    

    var barChartOptions = {
        responsive: true,
        maintainAspectRatio: false,
        datasetFill: false,
        scales: {
          xAxes: [{
            barPercentage: 0.5, // Adjust the value as needed (0.5 means 50% of available space)
            categoryPercentage: 0.8, // Adjust the value as needed (0.8 means 80% of available space)
            scaleLabel: {
              display: true,
              labelString: '<-- Department Action for Evaluation Study -->',
              fontColor: 'green', 
                fontFamily:"monospace",
                fontSize: 17,
                padding: {
                  top: 5  
                }
            },
            ticks: {
                callback: function(label, index, labels) {
                    if (/\s/.test(label)) {
                      return label.split(" ");
                    }else{
                      return label;
                    }              
                  },
                    beginAtZero:true,
                    fontColor: 'black',
                    fontSize: 16,
                    fontFamily:"monospace", 
                },
            // Other X-axis configurations
          }],
          yAxes: [{
              ticks: {
                  min: 0,
                  max: 300,
                  stepSize: 30,          // 🔒 compulsory
                  padding: 15,           // 🔴 creates gap between labels
                  fontColor: 'black',
                  fontSize: 16,
                  fontFamily: "monospace",
                  beginAtZero: true
              },
              gridLines: {
                  drawBorder: true,
                  lineWidth: 1,
                  color: 'rgba(0,0,0,0.1)',
                  zeroLineColor: 'rgba(0,0,0,0.25)',
                  tickMarkLength: 10     // 🔴 extra vertical space
              },
              scaleLabel: {
                  display: true,
                  labelString: '<-- No. of Days -->',
                  fontColor: 'green',
                  fontFamily: "monospace",
                  fontSize: 17,
                  padding: {
                      top: 10,
                      bottom: 10
                  }
              },
              afterFit: function (scale) {
                  scale.height += 25;    // 🔴 FORCE extra Y-axis space
              }
          }]
        }
      };

      barChart = new Chart(barChartCanvas, {
        type: 'bar',
        data: barChartData,
        options: barChartOptions
    })
  
  }

$(document).ready(function(){
    var barChartCanvas = $('#barChart').get(0).getContext('2d');
    $('#collapseTwo').collapse('show');
    $('#headingTwo button').removeClass('collapsed');
    $('#headingTwo button').attr('aria-expanded', 'true');
    withoutOnchange();
});

function withoutOnchange(){
    var draft_id = $('#scheme_list').val();
        draft_id =  btoa(draft_id);
               if (draft_id) {
                 donutChart();
                const url = "{{ route('get-donutchart-count', ':draft_id') }}".replace(':draft_id', draft_id);
                $.ajax({
                    type: 'POST',
                    dataType: 'json',
                    url: url,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (data) {
                          // Update the chart data
                   barChart.data.datasets[0].data = [
                        data['requistion_sent_hod'],
                        data['study_entrusted'],
                        data['draft_report'],
                        data['draft_report_send'],
                    ];

                    
                    // Update the chart
                    barChart.update();
                        $('.chartItems').css('display','block');
                    },
                    error: function (xhr, status, error) {
                      alert(error);
                    }
                });
        } else {
             alert('Please select Scheme');
        }
}
$('#scheme_list').on('change', function () {
        var draft_id = $(this).val();
        draft_id =  btoa(draft_id);
               if (draft_id) {
                 donutChart();
                const url = "{{ route('get-donutchart-count', ':draft_id') }}".replace(':draft_id', draft_id);
                $.ajax({
                    type: 'POST',
                    dataType: 'json',
                    url: url,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (data) {
                          // Update the chart data
                    barChart.data.datasets[0].data = [
                        data['requistion_sent_hod'],
                        data['study_entrusted'],
                        data['draft_report'],
                        data['draft_report_send'],
                    ];

                  

                    // Update the chart
                    barChart.update();
                    $('.chartItems').css('display','block');
                    },
                    error: function (xhr, status, error) {
                      alert(error);
                    }
                });
        } else {
             alert('Please select Scheme');
        }
       });
</script>
@if($user->welcome_popup)
<script>
 $(document).ready(function() {

    let modal = $('#welcome-user');

    modal.modal('show');

    setTimeout(function () {

        modal.modal('hide');

        // ✅ Update DB after popup closes
        $.ajax({
            url: "{{ route('welcome.popup.seen') }}",
            type: "POST",
            data: {
                _token: "{{ csrf_token() }}"
            }
        });

    }, 3000); // 3 seconds
});
</script>
@endif
@endsection