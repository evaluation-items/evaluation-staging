@extends('dashboards.admins.layouts.admin-dash-layout')
@section('title','Beneficiaries')
@section('content')
<style>/* Base Button Styling */
.btn-toggle {
  top: 10px;
  margin: .5rem;
  padding: 0;
  position: relative;
  border: none;
  height: 2rem;
  line-height: 2rem;
  border-radius: 1.5rem;
  width: 5rem;
  outline: none;
  background: #7C8288 !important;
  cursor: pointer;
  transition: background-color 0.25s;
}

/* Common styles for Yes/No text */
.btn-toggle:before,
.btn-toggle:after {
  font-weight: 600;
  font-size: 0.8rem;
  text-transform: uppercase;
  position: absolute;
  top: 0;
  transition: opacity 0.25s;
  color: #fff;
  letter-spacing: 0.75px;
  width: 100%;
  display: block;
}

/* "No" Text positioning */
.btn-toggle:before {
  content: 'No';
  text-align: right;
  padding-right: 0.6rem;
  opacity: 1;
}

/* "Yes" Text positioning */
.btn-toggle:after {
  content: 'Yes';
  text-align: left;
  padding-left: 0.6rem;
  opacity: 0;
}

/* The White Circle (Handle) */
.btn-toggle .handle {
  position: absolute;
  top: 0.1875rem;
  left: 0.1875rem;
  width: 1.625rem;
  height: 1.625rem;
  border-radius: 50%;
  background: #fff !important;
  transition: left 0.25s;
  z-index: 2;
}

/* ACTIVE STATE (When "Yes" is selected) */
.btn-toggle.active {
  background-color: #1565C1 !important;
}

.btn-toggle.active .handle {
  left: 3.2rem;
}

.btn-toggle.active:before {
  opacity: 0;
}

.btn-toggle.active:after {
  opacity: 1;
}

</style>
        <div class="content  d-flex flex-column flex-column-fluid" id="kt_content">
            <!--begin::Subheader-->
            <div class="subheader py-2 py-lg-6  subheader-solid " id="kt_subheader">
              <div class=" container-fluid  d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
                <!--begin::Info-->
                <div class="d-flex align-items-center flex-wrap mr-1">
                  <!--begin::Page Heading-->
                  <div class="d-flex align-items-baseline flex-wrap mr-5">
                    <!--begin::Page Title-->
                    <h5 class="text-dark font-weight-bold my-1 mr-5">
                      {{ __('message.beneficiaries')}} {{ __('message.list')}}                	            
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
                      <div class="row">
                        <div class="card-body">
                      <div class="col-lg-12">
                        <div class="row">
                          <div class="col-lg-3">
                            <button type="buton" id="addBeneficiaries" style="margin:10px" class="btn btn-primary">{{ __('message.add')}} {{ __('message.beneficiaries')}}</button>
                          </div>
                          <div class="col-lg-9">
                            @if(Session::has('email_err'))
                              <h4 style="color:red">{{ Session::get('email_err') }}</h4>
                            @endif
                          </div>
                        </div>
                        <table class="table table-bordered table-striped dataTable dtr-inline" id="example1" style="text-align: center;">
                          <thead>
                              <tr>
                                  <th width="5%">{{ __('message.no')}}</th>
                                  <th width="10%">{{ __('message.name')}}</th>
                                  <th width="10%">{{ __('message.status')}}</th>
                                  <th width="10%">{{ __('message.actions')}}</th>
                              </tr>
                          </thead>
                          <tbody>
                            @php
                              $i = 1;
                            @endphp
                              @if(isset($beneficiarie_item) && $beneficiarie_item->count() > 0) 
                                @foreach ($beneficiarie_item as $beneficiaries_item)
                                      <tr>
                                          <td>{{ $i++ }}</td>
                                          <td>{{ $beneficiaries_item->name }}</td> 
                                          <td class="text-center">
                                          <button type="button" data-id="{{ $beneficiaries_item->id }}" class="btn  btn-sm btn-secondary btn-toggle {{ $beneficiaries_item->status == 1 ? 'active' : '' }}" data-toggle="button" aria-pressed="{{ $beneficiaries_item->status == 1 ? 'true' : 'false' }}" autocomplete="off">
                                            <div class="handle"></div>
                                          </button>

                                        </td>
                                          <td>
                                              <a href="{{ route('beneficiaries.show',$beneficiaries_item->id) }}" class="btn btn-xs btn-info" style="display:inline-block">{{ __('message.view')}}</a>
                                              <!-- <a href="{{ route('beneficiaries.edit',$beneficiaries_item->id) }}" class="btn btn-xs btn-primary editBeneficiaries">Edit</a> -->
                                              <a  class="btn btn-xs btn-primary editBeneficiaries" data-id="{{ $beneficiaries_item->id }}" data-name="{{ $beneficiaries_item->name }}">{{ __('message.edit')}}</a>

                                              <a href="#myModal" class="btn btn-xs btn-danger trigger-btn" data-id= "{{$beneficiaries_item->id}}"data-bs-toggle="modal">{{ __('message.delete')}}</a> 
              
                                              <form id="delete-form-{{$beneficiaries_item->id}}" action="{{ route('beneficiaries.destroy',$beneficiaries_item->id) }}" method="POST" style="display: none;">
                                                  @csrf
                                                  @method('DELETE')
                                              </form> 
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
  <div class="modal fade" id="add_beneficiaries" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">{{ __('message.add')}} {{ __('message.beneficiaries')}}</h4>
          <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="container">
          <form  method="POST" id="beneficiariesFrm" class="beneficiariesItem" enctype="multipart/form-data" autocomplete="off">
            @csrf
          
              <div class="row">
                  <label for="name">{{ __('message.beneficiaries')}} {{ __('message.name')}} <span class="required_filed"> * </span> : </label>
                  <input type="text" name="name" id="name" value=""  class="form-control pattern" autocomplete="off">
              </div>
              <div class="row">
                  <div class="col-xl-12" style="text-align: right;margin-top:10px;">
                      <button type="button" class="btn btn-primary beneficiariesFrm">{{ __('message.submit')}}</button>
                      <button type="button" class="btn btn-default" data-bs-dismiss="modal">{{ __('message.close')}}</button>
                  </div>
              </div>
            </form>
          </div>
        </div>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>

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
				<button type="button" class="btn btn-danger deleteBtn" data-beneficiaries-id="">{{ __('message.delete')}}</button>
			</div>
		</div>
	</div>
