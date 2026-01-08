@extends('dashboards.eva-dir.layouts.evaldir-dash-layout')
@section('title','Studies -  Dir. Of Evaluation')
<link rel="stylesheet" href="{{asset('css/choices.min.css')}}">
<script src="{{asset('js/choices.min.js')}}"></script>

<style>.borderless {border:0px !important;} table th,td {padding:5px !important} .ro_dd_p{margin-bottom:5px;} </style>
@section('content')
@php
  use Illuminate\Support\Facades\Crypt;
@endphp
<!--begin::Content-->
<div class="content  d-flex flex-column flex-column-fluid" id="kt_content">
    <!--begin::Subheader-->
	<div class="subheader py-2 py-lg-6 subheader-solid " id="kt_subheader">
		<div class=" container-fluid d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
		<!--begin::Info-->
			<div class="d-flex align-items-center flex-wrap mr-1">
			<!--begin::Page Heading-->
				<div class="d-flex align-items-baseline flex-wrap mr-5">
				<!--begin::Page Title-->
					<h5 class="text-dark font-weight-bold my-1 mr-5">Proposal's List</h5>
				<!--end::Page Title-->
				</div>
			<!--end::Page Heading-->
			</div>
		<!--end::Info-->
		</div>
	</div>
	<!--end::Subheader-->

	<!--begin::Entry-->
	<div class="d-flex flex-column-fluid" id="new_proposals_container">
		<!--begin::Container-->
			<div class=" container ">
			<!--begin::Card-->
				<div class="card card-custom gutter-b">
					<div class="card-header flex-wrap py-3">
						<div class="card-toolbar">
							<h5>New Proposals Received</h5>
						</div>
					</div>
					<div class="card-body">
					<!--begin: Datatable-->
						<table class="table table-bordered table-hover show-datatable" id="kt_datatable" style="margin-top: 13px !important">
							<thead>
								<tr>
									<th>Sr No.</th>
									<th>Scheme Name</th>
									<th>Department Name</th>
									<th>Received Date From GAD</th>
									<th>Actions</th>
								</tr>
							</thead>
              <tbody>
                @php $i=1; @endphp
                @foreach($new_proposals as $prop)
                
                <tr>
                  <td>{{ $i }}</td>
                  <td>{{ $prop->scheme_name }}</td>
                  <td>{{ $prop->dept_name }}</td>
                  <td  width="20%">{{date('M d Y',strtotime( $prop->created_at))}}</td>
                  <td width="23%">
                  <a href="{{ route('schemes.proposal_detail',[Crypt::encrypt($prop->draft_id),Crypt::encrypt($prop->id)]) }}" class="btn btn-xs btn-info">View</a>
                    @if(auth()->user()->role == 2)
                    <button type="button" class="btn btn-xs btn-danger"  onclick="add_team('{{ $prop->draft_id }}','{{ $prop->id }}','{{ $prop->dept_id }}')"  style="display:inline-block">Assign to DD</button>
                    <button type="button" class="btn btn-xs btn-danger" onclick="fn_backward_modal('{{ $prop->draft_id }}','{{ $prop->id }}','{{ $prop->dept_id }}')" style="display:inline-block">Backwrd to GAD</button>
                    @endif
                
                  </td>
                </tr>
                @php $i++; @endphp
          
                @endforeach
              </tbody>
						</table>
					<!--end: Datatable-->
					</div>
				</div>
			<!--end::Card-->
            </div>
			<!--end::Container-->
		</div>
		<!--end::Entry-->



        	<!--begin::Entry-->
	<div class="d-flex flex-column-fluid" id="ongoing_proposals_container">
		<!--begin::Container-->
			<div class=" container ">
			<!--begin::Card-->
				<div class="card card-custom gutter-b">
					<div class="card-header flex-wrap py-3">
						<div class="card-toolbar">
							<h5>Ongoing Evaluation Studies </h5>
						</div>
					</div>
					<div class="card-body">
					<!--begin: Datatable-->
					<table class="table table-bordered table-hover show-datatable" id="kt_datatable" style="margin-top: 13px !important">
						<thead>
								<tr>
									<th>Sr No.</th>
									<th>Scheme Name</th>
									<th>Created Date</th>
									<th>Actions</th>
								</tr>
							</thead>
                <tbody>
                  @php $i=1; @endphp
                  @foreach($ongoing_proposal as $prop)
                  
                    <tr>
                    <td>{{ $i }} </td>
                    <td>{{ SchemeName($prop->scheme_id) }}</td> 
                    <td>{{ !empty($prop->created_at) ?  date('M d Y',strtotime($prop->created_at)) : '-'}}</td> 
                    <td width="23%">
                      {{-- <a href="{{ route('schemes.proposal_detail',[$prop->draft_id,$prop->id]) }}" class="btn btn-xs btn-info" style="display: inline-block">View Scheme</a> --}}
                    @if(App\Models\Stage::where('scheme_id',$prop->scheme_id)->latest()->first())
                      <a href="{{ route('stages.show', ['stage' => $prop->scheme_id]) }}" class="btn btn-xs btn-info" style="display: inline-block">View Stage</a>
                      @endif
                    </td>
                    </tr>
                  
                    @php $i++; @endphp
                    @endforeach
                </tbody>
						</table>
					<!--end: Datatable-->
					</div>
				</div>
			<!--end::Card-->
			</div>
			<!--end::Container-->
		</div>
		<!--end::Entry-->
		
		<!--begin::Entry-->
	<div class="d-flex flex-column-fluid" id="returned_proposals_container">
		<!--begin::Container-->
			<div class=" container ">
			<!--begin::Card-->
				<div class="card card-custom gutter-b">
					<div class="card-header flex-wrap py-3">
						<div class="card-toolbar">
							<h5>Returned Proposals</h5>
						</div>
					</div>
					<div class="card-body">
					<!--begin: Datatable-->
						<table class="table table-bordered table-hover show-datatable" id="kt_datatable" style="margin-top: 13px !important">
                            <thead>
                                <tr>
                                  <th>Sr No.</th>
                                  <th>Scheme Name</th>
                                  <th>Department Name</th>
                                  <th>Received Date</th>
                                  <th>Returned Date</th>
                                  <th>Remark</th>
                                  <th>Actions</th>
                                </tr>
                              </thead>
                              <tbody>
                                @php $i=1; @endphp
                              @foreach($returned_proposals as $prop)
                                {{-- @if($prop->status_id == $status_id_return or $prop->status_id == 26) --}}
                                <tr>
                                  <td>{{ $i }}</td>
                                  <td>{{ $prop->scheme_name }}</td> 
                                  <td>{{ $prop->dept_name }}</td>
                                  <td  width="20%">{{date('M d Y',strtotime( $prop->created_at))}}</td>
                                  <td  width="20%">{{(!empty($prop->return_eval_date)) ? date('M d Y',strtotime( $prop->return_eval_date)) : '-'}}</td>
                                  <td width="23%">{{ $prop->remarks}}</td>
                                  <td width="20%">
                                    <a href="{{ route('schemes.proposal_detail',[$prop->draft_id,$prop->id]) }}" class="btn btn-xs btn-info" style="display: inline-block">View</a>
                                  </td>
                                </tr>
                                {{-- @endif --}}
                                @php $i++; @endphp
                                @endforeach
                              </tbody>
						</table>
					<!--end: Datatable-->
					</div>
				</div>
			<!--end::Card-->
			</div>
			<!--end::Container-->
		</div>
		<!--end::Entry-->
        	<!--begin::Entry-->
	<div class="d-flex flex-column-fluid" id="complete_proposal">
		<!--begin::Container-->
			<div class="container">
			<!--begin::Card-->
				<div class="card card-custom gutter-b">
					<div class="card-header flex-wrap py-3">
						<div class="card-toolbar">
							<h5>Completed Evaluation Studies (PDFs)</h5>
						</div>
					</div>
					<div class="card-body custom_filter_body">
              <div class="custom_filter">
                <div class="col-md-2 year_items">
                  <select class="form-control" id="year_select" aria-label="Default select example">
                    @php
                      $years = getYears();
                    @endphp
                        <option value="">Select Year</option>
                      @foreach ($years as $year)
                          <option value="{{$year}}">{{$year}}</option>
                      @endforeach
                  </select>
                </div>
                <div class="col-md-4 department_items">
                  <select id="department_select" class="form-control" aria-label="Default select example">
                    @php
                      $department = App\Models\Department::get();
                    @endphp
                    <option value="">Select Department</option>
                    @foreach ($department as $department_items)
                        <option value="{{$department_items->dept_id}}">{{$department_items->dept_name}}</option>
                    @endforeach
                    
                  </select>
                </div>
                <div class="col-md-3 dd_user_items">
                  @php 
                    $ddUser = App\Models\User::where('role',15)->get();
                  @endphp
                    <select id="deputyDirector_select" class="form-control" aria-label="Default select example">
                      <option value="">Select Deputy Director</option>
                      @foreach ($ddUser as $dd_user_detail)
                          <option value="{{$dd_user_detail->id}}">{{$dd_user_detail->name}}</option>
                      @endforeach
                    </select>
                </div>
              </div>
					<!--begin: Datatable-->
						<table class="table table-bordered table-hover custom_complete_table" id=""  style="margin-top: 13px !important">
							<thead>
								<tr>
									<th>Sr No.</th>
									<th>Scheme Name</th>
									<th>Completed Date</th>
									<th>Actions</th>
								</tr>
							</thead>
						<tbody>
							@php $i=1; @endphp
							@foreach($complted_proposal as $complete)
							  <tr>
								<td>{{ $i }}</td>
								<td>{{ SchemeName($complete->scheme_id) }}</td> 
								<td  width="20%">{{date('M d Y',strtotime( $complete->updated_at))}}</td>
								<td width="23%">
								  <a href="{{route('stages.downalod',$complete->id)}}" class="btn btn-xs btn-info" style="display: inline-block">Stage Report Download</a>
								</td>
							  </tr>
						   
							  @php $i++; @endphp
							  @endforeach
						</tbody>
						</table>
					<!--end: Datatable-->
					</div>
				</div>
			<!--end::Card-->
			</div>
			<!--end::Container-->
		</div>
		<!--end::Entry-->
	
