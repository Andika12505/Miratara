<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'MiraTara Fashion')</title>
    <link rel="stylesheet" href="{{ asset('css/bootstrap/bootstrap.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('themify-icons/themify-icons.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/nav_carousel_style.css') }}" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    @stack('styles')
    
    <style>
        /* YOUR ORIGINAL STYLES - Keep these */
        /* Main content offset for fixed navbar */
        body {
            padding-top: 22px; /* Adjust this value based on your navbar height */
        }
        
        /* Navbar styling */
        .navbar {
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            z-index: 1030;
        }
        
        /* Main content wrapper */
        .main-content {
            min-height: calc(100vh - 80px);
        }
        
        /* Responsive navbar padding */
        @media (max-width: 991.98px) {
            body {
                padding-top: 70px;
            }
            .main-content {
                min-height: calc(100vh - 70px);
            }
        }
        
        @media (max-width: 575.98px) {
            body {
                padding-top: 65px;
            }
            .main-content {
                min-height: calc(100vh - 65px);
            }
        }

        /* ADDITIONAL STYLES for cart functionality */
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
        
        /* Cart badge styling */
        .cart-count {
            font-size: 0.75rem;
            position: absolute;
            top: -5px;
            right: -10px;
        }
        
        .nav-link {
            position: relative;
        }
    </style>
