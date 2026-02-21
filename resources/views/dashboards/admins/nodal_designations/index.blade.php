@extends('dashboards.admins.layouts.admin-dash-layout')
@section('title','Nodal Designation Of Physical')
@section('content')

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
                      Nodal Designation List               	            
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
                            <button type="buton" id="addNodalDesignations" style="margin:10px" class="btn btn-primary">{{ __('message.add')}} Nodal Designation</button>
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
                                  <th width="5%">{{ __('message.dept_id')}}</th>
                                  <th width="10%">{{ __('message.name')}}</th>
                                  <th width="10%">{{ __('message.actions')}}</th>
                              </tr>
                          </thead>
                          <tbody>
                            @php
                              $i = 1;
                            @endphp
                              @if(isset($nodal) && $nodal->count() > 0) 
                                @foreach ($nodal as $nodal_item)
                                      <tr>
                                          <td>{{ $i++ }}</td>
                                          <td>{{ department_name($nodal_item->dept_id) }}</td>
                                          <td>{{ $nodal_item->designation_name }}</td> 
                                          <td>
                                              <a href="{{ route('nodal-designations.show',$nodal_item->id) }}" class="btn btn-xs btn-info" style="display:inline-block">{{ __('message.view')}}</a>
                                              
                                              <a  class="btn btn-xs btn-primary editNodalDesignation"  data-id="{{ $nodal_item->id }}"  data-dept-id="{{ $nodal_item->dept_id }}" data-name="{{ $nodal_item->designation_name }}">{{ __('message.edit')}}</a>

                                              <a href="javascript:void(0)"  class="btn btn-xs btn-danger trigger-btn" data-id="{{ $nodal_item->id }}" data-bs-toggle="modal"  data-bs-target="#myModal">  {{ __('message.delete') }}</a>

                                                  <form id="delete-form-{{ $nodal_item->id }}"
                                                        action="{{ route('nodal-designations.destroy', $nodal_item->id) }}"
                                                        method="POST"
                                                        style="display:none;">
                                                      @csrf
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
<div class="modal fade" id="add_NodalDesignations" style="display: none;" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">{{ __('message.add')}} Nodal Designation</h4>
        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="container">
        <form id="nodalDesignationFrm" method="POST" action="{{ route('nodal-designations.store') }}">
          @csrf
          <input type="hidden" name="_method" id="formMethod" value="POST">
          <input type="hidden" name="nodal_designation_id" id="nodal_designation_id">

          <div class="row">
              <label>Department <span class="required_filed">*</span></label>
              <select name="dept_id" id="dept_id" class="form-control">
                  <option value="">-- Select Department --</option>

                  @foreach($departments as $department)
                      <option value="{{ $department->dept_id }}">
                          {{ $department->dept_name }}
                      </option>
                  @endforeach
              </select>
          </div>

          <div class="row mt-2">
              <label>Nodal Designation Name <span class="required_filed">*</span></label>
              <input type="text" name="designation_name" id="designation_name"
                    class="form-control" autocomplete="off">
          </div>

          <div class="row mt-3">
              <div class="col-xl-12 text-end">
                  <button type="submit" class="btn btn-primary">
                      Submit
                  </button>
                  <button type="button" class="btn btn-default" data-bs-dismiss="modal">
                      Close
                  </button>
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
				<button type="button" class="btn btn-danger deleteBtn" data-unit-id="">{{ __('message.delete')}}</button>
			</div>
		</div>
	</div>
</div> 

<script src="{{asset('js/3.2.1.jquery.min.js')}}"></script>
<script type="text/javascript">

  $(document).ready(function(){
let deleteId = null;

    $('.trigger-btn').on('click', function () {
        deleteId = $(this).data('id');
    });
    $('.deleteBtn').on('click', function () {
        if (!deleteId) return;

        $('#delete-form-' + deleteId).submit();
    });
  });
  $(document).ready(function () {

    // ADD
    $('#addNodalDesignations').on('click', function () {

    // Reset form
    $('#nodalDesignationFrm')[0].reset();

    // Clear hidden ID (important)
    $('#nodal_designation_id').val('');

    // Set method to POST
    $('#formMethod').val('POST');

    // Set action to STORE route
    $('#nodalDesignationFrm').attr(
        'action',
        "{{ route('nodal-designations.store') }}"
    );

    // Open modal
    $('#add_NodalDesignations').modal('show');
});


    // EDIT
    $('.editNodalDesignation').on('click', function () {

        let id   = $(this).data('id');
        let name = $(this).data('name');
        let dept = $(this).data('dept-id');
//console.log('Editing ID:', id, 'Name:', name, 'Dept ID:', dept);
        $('#nodal_designation_id').val(id);
        $('#designation_name').val(name);
        $('#dept_id').val(dept);

        $('#formMethod').val('PUT');

        let updateUrl = "{{ route('nodal-designations.update', ':id') }}";
        updateUrl = updateUrl.replace(':id', id);

        $('#nodalDesignationFrm').attr('action', updateUrl);

        $('#add_NodalDesignations').modal('show');
    });

    // SUBMIT (AJAX)
    $('#nodalDesignationFrm').on('submit', function (e) {
        e.preventDefault();

        let form = $(this);
        let url = form.attr('action');

        $.ajax({
            type: 'POST', // always POST
            url: url,
            data: form.serialize(),
            success: function (response) {
                alert(response.success || 'Saved successfully');
                location.reload();
            },
            error: function (xhr) {
                console.error(xhr.responseText);
            }
        });

        $('#add_NodalDesignations').modal('hide');
    });

});

</script>
@endsection