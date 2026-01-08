@extends('dashboards.implementations.layouts.ia-dash-layout')
@section('title','Request Proposal')
<style>
  .borderless {
    border:0px !important;
  }
  .main-footer {
    display: none;
  }
</style>
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
          <h5 class="text-dark font-weight-bold my-1 mr-5">
            Request Proposal Evaluation
          </h5>
        <!--end::Page Title-->                  
        </div>
      <!--end::Page Heading-->
      </div>
    <!--end::Info-->
    </div>
  </div>
<!--end::Subheader-->
<!-- </div> -->
<!--end::Content-->    
<!--begin::Entry-->
<div class="d-flex flex-column-fluid">
  <!--begin::Container-->
  <div class=" container ">
    <div class="card card-custom card-transparent">
      <div class="card-body p-0">
        <!--begin: Wizard-->
        <div class="wizard wizard-4" id="kt_wizard_v4" data-wizard-state="step-first" data-wizard-clickable="true">
          <div class="card card-custom card-shadowless rounded-top-0">
            <div class="card-body p-10">

              <div class="row">

                  @if($errors->any())
                      <div class="alert alert-danger">
                          <ul>
                              @foreach ($errors->all() as $error)
                                  <li>{{ $error }}</li>
                              @endforeach
                          </ul>
                      </div>
                  @endif

                <div class="col-xl-12 col-xxl-12">
                  <form method="post" action="{{ route('create-proposal') }}">
                    @csrf

                    <div class="pb-5" data-wizard-type="step-content" data-wizard-state="current">
                      <!--begin::Input-->
                      <div class="row">
                          <div class="col-xl-12">
                            <div class="form-group">
                              <label> Scheme * :</label>
                              <select name="scheme_id" class="form-control" onchange="fn_send_to_edit(this.value)">
                                <option value="">Select Scheme</option>
                                @foreach($schemes as $key=>$value)
                                  <option value="{{ $value->scheme_id }}">{{ $value->scheme_name }}</option>
                                @endforeach
                              </select>
                            </div>
                          <div class="col-xl-12" style="display:none">
                            <div class="form-group">
                              <label> Priority * :</label>
                              <select name="priority" class="form-control">
                                <option value="">Select Priority</option>
                                <option value="1" selected>Top Most</option>
                                <option value="2">Top</option>
                                <option value="3">Moderate</option>
                              </select>
                            </div>
                          </div>
                          <div class="col-xl-12" style="display:none">
                            <div class="form-group">
                              <label> Time duration for completion of the evalution study * :</label>
                              <select name="timeforeval" class="form-control">
                                <option value="">Select Duration</option>
                                <option value="3" selected>Within 3 months</option>
                                <option value="4-6">4 to 6 months</option>
                                <option value="7-9">7 to 9 months</option>
                              </select>
                            </div>
                          </div>
                          <div class="col-xl-12">
                            <div class="row">
                              <div class="col-xl-10"></div>
                              <div class="col-xl-1">
                                <div class="form-group">
                                  <button type="submit" class="btn btn-success" style="float:right">View</button>
                                </div>
                              </div>
                              <div class="col-xl-1">
                                <div class="form-group">
                                  <a id="edit_btn" class="btn btn-info">Edit</a>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                  </form>
                </div>
              </div>
            </div> <!-- card-body -->
          </div> <!-- card -->
        </div> <!-- wizard -->
      </div> <!-- card-body -->
    </div>
  </div>
</div>
</div>
@endsection
{{-- <script src="{{asset('js/3.2.1.jquery.min.js')}}"></script> --}}
<script src="{{asset('js/jquery.min.js')}}"></script>
<script type="text/javascript">
  function fn_send_to_edit(the_val) {
    // var getUrl = window.location;
    var getUrl = "{{ URL::current() }}";
    var baseUrl = getUrl.replace('/request-proposal','/');
    var theBaseUrl = baseUrl+'scheme_edit/'+the_val;
    // var baseUrl = getUrl .protocol + "//" + getUrl.host + "/scheme_edit/" + the_val; //+ getUrl.pathname.split('/')[1];
    // alert(baseUrl);
    $("#edit_btn").attr('href',theBaseUrl);
  }
</script>


