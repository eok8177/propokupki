@extends('backend.layouts.admin')

@section('content')

<div class="container">
  <h1 class="title-page">Профиль</h1>
</div>
<hr>

<div class="profile">
  {!! Form::open(['route' => ['admin.profile.update', $user->id], 'method' => 'POST']) !!}
    <div class="container">
      <div class="form-group col-md-4 pl-0">
        <label>Email</label>
        <p>Вы можете изменить ваш email</p>
        <input type="email" name="email" value="{{ $user->email }}" class="form-control" placeholder="Enter email">
      </div>
    </div>
    <hr>
    <div class="container">
      <div class="form-group col-md-4 pl-0">
        <label>Имя</label>
        <p>Вы можете изменить ваше имя</p>
        <input type="text" name="name" value="{{ $user->name }}" class="form-control" placeholder="Enter name">
      </div>
      <div class="form-group col-md-4 pl-0">
        <label>Пароль</label>
        <p>Измените Ваш пароль</p>
        <input type="password" name="old_password" class="form-control" placeholder="Cтарый пароль">
        @if ($errors->has('old_password'))
            <span class="invalid-feedback" role="alert">
                <strong>{{ $errors->first('old_password') }}</strong>
            </span>
        @endif
        <input type="password" name="password" class="form-control mt-4" placeholder="Новый пароль">
        @if ($errors->has('password'))
            <span class="invalid-feedback" role="alert">
                <strong>{{ $errors->first('password') }}</strong>
            </span>
        @endif
        <input type="password" name="password_confirmation" class="form-control" placeholder="Новый пароль еще раз">
        @if ($errors->has('password_confirmation'))
            <span class="invalid-feedback" role="alert">
                <strong>{{ $errors->first('password_confirmation') }}</strong>
            </span>
        @endif
      </div>

      <button type="submit" class="btn btn-red mt-5">Сохранить данные</button>

    </div>
  {{ Form::close() }}
</div>


@endsection