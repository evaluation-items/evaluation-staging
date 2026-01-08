@extends('dashboards.eva-dd.layouts.evaldd-dash-layout')
@section('title','Dashboard - Deputy Director')
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
@php
  use Illuminate\Support\Facades\Crypt;
@endphp
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-12">
                <h1 class="m-0 text-center font-weight-bold"><U>Directorate of Evaluation Deputy Director | <?php echo strtoupper(__('message.dashboard')); ?></U></h1>
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
                      <a href="{{ route('evaldd.proposal', ['param' => 'new']) }}" > <span class="info-box-text">{{ __('message.no_of_new_study')}}</span></a>
                      <span class="info-box-number">
                          {{$new_count}}
                      </span>
                  </div>
              </div>
          </div>
          <div class="col-12 col-sm-6 col-md-3">
              <div class="info-box mb-3">
                  <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-tasks"></i></span>
                  <div class="info-box-content">
                  <a href="{{ route('evaldd.proposal', ['param' => 'on_going']) }}" ><span class="info-box-text">{{ __('message.no_of_on-going_studies')}}</span></a>
                      <span class="info-box-number">{{$ongoing_count}}</span>
                  </div>
              </div>
          </div>

          <div class="col-12 col-sm-6 col-md-3">
              <div class="info-box mb-3">
                  <span class="info-box-icon bg-secondary elevation-1"><i class="fas fa-list"></i></span>
                  <div class="info-box-content">
                      <a href="{{ route('evaldd.proposal', ['param' => 'completed']) }}" ><span class="info-box-text">{{ __('message.no_of_complete_studies')}}</span></a>
                      <span class="info-box-number">{{$completed_count}}</span>
                  </div>
              </div>
          </div>
          <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box mb-3">
                <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-file-pdf"></i></span>
                <div class="info-box-content">
                    <a href="{{route('evaldd.stage-detail-report-list')}}"><span class="info-box-text">{{ __('message.download_stage_detail_report')}}</span></a>
                </div>
            </div>
        </div>
      </div>
      {{-- @if(Auth::check() && Auth::user()->role_manage == 3) --}}

      <div class="row">
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
                      <label for="scheme_list"> {{ __('message.select_scheme')}} <span class="required_filed"> * </span> : </label>
                      <select class="form-control scheme_list" name="scheme" id="scheme_list">
                        <!-- <option>Select Scheme </option> -->
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
          {{-- @endif --}}
  </div>    
</section>

