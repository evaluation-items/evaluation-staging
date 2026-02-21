@extends('layouts.app')
@section('content')
<style>

.org-chart-svg {
    height: auto;
    max-width: auto;
    display: inline-block;
}
</style>

<div class="container col-lg-12 col-md-12 col-sm-12 left_col">
    <div class="org-chart-section">
        <h4 class="text-center mb-3">
            Evaluation Office Organization Chart
        </h4>

        <div class="org-chart-wrapper">
           <img src="{{ asset('img/evaluation_chart_1.png') }}" alt="Organization Chart" class="org-chart-svg">
        </div>
    </div>

</div>
@endsection
