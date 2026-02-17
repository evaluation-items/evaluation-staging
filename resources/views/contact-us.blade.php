@extends('layouts.app')
@section('content')
<style>
.page-title {
    font-weight: 600;
    margin-top: 50px;
    color: #084a84;
}

p a {
    color: #084a84;
    text-decoration: none;
}

p a:hover {
    text-decoration: underline;
}

</style>
<div class="container my-4">

    <!-- Page Title -->
    <div class="row mb-3">
        <div class="col-12">
            <h4 class="page-title text-center">Contact Us</h4>
            <hr>
        </div>
    </div>

    <!-- Content -->
    <div class="row justify-content-center">

        <!-- Address -->
        <div class="col-lg-5 col-md-6 col-sm-12 mb-4">
            <h5 class="mb-3">Address:</h5>

            <p class="mb-2">
                <strong>Directorate of Evaluation</strong><br>
              {{ __('message.general_administration') }}<br>
                Sector 18, Gandhinagar,<br>
                Gujarat – 382009
            </p>

            <p class="mb-2">
                <strong>Ph. No.:</strong>
                <a href="tel:07923252861">079-23252861</a>
            </p>
            <p>
                <strong>E-mail:</strong>
                <a href="mailto:direvl@gujarat.gov.in">direvl@gujarat.gov.in</a>
            </p>
        </div>

        <!-- Map -->
        <div class="col-lg-6 col-md-6 col-sm-12 mb-4">
            <iframe
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3666.786831048218!2d72.6594427!3d23.214437999999994!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x395c2babb2d53019%3A0x36c6aec1097de573!2sDirectorate%20Of%20Economics%20And%20Statistics!5e0!3m2!1sen!2sin!4v1718779749402!5m2!1sen!2sin"
                width="100%"
                height="280"
                style="border:1px solid #ddd; border-radius:4px;"
                allowfullscreen
                loading="lazy"
                referrerpolicy="no-referrer-when-downgrade">
            </iframe>
        </div>

    </div>
</div>
@endsection