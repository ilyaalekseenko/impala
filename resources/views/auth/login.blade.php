@extends('layouts.main_auth')

@section('content')
    <div class="flex flex-col justify-center items-center w-full h-full gap-6">
        <img class="impala-logo" src="{{ asset('/assets/img/logo.svg') }}" alt="">
        {{--<div class="impala-block-title">{{ __('auth.auth') }}</div>--}}
        <div class="impala-box w-[300px] h-auto p-4">
            <form class="flex flex-col gap-4" method="POST" action="{{ route('login') }}">
                @csrf

                <div class="impala-row-input">
                    <label for="email" class="impala-label">{{ __('auth.email') }}</label>

                    <div class="flex flex-col gap-2 w-full">
                        <input id="email" type="email" class="impala-input @error('email') is-invalid @enderror"
                               name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                        @error('email')
                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                        @enderror
                    </div>
                </div>

                <div class="impala-row-input">
                    <label for="password"
                           class="impala-label">{{ __('auth.password_field') }}</label>

                    <div class="flex flex-col gap-2 w-full">
                        <input id="password" type="password"
                               class="impala-input @error('password') is-invalid @enderror" name="password" required
                               autocomplete="current-password">

                        @error('password')
                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                        @enderror
                    </div>
                </div>

                <div class="impala-row-input">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="remember"
                               id="remember" {{ old('remember') ? 'checked' : '' }}>

                        <label class="form-check-label" for="remember">
                            {{ __('auth.remember_me') }}
                        </label>
                    </div>
                </div>

                <div class="impala-row-input">
                    <button type="submit" class="impala-btn impala-btn-primary w-full">
                        {{ __('auth.log_in') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
    </div>
@endsection
