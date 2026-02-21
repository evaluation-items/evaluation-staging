<style>
.customer-logos .owl-nav {
    position: absolute;
    top: 50%;
    width: 100%;
    transform: translateY(-50%);
    pointer-events: none;
}

.customer-logos .owl-nav button {
    position: absolute;
    background: transparent !important;
    border: none !important;
    font-size: 26px;
    color: #333 !important;
    pointer-events: all;
}

.customer-logos .owl-prev {
    left: -35px;   /* adjust if needed */
}

.customer-logos .owl-next {
    right: -35px;  /* adjust if needed */
}

.customer-logos .owl-nav button:hover {
    color: #000 !important;
}
</style>
<div class="media-photo">
    <div class="container position-relative">
        <div class="row">
            <div class="col-md-12">
                <div id="footer_logos">
                    <div class="customer-logos owl-carousel owl-theme">
                        <div class="item">
                            <a href="https://www.mospi.gov.in/" target="_blank">
                                <img alt="Sponser Image MSME"
                                    src="{{ asset('img/grit.jpeg') }}"
                                    class="cat_article_img img-responsive img-thumbnail">
                            </a>
                        </div>
                        <div class="item">
                            <a href="https://www.niti.gov.in/" target="_blank">
                                <img alt="Sponser Image MSME"
                                    src="{{ asset('img/niti_ayog.png') }}"
                                    class="cat_article_img img-responsive img-thumbnail">
                            </a>
                        </div>
                         <div class="item">
                            <a href="https://www.mospi.gov.in/" target="_blank">
                                <img alt="Sponser Image MSME"
                                    src="{{ asset('img/mospi.jpeg') }}"
                                    class="cat_article_img img-responsive img-thumbnail">
                            </a>
                        </div>
                        <div class="item">
                            <a href="https://dmeo.gov.in/" target="_blank">
                                <img src="{{ asset('css/main_index_css/dmeo-logo.jpg') }}"
                                     alt="DMEO Logo"
                                     class="img-responsive img-thumbnail">
                            </a>
                        </div>
                        <div class="item">
                            <a href="https://digilocker.gov.in/" target="_blank">
                                <img src="{{ asset('css/main_index_css/Sponsers38JyAsNgwhEKXVZcEyrdw69P3BNMrnBu.jpg') }}"
                                     alt="DigiLocker Logo"
                                     class="img-responsive img-thumbnail">
                            </a>
                        </div>
                        <div class="item">
                            <a href="https://gujaratindia.gov.in/" target="_blank">
                                <img src="{{ asset('css/main_index_css/SponsersgmPixRRy-_TiLtt_3sqNUbETDRBYBLFL.jpg') }}"
                                     alt="Gujarat India Logo"
                                     class="img-responsive img-thumbnail">
                            </a>
                        </div>
                        <div class="item">
                            <a href="https://gswan.gujarat.gov.in/" target="_blank">
                                <img src="{{ asset('css/main_index_css/Sponsersi_pzy_jmPI4sT8qPOszNOAxDWI1GnYUe.jpg') }}"
                                     alt="GSWAN Logo"
                                     class="img-responsive img-thumbnail">
                            </a>
                        </div>
                        <div class="item">
                            <a href="https://www.india.gov.in/" target="_blank">
                                <img src="{{ asset('css/main_index_css/SponsersSZw8qMUVHA7_dFHXpd8hzBqPv8-axvHo.png') }}"
                                     alt="India Gov Logo"
                                     class="img-responsive img-thumbnail">
                            </a>
                        </div>
                        <div class="item">
                            <a href="https://dst.gujarat.gov.in/" target="_blank">
                                <img src="{{ asset('css/main_index_css/SponserseatoNGTlC0c4Cy4fGFKHDoc99qb7vTgo.jpg') }}"
                                     alt="DST Gujarat Logo"
                                     class="img-responsive img-thumbnail">
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<footer>  
    <div class="container">      
        <div class="row">
            <div class="col-md-12 col-sm-12 Useful_link_sec">
                <div class="row">
                     <ul>
                        <li><a href="{{route('accessibility-statement')}}">Accessibility Statement</a></li>
                        <li><a href="{{route('copyright-policy')}}">Copyright Policy</a></li>
                        <li><a href="{{route('feedback')}}">Feedback</a></li>
                        <li><a href="{{route('help')}}">Help</a></li>
                        <li><a href="{{route('hyperlink-policy')}}">Hyperlink Policy</a></li>
                        <li><a href="{{route('privacy-policy')}}">Privacy Policy</a></li>
                        <li><a href="{{route('terms-condition')}}">Terms &amp; Condition</a></li>
                    </ul>
                </div>
                <div class="row">
                    <div class="social-icons">
                        <ul class="footer-social">
                            <li>
                                <a target="_blank" href="https://www.facebook.com/facebook">
                                    <img src="{{asset('css/main_index_css/facebook.png')}}" alt="Facebook Icon" class="img-responsive">
                                </a>
                            </li>
                            <li>
                                <a target="_blank" href="http://www.twitter.com/twitter">
                                    <img src="{{asset('css/main_index_css/twitter.png')}}" alt="Twitter Icon" class="img-responsive">
                                </a>
                            </li>
                            <li>
                                <a target="_blank" href="https://plus.google.com/explore">
                                    <img src="{{asset('css/main_index_css/googleplus.png')}}" alt="Google Plus Icon" class="img-responsive">
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
                
                <div class="row ftr-content">
                    <p><span>{{ __('message.copyright')}} © @php echo date('Y'); @endphp - {{ __('message.all_right')}} - {{ __('message.director_of_evaluation')}}</span></p>

                    <p>{{ __('message.nic')}}</p>

                    <p>{{ __('message.info_manager')}} </strong>, {{ __('message.dd')}} {{ __('message.director_of_evaluation')}} - {{ __('message.email')}}: <a href="mailto:jdevl@gujarat.gov.in">jdevl[at]gujarat[dot]gov[dot]in</a></p>

                    <p>{{ __('message.manage_by')}} {{ __('message.director_of_evaluation')}}.</p>

                    <div class="btm-content">
                         @php
                            //$visitorCount = App\Models\Visitor::count();
                        @endphp
                        <span id="vstcnt">{{ __('message.visiter')}} : 00</span>
                         <p>{{ __('message.last_update')}} : {{ config('app.last_updated') ?? env('SITE_LAST_UPDATED') }}</p>
                       
                        
                        {{-- <figure>
                            <a target="_blank" href="javascript:void(0)">
                                <img src="{{asset('css/main_index_css/ftr_img1.gif')}}" alt="Footer Image 1" class="img-responsive">
                            </a>
                            <a target="_blank" href="javascript:void(0)">
                                <img src="{{asset('css/main_index_css/ftr_img2.png')}}" alt="Footer Image 2" class="img-responsive">
                            </a>
                            <a target="_blank" href="javascript:void(0)">
                                <img src="{{asset('css/main_index_css/ftr_img3.jpg')}}" alt="Footer Image 3" class="img-responsive">
                            </a>
                        </figure> --}}
                    </div>
                </div>
            </div> 
           
            {{-- @include('chatbox')
            <div class="to-top-container1" id="Top-scroll">
                <a href="javascript:void(0)" class="scroll-top on"><span class="screen-reader-text">Go to Top</span></a>
            </div>   --}}
        </div>  
    </div>  

