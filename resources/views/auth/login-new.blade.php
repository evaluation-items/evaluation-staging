<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Transforming Survey Ecosystem, Gujarat</title>
    {{-- <link rel="stylesheet" href="{{ asset('css/login.css') }}"> --}}
    <link href="{{asset('plugins/fontawesome-free/css/all.min.css')}}" rel="stylesheet">
</head>
 <body>
<style>
    body{
        margin: 0;
        
    }
/* 1. Base Layout & Container */
.main-login-wrapper {
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 100vh;
    background-color: #f8f9fa;
    
}

.login-card {
    display: flex;
    width: 100%;
    max-width: 1100px;
    background: #fff;
    border-radius: 15px;
    box-shadow: 0 10px 40px rgba(0,0,0,0.1);
    overflow: hidden; /* Clips the pattern and background */
}

/* 2. Left Form Section */
.login-section-left {
    flex: 1.2;
    padding: 50px;
    display: flex;
    flex-direction: column;
    justify-content: center;
}

.nic-branding img { 
    height: 60px; 
    /* margin-bottom: 40px;  */
}

/* Form Width Control */
.form-content { 
    max-width: 420px; 
    width: 100%; 
}

.sign-in-title { 
    font-size: 32px; 
    font-weight: 700; 
    margin-bottom: 8px; 
}

.helper-text { 
    font-size: 14px; 
    color: #666; 
    margin-bottom: 30px; 
}

.form-group { 
    margin-bottom: 16px; 
}

.form-group label {
    display: block;
    font-weight: 600;
    margin-bottom: 8px;
    font-size: 14px;
}

/* 3. Input & Eye Icon Styling */
.password-field-container {
    position: relative;
    display: flex;
    align-items: center;
}

.form-control {
    width: 100%;
    padding: 12px 15px;
    background: #eef3f7; /* Matching the light blue tint */
    border: 1px solid #d1d9e0;
    border-radius: 6px;
    font-size: 15px;
    box-sizing: border-box;
    transition: border-color 0.2s;
}

.form-control:focus {
    border-color: #00426a;
    outline: none;
}

.toggle-password {
    position: absolute;
    right: 15px;
    cursor: pointer;
    color: #888;
}

/* 4. Captcha & Forgot Link Row */
.captcha-header {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 10px;
}

.captcha-img-box {
    background: #fff;
    border: 1px solid #d1d9e0;
    border-radius: 4px;
    padding: 4px;
    display: flex;
    align-items: center;
}

.btn-refresh {
    background: #eef3f7;
    border: 1px solid #d1d9e0;
    padding: 8px 12px;
    border-radius: 4px;
    cursor: pointer;
    font-size: 18px;
    color: #444;
}

