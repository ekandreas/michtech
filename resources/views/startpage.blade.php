@extends('layouts.master')

@section('content')
    <div class="container">
        <div class="columns">
            <div class="column is-6" id="app-folder">
                <div v-for="folder in folders">
                    <folder :id="folder.id" :showdocuments="folder.showDocuments" :showuploads="folder.showUploads"></folder>
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