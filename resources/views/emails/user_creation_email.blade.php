<!DOCTYPE html>
<html>
    <head>
        <style>
            table, td, th {border: 1px solid black;border-collapse: collapse;}
        </style>
    </head>
    <body>
        <center><h3>{{ $details['subject'] }}</h3></center>
        <table>
            <tr>
                <th>Email</th><td> {{ $details['email'] }}</td>
            </tr>
            <tr>
                <th>Password</th><td> {{ $details['password'] }}</td>
            </tr>
        </table>
    </body>
</html>
