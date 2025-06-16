@extends('layouts.main')

@section('title', 'Login - MiraTara Fashion')

@section('content')
    <div class="container mt-5 pt-5 mb-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow">
                    <div class="card-body p-4">
                        <div class="text-center mb-4">
                            <h2 class="fw-bold" style="color: #000">Welcome Back</h2>
                            <p class="text-muted">Sign in to your MiraTara account</p>
                        </div>

                        <x-auth-session-status class="mb-4" :status="session('status')" />

                        <form method="POST" action="{{ route('login') }}">
                            @csrf

                            <div class="mb-3">
                                <label for="email" class="form-label">Username atau Email</label>
                                <x-text-input id="email" class="form-control" type="text" name="email" :value="old('email')" required autofocus autocomplete="username" />
                                <x-input-error :messages="$errors->get('email')" class="mt-2" />
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <x-text-input id="password" class="form-control"
                                                type="password"
                                                name="password"
                                                required autocomplete="current-password" />
                                <x-input-error :messages="$errors->get('password')" class="mt-2" />
                            </div>

                            <div class="form-check mb-3">
                                <input id="remember_me" type="checkbox" class="form-check-input" name="remember">
                                <label for="remember_me" class="form-check-label">
                                    {{ __('Remember me') }}
                                </label>
                            </div>

                            <div class="d-grid mt-4">
                                @if (Route::has('password.request'))
                                    <a class="text-center" href="{{ route('password.request') }}" style="color: #ffc0cb; text-decoration: none; display: block; margin-bottom: 1rem;">
                                        {{ __('Forgot your password?') }}
                                    </a>
                                @endif

                                <x-primary-button class="w-100">
                                    {{ __('Log in') }}
                                </x-primary-button>
                            </div>

                            <div class="text-center mt-4">
                                <p class="mb-0">
                                Belum punya akun?
                                <a href="{{ route('register_page') }}" style="color: #ffc0cb; font-weight: 500">Daftar disini</a>
                                </p>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection