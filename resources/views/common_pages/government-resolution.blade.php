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
<div class="col-lg-12 col-md-12 col-sm-12 left_col">
    <h4 class="who-title text-center">Government of Resolution/Circulars</h4>
    <div class="table-responsive">
        <table class="table table-bordered who-table">
            <thead>
                <tr class="evenRow">
                        <th width="80" valign="top" align="center" class="firstTh">Sr. No.</th>
                        <th>Date</th>
                        <th>GR/Circular No.</th>
                        <th valign="top" align="left">Subject/Title</th>
                        <th width="80" valign="top" align="left" class="lastTh">Download</th>
                        {{-- <th>Document Type</th> --}}
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1</td>
                    <td>૧૮-૦૫-૧૯૮૭</td>
                    <td>મલવ-૧૦૮૬-૨૮૪૯-૭</td>
                    <td>આયોજીત યોજનાઓના મૂલ્યાંકન અંગે</td>
                    <td>
                        <a title="1987 Resolution" target="_blank" href="{{asset('GR/1987_resolution.pdf')}}">
                            <img title="1987 Resolution" alt="1987 Resolution" src=" {{asset('GR/download.png')}}" width="50px" height="50px">
                        </a>
                    </td>
                    {{-- <td>Circular</td> --}}
                </tr>
                
                <tr>
                    <td>2</td>
                    <td>૧૩/૦૮/૨૦૦૮</td>
                    <td>મલવ-૧૦૦૮/૯૪૩/5</td>
                    <td>આયોજીત યોજનાઓના મુલ્યાંકન અભ્યાસો બાબત</td>
                    <td>
                        <a title="2008 Resolution" target="_blank" href="{{asset('GR/2008_resolution.pdf')}}">
                            <img title="2008 Resolution" alt="2008 Resolution" src=" {{asset('GR/download.png')}}" width="50px" height="50px">
                        </a>
                    </td>
                    {{-- <td>Circular</td> --}}
                </tr>
                <tr>
                    <td>3</td>
                    <td>૨૨/૦૮/૨૦૦૮</td>
                    <td>મલવ-૧૦૦૮/૯૪૩/5</td>
                    <td>આયોજીત યોજનાઓના મુલ્યાંકન અભ્યાસો બાબત</td>
                    <td>
                        <a title="2008-1 Resolution" target="_blank" href="{{asset('GR/2008-1_resolution.pdf')}}">
                            <img title="2008-1 Resolution" alt="2008-1 Resolution" src=" {{asset('GR/download.png')}}" width="50px" height="50px">
                        </a>
                    </td>
                    {{-- <td>Circular</td> --}}
                </tr>
                <tr>
                    <td>4</td>
                    <td>૨૩/૫/૨૦૧૩</td>
                    <td>મલવ-૧૦૦૮/૯૪૩/ઠ</td>
                    <td>આયોજીત યોજનાઓના મૂલ્યાંકન અભ્યાસો બાબત</td>
                    <td>
                        <a title="2013 Resolution" target="_blank" href="{{asset('GR/2013_resolution.pdf')}}">
                            <img title="2013 Resolution" alt="2013 Resolution" src=" {{asset('GR/download.png')}}" width="50px" height="50px">
                        </a>
                    </td>
                    {{-- <td>Resolution</td> --}}
                </tr>
                <tr>
                    <td>5</td>
                    <td>15 July 2015</td>
                    <td>MLV/102015/347966/TH</td>
                    <td>Reconstitution of Steering committee to prepare the draft of State Evaluation Policy.</td>
                    <td>
                        <a title="Resolution" target="_blank" href="{{asset('GR/resolution.pdf')}}">
                            <img title="Resolution" alt="Resolution" src=" {{asset('GR/download.png')}}" width="50px" height="50px">
                        </a>
                    </td>
                    {{-- <td>Resolution</td> --}}
                </tr>
            </tbody>
        </table>
    </div>
</div>
@endsection