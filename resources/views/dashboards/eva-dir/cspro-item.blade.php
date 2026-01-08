@extends('dashboards.eva-dir.layouts.evaldir-dash-layout')
@section('title','Csentry Details')
@section('content')
  <!-- Fixed CSS Links -->
  <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css')}}">
  
  {{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"> --}}

  <style>
    .mb-0 > a {
     display: block;
     position: relative;
   }
   .mb-0 > a:after {
     content: "\f078"; /* fa-chevron-down */
     font-family: 'FontAwesome';
     position: absolute;
     right: 0;
   }
   .mb-0 > a[aria-expanded="true"]:after {
     content: "\f077"; /* fa-chevron-up */
   }
 </style>
 @php
    $i = 1;
    //$labelTitle = DB::table('imaster.item_labels')->select('record_name','title')->distinct()->get();
@endphp

    @if (session()->has('success'))
            <div class="alert alert-success">
                {{ session()->get('success') }}
            </div>
    @endif
  
<div id="accordion">
@if(!is_null($groupedCases) && is_countable($groupedCases) && $groupedCases->count() > 0)
    @foreach ($groupedCases as $title => $cases)
        <div class="card">
            <div class="card-header" id="heading-{{$i}}">
                <h5 class="mb-0">
                    <a role="button" data-toggle="collapse" href="#collapse-{{$i}}" 
                        aria-expanded="true" aria-controls="collapse-{{$i}}">
                        {{$i}}. {{$title}}
                    </a>
                </h5>
            </div>
            @php
            //dd($cases);
            @endphp
            <div id="collapse-{{$i}}" class="collapse show" data-parent="#accordion" aria-labelledby="heading-{{$i}}">
                <div class="card-body">
                    @if($cases->count() > 0)
                        <div id="accordion-{{$i}}">
                            @foreach($cases as $index => $case)
                                @php  $caseIndex = $i . '-' . ($index + 1); @endphp
                                <div class="card">
                                    <div class="card-header" id="heading-{{$caseIndex}}">
                                        <h5 class="mb-0">
                                            <a class="collapsed" role="button" data-toggle="collapse" 
                                                href="#collapse-{{$caseIndex}}" aria-expanded="false" 
                                                aria-controls="collapse-{{$caseIndex}}">
                                                Person Id: {{ $case->person_id }} | Form Number: {{ $case->form_number }}
                                            </a>
                                        </h5>
                                    </div>

                                    <div id="collapse-{{$caseIndex}}" class="collapse" data-parent="#accordion-{{$i}}" aria-labelledby="heading-{{$caseIndex}}">
                                        <div class="card-body">
                                            <div id="accordion-{{$caseIndex}}">
                                                @php 
                                                    $questionCounter = 1;
                                                @endphp
                                                 @foreach($case->groupedAnswers as $recordName => $answers)
                                                {{-- @foreach($case->answers as $category => $questions) --}}
                                                    @php $questionIndex = $caseIndex . '-' . $questionCounter; @endphp
                                                    <div class="card">
                                                        <div class="card-header" id="heading-{{$questionIndex}}">
                                                            <h5 class="mb-0">
                                                                <a class="collapsed" role="button" 
                                                                    data-toggle="collapse" href="#collapse-{{$questionIndex}}" 
                                                                    aria-expanded="false" aria-controls="collapse-{{$questionIndex}}">
                                                                    {{ ucfirst($recordName) }}  <!-- Show the category name -->
                                                                </a>
                                                            </h5>
                                                        </div>
                                                        <div id="collapse-{{$questionIndex}}" class="collapse" data-parent="#accordion-{{$caseIndex}}" aria-labelledby="heading-{{$questionIndex}}">
                                                            <div class="card-body table-responsive">
                                                                <table class="table table-bordered">
                                                                    <thead>
                                                                        <tr>
                                                                            <th>SrNo.</th>
                                                                            <th>પ્રશ્ન (Question)</th>
                                                                            <th>ઉત્તર (Answer)</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        @foreach($answers as $answer)
                                                                            <tr>
                                                                                <td>{{ $loop->iteration }}</td>
                                                                                <td><strong>{{ $answer->question_label }}</strong></td>
                                                                                <td>{{ $answer->answer }}</td>
                                                                            </tr>
                                                                        @endforeach
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    @php $questionCounter++; @endphp
                                                @endforeach
                                            </div> <!-- Inner accordion end -->
                                            
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>  
                    @else
                        No record Found!
                    @endif      
                </div>
            </div>
        </div>
        @php $i++; @endphp
    @endforeach
 @else
    <div class="text-center"><img src="{{asset('img/download.jfif')}}" style="position: absolute; margin-top: 15%;"></div>
@endif
</div> 
<script src="{{asset('js/jquery.min.js')}}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.0.0/js/bootstrap.min.js"></script>
@endsection