</div>
<!--end::Content-->

<div class="modal fade" id="assign_to_gad" style="display: none;" aria-hidden="true" onsubmit="$('.submit_btn').hide()">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Add Remarks</h4>
          <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="container">
            <form id="remarksFrm" method="post" action="{{ route('evaldir.returntogad') }}" enctype="multipart/form-data">
              @csrf
              <input type="hidden" name="draft_id" id="return_draft_id">
              <input type="hidden" name="send_id" id="return_send_id">
              <input type="hidden" name="dept_id" id="return_dept_id">
              <div class="row form-group">
                <textarea name="remarks" class="form-control" placeholder="Add Remarks" maxlength="500"></textarea>
              </div>
              <div class="row form-group">
                <!-- <label for="team_members" class="col-xl-12">Team Member DD</label> -->
                <!-- <div class="col-xl-12" id="add_team_members_dd_div"> -->
                <label for="" class="col-xl-12">Team Member DD *</label>
                <select name="team_member_dd[]" class="from-control"  multiple="multiple">
                  <option value=""></option>
                  @foreach($dd as $attkey => $attval)
                    <option value="{{ $attval->id }}" data-badge="">{{ $attval->name }}</option>
                  @endforeach
                </select>
                <!-- </div> -->
              </div>
           
              <div class="row">
                <div class="col-xl-8"></div>
                <div class="col-xl-2">
                  <button type="button" class="btn btn-default" data-bs-dismiss="modal">Close</button>
                </div>
                <div class="col-xl-2">
                  <button type="submit" class="btn btn-primary submit_btn_meeting">Submit</button>
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
  <div class="modal fade" id="add_team" style="display: none;" aria-hidden="true" onsubmit="return fn_submit_add_team()">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Team Formation</h4>
          <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="container">
            {{-- <form method="post" action="{" enctype="multipart/form-data" id="evaldir_addteam">
              @csrf --}}
              <form id="assignDDFrm" method="post" action="{{ route('evaldir.sendschemetodd') }}" enctype="multipart/form-data">
                @csrf
              <div id="first_dd" style="display:none;"></div>
              <div id="first_ro" style="display:none;"></div>
              <input type="hidden" name="draft_id" id="return_draft_id">
              <input type="hidden" name="send_id" id="return_send_id">
              <input type="hidden" name="dept_id" id="return_dept_id">
              <div class="row form-group">
                <label for="teamname">Add Remark *</label>
                  <textarea name="remarks" class="form-control remarks" placeholder="Add Remarks" maxlength="500"></textarea>
              </div>
              <div class="row form-group">
                <!-- <label for="team_members" class="col-xl-12">Team Member DD</label> -->
                <!-- <div class="col-xl-12" id="add_team_members_dd_div"> -->
                <label for="choices-multiple-remove-button" class="col-xl-12">Team Member DD *</label>
                <select id="choices-multiple-remove-button" name="team_member_dd[]" class="form-control" placeholder="Team Member DD" multiple style="min-width:100% !important">
                  @foreach($dd as $attkey => $attval)
                    <option value="{{ $attval->id }}">{{ $attval->name }}</option>
                  @endforeach
                </select>
                <!-- </div> -->
              </div>
             
            
              <div class="row" id="the_second_last_div">
               
                <div class="col-xl-12" style="text-align: right;">
                  <!-- <button type="submit" class="btn btn-primary add_submit_btn_meeting" style="display:none">Submit</button> -->
                  <button type="submit" class="btn btn-primary add_submit_btn_meeting">Submit</button>
                  <button type="button" class="btn btn-default add_close_btn_meeting" data-bs-dismiss="modal">Close</button>
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



  <div class="modal fade" id="return_proposal" style="display: none;" aria-hidden="true" onsubmit="$('.submit_btn').hide()">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Add Remarks</h4>
          <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="container">
            <form id="returnScheme" method="post" action="{{ route('evaldir.returntogad') }}" enctype="multipart/form-data">
              @csrf
              <input type="hidden" name="draft_id" id="return_draft_id">
              <input type="hidden" name="send_id" id="return_send_id">
              <input type="hidden" name="dept_id" id="return_dept_id">
              <div class="row form-group">
                <textarea name="remarks" class="form-control remarks" placeholder="Add Remarks" maxlength="500"></textarea>
              </div>
              <div class="row">
               
                <div class="col-xl-12" style="text-align: right;">
                  <button type="submit" class="btn btn-primary submit_btn_meeting">Submit</button>
                  <button type="button" class="btn btn-default" data-bs-dismiss="modal">Close</button>
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
            
  <div class="modal fade" id="pendingremarks" tabindex="-1" role="dialog" aria-labelledby="pendingremarksLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="pendingremarksLabel">Add Remarks</h5>
          <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form method="post" action="{{ route('evaldir.pending_proposal_remarks') }}">
            @csrf
            <input type="hidden" name="theid" id="get_the_id">
            <div class="form-group">
              <textarea rows="3" style="font-size:25px" name="remarks" class="form-control" id="remarks_text"></textarea>
            </div>
            <div class="form-group" style="text-align:right">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="margin:0px 10px">Close</button>
              <button type="submit" class="btn btn-primary">Save</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
  @endsection
  {{-- <script src="{{asset('js/3.2.1.jquery.min.js')}}"></script> --}}
  <script src="{{asset('js/jquery.min.js')}}"></script>
