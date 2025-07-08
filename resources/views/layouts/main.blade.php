<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>@yield('title', 'MiraTara Fashion')</title>

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="stylesheet" href="{{ asset('css/bootstrap/bootstrap.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('themify-icons/themify-icons.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/nav_carousel_style.css') }}" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    
    @stack('styles')
    <style>
        .navbar .nav-login-button {
            border: 1px solid #ffc0cb;
            border-radius: 5px;
            padding: 8px 15px;
            text-decoration: none;
            color: #ffc0cb;
            transition: all 0.3s ease;
            white-space: nowrap;
        }
        .navbar .nav-login-button:hover {
            background-color: #ffc0cb;
            color: white;
        }
        .navbar .nav-login-button.btn-primary {
            background-color: #ffc0cb;
            color: white;
        }
        .navbar .nav-login-button.btn-primary:hover {
            background-color: #ff8fab;
        }
        .navbar-nav .nav-item.dropdown .nav-link {
            display: flex;
            align-items: center;
        }
        .navbar-nav .nav-item.dropdown .nav-link i {
            margin-right: 5px;
        }
        .navbar-collapse {
            justify-content: flex-end !important;
        }
        .navbar-nav {
            margin-left: auto !important;
        }
        .d-flex.align-items-center {
            margin-left: 1rem;
        }
        .navbar-toggler {
            margin-left: 5px;
            order: 2;
        }
        .navbar-brand {
            margin-right: auto;
        }
    </style>
</head>

{{-- Letakkan ini di resources/views/layouts/main.blade.php, sebelum </body> --}}

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {

    const cartOffcanvasEl = document.getElementById('cartOffcanvas');
    const offcanvasCartBody = document.getElementById('offcanvasCartBody');
    const cartCountBadge = document.querySelector('.cart-count');

    // FUNGSI UNTUK MENGAMBIL ISI CART & MENAMPILKANNYA
    const fetchCartContent = async () => {
        // Tampilkan loading spinner
        offcanvasCartBody.innerHTML = `<div class="text-center py-5"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div></div>`;
        
        try {
            // Kita perlu route baru untuk ini, misal '/cart/content'
            // Untuk sekarang, kita akan render dari halaman cart index (kurang efisien, tapi bisa)
            // Cara yang lebih baik adalah membuat endpoint API khusus.
            // Mari kita asumsikan kita punya halaman /cart yang bisa kita fetch
            const response = await fetch('{{ route("cart.index") }}');
            const html = await response.text();
            
            // Ambil hanya bagian konten dari halaman cart
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, "text/html");
            const cartContent = doc.querySelector('.container').innerHTML;

            offcanvasCartBody.innerHTML = cartContent;

        } catch (error) {
            console.error('Gagal memuat keranjang:', error);
            offcanvasCartBody.innerHTML = '<p class="text-center text-danger">Gagal memuat keranjang. Silakan coba lagi.</p>';
        }
    };

    // Saat offcanvas dibuka, panggil fungsi untuk memuat konten
    cartOffcanvasEl.addEventListener('show.bs.offcanvas', function () {
        fetchCartContent();
    });

    // FUNGSI UNTUK MENANGANI SUBMIT "ADD TO CART"
    document.querySelectorAll('.add-to-cart-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const button = form.querySelector('button[type="submit"]');
            const originalButtonText = button.innerHTML;
            button.innerHTML = 'Menambahkan...';
            button.disabled = true;

            const formData = new FormData(form);

            fetch('{{ route("cart.add") }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update badge di navbar
                    if(cartCountBadge) {
                        cartCountBadge.textContent = data.cartCount;
                    }
                    
                    // Beri feedback visual di tombol
                    button.innerHTML = 'Ditambahkan!';
                    setTimeout(() => {
                        button.innerHTML = originalButtonText;
                        button.disabled = false;
                    }, 1500);

                } else {
                    throw new Error(data.message || 'Gagal menambahkan produk');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan. Silakan coba lagi.');
                button.innerHTML = originalButtonText;
                button.disabled = false;
            });
        });
    });
});
</script>
@endpush

