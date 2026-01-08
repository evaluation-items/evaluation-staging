<footer>  
    <div class="container">      
        <div class="row">
            <div class="col-md-12 col-sm-12 Useful_link_sec">
                <div class="row">
                    <ul>
                        @php
                            $menu_item = App\Models\MenuItem::orderBy('name','ASC')->get();
                        @endphp
                        @foreach ($menu_item as $item)
                         <li><a href="{{route('slug',$item->slug)}}">{{App::getLocale() == 'en' ? $item->name : $item->menu_guj}}</a></li>
                        @endforeach
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

                    <p>{{ __('message.info_manager')}} <strong>{{ __('message.valand')}},</strong>, {{ __('message.dd')}} {{ __('message.director_of_evaluation')}} - {{ __('message.email')}}: <a href="mailto:jdevl@gujarat.gov.in">jdevl[at]gujarat[dot]gov[dot]in</a></p>

                    <p>{{ __('message.manage_by')}} {{ __('message.director_of_evaluation')}}.</p>

                    <div class="btm-content">
                        <span id="vstcnt">{{ __('message.visiter')}} : 68064</span>
                        <p>{{ __('message.last_update')}} : <?php echo date('d-M-Y h:i a', time()); ?></p>
                        
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
</footer>
<script>
    AOS.init({
        // duration: 1000,
        // once: false
    });
</script>
<script>
window.addEventListener('load', function() {
    // jQuery
	  if (window.jQuery) jQuery.fn.jquery = '';

	  // jQuery UI
	  if (window.jQuery && jQuery.ui) jQuery.ui.version = '';

	  // DataTables
	  if (window.jQuery && jQuery.fn.dataTable) jQuery.fn.dataTable.version = '';

	  // FancyBox
	  if (window.jQuery && jQuery.fancybox) jQuery.fancybox.version = '';

	  // Bootstrap
	  if (window.bootstrap && bootstrap.Tooltip) {
		// Bootstrap 5 stores version in bootstrap.Tooltip.VERSION
		bootstrap.Tooltip.VERSION = '';
	  }

     if (localStorage.getItem('wcag-contrast') === 'enabled') {
        applyWCAGContrastTheme();
     }

});
   $(document).ready(function(){

	
    //$('.theme-icon').css('left', '-34%');

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
        arrows: false, // Hide navigation arrows
        dots: false, // Hide bottom dots
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
