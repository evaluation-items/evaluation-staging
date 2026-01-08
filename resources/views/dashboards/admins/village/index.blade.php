@extends('dashboards.admins.layouts.admin-dash-layout')
@section('title','Village')
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
                     {{ __('message.village') }} {{ __('message.list') }}                	            
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
                          <div class="col-lg-3">
                            @if(Session::has('email_err'))
                              <h4 style="color:red">{{ Session::get('email_err') }}</h4>
                            @endif
                          </div>
                      <div class="col-lg-12">
                        <div class="row">
                          <div class="col-lg-3">
                            <button type="buton" id="addVillage" style="margin:10px" class="btn btn-primary">{{ __('message.add') }} {{ __('message.village') }}</button>
                          </div>
                          
                          <div class="col-lg-3">
                            <select class="form-control district" id="district" aria-label="Default select example">
                                  <option value="">{{ __('message.select') }} {{ __('message.district') }} {{ __('message.name') }}</option>
                                  @foreach ($district_list as $key => $district)
                                    <option value="{{$key}}">{{$district}}</option>
                                  @endforeach
                            </select>
                         </div>
                         <div class="col-lg-3">
                          <select class="form-control talukaList" id="taluka" aria-label="Default select example">
                                <option value="">{{ __('message.select') }} {{ __('message.taluka') }} {{ __('message.name') }}</option>

                          </select>
                       </div>
                        </div>
                        <table class="table table-bordered table-striped dataTable dtr-inline villageDatatable" id="example1" style="text-align: center;">
                          <thead>
                              <tr>
                                  <th width="5%">{{ __('message.no') }}</th>
                                  <th width="10%">{{ __('message.district') }} {{ __('message.name') }}</th>
                                  <th width="10%">{{ __('message.taluka') }} {{ __('message.name') }}</th>
                                  <th width="10%">{{ __('message.name') }}</th>
                                  <th width="10%">{{ __('message.actions') }}</th>
                              </tr>
                          </thead>
                          <tbody>
                            @php  $i = 1; @endphp
                             @if ($village_list->count() > 0) 
                                  @foreach ($village_list as $key => $village_data)
                                    @foreach ($village_data->district as $dist)
                                        @foreach ($village_data->taluka_list as $taluka)
                                        <tr>
                                            <td>{{ $i++ }}</td>
                                            <td>{{ $dist->name_e }}</td>
                                            <td>{{ $taluka->tname_e}}</td> 
                                            <td>{{ $village_data->vname_e }}</td> 
                                            <td>
                                                <a href="{{ route('village.show',$village_data->id ) }}" class="btn btn-xs btn-info" style="display:inline-block">{{ __('message.view') }}</a>
                                                <a href="{{ route('village.edit',$village_data->id ) }}" class="btn btn-xs btn-primary">{{ __('message.edit') }}</a>
                                                <a href="#myModal" class="btn btn-xs btn-danger trigger-btn" data-id= "{{$village_data->id}}"data-bs-toggle="modal">{{ __('message.delete') }}</a> 
                                                {{-- <form id="delete-form-{{$village_data->id }}" action="{{ route('village_destroy.destroy',$village_data->id ) }}" method="POST" style="display: none;">
                                                    @csrf
                                                    @method('DELETE')
                                                </form>  --}}
                                            </td>
                                        </tr>
                                        @endforeach
                                    @endforeach
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

