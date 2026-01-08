@extends('layouts.app')
@section('content')
<style>

.org-chart-svg {
    height: auto;
    max-width: 1200px;
    display: inline-block;
}
</style>

<div class="col-lg-12 col-md-12 col-sm-12 left_col">
    <div class="org-chart-section">
        <h4 class="text-center mb-3">
            Evaluation Office Organization Chart
        </h4>

        <div class="org-chart-wrapper">
           <img src="{{ asset('img/evaluation_chart.png') }}" alt="Organization Chart" class="org-chart-svg">
        </div>
    </div>

</div>
@endsection
