<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html lang="en">
<head>  
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('title')</title>
  <base href="{{ \URL::to('/') }}">

  <!-- Google Font: Source Sans Pro -->
 <link rel="stylesheet" href="{{asset('css/font-family.css')}}">
  <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="{{ asset('plugins/fontawesome-free/css/all.min.css') }}">
  <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
   <link rel="stylesheet" href="{{ asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
  <link rel="stylesheet" href="{{ asset('plugins/ijaboCropTool/ijaboCropTool.min.css') }}">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{ asset('dist/css/adminlte.min.css') }}">
  {{-- <link rel="stylesheet" type="text/css" href="{{ asset('plugins/datatables-jquery/dataTables.min.css') }}"> --}}
  <link rel="stylesheet" type="text/css" href="{{ asset('css/style.css') }}">
  <link href="{{asset('css/jquery-ui.css')}}" rel="Stylesheet" type="text/css" />
<link href="{{asset('css/select2.min.css')}}" rel="stylesheet" />

</head>
<body class="sidebar-mini layout-fixed text-sm sidebar-collapse">
<div class="wrapper">

  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
      <li class="nav-item">
          <select class="language-select form-control">
            <option value="en" {{ App::getLocale() == 'en' ? 'selected' : '' }}>English</option>
            <option value="gu" {{ App::getLocale() == 'gu' ? 'selected' : '' }}>ગુજરાતી</option>
          </select>
          {{-- <div class="language">
              <a href="{{ url('lang/en') }}" class="{{(App::getLocale() == 'en') ? 'slected_lanug' : ''}}">English</a>
              <a href="{{ url('lang/gu') }}" class="{{(App::getLocale() == 'gu') ? 'slected_lanug' : ''}}" >ગુજરાતી</a>
          </div> --}}
       </li>
      <li class="nav-item d-none d-sm-inline-block ml-auto">
        <a class="nav-link" style="cursor:pointer;" onclick="event.preventDefault();document.getElementById('logout-form').submit();">
            {{ __('message.logout') }}
        </a>

        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
            @csrf
        </form>
      </li>
    </ul>


  </nav>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <!-- <div class="image">
          <img src="dist/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image">
        </div> -->
        <div class="info">
        
           <p><a href="{{ route('dashboard') }}" class="d-block">{{ department_name(Auth::user()->dept_id)}}</a></p> 
        </div>
      </div>
      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column nav-legacy nav-compact nav-child-indent nav-collapse-hide-child nav-flat" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
                <li class="nav-item">
                    <a href="{{ route('dashboard')}}" class="nav-link {{ Route::currentRouteName() == 'dashboard' ? 'active' : '' }}"><i class="nav-icon fas fa-home"></i><p>{{ __('message.dashboard')}}</p></a>
                </li>
				        {{-- <li class="nav-item">
                    <a href="{{ route('test')}}" class="nav-link"><i class="nav-icon fas fa-home"></i><p>Dashboard 2</p></a>
                </li> --}}
                <li class="nav-item">
                  <a href="{{ route('profile')}}" class="nav-link {{ (request()->is('profile*')) ? 'active' : '' }}">
                    <i class="nav-icon fas fa-user"></i>
                    <p>
                     {{ __('message.profile')}}
                    </p>
                  </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('proposals', ['param' => 'new']) }}" class="nav-link {{ Route::currentRouteName() == 'proposals' && request()->param == 'new' ? 'active' : '' }}">
                        <i class="nav-icon fas fa-book"></i>
                        <p>  {{ __('message.new_proposals')}}</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('proposals', ['param' => 'forward']) }}" class="nav-link {{ Route::currentRouteName() == 'proposals' && request()->param == 'forward' ? 'active' : '' }}"><i class="nav-icon fas fa-book"></i><p>{{ __('message.forwarded_proposals')}}</p></a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('proposals', ['param' => 'return']) }}" class="nav-link {{ Route::currentRouteName() == 'proposals' && request()->param == 'return' ? 'active' : '' }}"><i class="nav-icon fas fa-book"></i><p>{{ __('message.proposal_returned_from_gad')}}</p></a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('proposals', ['param' => 'on_going']) }}" class="nav-link {{ Route::currentRouteName() == 'proposals' && request()->param == 'on_going' ? 'active' : '' }}"><i class="nav-icon fas fa-book"></i><p>{{ __('message.ongoing_evaluation_studies')}}</p></a>
                </li>
                <li class="nav-item">
                    <a href="{{  route('proposals', ['param' => 'completed']) }}" class="nav-link {{ Route::currentRouteName() == 'proposals' && request()->param == 'completed' ? 'active' : '' }}"><i class="nav-icon fas fa-book"></i><p>{{ __('message.completed_evaluation_studies')}}</p></a>
                </li>
                <li class="nav-item">
                    <a href="{{  route('department_hod.index') }}" class="nav-link {{ Route::currentRouteName() == 'department_hod.index' ? 'active' : '' }}"><i class="nav-icon fas fa-book"></i><p>{{ __('message.hod')}}</p></a>
                </li>
                
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper" >
  
  @yield('content')
  </div>
  <!-- /.content wrapper -->

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
    <div class="p-3">
      <h5>Title</h5>
      <p>Sidebar content</p>
    </div>
  </aside>
  <!-- /.control-sidebar -->

  <!-- Main Footer -->
  <footer class="main-footer">
    <!-- To the right -->
    {{-- <div class="float-right d-none d-sm-inline">
      Anything you want
    </div> --}}
    <!-- Default to the left -->
    <div class="footer">
       <strong>National Informatical Center (NIC) &copy; <?php echo date('Y'); ?>.</strong> All rights reserved.
    </div>
  </footer>
