@extends('layouts.app')
@section('content')
<link href="{{asset('plugins/fontawesome-free/css/all.min.css')}}" rel="stylesheet">
<style>

/* Update your existing CSS to target the new wrapper */
.input-container {
    position: relative;
    display: flex;
    align-items: center; /* This vertically centers the eye automatically */
}

.toggle-password {
    position: absolute;
    right: 12px;
    cursor: pointer;
    color: #888;
    z-index: 10;
    /* No top or margin-top needed here! */
}

.form-control {
    width: 100%;
    padding-right: 40px !important; /* Keep this so text doesn't hide under the eye */
}

/* Ensure the error message stays below the input, not beside it */
label.error {
    display: block;
    width: 100%;
    margin-top: 5px;
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
			<h4 class="card-title login-header" style="text-align: center;margin-bottom: 16px;">Update Email & Password</h4>
			<div class="login-box">
                <form method="POST" action="{{ route('force.reset.submit') }}" class="my-login-validation" autocomplete="off" id="resetFrm">
                    <input type="hidden" name="user_id" value="{{ Crypt::encrypt($user->id) }}">
                    @csrf
                    <div class="form-group">
                        <label for="email">{{ __('message.email')}} <span class="required_filed"> * </span></label>
                            <input id="email" type="email" class="form-control" name="email" placeholder="Enter email" required>
                            @if ($errors->has('email'))
                                <span class="text-danger">{{ $errors->first('email') }}</span>
                            @endif
                    </div>

                            <div class="form-group mb-3">
                                <label for="password">Password</label>
                                <div class="input-container">
                                    <input type="password" id="password" name="password" class="form-control">
                                    <span class="toggle-password">
                                        <i class="fa fa-eye"></i>
                                    </span>
                                </div>
                            </div>

                    <div class="form-group mb-3">
                        <label for="password_confirmation">Confirm Password</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" class="form-control">
                    </div>


                    <div class="form-group">
						<input type="submit" class="btn btn-primary btn-block login-btn" value=" Update &amp; Login" style="background-color:#367be9 !important;margin-top: 20%;">
					</div>
                </form>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
function adjustRememberMargin() {
    var form = $('#resetFrm');
    var firstError = form.find('.has-error').first();
  
}

// Run on form submit
$(document).on('click', '.login-btn', function () {
    adjustRememberMargin();
});

// Run when user interacts with inputs (including checkbox)
$(document).on('input change', '#resetFrm input', function () {
    adjustRememberMargin();
});

$(document).on('click', '.toggle-password', function (e) {
    e.preventDefault(); // Stop any default bubbling
    
    let inputs = $('#password, #password_confirmation');
    let icon = $(this).find('i');

    // Use prop('type') for better cross-browser compatibility
    if (inputs.prop('type') === 'password') {
        inputs.prop('type', 'text');
        icon.removeClass('fa-eye').addClass('fa-eye-slash');
    } else {
        inputs.prop('type', 'password');
        icon.removeClass('fa-eye-slash').addClass('fa-eye');
    }
});

$(document).ready(function () {
    $("#resetFrm").validate({
        rules: {
            email: { 
                required: true,
                email: true 
            },
            password: { 
                required: true,
                minlength: 6
            },
            password_confirmation: { 
                required: true,
                equalTo: "#password"
            }
        },

        messages: {
            email: { required: "Please enter email address" },
            password: { required: "Please enter password" },
            password_confirmation: { 
                required: "Please confirm your password",
                equalTo: "Password does not match"
            }
        },

        highlight: function (element) {
            $(element).addClass("is-invalid");
        },

        unhighlight: function (element) {
            $(element).removeClass("is-invalid");
        },

        // ⭐ Place error BELOW input field
        errorPlacement: function (error, element) {
            error.addClass("text-danger small mt-1 d-block");
            error.insertAfter(element);
        },

        submitHandler: function (form) {
            form.submit();
        }
    });
});


</script>
@endsection