<script>
  
 
          $(document).ready(function () {

            var datatable =  $('.custom_complete_table').DataTable({
              columns: [
                { data: 'DT_RowIndex' },
                { data: 'scheme_name' },
                { data: 'completed_date' },
                { data: 'actions' }
                ],
            });
            $('#year_select, #department_select, #deputyDirector_select').change(function() {
              updateTable();
          });

          
    function updateTable() {
          // Get selected values
        var selectedYear = $('.year_items select').val();
        var selectedDepartment = $('.department_items select').val();
        var selectedDeputyDirector = $('.dd_user_items select').val();

          // Make an AJAX request to fetch updated data
          $.ajax({
              url: "{{route('custom_filter_items')}}",
              method: 'POST',
              data: {
                  year: selectedYear,
                  department: selectedDepartment,
                  deputyDirector: selectedDeputyDirector,
                  _token: '{{ csrf_token() }}'
              },
              success: function(response) {
                 // Clear existing rows in the table
                datatable.clear();

                // Add new rows to the table
                datatable.rows.add(response.data);

                // Redraw the table
                datatable.draw();
              },
              error: function(error) {
                  console.log(error);
              }
          });
      }

    // Initial table update
    updateTable();
     
   });

  function frwdgad(val,uid,uname,sid,did,stid){
    // alert(val'+---+'uid'+----+'uanme'+-----+'sid'+-----+'did'+-----+'stid);
    $.ajax({
      type: "post",
      url: "{{ url('g') }}",
      data: {
        "_token": "{{ csrf_token() }}",
        user_id:uid,
        dept_id:did,
        draft_id:sid,
        created_by:uname,
        status_id:stid
      },
      success: function(response)
      {
          alert("Successfull Data"); 
          location.reload();
      }
      
    });
  }
 
