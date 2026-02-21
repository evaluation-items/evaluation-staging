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
    /* Base Layout & Responsive Container */
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

    /* Left Section: Form */
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
        font-size: 30px; 
        font-weight: 700; 
        margin-bottom: 8px; 
    }

    .helper-text { 
        font-size: 14px; 
        color: #666; 
        margin-bottom: 25px; 
    }

    /* Input Styling */
    .form-control {
        width: 100%;
        padding: 12px 15px;
        background: #eef3f7;
        border: 1px solid #d1d9e0;
        border-radius: 6px;
        font-size: 15px;
    }

    .btn-submit {
        width: 100%;
        padding: 12px;
        background: #333;
        color: #fff;
        border: none;
        border-radius: 5px;
        font-weight: bold;
        cursor: pointer;
        margin-top: 20px;
    }

    .btn-submit:hover { background: #000; }

    /* Alert Box Width and Disappear Logic Styling */
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
                <h2 class="sign-in-title">Reset Password</h2>
                <p class="helper-text">Please enter your email and your new password to regain access to your account.</p>

                <form method="POST" action="{{ route('password.update') }}" id="resetFrm">
                    @csrf
                    <input type="hidden" name="token" value="{{ $token }}">

                    <div class="form-group mb-3">
                        <label class="fw-bold mb-1">Email Address</label>
                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ $email ?? old('email') }}" readonly>
                        @error('email')
                            <span class="text-danger small d-block mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group mb-3">
                        <label class="fw-bold mb-1">New Password <span class="text-danger">*</span></label>
                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" placeholder="Enter new password" required>
                        @error('password')
                            <span class="text-danger small d-block mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group mb-3">
                        <label class="fw-bold mb-1">Confirm New Password <span class="text-danger">*</span></label>
                        <input id="password-confirm" type="password" class="form-control" name="password_confirmation" placeholder="Repeat new password" required>
                    </div>

                    <button type="submit" class="btn-submit">Update Password</button>
                </form>
            </div>
        </div>

        <div class="login-section-right" style="flex: 1; background-color: #00426a; color: #fff; padding: 60px; display: flex; align-items: center; position: relative;">
            <div class="welcome-text-wrapper">
                <h1 style="font-size: 28px; font-weight: 500;">Secure Your Identity.</h1>
                <p style="color: rgba(255,255,255,0.7); margin-top: 15px;">Choosing a strong password helps keep your survey data and personal information safe within the Gujarat Government ecosystem.</p>
            </div>
        </div>
    </div>
</div>

<script>
    // Auto-hide alert messages after 5 seconds
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
</script>
</body>
</script>