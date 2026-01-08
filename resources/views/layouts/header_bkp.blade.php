<header>
    @php
        use Illuminate\Support\Facades\Config;
    @endphp
     <div class="top_bar">
        <div class="container-test">
            <div class="row">
                <div class="header_row">
                    <div class="col-md-7">           
                        <div class="top_links">
                            <a href="javascript:void(0)">
                                <img src="{{asset('css/main_index_css/home-top.png')}}" alt="Home Icon" class="img-responsive">
                            </a>
                                <a href="javascript:void(0)" class="skip_main_content_start" title="click to go home page ">
                                    <img src="{{asset('css/main_index_css/aerow-top.png')}}" alt="Down Arrow Icon" class="img-responsive">
                                    {{ __('message.skip_to_main_content') }}                     
                                </a> 
                            | 
                            <a href="javascript:void(0)" title="click to reach main content">
                                <em class="fa fa-volume-up" aria-hidden="true"></em> 
                                Screen Reader Access 
                            </a>
                        </div>
                    </div> 

                    <div class="col-md-5">
                        <div class="text-right pull-right">
                            <div class="extra-links">
                                <a id="orange" href="javascript:void(0)">A</a>
                                <a id="white" href="javascript:void(0)">A</a>
                                <a id="black" style="background:#000;color:#fff;" href="javascript:void(0)">A</a>
                                <a id="green" href="javascript:void(0)">A</a> 
                            
                            </div>
                            <div class="change-size">
                                <h3>Text Resize </h3>
                                <div class="font_size">
                                    <a href="javascript:void(0)" title="Decrease font size" onclick="decreaseFont();" class="font-minus">A-</a> 
                                    <a href="javascript:void(0)" title="Reset font size" onclick="resetFont();" class="font-normal active">A</a> 
                                    <a href="javascript:void(0)" title="Increase font size" onclick="increaseFont();" class="font-plus">A+</a>
                                </div>
                            </div>
                            <div class="language">
                                <select class="language-select">
                                    <option value="en" {{ App::getLocale() == 'en' ? 'selected' : '' }}>English</option>
                                    <option value="gu" {{ App::getLocale() == 'gu' ? 'selected' : '' }}>ગુજરાતી</option>
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
                <div class="logo-text" style="left:7%; position: relative;margin-top: -60px;">
                    <h5 class="mb-1 fw-bold text-dark" style="font-weight:bold;">
                        {{ __('message.director_of_evaluation') }}
                    </h5>
                    <div class="text-secondary" style="font-size: 0.95rem;margin-top: 1%;">
                        {{ __('message.general_administration') }} <br> 
                        <strong>{{ __('message.government_of_gujarat') }}</strong>
                    </div>
                </div>
            </div>

            <!-- Right: NIC Logo -->
            <div class="col-md-4 text-md-end text-center mt-3 mt-md-0" style="margin-top: 2%;">
                <img src="{{ asset('img/nic_logo.jpg') }}" alt="NIC Logo" style="height: 55px;">
            </div>
        </div>
    </div>
    <nav>
        <div class="container">
            <div class="row">
                <div class="col-md-12 menu-col-12">
                    <div id="sidr" style="transition: left 0.2s ease 0s;" class="sidr left">
                        <ul>
                            <li>
                                <a aria-label="home" href="{{env('APP_URL')}}/">{{ __('message.home') }}</a>
                                {{-- <ul class="sub_nav1">
                                    <div class="col-md-12">
                                        <h2 class="sub_title">
                                            <a href="{{route('slug','background')}}">{{ __('message.background') }}</a> 
                                        </h2>
                                        <h2 class="sub_title">
                                            <a href="{{route('slug','functions')}}">{{ __('message.functions') }}</a> 
                                        </h2>
                                    </div>
                                </ul> --}}
                            </li> 
                            {{-- <li>
                                <a href="{{ route('login')}}">Login</a>  
                                {{-- <ul class="sub_nav1">
                                    <div class="col-md-12">
                                        <h2 class="sub_title ">
                                            <a href="{{Config::get('custom_url.login')}}" target="_blank">ODK Central Login</a> 
                                        </h2>
                                    </div>
                                </ul> 
                            </li> --}}
                            <li>
                                <a href="{{ route('about-us') }}">{{ __('message.about_us') }}</a>  
                                {{-- <ul class="sub_nav1">
                                    <div class="col-md-12">
                                        <h2 class="sub_title ">
                                            <a href="{{Config::get('custom_url.url')}}organization-chart">{{ __('message.organization_chart') }}</a> 
                                        </h2>
                                        <h2 class="sub_title ">
                                            <a href="{{Config::get('custom_url.url')}}whos-who">{{ __('message.whos_who') }}</a> 
                                        </h2>
                                        <h2 class="sub_title ">
                                            <a href="{{Config::get('custom_url.url')}}location-map">{{ __('message.location_map') }}</a> 
                                        </h2>
                                        <h2 class="sub_title ">
                                            <a href="{{Config::get('custom_url.url')}}branch-informations">{{ __('message.branch_information') }}</a> 
                                        </h2>
                                    </div>
                                </ul> --}}
                            </li>
                           
                            <li>
                                <a aria-label="publications" href="{{route('publication_front_page')}}">{{ __('message.publications') }}</a>
                              
                            </li> 
                            <li>
                                <a aria-label="contact-us" href="{{route('contact-us')}}">{{ __('message.contact_us') }}</a>
                            </li> 
                           <li>
                                <a aria-label="publications" href="#">Policy guidance</a>
                            </li>
                             <li>
                                <a aria-label="publications" href="#">Media gallery</a>
                            </li>
                        </ul>
						
						<button class="pull-right btn nav-login-btn" style="/*! margin-right:25%; */margin-top: 1%; border-radius: 18px;border: 0px solid #fff;background-color: #fff;margin-bottom: 1%;">
                            <a  href="{{route('login')}}">
                                {{ __('message.login') }}                        
                            </a>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </nav>
</header>