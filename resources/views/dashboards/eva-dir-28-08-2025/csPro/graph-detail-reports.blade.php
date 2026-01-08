
{{-- @extends(Auth::user()->role_manage == 2 ? 'dashboards.eva-dir.layouts.evaldir-dash-layout' : 'dashboards.eva-dd.layouts.evaldd-dash-layout') --}}
@extends('dashboards.eva-dir.layouts.evaldir-dash-layout' )
@section('title','Detail Report')
@section('content')

<link rel="stylesheet" href="{{ asset('plugins/fontawesome-free/css/all.min.css') }}">
<link href="{{asset('plugins/select2/css/select2.min.css')}}" rel="stylesheet" />
  
<style>
    #questionContainer {
        width: 793px; /* A4 width at 96 DPI */
        margin: 0 auto;
    }
    table {
        width: 100%;
        margin-top: 20px;
        border-collapse: collapse;
    }
 
    .chart-tabs .nav-link {
        min-width: 120px;
        text-align: left;
        font-weight: 500;
    }
    .total-summary td{
        /* text-align: center; */
        font-weight: bold;
    }
    .graph-count-td{
        text-align: right;
    }
    .chart-wrapper {
        width: 793px;
        margin: 0 auto;
    }
  /* Keep the width manageable */
  @media print {
    .chart-tabs {
        display: none !important;
    }

    /* Optional: Adjust margins or hide other UI elements */
    .navbar, .sidebar, .print-hide {
        display: none !important;
    }
}

</style>

<div class="content  d-flex flex-column flex-column-fluid" id="kt_content">
  <!--begin::Subheader-->
  <div class="subheader py-2 py-lg-6  subheader-solid " id="kt_subheader">
    <div class=" container-fluid  d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
      <!--begin::Info-->
      <div class="d-flex align-items-center flex-wrap mr-1">
        <!--begin::Page Heading-->
        <div class="d-flex align-items-baseline flex-wrap mr-5">
          <!--begin::Page Title-->
          <h5 class="text-dark font-weight-bold my-1 mr-5 text-center">{{ __('message.graph_detail_report')}}</h5>
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
    <div class="container">
      <!--begin::Card-->
      <div class="cspro-items">
        <div class="row">
          <div class="col-md-12">
            <div class="form-group">
              <label for="exampleSelect2">{{ __('message.select_case')}}:</label>
              <select class="select-case form-control custom-form-control" id="select-case">
                <option value="">{{ __('message.select_case')}}</option>
                    @foreach($case_title as $case_item)
                    <option value="{{ $case_item->t_id }}">{{ $case_item->title }}</option>
                     @endforeach
              </select>
            </div>
          </div>
        </div>
      </div>
      <div class="card card-custom gutter-b detail-report" style="display:none;">
        <div class="card-header flex-wrap py-3">
            <div class="card-toolbar row">
                {{-- <div class="col-md-3"> --}}
                    <h5>{{ __('message.list_of_graph_detail_report')}}</h5>
                {{-- </div>
                <div class="col-md-3">
                    <button onclick="downloadPDF()" class="btn btn-success mb-3">⬇️ Download PDF</button>
                </div>
                <div class="col-md-3">
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#chartModal">
                        Choose Graph Type
                      </button>
                </div>
                <div class="col-md-3">
                    <button onclick="printReport()" class="btn btn-primary float-right">🖨️ Print Report</button>
                </div> --}}
            </div>
        </div>
        <div class="card-body">
            <div id="questionContainer" class="pdf-scale-container">
                <div id="reportContainer">
                    <!-- Each question section will be appended here dynamically -->
                    <div class="question-section">
                        <div class="question-header"><strong>Question 1</strong></div>
                        <div class="question-body">
                            <!-- Table and Chart go here -->
                        </div>
                    </div>

                    <!-- More question-section divs will go here -->
                </div>
            </div>
        </div>
    </div>

      <!--end::Card-->
    </div>
    <!--end::Container-->
  </div>
  <!--end::Entry-->
