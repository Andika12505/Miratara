<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk - MiraTara</title>
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- Custom CSS (jika ada) -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet"> {{-- Sesuaikan jika Anda punya CSS umum --}}

    <style>
        body {
            background-color: #f8f9fa;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0; /* Pastikan margin body 0 */
        }
        .login-container {
            max-width: 360px; /* Diperkecil dari 400px */
            width: 90%; /* Fleksibel untuk layar kecil */
            padding: 25px; /* Sedikit dikurangi padding */
            background-color: #ffffff;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            margin: 20px auto; /* Margin atas/bawah agar tidak menempel, dan auto untuk tengah */
        }
        .login-header {
            text-align: center;
            margin-bottom: 25px; /* Sedikit dikurangi margin */
        }
        .login-header img {
            max-height: 70px; /* Sedikit dikurangi ukuran logo */
            margin-bottom: 10px;
        }
        img{
            max-width: 100%;
            display: block;
        }
        .login-header h2 { /* Ini adalah target yang akan dihapus atau diubah */
            font-weight: 700;
            color: #333;
            font-size: 1.2rem; /* Sedikit dikurangi ukuran font */
        }
        .form-label {
            font-size: 0.95rem; /* Ukuran font label */
        }
        .form-control {
            font-size: 0.95rem; /* Ukuran font input */
        }
        .form-control:focus {
            box-shadow: none;
            border-color: #ffc0cb;
        }
        .btn-primary {
            background-color: #ffc0cb;
            border-color: #ffc0cb;
            font-weight: 600;
            transition: background-color 0.3s ease;
            padding: 0.75rem 1rem; /* Penyesuaian padding tombol */
            font-size: 1rem; /* Penyesuaian ukuran font tombol */
        }
        .btn-primary:hover {
            background-color: #ff8fab;
            border-color: #ff8fab;
        }
        .btn-link {
            color: #ffc0cb;
            text-decoration: none;
            font-size: 0.9rem; /* Ukuran font link */
        }
        .btn-link:hover {
            color: #ff8fab;
        }
        .invalid-feedback {
            display: block; /* Pastikan pesan error selalu terlihat */
            font-size: 0.85rem; /* Ukuran font pesan error */
        }
        /* Custom Alert Styling for Login Page (matching admin alerts if possible) */
        .alert {
            border: none;
            border-radius: 8px;
            padding: 0.8rem 1rem; /* Penyesuaian padding alert */
            margin-bottom: 1.2rem; /* Penyesuaian margin alert */
            font-size: 0.9rem; /* Penyesuaian ukuran font alert */
        }
        .alert-danger {
            background-color: rgba(220, 53, 69, 0.1);
            color: #721c24;
            border-left: 4px solid #dc3545;
        }
        .alert-success { /* Tambahkan jika Anda juga mengirim pesan sukses */
            background-color: rgba(40, 167, 69, 0.1);
            color: #155724;
            border-left: 4px solid #28a745;
        }

        /* Responsive adjustments for smaller screens */
        @media (max-width: 480px) {
            .login-container {
                padding: 20px;
                margin: 10px auto;
            }
            .login-header h2 {
                font-size: 1rem;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-header">
            <img src="{{ asset('images/logo1.png') }}" alt="MiraTara Logo"> {{-- Sesuaikan path logo --}}
            <h2>Masuk ke Akun Anda</h2> {{-- Mengubah teks agar lebih umum --}}
        </div>

        {{-- Session Status / Flash Message (untuk pesan sukses, dll.) --}}
        @if (session('status'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('status') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        {{-- Session Error Message (untuk pesan seperti dari middleware IsAdmin) --}}
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            {{-- Username or Email --}}
            <div class="mb-3">
                <label for="username" class="form-label">Username atau Email</label>
                <input id="username" class="form-control @error('username') is-invalid @enderror" type="text" name="username" value="{{ old('username') }}" required autofocus autocomplete="username" />
                @error('username')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            {{-- Password --}}
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input id="password" class="form-control @error('password') is-invalid @enderror" type="password" name="password" required autocomplete="current-password" />
                @error('password')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            {{-- Remember Me & Forgot Password --}}
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="form-check">
                    <input id="remember_me" class="form-check-input" type="checkbox" name="remember">
                    <label class="form-check-label" for="remember_me">Ingat Saya</label>
                </div>
                @if (Route::has('password.request'))
                    <a class="btn-link" href="{{ route('password.request') }}">
                        Lupa Password?
                    </a>
                @endif
            </div>

            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-primary">Masuk</button>
            </div>

            <div class="text-center mt-3">
                Belum punya akun? 
                @if (Route::has('register_page')) {{-- Menggunakan route kustom Anda --}}
                    <a class="btn-link" href="{{ route('register_page') }}">Daftar Sekarang</a>
                @endif
            </div>
        </form>
    </div>

    <!-- Bootstrap Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