<body>
    <script src="{{ asset('js/bootstrap/bootstrap.bundle.min.js') }}"></script>

    <section id="header">
        <nav class="navbar navbar-expand-lg fixed-top bg-white">
            <div class="container-fluid">
                <a class="navbar-brand me-auto" href="{{ route('homepage') }}">
                    <img src="{{ asset('images/logo1.png') }}" alt="Logo" height="40" />
                </a>

                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse justify-content-center" id="navbarSupportedContent">
                    <ul class="navbar-nav justify-content-center mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a class="nav-link mx-lg-2 @if(Request::routeIs('homepage')) active @endif" aria-current="page" href="{{ route('homepage') }}">Home</a>
                        </li>

                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle mx-lg-2" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Women's
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="#">Dresses</a></li>
                                <li><a class="dropdown-item" href="#">Skirt</a></li>
                                <li><a class="dropdown-item" href="#">Sweaters</a></li>
                                <li><a class="dropdown-item" href="#">Jackets</a></li>
                            </ul>
                        </li>

                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle mx-lg-2" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Accessories
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="#">Jewelry</a></li>
                                <li><a class="dropdown-item" href="#">Shoes</a></li>
                                <li><a class="dropdown-item" href="#">Bags</a></li>
                            </ul>
                        </li>
                    </ul>

                    <div class="d-flex align-items-center ms-auto">

                        {{-- Tombol Keranjang Belanja --}}
                        <ul class="navbar-nav">
                            <li class="nav-item">
                                <a class="nav-link" data-bs-toggle="offcanvas" href="#cartOffcanvas" role="button" aria-controls="cartOffcanvas">
                                    <i class="fas fa-shopping-cart"></i>
                                    <span class="badge rounded-pill bg-danger cart-count">
                                        {{ Cart::count() > 0 ? Cart::count() : '' }}
                                    </span>
                                </a>
                            </li>
                            </li>
                        </ul>

                        {{-- Bagian Login/Logout/Akun --}}
                        <div class="ms-3">
                            @auth
                            <div class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="navbarDropdownUser" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    @if (Auth::user()->profile_photo_path)
                                        <img src="{{ asset('storage/' . Auth::user()->profile_photo_path) }}" alt="Foto" class="rounded-circle me-2" style="width: 25px; height: 25px; object-fit: cover;">
                                    @else
                                        <i class="fas fa-user-circle fa-lg me-2"></i>
                                    @endif
                                    {{ Auth::user()->username }}
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdownUser">
                                    <li><a class="dropdown-item" href="{{ route('customer.account.view') }}">Lihat Akun</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <form id="logout-form-customer" action="{{ route('logout') }}" method="POST" style="display: none;">
                                            @csrf
                                        </form>
                                        <a class="dropdown-item" href="#" onclick="event.preventDefault(); document.getElementById('logout-form-customer').submit();">Logout</a>
                                    </li>
                                </ul>
                            </div>
                            @else
                            <a href="{{ route('login_page') }}" class="nav-login-button me-2">Login</a>
                            <a href="{{ route('register_page') }}" class="nav-login-button btn-primary">Register</a>
                            @endauth
                        </div>
                    </div>
                </div>
            </div>
        </nav>
    </section>

    @yield('content')

    <div class="offcanvas offcanvas-end" tabindex="-1" id="cartOffcanvas" aria-labelledby="cartOffcanvasLabel">
  <div class="offcanvas-header border-bottom">
    <h5 class="offcanvas-title" id="cartOffcanvasLabel">
        <i class="fas fa-shopping-cart me-2"></i> Keranjang Belanja
    </h5>
    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
  </div>
  <div class="offcanvas-body" id="offcanvasCartBody">
    {{-- Konten keranjang akan dimuat di sini oleh JavaScript --}}
    <div class="text-center py-5">
        <p>Keranjang Anda kosong.</p>
    </div>
  </div>
</div>

    @stack('scripts')
</body>
</html>