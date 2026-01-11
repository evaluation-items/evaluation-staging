
@extends('layouts.app')
@section('content')

<link href="{{asset('plugins/fontawesome-free/css/all.min.css')}}" rel="stylesheet">
<style>


/* Eye Icon */
.toggle-password {
    position: absolute;
    right: 44px;
    top: 37%;
    cursor: pointer;
    color: #888;
}

.toggle-password:hover {
    color: #333;
}
.error{
	color: #e02d2d !important;
}
</style>

<div class="card1" style="border:none !important;">
	@if (session()->has('success'))
			<div class="alert alert-success" style="margin-top: 10%;">
				{{ session()->get('success') }}
			</div>
	@endif
	@session('error')
		<div class="alert alert-danger" role="alert" style="margin-top: 6%;"> 
			{{ $value }}
		</div>
	@endsession
	<div class="login-page officer-login">
		<div class="card-body">
			<h4 class="card-title login-header" style="text-align: center;margin-bottom: 16px;">{{ __('message.login')}}</h4>
			<div class="form-group login-select-option">
				<label for="officer_login">{{ __('message.officer_login')}}</label>
				<select name="officer_login" id="officer_login"	 class="form-control officer_login">
					<option>{{ __('message.select_officer')}}</option>
					<option value="1">{{ __('message.concern_department')}}</option>
					<option value="2">{{ __('message.gad_planing')}}</option>
					<option value="3">{{ __('message.evaluation_office')}}</option>
				</select>
			</div>
			<div class="login-box" style="display:none;">
				<form method="POST" class="my-login-validation" autocomplete="off" action="{{ route('login.submit') }}" id="loginFrm">
					<input type="hidden" name="login_user" class="login_user" value="">
					@csrf
					<div class="form-group">
						<label for="email">{{ __('message.email')}} <span class="required_filed"> * </span></label>
						<input id="email" type="email" class="form-control" name="email" value="" required autofocus placeholder="Enter email">
						@if ($errors->has('message'))
								<span class="text-danger">{{ $errors->first('message') }}</span>
						@endif
						@if ($errors->has('email'))
							<span class="text-danger">{{ $errors->first('email') }}</span>
						@endif
					</div>
					<div class="form-group">
						<label for="password">Password <span class="required_filed"> * </span></label>
						<input id="password" type="password" class="form-control" name="password" placeholder="Enter password" required>
						{{-- <div class="password-wrapper"> --}}
							<span class="toggle-password">
								<i class="fa fa-eye" id="toggleIcon"></i>
							</span>
						{{-- </div> --}}
					</div>
					<div class="form-group">
						<label for="captcha" style="margin-top:2%;">{{ __('message.captcha')}} <span class="required_filed"> * </span></label>
						<div class="captcha">
							<span class="captcha-img">{!! captcha_img('clean') !!}</span>
							<button type="button" class="btn btn-refresh" style="margin-top: -10%;"><i class="fa fa-refresh" style="font-size:22px;"></i>												</button>
						</div>
						<input id="captcha" name="captcha" type="text" class="form-control" placeholder="Enter Captcha" required >
						@if ($errors->has('message'))
							<span class="text-danger">{{ $errors->first('message') }}</span>
						@endif
						@if ($errors->has('captcha'))
							<span class="text-danger">{{ $errors->first('captcha') }}</span>
						@endif
					</div>
					<div class="clearfix"></div>
					<br />
					<div class="form-group form-check d-flex">
						<input type="checkbox" name="remember" id="remember" class="form-check-input">
						<label for="remember" class="form-check-label mt-2">{{ __('message.remember_me')}}</label>
					</div>

					
					{{-- <div class="form-group form-check">
						<input type="checkbox" name="remember" id="remember" class="form-check-input">
						<label for="remember" class="form-check-label mt-2">Remember Me</label>
					</div> --}}
					
					<div class="form-group">
						<input type="submit" class="btn btn-primary btn-block login-btn" value="{{ __('message.login')}}" style="background-color:#367be9 !important;">
					</div>
					{{-- <div class="mt-4 text-center">
						Don't have an account? <a href="{{route('register')}}">Create One</a>
					</div> --}}
				</form>
			</div>
		</div>
	</div>
</div>
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
			$(".captcha span").html(data.captcha);
		}
	});
});
// $('.officer_login').on('change',function(){
// 	var item = $(this).val();
// 	$('.login_user').val(item);
// 	var text = $('.officer_login option:selected').text();
// 	if(item != ""){
// 		$('.login-header').text(text+' Login');
// 		$('.login-box').css('display','block');
// 		$('.login-select-option').css('display','none');
// 	}else{
// 		$('.login-box').css('display','none');
// 		$('.login-select-option').css('display','block');
// 	}
// });
$('.officer_login').on('change', function() {
    var item = $(this).val();
    var text = $('.officer_login option:selected').text();
    $('.login_user').val(item);
    var login_txt = "{{ __('message.login') }}";

    if (item !== "") {
        $('.login-header').text(text+' '+login_txt);
        $('.login-box').fadeIn(); // Smooth display
        $('.login-select-option').hide();
    } else {
        $('.login-box').fadeOut();
        $('.login-select-option').show();
    }
});

$(document).ready(function () {
    $("#loginFrm").validate({
        rules: {
            email: { required: true },
            password: { required: true },
            captcha: { required: true }
        },
        messages: {
            email: { required: "Please enter email address" },
            password: { required: "Please enter password" },
            captcha: { required: "Please enter captcha code" }
        },
        highlight: function (element) {
            // Remove the class when an error occurs
            $(element).closest(".has-error").find(".input-group-append").hide();
        },
        unhighlight: function (element) {
            // Add back the class when input is valid
            $(element).closest(".has-error").find(".input-group-append").show();
        },
        errorPlacement: function (error, element) {
            error.insertAfter(element); // Place error message after input field
        },
        submitHandler: function (form) {
            form.submit();
        }
    });
});
</script>

@endsection

