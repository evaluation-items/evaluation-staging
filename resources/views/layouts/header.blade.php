<header>
    @php
        use Illuminate\Support\Facades\Config;
    @endphp
    <style>
        @media screen and (max-width: 768px) {
            .nic-image {
                margin-left: 70%;
                margin-top: -7%;
            }

            header .top_bar {
                padding: 7px 4px;
            }

        }

        /* Global styles */
        .nav-toggle {
            display: none;
            /* Default: hidden */
        }

        .close-btn {
            display: none;
            /* Default: hidden */
        }

        /* Show on small screens only */
        @media screen and (max-width: 767px) {
            .nav-toggle {
                display: block;
                /* Show menu button on mobile */
                color: white;
                padding: 8px 12px;
                border: none;
                font-size: 18px;
                cursor: pointer;
                background-color: #02253c;
            }

            #sidr {
                display: none;
                position: fixed;
                top: 0;
                left: -100%;
                height: 100%;
                width: 250px;
                background: #fff;
                z-index: 9999;
                transition: left 0.3s ease;
                box-shadow: 2px 0 5px rgba(0, 0, 0, 0.3);
                padding: 20px;
            }

            #sidr.active {
                display: block;
                left: 0;
                background-color: #02253c;
                color: white;
            }

            .close-btn {
                display: block;
                /* Show menu button on mobile */
                position: absolute;
                top: 10px;
                right: 10px;
                background: #02253c;
                color: white;
                border: none;
                padding: 5px 10px;
                cursor: pointer;
                font-size: 16px;
            }
        }

        #sidr>ul {
            margin-left: 0% !important;
        }
        nav ul li a {
                padding: 2px 8px 2px !important;
        }
       /* parent must be relative */
        .has-submenu {
            position: relative;
        }

        .sub_nav1 {
            display: none;
            position: absolute;
            top: 100%;
            left: 0;
            z-index: 9999;
        }
        nav ul li {
            position: relative;
        }

        nav ul li:hover > .sub_nav1 {
            display: block;
        }
        /* submenu base */
        .sub-dropdown {
            display: none;
            position: absolute;
            top: 76px;
            left: 100%;
            min-width: 180px;
            /* background: #084a84; */
            padding: 0;
            margin: 0;
            list-style: none;
            z-index: 9999;
        }

        /* show submenu on hover */
        .has-submenu:hover > .sub-dropdown {
            display: block;
        }

        /* hover safety (prevents flicker) */
        .has-submenu::after {
            content: '';
            position: absolute;
            top: 0;
            right: -15px;
            width: 15px;
            height: 100%;
        }

        /* submenu items */
        .sub-dropdown li h2 {
            margin: 0;
        }

        .sub-dropdown li a {
            display: block;
            padding: 10px 14px;
            color: #fff;
            text-decoration: none;
            font-size: 13px;
        }
        /* =========================
        MOBILE VIEW ONLY
        ========================= */
      

        /* Hide new Committee menu on desktop */
        .mobile-committee {
            display: none;
        }

        /* Show new Committee menu on mobile */
        @media (max-width: 767px) {
            .mobile-committee {
                display: block;
            }
             .sub_nav1 .has-submenu {
                display: none !important;
            }
        }

    </style>
    <div class="top_bar">
        <div class="container">
            <div class="row">
                <div class="header_row">
                    <div class="col-md-7">
                        <div class="top_links">
                            <a>
                                <img src="{{ asset('css/main_index_css/home-top.png') }}" alt="Home Icon"
                                    class="img-responsive">
                            </a>
                            <a class="skip_main_content_start" role="link" title="Skip to main content">
                                <img src="{{ asset('css/main_index_css/aerow-top.png') }}" alt="Down Arrow Icon"
                                    class="img-responsive">
                                {{ __('message.skip_to_main_content') }}
                            </a>

                        </div>
                    </div>

                    <div class="col-md-5">
                        <div class="text-left pull-left">
                            <button class="nav-toggle d-md-none">☰</button>
                        </div>
                        <div class="text-right pull-right">
                            <div class="search-box" style="margin-top: -12%;margin-left: -2%;">
                                <form action="{{ route('search') }}" method="POST" role="search" class="search-form">
                                    @csrf
                                    <input type="text" name="query"
                                        placeholder="{{ __('message.search_placeholder') }}" aria-label="Search"
                                        autocomplete="off">
                                    <button type="submit" aria-label="Search Button" class="btn-search"
                                        style="margin-left: -3px;"><i class="fa fa-search"></i></button>
                                </form>
                            </div>
                            <!-- <div class="extra-links">
                                <a id="orange" >A</a>
                                <a id="white" >A</a>
                                <a id="black" style="background:#000;color:#fff;" >A</a>
                                <a id="green" >A</a>
                            </div>
                            <div class="change-size">
                                <h3>Text Resize </h3>
                                <div class="font_size">
                                    <a  title="Decrease font size" onclick="decreaseFont();" class="font-minus">A-</a>
                                    <a  title="Reset font size" onclick="resetFont();" class="font-normal active">A</a>
                                    <a title="Increase font size" onclick="increaseFont();" class="font-plus">A+</a>
                                </div>
                            </div>-->
                            <div class="language">
                                <select class="language-select" aria-label="Choose Language">
                                    <option value="en" {{ App::getLocale() == 'en' ? 'selected' : '' }}>English
                                    </option>
                                    <option value="gu" {{ App::getLocale() == 'gu' ? 'selected' : '' }}>ગુજરાતી
                                    </option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container py-3">
        <div class="row align-items-center">
            <!-- Left: Emblem + Text -->
            <div class="col-md-8 d-flex align-items-center">
                <!-- Emblem -->
                <div class="me-3 d-flex align-items-center" style="margin-top: 2%;">
                    <img src="{{ asset('img/emblem.png') }}" alt="Government Emblem" style="height: 66px;">
                </div>

                <!-- Text -->
                <div class="logo-text" style="left:7%; position: relative;margin-top: -8%;">
                    <h1 class="mb-1 fw-bold text-dark" style="font-weight:bold; font-size:1.25rem;">
                        {{ __('message.director_of_evaluation') }}
                    </h1>
                    <div class="text-secondary" style="margin-top: 5px;">
                        <strong> Directorate of Evaluation </strong> <br>
                        {{ __('message.general_administration') }}<br>
                         {{ __('message.government_of_gujarat') }}
                    </div>
                </div>
            </div>

            <!-- Right: NIC Logo -->
            <div class="col-md-4 text-md-end text-center mt-3 mt-md-0" style="margin-top: 2%;">
                <img src="{{ asset('img/nic_logo.jpg') }}" alt="NIC Logo" style="height: 55px;" class="nic-image">
            </div>
        </div>
    </div> 
    <nav style="margin: 28px 0px 0px 0px !important;">
        <div class="container">
            <div class="row">
                <div class="col-md-12 menu-col-12">
                    <div id="sidr" style="transition: left 0.2s ease 0s;" class="sidr left">
                        <button class="close-btn">X</button>
                        <ul style="margin-left: -2% !important;">
                            <li>
                                <a aria-label="home" href="{{ env('APP_URL') }}/">{{ __('message.home') }}</a>
                            </li>
                           <li>
                            <li>
                                <a href="{{ route('about-us') }}" class="menu-toggle">
                                    {{ __('message.about_us') }}
                                    <i class="fa fa-caret-down" aria-hidden="true"></i>
                                </a>
                            
                                <ul class="sub_nav1">
                                    <h2 class="sub_title">
                                        <a href="{{ route('organization-chart') }}">
                                            {{ __('message.organization_chart') }}
                                        </a>
                                    </h2>
                                    <h2 class="sub_title">
                                        <a href="{{ route('vission-mission') }}">
                                            Vision, Mission
                                        </a>
                                    </h2>
                                    <h2 class="sub_title">
                                        <a href="{{ route('whos-who') }}">
                                            {{ __('message.whos_who') }}
                                        </a>
                                    </h2>
                                    <div class="has-submenu">
                                        <h2 class="sub_title submenu-title">
                                            <a href="javascript:void(0)">
                                                Committee <i class="fa fa-caret-right"></i>
                                            </a>
                                        </h2>

                                        <ul class="sub-dropdown">
                                            <h2 class="sub_title">
                                                <a href="{{ route('dec') }}">DEC</a>
                                            </h2>
                                        
                                            <h2 class="sub_title">
                                                <a href="{{ route('ecc') }}">ECC</a>
                                            </h2>
                                        </ul>
                                    </div>
                                </ul>
                            </li>

                            <li>
                                <a aria-label="publications"
                                    href="{{ route('publication_front_page') }}">{{ __('message.publications') }}</a>
                            </li>
                           
                            <li>
                                <a aria-label="privacy-guidance" href="javascript:void(0)">Policy guidance</a>
                            </li>
                            <li>
                                <a aria-label="media-gallery" href="{{route('media-gallery')}}">Media gallery</a>
                            </li>
                            <li>
                                <a  href="javascript:void(0)">E-Citizen <i class="fa fa-caret-down" aria-hidden="true"></i></a>
                                <ul class="sub_nav1" style="right: -135%;">
                                    <div class="col-md-12">
                                        <h2 class="sub_title">
                                            <a href="javascript:void(0)">Right to Information</a>
                                        </h2>
                                        <h2 class="sub_title ">
                                                <a href="{{ route('government-resolution') }}" target="_self" title="">Government Resolution</a>
                                        </h2>
                                    </div>
                                </ul>
                            </li>
                            <li>
                                <a aria-label="contact-us"
                                    href="{{ route('contact-us') }}">{{ __('message.contact_us') }}</a>
                            </li>
                            <!-- Committee (Mobile Visible) -->
                            <li class="mobile-committee">
                                <a href="javascript:void(0)">
                                    Committee <i class="fa fa-caret-down"></i>
                                </a>

                                <ul class="sub_nav1">
                                    <h2 class="sub_title">
                                        <a href="{{ route('dec') }}">DEC</a>
                                    </h2>
                                    <h2 class="sub_title">
                                        <a href="{{ route('ecc') }}">ECC</a>
                                    </h2>
                                </ul>
                            </li>

                        </ul>

                        <button class="pull-right btn nav-login-btn" style="margin-top: 5px; border-radius: 18px;border: 0px solid #fff;background-color: #fff;">
                            <a href="{{ route('login') }}"> {{ __('message.login') }}</a>
                        </button>

                    </div>
                </div>
            </div>
        </div>
    </nav>
</header>
