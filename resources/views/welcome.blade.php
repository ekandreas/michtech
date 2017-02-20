@extends('laravel-bulma-starter::layouts.bulma')

@section('content')
    Some content.
@endsection

@push('right-nav-menu')
    <a class="nav-item is-tab" href="{{ url('/some-link') }}">A menu item</a>
    <a class="nav-item is-tab" href="{{ url('/some-other-link') }}">Another menu item</a>
@endpush