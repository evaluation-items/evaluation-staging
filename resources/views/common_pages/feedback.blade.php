@extends('layouts.app')
@section('content')
<style>
.feedback .form-group .form-control 
 {
    height: 32px;
    width: 43%;
}
</style>
    <div class="menu-item-pages col-lg-9 col-md-8 col-sm-12 left_col">
        <h4>Feedback</h4>
        <div class="description" style="margin-top: 1%;">
            <p>We welcome your feedback and suggestion about the portal to help us further and serve you better. You may use the Speak Out facility to send us your detailed opinion or fill up the form below to write to us with your input and queries.</p>
            <p>Your feedback is important to us and we will try to respond to your queries as soon as possible.</p>
            <div class="row">
                <div class="col-md-12 feedback">
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
                </div>
      
                <form method="POST" action="{{ route('feedback.submit') }}" id="feedbackFrm" autocomplete="off">
                    @csrf
                    <div class="form-group">
                        <label for="name">Name <span class="required_filed"> * </span></label>
                        <input type="text" class="form-control" id="name" name="name" autocomplete="off">
                        @if ($errors->has('message'))
                                <span class="text-danger">{{ $errors->first('message') }}</span>
                        @endif
                        @if ($errors->has('name'))
                            <span class="text-danger">{{ $errors->first('name') }}</span>
                        @endif
                    </div>
                    <div class="form-group">
                        <label for="contactno">Contact No </label>
                        <input type="text" class="form-control" id="contactno" name="contactno" autocomplete="off">      
                    </div>
                    <div class="form-group">
                        <label for="email">Email <span class="required_filed"> * </span></label>
                        <input type="email" class="form-control" id="email" name="email" autocomplete="off">     
                            @if ($errors->has('message'))
                                    <span class="text-danger">{{ $errors->first('message') }}</span>
                            @endif
                            @if ($errors->has('email'))
                                <span class="text-danger">{{ $errors->first('email') }}</span>
                            @endif 
                    </div>
                    <div class="form-group">
                        <label for="address">Address </label>
                        <input type="text" class="form-control" id="address" name="address" autocomplete="off">      
                    </div>
                    <div class="form-group">
                        <label for="city">City <span class="required_filed"> * </span></label>
                        <input type="text" class="form-control" id="city" name="city" autocomplete="off">  
                            @if ($errors->has('message'))
                                    <span class="text-danger">{{ $errors->first('message') }}</span>
                            @endif
                            @if ($errors->has('city'))
                                <span class="text-danger">{{ $errors->first('city') }}</span>
                            @endif    
                    </div>
                    <div class="form-group">
                        <label for="state">State</label>
                        <input type="text" class="form-control" id="state" name="state" autocomplete="off">      
                    </div>
                    <div class="form-group">
                        <label for="country">Country</label>
                        <input type="text" class="form-control" id="country" name="country" autocomplete="off">      
                    </div>
                    <div class="form-group">
                        <label for="message">Message <span class="required_filed"> * </span></label>
                        <textarea class="form-control" id="message" name="message" rows="4" autocomplete="off"></textarea> 
                        @if ($errors->has('message'))
                                <span class="text-danger">{{ $errors->first('message') }}</span>
                        @endif
                        @if ($errors->has('message'))
                            <span class="text-danger">{{ $errors->first('message') }}</span>
                        @endif               
                    </div>
                    <div class="form-group">
                        <label for="captcha">{{ __('message.captcha')}} <span class="required_filed"> * </span></label>
                        <div class="captcha">
                            <span class="captcha-img">{!! captcha_img('flat') !!}</span>
                            <button type="button" class="btn btn-refresh" style="margin-top: -4%;"><i class="fa fa-refresh" style="font-size:22px;"></i>												</button>
                        </div>
                        <input id="captcha" name="captcha" type="text" class="form-control" placeholder="Enter Captcha" autocomplete="off">
                        @if ($errors->has('message'))
                            <span class="text-danger">{{ $errors->first('message') }}</span>
                        @endif
                        @if ($errors->has('captcha'))
                            <span class="text-danger">{{ $errors->first('captcha') }}</span>
                        @endif
                    </div>
                    <button type="submit" class="btn btn-success" style="margin-top: 1%;margin-bottom: 6%;">Submit</button>
                </form>
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