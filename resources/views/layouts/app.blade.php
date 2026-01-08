<!DOCTYPE html>
    <html lang="en" class="lang_en">
        <head>
            <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
            <meta http-equiv="X-UA-Compatible" content="IE=edge">
            <meta name="viewport" content="width=device-width, initial-scale=1">
            <meta name="csrf-token" content="{{ csrf_token() }}">
            <meta name="description" content="">
            <meta name="keywords" content="">
            <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
            <title>Directorate of Evaluation Gujarat | Government Of Gujarat</title>
            <link rel="shortcut icon" type="image/png" href="{{asset('css/main_index_css/favicon.png')}}">
            <!-- Bootstrap -->
            <!-- AOS CSS -->
            <link rel="stylesheet" href="{{asset('css/aos.css')}}">

            <link href="{{asset('css/main_index_css/bootstrap.min.css')}}" rel="stylesheet">
            <link rel="stylesheet" href="{{asset('css/main_index_css/flexslider.css')}}" type="text/css" media="screen">
            <link rel="stylesheet" type="text/css" href="{{ asset('css/my-login.css')}}">

            <link rel="stylesheet" type="text/css" href="{{asset('css/slick.css')}}" >
            <link rel="stylesheet" type="text/css" href="{{asset('css/slick-theme.css')}}" />
            <link href="{{asset('css/main_index_css/style.css')}}" rel="stylesheet" id="aStyleLink">
            <link href="{{asset('css/main_index_css/responsive.css')}}" rel="stylesheet">
            <link href="{{asset('css/main_index_css/navigation.css')}}" rel="stylesheet" type="text/css">

            <link href="{{asset('css/main_index_css/font/font.css')}}" rel="stylesheet">
            <link href="{{asset('css/main_index_css/dataTables.bootstrap.min.css')}}" rel="stylesheet" type="text/css">
            <link rel="stylesheet" href="{{asset('css/swiper-bundle.min.css')}}">
            <link rel="stylesheet" href="{{asset('css/chatbot.css')}}">
            <link rel="stylesheet" href="{{asset('css/main_index_css/owl.carousel.css')}}">
        </head>
        <body>
            <div class="page-wrapper d-flex min-vh-100">
                @include('layouts.header')
                <script src="{{asset('js/jquery-laest-version.min.js')}}"></script>
               <script src="{{asset('css/main_index_css/js/custom.js')}}"></script>
                <main class="flex-grow-1">
                <section class="inner-info-sec text-left">
                    <!-- Breadcrumb + Content -->
                    <div class="breadcrumb">
                            @php
                                $routeName = Route::currentRouteName();

                                $breadcrumbs = [
                                    'home' => ['Home'],

                                    'about-us' => ['Home', 'About Us'],
                                    'organization-chart' => ['Home', 'About Us', 'Organization Chart'],
                                    'whos-who' => ['Home', 'About Us', 'Who’s Who'],

                                    'dec' => ['Home', 'Committee', 'DEC'],
                                    'ecc' => ['Home', 'Committee', 'ECC'],

                                    'publication_front_page' => ['Home', 'Publications'],
                                   // 'policy-guidence' => ['Home', 'Policy & Guidence'],
                                   //'media-gallary' => ['Home', 'Media Gallary'],
                                   'right-to-information' => ['Home', 'E-citizen', 'Right to Information'],
                                   'government-resolution' => ['Home', 'E-citizen', 'Goverment Resolution'],

                                    'contact-us' => ['Home', 'Contact Us'],
                                ];

                                $currentBreadcrumb = $breadcrumbs[$routeName] ?? ['Home'];
                            @endphp
                                <div class="container">
                                        <ol>
                                            @foreach($currentBreadcrumb as $key => $crumb)
                                                @if($key === 0)
                                                    <li>
                                                        <a href="{{ url('/') }}">{{ $crumb }}</a>
                                                    </li>
                                                @else
                                                    <li class="active breadcrumCls"> {{ $crumb }}</li>
                                                @endif
                                            @endforeach
                                        </ol>
                                    </div>
                            {{-- <div class="container">
                                <ol>
                                    <div id="myDivrti">
                                        <li><a href="{{env('APP_URL')}}">{{ __('message.home') }}  </a></li>
                                        <li class="active" style="margin-top: 1px;"> {{($formattedSegment != "" ? $formattedSegment : env('APP_NAME'))}} </li>
                                    </div>
                                </ol>
                            
                            </div> --}}
                        </div>
                    <div class="row main-content-sec {{ request()->segment(1) }}">
                    <div class="container">
                        @yield('content')
                    </div>
                    </div>
                </section>
                </main>

                @include('layouts.footer')
            </div>
        </body>
        {{-- <body>
        
            <div class="layout test">
                @include('layouts.header')
                <script src="{{asset('js/jquery-laest-version.min.js')}}"></script>
                <!--<script src="{{asset('css/main_index_css/js/custom.js')}}"></script>-->
                <section class="inner-info-sec text-left">
                    <div class="breadcrumb">
                        @php
                            $segment = request()->segment(1);
                            $formattedSegment = ucwords(str_replace('-', ' ', $segment));
                        @endphp
                        <div class="container">
                            <ol>
                                <div id="myDivrti">
                                    <li><a href="{{env('APP_URL')}}">{{ __('message.home') }}  </a></li>
                                    <li class="active" style="margin-top: 1px;"> {{($formattedSegment != "" ? $formattedSegment : env('APP_NAME'))}} </li>
                                </div>
                            </ol>
                        
                        </div>
                    </div>
                    <div class="row main-content-sec {{ request()->segment(1) }}">
                        <div class="container">
                            @yield('content')
                        </div>
                    </div>
                </section>
            </div>
            @include('layouts.footer')
        </body> --}}
    </html>









