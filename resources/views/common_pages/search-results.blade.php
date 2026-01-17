@extends('layouts.app')
@section('content')

<div class="row">
    <div class="col-lg-9 col-md-8 col-sm-12 left_col advisory-wrapper">
            <h4 class="page-title text-center">Search Results for "{{ $keyword }}"</h4>
            <p class="content-text">
                 @if(count($results) > 0)
                        <ul>
                            @foreach($results as $result)
                                <li>
                                    <p>
                                        {!! preg_replace(
                                            '/(' . preg_quote($keyword, '/') . ')/i',
                                            '<span style="background-color: yellow; font-weight: bold;">$1</span>',
                                            e($result['content'])
                                        ) !!}
                                    </p>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p>No results found.</p>
                    @endif
           </p>
    </div>
</div>

@endsection
