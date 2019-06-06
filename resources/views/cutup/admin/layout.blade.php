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
  <nav class="navbar navbar-expand-md">
    <div class="container">
      <div class="left-nav">
        <a class="logo" href="#"><img src="{{ asset('img/admin/logo.svg') }}" alt=""></a>
        <a href="/" class="">Магазины <span class="badge badge-pill badge-warning">238</span></a>
        <a href="/" class="">Акции <span class="badge badge-pill badge-warning">1703</span></a>
      </div>

      <div class="right-nav dropdown">
        <a href="#" class="dropdown-toggle" id="userMenu" data-toggle="dropdown">Margarita</a>
        <div class="dropdown-menu" aria-labelledby="userMenu">
          <hr>
          <a href="#"><i class="ico ico-user"></i> Профиль</a>
          <a href="#"
          onclick="event.preventDefault();document.getElementById('logout-form').submit();"><i class="ico ico-logout"></i> Выйти</a>

          <form id="logout-form" action="#" method="POST" style="display: none;">{{ csrf_field() }}</form>
        </div>
      </div>

    </div>
  </nav>

  {{--  PAGE  --}}
  <div class="flash-message">
    @foreach (['danger', 'warning', 'success', 'info'] as $msg)
      @if(Session::has($msg))
        <p class="alert alert-{{ $msg }}">{{ Session::get($msg) }} <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></p>
      @endif
    @endforeach

    @if (count($errors) > 0)
      <div class="alert alert-danger">
        <ul>
          @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
        </ul>
      </div>
    @endif
  </div>

  @yield('content')

  {{--  FOOTER  --}}
  <hr>
  <footer>
    <div class="container">
      <p>© 2019 ProPokupki — Акции и скидки Украины</p>
    </div>
  </footer>

  <!-- Scripts -->
  <script src="{{ asset('js/admin.js') }}"></script>

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
