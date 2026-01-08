<!DOCTYPE html>
<html>
<head>
    <title>Final Report PDF</title>
    <style>
      @font-face {
            font-family: 'Noto Sans Gujarati';
            src: url('{{ storage_path('fonts/NotoSansGujarati-Regular.ttf') }}') format('truetype');
            font-weight: normal;
            font-style: normal;
        }

        @font-face {
            font-family: 'Arial';
            src: url('{{ storage_path('fonts/Arial-Regular.ttf') }}') format('truetype');
            font-weight: normal;
            font-style: normal;
        }

        body {
            font-family: 'Arial', sans-serif;
        }

        .gujarati-text {
            font-family: 'Noto Sans Gujarati', sans-serif;
        }
        h1 {
            text-align: center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        .containers {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
    </style>
</head>

<body>

    <h1>Final Report</h1>
      <div class="container">
          <!--begin: Wizard-->
          <div class="wizard wizard-4" id="kt_wizard_v4" data-wizard-state="step-first" data-wizard-clickable="true">
            <div class="card card-custom card-shadowless rounded-top-0">
              <div class="card-body p-10">
                <div class="row table-responsive">
                    <table class="table table-bordered table-striped dataTable dtr-inline" id="example1">
                        <thead>
                            <tr>
                              <th width="8%">Sr No.</th>
                              <th>Scheme Name</th>
                              <th>Current Stage</th>
                            </tr>
                          </thead>
                          <tbody>
                            @php $i=1;  @endphp
                            @foreach($report_item as $prop)
                                <tr>
                                    <td>{{ $i++ }}</td>
                                    <td {{ is_gujarati(SchemeName($prop->scheme_id)) ? 'gujarati-text' : '' }}>{{ SchemeName($prop->scheme_id) }}</td>
                                    <td>{{ current_stages($prop->id) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
              </div>
            </div>
          </div>
      </div>
   <script>
    var is_chrome = function () { return Boolean(window.chrome); }
    if(is_chrome){
        window.print();
        //  setTimeout(function(){window.close();}, 10000);
        //  give them 10 seconds to print, then close
    }else{
        window.print();
    }
    </script>
</body>
</html>
