@extends('layouts.master')

@section('content')
    <div class="container" id="admin">
        <h2 class="title">Admin</h2>
        <admin-folder></admin-folder>
    </div>
@endsection

@section('scripts')
    <script async type="text/javascript" src="{{ mix('js/admin.js') }}"></script>
@endsection