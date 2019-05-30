@extends('cutup.layout_auth')

@section('content')

<div class="block">
  <div class="header">
    <a href="#"><img src="{{ asset('img/logo.svg') }}"></a>
  </div>
  <div class="body">
    <h1 class="title">Регистрация</h1>
    <form action="">
        <input type="text" placeholder="Имя">
        <input type="email" placeholder="Email">
        <input type="password" placeholder="Пароль">
        <button type="submit" class="btn btn-red">Зарегистрироваться</button>
    </form>
    <p>Регистрация с помощью соцсетей</p>
    <div class="inline-btns">
        <a href=""><i class="ico ico-gp"></i>Google</a>
        <a href=""><i class="ico ico-fb"></i>Facebook</a>
    </div>
  </div>
</div>

@endsection