</div>
<!--end::Content-->
@endsection
<!-- jQuery -->
<script src="{{asset('js/chart.js')}}"></script>
<script src="{{asset('js/jquery-laest-version.min.js')}}"></script>
<script>
    let chart;
    let chartDataMap = {};
    $(document).ready(function(){

        $('#select-case').select2();
        
        $('#select-case').on('change',function(){
            const  selectVal = $(this).val();
          
            if(selectVal){
                loadQuestionData(selectVal);
            }else{
                alert('Before Graph detail report please select a case');
            }
      });
      const backgroundColors = [
        '#4dc9f6', '#f67019', '#f53794', '#537bc4',
        '#acc236', '#166a8f', '#00a950', '#58595b',
        '#8549ba'
        ];
        const borderColors = backgroundColors.map(color => '#333'); // Or use shades if needed

    function loadQuestionData(selectVal){
        $.ajax({
            type: 'GET',
            url: "{{ route('evaldir.case-graph-report') }}", 
            data: {
             //   _token: '{{ csrf_token() }}',
                t_id: selectVal,
            },
            success: function(response) {
                $('.cspro-items').hide();
                $('.detail-report').show();
                chartDataMap = {}; // add this before response.forEach in loadQuestionData
                $('#questionContainer').html(''); // clear old content if any

                response.forEach((questionBlock, index) => {
                        let labels = [];
                        let percentages = [];
                        let total_counts = [];
                        let tbody = '';

                            questionBlock.data.forEach(item => {
                                // Exclude from chart if it's the total summary
                                if (item.option !== 'Total Summary') {
                                    labels.push(item.option);
                                    percentages.push(item.percentage);
                                    total_counts.push(item.count);
                                }

                                // Bold Total Summary row in table
                                const isTotal = item.option === 'Total Summary';
                                const rowClass = isTotal ? 'fw-bold bg-light total-summary' : '';
                              

                                tbody += `<tr class="${rowClass}">
                                    <td>${item.option}</td>
                                    <td class="graph-count-td">${item.count}</td>
                                    <td class="graph-count-td">${item.percentage}</td>
                                </tr>`;
                            });


                    // Create dynamic HTML for question block
                    const questionHTML = `
                        <div class="question-block mb-4" id="question-block-${index}">
                        <h4>${index + 1}. ${questionBlock.question}</h4>

                        <div class="d-flex justify-content-between align-items-start mt-3">
                            <!-- Chart and Table Area -->
                            <div class="flex-grow-1 pe-3" id="chart-and-table-${index}">
                                <div id="chart-area-${index}" class="chart-wrapper">
                                    <canvas id="chartCanvas_${index}" ></canvas>
                                </div>

                                <div id="table-area-${index}" style="display: none;">
                                    <table class="table table-bordered table-striped mt-3">
                                        <thead class="table-dark">
                                            <tr>
                                                <th>Detail</th>
                                                <th>Beneficiary</th>
                                                <th>Percentage (%)</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            ${tbody}
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- Vertical Tabs -->
                         <ul class="nav flex-column nav-pills chart-tabs" id="chart-tabs-${index}" 
                                        style="margin-left: 20px; margin-top: 10%; min-width: 120px;">
                                <li class="nav-item mb-1">
                                    <a class="chart-tab nav-link active" href="#" data-index="${index}" data-type="bar">
                                        <i class="fas fa-chart-bar me-1"></i> Bar
                                    </a>
                                </li>
                                <li class="nav-item mb-1">
                                    <a class="chart-tab nav-link" href="#" data-index="${index}" data-type="horizontal">
                                        <i class="fas fa-align-left me-1"></i> Horizontal
                                    </a>
                                </li>
                                <li class="nav-item mb-1">
                                    <a class="chart-tab nav-link" href="#" data-index="${index}" data-type="pie">
                                        <i class="fas fa-chart-pie me-1"></i> Pie
                                    </a>
                                </li>
                                <li class="nav-item mb-1">
                                    <a class="chart-tab nav-link" href="#" data-index="${index}" data-type="detail">
                                        <i class="fas fa-table me-1"></i> Table
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="chart-tab nav-link" href="#" data-index="${index}" data-type="both">
                                        <i class="fas fa-columns"></i> Both
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>

                    `;

                    $('#questionContainer').append(questionHTML);
                    // Draw chart
               
                    chartDataMap[index] = {
                        labels,
                        percentages,
                        tbody,
                        chartInstance: null,
                        lastChartType: 'bar',
                        total_counts,
                    };
                        renderChart(index, 'both');

                });

            }//success end
        });
    }


// if (!Chart.registry.plugins.get('customLabelPlugin')) {
//     Chart.register({
//         id: 'customLabelPlugin',
//         afterDraw(chart) {
//             const ctx = chart.ctx;
//             const chartType = chart.config.type;
//             const indexAxis = chart.config.options.indexAxis || 'x';

//             ctx.save();
//             ctx.font = "bold 14px sans-serif";
//             ctx.fillStyle = "#000";
//             ctx.textAlign = "center";
//             ctx.textBaseline = "middle";

//             chart.data.datasets.forEach((dataset, i) => {
//                 const meta = chart.getDatasetMeta(i);

//                 meta.data.forEach((element, index) => {
//                     const value = dataset.data[index];
//                     const total = dataset.data.reduce((a, b) => a + b, 0);
//                     const percentage = ((value / total) * 100).toFixed(2);

//                     if (value > 0) {
//                         let x = element.x;
//                         let y = element.y;

//                         if (chartType === 'pie') {
//                             const radius = element.outerRadius;
//                             const angle = (element.startAngle + element.endAngle) / 2;
//                             const labelX = element.x + (radius / 2) * Math.cos(angle);
//                             const labelY = element.y + (radius / 2) * Math.sin(angle);

//                             // Only draw label if slice is large enough (e.g., >5%)
//                             if (percentage >= 2) {
//                                 ctx.fillText(`${percentage}`, labelX, labelY);
//                             }
//                         } else if (indexAxis === 'y') {
//                             // Horizontal bar
//                             ctx.fillText(`${percentage}`, element.x + 30, element.y);
//                         } else {
//                             // Vertical bar
//                             ctx.fillText(`${percentage}`, element.x, element.y - 15);
//                         }
//                     }
//                 });
//             });

//             ctx.restore();
//         }
//     });
// }

if (!Chart.registry.plugins.get('customLabelPlugin')) {
    Chart.register({
        id: 'customLabelPlugin',
        afterDraw(chart) {
            const ctx = chart.ctx;
            const chartType = chart.config.type;
            const indexAxis = chart.config.options.indexAxis || 'x';

            ctx.save();
            ctx.font = "bold 14px sans-serif";
            ctx.fillStyle = "#000";
            ctx.textAlign = "center";
            ctx.textBaseline = "middle";

            chart.data.datasets.forEach((dataset, i) => {
                const meta = chart.getDatasetMeta(i);
                const total = dataset.data.reduce((a, b) => a + b, 0); // total is correct
                
                meta.data.forEach((element, index) => {
                  
                  const count = dataset.data[index];
                    const total = dataset.data.reduce((a, b) => a + b, 0);
                    const percentage = total > 0 ? ((count / total) * 100).toFixed(2) : 0;
                    const label = `${count} (${percentage}%)`;

                    if (count > 0) {
                        if (chartType === 'pie') {
                            const radius = element.outerRadius;
                            const angle = (element.startAngle + element.endAngle) / 2;
                            const labelX = element.x + (radius / 2) * Math.cos(angle);
                            const labelY = element.y + (radius / 2) * Math.sin(angle);

                            if (percentage >= 2) {
                                ctx.fillText(label, labelX, labelY);
                            }
                        } else if (indexAxis === 'y') {
                            // Horizontal bar chart
                            ctx.fillText(label, element.x + 30, element.y);
                        } else {
                            // Vertical bar chart
                            ctx.fillText(label, element.x, element.y - 15);
                        }
                    }
                });
            });

            ctx.restore();
        }
    });
}



function renderChart(index, type) {
    const ctx = document.getElementById(`chartCanvas_${index}`).getContext('2d');
    const wrapper = document.getElementById(`chart-area-${index}`);

    // Destroy previous chart instance
    if (chartDataMap[index].chartInstance) {
        chartDataMap[index].chartInstance.destroy();
    }
    // Show both chart and table
    if (type === 'both') {
        const $chartArea = $(`#chart-area-${index}`);
        const $tableArea = $(`#table-area-${index}`);

        if ($chartArea.length && $tableArea.length) {
            $chartArea.show();
            $tableArea.show();

            const fallbackType = chartDataMap[index].lastChartType || 'bar';
            chartDataMap[index].lastChartType = fallbackType;

            let chartType = fallbackType;
            let indexAxis = 'x';

            if (fallbackType === 'horizontal') {
                chartType = 'bar';
                indexAxis = 'y';
            } else if (fallbackType === 'pie') {
                chartType = 'pie';
            }

            if (chartType === 'pie') {
                wrapper.style.width = '500px';
            } else {
                wrapper.style.width = '793px';
            }

            chartDataMap[index].chartInstance = new Chart(ctx, {
                type: chartType,
                data: {
                    labels: chartDataMap[index].labels,
                    datasets: [{
                        label: 'Percentage (%)',
                        data: chartDataMap[index].total_counts,
                        backgroundColor: backgroundColors.slice(0, chartDataMap[index].labels.length),
                        borderColor: borderColors.slice(0, chartDataMap[index].labels.length),
                        borderWidth: 1,
                        barThickness: 40
                    }]
                },
                options: {
                    indexAxis: indexAxis,
                    responsive: true,
                    animation: {
                        duration: 1000,
                        easing: 'easeOutQuart'
                    },
                    plugins: {
                        legend: { display: false },
                        tooltip: { enabled: false }
                    },
                    layout: { padding: 20 },
                    scales: (chartType === 'pie') ? {} : {
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Percentage'
                            }
                        }
                    }
                }
            });
        } else {
            console.warn(`Missing DOM elements for BOTH view: chart-area-${index} or table-area-${index}`);
        }
        return;
    }

    // Show only table view
    if (type === 'detail') {
        $(`#chart-area-${index}`).hide();
        $(`#table-area-${index}`).show();
        return;
    }

    // Show only chart
    $(`#chart-area-${index}`).show();
    $(`#table-area-${index}`).hide();

    chartDataMap[index].lastChartType = type;

    let chartType = type;
    let indexAxis = 'x';

    if (type === 'horizontal') {
        chartType = 'bar';
        indexAxis = 'y';
    } else if (type === 'pie') {
        chartType = 'pie';
    }

    wrapper.style.width = (chartType === 'pie') ? '500px' : '793px';

    chartDataMap[index].chartInstance = new Chart(ctx, {
        type: chartType,
        data: {
            labels: chartDataMap[index].labels,
            datasets: [{
                label: 'Percentage (%)',
                data: chartDataMap[index].total_counts,
                backgroundColor: backgroundColors.slice(0, chartDataMap[index].labels.length),
                borderColor: borderColors.slice(0, chartDataMap[index].labels.length),
                borderWidth: 1,
                barThickness: 40
            }]
        },
        options: {
            indexAxis: indexAxis,
            responsive: true,
            animation: {
                duration: 1000,
                easing: 'easeOutQuart'
            },
           plugins: {
            legend: { display: chartType === 'pie' }, // show legend for pie
            tooltip: { enabled: false },
            datalabels: {
                color: '#000',
                anchor: chartType === 'pie' ? 'center' : 'end',
                align: chartType === 'pie' ? 'center' : 'top',
                formatter: (value) => value + '%',
                font: {
                    weight: 'bold'
                }
            }
        },
            layout: { padding: 20 },
            scales: (chartType === 'pie') ? {} : {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Percentage'
                    }
                }
            }
        }
    });
}


let debounceTimer;
$(document).on('click', '.chart-tab', function(e) {
    e.preventDefault();
    const index = $(this).data('index');
    const type = $(this).data('type');

    // Update active tab UI
    $(`#chart-tabs-${index} .nav-link`).removeClass('active');
    $(this).addClass('active');

    // Call the render function which already handles 'detail' and 'both'
    renderChart(index, type);
});

});
</script>

  

