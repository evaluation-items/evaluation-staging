@extends('layouts.app')

@section('content')
<style>
.ecc-wrapper {
    margin-top: 25px;
    background: #ffffff;
    padding: 25px 30px;
    border-radius: 6px;
}

 /* .page-title {
    font-weight: 600;
    /* margin-bottom: 20px; 
    color: #1f2d3d;
}

.section-title {
    /* margin-top: 25px; 
    font-weight: 600;
    color: #2c3e50;
}  */
/* .content-text {
    text-align: justify;
    line-height: 1.8;
    font-size: 15px;
    margin-bottom: 12px;
} */

.ecc-table {
    width: 100%;
    background: #fff;
    border-collapse: collapse;
    font-size: 14px;
}

.ecc-table th,
.ecc-table td {
    border: 1px solid #ccc !important;
    padding: 10px;
    vertical-align: middle;
}

.ecc-table thead th {
    background-color: #f2f2f2;
    font-weight: 600;
    text-align: center;
}

.section-title {
    margin-top: 25px;
    font-weight: 600;
}

.function-list li {
    line-height: 1.7;
    margin-bottom: 6px;
    text-align: justify;
}
.table-responsive {
    display: block !important;
    visibility: visible !important;
    opacity: 1 !important;
    height: auto !important;
    overflow: visible !important;
    position: static !important;
    transform: none !important;
}
</style>
<div class="container">
    <div class="ecc-wrapper">
        <h4 class="page-title text-center">
            {{ __('message.evaluation_coordination_committee') }}
        </h4>
        <p class="content-text">
            {{ __('message.paregraph-20') }}
        </p>
        <div class="table-responsive">
            <table class="table table-bordered ecc-table">
                <thead>
                    <tr>
                        <th style="width:10%">{{ __('message.sr_no') }}</th>
                        <th colspan="2">
                            Evaluation Co-ordination Committee (ECC)
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="text-center">1</td>
                        <td>{{ __('message.paregraph-11') }}</td>
                        <td class="text-center">{{ __('message.chairman') }}</td>
                    </tr>
                    <tr>
                        <td class="text-center">2</td>
                        <td>{{ __('message.paregraph-12') }}</td>
                        <td class="text-center">{{ __('message.member') }}</td>
                    </tr>
                    <tr>
                        <td class="text-center">3</td>
                        <td>{{ __('message.paregraph-16') }}</td>
                        <td class="text-center">{{ __('message.invitee') }}</td>
                    </tr>
                    <tr>
                        <td class="text-center">4</td>
                        <td>Director, Directorate of Evaluation</td>
                        <td class="text-center">{{ __('message.member') }}</td>
                    </tr>
                    <tr>
                        <td class="text-center">5</td>
                        <td>
                            Financial Advisor of General Administration Department (Planning)
                        </td>
                        <td class="text-center">{{ __('message.member') }}</td>
                    </tr>
                    <tr>
                        <td class="text-center">6</td>
                        <td>{{ __('message.paregraph-15') }}</td>
                        <td class="text-center">{{ __('message.member_secretary') }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <p class="section-title">
            {{ __('message.functions_of_committee') }} :
        </p>

        <ol class="function-list">
            <li>{{ __('message.paregraph-21') }}</li>
            {{-- <li>{{ __('message.paregraph-22') }}</li> --}}
            <li>{{ __('message.paregraph-23') }}</li>
            <li>{{ __('message.paregraph-24') }}</li>
            {{-- <li>{{ __('message.paregraph-25') }}</li> --}}
        </ol>

    </div>
</div>

@endsection
