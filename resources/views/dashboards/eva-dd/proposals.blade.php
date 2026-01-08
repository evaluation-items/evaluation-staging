@extends('dashboards.eva-dd.layouts.evaldd-dash-layout')
@section('title','Study - Evalution D.D')
@section('content')
 <!--begin::Content-->
 <div class="content  d-flex flex-column flex-column-fluid" id="kt_content">
            <!--begin::Subheader-->
            <div class="subheader py-2 py-lg-6  subheader-solid " id="kt_subheader">
              <div class=" container-fluid  d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
                <!--begin::Info-->
                <div class="d-flex align-items-center flex-wrap mr-1">
                  <!--begin::Page Heading-->
                  <div class="d-flex align-items-baseline flex-wrap mr-5">
                    <!--begin::Page Title-->
                    <h5 class="text-dark font-weight-bold my-1 mr-5">Studies</h5>
                    <!--end::Page Title-->                  
                  </div>
                  <!--end::Page Heading-->
                </div>
                <!--end::Info-->
              </div>
            </div>
            <!--end::Subheader-->
            <!--begin::Entry-->
            <div class="d-flex flex-column-fluid">
              <!--begin::Container-->
              <div class=" container-fluid ">
                <!--begin::Card-->
                <div class="card card-custom gutter-b">
                  <div class="card-body">
                    <!--begin: Datatable-->
                     <table class="table table-bordered table-hover show-datatable" id="kt_datatable" style="margin-top: 13px !important">
                      <thead>
                        <tr>
                          <th>Sr No.</th>
                          <th>Evaluation Study</th>
                          <th>Department</th>
                          <th>Study Objective</th>
                          <th>Actions</th>
                        </tr>
                      </thead>
                      <tbody>
                      @foreach($proposals as $propkey => $prop)
                        @if(in_array(Auth::user()->id, explode(',', $prop->team_member_dd)))
                          @if($prop->status_id = 23)
                          <tr>
                            <td>{{ ++$propkey }}</td>
                            <td>{{ $prop->scheme_name }}</td>
                            <td>{{ department_name($prop->dept_id) }}</td>
                            <td width="23%">{{ (!is_null($prop->scheme_objective) ? $prop->scheme_objective : '-') }}</td>
                            <td>
                              <a href="{{ route('evaldd.proposal_detail',$prop->draft_id) }}" class="btn btn-xs btn-info">View</a>
                              <a href="javascript:void(0)" onclick="show_prop_status({{$prop->draft_id}})" class="btn btn-xs btn-info">Activity Status</a>
                              @if(auth()->user()->role == 15)
                              <a href="{{ route('stages.create', ['draft_id' => $prop->draft_id]) }}" class="btn btn-xs btn-info">Add Stage</a>
                              @endif
                            </td>
                          </tr>
                          @endif
                        @endif
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

