@extends('layouts.app')
@section('content')

    <h2>Search Results for "{{ $keyword }}"</h2>

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

@endsection
