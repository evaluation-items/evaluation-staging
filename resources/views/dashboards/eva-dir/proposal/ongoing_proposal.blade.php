@extends('dashboards.eva-dir.layouts.evaldir-dash-layout')
@section('title','Ongoing Proposals')

@section('content')
<style>
  .content-wrapper{
    background: #ffffba;
  }
</style>
 <!--begin::Content-->
 <div class="content  d-flex flex-column flex-column-fluid" id="kt_content">
            <!--begin::Subheader-->
            <div class="subheader py-2 py-lg-6  subheader-solid " id="kt_subheader">
              <div class=" container-fluid  d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
                <!--begin::Info-->
                <div class="d-flex align-items-center flex-wrap mr-1">
                  <!--begin::Page Heading-->
                  <div class="d-flex text-center flex-wrap mr-5">
                    <!--begin::Page Title-->
                    <h5 class="text-dark font-weight-bold my-1 mr-5">{{ __('message.ongoing_proposals') }}</h5>
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

                  <div class="card card-custom gutter-b table-responsive">
                      <div class="card-header flex-wrap py-3">
                        <div class="card-toolbar">
                          <h5>{{ __('message.ongoing_gad_desc')}}</h5>
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
                                  <th width="8%">{{ __('message.no') }}</th>
                                  <th>{{ __('message.scheme_name') }}</th>
                                  <th>{{ __('message.assigned_date') }}</th>
                                  <th>{{ __('message.branch_name') }}</th>
                                  <th>{{ __('message.current_stage') }}</th>
                                  <th>{{ __('message.actions') }}</th>
                                  {{-- <th width="8%">Sr No.</th>
                                  <th>Scheme Name</th>
                                  <th>Assigned Date</th>
                                  <th>Branch Name</th>
                                  <th>Current Stage</th>
                                  <th>Actions</th> --}}
                                </tr>
                              </thead>
                                <tbody>
                                  @php $i=1; @endphp
                                  @foreach($ongoing_proposal as $prop)
                                    <tr>
                                    <td>{{  $i++}} </td>
                                    <td>{{ SchemeName($prop->scheme_id) }}</td> 
                                    <td>{{ !empty($prop->created_at) ?  date('d-M-y',strtotime($prop->created_at)) : '-'}}</td> 
                                    <td>{{ isset($prop->schemeSend->team_member_dd) ? branch_list($prop->schemeSend->team_member_dd) : '-' }}</td>
                                    <td>{{current_stages($prop->id)}}</td>
                                    <td width="20%">
                                      {{-- <a href="{{ route('schemes.proposal_detail',[$prop->draft_id,$prop->id]) }}" class="btn btn-xs btn-info" style="display: inline-block">View Scheme</a> --}}
                                    @if(App\Models\Stage::where('scheme_id',$prop->scheme_id)->latest()->first())
                                      <a href="{{ route('stages.show', ['stage' => $prop->scheme_id]) }}" class="btn btn-xs btn-info" style="display: inline-block">{{ __('message.detailed_stage') }}</a>
                                      @endif
                                    </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                          <!--end: Datatable-->
                      </div>
                    </div>
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
   $(document).ready(function() {
      $('.dataTable').DataTable({
          columnDefs: [
              { type: 'date', targets: 2 } 
          ]
      });
  });
</script>