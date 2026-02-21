@extends('layouts.app')
@section('content')
@if(!is_null($menu_item))

    <div class="menu-item-pages">
        <h4>{{$menu_item->name}} </h4>
        <div class="description" style="margin-top: 1%;">{!! $menu_item->description !!}</div>
    </div>

@endif
@endsection