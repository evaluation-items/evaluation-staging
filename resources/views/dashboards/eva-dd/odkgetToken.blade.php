@extends('dashboards.eva-dd.layouts.evaldd-dash-layout')
@section('title','ODK List - Eval D.D')

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
                <h5 class="text-dark font-weight-bold my-1 mr-5">ODK List</h5>
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
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
                    <i class="fas fa-link"></i> Mapping Projects 
                    </button>
                    <!-- Model -->
                    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <form method="post" action="javascript:void(0)"><!-- method="post" action="{{route('evaldd.odkStudyStore')}}"> -->
                            @csrf
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">Mapping</h5>
                                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                            <i aria-hidden="true" class="fas fa-times"></i>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="col-lg-12">
                                                    <label> Project Name :</label>
                                                    <select class="form-control" name="study_id" id="study_id">
                                                        <option>-- select Project-- </option>
                                                        @foreach ($odk_projects_list as $project)
                                                        <option value="{{ $project->project_id}}" name="study_id" id="study_id">{{ $project->project_name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-lg-12">
                                                    <label> Scheme Name :</label>
                                                    <select class="form-control" name="odk_form_id" id="odk_form_id">
                                                        <option>-- select Project-- </option>
                                                        @foreach($schemes as $scheme)
                                                            <option value="{{$scheme->scheme_id}}" name="odk_form_id" id="odk_form_id">{{ $scheme->scheme_name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-info font-weight-bold" data-bs-dismiss="modal"> <i class="fas fa-times"></i> Close</button>
                                        <!-- <button type="button" class="btn btn-success font-weight-bold">Save</button> -->
                                        <button type="button" id="store_odk" class="btn btn-primary" onclick="odkmap(this.value,'{{ $project['name']}}','{{$scheme->scheme_id}}')">
                                        <i class="fab fa-wpforms"></i>  Save
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div><!-- End Modal -->
                    <!--begin: Datatable-->
                    <table class="table table-bordered table-hover" id="kt_datatable" style="margin-top: 13px !important">
                        <thead>
                            <tr>
                                <!-- <th>Study ID</th> -->
                                <th>Scheme Name</th>
                                <th>Project</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            {{-- @if(!empty($mapped_project) && $mapped_project->count()) --}}
                            @if(!empty($odk_projects_list) && $odk_projects_list->count())
                                <?php $a=1; ?>
                                {{-- @foreach ($mapped_project as $scheme) --}}
                                @foreach ($odk_projects_list as $scheme)
                                    <tr value="{{$scheme->scheme_id }}">
                                        <!-- <td name="study_id" value="{{$scheme->scheme_id }}" id="study_id"></td>  -->
                                        <td>{{ $scheme->scheme_name }}</td>
                                        <td>{{ $scheme->project_name }}</td>
                                        <td>    
                                           <a class="btn btn-primary" href="{{ route('evaldd.odk_project_form',['id' => $scheme->project_id])}}">Form Details</a>
                                        </td>
                                    </tr>
                                    <?php $a++; ?>
                                @endforeach
                            @else
                                <tr><td colspan="10">There are no data.</td></tr>
                            @endif
                        </tbody>
                      
                </table> <!--end: Datatable-->
                </div>
                <!--  Forms Modal -->
                <!-- Model -->
                <div class="modal fade" id="#modal-lg" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <form method="post" action="javascript:void(0)"><!-- method="post" action="{{route('evaldd.odkStudyStore')}}"> -->
                        @csrf
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Forms Data</h5>
                                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                        <i aria-hidden="true" class="fas fa-times"></i>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="col-lg-12">
                                                <label> Form Name :</label>
                                                
                                            </div>
                                            <div class="col-lg-12">
                                                <label> Target :</label>
                                                <input type="numnber" value="">
                                                <!-- <select class="form-control" name="odk_form_id" id="odk_form_id">
                                                    <option>-- select Project-- </option>
                                                    @foreach($schemes as $scheme)
                                                        <option value="{{$scheme->scheme_id}}" name="odk_form_id" id="odk_form_id">{{ $scheme->scheme_name }}</option>
                                                    @endforeach
                                                </select> -->
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-info font-weight-bold" data-bs-dismiss="modal"> <i class="fas fa-times"></i> Close</button>
                                    <!-- <button type="button" class="btn btn-success font-weight-bold">Save</button> -->
                                    <button type="button" id="" onclick="#" class="btn btn-primary"><i class="fas fa-save"></i> Save</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div><!-- End Modal -->
                <!-- End Forms Modal -->
            </div><!--end::Card-->
            </div><!--end::Container-->
        </div><!--end::Entry-->
    </div><!--end::Content-->
@endsection
<script>
    
    function get_form(project_id){
        alert(project_id);
//        return false;
        $.ajax({
            type: "post",
            // url: "{{ URL('forms')}}/project_id",
            url : "{{route('evaldd.odk_project_form')}}",
            data: { project_id:project_id},
            dataType: "json",
            success: function(result){
                console.log(result);
            }
        });
    } 

    function odkmap(a){
        var odk_form_id=  $('#odk_form_id_'+a).val();
        var study_id = $('#study_id_'+a).val();
        var scheme_name = $('#scheme_name_'+a).val();
        var project_name = $('#project_name').val();
        var odk_form = $('#odk_form_id :selected').val();
        alert(odk_form);
//        return false;
        $.ajax({
            type: "POST",
            url: "{{ route('evaldd.odkStudyStore')}}",
            data: { study_id:study_id, odk_form_id:odk_form_id, scheme_name:scheme_name,project_name:project_name},
            dataType: "json",
            success: function(result){
            }
        });
    } 
</script>