</head>
<body>
    <script src="{{ asset('js/bootstrap/bootstrap.bundle.min.js') }}"></script>
    
    <section id="header">
        <nav class="navbar navbar-expand-lg fixed-top bg-white">
            <div class="container-fluid">
                <a class="navbar-brand me-auto" href="{{ route('homepage') }}">
                    <img src="{{ asset('images/logo1.png') }}" alt="Logo" height="40" />
                </a>
                
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" 
                        data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" 
                        aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                
                <div class="collapse navbar-collapse justify-content-center" id="navbarSupportedContent">
                    <ul class="navbar-nav justify-content-center mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a class="nav-link mx-lg-2 @if(Request::routeIs('homepage')) active @endif" 
                            aria-current="page" href="{{ route('homepage') }}">Home</a>
                        </li>
                        
                        <!-- NEW: Find Your Vibe Dropdown -->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle mx-lg-2 vibe-nav-link" href="#" role="button" 
                            data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-sparkles me-1"></i>Find Your Vibe
                            </a>
                            <ul class="dropdown-menu vibe-dropdown">
                                <li class="dropdown-header">Discover Your Perfect Style</li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <a class="dropdown-item vibe-dropdown-item" 
                                    href="{{ route('products.index', ['vibe_name' => 'beach_getaway']) }}">
                                        <div class="vibe-dropdown-content">
                                            <i class="fas fa-umbrella-beach vibe-dropdown-icon beach-color"></i>
                                            <div class="vibe-dropdown-text">
                                                <span class="vibe-dropdown-title">Beach Getaway</span>
                                                <small class="vibe-dropdown-desc">Casual & breezy vacation vibes</small>
                                            </div>
                                        </div>
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item vibe-dropdown-item" 
                                    href="{{ route('products.index', ['vibe_name' => 'elegant_evening']) }}">
                                        <div class="vibe-dropdown-content">
                                            <i class="fas fa-cocktail vibe-dropdown-icon elegant-color"></i>
                                            <div class="vibe-dropdown-text">
                                                <span class="vibe-dropdown-title">Elegant Evening</span>
                                                <small class="vibe-dropdown-desc">Sophisticated occasion wear</small>
                                            </div>
                                        </div>
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item vibe-dropdown-item" 
                                    href="{{ route('products.index', ['vibe_name' => 'sporty_active']) }}">
                                        <div class="vibe-dropdown-content">
                                            <i class="fas fa-dumbbell vibe-dropdown-icon sporty-color"></i>
                                            <div class="vibe-dropdown-text">
                                                <span class="vibe-dropdown-title">Sporty Active</span>
                                                <small class="vibe-dropdown-desc">Performance & comfort</small>
                                            </div>
                                        </div>
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item vibe-dropdown-item" 
                                    href="{{ route('products.index', ['vibe_name' => 'daily_casual']) }}">
                                        <div class="vibe-dropdown-content">
                                            <i class="fas fa-coffee vibe-dropdown-icon casual-color"></i>
                                            <div class="vibe-dropdown-text">
                                                <span class="vibe-dropdown-title">Daily Casual</span>
                                                <small class="vibe-dropdown-desc">Everyday essentials</small>
                                            </div>
                                        </div>
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <a class="dropdown-item browse-all-vibes" href="{{ route('products.index') }}">
                                        <i class="fas fa-search me-2"></i>Browse All Products
                                    </a>
                                </li>
                            </ul>
                        </li>
                        
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle mx-lg-2" href="#" role="button" 
                            data-bs-toggle="dropdown" aria-expanded="false">
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
                            <a class="nav-link dropdown-toggle mx-lg-2" href="#" role="button" 
                            data-bs-toggle="dropdown" aria-expanded="false">
                                Accessories
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="#">Jewelry</a></li>
                                <li><a class="dropdown-item" href="#">Shoes</a></li>
                                <li><a class="dropdown-item" href="#">Bags</a></li>
                            </ul>
                        </li>
                    </ul>
                    
                    <!-- RIGHT SIDE: Cart + Auth -->
                    <div class="d-flex align-items-center ms-auto">
                        <!-- Cart Icon -->
                        <ul class="navbar-nav">
                            <li class="nav-item">
                                <a class="nav-link mx-lg-2" data-bs-toggle="offcanvas" href="#cartOffcanvas" 
                                role="button" aria-controls="cartOffcanvas" title="Keranjang Belanja">
                                    <i class="fas fa-shopping-cart"></i>
                                    <span class="badge rounded-pill bg-danger cart-count">
                                        {{ Cart::count() > 0 ? Cart::count() : '' }}
                                    </span>
                                </a>
                            </li>
                        </ul>

                        <!-- Auth Section -->
                        <div class="ms-3">
                            @auth
                            <div class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" 
                                id="navbarDropdownUser" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    @if (Auth::user()->profile_photo_path)
                                        <img src="{{ asset('storage/' . Auth::user()->profile_photo_path) }}" 
                                            alt="Foto" class="rounded-circle me-2" 
                                            style="width: 25px; height: 25px; object-fit: cover;">
                                    @else
                                        <i class="fas fa-user-circle fa-lg me-2"></i>
                                    @endif
                                    {{ Auth::user()->username ?? Auth::user()->name }}
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdownUser">
                                    <li><a class="dropdown-item" href="{{ route('customer.account.view') }}">Lihat Akun</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <form id="logout-form-customer" action="{{ route('logout') }}" method="POST" style="display: none;">
                                            @csrf
                                        </form>
                                        <a class="dropdown-item" href="#" 
                                        onclick="event.preventDefault(); document.getElementById('logout-form-customer').submit();">
                                        Logout
                                        </a>
                                    </li>
                                </ul>
                            </div>
                            @else
                            <a href="{{ route('login_page') }}" class="nav-login-button btn-primary me-2">Login</a>
                            <a href="{{ route('register_page') }}" class="nav-login-button btn-primary">Register</a>
                            @endauth
                        </div>
                    </div>
                </div>
            </div>
        </nav>
    </section>

    <style>
    /* === ENHANCED VIBE NAVIGATION STYLES === */

    /* Vibe nav link with sparkle icon */
    .vibe-nav-link {
        color: #ffc0cb !important;
        font-weight: 600;
        position: relative;
    }

    .vibe-nav-link:hover {
        color: #ff8fab !important;
    }

    .vibe-nav-link::before {
        background-color: #ffc0cb !important;
    }

    /* Enhanced vibe dropdown */
    .vibe-dropdown {
        min-width: 280px;
        padding: 10px 0;
        border: 1px solid #ffc0cb;
        box-shadow: 0 10px 30px rgba(255, 192, 203, 0.2);
        border-radius: 8px;
    }

    .vibe-dropdown .dropdown-header {
        color: #333;
        font-weight: 600;
        font-size: 0.9rem;
        text-align: center;
        padding: 8px 20px;
    }

    .vibe-dropdown-item {
        padding: 12px 20px !important;
        border: none;
        transition: all 0.3s ease;
    }

    .vibe-dropdown-item:hover {
        background: linear-gradient(135deg, rgba(255, 192, 203, 0.1) 0%, rgba(255, 139, 171, 0.1) 100%);
        transform: translateX(5px);
    }

    .vibe-dropdown-content {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .vibe-dropdown-icon {
        font-size: 1.4rem;
        width: 30px;
        text-align: center;
        transition: transform 0.3s ease;
    }

    .vibe-dropdown-item:hover .vibe-dropdown-icon {
        transform: scale(1.1);
    }

    /* Vibe icon colors */
    .beach-color { color: #5DADE2; }
    .elegant-color { color: #AF7AC5; }
    .sporty-color { color: #F1948A; }
    .casual-color { color: #A9DFBF; }

    .vibe-dropdown-text {
        display: flex;
        flex-direction: column;
        gap: 2px;
    }

    .vibe-dropdown-title {
        font-weight: 600;
        color: #333;
        font-size: 0.95rem;
    }

    .vibe-dropdown-desc {
        color: #666;
        font-size: 0.8rem;
        line-height: 1.2;
    }

    .browse-all-vibes {
        color: #ffc0cb !important;
        font-weight: 500;
        text-align: center;
        padding: 10px 20px !important;
    }

    .browse-all-vibes:hover {
        background: rgba(255, 192, 203, 0.1);
        color: #ff8fab !important;
    }

    /* Mobile responsiveness for vibe dropdown */
    @media (max-width: 991.98px) {
        .vibe-dropdown {
            min-width: 250px;
            position: static;
            transform: none;
            box-shadow: none;
            border: none;
            border-radius: 0;
            background: #f8f9fa;
            margin-top: 10px;
        }
        
        .vibe-dropdown-item {
            padding: 10px 15px !important;
        }
        
        .vibe-dropdown-content {
            gap: 10px;
        }
        
        .vibe-dropdown-icon {
            font-size: 1.2rem;
            width: 25px;
        }
    }

    /* Quick access button for mobile */
    @media (max-width: 575.98px) {
        .vibe-nav-link {
            font-size: 0.9rem;
        }
        
        .vibe-dropdown-title {
            font-size: 0.9rem;
        }
        
        .vibe-dropdown-desc {
            font-size: 0.75rem;
        }
    }

    /* Accessibility improvements */
    .vibe-dropdown-item:focus {
        outline: 2px solid #ffc0cb;
        outline-offset: -2px;
    }

    .nav-link:focus {
        outline: 2px solid #ffc0cb;
        outline-offset: 2px;
    }
    </style>

    <!-- YOUR ORIGINAL MAIN CONTENT WRAPPER -->
    <main class="main-content">
        @yield('content')
    </main>

    <!-- YOUR ORIGINAL FOOTER -->
    @include('components.footer')

    <!-- MODULAR CART OFFCANVAS COMPONENT -->
    @include('components.cart-offcanvas')

    <script>
    // === GLOBAL CART FUNCTIONS === 
    // Available immediately on all pages

    // Clear cart function
    window.clearCartUniversal = async function(source = 'page') {
        if (!confirm('Apakah Anda yakin ingin mengosongkan seluruh keranjang belanja?')) {
            return;
        }
        
        try {
            console.log('Clearing cart from:', source);
            
            const response = await fetch('{{ route("cart.clear") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
            
            const data = await response.json();
            console.log('Clear cart response:', data);
            
            if (data.success) {
                if (typeof updateCartBadge === 'function') {
                    updateCartBadge(0);
                }
                
                if (typeof fetchCartContent === 'function') {
                    fetchCartContent();
                }
                
                if (typeof showCartToast === 'function') {
                    showCartToast('Keranjang berhasil dikosongkan!', 'success');
                } else {
                    alert('Keranjang berhasil dikosongkan!');
                }
                
                if (window.location.pathname.includes('/cart')) {
                    setTimeout(() => location.reload(), 1000);
                }
                
            } else {
                throw new Error(data.message || 'Failed to clear cart');
            }
            
        } catch (error) {
            console.error('Error clearing cart:', error);
            
            if (typeof showCartToast === 'function') {
                showCartToast('Gagal mengosongkan keranjang', 'error');
            } else {
                alert('Gagal mengosongkan keranjang');
            }
        }
    };

    // Update quantity function
    window.updateQuantity = async function(rowId, newQuantity) {
        if (newQuantity < 0) {
            newQuantity = 0;
        }
        
        const cartItem = document.querySelector(`[data-row-id="${rowId}"]`);
        if (cartItem) cartItem.classList.add('loading');
        
        try {
            const formData = new FormData();
            formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
            formData.append('quantity', newQuantity);
            
            const response = await fetch(`/cart/update/${rowId}`, {
                method: 'POST',
                body: formData
            });
            
            if (response.ok) {
                const result = await response.json();
                
                if (result.success) {
                    if (newQuantity === 0 || result.removed) {
                        alert('Produk berhasil dihapus dari keranjang!');
                    } else {
                        alert('Jumlah produk berhasil diupdate!');
                    }
                    location.reload();
                } else {
                    throw new Error(result.message || 'Failed to update quantity');
                }
            } else {
                throw new Error('Failed to update quantity');
            }
        } catch (error) {
            console.error('Error updating quantity:', error);
            alert('Gagal mengupdate jumlah produk. Silakan coba lagi.');
        }
        
        if (cartItem) cartItem.classList.remove('loading');
    };

    // Remove item function
    window.removeItem = async function(rowId, productName) {
        if (!confirm(`Apakah Anda yakin ingin menghapus "${productName}" dari keranjang?`)) {
            return;
        }
        
        const cartItem = document.querySelector(`[data-row-id="${rowId}"]`);
        if (cartItem) cartItem.classList.add('loading');
        
        try {
            const response = await fetch(`/cart/remove/${rowId}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
            
            if (response.ok) {
                const result = await response.json();
                
                if (result.success) {
                    alert('Produk berhasil dihapus dari keranjang!');
                    location.reload();
                } else {
                    throw new Error(result.message || 'Failed to remove item');
                }
            } else {
                throw new Error('Failed to remove item');
            }
        } catch (error) {
            console.error('Error removing item:', error);
            alert('Gagal menghapus produk. Silakan coba lagi.');
        }
        
        if (cartItem) cartItem.classList.remove('loading');
    };

    // Alias for backward compatibility
    window.clearCart = function() {
        return window.clearCartUniversal('cart-page');
    };
    </script>

    @stack('scripts')
</body>
</html>