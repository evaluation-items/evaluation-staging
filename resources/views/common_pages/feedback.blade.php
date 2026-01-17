@extends('layouts.app')
@section('content')
<style>

/* .card-header {
    background-color: #0d6efd;
    /* border-radius: 10px 10px 0 0; 
} */

.form-control {
    border-radius: 6px;
}

.required_filed {
    color: red;
}

/* .btn-primary {
    border-radius: 25px;
    font-weight: 500;
} */
</style>
<div class="row">
    <div class="col-lg-9 col-md-8 col-sm-12 left_col advisory-wrapper">
            <h4 class="page-title text-center">
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
                            <!-- Card Header -->
                            
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
                                <form method="POST" action="{{ route('feedback.submit') }}" autocomplete="off">
                                    @csrf
                                    <div class="inner-form-border">
                                        <!-- Row 1 -->
                                        <div class="row">
                                            <div class="form-group col-md-6">
                                                <label>Name <span class="required_filed">*</span></label>
                                                <input type="text" class="form-control" name="name">
                                                @error('name')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>

                                            <div class="form-group col-md-6">
                                                <label>Contact No</label>
                                                <input type="text" class="form-control" name="contactno">
                                            </div>
                                        </div>

                                        <!-- Row 2 -->
                                        <div class="row">
                                            <div class="form-group col-md-6">
                                                <label>Email <span class="required_filed">*</span></label>
                                                <input type="email" class="form-control" name="email">
                                                @error('email')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>

                                            <div class="form-group col-md-6">
                                                <label>Address</label>
                                                <input type="text" class="form-control" name="address">
                                            </div>
                                        </div>

                                        <!-- Row 3 -->
                                        <div class="row">
                                            <div class="form-group col-md-6">
                                                <label>City <span class="required_filed">*</span></label>
                                                <input type="text" class="form-control" name="city">
                                                @error('city')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>

                                            <div class="form-group col-md-6">
                                                <label>State</label>
                                                <input type="text" class="form-control" name="state">
                                            </div>
                                        </div>

                                        <!-- Row 4 -->
                                        <div class="row">
                                            <div class="form-group col-md-6">
                                                <label>Country</label>
                                                <input type="text" class="form-control" name="country">
                                            </div>
                                        </div>

                                        <!-- Message -->
                                        <div class="form-group">
                                            <label>Message <span class="required_filed">*</span></label>
                                            <textarea class="form-control" name="message" rows="5"
                                                placeholder="Write your feedback here..."></textarea>
                                            @error('message')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <!-- Captcha -->
                                        <div class="form-group">
                                            <label>Captcha <span class="required_filed">*</span></label>

                                            <div class="row align-items-center">
                                                <div class="col-md-4 mb-2">
                                                    {!! captcha_img('clean') !!}
                                                </div>

                                                <div class="col-md-2 mb-2">
                                                    <button type="button" class="btn btn-outline-secondary btn-sm btn-refresh w-100">
                                                        <i class="fa fa-refresh"></i>
                                                    </button>
                                                </div>

                                                <div class="col-md-6">
                                                    <input type="text" class="form-control"
                                                        name="captcha"
                                                        placeholder="Enter captcha">
                                                </div>
                                            </div>

                                            @error('captcha')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <!-- Submit -->
                                        <div class="text-center mt-5 mb-5">
                                            <button type="submit" class="btn btn-primary px-5">
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