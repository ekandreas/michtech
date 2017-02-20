<!DOCTYPE html>
<html>
@include('parts.head')
<body>
@include('parts.header')
<div class="section main">
    @yield('content')
</div>
@include('parts.footer')
@include('parts.scripts')
</body>
</html>