<div class="modal fade" id="add_village" style="display: none;" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">{{ __('message.add') }} {{ __('message.village') }}</h4>
        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="container">
          <form id="villageFrm" method="post" enctype="multipart/form-data" action="{{ route('village.store') }}" autocomplete="off">
            @csrf
            <div class="row">
              <label for="dcode">{{ __('message.district') }} {{ __('message.name') }} <span class="required_filed"> * </span> : </label>
              <select class="form-control district" id="district" name="dcode" aria-label="Default select example">
                  <option value="">{{ __('message.select') }} {{ __('message.district') }} {{ __('message.name') }}</option>
                  @foreach ($district_list as $key => $district)
                    <option value="{{$key}}">{{$district}}</option>
                  @endforeach
                </select>
            </div>
            <div class="row">
              <label for="tcode">{{ __('message.taluka') }} {{ __('message.name') }} <span class="required_filed"> * </span> : </label>
                <select class="form-control talukaList" id="taluka" name="tcode" aria-label="Default select example">
                  <option value="">{{ __('message.select') }} {{ __('message.taluka') }} {{ __('message.name') }}</option>
                </select>
            </div>

            <div class="row">
                <label for="vname_e">{{ __('message.village') }} {{ __('message.name') }} <span class="required_filed"> * </span> : </label>
                <input type="text" name="vname_e" id="vname_e" value="" class="form-control pattern" autocomplete="off">
            </div>
            <div class="row">
                <div class="col-xl-12" style="text-align: right;margin-top:10px;">
                    <button type="submit" class="btn btn-primary">{{ __('message.submit') }}</button>
                    <button type="button" class="btn btn-default" data-bs-dismiss="modal">{{ __('message.close') }}</button>
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
				<h4 class="modal-title w-100">{{ __('message.are_you_sure') }}</h4>	
                <button type="button" class="close" data-bs-dismiss="modal" aria-hidden="true">&times;</button>
			</div>
			<div class="modal-body">
				<p>{{ __('message.delete_msg') }}</p>
			</div>
			<div class="modal-footer justify-content-center">
				<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('message.cancel') }}</button>
				<button type="button" class="btn btn-danger deleteBtn" data-village-id="">{{ __('message.delete') }}</button>
			</div>
		</div>
	</div>
</div> 
@endsection

<script src="{{asset('js/jquery-laest-version.min.js')}}"></script>
<script type="text/javascript">

  $(document).ready(function(){

    var datatable =  $('.villageDatatable').DataTable({
      columns: [
        { data: 'DT_RowIndex' },
        { data: 'district_name' },
        { data: 'taluka_name' },
        { data: 'name' },
        { data: 'actions' }
        ],
    });
    $('.talukaList').change(function() {
      var tcode = $(this).val();
      const url = "{{ route('village_list') }}";
      if(tcode != ""){
        $.ajax({
                type: 'GET',
                url: url,
                data:{'tcode':tcode},
                headers: {
                  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (response) {
                  // Clear existing rows in the table
                  datatable.clear();

                  // Add new rows to the table
                  datatable.rows.add(response.data);

                  // Redraw the table
                  datatable.draw();
                },
                error: function () {
                    console.log('Error: Unable to delete department.');
                }
              });
      }
  });

    
    $('#addVillage').on('click',function(){
         $('#add_village').modal('show');
    });

    //Get Taluka
    $('.district').on('change',function(){
        var district_code = $(this).val();
        if(district_code != ""){
          $.ajax({
            type:'POST',
            dataType:'json',
            url:"{{ route('get_taluka') }}",
            data:{'_token':"{{ csrf_token() }}",'taluka_dcode':district_code},
            
            success:function(response) {
                $(".talukaList").html('');
              if(response.error != '' && response.error != undefined){
                  alert(response.error);
              }else{
                  $.each(response.talukas,function(reskey,resval){
                     $('.talukaList').append($("<option></option>").attr("value", resval.tcode) .text(resval.tname_e)); 
                  });
              }
            },
            error:function() {
                console.log('districts ajax error');
            }
          })
        }else{
          alert('Something went wrong');
        }
    });
  });
  $(document).on('click','.trigger-btn', function () {
        var district_id = $(this).data('id');
        var deleteBtn = $('.deleteBtn');
        var deleteId = deleteBtn.data('village-id');
        deleteBtn.data('village-id',district_id);
        
    });

    $(document).on('click' ,'.deleteBtn', function () {

      $('#myModal').modal('hide');
          const id = $(this).data('village-id');
          if (id !== "") {
            const url = "{{ route('village_destroy.destroy', ':id') }}".replace(':id', id);
              $.ajax({
                type: 'POST',
                dataType: 'json',
                url: url,
                headers: {
                  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                  success: function (response) {
                     alert(response.success);
                     location.reload();
                  },
                  error: function () {
                      console.log('Error: Unable to delete department.');
                  }
              });
          }
      });

     
  
    //   route('village_destroy.destroy', $village->id)
    //     $('#delete-form-'+id).get(0).submit();
    //     $('#myModal').modal('hide');
    // });

</script>