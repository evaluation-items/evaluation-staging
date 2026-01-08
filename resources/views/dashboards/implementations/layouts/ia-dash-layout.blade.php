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
  <!-- Theme style -->
  <link rel="stylesheet" href="{{ asset('dist/css/adminlte.min.css') }}">
  <link rel="stylesheet" type="text/css" href="{{ asset('plugins/datatables-jquery/dataTables.min.css') }}">
  <link rel="stylesheet" type="text/css" href="{{ asset('css/style.css') }}">
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
    <!-- Brand Logo -->
    <!-- <a href="{{ route('implementation.dashboard') }}" class="brand-link">
      <img src="eval/public/dist/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
      <span class="brand-text font-weight-light">IA Nodal Officer</span>
    </a>
 -->
    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <!-- <div class="image">
          <img src="dist/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image">
        </div> -->
        <div class="info">
          @php
            $dept_id = Auth::user()->dept_id;
            $dept_name =  department_name($dept_id);
           // $dept_name = DB::table('imaster.departments')->where('dept_id',$dept_id)->value('dept_name');
          @endphp
          <p><a href="{{ route('implementation.proposals') }}" class="d-block">{{ $dept_name }}</a></p>
        </div>
      </div>
      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column nav-legacy nav-compact nav-child-indent nav-collapse-hide-child nav-flat" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
		   <li class="nav-item">
			 <a href="{{ route('implementation.proposals')}}" class="nav-link test-add-data"><i class="nav-icon fas fa-home"></i><p>Dashboard</p></a>
		  </li>
		  <li class="nav-item">
			<a href="{{ route('schemes.index')}}" class="nav-link"><i class="nav-icon fas fa-book"></i><p>Report Module</p></a>
		  </li>
		  {{-- <li class="nav-item">
			<a href="{{ route('schemes.proposals')}}" class="nav-link"><i class="nav-icon fas fa-book"></i>
			  <p>Proposals</p>
			</a>
		  </li> --}}
      <li class="nav-item">
        <a href="{{ route('implementation.proposals')}}" class="nav-link"><i class="nav-icon fas fa-book"></i>
          <p>Proposals</p>
        </a>
      </li>
		  {{-- <li class="nav-item">
			<a href="{{ route('schemes.meetings')}}" class="nav-link"><i class="nav-icon fas fa-user"></i>
			  <p>Meetings</p>
			</a>
		  </li> --}}
		  {{-- <li class="nav-item">
			<a href="{{ route('schemes.communication')}}" class="nav-link"><i class="nav-icon fas fa-handshake"></i>
			  <p>Communication</p>
			</a>
		  </li> --}}
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
  @yield('content')
  </div>
  <!-- /.content-wrapper -->

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
<script src="{{asset('js/jquery.min.js')}}"></script>
<script type="text/javascript" src="{{asset('js/jquery.validate.min.js')}}"></script>
<script type="text/javascript" src="{{ asset('plugins/datatables-jquery/jquery.dataTables.min.js') }}"></script>
<!-- Bootstrap 4 -->
<script src="{{ asset('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<!-- AdminLTE App -->
<script src="{{ asset('dist/js/adminlte.min.js') }}"></script>
<script src="{{asset('js/validate_init.js')}}"></script>
<script src="{{asset('js/common.js')}}"></script>
<!-- REQUIRED SCRIPTS -->
<script>
  $(document).ready(function() {
      $('.show-datatable').DataTable();
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

    // $("#forwardScheme").validate({
    //     rules: {
    //       remarks: {
    //         required: true,
    //       },
    //       action: "required"
    //     },
    //     messages: {
    //       remarks: {
    //         required: "Please enter remarks data",
          
    //       },
    //       action: "Please provide some data"
    //     }
    //   });
  });

</script>

</body>
</html>
