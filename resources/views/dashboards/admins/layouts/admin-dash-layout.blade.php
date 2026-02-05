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
{{-- 
  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="{{asset('css/font-family.css')}}">
  <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="{{ asset('plugins/fontawesome-free/css/all.min.css')}}">
   <link rel="stylesheet" href="{{ asset('plugins/ijaboCropTool/ijaboCropTool.min.css') }}">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{asset('css/bootstrap.min.css')}}">

  <link rel="stylesheet" href="{{ asset('dist/css/adminlte.min.css')}}">
  <link rel="stylesheet" type="text/css" href="{{ asset('plugins/datatables-jquery/dataTables.min.css')}}">
  <link rel="stylesheet" type="text/css" href="{{ asset('css/style.css') }}"> --}}

  <link rel="stylesheet" href="{{asset('css/font-family.css')}}">
  <link rel="stylesheet" href="{{ asset('plugins/fontawesome-free/css/all.min.css') }}">
    {{-- <link rel="stylesheet" href="{{ asset('plugins/datatables-jquery/css/dataTables.bootstrap4.min.css') }}"> --}}
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
          <select class="language-select form-control">
            <option value="en" {{ App::getLocale() == 'en' ? 'selected' : '' }}>English</option>
            <option value="gu" {{ App::getLocale() == 'gu' ? 'selected' : '' }}>ગુજરાતી</option>
          </select>
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
    {{-- <a href="{{ \URL::to('/')}}" class="brand-link">
      <img src="{{ asset('dist/img/AdminLTELogo.png')}}" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
      <span class="brand-text font-weight-light">Your Site</span>
    </a> --}}

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
           <img src="{{ Auth::user()->picture }}" class="img-circle elevation-2 admin_picture" alt="User Image"> 
        </div>
        <div class="info">
          <h2><a href="{{ route('admin.dashboard')}}" class="d-block admin_name">{{ Auth::user()->name }}</a></h2>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column nav-legacy nav-compact nav-child-indent nav-collapse-hide-child nav-flat" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
                <li class="nav-item">
                <a href="{{ route('admin.dashboard')}}" class="nav-link {{ (request()->is('admin/dashboard*')) ? 'active' : '' }}">
                  <i class="nav-icon fas fa-home"></i>
                  <p>
                    {{ __('message.dashboard')}}
                  </p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('admin.profile')}}" class="nav-link {{ (request()->is('admin/profile*')) ? 'active' : '' }}">
                  <i class="nav-icon fas fa-user"></i>
                  <p>
                   {{ __('message.profile')}}
                  </p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('admin.proposal', ['param' => 'new']) }}" class="nav-link {{ Route::currentRouteName() == 'admin.proposal' && request()->param == 'new' ? 'active' : '' }}">
                    <i class="nav-icon fas fa-book"></i>
                    <p>{{ __('message.new_proposals')}}</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('admin.roles')}}" class="nav-link {{ (request()->is('admin/roles*')) ? 'active' : '' }}">
                  <i class="nav-icon fas fa-user"></i>
                  <p>{{ __('message.designation')}}</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('departments.index')}}" class="nav-link {{ (request()->is('departments*')) ? 'active' : '' }}">
                  <i class="nav-icon fas fa-copy"></i>
                  <p>{{ __('message.deparment')}}</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('subdepartments.index')}}" class="nav-link {{ (request()->is('subdepartments*')) ? 'active' : '' }}">
                  <i class="nav-icon fas fa-copy"></i>
                  <p>{{ __('message.sub_deparment')}}</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('admin.user_list')}}" class="nav-link {{ (request()->is('admin/user_list*')) ? 'active' : '' }}">
                  <i class="nav-icon fas fa-user"></i>
                  <p>{{ __('message.user_list')}}</p>
                </a>
              </li>
              {{-- <li class="nav-item">
                <a href="{{ route('evaluation_user.index')}}" class="nav-link {{ (request()->is('admin/evaluation_user*')) ? 'active' : '' }}">
                  <i class="nav-icon fas fa-user"></i>
                  <p>Evaluation User List</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('branch.index')}}" class="nav-link {{ (request()->is('admin/branch*')) ? 'active' : '' }}">
                  <i class="nav-icon fas fa-user"></i>
                  <p>Branch</p>
                </a>
              </li> --}}
              <li class="nav-item">
                <a href="javasc" class="nav-link">
                    <i class="nav-icon far fa-plus-square"></i>
                    <p>{{ __('message.evaluation_user_list')}} <i class="fas fa-angle-left right"></i></p>
                </a>
                <ul class="nav nav-treeview" style="display: none;">
                    <li class="nav-item">
                        <a href="{{ route('evaluation_user.index')}}" class="nav-link {{ (request()->is('admin/evaluation_user*')) ? 'active' : '' }}">
                          <i class="nav-icon fas fa-user"></i><p>{{ __('message.evaluation_users')}}</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('branch.index')}}" class="nav-link {{ (request()->is('admin/branch*')) ? 'active' : '' }}">
                          <i class="far fa-circle nav-icon"></i><p>{{ __('message.branchess')}}</p>
                        </a>
                       
                    </li>
                </ul>
            </li>
              {{-- <li class="nav-item">
                <a href="{{ route('admin.odk_user_list')}}" class="nav-link {{ (request()->is('admin/odk_user_list*')) ? 'active' : '' }}">
                  <i class="nav-icon fas fa-user"></i>
                  <p>ODK User List</p>
                </a>
              </li> --}}
              {{-- <li class="nav-item">
                <a href="{{route('import-view')}}" class="nav-link">
                  <i class="nav-icon fas fa-user"></i>
                  <p>Upload Data</p>
                </a>
              </li> --}}
              <li class="nav-item">
                <a href="{{route('state.index')}}" class="nav-link {{ Route::currentRouteName() == 'state.index' ? 'active' : '' }}">
                  <i class="nav-icon fas fa-user"></i>
                  <p>{{ __('message.manage_state')}}</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{route('district.index')}}" class="nav-link {{ Route::currentRouteName() == 'district.index' ? 'active' : '' }}">
                  <i class="nav-icon fas fa-user"></i>
                  <p>{{ __('message.manage_district')}}</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{route('taluka.index')}}" class="nav-link {{ Route::currentRouteName() == 'taluka.index' ? 'active' : '' }}">
                  <i class="nav-icon fas fa-user"></i>
                  <p>{{ __('message.manage_taluka')}}</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{route('village.index')}}" class="nav-link {{ Route::currentRouteName() == 'village.index' ? 'active' : '' }}">
                  <i class="nav-icon fas fa-user"></i>
                  <p>{{ __('message.manage_village')}}</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{route('units.index')}}" class="nav-link {{ Route::currentRouteName() == 'units.index' ? 'active' : '' }}">
                  <i class="nav-icon fas fa-user"></i>
                  <p>{{ __('message.units_of_physical')}}</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{route('nodal-designations.index')}}" class="nav-link {{ Route::currentRouteName() == 'nodal-designations.index' ? 'active' : '' }}">
                  <i class="nav-icon fas fa-user"></i>
                  <p>{{ __('message.nodal_designation')}}</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{route('beneficiaries.index')}}" class="nav-link {{ Route::currentRouteName() == 'beneficiaries.index' ? 'active' : '' }}">
                  <i class="nav-icon fas fa-user"></i>
                  <p>{{ __('message.selection_of_field_area')}}</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{route('admin.advertisement_list')}}" class="nav-link {{ Route::currentRouteName() == 'admin.advertisement_list' ? 'active' : '' }}">
                  <i class="nav-icon fas fa-ad"></i>
                  <p>{{ __('message.advertiesment')}}</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{route('digital_project_libraries.index')}}" class="nav-link {{ Route::currentRouteName() == 'digital_project_libraries.index' ? 'active' : '' }}">
                  <i class="nav-icon fas fa-project-diagram"></i>
                  <p> {{ __('message.evaluation_report_library')}}</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{  route('department_hod.index') }}" class="nav-link {{ Route::currentRouteName() == 'department_hod.index' ? 'active' : '' }}"><i class="nav-icon fas fa-book"></i><p> {{ __('message.hod')}} </p></a>
              </li>
              <li class="nav-item">
                <a href="{{  route('menu-item.index') }}" class="nav-link"><i class="nav-icon fas fa-book"></i><p> {{ __('message.menu_items')}} </p></a>
              </li>
              {{-- <li class="nav-item">
                <a href="{{ route('publication.index')}}" class="nav-link {{ (request()->is('admin/publication*')) ? 'active' : '' }}">
                  <i class="nav-icon fas fa-user"></i>
                  <p>Publications Branch</p>
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
</div>
<!-- ./wrapper -->

<!-- REQUIRED SCRIPTS -->

<script src="{{asset('js/jquery.min.js')}}"></script>
<script type="text/javascript" src="{{asset('js/jquery.validate.min.js')}}"></script>
<script type="text/javascript" src="{{ asset('plugins/datatables-jquery/jquery.dataTables.min.js') }}"></script>
<script src="{{asset('plugins/datatables-jquery/dataTables.bootstrap4.min.js')}}"></script>
<script src="{{ asset('dist/js/adminlte.min.js')}}"></script>
<script src="{{ asset('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('plugins/ijaboCropTool/ijaboCropTool.min.js') }}"></script>
<script src="{{asset('js/popper.min.js')}}"></script>
<script src="{{asset('js/bootstrap.min.js')}}"></script>
<script src="{{asset('js/validate_init.js')}}"></script>
<script src="{{asset('js/select2.min.js')}}"></script>
<script src="{{asset('js/jquery.inputmask.min.js')}}"></script>
<script src="{{asset('js/common.js')}}"></script>
{{-- CUSTOM JS CODES --}}
<script>
  $(document).ready(function() {
    $("#load_gif_img").hide();
    $('.select2').select2();
});
  $(document).ready(function() {
      $('.dataTable').DataTable();
  });
  $(document).ready(function(){
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
  $.ajaxSetup({
     headers:{
       'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
     }
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
           beforeSend:function() {
                $("#load_gif_img").show();
          },
           success:function(data){
                if(data.status == 0){
                  $.each(data.error, function(prefix, val){
                    $('span.'+prefix+'_error').text(val[0]);
                  });
                }else{
                  $("#load_gif_img").hide();
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
            beforeSend:function() {
                $("#load_gif_img").html('<img id="beneficiariesGeoLocal_img" src="eval/public/loading.gif" style="max-width:200px;max-height:200px">');
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
