<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Proposal Detail</title>
    <style>
        body {
            font-family: dejavusans;
            font-size: 12px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table, td, th {
            border: 1px solid #000;
        }

        td, th {
            padding: 6px;
            vertical-align: top;
            page-break-inside: avoid;
        }

        tr {
            page-break-inside: avoid;
        }

        .title {
            text-align: center;
            font-weight: bold;
            font-size: 16px;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>

<div class="title">Proposal Detail</div>

<table>
    <tr>
        <td><strong>Department Name</strong></td>
        <td>{{ $proposal->department_name }}</td>
    </tr>
    <tr>
        <td><strong>Nodal Officer</strong></td>
        <td>{{ $proposal->nodal_officer }}</td>
    </tr>
    <tr>
        <td><strong>Name of the Nodal Officer (નોડલ અધિકારીનું નામ)</strong></td>
        <td>{{ $proposal->convener_name }}</td>
    </tr>
</table>
{{-- 
<table style="margin-top:-20px;">
    <tr>
        <th rowspan="{{ $financial_progress->count() + 2 }}">
            Financial & Physical Progress (component wise)
        </th>
        <th rowspan="2">Financial Year</th>
        <th colspan="3">Physical</th>
        <th colspan="2">Financial (Rs in Crores)</th>
    </tr>
    <tr>
        <th>Unit</th>
        <th>Target</th>
        <th>Achievement</th>
        <th>Provision</th>
        <th>Expenditure</th>
    </tr>

    @if($financial_progress->count())
        @foreach($financial_progress as $fpv)
        <tr>
            <td>{{ $fpv->financial_year }}</td>
            <td>{{ units($fpv->selection) }}</td>
            <td>{{ $fpv->target }}</td>
            <td>{{ $fpv->achievement }}</td>
            <td>{{ $fpv->allocation }}</td>
            <td>{{ $fpv->expenditure }}</td>
        </tr>
        @endforeach
    @endif

</table> --}}

</body>
</html>