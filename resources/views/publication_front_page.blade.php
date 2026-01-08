@extends('layouts.app')
@section('content')
<style>
.table>thead>tr>th {
    text-align: center;
    font-size: 14px;
     color: #000;
}
thead tr {
    font-weight: bold;
}
</style>
<div class="container">
	<div class="col-lg-9 col-md-8 col-sm-12 left_col">
		@if(count($list) > 0)
			<div class="box_wrap">
				<h4>{{ __('message.publications')}}</h4>
				<div class="publication-content">
					<ul>
						@foreach($list->unique('dept_id') as $item)
							<li><a href="{{ route('dept_publication', [$item->dept_id]) }}">{{ department_name($item->dept_id) }}</a></li>
						@endforeach
					</ul>
				</div>
			</div>
		@endif
	
		@if(count($dept_list) > 0)
			<div class="box_wrap">
				<h2>{{ __('message.publications')}}</h2>
				<div class="row">
					<div class="card-body publication">
						<div class="col-lg-12">
							<table class="table table-bordered table-striped dataTable dtr-inline" id="example1">
								<thead>
									<tr>
										<th>{{ __('message.no')}}</th>
										<th>{{ __('message.department_name')}}</th>
										<th>{{ __('message.publications')}} {{ __('message.name')}}</th>
										<th>{{ __('message.year')}}</th>
										{{-- <th>{{ __('message.document')}}</th> --}}
									</tr>
								</thead>
								<tbody>
									@php $i = 1; @endphp
									@foreach ($dept_list as $item_public)
										<tr>
											<td>{{ $i++ }}</td>
											<td>{{ department_name($item_public->dept_id) }}</td>
											<td>{{ $item_public->study_name ?? '-' }}</td>
											<td>{{ $item_public->year ?? '-' }}</td>
											{{-- <td>
												<a class="form-control" style="color:#084a84" 
												   href="{{ route('get_the_publication_document', [Crypt::encrypt($item_public->rand_val),$item_public->upload_file]) }}" 
												   target="_blank" 
												   title="{{ $item_public->upload_file }}">
													{{ __('message.view_document')}}
												</a>
											</td> --}}
										</tr>
									@endforeach
								</tbody>
							</table>
						</div> 
					</div>
				</div>
			</div>
		@endif
	</div>
	
</div>
@endsection