.btn-refresh:hover { background: #dce4eb; }

.forgot-link {
    font-size: 13px;
    color: #007bff;
    margin-left: auto; /* Correctly pushes Forgot Password to the far right */
    text-decoration: none;
}

/* 5. Right Branding Section */
.login-section-right {
    flex: 1;
    background-color: #00426a; /* Theme Blue */
    background-image: url({{ asset('img/login_bg.png') }}); /* Subtle background image */
    background-size: cover;
    background-position: center;
    color: #fff;
    padding: 60px;
    display: flex;
    align-items: center;
    position: relative;
}

.welcome-text-wrapper {
    position: relative;
    z-index: 2;
}

.welcome-text-wrapper h1 {
    font-size: 28px;
    line-height: 1.4;
    font-weight: 500;
    margin-bottom: 20px;
}

.welcome-text-wrapper p {
    font-size: 15px;
    color: rgba(255, 255, 255, 0.7);
    line-height: 1.6;
}

/* 6. Corner Pattern Image */
.corner-pattern {
    position: absolute;
    top: 20px;
    right: 20px;
    width: 130px;
    opacity: 0.4;
    pointer-events: none;
}

/* Align the Checkbox Group */
.form-check {
    display: flex;
    align-items: center; /* This handles the vertical "middle" alignment */
    margin-top: 15px;    /* This adds space ABOVE the whole row */
    gap: 8px;           /* This adds space between the box and the text */
}

.form-check-input {
    margin: 0; /* Important: removes default browser margins that cause shifts */
}

.form-check-label {
    font-size: 14px;
    color: #444;
    cursor: pointer;
    user-select: none;
    line-height: 1; /* Ensures the text height matches the checkbox height */
}

/* Ensure the Submit button has consistent spacing */
.btn-submit {
    width: 100%;
    padding: 12px;
    background: #333;
    color: #fff;
    border: none;
    border-radius: 5px;
    font-weight: bold;
    cursor: pointer;
    margin-top: 15px; /* Space between Remember Me and Button */
}

.btn-submit:hover { background: #000; }
.required_filed {
    color: #dd3131;
}
/* Color Palette */
.text-danger {
    color: #e02d2d !important;
    font-size: 13px;
    font-weight: 500;
}

/* Red border for invalid inputs */
.is-invalid {
    border: 1px solid #e02d2d !important;
}

/* Ensure eye icon stays visible during error */
.password-field-container {
    position: relative;
    width: 100%;
    display: flex;
    align-items: center;
}

/* Prevent error labels from being hidden */
label.error {
    display: block;
    width: 100%;
}
</style>
<div class="main-login-wrapper">
    <div class="login-card">
        <div class="login-section-left">
            <div class="nic-branding">
                <img src="{{ asset('img/emblem.png') }}" alt="NIC Logo">
            </div>

            <div class="form-content">
                <h2 class="sign-in-title">Sign in</h2>
                <p class="helper-text">Not sure where you are? <a href="{{ route('main-index') }}">Back to home</a></p>

                    <form method="POST" class="my-login-validation" autocomplete="off" action="{{ route('login.submit') }}" id="loginFrm">
                        @csrf
                    <div class="form-group">
                        <label for="email">Email ID <span class="required_filed"> * </span></label>
                        <input type="email" id="email" name="email" class="form-control" placeholder="testevl@gujevl.com" required>
                    </div>

                    <div class="form-group">
                        <label for="password">Password <span class="required_filed"> * </span></label>
                        <div class="password-field-container">
                            <input type="password" id="password" name="password" class="form-control" placeholder="********" required>
                            <span class="toggle-password">
                                <i class="fa fa-eye"></i>
                            </span>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="captcha-header">
                            <div class="captcha-img-box">
                                {!! captcha_img('clean') !!}
                            </div>
                            <button type="button" class="btn-refresh">
                                <i class="fa fa-refresh"></i>
                            </button>
                            <a href="{{ route('password.request') }}" class="forgot-link">Forgot Password</a>
                        </div>
                        <input id="captcha" name="captcha" type="text" class="form-control mt-2" placeholder="Enter Captcha" required>
                    </div>
                    <div class="form-group form-check d-flex align-items-center mb-3">
                        <input type="checkbox" name="remember" id="remember" class="form-check-input">
                        <label for="remember" class="form-check-label ms-2">
                            {{ __('message.remember_me')}}
                        </label>
                    </div>
                    <button type="submit" class="btn-submit">Submit</button>
                </form>
            </div>
        </div>

        <div class="login-section-right">
            <img src="https://kmea.karnataka.gov.in/assets/front/login_page/pattern.png" class="corner-pattern">
            <div class="welcome-text-wrapper">
                <h1>Welcome to Transforming Survey Ecosystem, Gujarat.</h1>
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
            </div>
        </div>
    </div>
</div>
 <script src="{{asset('js/jquery-laest-version.min.js')}}"></script>
<script type="text/javascript" src="{{asset('js/jquery.validate.min.js')}}"></script>
{{-- <div class="login-container">
    <div class="login-left">
        <div class="brand-logos">
            <img src="{{ asset('img/emblem.png') }}" alt="Logo" class="logo-gov">
            <div class="divider"></div>
            <img src="{{ asset('img/nic_logo.jpg') }}" alt="CEG" class="logo-ceg">
        </div>

        <div class="login-form-content">
            <h2>Sign in</h2>
            <p class="subtitle">Not sure where you are? <a href="{{ route('main-index') }}">Back to home</a></p>

            <form action="{{ route('login') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label>Email ID *</label>
                    <input type="email" name="email" class="form-control" placeholder="Enter Email ID">
                </div>

                <div class="form-group">
                    <label>Password *</label>
                    <div class="password-wrapper">
                        <input type="password" id="password" name="password" class="form-control" placeholder="Enter Password">
                        <span class="toggle-password">
                            <i class="fa fa-eye"></i>
                        </span>
                    </div>
                </div>

                <button type="submit" class="btn-submit">Submit</button>
            </form>
        </div>
    </div>

    <div class="login-right">
        <div class="overlay-content">
            <h1>Welcome to Transforming Survey Ecosystem, Gujarat.</h1>
            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
            <div class="footer-stats">
                <span class="avatar-group">...</span>
                <span>Built over 500 websites for Govt. of Karnataka.</span>
            </div>
        </div>
    </div>
</div> --}}
<script type="text/javascript">
function adjustRememberMargin() {
    var form = $('#loginFrm');
    var firstError = form.find('.has-error').first();
  
}

// Run on form submit
$(document).on('click', '.login-btn', function () {
    adjustRememberMargin();
});

// Run when user interacts with inputs (including checkbox)
$(document).on('input change', '#loginFrm input', function () {
    adjustRememberMargin();
});


$(document).on('click', '.toggle-password', function() {
    var passwordField = $('#password');
    var icon = $(this).find('i');

    if (passwordField.attr('type') === 'password') {
        passwordField.attr('type', 'text');
        icon.removeClass('fa-eye').addClass('fa-eye-slash');
    } else {
        passwordField.attr('type', 'password');
        icon.removeClass('fa-eye-slash').addClass('fa-eye');
    }
});

$(".btn-refresh").click(function(){
	var url = "{{ route('refresh_captcha')}}";
	$.ajax({
		type:'GET',
		url: url,
		success:function(data){
			$(".captcha-img-box").html(data.captcha);
		}
	});
});

$(document).ready(function () {
    $("#loginFrm").validate({
        rules: {
            email: { required: true, email: true },
            password: { required: true },
            captcha: { required: true }
        },
        messages: {
            email: { required: "Please enter email address" },
            password: { required: "Please enter password" },
            captcha: { required: "Please enter captcha code" }
        },
        highlight: function (element) {
            $(element).addClass("is-invalid"); // Add red border to input
        },
        unhighlight: function (element) {
            $(element).removeClass("is-invalid"); // Remove red border
        },
        errorPlacement: function (error, element) {
            error.addClass("text-danger small d-block mt-1"); // Standardize error style
            
            if (element.attr("name") == "password") {
                // Place error AFTER the password-field-container so eye icon stays inside the box
                error.insertAfter(element.closest('.password-field-container'));
            } else if (element.attr("name") == "captcha") {
                // Place error AFTER the captcha input, below the row
                error.insertAfter(element);
            } else {
                error.insertAfter(element);
            }
        },
        submitHandler: function (form) {
            form.submit();
        }
    });
});
</script>
 </body>
</html>

