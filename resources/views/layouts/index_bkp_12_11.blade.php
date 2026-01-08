@extends('layouts.app')
@section('content')

            <section>
                <div id="slider">
                    <a href="javascript:void(0)" class="control_next">&gt;</a>
                    <a href="javascript:void(0)" class="control_prev">&lt;</a>
                    <ul>
                        <li><img src="{{asset('img/graph-1.jpeg')}}" alt="Slide 1"></li>
                        <li><img src="{{asset('img/graph-1.jpg')}}" alt="Slide 2"></li>
                        <li><img src="{{asset('img/graph-2.png')}}" alt="Slide 3"></li>
                        <li><img src="{{asset('img/graph-3.png')}}" alt="Slide 4"></li>
                        
                    </ul>
                </div>
                <script>
                    jQuery(document).ready(function ($) {
                            setInterval(function () {
                                moveRight();
                            }, 5000); // 5 seconds

                            var slideCount = $('#slider ul li').length;
                            var slideWidth = $('#slider').width();
                            var sliderUlWidth = slideCount * slideWidth;

                            $('#slider ul').css({ width: sliderUlWidth, marginLeft: -slideWidth });

                            $('#slider ul li:last-child').prependTo('#slider ul');

                            function moveLeft() {
                                $('#slider ul').animate({
                                    left: +slideWidth
                                }, 500, function () {
                                    $('#slider ul li:last-child').prependTo('#slider ul');
                                    $('#slider ul').css('left', '');
                                });
                            }

                            function moveRight() {
                                $('#slider ul').animate({
                                    left: -slideWidth
                                }, 500, function () {
                                    $('#slider ul li:first-child').appendTo('#slider ul');
                                    $('#slider ul').css('left', '');
                                });
                            }

                            $('a.control_prev').click(function () {
                                moveLeft();
                            });

                            $('a.control_next').click(function () {
                                moveRight();
                            });
                    });  
                </script>
            </section>
        {{-- <div class="container"> --}}
            <a href="{{Config::get('custom_url.url')}}director-of-evaluation1#" class="skip" id="skip_main_content_start" style="visibility: hidden;">skip_main_content_start</a>
            <div class="clear" id="skip_main_content_end"></div>
            <div class="row px-4 main-content-index">
                <div class="col-lg-9 col-md-8 col-sm-12 left_col">
                    <h4 class="aos-item" data-aos="zoom-in">{{ __('message.director_of_evaluation')}}</h4><p>{{ __('message.activities_of_directorate') }}</p>
                    <h4 class="aos-item" data-aos="zoom-in">1. {{ __('message.background') }} :</h4>
                    <p class="aos-item" data-aos="zoom-in">{{ __('message.paregraph-1')}}</p>
                    <h4>2. {{ __('message.org_structure')}}:</h4>
                    <p class="aos-item" data-aos="zoom-in">{{ __('message.paregraph-2')}}</p>
                        <table class="table table-bordered table-striped">
                            <thead class="table-dark">
                                <tr>
                                    <th>{{ __('message.sr_no') }}</th>
                                    <th>{{ __('message.category') }}</th>
                                    <th>{{ __('message.no_of_posts') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr><td class="text-center">1</td><td class="text-center">Class I</td><td class="text-center">9</td></tr>
                                <tr><td class="text-center">2</td><td class="text-center">Class II</td><td class="text-center">8</td></tr>
                                <tr><td class="text-center">3</td><td class="text-center">Class III</td><td class="text-center">53</td></tr>
                                <tr><td class="text-center">4</td><td class="text-center">Class IV</td><td class="text-center">5</td></tr>
                                <tr class="table-warning"><td colspan="2">{{ __('message.total') }}</td><td class="text-center">75</td></tr>
                            </tbody>
                        </table>
                    {{-- </figure> --}}
                    <h4>3. {{ __('message.advisory_committee')}} :</h4>
                    <p class="aos-item" data-aos="zoom-in">{{ __('message.paregraph-3')}} </p><p>(I) {{ __('message.paregraph-4')}}</p>
                    <p class="aos-item" data-aos="fade-up"> {{ __('message.paregraph-5')}}</p>
                    <table class="table table-bordered table-striped aos-item" data-aos="fade-up">
                        <thead class="table-dark">
                            <tr>
                                <th>{{ __('message.sr_no') }}</th>
                                <th colspan="2">{{ __('message.committee') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr><td class="text-center">(1)</td><td>{{ __('message.paregraph-5')}}</td><td class="text-center">{{ __('message.chairman') }}</td></tr>
                            <tr><td class="text-center">(2)</td><td>{{ __('message.paregraph-7')}}</td> <td class="text-center">{{ __('message.member') }}</td></tr>
                            <tr><td class="text-center">(3)</td><td>{{ __('message.director_of_evaluation')}} </td><td class="text-center">{{ __('message.member') }}</td></tr>
                            <tr><td class="text-center">(4)</td><td>{{ __('message.paregraph-8')}}</td><td class="text-center">{{ __('message.member') }}</td></tr>
                            <tr><td class="text-center">(5)</td><td>{{ __('message.paregraph-9')}}</td><td class="text-center">{{ __('message.member') }}</td></tr>
                            <tr><td class="text-center">(6)</td><td>{{ __('message.paregraph-10')}}</td><td class="text-center">{{ __('message.member_secretary') }}</td></tr>
                        </tbody>
                    </table>
                    <p class="aos-item" data-aos="fade-up">{{ __('message.functions_of_committee')}} :</p>
                    <ol>
                        <li class="aos-item" data-aos="fade-up">{{ __('message.paregraph-17')}}</li>
                        <li class="aos-item" data-aos="fade-up">{{ __('message.paregraph-18')}}</li>
                        <li class="aos-item" data-aos="fade-up">{{ __('message.paregraph-19')}}</li>
                    </ol>
                    <p class="aos-item" data-aos="fade-up">(II) {{ __('message.evaluation_coordination_committee')}}</p>
                    <p class="aos-item" data-aos="fade-up"> {{ __('message.paregraph-20')}}</p>
                
                    <table class="table table-bordered table-striped aos-item"  data-aos="fade-up">
                        <thead class="table-dark">
                            <tr>
                                <th>{{ __('message.sr_no') }}</th>
                                <th colspan="2">{{ __('message.committee') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr><td class="text-center">(1)</td><td>{{ __('message.paregraph-11')}}</td><td class="text-center">{{ __('message.chairman') }}</td></tr>
                            <tr><td class="text-center">(2)</td><td>{{ __('message.paregraph-12')}}</td><td class="text-center">{{ __('message.member') }}</td></tr>
                            <tr><td class="text-center">(3)</td><td>{{ __('message.paregraph-13')}}</td><td class="text-center">{{ __('message.member') }}</td></tr>
                            <tr><td class="text-center">(4)</td><td>{{ __('message.paregraph-14')}}</td><td class="text-center">{{ __('message.member') }}</td></tr>
                            <tr><td class="text-center">(5)</td><td>{{ __('message.paregraph-15')}}</td><td class="text-center">{{ __('message.member') }}</td></tr>
                            <tr><td class="text-center">(6)</td><td>{{ __('message.director_of_evaluation')}}</td><td class="text-center">{{ __('message.member') }}</td></tr>
                            <tr><td class="text-center">(7)</td><td>{{ __('message.paregraph-16')}}</td><td class="text-center">{{ __('message.invitee') }}</td></tr>
                        </tbody>
                    </table>
                    <p class="aos-item" data-aos="fade-up">{{ __('message.functions_of_committee')}} :</p>
                    <ol>
                        <li class="aos-item" data-aos="fade-up">{{ __('message.paregraph-21')}}</li>
                        <li class="aos-item" data-aos="fade-up">{{ __('message.paregraph-22')}}</li>
                        <li class="aos-item" data-aos="fade-up">{{ __('message.paregraph-23')}}</li>
                        <li class="aos-item" data-aos="fade-up">{{ __('message.paregraph-24')}}</li>
                        <li class="aos-item" data-aos="fade-up">{{ __('message.paregraph-25')}}</li>
                    </ol>
                    <h4 class="aos-item" data-aos="fade-up">{{ __('message.paregraph-26')}}</h4>
                    <ol class="aos-item" data-aos="fade-up">
                        <li class="aos-item" data-aos="fade-up">{{ __('message.paregraph-27')}}</li>
                        <li class="aos-item" data-aos="fade-up">{{ __('message.paregraph-28')}}</li>
                        <li class="aos-item" data-aos="fade-up">{{ __('message.paregraph-29')}}</li>
                        <li class="aos-item" data-aos="fade-up">{{ __('message.paregraph-30')}}</li>
                        <li class="aos-item" data-aos="fade-up">{{ __('message.paregraph-31')}}</li>
                        <li class="aos-item" data-aos="fade-up">{{ __('message.paregraph-32')}}</li>
                    </ol>
                    <p class="aos-item" data-aos="fade-up">{{ __('message.paregraph-33')}}</p>
                    <h4 class="aos-item" data-aos="fade-up">5. {{ __('message.paregraph-34')}} :</h4>
                    <p class="aos-item" data-aos="fade-up">{{ __('message.paregraph-35')}}</p>
                    <ol>
                        <li class="aos-item" data-aos="fade-up">{{ __('message.paregraph-36')}}</li>
                        <li class="aos-item" data-aos="fade-up">{{ __('message.paregraph-37')}}</li>
                        <li class="aos-item" data-aos="fade-up">{{ __('message.paregraph-38')}}</li>
                        <li class="aos-item" data-aos="fade-up">{{ __('message.paregraph-39')}}</li>
                        <li class="aos-item" data-aos="fade-up">{{ __('message.paregraph-40')}}</li>
                        <li class="aos-item" data-aos="fade-up">{{ __('message.paregraph-41')}}</li>
                        <li class="aos-item" data-aos="fade-up">{{ __('message.paregraph-42')}}</li>
                        <li class="aos-item" data-aos="fade-up">{{ __('message.paregraph-43')}}</li>
                        <li class="aos-item" data-aos="fade-up">{{ __('message.paregraph-44')}}</li>
                        <li class="aos-item" data-aos="fade-up">{{ __('message.paregraph-45')}}</li>
                        <li class="aos-item" data-aos="fade-up">{{ __('message.paregraph-46')}}</li>
                    </ol>
                    <p>{{ __('message.paregraph-47')}}</p>
                    
                    <ol class="knowledge-links">
                        <li class="aos-item" data-aos="fade-up"><a href="{{Config::get('custom_url.url')}}uploads/mediafiles/1.pdf">{{ __('message.public_knowledge') }} 1</a></li>
                        <li class="aos-item" data-aos="fade-up"><a href="{{Config::get('custom_url.url')}}uploads/mediafiles/2.pdf">{{ __('message.public_knowledge') }} 2</a></li>
                        <li class="aos-item" data-aos="fade-up"><a href="{{Config::get('custom_url.url')}}uploads/mediafiles/3.pdf">{{ __('message.public_knowledge') }} 3</a></li>
                        <li class="aos-item" data-aos="fade-up"><a href="{{Config::get('custom_url.url')}}uploads/mediafiles/4.pdf">{{ __('message.public_knowledge') }} 4</a></li>
                    </ol>
                    <p>&nbsp;</p> 
                </div>
                
                <div class="col-lg-3 col-md-4 col-sm-12 sidebar_col">
                        <div class="link-sec">
                            <div class="sub-title">
                                <h4>{{ __('message.other_offices')}}</h4>
                            </div>

                            <div class="box_wrap">
                            <h2>{{ __('message.other_offices')}}</h2>
                                <div class="office-content">
                                        <ul class="listed">
                                            <li><a href="{{Config::get('custom_url.url')}}gujarat-social-infrastructure-development-society">{{ __('message.gujarat_social_infra')}}</a></li>
                                            <li><a href="{{Config::get('custom_url.url')}}">{{ __('message.directorate_economics')}}</a></li>
                                        </ul>
                                </div>
                            </div>

                            <div class="read-more">
                                <a href="{{Config::get('custom_url.url')}}other-offices">{{ __('message.more')}}</a>
                            </div>
                        </div>
                        <div class="external-link-info">
                            <ul>
                                <li style="display: none">
                                    <a href="{{Config::get('custom_url.vibrantgujarat')}}" target="_blank">
                                        <img src="{{asset('css/main_index_css/Vglogo15225.jpg')}}" alt="Image 1" class="img-responsive">
                                    </a>
                                </li>
                                <li>
                                    <a href="{{Config::get('custom_url.gujarattourism')}}" target="_blank">
                                        <img src="{{asset('css/main_index_css/gujarat-tourism.jpg')}}" alt="Image 2" class="img-responsive">
                                    </a>
                                </li>
                                <li>
                                <a href="{{Config::get('custom_url.gujaratindia')}}" target="_blank">
                                    <img src="{{asset('css/main_index_css/explore-gujarat.jpg')}}" alt="Image 3" class="img-responsive">
                                </a>
                                </li>
                            </ul>
                        </div>
                </div> 

                <div class="theme-container closetheme">
                    <div class="position-relative">
                            {{-- <button class="theme-toggle-button" style="background: none; border: none;"> --}}
                            <svg stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 512 512" class="theme-icon" type="button" height="1em" width="1em" xmlns="http://www.w3.org/2000/svg"><path fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="32" d="M262.29 192.31a64 64 0 1 0 57.4 57.4 64.13 64.13 0 0 0-57.4-57.4zM416.39 256a154.34 154.34 0 0 1-1.53 20.79l45.21 35.46a10.81 10.81 0 0 1 2.45 13.75l-42.77 74a10.81 10.81 0 0 1-13.14 4.59l-44.9-18.08a16.11 16.11 0 0 0-15.17 1.75A164.48 164.48 0 0 1 325 400.8a15.94 15.94 0 0 0-8.82 12.14l-6.73 47.89a11.08 11.08 0 0 1-10.68 9.17h-85.54a11.11 11.11 0 0 1-10.69-8.87l-6.72-47.82a16.07 16.07 0 0 0-9-12.22 155.3 155.3 0 0 1-21.46-12.57 16 16 0 0 0-15.11-1.71l-44.89 18.07a10.81 10.81 0 0 1-13.14-4.58l-42.77-74a10.8 10.8 0 0 1 2.45-13.75l38.21-30a16.05 16.05 0 0 0 6-14.08c-.36-4.17-.58-8.33-.58-12.5s.21-8.27.58-12.35a16 16 0 0 0-6.07-13.94l-38.19-30A10.81 10.81 0 0 1 49.48 186l42.77-74a10.81 10.81 0 0 1 13.14-4.59l44.9 18.08a16.11 16.11 0 0 0 15.17-1.75A164.48 164.48 0 0 1 187 111.2a15.94 15.94 0 0 0 8.82-12.14l6.73-47.89A11.08 11.08 0 0 1 213.23 42h85.54a11.11 11.11 0 0 1 10.69 8.87l6.72 47.82a16.07 16.07 0 0 0 9 12.22 155.3 155.3 0 0 1 21.46 12.57 16 16 0 0 0 15.11 1.71l44.89-18.07a10.81 10.81 0 0 1 13.14 4.58l42.77 74a10.8 10.8 0 0 1-2.45 13.75l-38.21 30a16.05 16.05 0 0 0-6.05 14.08c.33 4.14.55 8.3.55 12.47z"></path></svg>
                        {{-- </button> --}}
                           <div class="font_size-item">
                                <a href="javascript:void(0)" onclick="decreaseFont();">A⁻</a>
                                <a href="javascript:void(0)" class="active" onclick="resetFont();">A</a>
                                <a href="javascript:void(0)" onclick="increaseFont();">A⁺</a>
                                <a id="black" style="background:#000;color:#fff;" href="javascript:void(0)">A</a> 
                            </div>

                            <hr>

                            <div class="language-btn">
                                <button class="lang-btn {{ App::getLocale() == 'en' ? 'active' : '' }}" data-lang="en">ENGLISH</button>
                                <button class="lang-btn {{ App::getLocale() == 'gu' ? 'active' : '' }}" data-lang="gu">ગુજરાતી</button>
                            </div>
                    </div>   
                </div>
            </div>

        <section>
            <div class="media-photo">
                <div class="container">
                    <div class="row">
                        <div id="footer_logos">
                            <div class="container">
                                <div class="col-md-12 customer-logos">
                                    <div id="sponsor-slider">
                                        <div class="slider-item">
                                            <img alt="Sponsor Image" src="{{ asset('css/main_index_css/Sponsersoi99Cm54sMzjlcZw-Gk0SAgu2wgMpZhU.jpg') }}" class="img-responsive img-thumbnail">
                                        </div>
                                        <div class="slider-item">
                                            <img alt="Sponsor Image" src="{{ asset('css/main_index_css/Sponsers38JyAsNgwhEKXVZcEyrdw69P3BNMrnBu.jpg') }}" class="img-responsive img-thumbnail">
                                        </div>
                                        <div class="slider-item">
                                            <img alt="Sponsor Image" src="{{ asset('css/main_index_css/SponsersgmPixRRy-_TiLtt_3sqNUbETDRBYBLFL.jpg') }}" class="img-responsive img-thumbnail">
                                        </div>
                                        <div class="slider-item">
                                            <img alt="Sponsor Image" src="{{ asset('css/main_index_css/Sponsersi_pzy_jmPI4sT8qPOszNOAxDWI1GnYUe.jpg') }}" class="img-responsive img-thumbnail">
                                        </div>
                                        <div class="slider-item">
                                            <img alt="Sponsor Image" src="{{ asset('css/main_index_css/SponsersSZw8qMUVHA7_dFHXpd8hzBqPv8-axvHo.png') }}" class="img-responsive img-thumbnail">
                                        </div>
                                        <div class="slider-item">
                                            <img alt="Sponsor Image" src="{{ asset('css/main_index_css/SponserseatoNGTlC0c4Cy4fGFKHDoc99qb7vTgo.jpg') }}" class="img-responsive img-thumbnail">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section> 
@endsection