@extends('auth.layouts')

@section('content')
<div class="block">
    <div class="header">
        <a href="/"><img src="{{ asset('img/logo.svg') }}"></a>
    </div>

    <div class="body">
        <h1 class="title">{{ __('Reset Password') }}</h1>
        <form method="POST" action="{{ route('password.update') }}">
            @csrf

            <input type="hidden" name="token" value="{{ $token }}">

            <div class="form-group row">

                    <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ $email ?? old('email') }}" required autofocus placeholder="{{ __('E-Mail Address') }}">

                    @if ($errors->has('email'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('email') }}</strong>
                        </span>
                    @endif
            </div>

            <div class="form-group row">

                    <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required placeholder="{{ __('Password') }}">

                    @if ($errors->has('password'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('password') }}</strong>
                        </span>
                    @endif
            </div>

            <div class="form-group row">

                    <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required placeholder="{{ __('Confirm Password') }}">
            </div>

            <div class="form-group row mb-0">
                <div class="col-md-6 offset-md-4">
                    <button type="submit" class="btn btn-red">
                        {{ __('Reset Password') }}
                    </button>
                </div>
            </div>
        </form>
    </div>



</div>
@endsection