</div> 

{{-- <script src="{{asset('js/3.2.1.jquery.min.js')}}"></script> --}}
<script src="{{asset('js/jquery.min.js')}}"></script>
<script type="text/javascript">

  $(document).ready(function(){

    $('.trigger-btn').on('click', function () {
        var district_id = $(this).data('id');
        
        var deleteBtn = $('.deleteBtn');
        var deleteId = deleteBtn.data('beneficiaries-id');
            deleteBtn.data('beneficiaries-id',district_id);
        
    });
    $('.deleteBtn').on('click', function () {
      const id = $(this).data('beneficiaries-id');
      $('#delete-form-'+id).get(0).submit();
      $('#myModal').modal('hide');
      });
    $('#addBeneficiaries').on('click',function(){
         $('#add_beneficiaries').modal('show');
         var fromAction = "{{ route('beneficiaries.store') }}";
         $('.beneficiariesItem').attr('action', fromAction);

    });
    $('.editBeneficiaries').on('click',function(){
      var unitId = $(this).data('id');
      var unitName = $(this).data('name');

      $('#beneficiaries_id').val(unitId);
      $('#name').val(unitName);
      var formAction = "{{ route('beneficiaries.update', ['beneficiary' => 'beneficiaries_id']) }}".replace('beneficiaries_id', unitId);
        $('.beneficiariesItem').attr('action', formAction);
        $('.beneficiariesItem').attr('method', 'PUT');

      $('#add_beneficiaries').modal('show');
    });
    $('.beneficiariesFrm').on('click', function(e) {
      var name = $('#name').val();
     
      if(name != ""){
        e.preventDefault();
        var form = $('#beneficiariesFrm');
        var url = form.attr('action');
        var formData = form.serialize();
        var method = form.attr('method');
        $.ajax({
            type: method,
            url: url,
            data: formData,
            success: function(response) {
               if(response.success){
                  alert(response.success);
               }
               location.reload();
            },
            error: function(xhr, status, error) {
                // Handle error response
                console.error(xhr, status, error);
            }
        });
        $('#add_beneficiaries').modal('hide');

      }else{
        alert('Please enter name');
      }
    });
  });
  document.querySelectorAll('.btn-toggle').forEach(ele => {
    ele.addEventListener('click', function(e) {
        // 1. Prevent default behavior immediately
        e.preventDefault();

        // 2. Toggle the active class for visual feedback
        const isActive = this.classList.toggle('active');
        
        // 3. Get ID using vanilla JS (since you used querySelectorAll)
        // Ensure your HTML has data-id="123"
        const rawId = this.getAttribute('data-id');
        
        if (rawId) {
            const id = btoa(rawId);
            const url = "{{ route('beneficiaries.status', ':id') }}".replace(':id', id);

            $.ajax({
                type: 'POST',
                dataType: 'json',
                url: url,
                data: {
                    status: isActive ? 1 : 0,
                    id: id
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (response) {
                    // Using a console log is smoother than alert if reloading
                    console.log(response.message);
                    location.reload();
                },
                error: function (jqXHR, exception) {
                    let msg = '';
                    if (jqXHR.status === 0) msg = 'Not connect.\n Verify Network.';
                    else if (jqXHR.status == 404) msg = 'Requested page not found. [404]';
                    else if (jqXHR.status == 500) msg = 'Internal Server Error [500].';
                    else if (exception === 'timeout') msg = 'Time out error.';
                    else msg = 'Error: ' + jqXHR.responseText;
                    
                    alert(msg);
                    // Revert toggle if error occurs
                    ele.classList.toggle('active'); 
                },
            });
        }
    });
});
</script>
@endsection