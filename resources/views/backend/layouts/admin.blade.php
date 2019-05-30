<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <link href="{{ asset('css/admin.css') }}" rel="stylesheet">
    @stack('styles')
</head>

<body>
{{-- Navigation --}}
@include('backend.layouts.header')

@yield('content')

{{--  FOOTER  --}}
<hr>
@include('backend.layouts.footer')
<!-- Scripts -->
<script src="{{ asset('js/admin.js') }}"></script>
<script src="{{ asset('/backend/js/custom.js') }}"></script>
@stack('scripts')
</body>
</html>
