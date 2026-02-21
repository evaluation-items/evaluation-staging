@extends('dashboards.admins.layouts.admin-dash-layout')
@section('title','SDG Goals Details')
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
                      SDG Goals List               	            
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
                            <button type="buton" id="addSdgGoals" style="margin:10px" class="btn btn-primary">{{ __('message.add')}} SDG Goal</button>
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
                                  <th width="5%">{{ __('message.name')}}</th>
                                  <th width="10%">{{ __('message.name')}} Gujarati</th>
                                  <th width="10%">{{ __('message.actions')}}</th>
                              </tr>
                          </thead>
                          <tbody>
                            @php
                              $i = 1;
                            @endphp
                              @if(isset($sdggoals) && $sdggoals->count() > 0) 
                                @foreach ($sdggoals as $sdggoal_item)
                                      <tr>
                                          <td>{{ $i++ }}</td>
                                          <td>{{ $sdggoal_item->goal_name }}</td>
                                          <td>{{ $sdggoal_item->goal_name_guj ?? '-' }}</td> 
                                          <td>
                                              <a href="{{ route('sdg-goals.show',$sdggoal_item->goal_id) }}" class="btn btn-xs btn-info" style="display:inline-block">{{ __('message.view')}}</a>
                                              
                                              <a  class="btn btn-xs btn-primary editSdgGoal" data-name-guj="{{ $sdggoal_item->goal_name_guj }}" data-id="{{ $sdggoal_item->goal_id }}" data-name="{{ $sdggoal_item->goal_name }}">{{ __('message.edit')}}</a>

                                              <a href="javascript:void(0)"  class="btn btn-xs btn-danger trigger-btn" data-id="{{ $sdggoal_item->goal_id }}" data-bs-toggle="modal"  data-bs-target="#myModal">  {{ __('message.delete') }}</a>

                                                  <form id="delete-form-{{ $sdggoal_item->goal_id }}"
                                                        action="{{ route('sdg-goals.destroy', $sdggoal_item->goal_id) }}"
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
<div class="modal fade" id="add_SdgGoals" style="display: none;" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">{{ __('message.add')}} SDG Goal</h4>
        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="container">
        <form id="sdgGoalFrm" method="POST" action="{{ route('sdg-goals.store') }}">
          @csrf
          <input type="hidden" name="_method" id="formMethod" value="POST">
          <input type="hidden" name="sdg_goal_id" id="sdg_goal_id">

          <div class="row mt-2">
              <label>SDG Goal Name <span class="required_filed">*</span></label>
              <input type="text" name="goal_name" id="goal_name" class="form-control" autocomplete="off">
          </div>
           <div class="row mt-2">
              <label>SDG Goal Name in Gujarati <span class="required_filed">*</span></label>
              <input type="text" name="goal_name_guj" id="goal_name_guj" class="form-control" autocomplete="off">
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
    $('#addSdgGoals').on('click', function () {

    // Reset form
    $('#sdgGoalFrm')[0].reset();

    // Clear hidden ID (important)
    $('#sdg_goal_id').val('');

    // Set method to POST
    $('#formMethod').val('POST');

    // Set action to STORE route
    $('#sdgGoalFrm').attr(
        'action',
        "{{ route('sdg-goals.store') }}"
    );

    // Open modal
    $('#add_SdgGoals').modal('show');
});


    // EDIT
    $('.editSdgGoal').on('click', function () {

        let id   = $(this).data('id');
        let name = $(this).data('name');
        let dept = $(this).data('dept-id');
//console.log('Editing ID:', id, 'Name:', name, 'Dept ID:', dept);
        $('#sdg_goal_id').val(id);
        $('#goal_name').val(name);
        $('#goal_name_guj').val($(this).data('name-guj'));
       

        $('#formMethod').val('PUT');

        let updateUrl = "{{ route('sdg-goals.update', ':id') }}";
        updateUrl = updateUrl.replace(':id', id);

        $('#sdgGoalFrm').attr('action', updateUrl);

        $('#add_SdgGoals').modal('show');
    });

    // SUBMIT (AJAX)
    $('#sdgGoalFrm').on('submit', function (e) {
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

        $('#add_SdgGoals').modal('hide');
    });

});

</script>
@endsection