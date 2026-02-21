@extends('layouts.app')

@section('content')
<style>


.who-title {
    font-weight: 600;
    margin-bottom: 20px;
    margin-top: 50px;
    color: #1f2d3d;
}

.who-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 14px;
}

.who-table thead th {
    background-color: #f2f2f2;
    color: #000;
    text-align: center;
    vertical-align: middle;
    border: 1px solid #ddd;
    padding: 10px;
    font-weight: 600;
}

.who-table tbody td {
    border: 1px solid #ddd;
    padding: 8px 10px;
    vertical-align: middle;
}

.who-table tbody td:nth-child(1),
.who-table tbody td:nth-child(4),
.who-table tbody td:nth-child(5) {
    text-align: center;
    white-space: nowrap;
}

.who-table tbody tr:hover {
    background-color: #f9f9f9;
}

</style>
<div class="container">
    <div class="who-wrapper">
        <h4 class="who-title text-center mb-3">Who's Who</h4>
        <div class="table-responsive">
            <table class="table table-bordered table-striped who-table">
                <thead class="table-dark">
                    <tr>
                        <th>Sr. No</th>
                        <th>Name</th>
                        <th>Designation</th>
                        <th>Phone No.</th>
                        {{-- <th>Mobile No.</th> --}}
                        <th>Email Address</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>1</td>
                        <td>Dr. Rakesh R. Pandya</td>
                        <td>Director</td>
                        <td>52861</td>
                        {{-- <td>9377298620</td> --}}
                        <td>direvl@gujarat.gov.in</td>
                    </tr>
                    <tr>
                        <td>2</td>
                        <td>Shri M. B. Gamit</td>
                        <td>Deputy Director (I/C)</td>
                        <td>52877</td>
                        {{-- <td>9879138813</td> --}}
                        <td>jdevl@gujarat.gov.in</td>
                    </tr>
                    <tr>
                        <td>3</td>
                        <td>Smt. A. G. Prajapati</td>
                        <td>Deputy Director</td>
                        <td>-</td>
                        {{-- <td>9998803098</td> --}}
                        <td>ddevl4@gujarat.gov.in</td>
                    </tr>
                    <tr>
                        <td>4</td>
                        <td>S. G. Prajapati</td>
                        <td>Deputy Director</td>
                        <td>-</td>
                        {{-- <td>9979990718</td> --}}
                        <td>ddevl4@gujarat.gov.in</td>
                    </tr>
                    <tr>
                        <td>5</td>
                        <td>R. M. Maher</td>
                        <td>Deputy Director</td>
                        <td>-</td>
                        {{-- <td>9979990718</td> --}}
                        <td>roevl1@gujarat.gov.in</td>
                    </tr>
                    <tr>
                        <td>6</td>
                        <td>R. R. Christian</td>
                        <td>Deputy Director</td>
                        <td>52859</td>
                        {{-- <td>9979990718</td> --}}
                        <td>ddevl5@gujarat.gov.in</td>
                    </tr>
                    <tr>
                        <td>7</td>
                        <td>V. B. Parmar</td>
                        <td>Deputy Director</td>
                        <td>52877</td>
                        {{-- <td>9979990718</td> --}}
                        <td>-</td>
                    </tr>
                    <tr>
                        <td>8</td>
                        <td>P. K. Saliya</td>
                        <td>Deputy Director</td>
                        <td>-</td>
                        {{-- <td>9979990718</td> --}}
                        <td>ddsurvey6@gujarat.gov.in</td>
                    </tr>
                    <tr>
                        <td>9</td>
                        <td>A. R. Sadhu</td>
                        <td>Deputy Director</td>
                        <td>-</td>
                        {{-- <td>9979990718</td> --}}
                        <td>ddevl3@gujarat.gov.in</td>
                    </tr>
                    <tr>
                        <td>10</td>
                        <td>J. B. Virpura</td>
                        <td>Deputy Director</td>
                        <td>-</td>
                        {{-- <td>9979990718</td> --}}
                        <td></td>
                    </tr>
                   
                </tbody>
            </table>
        </div>
    </div>

</div>

@endsection
