
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
			<h4 class="card-title">Forgot Password</h4>
			<form method="POST" class="my-login-validation" novalidate="" action="{{ route('password.email') }}">
				@csrf

				@if (session('status'))
					<div class="alert alert-ssuccess">
						{{ session('status') }}
					</div>
				@endif
				<div class="form-group">
					<label for="email">E-Mail Address</label>
					<input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" placeholder="Enter your email">
					<span class="text-danger">@error('email'){{ $message }} @enderror</span>
				</div>

				<div class="form-group mt-10">
					<button type="submit" class="btn btn-primary btn-block">
						Send Password Link
					</button>
				</div>
			</form>
		</div>
	</div>
</div>
@endsection

