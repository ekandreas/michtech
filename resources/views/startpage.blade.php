@extends('layouts.master')

@section('content')
    <div class="container">
        <div class="columns">
            <div class="column is-6" id="app-folder">
                <folder v-for="folder in folders" :id="folder.id"></folder>
            </div>
            <div class="column is-6">
                @include('parts.michtech')
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script async type="text/javascript" src="{{ asset('js/app-folder.js') }}"></script>
@endsection