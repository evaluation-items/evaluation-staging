<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Evaluation Summary Report Library</title>
    @php
   // dd(storage_path('fonts/NotoSansGujarati-Regular.ttf'));
    @endphp
    <style>
        @font-face {
            font-family: 'Noto Sans Gujarati';
            src: url('/storage/fonts/NotoSansGujarati-Regular.ttf') format('truetype');
            font-weight: normal;
            font-style: normal;
        }

        @font-face {
            font-family: 'Arial';
            src: url('/storage/fonts/Arial-Regularr.ttf') format('truetype');
            /* url('{{ storage_path("fonts/Arial-Regular.ttf") }}') format('truetype'); */
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
    </style>
</head>
<body>
    <h1>Evaluation Summary Report Library</h1>
    <div class="container">
        <div class="wizard wizard-4" id="kt_wizard_v4" data-wizard-state="step-first" data-wizard-clickable="true">
            <div class="card card-custom card-shadowless rounded-top-0">
                <div class="card-body p-10">
                    <div class="row table-responsive">
                        <div class="card-body">
                            <div class="col-lg-12">
                            
                                <table class="table table-bordered table-striped dataTable dtr-inline" id="example1">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Name</th>
                                            <th>Year</th>
                                            <th>Department Name</th>
                                            <th>Organization Name</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if($data->count() > 0)
                                        @foreach($data as $key => $items)
                                            <tr>
                                                <td>{{ ++$key }}</td>
                                                <td class="{{ is_gujarati($items->study_name) ? 'gujarati-text' : '' }}">{{ $items->study_name }}</td>
                                                <td>{{ $items->year }}</td>
                                                <td width="23%" class="{{ is_gujarati(department_name($items->dept_id)) ? 'gujarati-text' : '' }}">{{ department_name($items->dept_id) }}</td>
                                                <td class="{{ is_gujarati($items->org_name) ? 'gujarati-text' : '' }}">{{ $items->org_name }}</td>
                                            </tr>
                                        @endforeach
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script> --}}

<script>
    // jQuery(document).ready(function(){
    //     window.print();
    // });   
</script>
</body>
</html>
