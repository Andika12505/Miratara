@extends('layouts.main')

@section('title', 'Register - MiraTara Fashion')

@section('content')
    <div class="container mt-5 pt-5 mb-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow">
                    <div class="card-body p-4">
                        <div class="text-center mb-4">
                            <h2 class="fw-bold" style="color: #000">Create Account</h2>
                            <p class="text-muted">Join MiraTara family</p>
                        </div>

                        <form method="POST" action="{{ route('register') }}">
                            @csrf

                            <div>
                                <label for="full_name" class="form-label">Full Name</label>
                                <x-text-input id="full_name" class="form-control" type="text" name="full_name" :value="old('full_name')" required autofocus autocomplete="name" />
                                <x-input-error :messages="$errors->get('full_name')" class="mt-2" />
                            </div>

                            <div class="mt-4">
                                <label for="username" class="form-label">Username</label>
                                <x-text-input id="username" class="form-control" type="text" name="username" :value="old('username')" required autocomplete="username" />
                                <x-input-error :messages="$errors->get('username')" class="mt-2" />
                            </div>

                            <div class="mt-4">
                                <label for="email" class="form-label">Email</label>
                                <x-text-input id="email" class="form-control" type="email" name="email" :value="old('email')" required autocomplete="username" />
                                <x-input-error :messages="$errors->get('email')" class="mt-2" />
                            </div>

                            <div class="mt-4">
                                <label for="phone" class="form-label">Phone (Optional)</label>
                                <x-text-input id="phone" class="form-control" type="text" name="phone" :value="old('phone')" autocomplete="phone" />
                                <x-input-error :messages="$errors->get('phone')" class="mt-2" />
                            </div>

                            <div class="mt-4">
                                <label for="password" class="form-label">Password</label>
                                <x-text-input id="password" class="form-control"
                                                type="password"
                                                name="password"
                                                required autocomplete="new-password" />
                                <x-input-error :messages="$errors->get('password')" class="mt-2" />
                                {{-- Password Requirements (ini akan memerlukan JS Anda untuk styling) --}}
                                <div class="password-requirements mt-2">
                                    <small class="text-muted">Password harus mengandung:</small>
                                    <ul class="requirements-list">
                                        <li id="req-length" class="req-item">8-20 karakter</li>
                                        <li id="req-capital" class="req-item">1 huruf kapital (A-Z)</li>
                                        <li id="req-number" class="req-item">1 angka (0-9)</li>
                                        <li id="req-special" class="req-item">1 karakter special (@!$#%^&lt;&gt;?_-)</li>
                                    </ul>
                                </div>
                            </div>

                            <div class="mt-4">
                                <label for="password_confirmation" class="form-label">Confirm Password</label>
                                <x-text-input id="password_confirmation" class="form-control"
                                                type="password"
                                                name="password_confirmation" required autocomplete="new-password" />
                                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                            </div>

                            <div class="d-grid mt-4">
                                <x-primary-button class="w-100">
                                    {{ __('Register') }}
                                </x-primary-button>
                            </div>

                            <div class="text-center mt-3">
                                <p>
                                    Sudah punya akun?
                                    <a href="{{ route('login_page') }}" style="color: #ffc0cb">Login disini</a>
                                </p>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection