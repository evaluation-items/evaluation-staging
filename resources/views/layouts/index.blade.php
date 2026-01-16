@extends('layouts.app')
@section('content')
    <style>
        [data-aos="fade"] {
            opacity: 0;
            transition: opacity 0.8s ease-in-out;
        }

        [data-aos="fade"].aos-animate {
            opacity: 1;
            color: #464646 !important;
            /* visible text */
        }

        :root {
            --green: #73a92e;
            --panel-bg: #ffffff;
            --accent: #23286b;
            --muted: #6b6b6b;
            --border: #e6e6e6;
            /* --font-sans:"Helvetica Neue",Arial,sans-serif; */
        }

        .whatsnew-card {
            width: 100%;
            /* position: absolute; */
            margin-left: 10%;
            max-width: 420px;
            border-radius: 6px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            background: var(--panel-bg);
            border: 1px solid var(--border);
            /* bottom:55%;  fix to bottom */
        }

        .whatsnew-header {
            background: linear-gradient(180deg, var(--green), #6ea229);
            color: #fff;
            text-align: center;
            padding: 22px 16px;
        }

        .whatsnew-header h2 {
            margin: -16px;
            font-size: 28px;
            letter-spacing: 0.5px;
            line-height: 1;
            font-weight: 700;
        }

        .whatsnew-content {
            height: 250px;
            /* scrolling area height */
            overflow: hidden;
            position: relative;
            background: #fff;
            padding: 0 10px;
        }

        /* animation container */
        .scroll-up {
            display: flex;
            flex-direction: column;
            gap: 10px;
            animation: scrollUp 15s linear infinite;
        }

        /* animation keyframes */
        @keyframes scrollUp {
            0% {
                transform: translateY(100%);
            }

            100% {
                transform: translateY(-100%);
            }
        }

        /* each item */
        .wn-item {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            font-size: 18px;
            color: #000;
        }

        .wn-index {
            font-weight: 700;
        }

        .wn-text span {
            font-weight: 700;
            font-size: 18px;
            display: block;
        }

        .wn-text img {
            vertical-align: middle;
        }

        /* read more */
        .read-more-wrap {
            background: linear-gradient(180deg, #2e2f78, #1d2260);
            text-align: center;
        }

        .read-more-item {
            display: block;
            color: #fff;
            text-transform: uppercase;
            font-weight: 700;
            padding: 18px 12px;
            font-size: 20px;
            text-decoration: none;
            letter-spacing: 1px;
        }

        .main-content-index p,
        .main-content-index li {
            text-align: justify;
            font-size: 15px;
            line-height: 1.3;
            margin-bottom: 10px;
        }

        ol {
            /* padding-left: 22px; */
        }

        @media (max-width: 991px) {
            .whatsnew-card {
                position: static;
                transform: none;
                margin: 20px auto;
                width: 100%;
            }

            .left_col {
                padding-right: 0;
            }

            .sidebar_col {
                padding-left: 0;
                margin-top: 25px;
            }
        }

        @media (max-width: 576px) {
            body {
                font-size: 15px;
            }

            p,
            li {
                font-size: 15px;
            }

            .left_col h4 {
                font-size: 15px;
            }
        }

        .knowledge-links {
            gap: 0px !important;
        }
    </style>
    <section>
        <div id="slider1">
            {{-- <a href="javascript:void(0)" class="control_next">&gt;</a>
            <a href="javascript:void(0)" class="control_prev">&lt;</a> --}}
            <ul>
                <li><img src="{{ asset('img/graph-1.jpeg') }}" alt="Slide 1"></li>
                {{-- <li><img src="{{asset('img/graph-1.jpg')}}" alt="Slide 2"></li>
                <li><img src="{{asset('img/graph-2.png')}}" alt="Slide 3"></li>
                <li><img src="{{asset('img/graph-3.png')}}" alt="Slide 4"></li> --}}

            </ul>
        </div>
        {{-- <script>
            jQuery(document).ready(function($) {
                setInterval(function() {
                    moveRight();
                }, 5000); // 5 seconds

                var slideCount = $('#slider ul li').length;
                var slideWidth = $('#slider').width();
                var sliderUlWidth = slideCount * slideWidth;

                $('#slider ul').css({
                    width: sliderUlWidth,
                    marginLeft: -slideWidth
                });

                $('#slider ul li:last-child').prependTo('#slider ul');

                function moveLeft() {
                    $('#slider ul').animate({
                        left: +slideWidth
                    }, 500, function() {
                        $('#slider ul li:last-child').prependTo('#slider ul');
                        $('#slider ul').css('left', '');
                    });
                }

                function moveRight() {
                    $('#slider ul').animate({
                        left: -slideWidth
                    }, 500, function() {
                        $('#slider ul li:first-child').appendTo('#slider ul');
                        $('#slider ul').css('left', '');
                    });
                }

                $('a.control_prev').click(function() {
                    moveLeft();
                });

                $('a.control_next').click(function() {
                    moveRight();
                });
            });
        </script> --}}
    </section>
    {{-- <div class="container"> --}}
    <a href="{{ Config::get('custom_url.url') }}director-of-evaluation1#" class="skip" id="skip_main_content_start"
        style="visibility: hidden;">skip_main_content_start</a>
    <div class="clear" id="skip_main_content_end"></div>
    <div class="px-4 main-content-index justify-content-center">

        <div class="col-lg-8 col-md-7 col-sm-12 left_col">
            <h4 class="aos-item" data-aos="zoom-in">Directorate Of Evaluation of Gujarat</h4>
            <p></p>
            <h4 class="aos-item" data-aos="zoom-in">1. {{ __('message.background') }} :</h4>
            <p class="aos-item" data-aos="zoom-in">{{ __('message.paregraph-1') }}</p>

            <h4 class="aos-item" data-aos="fade-up">{{ __('message.paregraph-26') }}</h4>
            <ol class="aos-item" data-aos="fade-up">
                <li class="aos-item" data-aos="fade-up">{{ __('message.paregraph-27') }}</li>
                <li class="aos-item" data-aos="fade-up">{{ __('message.paregraph-28') }}</li>
                <li class="aos-item" data-aos="fade-up">{{ __('message.paregraph-29') }}</li>
                <li class="aos-item" data-aos="fade-up">{{ __('message.paregraph-30') }}</li>
                <li class="aos-item" data-aos="fade-up">{{ __('message.paregraph-31') }}</li>
                <li class="aos-item" data-aos="fade-up">{{ __('message.paregraph-32') }}</li>
            </ol>
            <p class="aos-item" data-aos="fade-up">{{ __('message.paregraph-33') }}</p>
            <h4 class="aos-item" data-aos="fade-up">{{ __('message.paregraph-34') }} :</h4>
            <p class="aos-item" data-aos="fade-up">{{ __('message.paregraph-35') }}</p>
            <ol>
                <li class="aos-item" data-aos="fade-up">{{ __('message.paregraph-36') }}</li>
                <li class="aos-item" data-aos="fade-up">{{ __('message.paregraph-37') }}</li>
                <li class="aos-item" data-aos="fade-up">{{ __('message.paregraph-38') }}</li>
                <li class="aos-item" data-aos="fade-up">{{ __('message.paregraph-39') }}</li>
                <li class="aos-item" data-aos="fade-up">{{ __('message.paregraph-40') }}</li>
                <li class="aos-item" data-aos="fade-up">{{ __('message.paregraph-41') }}</li>
                <li class="aos-item" data-aos="fade-up">{{ __('message.paregraph-42') }}</li>
                <li class="aos-item" data-aos="fade-up">{{ __('message.paregraph-43') }}</li>
                <li class="aos-item" data-aos="fade-up">{{ __('message.paregraph-44') }}</li>
                <li class="aos-item" data-aos="fade-up">{{ __('message.paregraph-45') }}</li>
                <li class="aos-item" data-aos="fade-up">{{ __('message.paregraph-46') }}</li>
            </ol>
            {{--   <p>{{ __('message.paregraph-47') }}</p>

            <ol class="knowledge-links">
                <li class="aos-item" data-aos="fade-up"><a aria-label="{{ __('message.public_knowledge') }} 1, PDF file"
                        href="{{ Config::get('custom_url.url') }}uploads/mediafiles/1.pdf">{{ __('message.public_knowledge') }}
                        1</a></li>
                <li class="aos-item" data-aos="fade-up"><a aria-label="{{ __('message.public_knowledge') }} 2, PDF file"
                        href="{{ Config::get('custom_url.url') }}uploads/mediafiles/2.pdf">{{ __('message.public_knowledge') }}
                        2</a></li>
                <li class="aos-item" data-aos="fade-up"><a aria-label="{{ __('message.public_knowledge') }} 3, PDF file"
                        href="{{ Config::get('custom_url.url') }}uploads/mediafiles/3.pdf">{{ __('message.public_knowledge') }}
                        3</a></li>
                <li class="aos-item" data-aos="fade-up"><a aria-label="{{ __('message.public_knowledge') }} 4, PDF file"
                        href="{{ Config::get('custom_url.url') }}uploads/mediafiles/4.pdf">{{ __('message.public_knowledge') }}
                        4</a>
                </li>
            </ol> --}}
            <p>&nbsp;</p>
        </div>
        <div class="col-lg-4 col-md-5 col-sm-12 sidebar_col">
            <div class="link-sec">
                <div class="sub-title">
                    <h4>{{ __('message.other_offices') }}</h4>
                </div>
                <div class="box_wrap">
                    <div class="office-content">
                        <ul class="listed">
                            <li>
                                <a href="https://planning.gujarat.gov.in/">General Administration Department(Planning)</a>
                            </li>
                            <li>
                                <a href="{{ Config::get('custom_url.url') }}">{{ __('message.directorate_economics') }}</a>
                            </li>
                            <li>
                                <a href="https://gujhd.gujarat.gov.in/">{{ __('message.gujarat_social_infra') }}</a>
                            </li>
                        </ul>
                    </div>
                </div>
                {{-- <div class="read-more">
                    <a href="{{ Config::get('custom_url.url') }}other-offices">{{ __('message.more') }}</a>
                </div> --}}
            </div>
            <div class="external-link-info">
                <ul>
                    <li style="display: none">
                        <a href="{{ Config::get('custom_url.vibrantgujarat') }}" target="_blank">
                            <img src="{{ asset('css/main_index_css/Vglogo15225.jpg') }}" alt="Image 1"
                                class="img-responsive">
                        </a>
                    </li>
                    <li>
                        <a href="{{ Config::get('custom_url.gujarattourism') }}" target="_blank">
                            <img src="{{ asset('css/main_index_css/gujarat-tourism.jpg') }}" alt="Image 2"
                                class="img-responsive">
                        </a>
                    </li>
                    <li>
                        <a href="{{ Config::get('custom_url.gujaratindia') }}" target="_blank">
                            <img src="{{ asset('css/main_index_css/explore-gujarat.jpg') }}" alt="Image 3"
                                class="img-responsive">
                        </a>
                    </li>
                </ul>
            </div>
            <div class="whatsnew-card" role="region" aria-label="What's new">
                <div class="whatsnew-header">
                    <h2>What's New</h2>
                </div>

                <div class="whatsnew-content">
                    <div class="scroll-up">
                        @php
                            $name = App\Models\Advertisement::active()->get();
                        @endphp

                        @if ($name->count() > 0)
                            @foreach ($name as $key => $item)
                                <div class="wn-item">
                                    <div class="wn-index">{{ ++$key }}.</div>
                                    <div class="wn-text">
                                        <span>{{ $item->name }}</span>
                                        @if ($item->is_adverties == '1')
                                            <img src="{{ asset('img/new.gif') }}" width="30" height="30">
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>

                <div class="read-more-wrap">
                    <a class="read-more-item" href="#" role="button" aria-label="Read more what's new">Read
                        More</a>
                </div>
            </div>
        </div>
        {{-- <div class="adverties" style="border: 1px solid #000;border: 1px solid #000;
    margin-left: 71%;
    margin-right: -13%;
    margin-top: 38%;">
            
        </div> --}}

        <div class="theme-container closetheme">
            <div class="position-relative">
                {{-- <button class="theme-toggle-button" style="background: none; border: none;"> --}}
                <svg stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 512 512"
                    class="theme-icon" type="button" height="1em" width="1em" xmlns="http://www.w3.org/2000/svg">
                    <path fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="32"
                        d="M262.29 192.31a64 64 0 1 0 57.4 57.4 64.13 64.13 0 0 0-57.4-57.4zM416.39 256a154.34 154.34 0 0 1-1.53 20.79l45.21 35.46a10.81 10.81 0 0 1 2.45 13.75l-42.77 74a10.81 10.81 0 0 1-13.14 4.59l-44.9-18.08a16.11 16.11 0 0 0-15.17 1.75A164.48 164.48 0 0 1 325 400.8a15.94 15.94 0 0 0-8.82 12.14l-6.73 47.89a11.08 11.08 0 0 1-10.68 9.17h-85.54a11.11 11.11 0 0 1-10.69-8.87l-6.72-47.82a16.07 16.07 0 0 0-9-12.22 155.3 155.3 0 0 1-21.46-12.57 16 16 0 0 0-15.11-1.71l-44.89 18.07a10.81 10.81 0 0 1-13.14-4.58l-42.77-74a10.8 10.8 0 0 1 2.45-13.75l38.21-30a16.05 16.05 0 0 0 6-14.08c-.36-4.17-.58-8.33-.58-12.5s.21-8.27.58-12.35a16 16 0 0 0-6.07-13.94l-38.19-30A10.81 10.81 0 0 1 49.48 186l42.77-74a10.81 10.81 0 0 1 13.14-4.59l44.9 18.08a16.11 16.11 0 0 0 15.17-1.75A164.48 164.48 0 0 1 187 111.2a15.94 15.94 0 0 0 8.82-12.14l6.73-47.89A11.08 11.08 0 0 1 213.23 42h85.54a11.11 11.11 0 0 1 10.69 8.87l6.72 47.82a16.07 16.07 0 0 0 9 12.22 155.3 155.3 0 0 1 21.46 12.57 16 16 0 0 0 15.11 1.71l44.89-18.07a10.81 10.81 0 0 1 13.14 4.58l42.77 74a10.8 10.8 0 0 1-2.45 13.75l-38.21 30a16.05 16.05 0 0 0-6.05 14.08c.33 4.14.55 8.3.55 12.47z">
                    </path>
                </svg>
                {{-- </button> --}}
                <div class="font_size-item">
                    <a href="javascript:void(0)" onclick="decreaseFont();">A⁻</a>
                    <a href="javascript:void(0)" class="active" onclick="resetFont();">A</a>
                    <a href="javascript:void(0)" onclick="increaseFont();">A⁺</a>
                    <a id="black" style="background:#000;color:#fff;" href="javascript:void(0)">A</a>
                </div>
                <hr>
                <div class="language-btn">
                    <button class="lang-btn {{ App::getLocale() == 'en' ? 'active' : '' }}"
                        data-lang="en">ENGLISH</button>
                    <button class="lang-btn {{ App::getLocale() == 'gu' ? 'active' : '' }}"
                        data-lang="gu">ગુજરાતી</button>
                </div>
            </div>
        </div>
    </div>
   
@endsection
