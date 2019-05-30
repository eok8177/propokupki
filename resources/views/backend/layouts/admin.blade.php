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
{{-- <script src="{{ asset('/backend/js/custom.js') }}"></script> --}}

<script>
  // Отображение картинки при выборе
  function handleImage(e) {
    var reader = new FileReader();
    reader.onload = function (event) {
      $(e.target).parent().find('img').attr('src',event.target.result);
      $('.preview-img').attr('src',event.target.result);
    }
    reader.readAsDataURL(e.target.files[0]);
  };
</script>

@stack('scripts')
</body>
</html>
