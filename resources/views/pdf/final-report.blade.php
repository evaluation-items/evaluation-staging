<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">

    <style>
        body {
            font-family: notosansgujarati;
            font-size: 12px;
            line-height: 1.6;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }

        th, td {
            border: 1px solid #000;
            padding: 6px;
            vertical-align: top;
        }

        th {
            width: 35%;
            background: #f2f2f2;
            font-weight: bold;
        }
    </style>
</head>
<body>

<h2>Final Evaluation Report</h2>

<table>
    <tr>
        <th>Department (વિભાગનું નામ)</th>
        <td>-</td>
    </tr>

    <tr>
        <th>Scheme Name (યોજનાનું નામ)</th>
        <td>{{ $proposal->scheme_name }}</td>
    </tr>

    <tr>
        <th>Scheme Short Name</th>
        <td>{{ $proposal->scheme_short_name }}</td>
    </tr>

    <tr>
        <th>Nodal Officer Name</th>
        <td>{{ $proposal->convener_name }}</td>
    </tr>

    <tr>
        <th>Email</th>
        <td>{{ $proposal->convener_email }}</td>
    </tr>

    <tr>
        <th>Major Objective</th>
        <td>{{ $proposal->major_objective }}</td>
    </tr>

    <tr>
        <th>Scheme Overview</th>
        <td>{{ $proposal->scheme_overview }}</td>
    </tr>
</table>

{{-- Financial Progress --}}

</body>
</html>