</script>
<script>
  
  $(document).ready(function () {
    //$(".js-select2").select2();
    $('.numberonly').keypress(function (e) {
      var charCode = (e.which) ? e.which : event.keyCode;
      if (String.fromCharCode(charCode).match(/[^0-9]/g))
        return false;
      });   
      $("#add_remarks").click(function(){
      var theid = $(this).val();
      $("#get_the_id").val(theid);
      $("#pendingremarks").modal('show');
    }); 
  });
 
  function fn_backward_modal(back_draft_id,back_send_id,dept_id) {
    $("#return_proposal #return_draft_id").val(back_draft_id);
    $("#return_proposal #return_send_id").val(back_send_id);
    $("#return_proposal #return_dept_id").val(dept_id);
    $("#return_proposal").modal('show');
  }
  function assign_to_gad(back_draft_id,back_send_id,dept_id) {
    $("#assign_to_gad #return_draft_id").val(back_draft_id);
    $("#assign_to_gad #return_send_id").val(back_send_id);
    $("#assign_to_gad #return_dept_id").val(dept_id);
   // $("#assign_to_gad").modal('show');
    // $(".js-select2").select2({
    //   closeOnSelect : false,
    //     placeholder : "Placeholder",
    //     allowHtml: true,
    //     allowClear: true,
    //     tags: true // создает новые опции на лету
    //   });
  }
  

