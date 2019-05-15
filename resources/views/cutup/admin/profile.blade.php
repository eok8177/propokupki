@extends('cutups.admin.layout')

@section('content')

<div class="container">
  <h1 class="title-page">Профиль</h1>
</div>
<hr>

<div class="profile">
  <form action="">
    <div class="container">
      <div class="form-group col-md-4 pl-0">
        <label>Email</label>
        <p>Вы можете изменить ваш email</p>
        <input type="email" class="form-control" placeholder="Enter email">
      </div>
    </div>
    <hr>
    <div class="container">
      <div class="form-group col-md-4 pl-0">
        <label>Имя</label>
        <p>Вы можете изменить ваше имя</p>
        <input type="text" class="form-control" placeholder="Enter name">
      </div>
      <div class="form-group col-md-4 pl-0">
        <label>Пароль</label>
        <p>Измените Ваш пароль</p>
        <input type="password" class="form-control" placeholder="Password">
        <input type="password" class="form-control mt-4" placeholder="Новый пароль">
        <input type="password" class="form-control" placeholder="Новый пароль еще раз">
      </div>

      <button type="submit" class="btn btn-red mt-5">Сохранить данные</button>

    </div>
  </form>
</div>


@endsection