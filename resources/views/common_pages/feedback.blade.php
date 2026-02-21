@extends('layouts.app')
@section('content')
<style>

.feedback-card {
    border-radius: 10px;
    border: none;
}

.card-header {
    background: linear-gradient(135deg, #0b3c5d, #0d6efd);
    color: white;
    border-radius: 10px 10px 0 0;
    padding: 15px 20px;
    font-size: 14px;
    font-weight: 600;
    /* text-align: center; */
}

.form-control {
    border-radius: 6px;
    height: 42px;
}

textarea.form-control {
    height: auto;
}

.form-group label {
    font-weight: 500;
    /* color: #333; */
}

.required_filed {
    color: red;
}

.inner-form-border {
    padding: 20px;
}

.btn-primary {
    border-radius: 25px;
    padding: 10px 40px;
    font-weight: 500;
    font-size: 16px;
}

.captcha-box {
    display: flex;
    align-items: center;
    gap: 10px;
}

.captcha-box img {
    border: 1px solid #ddd;
    border-radius: 6px;
    padding: 5px;
    background: #f9f9f9;
}

@media (max-width: 768px) {
    .btn-primary {
        width: 100%;
    }
}
</style>
<div class="container">
    <div class="col-lg-9 col-md-8 col-sm-12 left_col advisory-wrapper">
            <h4 class="page-title text-center mt-3">
                Feedback
            </h4>
            <p class="content-text">
                <p>We welcome your feedback and suggestion about the portal to help us further and serve you better. You may use the Speak Out facility to send us your detailed opinion or fill up the form below to write to us with your input and queries.</p>
                <p>Your feedback is important to us and we will try to respond to your queries as soon as possible.</p>            
            </p>

            <div class="container mt-9 mb-9">
                <div class="row justify-content-center">
                    <div class="col-md-9">
                        <div class="card shadow feedback-card">
    
                            <!-- Header -->
                            <div class="card-header">
                                Submit Your Feedback
                            </div>

                            <div class="card-body">

                                <!-- Alerts -->
                                @if(session('success'))
                                    <div class="alert alert-success">
                                        {{ session('success') }}
                                    </div>
                                @endif

                                @if(session('error'))
                                    <div class="alert alert-danger">
                                        {{ session('error') }}
                                    </div>
                                @endif

                                <!-- Form -->
                                <form id="feedbackFrm" method="POST" action="{{ route('feedback.submit') }}" autocomplete="off">
                                    @csrf

                                    <div class="inner-form-border">

                                        <!-- Row 1 -->
                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Name <span class="required_filed">*</span></label>
                                                    <input type="text" class="form-control" name="name">
                                                    @error('name')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Contact No</label>
                                                    <input type="text" class="form-control" name="contactno">
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Row 2 -->
                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Email <span class="required_filed">*</span></label>
                                                    <input type="email" class="form-control" name="email">
                                                    @error('email')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Address</label>
                                                    <input type="text" class="form-control" name="address">
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Row 3 -->
                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>City <span class="required_filed">*</span></label>
                                                    <input type="text" class="form-control" name="city">
                                                    @error('city')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>State</label>
                                                    <input type="text" class="form-control" name="state">
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Row 4 -->
                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Country</label>
                                                    <input type="text" class="form-control" name="country">
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Message -->
                                        <div class="form-group mb-4">
                                            <label>Message <span class="required_filed">*</span></label>
                                            <textarea class="form-control" name="message" rows="5"
                                                placeholder="Write your feedback here..."></textarea>
                                            @error('message')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <!-- Captcha -->
                                        <div class="form-group mb-4">
                                            <label>Captcha <span class="required_filed">*</span></label>

                                            <div class="captcha-box">
                                                <span class="captcha-img">
                                                    {!! captcha_img('clean') !!}
                                                </span>

                                                <button type="button" class="btn btn-refresh"><i class="fa fa-refresh" style="font-size:22px;"></i></button>

                                                <input type="text" class="form-control"
                                                    name="captcha"
                                                    placeholder="Enter captcha"
                                                    style="max-width: 200px;">
                                            </div>

                                            @error('captcha')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <!-- Submit -->
                                        <div class="text-center mt-4">
                                            <button type="submit" class="btn btn-primary">
                                                Submit Feedback
                                            </button>
                                        </div>

                                    </div>
                                </form>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
    </div>
</div>

<script type="text/javascript">
$(document).ready(function () {
    $(".btn-refresh").on('click',function(){
	var url = "{{ route('refresh_captcha')}}";
	$.ajax({
		type:'GET',
		url: url,
		success:function(data){
			$(".captcha-box span").html(data.captcha);
		}
	});
});
    $("#feedbackFrm").validate({
        rules: {
            name: { required: true },
            email: { required: true },
            city: { required: true },
            message: { required: true },
            captcha: { required: true }
        },
        messages: {
            email: { required: "Please enter email address" },
            name: { required: "Please enter your name" },
            city: { required: "Please enter city" },
            message: { required: "Please enter message" },
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