{{-- <section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-3 col-6">
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3>{{count($new_count)}}</h3>
                        <p>No. of New Study Work</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-person-add"></i>
                    </div>
                    <a href="{{ route('evaldd.proposal', ['param' => 'new']) }}" class="small-box-footer">View List <i class="fas fa-arrow-circle-right"></i></a>
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
                    <a href="{{ route('evaldd.proposal', ['param' => 'on_going']) }}" class="small-box-footer">View List <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            
            <div class="col-lg-3 col-6">
                <div class="small-box bg-secondary">
                    <div class="inner">
                        <h3>{{$completed_count}}</h3>
                        <p>No. of Complete  Studies</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-pie-graph"></i>
                    </div>
                    <a href="{{  route('evaldd.proposal', ['param' => 'completed']) }}" class="small-box-footer">View List <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
        </div>
        @if(Auth::check() && Auth::user()->role_manage == 3)

        <div class="row">
            <div id="accordion" class="col-md-12">
              <div class="card">
                <div class="card-header bg-info text-center" id="headingTwo">
                  <h5 class="mb-0">
                    <button class="btn collapsed text-white"data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                       Detail Report
                    </button>
                    <a href="{{route('summary_export')}}" class="btn btn-success float-left">Export Report</a>
                  </h5>
                </div>
                <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion">
                  <div class="card-body">
                    <div class="form-group">
                        <label for="scheme_list"> Select Scheme <span class="required_filed"> * </span> : </label>
                        <select class="form-control scheme_list" name="scheme" id="scheme_list">
                          <option>Select Scheme </option>
                          @foreach ($scheme_list as $key => $scheme_item)
                              <option value="{{ $key}}">{{ $scheme_item}}</option>
                          @endforeach
                        </select>
                    </div>
                    <div class="chartItems" style="display: none;">
                          <div class="card card-success">
                              <div class="card-header">
                                  <h3 class="card-title">Bar Chart</h3>
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
            @endif
    </div>
</section> --}}
<script src="{{asset('plugins/jquery/jquery.min.js')}}"></script>
<script src="{{asset('plugins/chart.js/Chart.min.js')}}"></script>
<script>
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
        {
          label               : earlyLabel, //grey
          backgroundColor     : 'rgba(210, 214, 222, 1)',
          borderColor         : 'rgba(210, 214, 222, 1)',
          pointRadius         : false,
          pointColor          : 'rgba(210, 214, 222, 1)',
          pointStrokeColor    : '#c1c7d1',
          pointHighlightFill  : '#fff',
          pointHighlightStroke: 'rgba(220,220,220,1)',
       //   data                : [65, 59, 80, 81, 56, 55, 40]
        },
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
              labelString: '<-- Stage wise Status of Evaluation Studies -->',
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
                max: 500,
                stepSize: 50,
                fontColor: 'black',
                fontSize: 16,
                fontFamily: "monospace",
            },
            scaleLabel: {
              display: true,
              labelString: '<-- Stages Day AS Per SOP -->',
              fontColor: 'green',  // Set color for Y-axis title
                fontFamily:"monospace", 
                fontSize: 17,
                padding: {
                  left: 20, 
                  right: 20
                }
            },
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
        if(draft_id) {
                draft_id =  btoa(draft_id);
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
                        data['requisition_delay'],
                        data['study_design_date_delay'],
                        data['study_design_receive_hod_date_delay'],
                        data['polot_study_date_delay'],
                        data['field_survey_startdate_delay'],
                        data['data_entry_level_start_delay'],
                        data['report_startdate_delay'],
                        data['report_draft_hod_date_delay'],
                        data['dept_eval_committee_datetime_delay'],
                        data['eval_cor_date_delay'],
                        data['final_report_delay'],
                        data['dropped_delay']
                    ];

                    barChart.data.datasets[1].data = [
                        data['requisition'],
                        data['study_design_date'],
                        data['study_design_receive_hod_date'],
                        data['polot_study_date'],
                        data['field_survey_startdate'],
                        data['data_entry_level_start'],
                        data['report_startdate'],
                        data['report_draft_hod_date'],
                        data['dept_eval_committee_datetime'],
                        data['eval_cor_date'],
                        data['final_report'],
                        data['dropped']
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
          
             alert("There is doesn't have any scheme");
             $('#accordion').css('display','none');
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
                        data['requisition_delay'],
                        data['study_design_date_delay'],
                        data['study_design_receive_hod_date_delay'],
                        data['polot_study_date_delay'],
                        data['field_survey_startdate_delay'],
                        data['data_entry_level_start_delay'],
                        data['report_startdate_delay'],
                        data['report_draft_hod_date_delay'],
                        data['dept_eval_committee_datetime_delay'],
                        data['eval_cor_date_delay'],
                        data['final_report_delay'],
                        data['dropped_delay']
                    ];

                    barChart.data.datasets[1].data = [
                        data['requisition'],
                        data['study_design_date'],
                        data['study_design_receive_hod_date'],
                        data['polot_study_date'],
                        data['field_survey_startdate'],
                        data['data_entry_level_start'],
                        data['report_startdate'],
                        data['report_draft_hod_date'],
                        data['dept_eval_committee_datetime'],
                        data['eval_cor_date'],
                        data['final_report'],
                        data['dropped']
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

@endsection