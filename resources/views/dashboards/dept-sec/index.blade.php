@extends('dashboards.dept-sec.layouts.deptsec-dash-layout')
@section('title','Dashboard')

@section('content')

<h1 class="text-center">
    {{ $dept_name }}
</h1>
<br />

<!-- content Header -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-12">
                <!-- <h1 class="text-center"> Directorate of Evaluation</h1> -->
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
                    <div class="card-body">
                        <div class="table-responsive" style="margin-top:20px">
                            <table class="table table-bordered table-hover table-stripped show-datatable" id="scheme_list_table">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Scheme Name</th>
                                        <th>Nodal Officer</th>
                                        <th>Status</th>
                                        <th>DD Name</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                	@if(!empty($proposals))
                                	@foreach($proposals as $akey => $aval)
                                		<tr>
                                			<td>{{ ++$akey }}</td>
                                			<td>{{ $aval['scheme_name'] }}</td>
                                			<td>{{ $aval['nodal_name'] }}</td>
                                			<td>Ongoing</td>
                                			<td>{{ $aval['convener_name'] }}</td>
                                            @php $id = $aval['draft_id']; $sid = $aval['send_id'];  @endphp
                                            <td><a href="{{ route('deptsec.proposal_detail',[$id,$sid]) }}" class="btn btn-xs btn-info">View</a></td>
                                		</tr>
                                	@endforeach
                                	@endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
