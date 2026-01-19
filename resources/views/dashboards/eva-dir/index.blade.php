
@extends('dashboards.eva-dir.layouts.evaldir-dash-layout')
@section('title','Dashboard - Evaluation Office')
{{-- <link rel="stylesheet" type="text/css" href="{{ asset('css/gadsec/index.css') }}"> --}}
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
                <h1 class="m-0 text-center font-weight-bold"><U>Directorate of Evaluation | <?php echo strtoupper(__('message.dashboard')); ?></U></h1>
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
                      <a href="{{ route('evaldir.proposal', ['param' => 'new']) }}" > <span class="info-box-text">{{ __('message.no_of_new_proposals') }}</span></a>
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
                      <a href="{{ route('evaldir.proposal', ['param' => 'forward']) }}" > <span class="info-box-text">No. of Forwraded Proposals</span></a>
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
                      <a href="{{ route('evaldir.proposal', ['param' => 'return']) }}" > <span class="info-box-text">{{ __('message.no_of_returned_proposals')}}</span></a>
                      <span class="info-box-number">{{$return_count}}</span>
                  </div>
              </div>
          </div>
          

           <div class="clearfix hidden-md-up"></div>
          <div class="col-12 col-sm-6 col-md-3">
              <div class="info-box mb-3">
                  <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-tasks"></i></span>
                  <div class="info-box-content">
                  <a href="{{ route('evaldir.proposal', ['param' => 'on_going']) }}" ><span class="info-box-text">{{ __('message.no_of_on-going_studies')}}</span></a>
                      <span class="info-box-number">{{$ongoing_count}}</span>
                  </div>

              </div>

          </div> 

          <div class="col-12 col-sm-6 col-md-3">
              <div class="info-box mb-3">
                  <span class="info-box-icon bg-secondary elevation-1"><i class="fas fa-list"></i></span>
                  <div class="info-box-content">
                      <a href="{{ route('evaldir.proposal', ['param' => 'completed']) }}" ><span class="info-box-text">{{ __('message.no_of_complete_studies')}}</span></a>
                      <span class="info-box-number">{{$completed_count}}</span>
                  </div>
              </div>
          </div>
      </div>
      <div class="row">
        <div class="col-md-12">
          <div class="card">
            <div class="card-header bg-purple">
              <h3 class="card-title" style="font-size: 1.5rem !important;">{{ __('message.stacked_bar_chart')}}</h3>
              <div class="card-tools">
                @php
                  $financialYears = getFinancialYear();
                  $current_fin_year = date("Y").'-'. date('y', strtotime('+1 year'));
                @endphp
                <select id="yearChart" name="year" class="form-control"> 
                    <option value="">{{ __('message.select_year')}}</option>
                    @foreach ($financialYears as $key => $financialYears)
                        <option value="{{$financialYears}}" {{($financialYears == $current_fin_year) ? 'selected' : ''}}>{{$financialYears}}</option>
                    @endforeach
                    
                </select>
              </div>
            </div>
            <div class="card-body">
              <div class="chart">
                <canvas id="stackedBarChart" style="height: 495px;"></canvas>
              </div>
            </div>
          </div>
        </div>
      </div>
    
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
              <a href="{{route('summary_export_all',['draft_id' => $draft_id_str])}}" class="btn btn-success float-left"> {{ __('message.export_report')}}</a>
              </h5>
            </div>
            <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion">
              <div class="card-body">
                <div class="form-group">
                    <label for="scheme_list"> {{ __('message.select_scheme')}} <span class="required_filed"> * </span> : </label>
                    <select class="form-control scheme_list" name="scheme" id="scheme_list">
                      <option>{{ __('message.select_scheme')}} </option>
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
                                <canvas id="barChart" style="min-height: 450px; height: 450px; max-height: 450px; max-width: 100%;"></canvas>
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
  </div>    
</section>

