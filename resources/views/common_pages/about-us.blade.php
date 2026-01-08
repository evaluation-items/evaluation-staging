@extends('layouts.app')
@section('content')
<style>
    .about-us-wrapper {
    background: #ffffff;
    padding: 25px 30px;
    border-radius: 6px;
}

.about-title {
    font-weight: 600;
    color: #1f2d3d;
    border-bottom: 2px solid #e5e5e5;
    padding-bottom: 10px;
}

.section-title {
    margin-top: 25px;
    font-weight: 600;
    color: #2c3e50;
}

.about-text {
    text-align: justify;
    line-height: 1.8;
    font-size: 15px;
    color: #333;
    margin-bottom: 15px;
}

.about-list {
    margin-left: 20px;
    margin-bottom: 20px;
}

.about-list li {
    text-align: justify;
    line-height: 1.7;
    font-size: 15px;
    margin-bottom: 8px;
}

    </style>
<div class="col-lg-9 col-md-8 col-sm-12 left_col">
    
    <div class="about-us-wrapper">
        <h3 class="about-title text-center mb-4">About Us</h3>

        <p class="about-text aos-item">
            {{ __('message.paregraph-26') }}
        </p>

        <ol class="about-list aos-item">
            <li>{{ __('message.paregraph-27') }}</li>
            <li>{{ __('message.paregraph-28') }}</li>
            <li>{{ __('message.paregraph-29') }}</li>
            <li>{{ __('message.paregraph-30') }}</li>
            <li>{{ __('message.paregraph-31') }}</li>
            <li>{{ __('message.paregraph-32') }}</li>
        </ol>

        <p class="about-text aos-item">
            {{ __('message.paregraph-33') }}
        </p>

        <h4 class="section-title aos-item">
            {{ __('message.paregraph-34') }} :
        </h4>

        <p class="about-text aos-item">
            {{ __('message.paregraph-35') }}
        </p>

        <ol class="about-list aos-item">
            <li>{{ __('message.paregraph-36') }}</li>
            <li>{{ __('message.paregraph-37') }}</li>
            <li>{{ __('message.paregraph-38') }}</li>
            <li>{{ __('message.paregraph-39') }}</li>
            <li>{{ __('message.paregraph-40') }}</li>
            <li>{{ __('message.paregraph-41') }}</li>
            <li>{{ __('message.paregraph-42') }}</li>
            <li>{{ __('message.paregraph-43') }}</li>
            <li>{{ __('message.paregraph-44') }}</li>
            <li>{{ __('message.paregraph-45') }}</li>
            <li>{{ __('message.paregraph-46') }}</li>
        </ol>
    </div>

</div>
@endsection
