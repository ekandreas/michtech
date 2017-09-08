<script async type="text/javascript" src="{{ mix('dist/app.js') }}"></script>
@yield('scripts')
<script src="https://cdn.ravenjs.com/3.17.0/raven.min.js" crossorigin="anonymous"></script>
<script>
    Raven.config('https://475631a7024f44eaa406c96861f144c7@sentry.io/214383').install()
</script>