<script src="{{asset('plugins/jquery/jquery.min.js')}}"></script>
<script src="{{asset('plugins/chart.js/Chart.min.js')}}"></script>
<script>
    var stackedBarChart;
    var barChart;
    function initializeChart(){
       const chartLabels = @json(__('message.chart_labels'));
       const new_schemeLabel = "{{ __('message.new_scheme') }}";
      const carry_forward_schemeLabel = "{{ __('message.carry_forward_scheme') }}";
     
        var areaChartData = {
        // labels  : ['Requisition', 'Study Design', 'Pilot', 'Field Work','Data Cleaning' , 'Report Writing', 
        // 'At IO Suggestions', 'DEC','ECC','Published','Dropped'],
        labels: chartLabels,
        datasets: [
          {
            label               : new_schemeLabel,
            backgroundColor     : '#eb730ef0',
            borderColor         : '#eb730ef0',
            pointRadius          : false,
            pointColor          : '#3b8bba',
            pointStrokeColor    : 'rgba(60,141,188,1)',
            pointHighlightFill  : '#fff',
            pointHighlightStroke: 'rgba(60,141,188,1)',
          //  data                : [1,2,5,3,2,4,2]
          },
          {
            label               : carry_forward_schemeLabel,
            backgroundColor     : '#03c8f4ad',
            borderColor         : '#03c8f4ad',
            pointRadius         : false,
            pointColor          : '#03c8f4ad',
            pointStrokeColor    : '#c1c7d1',
            pointHighlightFill  : '#fff',
            pointHighlightStroke: 'rgba(220,220,220,1)',
          //  data                : [1,2,3,4,5,3,2]
          },
        ]
      }
      // Configuring the chart options
          stackedBarChartCanvas = $('#stackedBarChart').get(0).getContext('2d');
          var stackedBarChartData = $.extend(true, {}, areaChartData);
          var stackedBarChartOptions = {
            responsive: true,
            maintainAspectRatio: false,
            tooltips: {
              enabled: false
            },
            hover: {
              animationDuration: 0
            },
            scales: {
              xAxes: [{
                stacked: true,
                scaleLabel: {
                  display: true,
                  labelString: '<-- Stages Count -->',
                  fontColor: 'green', 
                  fontFamily:"monospace", // Set color for X-axis title
                  fontSize: 15,
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
                      fontSize: 14,
                      fontFamily:"monospace", 
                  },
              }],
              yAxes: [{
                stacked: true,
                scaleLabel: {
                  display: true,
                  labelString: '<-- Total Scheme Count -->',
                  fontColor: 'green',  // Set color for Y-axis title
                  fontFamily:"monospace", 
                  fontSize: 15,
                  padding: {
                    left: 20, 
                    right: 20
                  }
                },
                ticks: {
                  beginAtZero: true,
                  fontColor: 'black',
                  fontSize: 14,
                  fontFamily:"monospace", 
                },
                plugins: {
                  datalabels: {
                      display: true,
                      color: 'white',
                      formatter: function(value, context) {
                          return context.dataset.label + ': ' + value;
                      }
                  }
              }
              }]
            },
            legend: {
              display: true, // Ensure legend is displayed
              labels: {
                fontColor: 'black',
                fontSize: 14,
                fontFamily: "monospace"
              }
            },
            animation: {
              onComplete: function() {
                var chartInstance = this.chart;
                var ctx = chartInstance.ctx;
                ctx.textAlign = "center";
                ctx.font = "15px monospace";
                ctx.fillStyle = "#000";

                Chart.helpers.each(this.data.datasets.forEach(function(dataset, i) {
                  var meta = chartInstance.controller.getDatasetMeta(i);
                  Chart.helpers.each(meta.data.forEach(function(bar, index) {
                    data = dataset.data[index];
                    if (data !== 0) { 
                      ctx.fillText(data, bar._model.x, bar._model.y - 5);
                    }
                  }), this);
                }), this);
              }
            },
            plugins: {
                  datalabels: {
                    anchor: 'end',
                    align: 'end',
                    color: function(context) {
                      var index = context.dataIndex;
                      var value = context.dataset.data[index];
                      return value > 0 ? 'black' : 'rgba(0, 0, 0, 0)';
                    },
                    formatter: Math.round,
                    font: {
                      weight: 'bold'
                    }
                  }
            }
        };
        // Ensure the chart is initialized before accessing its options
      stackedBarChart = new Chart(stackedBarChartCanvas, {
          type: 'bar',
          data: stackedBarChartData,
          options: stackedBarChartOptions
      });

      // Make an AJAX request to get the count of results
      $.ajax({
          url: "{{route('get-scheme-count')}}", // Your Laravel route
          method: 'GET',
          success: function(response) {
              stackedBarChart.options.scales.yAxes[0].ticks.max = response.count;
              stackedBarChart.update();
          },
          error: function(xhr, status, error) {
              console.error(error);
          }
      });
      // Make an AJAX request to get the count of results
      $.ajax({
          url: "{{route('get-stage-count')}}", 
          method: 'GET',
          success: function(data) {

          //New Scheme Count
          stackedBarChart.data.datasets[0].data  = [ 
                      data[0].requisition , //1
                      data[0].study_design_date  , //2
                      data[0].polot_study_date  ,  //3
                      data[0].field_survey_startdate  ,  //4
                      data[0].data_entry_level_start  ,  //5
                      data[0].report_startdate  ,  //6
                      data[0].report_sent_hod_date  , //7
                   //   data[0].report_draft_sent_hod_date  ,  //8
                      data[0].dept_eval_committee_datetime  ,  //8
                      data[0].eval_cor_date, //9
                      data[0].final_report  ,  //10
                      data[0].dropped     //11
          ];
        //Carry Forward Scheme Count
          stackedBarChart.data.datasets[1].data  = [ 
                      data[1].requisition, //1
                      data[1].study_design_date  , //2
                      data[1].polot_study_date  ,  //3
                      data[1].field_survey_startdate  ,  //4
                      data[1].data_entry_level_start  , //5 
                      data[1].report_startdate  ,  //6
                      data[1].report_sent_hod_date  ,  //7
                   //   data[1].report_draft_sent_hod_date  ,  //8
                      data[1].dept_eval_committee_datetime  ,  //8
                      data[1].eval_cor_date, //9
                      data[1].final_report  ,  //10
                      data[1].dropped     //11
          ];
              stackedBarChart.update();
          },
          error: function(xhr, status, error) {
              console.error(error);
          }
      });
    }

  function donutChart(){
      const chartLabels = @json(__('message.chart_labels'));
      const delayLabel = "{{ __('message.delay_stage_sop_count') }}";
      const earlyLabel = "{{ __('message.carry_forward_scheme') }}";
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
    var stackedBarChartCanvas = $('#stackedBarChart').get(0).getContext('2d');
    var barChartCanvas = $('#barChart').get(0).getContext('2d');
   
    initializeChart();
    
    // Add event listener to the year dropdown
      $('#yearChart').on('change', function () {
        var selectedYear = $(this).val();
        if (!selectedYear) {
          initializeChart();
        } else {
          $.ajax({
            url: "{{route('get-label')}}",
            method: 'GET',
            data: { year: selectedYear },
            success: function (data) {
              stackedBarChart.data.datasets[0].data  = [ 
                    data[0].requisition, //11
                    data[0].study_design_date  , //10
                    data[0].polot_study_date  ,  //9
                    data[0].field_survey_startdate  ,  //8
                    data[0].data_entry_level_start  , //7 
                    data[0].report_startdate  ,  //6
                    data[0].report_sent_hod_date  , //5
                    data[0].dept_eval_committee_datetime  ,  //4
                    data[0].eval_cor_date  ,  //3  
                    data[0].final_report  ,  //2
                    data[0].dropped     //1

              ];
            //Carry Forward Scheme Count
              stackedBarChart.data.datasets[1].data  = [ 
                      data[1].requisition, //11
                      data[1].study_design_date  , //10
                      data[1].polot_study_date  ,  //9
                      data[1].field_survey_startdate  ,  //8
                      data[1].data_entry_level_start  , //7  
                      data[1].report_startdate  ,  //6
                      data[1].report_sent_hod_date  , //5
                      data[1].dept_eval_committee_datetime  ,  //4
                      data[1].eval_cor_date  ,  //3
                      data[1].final_report  ,  //2
                      data[1].dropped      //1

              ];
                  
              stackedBarChart.update();
            },
            error: function (error) {
              console.error('Error fetching data:', error);
            }
          });
        }
      });
});

$('#scheme_list').on('change', function () {
        var draft_id = btoa($(this).val());
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