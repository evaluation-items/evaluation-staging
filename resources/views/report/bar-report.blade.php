
{{-- @extends(Auth::user()->role_manage == 2 ? 'dashboards.eva-dir.layouts.evaldir-dash-layout' : 'dashboards.eva-dd.layouts.evaldd-dash-layout') --}}
@extends(Auth::user()->role_manage == 2 ? 'dashboards.eva-dir.layouts.evaldir-dash-layout' : (Auth::user()->role_manage == 1 ? 'dashboards.gad-sec.layouts.gadsec-dash-layout' : 'dashboards.eva-dd.layouts.evaldd-dash-layout'))

@section('title','Detail Report')
@section('content')
<style>
    .container, .container-lg, .container-md, .container-sm, .container-xl {
      max-width: 100%;
      font-size: 14px;
      font-family: 'Source Sans Pro';
  }
  [hover-tooltip] {
  
  position: relative;
  cursor: default;
  
  &:hover {
    &::before {
      content: attr(hover-tooltip);
      font-size: 14px;
      text-align: center;
      position: absolute;
      display: block;
      left: 50%;
      min-width: 150px;
      max-width: 200px;
      bottom: calc(100% + #{10px});
      transform: translate(-50%);
      animation: fade-in 300ms ease;
      background: rgba(39, 39, 39, 1);
      border-radius: 4px;
      padding: 10px;
      color: #ffffff;
      z-index: 1;
    }
    
    &::after {
      content: '';
      position: absolute;
      display: block;
      left: 50%;
      width: 0;
      height: 0;
      bottom: calc(100% + #{10px - 4px});
      margin-left: - 6px/2;
      border: 1px solid black;
      border-color: rgba(39, 39, 39, 1) transparent transparent transparent;
      border-width: 4px 6px 0;
      animation: fade-in 300ms ease;
      z-index: 1;
    }
  }
}

@keyframes fade-in {
  from {
    opacity: 0;
  }
  to {
    opacity: 1;
  }
}

span{
  margin-left: 35%;
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
                  <div class="d-flex justify-content-center align-items-center">
                    <!--begin::Page Title-->
                    <h5 class="text-dark font-weight-bold">{{ __('message.detail_reports') }}</h5>
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
                  <div class="card-header flex-wrap py-3">
                    <div class="card-toolbar">
                      <h5>{{ __('message.list_of_detail_report')}}</h5>
                    </div>
                  </div>
                  @php $stages = __('message.chart_stage_scores'); @endphp

                  <div class="card-body col-md-12 table-responsive">
                    <!--begin: Datatable-->
                    <table class="table table-bordered table-striped dataTable dtr-inline" id="example1">
                     <thead>
                          <tr>
                              <th rowspan="2">{{ __('message.no') }}</th>
                              <th rowspan="2">{{ __('message.scheme_name') }}</th>
                              <th colspan="{{ count($stages) }}">{{ __('message.complete_delay_count') }}</th>
                              <th rowspan="2">{{ __('message.delay_count') }}</th>
                          </tr>
                          <tr>
                              @foreach($stages as $label => $score)
                                  <th>{{ $label }}/{{ $score }}</th>
                              @endforeach
                          </tr>
                      </thead>
                      <tbody>
                       @php $i = 1; @endphp
                        @foreach($proposal_list as $items)
                        @php
                            $item = StageCount($items->draft_id);
                            $get_count = $item['get_count'] ?? [];
                            $get_count_delay = $item['get_count_delay'] ?? [];
                            $final = array_merge($get_count, $get_count_delay);  
                            $remark_msg = Remarks($items->scheme_id);
                            $total_delay = 0;
                        @endphp
                        @if(!empty($final))
                            <tr>
                                <td>{{ $i++ }}</td>
                                <td>{{ SchemeName($items->scheme_id) }}</td>
                                @foreach ($final as $key => $count)
                                    @if (isset($final[$key.'_delay']))
                                    @php
                                      $delay = $final[$key.'_delay'];
                                      $total_delay += $delay;
                                    @endphp
                                        <td>
                                            @if($key == 'polot_study_date' && $remark_msg)
                                                {{ $count }} ({{  $delay  }})
                                                <span hover-tooltip="{{ $remark_msg }}" tooltip-position="top"><i class="fas fa-comment-dots" style="color: #3fa3dd;"></i>	
                                                </span>
                                            @else
                                                {{ $count }} ({{  $delay  }})
                                            @endif
                                        </td>
                                    @endif
                                @endforeach
                                <td>{{ $total_delay }}</td>
                            </tr>
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
<!--end::Content-->

@endsection
