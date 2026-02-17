
@extends('layouts.app')
@section('content')

<link href="{{asset('plugins/fontawesome-free/css/all.min.css')}}" rel="stylesheet">
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
			<h4 class="card-title">Reset Password</h4>
			<form method="POST" class="my-login-validation" novalidate="" action="{{ route('password.update') }}">
				@csrf

				<input type="hidden" name="token" value="{{ $token }}">
				<div class="form-group">
					<label for="email">Email</label>
					<input id="email" type="email" class="form-control" name="email" placeholder="Email address" value="{{ $email ?? old('email') }}">
					<span class="text-danger">@error('email'){{$message}} @enderror</span>
				</div>
				<div class="form-group">
					<label for="password">New Password</label>
					<input id="password" type="password" class="form-control" name="password" placeholder="Enter new password">
					<span class="text-danger">@error('password'){{$message}}@enderror</span>
				</div>
				<div class="form-group">
					<label for="password-confirm">Confirm Password</label>
					<input id="password-confirm" type="password" class="form-control" name="password_confirmation" placeholder="Enter confirm password">
					<span class="text-danger">@error('password_confirmation'){{$message}} @enderror</span>
				</div>

				<div class="form-group m-0">
					<button type="submit" class="btn btn-primary btn-block">
						Reset Password
					</button>
				</div>
			</form>
		</div>
	</div>
</div>
@endsection