<div class="modal fade" id="show_props" style="display: none;" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content"> <!-- style="width: 290%;margin-left: -90%;"> -->
      <div class="modal-header">
        <h4 class="modal-title">Change Status</h4>
        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="container-fluid" id="eval_activity_log_form">
          <!-- <form id="eval_activity_log_form" method="post" onsubmit="return fn_status_submit()"> -->
            <input type="hidden" name="study_id" id="study_id_of_evaluation">
            <div class="row form-group">
              @for($t=1;$t<=$count_columns;$t++)
              <div class="col-xl-3" id="the_col_{{$t}}">
                @php $y=0; $sam=0; $rfh=0; $dos=0; $fsd=0; $fs=0; $sa=0; $rw=0; $sf=0; $dec=0; $ecc=0; $for=0; $pub=0; $ir=0; @endphp
              @foreach($the_arr[$t] as $key => $val)
              <div class="row" style="margin:3px 0">
                <div class="col-xl-12">
                  @if($val['group_id'] == 1)
                    @if($y == 0)
                      <span style="font-size:20px;font-weight:bolder;margin-left:-10px;">Understanding of Scheme</span>
                      <br>
                      @php $y++; @endphp
                    @endif
                  @endif
                  @if($val['group_id'] == 2)
                    @if($ir == 0)
                      <span style="font-size:20px;font-weight:bolder;margin-left:-10px;">Sampling Design</span>
                      <br>
                      @php $ir++; @endphp
                    @endif
                  @endif
                  @if($val['group_id'] == 3)
                    @if($sam == 0)
                      <span style="font-size:20px;font-weight:bolder;margin-left:-10px;">Updation in Sampling Design</span>
                      <br>
                      @php $sam++; @endphp
                    @endif
                  @endif
                  @if($val['group_id'] == 4)
                    @if($rfh == 0)
                      <span style="font-size:20px;font-weight:bolder;margin-left:-10px;">Pilot Testing</span>
                      <br>
                      @php $rfh++; @endphp
                    @endif
                  @endif
                  @if($val['group_id'] == 5)
                    @if($dos == 0)
                      <span style="font-size:20px;font-weight:bolder;margin-left:-10px;">Finalization of Sampling Design</span>
                      <br>
                      @php $dos++; @endphp
                    @endif
                  @endif
                  @if($val['group_id'] == 6)
                    @if($fsd == 0)
                      <span style="font-size:20px;font-weight:bolder;margin-left:-10px;display:block;">Digitization of Schedule</span>
                      <!-- <br> -->
                      @php $fsd++; @endphp
                    @endif
                  @endif
                  @if($val['group_id'] == 7)
                    @if($fs == 0)
                      <span style="font-size:20px;font-weight:bolder;margin-left:-10px;display:block;">Field Survey</span>
                      <!-- <br> -->
                      @php $fs++; @endphp
                    @endif
                  @endif
                  @if($val['group_id'] == 8)
                    @if($sa == 0)
                      <span style="font-size:20px;font-weight:bolder;margin-left:-10px;display:block;">Statistical Analysis</span>
                      <!-- <br> -->
                      @php $sa++; @endphp
                    @endif
                  @endif
                  @if($val['group_id'] == 9)
                    @if($rw == 0)
                      <span style="font-size:20px;font-weight:bolder;margin-left:-10px;display:block;">Report Writing</span>
                      <!-- <br> -->
                      @php $rw++; @endphp
                    @endif
                  @endif
                  @if($val['group_id'] == 10)
                    @if($ir == 0)
                      <span style="font-size:20px;font-weight:bolder;margin-left:-10px;display:block;">DEC Meeting</span>
                      <!-- <br> -->
                      @php $ir++; @endphp
                    @endif
                  @endif
                  @if($val['group_id'] == 11)
                    @if($dec == 0)
                      <span style="font-size:20px;font-weight:bolder;margin-left:-10px;display:block;">ECC Meeting</span>
                      <!-- <br> -->
                      @php $dec++; @endphp
                    @endif
                  @endif
                  @if($val['group_id'] == 12)
                    @if($pub == 0)
                      <span style="font-size:20px;font-weight:bolder;margin-left:-10px;display:block;">Publication</span>
                      <!-- <br> -->
                      @php $pub++; @endphp
                    @endif
                  @endif
                  <span style="display:block">
                  <input type="radio" class="show_status_input" id="show_status_id_{{ $val['id'] }}_{{ str_replace(' ','_',$val['activity_name']) }}" name="status_id" value="{{ $val['id'] }}"> <span class="show_activity_name_span" id="show_activity_name_{{ $val['id'] }}"> {{ $val['activity_name'] }} </span></span>
                </div>
              </div>
              @endforeach
              </div>
              @endfor
            </div>
            <hr>
            <div class="row" id="status_btn_row">
              <div class="col-xl-5"></div>
              <div class="col-xl-1" style="text-align:right;" id="status_close_btn">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
              </div>
              <div class="col-xl-1 status_submit_btn">
                <button type="button" onclick="fn_status_submit('1')" id="approve_status_btn" value="1" class="btn btn-primary submit_btn_meeting">Approve</button>
              </div>
              <div class="col-xl-1 status_submit_btn">
                <button type="button" onclick="fn_status_submit('2')" id="reject_status_btn" value="2" class="btn btn-danger submit_btn_meeting">Reject</button>
              </div>
              <div class="col-xl-4"></div>
            </div>
          <!-- </form> -->
        </div>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>


 <!--end::Content-->
