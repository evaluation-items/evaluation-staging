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
  <link rel="stylesheet" href="{{ asset('plugins/fontawesome-free/css/all.min.css')}}">
   <link rel="stylesheet" href="{{ asset('plugins/ijaboCropTool/ijaboCropTool.min.css') }}">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{ asset('dist/css/adminlte.min.css')}}">
  <link rel="stylesheet" type="text/css" href="{{ asset('plugins/datatables-jquery/dataTables.min.css')}}">
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
      <li class="nav-item dropdown">
        <a class="nav-link"data-bs-toggle="dropdown" href="#" aria-expanded="true">
          <i class="far fa-bell"></i>
          <span class="badge badge-warning navbar-badge">15</span>
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-left" style="left: inherit; right: 0px;">
          <span class="dropdown-item dropdown-header">15 Notifications</span>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item">
            <i class="fas fa-envelope mr-2"></i> @if(Session::has('proposals')) {{ Session::get('proposals') }} @else 0 @endif Proposals
            <span class="float-right text-muted text-sm"> on {{ Session::get('last_proposal_time') }}</span>
          </a>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item">
            <i class="fas fa-users mr-2"></i> 8 friend requests
            <span class="float-right text-muted text-sm">12 hours</span>
          </a>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item">
            <i class="fas fa-file mr-2"></i> 3 new reports
            <span class="float-right text-muted text-sm">2 days</span>
          </a>
<!--           <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item dropdown-footer">See All Notifications</a> -->
        </div>
      </li>
    </ul>


  </nav>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <!-- <a href="{{ \URL::to('/')}}" class="brand-link">
      <img src="dist/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
      <span class="brand-text font-weight-light">Department Sec</span>
    </a> -->

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <!-- <div class="image">
          <img src="{{ Auth::user()->picture }}" class="img-circle elevation-2 admin_picture" alt="User Image">
        </div> -->
        <div class="info">
          @php
            $dept_id = Auth::user()->dept_id;
            $dept_name = DB::table('imaster.departments')->where('dept_id',$dept_id)->value('dept_name');
          @endphp
          <p><a href="#" class="d-block admin_name">{{ $dept_name }}</a></p>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column nav-legacy nav-compact nav-child-indent nav-collapse-hide-child nav-flat" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
               <li class="nav-item">
                <a href="{{route('deptsec.dashboard') }}" class="nav-link"><i class="nav-icon fas fa-home"></i><p>Dashboard</p></a>
              </li>
              <li class="nav-item">
                <a href="{{route('deptsec.schemes') }}" class="nav-link "><i class="nav-icon fas fa-book"></i><p>Schemes</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{route('deptsec.proposal')}}" class="nav-link "><i class="nav-icon fas fa-book"></i><p>Proposals</p><span style="margin-left:10px;font-size:14px" class="badge badge-primary"> {{ Session::get('proposals') }} </span>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{route('deptsec.meetings')}}" class="nav-link "><i class="nav-icon fas fa-user"></i><p>Meetings</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{route('deptsec.communication')}}" class="nav-link "><i class="nav-icon fas fa-handshake"></i><p>Communication</p>
                </a>
              </li>
			  <!--
              <li class="nav-item">
                <a href="{{-- route('deptsec.listconveners') --}}" class="nav-link "><i class="nav-icon fas fa-book"></i><p>Convener's List</p></a>
              </li>
              <li class="nav-item">
                <a href="{{-- route('deptsec.nodallist') --}}" class="nav-link "><i class="nav-icon fas fa-book"></i><p>Nodal's List</p></a>
              </li>
              <li class="nav-item">
                <a href="{{-- route('deptsec.listadvisers') --}}" class="nav-link "><i class="nav-icon fas fa-book"></i><p>Adviser's List</p></a>
              </li>
			  -->
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

<!-- REQUIRED SCRIPTS -->

<!-- jQuery -->
<script src="{{ asset('plugins/jquery/jquery.min.js')}}"></script>
<!-- Bootstrap 4 -->
<script src="{{ asset('plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<script src="{{ asset('plugins/ijaboCropTool/ijaboCropTool.min.js') }}"></script>
<!-- AdminLTE App -->
<script src="{{ asset('dist/js/adminlte.min.js')}}"></script>
<script type="text/javascript" src="{{ asset('plugins/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('js/common.js')}}"></script>

<script>
  $.ajaxSetup({
     headers:{
       'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
     }
  });
  
  $(function(){

    /* UPDATE ADMIN PERSONAL INFO */

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
          processUrl:'{{ route("adminPictureUpdate") }}',
          // withCSRF:['_token','{{ csrf_token() }}'],
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

  $(document).ready(function() {
      $('.show-datatable').DataTable();
  });

  $(document).ready(function(){
    var ktcontent = $("#kt_content").height();
    $(".content-wrapper").css('min-height',ktcontent);
  });

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
          $("#the_ratios").after("<p class='text-red' id='ratio_error'>Sum of Center Govt. and State Govt. must be 100 percent</p>");
      } else {
          $("#state_ratio").css('border-color','#ced4da');
          $("#central_ratio").css('border-color','#ced4da');
          $("#ratio_error").remove();
      }
    });
  });


</script>
</body>
</html>