<script src="{{ asset('js/my-login.js')}}"></script>
<script type="text/javascript" src="{{asset('js/jquery.validate.min.js')}}"></script>
<script src="{{ asset('bootstrap/js/popper.min.js')}}"></script>
<script src="{{ asset('bootstrap/js/bootstrap.js')}}"></script>
<script type="text/javascript" src="{{asset('css/main_index_css/slick.min.js.download')}}"></script>
<script type="text/javascript" src="{{asset('css/main_index_css/jquery.sidr.js.download')}}"></script>
<script src="{{asset('css/main_index_css/script.js.download')}}"></script>
<script src="{{asset('css/main_index_css/owl.carousel.js.download')}}"></script>
{{-- <script src="{{asset('css/main_index_css/jquery.dataTables.min.js.download')}}"></script> --}}
{{-- <script src="{{asset('css/main_index_css/dataTables.bootstrap.min.js.download')}}"></script> --}}
<link rel="stylesheet" href="{{asset('css/main_index_css/jquery.fancybox.min.css')}}">
<script src="{{asset('css/main_index_css/jquery.fancybox.min.js.download')}}"></script>
<script src="{{asset('css/main_index_css/jquery.jConveyorTicker.min.js.download')}}"></script>
<script src="{{asset('css/main_index_css/yii.js.download')}}"></script>
<script src="{{asset('css/main_index_css/yii.gridView.js.download')}}"></script>
<script src="{{asset('css/main_index_css/yii.activeForm.js.download')}}"></script>
<script src="{{asset('css/main_index_css/yii.validation.js.download')}}"></script>
<script src="{{asset('css/main_index_css/yii.captcha.js.download')}}"></script>
<link href="{{asset('css/main_index_css/magnific-popup.css')}}" rel="stylesheet">
<script src="{{asset('css/main_index_css/jquery.magnific-popup.min.js.download')}}"></script>
<script defer="" src="{{asset('css/main_index_css/jquery.flexslider.js.download')}}"></script>
<script src="{{asset('js/bootstrap.bundle.min.js')}}"></script>
<script src="{{asset('js/swiper-bundle.min.js')}}"></script>
<script src="{{asset('js/aos.js')}}"></script>
<script src="{{asset('js/common.js')}}"></script>
<script src="{{asset('js/accessibility.js')}}"></script>
</footer>
<script>
    function slideSlider(){
        $("#slider-scroller").css({"left":"0%","transition":"all 0s linear"});
        $("#slider-scroller").css({"left": String(parseInt($("#slider-scroller").css("left")) - 400) + "px","transition":"all 5s linear"});
        setTimeout(function(){moveSliderItem()}, 2635);
    }

        function moveSliderItem(){
        $("#slider-scroller div").first().detach().appendTo($("#slider-scroller"));
        slideSlider();
        }

    slideSlider();