@endsection
<script type="text/javascript" src="{{ asset('plugins/jquery/jquery.js') }}"></script>
<script type="text/javascript">
  $(document).ready(function () {
    $('.numberonly').keypress(function (e) {
      var charCode = (e.which) ? e.which : event.keyCode;
      if (String.fromCharCode(charCode).match(/[^0-9]/g))
        return false;
      });
    $.ajaxSetup({
      headers : {
        'X-CSRF-TOKEN':$("meta[name='csrf-token']").attr('content')
      }
    });
  });

  function show_prop_status(id) {
    $.ajax({
      type:'post',
      dataType:'json',
      url:"{{ route('evaldd.get_prop_status') }}",
      data:{'id':id},
      beforeSend:function() {
        $(".show_status_input").removeAttr('checked');
        $(".show_activity_name_span").css({'font-weight':'normal','background-color':'#FFFFFF', 'margin-left':'5px', 'margin-top':'-5px'});
        $("#show_props .show_status_input").prop('checked',false);
      },
      complete:function() {
        $("#show_props .show_status_input").removeAttr('checked');
      },
      success:function(response) {
        if(response.approval_rejected != null) {
          $("#eval_activity_log_form #show_activity_name_"+response.approval_rejected).css({"font-weight":"bolder","background-color":"red"});
        }
        if(response.approved != null) {
          $("#eval_activity_log_form #show_activity_name_"+response.approved).css({"font-weight":"bolder","background-color":"lightgreen"});
          $("#eval_activity_log_form #show_activity_name_"+response.approved).parent().addClass('show_activity_name_parent_'+response.approved);
          $("#eval_activity_log_form .show_activity_name_parent_"+response.approved+' input').click();
        }
        if(response.pending_for_approval != null) {
          $("#eval_activity_log_form #show_activity_name_"+response.pending_for_approval).css({"font-weight":"bolder","background-color":"#CCCC00"});
        }
        $("#eval_activity_log_form #study_id_of_evaluation").val(response.id);
        $.each(response.eval_activities,function(key,val) {
          var activity_name_id_first = val.activity_name.replace(/ /g,"_");
          var activity_name_id = activity_name_id_first.replace('/',"_");
          $("#eval_activity_log_form #show_status_id_"+val.id+"_"+activity_name_id).removeAttr('checked');
          $("#eval_activity_log_form #show_activity_name_"+val.id).css({"font-weight":"normal","background-color":"#FFFFFF"});
          if(val.approval == 2 && val.approve_reject_by == 1) {
            $("#eval_activity_log_form #show_activity_name_"+val.id).css({"font-weight":"bolder","background-color":"red"});
          } else if(val.approval == 1 && val.approve_reject_by == 2) {
            $("#eval_activity_log_form #show_activity_name_"+val.id).css({"font-weight":"bolder","background-color":"#FFA500"});
          } else if(val.approval == 1 && val.approve_reject_by == 1) {
            $("#eval_activity_log_form #show_activity_name_"+val.id).css({"font-weight":"bolder","background-color":"lightgreen"});
          } else if(val.approval == 0 && val.approve_reject_by == 3 && val.last_study_id == 1) {
            $("#eval_activity_log_form #show_activity_name_"+val.id).css({"font-weight":"bolder","background-color":"#CCCC00"});
			if(response.approved == null) {
				$("#eval_activity_log_form #show_status_id_"+val.id+"_"+activity_name_id).click();
			}
          }
          if(response.approved != null) {
            $("#eval_activity_log_form #show_activity_name_"+response.approved).css({"font-weight":"bolder","background-color":"lightgreen"});
		  }
        });
        $.each(response.checked_activity,function(chk_key,chk_val) {
          if(chk_val.sub_activity_name == null) {
            var make_activity_name_first = chk_val.activity_name.replace(/ /g,"_");
            var make_activity_name = make_activity_name_first.replace('/','_');
          } else {
            var make_activity_name_first = chk_val.sub_activity_name.replace(/ /g,"_");
            var make_activity_name = make_activity_name_first.replace('/','_');
          }
          if(chk_val.approval !== 2 && chk_val.approve_reject_by !== 2 && chk_val.last_study_id == 1) {
            $("#eval_activity_log_form #show_status_id_"+chk_val.id+"_"+make_activity_name).click();
          }
        });
      },
      error:function() {
        console.log('evalro ajax error');
      }
    });
    $("#show_props").modal('show');
  }

  function fn_status_submit(thenum) {
    var study_id_is = $("#eval_activity_log_form #study_id_of_evaluation").val();
    var status_id_is = $("#eval_activity_log_form input[name='status_id']:checked").val();
    $.ajax({
      type:'post',
      dataType:'json',
      url:"{{ route('evaldd.savestatus') }}",
      data:{'study_id':study_id_is,'status_id':status_id_is,'thenum':thenum},
      success:function(response) {
        location.reload();
      },
      error:function(){
        console.log('eval activity log form submit ajax error');
      }
    });
  }


