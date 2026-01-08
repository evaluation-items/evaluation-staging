
@extends(Auth::user()->role_manage == 2 ? 'dashboards.eva-dir.layouts.evaldir-dash-layout' : 'dashboards.eva-dd.layouts.evaldd-dash-layout')
@section('title','Project Library List')
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
                     {{ __('message.evaluation_report_library_list') }}       	            
                    </h5>               
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
                  
                  <a href="#myModal" class="btn btn-primary font-weight-bold text-uppercase float-left"data-bs-toggle="modal"> {{ __('message.summary_report') }}</a>
                  <!--begin::Card-->
                  <div class="card card-custom gutter-b" style="margin-top: 30px;">
                    <div class="row">
                      <div class="card-body">
                        <div class="col-lg-12">
                          {{-- <div class="custom_filter"> --}}
                              <div class="col-md-4 form-group">
                                  <label for="year_count">{{ __('message.year') }}</label>
                                  <select class="form-control" name="year_count" id="year_count">
                                    <option value="">{{ __('message.select_year') }}</option>
                                    <option value="1">{{ __('message.all') }}</option>
                                    @foreach ($year as $item)
                                        <option value="{{$item}}">{{$item}} ({{countLibrary($item)}})</option>
                                    @endforeach
                                  </select>
                              </div>
                          {{-- </div> --}}
                          <table class="table table-bordered table-striped dataTable dtr-inline" id="example1">
                            <thead>
                                  <tr>
                                      <th>{{ __('message.no') }}</th>
                                      <th>{{ __('message.name') }}</th>
                                      <th>{{ __('message.year') }}</th>
                                      <th>{{ __('message.department_name') }}</th>
                                      <th>{{ __('message.organization_name') }}</th>
                                      <th>{{ __('message.actions') }}</th>
                                      {{-- <th>ID</th>
                                      <th>Name</th>
                                      <th>Year</th>
                                      <th>Department Name</th>
                                      <th>Organization Name</th>
                                      <th>Action</th> --}}
                                  </tr>
                              <tbody>
                                @php $i =1; @endphp
                                @if ($project_list->count() > 0) 
                                @foreach ($project_list as  $key => $items)
                                  @php
                                    $file =  (!is_null($items->upload_file)) ? explode(',', $items->upload_file) : null;
                                    $rand_val =  (!is_null($items->rand_val)) ? explode(',', $items->rand_val) : null;
                                  @endphp
                                <tr>
                                    <td>{{$i++}}</td>
                                    <td>{{$items->study_name}}</td>
                                    <td width="20%" class="text-center">{{$items->year}}</td>
                                    <td width="23%">{{department_name($items->dept_id)}}</td>
                                    <td>{{$items->org_name}}</td>
                                    <td>
                                      @if(!empty($file) && count($file) > 0)
                                      @foreach($file as $kgrs => $files)
                                          @if(isset($rand_val[$kgrs]))
                                              <a href="{{ route('get_the_document', [Crypt::encrypt($rand_val[$kgrs]), $files]) }}" target="_blank" title="{{ $files }}">
                                                  <i class="fas fa-file-pdf fa-2x" style="color:red;"></i>
                                              </a>
                                          @endif
                                      @endforeach
                                      @endif
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
        <div id="myModal" class="modal fade">
          <div class="modal-dialog" style="">
            <div class="modal-content">
              <div class="modal-header flex-column" style="width: auto;height: 62px;">
                <h4 class="modal-title">{{ __('message.summary_report') }}</h4>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close" style="padding-top: 0%;">
                  <span aria-hidden="true">×</span>
                </button>
              </div>
              <div class="modal-body">
                <div class="form-group">
                  <label for="from-year">{{ __('message.from_year') }}</label>
                  <select class="form-control" name="from-year" id="from-year">
                    <option value="">{{ __('message.select') }} {{ __('message.from_year') }}</option>
                    {{-- <option value="1">All</option> --}}
                    @foreach ($year as $item)
                      @php
                        list($from, $to) = explode('-', $item);
                      @endphp
                        <option value="{{$from}}">{{$from}}</option>
                    @endforeach
                  </select>
                </div>
                <div class="form-group">
                  <label for="year">{{ __('message.to_year') }}</label>
                  <select class="form-control" name="year" id="year" disabled>
                    <option value="">{{ __('message.select') }} {{ __('message.to_year') }}</option>
                    {{-- <option value="1">All</option> --}}
                    @foreach ($year as $item)
                      @php
                        list($from, $to) = explode('-', $item);
                        $toYear = (int) ('20' . $to);
                      @endphp
                        <option value="{{$to}}">{{  $toYear}}</option>
                    @endforeach
                  </select>
                </div>
                <div class="summary-data">

                </div>
              </div>
              {{-- <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary">Done</button>
              </div> --}}
            </div>
          </div>
        </div>

@endsection
<script src="{{asset('js/jquery.min.js')}}"></script>
<script type="text/javascript">

var from_year = "";
var to_year = "";

$(document).on('change', '#from-year', function () {
    from_year = $(this).val();
    if (from_year != "") {
        $('#year').removeAttr('disabled');
    } else {
        $('#year').attr('disabled', 'disabled');
    }
    updateYear();
});

$(document).on('change', '#year', function () {
    to_year = $(this).val();
    updateYear();
});

function updateYear() {
    if (from_year != "" && to_year != "") {
        const url = "{{ route('summary-digital-item') }}";
          $.ajax({
            type: 'POST',
        //    dataType: 'json',
            url: url,
            data:{from_year:from_year,to_year:to_year},
            headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
              success: function (response) {
              
                $('.summary-data').append(response);
                $('.modal-dialog').css('max-width','990px');
                $('#year').attr('disabled','disabled');
                $('#from-year').attr('disabled','disabled');
               
              },
              error: function () {
                  console.log('Error: Unable to delete department.');
              }
          });
            
    }
}
$('.printData').on('click', function(){
    window.print();
});
</script>