@extends('cutup.layout_auth')

@section('content')

<div class="block">
  <div class="header">
    <a href="#"><img src="{{ asset('img/logo.svg') }}"></a>
  </div>
  <div class="body">
    <h1 class="title">Авторизация</h1>
    <form action="">
        <input type="email" placeholder="Email">
        <input type="password" placeholder="Пароль">
        <button type="submit" class="btn btn-red">Войти</button>
    </form>
    <p>Войти с помощью соцсетей</p>
    <div class="inline-btns">
        <a href=""><i class="ico ico-gp"></i>Google</a>
        <a href=""><i class="ico ico-fb"></i>Facebook</a>
    </div>
  </div>
  <div class="footer">
      <a href="#" class="red-link">Зарегистрироваться</a>
  </div>
</div>

@endsection