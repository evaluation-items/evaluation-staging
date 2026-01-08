@extends('layouts.app')

@section('content')
<style>
.who-wrapper {
    background: #fff;
    padding: 25px;
    border-radius: 6px;
}

.who-title {
    font-weight: 600;
    margin-bottom: 20px;
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
<div class="col-lg-9 col-md-8 col-sm-12 left_col">
    <div class="who-wrapper">
        <h4 class="who-title text-center">Who's Who</h4>
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
                        <td>54353</td>
                        {{-- <td>9377298620</td> --}}
                        <td>direvl@gujarat.gov.in</td>
                    </tr>
                    <tr>
                        <td>2</td>
                        <td>Shri M. B. Gamit</td>
                        <td>Deputy Director</td>
                        <td>52877</td>
                        {{-- <td>9879138813</td> --}}
                        <td>ddevl5@gujarat.gov.in</td>
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
                        <td>Ms. Richa Mher</td>
                        <td>Deputy Director (Loan Service)</td>
                        <td>-</td>
                        {{-- <td>9979990718</td> --}}
                        <td>ddevl3@gujarat.gov.in</td>
                    </tr>
                    <tr>
                        <td>5</td>
                        <td>Shri S. J. Patel</td>
                        <td>Research Officer</td>
                        <td>52876</td>
                        {{-- <td>9773120143</td> --}}
                        <td>roevladm@gujarat.gov.in</td>
                    </tr>
                    <tr>
                        <td>6</td>
                        <td>Smt. S. K. Zala</td>
                        <td>Research Officer</td>
                        <td>55642</td>
                        {{-- <td>6354131584</td> --}}
                        <td>roevl1@gujarat.gov.in</td>
                    </tr>
                    <tr>
                        <td>7</td>
                        <td>Ms. B. A. Makwana</td>
                        <td>Research Officer</td>
                        <td>55642</td>
                        {{-- <td>9723275151</td> --}}
                        <td>roevl2@gujarat.gov.in</td>
                    </tr>
                    <tr>
                        <td>8</td>
                        <td>Shri D. M. Parmar</td>
                        <td>Research Officer</td>
                        <td>55645</td>
                        {{-- <td>7874820244</td> --}}
                        <td>roevl3@gujarat.gov.in</td>
                    </tr>
                    <tr>
                        <td>9</td>
                        <td>Shri D. B. Zala</td>
                        <td>Research Officer</td>
                        <td>55645</td>
                        {{-- <td>8460660088</td> --}}
                        <td>-</td>
                    </tr>
                    <tr>
                        <td>10</td>
                        <td>Smt. D. R. Gameti</td>
                        <td>Research Officer</td>
                        <td>52864</td>
                        {{-- <td>9428218842</td> --}}
                        <td>roevl4@gujarat.gov.in</td>
                    </tr>
                    <tr>
                        <td>11</td>
                        <td>Smt. V. D. Sangada</td>
                        <td>Research Officer</td>
                        <td>52864</td>
                        {{-- <td>8238976711</td> --}}
                        <td>roevl5@gujarat.gov.in</td>
                    </tr>
                    <tr>
                        <td>12</td>
                        <td>Shri S. K. Shrimali</td>
                        <td>Research Officer</td>
                        <td>52876</td>
                        {{-- <td>7383214920</td> --}}
                        <td>roevl6@gujarat.gov.in</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

</div>

@endsection