// Function to open sidebar
function openMain() {
    const mainElement = document.getElementById('uw-main');
    if (mainElement) {
        mainElement.style.right = '0px';
    }
}
	
</script>
<script>
    AOS.init({
        //  duration: 800,
        // once: true,       // run only once
        // mirror: false 
    });
</script>
<script>
      
   $(document).ready(function(){

    $('.customer-logos').owlCarousel({
        loop: true,
        margin: 15,
        autoplay: true,
        autoplayTimeout: 3000,
       // nav: true,
       // dots: false,
       // navText: ['&#8249;', '&#8250;'], // ‹ ›
        responsive: {
            0: { items: 1 },
            600: { items: 3 },
            1000: { items: 5 }
        }
    });


    $('#uw-widget-custom-trigger2').on('click', function() {
		openMain();  
	});
    
    $('.nav-toggle').on('click', function() {
        $('#sidr').addClass('active');
    });

    $('.close-btn').on('click', function() {
        $('#sidr').removeClass('active');
    });

     if (localStorage.getItem('wcag-contrast') === 'enabled') {
        applyWCAGContrastTheme();
     }

    $('.theme-icon').on('click', function () {
        const container = $('.theme-container');

        container.toggleClass('opentheme closetheme');

        if (container.hasClass('opentheme')) {
            $('.theme-icon').css('left', '-17%');
          //  $('.theme-container').css('width', '232px');
        } else {
            $('.theme-icon').css('left', '-20%');
            //$('.theme-container').css('width', '220px');
        }
    });
    $('.lang-btn').on('click',function () {
        $('.lang-btn').removeClass('active');
        $(this).addClass('active');
        // Add language change logic here
    });
     $('.skip_main_content_start').on('click',function () {
        $('html, body').animate({
            scrollTop: $("#skip_main_content_end").offset().top
        }, 2000);
    });
    $("#orange").click(function(){
        document.documentElement.style.setProperty('--bg-color', '#f69f20');
        document.documentElement.style.setProperty('--text-color', '#000');
        document.documentElement.style.setProperty('--link-color', '#25189F');
        document.documentElement.style.setProperty('--breadcrumb-color', '#f69f20');

        //document.getElementById('aStyleLink').href= 'css/main_index_css/orange-theme.css';
    });
    
    $("#white").click(function(){
        document.documentElement.style.setProperty('--bg-color', '#fff');
        document.documentElement.style.setProperty('--text-color', '#000');
        document.documentElement.style.setProperty('--header-text-color', '#fff');
        document.documentElement.style.setProperty('--breadcrumb-color', '#f5f5f5');
    });
     $("#black").click(function(){
        if (localStorage.getItem('wcag-contrast') === 'enabled') {
            removeWCAGContrastTheme();
        } else {
            localStorage.setItem('wcag-contrast', 'enabled');
            applyWCAGContrastTheme();
        } 
    });
    $("#green").click(function(){
        document.documentElement.style.setProperty('--bg-color', '#86fb50');
        document.documentElement.style.setProperty('--text-color', '#000');
        document.documentElement.style.setProperty('--breadcrumb-color', '#86fb50');
    });


    $(".scroll-top").on('click',function () {
        $("html, body").animate({ scrollTop: 0 }, "slow");
    });
    $('#sponsor-slider').slick({
        slidesToShow: 4, // Show 4 images at a time
        slidesToScroll: 1,
        autoplay: true, // Auto-play enabled
        autoplaySpeed: 2000, // 2 seconds per slide
        speed: 1000, // Smooth transition speed
        infinite: true, // Loop infinitely
        arrows: true, // Hide navigation arrows
        dots: true, // Hide bottom dots
        pauseOnHover: true,
        responsive: [
            {
                breakpoint: 1024,
                settings: { slidesToShow: 3 }
            },
            {
                breakpoint: 768,
                settings: { slidesToShow: 2 }
            },
            {
                breakpoint: 480,
                settings: { slidesToShow: 1 }
            }
        ]
    });
      $('.language-select').on('change', function () {
        const selectedLang = $(this).val();

        $.ajax({
            url: '{{ route("lang.change") }}',
            method: 'POST',
            data: {
                locale: selectedLang,
                _token: '{{ csrf_token() }}'
            },
            success: function (response) {
                if (response.status === 'success') {
                    location.reload(); // reload current page with new language
                }
            }
        });
    });
      $('.lang-btn').on('click', function () {
            const selectedLang = $(this).data('lang');
            $.ajax({
                url: '{{ route("lang.change") }}',
                method: 'POST',
                data: {
                    locale: selectedLang,
                    _token: '{{ csrf_token() }}'
                },
                success: function (response) {
                    if (response.status === 'success') {
                        location.reload(); // refresh page with new locale
                    }
                }
            });
        });
});
function applyWCAGContrastTheme() {
    document.documentElement.style.setProperty('--bg-color', '#000');
    document.documentElement.style.setProperty('--text-color', '#ff0');
    document.documentElement.style.setProperty('--header-text-color', '#fff');
    document.documentElement.style.setProperty('--breadcrumb-color', '#000');
    document.documentElement.style.setProperty('--form-group-text-color', '#333');
}

function removeWCAGContrastTheme() {
    document.documentElement.style = ''; // resets all custom variables
    localStorage.removeItem('wcag-contrast');
    location.reload(); // optional: to refresh the theme across components
}
 
</script>
<script>
document.querySelectorAll('.menu-toggle').forEach(link => {
    link.addEventListener('click', function (e) {
        if (window.innerWidth > 768) {
            const submenu = this.nextElementSibling;

            if (submenu && submenu.style.display !== 'block') {
                e.preventDefault(); // stop page navigation
                submenu.style.display = 'block';
            }
        }
    });
});
</script>
