@extends('auth.layouts')

@section('content')
<div class="block">
  <div class="header">
    <a href="/"><img src="{{ asset('img/logo.svg') }}"></a>
  </div>




    <div class="body">
        <h1 class="title">{{ __('Reset Password') }}</h1>
        @if (session('status'))
            <div class="alert alert-success" role="alert">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <div class="form-group row">

                <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required placeholder="{{ __('E-Mail Address') }}">

                @if ($errors->has('email'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('email') }}</strong>
                    </span>
                @endif
            </div>

            <button type="submit" class="btn btn-red">{{ __('Send Password Reset Link') }}</button>
        </form>


    </div>

  <div class="footer">
      <p>Войти с помощью соцсетей</p>
      <div class="inline-btns">
          <a href=""><i class="ico ico-gp"></i>Google</a>
          <a href=""><i class="ico ico-fb"></i>Facebook</a>
      </div>
  </div>

</div>
@endsection
