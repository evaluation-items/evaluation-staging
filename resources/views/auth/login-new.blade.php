
@extends('layouts.app')
@section('content')
<style>
  /* Main Page Wrapper */
.main-login-wrapper {
    display: flex;
    justify-content: center;
    align-items: center;
    /* min-height: 100vh; */
    /* background-color: #f0f2f5; */
    padding: 20px;
}

/* The Split Card */
.login-card {
    display: flex;
    width: 100%;
    max-width: 1100px;
    background: #fff;
    border-radius: 15px;
    overflow: hidden;
    box-shadow: 0 10px 25px rgba(0,0,0,0.1);
}

/* Left Side */
.login-section-left {
    flex: 1;
    padding: 50px;
}

.nic-branding img { height: 60px; margin-bottom: 30px; }
.sign-in-title { color: #008080; font-size: 28px; margin-bottom: 5px; }
.helper-text { font-size: 14px; margin-bottom: 30px; color: #666; }

/* Form Elements */
.form-group { margin-bottom: 20px; }
.form-group label { display: block; font-weight: bold; margin-bottom: 8px; font-size: 13px; }

.password-field-container { position: relative; display: flex; align-items: center; }

.form-control {
    width: 100%;
    padding: 12px;
    background: #eef3f7;
    border: 1px solid #d1d9e0;
    border-radius: 5px;
}

.toggle-password {
    position: absolute;
    right: 12px;
    cursor: pointer;
    color: #888;
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

/* Right Side - THE H1 FIX */
.login-section-right {
    flex: 1;
    background: #00446d; /* Navy Dark Blue */
    background-image: url('{{ asset("img/login_bg.png") }}');
    background-size: cover;
    color: #fff;
    display: flex;
    align-items: center; /* Vertically centers the H1 */
    padding: 60px;
    position: relative; /* This is the anchor for the pattern */
    overflow: hidden;    /* Prevents the pattern from sticking out of the card */
}

.welcome-text-wrapper h1 {
    font-size: 24px;
    font-weight: 500;
    line-height: 1.4;
    margin-bottom: 15px;
}

.welcome-text-wrapper p {
    font-size: 14px;
    color: #8a94a6;
    line-height: 1.6;
}

.pattern {
    position: absolute;
    top: 24px;
    right: 32px;
    width: 150px;
    height: auto;       /* Optional: makes it blend better with the blue */
    pointer-events: none; /* Ensures the image doesn't block clicks */
}
</style>
<div class="main-login-wrapper">
    <div class="login-card">
        <div class="login-section-left">
            <div class="nic-branding">
                <img src="{{ asset('img/emblem.png') }}" alt="Logo" class="logo-gov">
                <img src="{{ asset('img/nic_logo.jpg') }}" alt="NIC Logo">
            </div>

            <div class="form-content">
                <h2 class="sign-in-title">Sign in</h2>
                <p class="helper-text">Not sure where you are? <a href="#">Back to home</a></p>

                <form id="loginForm">
                    <div class="form-group">
                        <label for="email">Email ID *</label>
                        <input type="email" id="email" class="form-control" placeholder="testevl@gujevl.com">
                    </div>

                    <div class="form-group">
                        <label for="password">Password *</label>
                        <div class="password-field-container">
                            <input type="password" id="password" class="form-control" value="********">
                            <span class="toggle-password">
                                <i class="fa fa-eye"></i>
                            </span>
                        </div>
                    </div>

                    <button type="submit" class="btn-submit">Submit</button>
                </form>
            </div>
        </div>

        <div class="login-section-right">
            <div class="welcome-text-wrapper">
                <img src="https://kmea.karnataka.gov.in/assets/front/login_page/pattern.png" class="pattern">
                <h1>Welcome to Transforming Survey Ecosystem, Gujarat.</h1>
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
                
            </div>
        </div>
    </div>
</div>
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
@endsection

