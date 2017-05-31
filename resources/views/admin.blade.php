@extends('layouts.master')

@section('content')
    <div class="container" id="admin">
        <h1 class="title">Admin</h1>

        <p>Här sköter admin om mappar och texter på webbplatsen.</p>

        <br/><br/>

        <p>
            <h2 class="title">Mappar</h2>
            <admin-folder></admin-folder>
        </p>

        <br/><br/>

        <p>
            <h2 class="title">Texter</h2>
            <admin-texts></admin-texts>
        </p>

    </div>
@endsection

@section('scripts')
    <script async type="text/javascript" src="{{ mix('dist/admin.js') }}"></script>
    <script src="https://cloud.tinymce.com/stable/tinymce.min.js?apiKey=d43ssffprz5kchs9kcgx9l0xfatdriksob83aqnld3b6irab"></script>
@endsection