</div>
<!-- ./wrapper -->
{{-- <script src="{{asset('js/jquery.min.js')}}"></script> old file version 3.3.1--}}
<script src="{{asset('js/jquery.min.js')}}"></script>
<script type="text/javascript" src="{{asset('js/jquery.validate.min.js')}}"></script>
<script type="text/javascript" src="{{ asset('plugins/datatables-jquery/jquery.dataTables.min.js') }}"></script>
<script src="{{asset('plugins/datatables-jquery/dataTables.bootstrap4.min.js')}}"></script><!-- Bootstrap 4 -->
<script src="{{ asset('plugins/ijaboCropTool/ijaboCropTool.min.js') }}"></script>
<script src="{{ asset('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('plugins/datatables-responsive/js/dataTables.responsive.min.js')}}"></script>
<script src="{{ asset('plugins/datatables-responsive/js/responsive.bootstrap4.min.js')}}"></script>
<!-- AdminLTE App -->
<script src="{{ asset('dist/js/adminlte.min.js') }}"></script>
<script src="{{asset('js/validate_init.js')}}"></script>
<script src="{{asset('js/jquery.inputmask.min.js')}}"></script>
<script src="{{asset('js/jquery-ui.js')}}"></script>
<script src="{{asset('js/common.js')}}"></script>
<script src="{{asset('js/select2.min.js')}}"></script>
<!-- REQUIRED SCRIPTS -->
<script>
    $.ajaxSetup({
     headers:{
       'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
     }
  });

  $(document).ready(function() {
      $('.dataTable').DataTable();
      $('.content-wrapper').removeAttr('style');
      $('#welcome-user').modal('show');
  });

  $(document).ready(function () {
    $('.numberonly').keypress(function (e) {
      var charCode = (e.which) ? e.which : event.keyCode;
      if(String.fromCharCode(charCode).match(/[^0-9\-]/g))
        return false;
      });    
  });
  function check_fin_year(year) {
    if(/^[\d]{4}[\-][\d]{2}$/.test(year)) {
        $("#fin_year_span").text('');
        return true;
    } else {
        $("#fin_year_span").text('Please type Financial Year. e.g. 2020-21');
        $("#fin_year_id").focus();
        return false;
    }
  }
  $(document).ready(function(){
    var evaldone = $("#evaldoneinpast").val();
    if(evaldone == 'Yes') {
      $(".evaldoneinpast_ifyes").show();
    } else {
      $(".evaldoneinpast_ifyes").hide();
    }

     $('.language-select').on('change', function () {
        const selectedLang = $(this).val();

        $.ajax({
            url: '{{ route("lang.change") }}',
            method: 'POST',
            data: {
                locale: selectedLang,
                _token: '{{ csrf_token() }}'
            },
            success: function (response) {
                if (response.status === 'success') {
                    location.reload(); // reload current page with new language
                }
            }
        });
    });


  });
  $(document).ready(function(){
    $("#evaldoneinpast").change(function(){
      var evaldoneis = $(this).val();
      if(evaldoneis == 'Yes') {
        $(".evaldoneinpast_ifyes").show();
      } else {
        $(".evaldoneinpast_ifyes").hide();
      }
    });
  });

  $(document).ready(function(){
    $("#state_ratio, #central_ratio").keyup(function(e){
      var state_ratio = $("#state_ratio").val();
      var central_ratio = $("#central_ratio").val();
      var plus_ratio = Number(state_ratio) + Number(central_ratio);
      if(plus_ratio != 100) {
          $("#state_ratio").css('border-color','red');
          $("#central_ratio").css('border-color','red');
          $("#ratio_error").remove();
          $("#the_ratios").after("<p class='text-red' id='ratio_error'>State and central ratio must be 100 %</p>");
      } else {
          $("#state_ratio").css('border-color','#ced4da');
          $("#central_ratio").css('border-color','#ced4da');
          $("#ratio_error").remove();
      }
    });
  });

