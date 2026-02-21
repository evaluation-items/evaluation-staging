<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('title')</title>
  <base href="{{ \URL::to('/') }}">
  <link rel="stylesheet" href="{{asset('css/font-family.css')}}">
  <link rel="stylesheet" href="{{ asset('plugins/fontawesome-free/css/all.min.css') }}">
   <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
  <link rel="stylesheet" href="{{ asset('plugins/ijaboCropTool/ijaboCropTool.min.css') }}">
  <link rel="stylesteet" href="{{asset('plugins/chart.js/Chart.min.css')}}">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{ asset('dist/css/adminlte.min.css') }}">
  <link rel="stylesheet" href="{{asset('css/jquery-ui.css')}}">
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
      <li class="nav-item">
        <img src="{{ asset('img/emblem.png') }}" alt="Government Emblem" style="height: 56px;">
        <div class="logo-text" style="left: 14%;position: relative;margin-top: -19%;"><h1 class="mb-1 fw-bold text-dark" style="font-weight:bold; font-size:1.25rem;">{{ __('message.director_of_evaluation') }}</h1> Directorate of Evaluation  </div>
      </li>
        <li class="nav-item d-none d-sm-inline-block ml-auto">
          
          <a class="nav-link" style="cursor:pointer;" onclick="event.preventDefault();document.getElementById('logout-form').submit();">
            {{ __('message.logout') }} 
            <i class="nav-icon fas fa-power-off text-danger"></i>
          </a>
          <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
              @csrf
          </form>
      </li>
    </ul>
    <ul class="navbar-nav ml-auto">
    <li class="nav-item d-none d-sm-inline-block">
      <select class="language-select form-control">
        <option value="en" {{ App::getLocale() == 'en' ? 'selected' : '' }}>English</option>
        <option value="gu" {{ App::getLocale() == 'gu' ? 'selected' : '' }}>ગુજરાતી</option>
      </select>
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
        <div class="info">
          <p><a href="{{ route('evaldd.dashboard') }}" class="d-block">Evaluation Deputy Director</a></p>
        </div>
      </div>
      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column nav-legacy nav-compact nav-child-indent nav-collapse-hide-child nav-flat" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
                <li class="nav-item">
                    <a href="{{ route('evaldd.dashboard') }}" class="nav-link {{ Route::currentRouteName() == 'evaldd.proposal' && request()->param == 'on_going' ? 'active' : '' }}"><i class="nav-icon fas fa-home"></i><p>{{ __('message.dashboard')}}</p></a>
                </li>
                <li class="nav-item">
                  <a href="{{ route('evaldd.profile')}}" class="nav-link {{ Route::currentRouteName() == 'evaldd.profile' ? 'active' : '' }}">
                    <i class="nav-icon fas fa-user"></i>
                    <p>
                      {{ __('message.profile')}}
                    </p>
                  </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('evaldd.proposal', ['param' => 'new']) }}" class="nav-link {{ Route::currentRouteName() == 'evaldd.proposal' && request()->param == 'new' ? 'active' : '' }}">
                        <i class="nav-icon fas fa-align-justify"></i>
                        <p>{{ __('message.new_study_work')}}</p>
                    </a>
                </li>
                {{-- <li class="nav-item">
                    <a href="{{ route('evaldd.proposal', ['param' => 'forward']) }}" class="nav-link"><i class="nav-icon fas fa-book"></i><p>Forwarded Proposals</p></a>
                </li> --}}
                
                <li class="nav-item">
                    <a href="{{ route('evaldd.proposal', ['param' => 'on_going']) }}" class="nav-link {{ Route::currentRouteName() == 'evaldd.proposal' && request()->param == 'on_going' ? 'active' : '' }}"><i class="nav-icon fas fa-tasks"></i><p>{{ __('message.ongoing_proposals')}}</p></a>
                </li>

                <li class="nav-item">
                    <a href="{{  route('evaldd.proposal', ['param' => 'completed']) }}" class="nav-link {{ Route::currentRouteName() == 'evaldd.proposal' && request()->param == 'completed' ? 'active' : '' }}"><i class="nav-icon fas fa-book"></i><p>{{ __('message.completed_evaluation_studies')}}</p></a>
                </li>
                @if(Auth::check() && Auth::user()->role_manage == 4 && Auth::user()->role == 38)
                <li class="nav-item">
                  <a href="{{  route('evaldd.proposal', ['param' => 'tranfered']) }}" class="nav-link {{ Route::currentRouteName() == 'evaldd.proposal' && request()->param == 'tranfered' ? 'active' : '' }}"><i class="nav-icon fas fa-book"></i><p>{{ __('message.transfred_proposals')}}</p></a>
                </li>
                @endif
                {{-- @if(Auth::check() && Auth::user()->role_manage == 3) --}}
                <li class="nav-item">
                  <a href="{{  route('detail_report') }}" class="nav-link {{ Route::currentRouteName() == 'detail_report'  ? 'active' : '' }}"><i class="nav-icon fas fa-book"></i><p>{{ __('message.detail_reports')}}</p></a>
                </li> 
                <li class="nav-item">
                  <a href="{{  route('project_list.index') }}" class="nav-link {{ Route::currentRouteName() == 'project_list.index'  ? 'active' : '' }}"><i class="nav-icon fas fa-file-pdf"></i><p>{{ __('message.evaluation_reports')}}</p></a>
                </li> 
               
               {{-- @endif --}}
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
<script src="{{asset('plugins/jquery/jquery.min.js')}}"></script>
<script type="text/javascript" src="{{asset('js/jquery.validate.min.js')}}"></script>
<script type="text/javascript" src="{{ asset('plugins/datatables-jquery/jquery.dataTables.min.js') }}"></script>
<script src="{{asset('plugins/datatables-jquery/dataTables.bootstrap4.min.js')}}"></script>
<script src="{{ asset('dist/js/adminlte.min.js')}}"></script>
<script src="{{ asset('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('plugins/ijaboCropTool/ijaboCropTool.min.js') }}"></script>
<script src="{{asset('js/popper.min.js')}}"></script>
<script src="{{asset('js/bootstrap.min.js')}}"></script>
<script src="{{asset('js/validate_init.js')}}"></script>
<script src="{{asset('js/jquery.inputmask.min.js')}}"></script>
<script src="{{asset('js/common.js')}}"></script>
<script>
  $.ajaxSetup({
     headers:{
       'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
     }
  });

  $(document).ready(function(){
    var winheight = $(document).height();
    $(".content-wrapper").css('min-height',winheight);

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

  $(function(){

    /* UPDATE ADMIN PERSONAL INFO */
     $('#phoneNumber').inputmask({
		mask: "(999) 999-9999",
		placeholder: "_",
		showMaskOnHover: false,
		showMaskOnFocus: true,
		clearIncomplete: true  // <- automatically clears incomplete input
	});
	
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
          processUrl:'{{ route("evaldd.PictureUpdate") }}',
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
      $('.dataTable').DataTable();
  });
</script>
</body>
</html>
