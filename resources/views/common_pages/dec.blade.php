@extends('layouts.app')
@section('content')
<style>
.advisory-wrapper {
    margin-top: 25px;
    background: #ffffff;
    padding: 25px 30px;
    border-radius: 6px;
}

.page-title {
    font-weight: 600;
    /* margin-bottom: 20px; */
    color: #1f2d3d;
}

.section-title {
    margin-top: 25px;
    font-weight: 600;
    color: #2c3e50;
}

.content-text {
    text-align: justify;
    line-height: 1.8;
    font-size: 15px;
    margin-bottom: 12px;
    color: #333;
}

.advisory-table {
    width: 100%;
    border-collapse: collapse;
    background: #fff;
    font-size: 14px;
}

.advisory-table th,
.advisory-table td {
    border: 1px solid #ccc !important;
    padding: 10px;
    color: #000;
}

.advisory-table thead th {
    background-color: #f2f2f2 !important;
    color: #000 !important;
    text-align: center;
    font-weight: 600;
}

.advisory-table tbody td {
    vertical-align: middle;
}

.function-list {
    margin-left: -15px;
    margin-top: 7px;
}

.function-list li {
    line-height: 1.7;
    margin-bottom: 8px;
    font-size: 15px;
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
<div class="row">

<div class="col-lg-9 col-md-8 col-sm-12 left_col advisory-wrapper">
    <h4 class="page-title text-center">
        {{ __('message.paregraph-4') }}
    </h4>
    <p class="content-text">
        {{ __('message.paregraph-5') }}
    </p>
    <div class="table-responsive">
        <table class="table table-bordered advisory-table">
            <thead>
                <tr>
                    <th style="width: 10%">{{ __('message.sr_no') }}</th>
                    <th colspan="2">Department of Evaluation Coordination Committee (DEC)</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="text-center">1</td>
                    <td>{{ __('message.paregraph-16') }}</td>
                    <td class="text-center">{{ __('message.chairman') }}</td>
                </tr>
                <tr>
                    <td class="text-center">2</td>
                    <td>{{ __('message.paregraph-7') }}</td>
                    <td class="text-center">{{ __('message.member') }}</td>
                </tr>
                <tr>
                    <td class="text-center">3</td>
                    <td>Director, Department of Evaluation</td>
                    <td class="text-center">{{ __('message.member') }}</td>
                </tr>
                <tr>
                    <td class="text-center">4</td>
                    <td>{{ __('message.paregraph-8') }}</td>
                    <td class="text-center">{{ __('message.member') }}</td>
                </tr>
                <tr>
                    <td class="text-center">5</td>
                    <td>{{ __('message.paregraph-9') }}</td>
                    <td class="text-center">{{ __('message.member') }}</td>
                </tr>
                <tr>
                    <td class="text-center">6</td>
                    <td>{{ __('message.paregraph-10') }}</td>
                    <td class="text-center">{{ __('message.member_secretary') }}</td>
                </tr>
            </tbody>
        </table>
    </div>

    <h5 class="section-title">
        {{ __('message.functions_of_committee') }} :
    </h5>

    <ol class="function-list">
        <li>{{ __('message.paregraph-17') }}</li>
        <li>{{ __('message.paregraph-18') }}</li>
        <li>{{ __('message.paregraph-19') }}</li>
    </ol>

</div></div>
@endsection