$(function(){

/* UPDATE ADMIN PERSONAL INFO */
$('.phoneNumber').inputmask("(999) 999-9999");
// $('#implementing_office_contact').inputmask("(999) 999-9999");
// $('#financial_adviser_phone').inputmask("(999) 999-9999");
$('#AdminInfoForm').on('submit', function(e){
    e.preventDefault();

    $.ajax({
       url:$(this).attr('action'),
       method:$(this).attr('method'),
       data:new FormData(this),
       processData:false,
       dataType:'json',
       contentType:false,
       beforeSend:function(){
           $(document).find('span.error-text').text('');
       },
       success:function(data){
            if(data.status == 0){
              $.each(data.error, function(prefix, val){
                $('span.'+prefix+'_error').text(val[0]);
              });
            }else{
              $('.admin_name').each(function(){
                 $(this).html( $('#AdminInfoForm').find( $('input[name="name"]') ).val() );
              });
              alert(data.msg);
            }
       }
    });
});

$(document).on('click','#change_picture_btn', function(){
  $('#admin_image').click();
});

$('#admin_image').ijaboCropTool({
      preview : '.admin_picture',
      setRatio:1,
      allowedExtensions: ['jpg', 'jpeg','png'],
      buttonsText:['CROP','QUIT'],
      buttonsColor:['#30bf7d','#ee5155', -15],
      processUrl:'{{ route("PictureUpdate") }}',
      withCSRF:['_token','{{ csrf_token() }}'],
      onSuccess:function(message, element, status){
         alert(message);
      },
      onError:function(message, element, status){
        alert(message);
      }
});


  $('#changePasswordAdminForm').on('submit', function(e){
      e.preventDefault();

      $.ajax({
          url:$(this).attr('action'),
          method:$(this).attr('method'),
          data:new FormData(this),
          processData:false,
          dataType:'json',
          contentType:false,
          beforeSend:function(){
            $(document).find('span.error-text').text('');
          },
          success:function(data){
            if(data.status == 0){
              $.each(data.error, function(prefix, val){
                $('span.'+prefix+'_error').text(val[0]);
              });
            }else{
              $('#changePasswordAdminForm')[0].reset();
              alert(data.msg);
            }
          }
      });
  });    
});


</script>

</body>
</html>
