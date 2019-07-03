@extends('auth.layouts')

@section('content')

    <div class="block">
        <div class="header">
            <a href="/"><img src="{{ asset('img/logo.svg') }}"></a>
        </div>
        <div class="body">
            <h1 class="title">Авторизация</h1>
            <form method="POST" action="{{ route('login') }}">
                @csrf
                <input id="email" placeholder="Email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required autofocus>
                @if ($errors->has('email'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('email') }}</strong>
                    </span>
                @endif
                <input id="password" placeholder="Пароль" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required>
                @if ($errors->has('password'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('password') }}</strong>
                    </span>
                @endif
                <input type="hidden" name="remember" value="1">
                {{-- <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}> --}}

                {{-- <label class="form-check-label" for="remember">
                    {{ __('Remember Me') }}
                </label> --}}
                <button type="submit" class="btn btn-red">Войти</button>
            </form>
            <p>Войти с помощью соцсетей</p>
            <div class="inline-btns">
                <a href="{{ url('login/google') }}"><i class="ico ico-gp"></i>Google</a>
                <a href="{{ url('login/facebook') }}"><i class="ico ico-fb"></i>Facebook</a>
            </div>
        </div>
        <div class="footer">
            <a href="/register" class="red-link">Зарегистрироваться</a>
            @if (Route::has('password.request'))
            <p>
                <a class="red-link" href="{{ route('password.request') }}">
                    {{ __('Forgot Your Password?') }}
                </a>
            </p>
            @endif
        </div>
    </div>

@endsection