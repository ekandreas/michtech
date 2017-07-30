@extends('layouts.master')

@section('content')
    <div class="container">
        <div class="columns">
            <div class="column is-6" id="app-folder">
                <div class="panel">
                    <p class="panel-block image">
                        <img src="{{ asset('images/michtech-measure.jpg') }}" alt="Michtech mäter" />
                    </p>
                </div>

                <div v-for="folder in folders">
                    <folder :id="folder.id"></folder>
                </div>
            </div>
            <div class="column is-6">
                @include('parts.texts')
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script async type="text/javascript" src="{{ mix('dist/app-folder.js') }}"></script>
@endsection