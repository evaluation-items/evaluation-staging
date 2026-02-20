<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Transforming Survey Ecosystem, Gujarat</title>
    {{-- <link rel="stylesheet" href="{{ asset('css/login.css') }}"> --}}
    <link href="{{asset('plugins/fontawesome-free/css/all.min.css')}}" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
 <body>
<style>
    /* 1. Base Layout (Matching Login) */
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
        border-radius: 30px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.1);
        overflow: hidden;
    }

    /* 2. Left Section */
    .login-section-left {
        flex: 1.2;
        padding: 50px;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .form-content { 
        max-width: 420px; 
        width: 100%; 
    }

    .nic-branding {
        display: flex;
        gap: 16px;
        margin-bottom: 20px;
    }

    .nic-branding img { height: 60px; }

    .sign-in-title { 
        font-size: 28px; 
        font-weight: 700; 
        margin-bottom: 8px; 
    }

    .helper-text { 
        font-size: 14px; 
        color: #666; 
        margin-bottom: 25px; 
    }

    /* 3. Inputs & Eye Icon */
    .input-container {
        position: relative;
        display: flex;
        align-items: center;
    }

    .form-control {
        width: 100%;
        padding: 12px 15px;
        background: #eef3f7;
        border: 1px solid #d1d9e0;
        border-radius: 6px;
        font-size: 15px;
    }

    .toggle-password {
        position: absolute;
        right: 15px;
        cursor: pointer;
        color: #888;
        z-index: 10;
    }

    .btn-submit {
        width: 100%;
        padding: 12px;
        background: #00426a; /* Theme Blue */
        color: #fff;
        border: none;
        border-radius: 5px;
        font-weight: bold;
        cursor: pointer;
        margin-top: 20px;
    }

    .btn-submit:hover { background: #002d4a; }

    /* 4. Alert Width & Colors */
    .alert {
        max-width: 1000px; 
        margin: 20px auto;
        padding: 12px 20px;
        border-radius: 8px;
        text-align: center;
    }
</style>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12"> 
            @if (session()->has('success'))
                <div class="alert alert-success mt-4">
                    {{ session('success') }}
                </div>
            @endif

            @session("error")
                <div class="alert alert-danger mt-4" role="alert"> 
                    {{ $value }}
                </div>
            @endsession
        </div>
    </div>
</div>

<div class="main-login-wrapper">
    <div class="login-card">
        <div class="login-section-left">
            <div class="nic-branding">
                <img src="{{ asset('img/emblem.png') }}" alt="NIC Logo">
                <div class="logo-text">
                    <h1 class="mb-1 fw-bold text-dark" style="font-size: 1.25rem;">Transforming Survey Ecosystem</h1> 
                    Directorate of Evaluation
                </div>
            </div>

            <div class="form-content">
                <h2 class="sign-in-title">Update Profile</h2>
                <p class="helper-text">Please set your actual email address and a new password to secure your account.</p>

                <form method="POST" action="{{ route('force.reset.submit') }}" id="resetFrm" autocomplete="off">
                    @csrf
                    <input type="hidden" name="user_id" value="{{ Crypt::encrypt($user->id) }}">

                    <div class="form-group mb-3">
                        <label class="fw-bold mb-1">New Email Address <span class="text-danger">*</span></label>
                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" placeholder="Enter valid email" required>
                        @error('email')
                            <span class="text-danger small d-block mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group mb-3">
                        <label class="fw-bold mb-1">New Password <span class="text-danger">*</span></label>
                        <div class="input-container">
                            <input type="password" id="password" name="password" class="form-control" placeholder="Min. 6 characters">
                            <span class="toggle-password">
                                <i class="fa fa-eye"></i>
                            </span>
                        </div>
                    </div>

                    <div class="form-group mb-3">
                        <label class="fw-bold mb-1">Confirm Password <span class="text-danger">*</span></label>
                        <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" placeholder="Repeat new password">
                    </div>

                    <button type="submit" class="btn-submit">Update & Login</button>
                </form>
            </div>
        </div>

        <div class="login-section-right" style="flex: 1; background-color: #00426a; color: #fff; padding: 60px; display: flex; align-items: center; position: relative;">
            <div class="welcome-text-wrapper">
                <h1 style="font-size: 28px; font-weight: 500;">Account Verification</h1>
                <p style="color: rgba(255,255,255,0.7); margin-top: 15px;">To maintain the integrity of the Gujarat Survey Ecosystem, please provide a valid email where you can receive official communications.</p>
            </div>
        </div>
    </div>
</div>

<script src="{{asset('js/jquery-laest-version.min.js')}}"></script>
<script type="text/javascript" src="{{asset('js/jquery.validate.min.js')}}"></script>
<script type="text/javascript">
    // Auto-hide Alerts after 5 seconds
    document.addEventListener('DOMContentLoaded', function() {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(function(alert) {
            setTimeout(function() {
                alert.style.transition = "opacity 0.5s ease";
                alert.style.opacity = "0";
                setTimeout(() => alert.remove(), 500);
            }, 5000); 
        });
    });

    // Toggle Password Visibility
    $(document).on('click', '.toggle-password', function() {
        let passwordField = $('#password');
        let confirmField = $('#password_confirmation');
        let icon = $(this).find('i');

        if (passwordField.attr('type') === 'password') {
            passwordField.attr('type', 'text');
            confirmField.attr('type', 'text'); // Optional: toggle both for convenience
            icon.removeClass('fa-eye').addClass('fa-eye-slash');
        } else {
            passwordField.attr('type', 'password');
            confirmField.attr('type', 'password');
            icon.removeClass('fa-eye-slash').addClass('fa-eye');
        }
    });

    // Validation
    $(document).ready(function () {
        $("#resetFrm").validate({
            rules: {
                email: { required: true, email: true },
                password: { required: true, minlength: 6 },
                password_confirmation: { required: true, equalTo: "#password" }
            },
            messages: {
                email: { required: "Please enter a valid email address" },
                password: { required: "Please provide a new password" },
                password_confirmation: { equalTo: "Passwords do not match" }
            },
            highlight: function (element) { $(element).addClass("is-invalid"); },
            unhighlight: function (element) { $(element).removeClass("is-invalid"); },
            errorPlacement: function (error, element) {
                error.addClass("text-danger small mt-1 d-block");
                if (element.closest('.input-container').length) {
                    error.insertAfter(element.closest('.input-container'));
                } else {
                    error.insertAfter(element);
                }
            }
        });
    });
</script>
</body>
</html>