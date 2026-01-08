@extends('dashboards.eva-dd.layouts.evaldd-dash-layout')
@section('title','ODK Project List - Eval D.D')

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
                <h5 class="text-dark font-weight-bold my-1 mr-5">ODK Project Form List</h5>
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
            <div class=" container ">
            <!--begin::Card-->
            <div class="card card-custom gutter-b">
                <div class="card-body">
                    <table class="table table-bordered table-hover" id="kt_datatable" style="margin-top: 13px !important">
                        <thead>
                            <tr>
                                <th>Form Name</th>
                                <th>ID and Version</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(!empty($form_details) && $form_details->count())
                                @foreach ($form_details as $project)
                                    <tr>
                                        <td>{{ $project->name }}</td>
                                        <td>{{ $project->xml_form_id }}</td>
                                        <td> <a href="{{ route('evaldd.user_list',['project_id'=>$project->project_id,'form_id' =>$project->xml_form_id]) }}" class="btn btn-xs btn-info">User List</a></td>
                                    </tr>
                                @endforeach
                            @else
                                <tr><td colspan="10">There are no data.</td></tr>
                            @endif
                        </tbody>
                </table> <!--end: Datatable-->
                </div>
            </div><!--end::Card-->
            </div><!--end::Container-->
        </div><!--end::Entry-->
    </div><!--end::Content-->
@endsection
<script type="text/javascript">
function form_detail(id){
    alert(id);
    $.ajax({
        url: "{{ url('evaldd/odk_form_detail') }}",
        method: "POST",
        data: {id:id},
        success: function (result) {
          //var data = $.parseJSON(result);
        //   $('#enum_list').html('')
        //   $('#enum_list').append(result);
        
        }
    });
  }
</script>