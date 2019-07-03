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

<body class="auth-page">

<div class="content">
    @yield('content')
</div>

{{--  FOOTER  --}}
<footer>
    <div class="container">
        <p>© 2019 ProPokupki — Акции и скидки Украины</p>
    </div>
</footer>

<!-- Scripts -->
<script src="{{ asset('js/admin.js') }}"></script>
@stack('scripts')
</body>
</html>
