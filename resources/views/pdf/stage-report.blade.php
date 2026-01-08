<!DOCTYPE html>
<html>
<head>
   
    <title>Stage Report</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #333;
            padding: 6px;
            text-align: left;
        }
    </style>
</head>
<body>
    <h2>Stage Summary Report</h2>
    <table>
        <thead>
            <tr>
                <th>Sr No.</th>
                <th>Scheme Name</th>
                <th>Assigned Date</th>
                <th>Branch Name</th>
                <th>Current Stage</th>
            </tr>
        </thead>
        <tbody>
            
            @foreach ($data as $key => $item)
                
                <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>{{ $item['scheme_name'] }}</td>
                    <td>{{ $item['assigned_date'] }}</td>
                    <td>{{ $item['branch_name'] }}</td>
                    <td>{{ $item['current_stage'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
