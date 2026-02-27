@extends('layouts.app')
@section('content')
    <style>
        [data-aos="fade"] {
            opacity: 0;
            transition: opacity 0.8s ease-in-out;
        }

        [data-aos="fade"].aos-animate {
            opacity: 1;
            color: #0e0808 !important;
            /* visible text */
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

    /* 1. Background Section */
    .inner-light-box {
        background: linear-gradient(90deg, rgba(33, 37, 41, 0.9) 9%, rgba(67, 137, 207, 0.9) 120%), 
                    url("{{ asset('img/kmea.png') }}") no-repeat center center;
        background-size: cover;
        background-attachment: fixed;
        padding: 80px 20px;
    }

/* 2. Base Card Structure (Universal for all cards) */
.custom-card {
   background: #ffffff;
    border-radius: 15px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    
    /* Fixed Height Settings */
    min-height: 350px; 
    height: 100%; 
    
    display: flex;
    flex-direction: column;
    overflow: hidden;
    transition: transform 0.3s ease;
}

.custom-card:hover {
    transform: translateY(-5px);
}

/* 3. Card Header (For the Dark Title block) */
.card-header-title {
    background: #212529;
    padding: 20px 15px;
    text-align: center;
}

.card-header-title h5 {
    color: #ffffff;
    margin: 0;
    font-size: 1.1rem;
    font-weight: 600;
}

/* 4. Card Body & List Items */
.custom-card .card-body {
    padding: 20px;
    display: flex;
    flex-direction: column;
    flex-grow: 1; /* This pushes the footer to the bottom */
}

.item-list {
    list-style: none;
    padding: 0;
    margin: 0 0 20px 0;
    flex-grow: 1;
}

.item-list li {
    font-size: 14px;
    color: #555;
    padding: 10px 0;
    border-bottom: 1px solid #f0f0f0;
    display: flex;
    align-items: center;
    margin-left: 0px !important;
}

.item-list li i {
    color: #212529;
    margin-right: 10px;
}

/* 5. Button (Centered at bottom) */
.card-footer-btn {
    margin-top: auto; /* Forces button to the bottom of the fixed-size card */
    text-align: center;
    padding-top: 15px;
}

.btn-purple {
    background: #212529;
    color: #fff !important;
    padding: 8px 25px;
    border-radius: 50px;
    font-size: 14px;
    transition: 0.3s;
}

.btn-purple:hover {
    background: #4389cf;
}
.info-card {
   background: #fff;
    border-radius: 12px;
    padding: 20px; /* Slightly more padding looks more professional */
    width: 100%;
    height: 325px; 
    box-shadow: 0 10px 30px rgba(0,0,0,0.05);
    border: 1px solid #e1e1e1;
    display: flex;
    flex-direction: column;
    justify-content: center;
}
.info-card hr {
    border: 0;
    border-top: 1px solid #e0e0e0; /* Light grey line */
    margin: 10px 0; /* Adjust vertical spacing between officials */
    width: 100%;
    opacity: 0.8;
}
.official-card {
    display: flex;
    gap: 15px;
    align-items: center; 
    padding: 5px 0;
}

.official-card img {
    width: 80px;
    height: 90px;
    object-fit: cover;
    border-radius: 8px;
    border: none; /* Remove any existing border */
    box-shadow: 0 2px 5px rgba(0,0,0,0.1); /* Optional: soft shadow instead of a line */
}

.official-info h4 {
    margin: 0;
    color: #0b4d2c;
    font-size: 16px;
    font-weight: 700;
}

.social-icons img {
    width: 30px;
    height: 30px;
}
.aboutUsButton {
    display: flex;
    justify-content: end;
    margin-top: 10px;
    width: 98%;
}
</style>

{{-- <section class="home-bg">
    <div class="container home-content">
        <div class="row" style="padding-top:10px;">
            <div class="col-md-4">
                <!-- LEFT CARD -->
                <div class="info-card">
                    <div class="official-card">
                        <img src="{{ asset('img/cm-guj-planning.png') }}" alt="Chief Minister">
                        <div class="official-info">
                            <h4>Shri Bhupendra Patel</h4>
                            <p>Hon'ble Chief Minister,<br>Government of Gujarat</p>
                            <div class="social-icons">
                                <a href="#"><img src="{{ asset('img/IconeFbcm2.png') }}" alt="Facebook"></a>
                                <a href="#"><img src="{{ asset('img/IconeTwcm2.png') }}" alt="X"></a>
                            </div>
                        </div>
                    </div>
                    <hr>
                     <!-- Secretary -->
                    <div class="official-card">
                        <img src="{{ asset('img/ms-ardra-agrawal.png') }}" alt="Secretary" style=" width: 80px;height: 80px;">
                        <div class="official-info">
                            <h4>Ms. Ardra Agarwal, IAS</h4>
                            <p>Secretary,<br>(Planning)</p>
                        </div>
                    </div>
                    <hr>
                    <div class="official-card">
                        <img src="{{ asset('img/ms-ardra-agrawal.png') }}" alt="Secretary" style=" width: 80px;height: 80px;">
                        <div class="official-info">
                            <h4>Ms. Ardra Agarwal, IAS</h4>
                            <p>Secretary,<br>(Planning)</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-8">
                <!-- RIGHT BANNER -->
                <div class="banner-card">
                    <img src="{{ asset('img/graph-1.jpeg') }}" class="img-fluid" alt="Directorate of Evaluation">
                </div>
            </div>
        </div>
    </div>
</section> --}}

        <div id="slider1 ">
            {{-- <a href="javascript:void(0)" class="control_next">&gt;</a>
            <a href="javascript:void(0)" class="control_prev">&lt;</a> --}}
                    <img src="{{ asset('img/main/final.png') }}" alt="Directorate of Evaluation" style="min-width: 100%;object-fit: fill;height: 500px;">

                   {{--<li><img src="{{asset('img/slider-img-2.png')}}" alt="Slide 2"></li>
               {{-- <li><img src="{{asset('img/graph-2.png')}}" alt="Slide 3"></li>
                <li><img src="{{asset('img/graph-3.png')}}" alt="Slide 4"></li>--}} 
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
 
    {{-- <div class="container"> --}}
        {{-- <section class="officials-section">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-5 mb-3">
                    <div class="official-card"> 
                        <img src="{{ asset('img/cm-guj-planning.png') }}" alt="Chief Minister" style="width: 80px;height: 80px;">
                        <div>
                            <h5>Shri Bhupendra Patel</h5>
                            <p>Hon'ble Chief Minister<br>Government of Gujarat</p>
                            <div class="social-icons">
                                <a href="https://www.facebook.com/ibhupendrapatel" target="_blank"><img title="Facebook" alt="Facebook" src="{{asset('img/IconeFbcm2.png')}}"></a>
                                <a href="https://twitter.com/Bhupendrapbjp" target="_blank"><img title="X" alt="X" src="{{asset('img/IconeTwcm2.png')}}"></a>
                                
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-5 mb-3">
                    <div class="official-card">
                        <img src="{{ asset('img/ms-ardra-agrawal.png') }}" alt="Secretary" style="width: 80px;height: 80px;">
                        <div>
                            <h5>Ms. Ardra Agarwal, IAS</h5>
                            <p>Secretary (Planning)</p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section> --}}

    <a href="{{ Config::get('custom_url.url') }}director-of-evaluation1#" class="skip" id="skip_main_content_start"
        style="visibility: hidden;">skip_main_content_start</a>
    <div class="clear" id="skip_main_content_end"></div>
    <div class="px-4 main-content-index justify-content-center container">
        <div class="row">
            <div class="col-lg-8 col-md-7 col-sm-12 left_col">
                {{-- <div class="info-card">
                    <div class="official-card">
                        <img src="{{ asset('img/cm-guj-planning.png') }}" alt="CM">
                        <div class="official-info">
                            <h4>Shri Bhupendra Patel</h4>
                            <p>Hon'ble Chief Minister</p>
                            <div class="social-icons">
                                <a href="#"><img src="{{ asset('img/IconeFbcm2.png') }}" ></a>
                                <a href="#"><img src="{{ asset('img/IconeTwcm2.png') }}"></a>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="official-card">
                        <img src="{{ asset('img/ms-ardra-agrawal.png') }}" alt="Secretary">
                        <div class="official-info">
                            <h4>Ms. Ardra Agarwal, IAS</h4>
                            <p>Secretary (Planning)</p>
                        </div>
                    </div>
                </div> --}}
                <div class="">
                        <h4 class="aos-item" data-aos="zoom-in">Directorate Of Evaluation</h4>
                        <p></p>
                        {{-- <h4>1. {{ __('message.background') }} :</h4> --}}
                        <p>{{ __('message.paregraph-1') }}</p>
                        <p> Every Evaluation studies are unique in itself as the subject under evaluation are different in context and relevance, target subject and impact in terms of socio-political-economical-cultural and structural realm. Directorate of Evaluation has its own unique way of doing evaluation of plans, projects and schemes etc implemented by various Department of Government. </p>
                            <div class="aboutUsButton"><a href="{{ route('about-us') }}">Know more about us</a></div>
                        {{-- <h4 class="aos-item" data-aos="fade-up">{{ __('message.paregraph-26') }}</h4>
                        <ol class="aos-item" data-aos="fade-up">
                            <li class="aos-item" data-aos="fade-up">{{ __('message.paregraph-27') }}</li>
                            <li class="aos-item" data-aos="fade-up">{{ __('message.paregraph-28') }}</li>
                            <li class="aos-item" data-aos="fade-up">{{ __('message.paregraph-29') }}</li>
                            <li class="aos-item" data-aos="fade-up">{{ __('message.paregraph-30') }}</li>
                            <li class="aos-item" data-aos="fade-up">{{ __('message.paregraph-31') }}</li>
                            <li class="aos-item" data-aos="fade-up">{{ __('message.paregraph-32') }}</li>
                        </ol> --}}
                        {{-- <p class="aos-item" data-aos="fade-up">{{ __('message.paregraph-33') }}</p> --}}
                        
                        <p>&nbsp;</p>
                    </div>
            </div>
            <div class="col-lg-4 col-md-5 col-sm-12 sidebar_col">
                <div class="info-card">
                    <div class="official-card">
                        <img src="{{ asset('img/cm-guj-planning.png') }}" alt="Chief Minister">
                        <div class="official-info">
                            <h4>Shri Bhupendra Patel</h4>
                            <p>Hon'ble Chief Minister,<br>Government of Gujarat</p>
                            <div class="social-icons">
                                <a href="#"><img src="{{ asset('img/IconeFbcm2.png') }}" alt="Facebook"></a>
                                <a href="#"><img src="{{ asset('img/IconeTwcm2.png') }}" alt="X"></a>
                            </div>
                        </div>
                    </div>

                    <hr> <div class="official-card">
                        <img src="{{ asset('img/ms-ardra-agrawal.png') }}" alt="Secretary">
                        <div class="official-info">
                            <h4>Ms. Ardra Agarwal, IAS</h4>
                            <p>Department of Planning Division,<br> Government of Gujarat</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
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
   <section class="outer-section">
    <div class="inner-light-box">
        <div class="container"> 
            <div class="row justify-content-center g-4"> 
                    <div class="col-xl-3 col-lg-4 col-md-6">
                        <div class="custom-card">
                            <div class="card-header-title">
                                <h5>{{ __('message.other_offices') }}</h5>
                            </div>
                            <div class="card-body">
                                <ul class="item-list">
                                    <li><i class="fa fa-angle-double-right" style="font-size:24px"></i><a href="https://planning.gujarat.gov.in/"> General Administration Department(Planning)</a></li>
                                    <li><i class="fa fa-angle-double-right" style="font-size:24px"></i> <a href="https://gujecostat.gujarat.gov.in/">{{ __('message.directorate_economics') }}</a></li>
                                    <li><i class="fa fa-angle-double-right" style="font-size:24px"></i> <a href="https://gujhd.gujarat.gov.in/">{{ __('message.gujarat_social_infra') }}</a></li>
                                </ul>
                                <div class="card-footer-btn">
                                    <a href="#" class="btn btn-purple">Read more</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    @php
                        $name = App\Models\Advertisement::active()->get();
                    @endphp
                    @if ($name->count() > 0)
                    <div class="col-xl-3 col-lg-4 col-md-6">
                        <div class="custom-card">
                            <div class="card-header-title">
                                <h5>What's New</h5>
                            </div>
                            <div class="card-body">
                                <ul class="item-list">
                                    @foreach ($name as $key => $item)
                                    <li>{{ ++$key }}. {{ $item->name }}
                                            @if ($item->is_adverties == '1')
                                                <img src="{{ asset('img/new.gif') }}" width="30" height="30">
                                            @endif</li>
                                    @endforeach
                                </ul>
                                <div class="card-footer-btn">
                                    <a href="#" class="btn btn-purple">Read more</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                    <div class="col-xl-3 col-lg-4 col-md-6">
                        <div class="custom-card">
                            <div class="card-header-title">
                                <h5>Recruitment</h5>
                            </div>
                            <div class="card-body">
                                <ul class="item-list">
                                    <li><i class="fa fa-angle-double-right" style="font-size:24px"></i>Updated Shortly</li>
                                </ul>
                                <div class="card-footer-btn">
                                    <a href="#" class="btn btn-purple">Read more</a>
                                </div>
                            </div>
                        </div>
                    </div>
            </div>
        </div>
    </div>
</section>

@endsection
