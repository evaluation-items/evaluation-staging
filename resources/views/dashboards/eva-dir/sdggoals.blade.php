@extends('dashboards.eva-dir.layouts.evaldir-dash-layout')
@section('title','Dashboard - Dir. of Evaluation')
<!-- <script type="text/javascript" src="/plugins/datatables-jquery/jquery.dataTables.min.js"></script>
<link rel="stylesheet" type="text/css" href="/plugins/datatables-jquery/dataTables.min.css"> -->
@section('content')
<!-- content Header -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-12">
                <h1 class="text-center"> Directorate of Evaluation</h1>
            </div>
        </div>
    </div>
</div>
<!-- End content Header -->
<!-- Content section -->
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card card-default">
                    <div class="card-header">
                        <button type="button" class="btn btn-success" onclick="add_goal()">Add</button>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive" style="margin-top:20px">
                            <table class="table table-bordered table-hover table-stripped show-datatable" id="scheme_list_table">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Goal Name</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($sdggoals as $key=>$value)
                                        <tr>
                                            <td>{{ ++$key }}</td>
                                            <td>{{ $value->goal_name }}</td>
                                            <td> @if($value->status == 1) Active @else Inactive @endif </td>
                                            <td> <a href="javascript:void(0)" class="btn btn-xs btn-info" onclick="edit_goal({{ $value->goal_id }})">Edit</a> </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<!-- Goal Add start -->
<div class="modal fade" id="add_goal_modal" tabindex="-1" role="dialog" aria-labelledby="add_goal_modalLabel" aria-hidden="true" style="display:none;">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="add_goal_modalLabel">Add Goal</h5>
        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form method="post" action="{{ route('evaldir.add_goal') }}">
            @csrf
            <div class="form-group">
                <label>Goal Name</label>
                <input type="text" name="goal_name" class="form-control pattern" maxlength="100">
            </div>
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
      </div>
      <div class="modal-footer">
      </div>
    </div>
  </div>
</div>
<!-- Goal Add end -->

<!-- Goal Edit start -->
<div class="modal fade" id="edit_goal_modal" tabindex="-1" role="dialog" aria-labelledby="add_goal_modalLabel" aria-hidden="true" style="display:none;">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="add_goal_modalLabel">Modal title</h5>
        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form method="post" action="{{ route('evaldir.edit_goal') }}">
            @csrf
            <input type="hidden" name="goal_id" id="goal_id">
            <div class="form-group">
                <label>Goal Name</label>
                <input type="text" name="goal_name" id="goal_name" class="form-control pattern" maxlength="100">
            </div>
            <div class="form-group">
                <label>Status</label>
                <select class="form-control" name="status" id="goal_status">
                    <option value="">Select Status</option>
                    <option value="1">Active</option>
                    <option value="0">Inactive</option>
                </select>
            </div>
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
      </div>
      <div class="modal-footer">
      </div>
    </div>
  </div>
</div>
<!-- Goal Edit end -->

</section>
@endsection
{{-- <script src="{{asset('js/3.2.1.jquery.min.js')}}"></script> --}}
<script src="{{asset('js/jquery.min.js')}}"></script>
<script>
$(document).ready(function(){
    $('#the_convergence_btn').click(function(){
        var ktcontent = $("#kt_content").height();
        $(".content-wrapper").css('min-height',ktcontent+50);
    });
});

function add_goal() {
    $("#add_goal_modal").modal('show');
}

function edit_goal(goalid) {
    $("#edit_goal_modal #goal_id").val(goalid);
    $.ajax({
        type:'post',
        dataType:'json',
        url:"{{ route('evaldir.get_goal_data') }}",
        data:{'_token':"{{ csrf_token() }}",'goal_id':goalid},
        success:function(response) {
            $("#edit_goal_modal #goal_name").val(response.goal_name);
            $("#edit_goal_modal #goal_status").val(response.status);
        },
        error:function() {
            console.log('get goal data ajax error');
        }
    });
    $("#edit_goal_modal").modal('show');
}

</script>