$(document).ready(function(){
  
  $('#approve_status_btn').click(function(){
    
    $.confirm({
      'title'   : 'Delete Confirmation',
      'message' : 'You are about to delete this item. <br />It cannot be restored at a later time! Continue?',
      'buttons' : {
        'Yes' : {
          'class' : 'blue',
          'action': function(){

            alert('yes');

          }
        },
        'No'  : {
          'class' : 'gray',
          'action': function(){
            alert('no');
          }  // Nothing to do in this case. You can as well omit the action property.
        }
      }
    });
    
  });
  
});


  // function fn_status_submit() {
  //   var study_id_is = $("#eval_activity_log_form #study_id_of_evaluation").val();
  //   var status_id_is = $("#eval_activity_log_form input[name='status_id']:checked").val();
  //   $.ajax({
  //     type:'post',
  //     dataType:'json',
  //     url:"{{ route('evaldd.savestatus') }}",
  //     data:{'_token':"{{ csrf_token() }}",'study_id':study_id_is,'status_id':status_id_is},
  //     success:function(response) {
  //       if(response.approval == 0) {
  //         if(confirm('Are you sure change scheme status ?')) {

  //           $.ajax({
  //             type:'post',
  //             dataType:'json',
  //             url:"{{ route('evaldd.savestatustwo') }}",
  //             data:{'_token':"{{ csrf_token() }}",'study_id':study_id_is,'status_id':status_id_is},
  //             success:function(response) {
  //               // console.log(JSON.stringify(response));
  //               window.location.reload();
  //             },
  //             error:function() {
  //               console.log('save status two ajax error');
  //             }
  //           });

  //         }
  //       } else {
  //         window.location.reload();
  //       }
  //     },
  //     error:function(){
  //       console.log('eval activity log form submit ajax error');
  //     }
  //   });
  // }

  $(document).ready(function(){
    var winwidth = $(window).outerWidth();

    $(".status_submit_btn").eq(0).css('text-align','center');

    if(window.matchMedia('(max-width: 1024px)').matches) {
      $("#show_props .modal-content").css('margin-left','-'+25+'%');
      var winwidthtwo = winwidth - 200;
      $("#show_props .modal-content").width(winwidthtwo);
      $("#status_close_btn button").css('margin','0px 20px 0px 40%');
      var close_btn = $("#status_close_btn").html();
      var submit_btn = $(".status_submit_btn").html();
      $("#status_close_btn").remove();
      $(".status_submit_btn").remove();
      $("#status_btn_row").html(close_btn+"  "+submit_btn);
    } else if(window.matchMedia('(max-width: 1280px)').matches) {
      $("#show_props .modal-content").css('margin-left','-'+60+'%');
      var winwidthtwo = winwidth - 100;
      $("#show_props .modal-content").width(winwidthtwo);
    } else if(window.matchMedia('(max-width: 1366px)').matches) {
      $("#show_props .modal-content").css('margin-left','-'+60+'%');
      var winwidthtwo = winwidth - 150;
      $("#show_props .modal-content").width(winwidthtwo);
    } else if(window.matchMedia('(max-width: 1600px)').matches) {
      $("#show_props .modal-content").css('margin-left','-'+90+'%');
      var winwidthtwo = winwidth - 150;
      $("#show_props .modal-content").width(winwidthtwo);
    } else if(window.matchMedia('(max-width: 1920px)').matches) {      
      $("#show_props .modal-content").css('margin-left','-'+90+'%');
      var winwidthtwo = winwidth - 150;
      $("#show_props .modal-content").width(winwidthtwo);
    }

  });

</script>