</script>
<script type="text/javascript">
  $(document).ready(function(){
    // var ktcontent = $(".container .card").height();
    // var ktcontent = $("#kt_datatable").height();
    // $(".content-wrapper").css('min-height',ktcontent);
  });

  function add_team(back_draft_id,back_send_id,dept_id) {
    $('.choices').width('100%');
    $("#add_team").modal('show');
    $("#add_team #return_draft_id").val(back_draft_id);
    $("#add_team #return_send_id").val(back_send_id);
    $("#add_team #return_dept_id").val(dept_id);
  }

  $(document).ready(function(){
    $(".teams_study_id").change(function() {
      var the_study_id = $(this).val();
      // console.log('the_study_id = '+the_study_id);
      $.ajax({
        type:'post',
        dataType:'json',
        url:"{{ route('evaldir.getteamcount') }}",
        data:{'_token':"{{csrf_token()}}",'study_id':the_study_id},
        beforeSend:function(){
          $(".team_name_text").val('');
        },
        success:function(response) {
          $(".team_name_text").val(response);
        },
        error:function() {
          console.log('getteamcount ajax error');
        }
      });
    });
  });

  function fn_submit_add_team() {
    
    var teamname = $("form #teamname").val();
    var study_id = $("form #study_id").val();
    if(teamname == '') {
      $("form #teamname").focus();
      $("#teamname_error").remove();
      $("form #teamname").after('<div id="teamname_error" class="text-danger">Team name cannot be blank</div>');
      return false;
    } else {
      $("#teamname_error").remove();
    }
    if(study_id == '') {
      $("form #study_id").focus();
      $("#study_id_error").remove();
      $("form #study_id").after('<div id="study_id_error" class="text-danger">Study cannot be blank</div>');
      return false;
    } else {
      // $("#showteampopup #showteampopup_back_btn").remove();
      $("#study_id_error").remove();
    }
    return true;
  }

  function fn_edit_team(id) {
    $(".choices").width('100%');
    $(".choices__inner").css('background-color','#FFFFFF');
    $.ajax({
      type:'post',
      dataType:'json',
      url:"{{ route('evaldir.editteam') }}",
      data:{'_token':"{{csrf_token()}}",'id':id},
      success:function(response) {
        // console.log(response.teams[0]['study_id']+' = study_id');
        $('#edit_team_modal form #teamname').val('');
        $("#team_member_dd_members").html('');
        $("#team_member_ro_members").html('');
        var the_study_id = 0;
        var the_dd_id = 0;
        var the_ro_id = 0;
        $.each(response.teams,function(key,val){
          $('#edit_team_modal form #teamname').val(val.team_name);
          the_study_id = val.study_id;
          the_dd_id = val.team_member_dd;
          the_ro_id = val.team_member_ro;
          $("#the_team_member_id").val(val.team_id);
          // console.log(val);
        });
        // console.log(the_dd_id+' = the_dd_id');
        $('#edit_team_modal form #study_id').html('');
        $('#edit_team_modal form #study_id').append('<option value="">Select Study Topic</option>');
        $.each(response.study,function(stukey,stuval){
          if(stuval.draft_id == the_study_id) {
            $('#edit_team_modal form #study_id').append('<option selected value="'+stuval.draft_id+'">'+stuval.scheme_name+'</option>');
          } else {
            $('#edit_team_modal form #study_id').append('<option value="'+stuval.draft_id+'">'+stuval.scheme_name+'</option>');
          }
        });
        $('#team_members_dd_div').html('');
        var iterate=0;
        var the_input = '';
        var the_dd_mems = '';
        var the_dd_iterate = 1;
        $.each(response.dd,function(memkey,memval){
          // console.log(memval.id+" = memval");
          if(the_dd_id.indexOf('"'+memval.id+'"') != '-1') {
            // the_dd_mems[the_dd_iterate] = memval.name;
            $("#team_member_dd_members").append(' ('+the_dd_iterate+') ' +memval.name);
            the_dd_iterate++;
            the_input = memval.name+' <input type="checkbox" name="team_member_dd[]" checked class="team_member_dd" value="'+memval.id+'" id="team_member_dd_'+iterate+'" style="margin:0px 10px 0px 0px">';
          } else {
            the_input = memval.name+' <input type="checkbox" name="team_member_dd[]" class="team_member_dd" value="'+memval.id+'" id="team_member_dd_'+iterate+'"  style="margin:0px 10px 0px 0px">';
          }
          iterate++;
          $('#team_members_dd_div').append(the_input);
        });
        $('#team_members_ro_div').html('');
        // console.log(JSON.stringify(response.ro)+" = response ro");
        // console.log(the_ro_id+" = the_ro_id");
        // var sss = ["16","26"];
        var the_ro_iterate = 1;
        $.each(response.ro,function(rokey,roval){
          var the_ro_input = '';
          // console.log(sss.indexOf("16")+" = inarray");
          if(the_ro_id.indexOf('"'+roval.id+'"') != '-1') {
            $("#team_member_ro_members").append(' ('+the_ro_iterate+') ' +roval.name+'  ');
            the_ro_iterate++;
            the_ro_input = roval.name+' <input type="checkbox" checked name="team_member_ro[]" value="'+roval.id+'" id="team_members" maxlength="100"> ';
          } else {
            the_ro_input = roval.name+' <input type="checkbox" name="team_member_ro[]" value="'+roval.id+'" id="team_members" maxlength="100"> ';
          }
          $('#team_members_ro_div').append(the_ro_input);
        });
        // $("#team_members_ro_div").after(the_ro_id);
      },
      error:function(){
        console.log('edit team ajax error');
      }
    });
    $("#edit_team_modal").modal('show');
  }

  function get_first_dd(ddid) {
    var dd_html = $("#add_team_members_dd_div input[type=checkbox]:checked").length;
    var ro_add_html = $("#add_team_members_ro_div input[type=checkbox]:checked").length;
    if(dd_html == 1) {
    // if($("form #first_dd").html() == '') {
      $("form #first_dd").html('<input type="text" name="first_dd" value="'+ddid+'" >');
    }
    if(dd_html > 0 && ro_add_html > 0) {
      // alert('if = '+dd_html);
      // alert('if = '+ro_add_html);
      $("form .add_submit_btn_meeting").show();
    } else {
      // alert(dd_html);
      // alert(ro_add_html);
      $("form .add_submit_btn_meeting").hide();
    }
  }

  function get_first_ro(roid) {
    var ro_html = $("#add_team_members_ro_div input[type=checkbox]:checked").length;
    var dd_add_html = $("#add_team_members_dd_div input[type=checkbox]:checked").length;
    if(ro_html == 1) {
    // if($("form #first_ro").html() == '') {
      $("form #first_ro").html('<input type="text" name="first_ro" value="'+roid+'">');
    }
    if(dd_add_html > 0 && ro_html > 0) {
      // alert('if = '+dd_html);
      // alert('if = '+ro_add_html);
      $("form .add_submit_btn_meeting").show();
    } else {
      // alert(dd_html);
      // alert(ro_add_html);
      $("form .add_submit_btn_meeting").hide();
    }
  }

  $(window).on('load',function() {
    $(".choices").css('width','100% !important');
    $(".choices__inner").css('background-color','#FFFFFF');
  });

  $(document).ready(function(){
    // alert($(".choices").width());
    var multipleCancelButton = new Choices('#choices-multiple-remove-button', {
        removeItemButton: true,
        maxItemCount:100,
        searchResultLimit:500,
        renderChoiceLimit:100
    });
  });

  $(document).ready(function(){


    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $("#evaldir_addteam").submit(function(e) {
      e.preventDefault();
      let formData = new FormData(this);
      $.ajax({
          type:'post',
          dataType:'json',
          url:"{{ route('evaldir.addteam') }}",
          data:formData,
          contentType:false,
          processData:false,
          success:function(response) {
            $("#add_team").modal('hide');
            var winwidth = $(window).width();
            var winheight = $(window).height();
            var the_html = '';
            $.each(response,function(thekey,theval){
              var the_dds = theval.dd_members;
              // var the_dd_members_arr = [];
              // var j = 1;
              // for(var i = 0;i<the_dds.length;i++) {
              //   the_dd_members_arr[i] = '('+j+') '+the_dds[i]+' ';
              //   j++;
              // }
              var the_ros = theval.ro_members;
              // var the_ro_members_arr = [];
              // var j = 1;
              // for(var i = 0;i<the_ros.length;i++) {
              //   the_ro_members_arr[i] = '('+j+') '+the_ros[i]+' ';
              //   j++;
              // }
              var the_table = '<table class="table table-hover" id="study_id_table"><tbody><tr><th>Study Name</th><td>'+theval.study_name+'</td></tr><tr><th>Team Name</th><td>'+theval.team_name+'</td></tr><tr><th>DD Leader</th><td>'+theval.dd_leader+'</td></tr><tr><th>RO Leader</th><td>'+theval.ro_leader+'</td></tr><tr><th>DD Members</th><td>'+the_dds.toString()+'</td></tr><tr><th>RO Members</th><td>'+the_ros.toString()+'</td></tr></tbody></table>';
              the_html += '<div class="row" style="padding-left:15%;background-color:transparent;color:black;font-size:40px;font-weight:bolder"><div class="col-xl-12"><div class="table-responsive">'+the_table+'</div></div></div>';
            });
            // console.log(the_html);
            $("#showteampopup").css('overflow','hidden').prepend(the_html).modal('show');
          },
          error:function() {
              console.log('add_scheme ajax error');
          }
      });
    });
  });
function reloadthepage() {
  document.location.reload();
}
</script>




