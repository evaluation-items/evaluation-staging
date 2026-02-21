@extends('dashboards.admins.layouts.admin-dash-layout')
@section('title','Menu Items')
@section('content')
<div class="content  d-flex flex-column flex-column-fluid" >
      <!--begin::Subheader-->
      <div class="subheader py-2 py-lg-6  subheader-solid " id="kt_subheader">
        <div class=" container-fluid  d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
          <!--begin::Info-->
          <div class="d-flex align-items-center flex-wrap mr-1">
            <!--begin::Page Heading-->
            <div class="d-flex align-items-baseline flex-wrap mr-5">
              <!--begin::Page Title-->
              <h5 class="text-dark font-weight-bold my-1 mr-5">
               {{ __('message.menu_items')}}               	            
              </h5>
              <!--end::Page Title-->                  
            </div>
            <!--end::Page Heading-->
          </div>
          <!--end::Info-->
        </div>
      </div>
      <!--end::Subheader-->
      @if(session()->has('success'))
        <div class="alert alert-success" role="alert">
          {{  session()->get('success') }}
        </div>
        @endif
        @if(session()->has('error'))
            <div class="alert alert-custom alert-notice alert-light-danger fade show" role="alert">
              <div class="alert-icon"><i class="flaticon-warning"></i></div>
              <div class="alert-text"> {{ session()->get('error') }}</div>
              <div class="alert-close">
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                      <span aria-hidden="true"><i class="ki ki-close"></i></span>
                  </button>
              </div>
          </div>
        @endif
        @if ($errors->any())
          <div class="alert alert-danger">
              <ul>
                  @foreach ($errors->all() as $error)
                      <li>{{ $error }}</li>
                  @endforeach
              </ul>
          </div>
        @endif
      <!--begin::Entry-->

      <div class="d-flex flex-column-fluid">
        <!--begin::Container-->
          <div class=" container ">  
            <!--begin::Card-->
            <div class="card card-custom gutter-b">
              <div class="col-lg-9">
                @if(Session::has('email_err'))
                  <h4 style="color:red">{{ Session::get('email_err') }}</h4>
                @endif
              </div>
              <div class="card-header flex-wrap py-3">
                <div class="card-toolbar">
                  <a href={{route('menu-item.create')}} style="margin:10px" class="btn btn-primary"> {{ __('message.add')}} {{ __('message.menu')}}</a>
                </div>
              </div>
              <div class="row">
                <div class="card-body">
                  <div class="col-lg-12">
                      <table class="table table-bordered table-striped dataTable dtr-inline" id="example1">
                          <thead>
                              <tr>
                                <th> {{ __('message.no')}}</th>
                                <th>{{ __('message.menu')}} {{ __('message.name')}}</th>
                                <th>{{ __('message.slug')}}</th>
                                <th>{{ __('message.actions')}}</th>
                              </tr>
                            <tbody>
                              @php  $i= 1; @endphp
                              @if ($menu_items->count() > 0) 
                                @foreach ($menu_items as $menu_item)
                                <tr>
                                    <td class="text-center">{{ $i++ }}</td>
                                    <td class="text-center">{{$menu_item->name}}</td>
                                    <td class="text-center">{{$menu_item->slug}}</td>
                                    <td class="text-center">
                                      <a href="{{ route('menu-item.show',$menu_item->id) }}" class="btn btn-xs btn-info" style="display:inline-block">{{ __('message.view')}}</a>
                                      <a href="{{ route('menu-item.edit',$menu_item->id) }}" class="btn btn-xs btn-primary">{{ __('message.edit')}}</a>
                                      <a href="#myModal" class="btn btn-xs btn-danger trigger-btn" data-id= "{{$menu_item->id}}" data-bs-toggle="modal">{{ __('message.delete')}}</a>
                                    </td>
                                </tr>
                                @endforeach
                              @endif
                            </tbody>
                      </table>
                  </div>
                </div>
              </div>
            </div>
            <!--end::Container-->
          </div>
        <!--end::Entry-->
      </div>
</div>

<!-- Modal HTML -->
<div id="myModal" class="modal fade">
	<div class="modal-dialog modal-confirm">
		<div class="modal-content">
			<div class="modal-header flex-column">
				<div class="icon-box">
					<i class="material-icons">&#xE5CD;</i>
				</div>						
				<h4 class="modal-title w-100">{{ __('message.are_you_sure')}}</h4>	
                <button type="button" class="close" data-bs-dismiss="modal" aria-hidden="true">&times;</button>
			</div>
			<div class="modal-body">
				<p>{{ __('message.delete_msg')}}</p>
			</div>
			<div class="modal-footer justify-content-center">
				<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('message.cancel')}}</button>
				<button type="button" class="btn btn-danger deleteBtn" data-dept-id="">{{ __('message.delete')}}</button>
			</div>
		</div>
	</div>
</div> 
<script src="{{asset('js/jquery.min.js')}}"></script>
<script type="text/javascript">

  $(document).ready(function(){
    $('.trigger-btn').on('click', function () {
        var id = $(this).data('id');
        console.log(id);
        var deleteBtn = $('.deleteBtn');
        var deleteId = deleteBtn.data('dept-id');
        deleteBtn.data('dept-id', id);
    });
    $('.deleteBtn').on('click', function () {
      $('#myModal').modal('hide');
      const id = btoa($(this).data('dept-id'));
          if (id !== "") {
            const url = "{{ route('menu-item-destroy.destroy', ':id') }}".replace(':id', id);
              $.ajax({
                type: 'POST',
                dataType: 'json',
                url: url,
                headers: {
                  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                  success: function (response) {
                     alert(response.message);
                     location.reload();
                  },
                  error: function () {
                      console.log('Error: Unable to delete department.');
                  }
              });
          }
      });
    });
</script>
@endsection