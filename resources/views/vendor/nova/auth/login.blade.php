@extends('nova::auth.layout')

@section('content')

<form method="POST" action="{{ route('nova.login') }}">
    {{ csrf_field() }}

    <div class="mb-6 {{ $errors->has('email') ? ' has-error' : '' }}">
        <input class="custom-input w-full" id="email" type="email" name="email" value="{{ old('email') }}" required autofocus placeholder="{{ __('Email Address') }}">
    </div>

    <div class="mb-2 {{ $errors->has('password') ? ' has-error' : '' }}">
        <input class="custom-input w-full" id="password" type="password" name="password" required 
            placeholder="{{ __('Password') }}">
    </div>

    @if (\Laravel\Nova\Nova::resetsPasswords())
        <div class="text-right mb-6">
            <a class="forget-password-link" href="{{ route('nova.password.request') }}">
                {{ __('Forgot Your Password?') }}
            </a>
        </div>
    @endif

    <div class="flex mb-4">
        <label class="cursor-pointer custom-check-box remember-me">
            <input class="checkbox mr-1 cursor-pointer" type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
            <span class="lowercase">{{ __('Remember Me') }}</span>
        </label>
    </div>

    <button class="btn btn-default login-button" type="submit">
        {{ __('Login') }}
    </button>
</form>

@endsection
