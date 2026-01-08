@extends('dashboards.eva-dir.layouts.evaldir-dash-layout')
@section('title','Returned Proposals')

@section('content')
<style>
  .content-wrapper{
    background: #ffdfba;
  }
</style>
@php
  use Illuminate\Support\Facades\Crypt;
@endphp
 <!--begin::Content-->
 <div class="content  d-flex flex-column flex-column-fluid" id="kt_content">
            <!--begin::Subheader-->
            <div class="subheader py-2 py-lg-6  subheader-solid " id="kt_subheader">
              <div class=" container-fluid  d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
                <!--begin::Info-->
                <div class="d-flex align-items-center flex-wrap mr-1">
                  <!--begin::Page Heading-->
                  <div class="d-flex align-items-center flex-wrap mr-5">
                    <!--begin::Page Title-->
                    <h5 class="text-dark font-weight-bold my-1 mr-5">Proposals Returned To GAD</h5>
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
              <div class="container">
                    <!--begin::Card-->
                    <div class="card card-custom gutter-b">
                        <div class="card-header flex-wrap py-3">
                          <div class="card-toolbar">
                            <h5>Proposals returned to GAD for queries or other actions.</h5>
                          </div>
                        </div>
                        <div class="card-body">
                          <div class="row">
                            @if(Session::has('frwd_success'))
                              <div class="text-red">
                                  
                              </div>
                            @endif
                          </div>
                          		<!--begin: Datatable-->
                                      <table class="table table-bordered table-striped dataTable dtr-inline" id="example1">
                                        <thead>
                                            <tr>
                                              <th width="8%">Sr No.</th>
                                              <th>Scheme Name</th>
                                              <th>Department Name</th>
                                              <th>Name of HOD</th>
                                              <th>Received date from GAD</th>
                                              <th>Returned Date</th>
                                              <th>Remark</th>
                                              <th>Actions</th>
                                            </tr>
                                          </thead>
                                          <tbody>
                                            @php $i=1; @endphp
                                          @foreach($returned_proposals as $prop)
                                            <tr>
                                              <td>{{  $i++; }}</td>
                                              <td>{{ proposal_name($prop->draft_id) }}</td>
                                              <td>{{ department_name($prop->dept_id) }}</td>
                                              <td>{{ hod_name($prop->draft_id) }}</td>
                                              <td>{{date('d-M-y',strtotime( $prop->created_at))}}</td>
                                              <td>{{(!empty($prop->return_eval_date)) ? date('d-M-y',strtotime( $prop->return_eval_date)) : '-'}}</td>
                                              <td width="23%">{{ $prop->remarks}}</td>
                                              <td width="10%">
                                                <a href="{{ route('schemes.proposal_detail',[Crypt::encrypt($prop->draft_id),Crypt::encrypt($prop->id)]) }}" class="btn btn-xs btn-info" style="display: inline-block">View</a>
                                              </td>
                                            </tr>
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
@endsection
{{-- <script src="{{asset('js/3.2.1.jquery.min.js')}}"></script> --}}
<script src="{{asset('js/jquery.min.js')}}"></script>
<script>
  $(document).ready(function(){
    $('.dataTable').DataTable({
          columnDefs: [
              { type: 'date', targets: [4,5] } 
          ]
      });
  })
</script>