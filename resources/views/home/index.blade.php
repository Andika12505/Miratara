@extends('layouts.main')

@section('title', 'Home - MiraTara Fashion')

@section('content')
<section id="home" class="home overflow-hidden">
    <div id="carouselExampleIndicators" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-indicators">
            <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="0" 
                    class="active" aria-current="true" aria-label="Slide 1"></button>
            <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="1" 
                    aria-label="Slide 2"></button>
        </div>
        <div class="carousel-inner">
            <div class="carousel-item active">
                <div class="home-banner home-banner-1" style="background-image: url('{{ asset('images/miaw1.png') }}');">
                    <div class="home-banner-text">
                        <a href="{{ route('products.index') }}" class="text-uppercase mt-4"></a>
                    </div>
                </div>
            </div>

            <div class="carousel-item">
                <div class="home-banner home-banner-2" style="background-image: url('{{ asset('images/miaw2.png') }}');">
                    <div class="home-banner-text">
                        <h1>Miaw</h1>
                        <h2>100% Discount For This All Day</h2>
                        <a href="{{ route('products.index') }}" class="btn-carousel text-uppercase mt-4">Our Products</a>
                    </div>
                </div>
            </div>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
            <span class="ti-angle-left slider-icon"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
            <span class="ti-angle-right slider-icon"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>
</section>

<section id="products" class="products">
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <div class="headline text-center mb-5">
                    <h2 class="pb-3 position-relative d-idline-block">Our Products</h2>
                </div>
            </div>
        </div>

        <div class="row g-4">
            @forelse($featuredProducts as $product)
            <div class="col-lg-4 col-md-6 col-sm-12">
                <div class="product-card">
                    <div class="product-image-container position-relative">
                        <img src="{{ $product->image ? asset('images/' . $product->image) : 'https://via.placeholder.com/400x600/f8f9fa/6c757d?text=' . urlencode($product->name) }}" 
                             alt="{{ $product->name }}" 
                             class="product-image"
                             onerror="this.src='https://via.placeholder.com/400x600/f8f9fa/6c757d?text=No+Image'">
                    </div>
                    
                    <div class="product-info">
                        <h3 class="product-title">{{ $product->name }}</h3>
                        
                        <div class="product-pricing">
                            <span class="current-price">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                        </div>
                        
                        <button class="add-to-bag-btn" 
                                data-product-id="{{ $product->id }}"
                                {{ $product->stock <= 0 ? 'disabled' : '' }}>
                            {{ $product->stock <= 0 ? 'OUT OF STOCK' : 'ADD TO BAG' }}
                        </button>
                    </div>
                </div>
            </div>
            @empty
            <!-- Fallback jika tidak ada produk -->
            <div class="col-12">
                <div class="text-center py-5">
                    <p class="text-muted">No featured products available at the moment.</p>
                    <a href="{{ route('products.index') }}" class="btn-carousel">View All Products</a>
                </div>
            </div>
            @endforelse
        </div>

        <!-- Link to view all products -->
        @if($featuredProducts->count() > 0)
        <div class="row mt-4 mb-4">
            <div class="col-12 text-center">
                <a href="{{ route('products.index') }}" class="btn-carousel">View All Products</a>
            </div>
        </div>
        @endif
    </div>
</section>

@push('styles')
<style>
/* Homepage specific container adjustments */
.container {
    max-width: 1400px;
    margin: 0 auto;
}

/* Use exact same product card styling as products page */
.product-card {
    background: #fff;
    border-radius: 0;
    overflow: hidden;
    transition: transform 0.3s ease;
    border: none;
    box-shadow: none;
    height: 100%;
    display: flex;
    flex-direction: column;
}

.product-card:hover {
    transform: translateY(-5px);
}

.product-image-container {
    position: relative;
    overflow: hidden;
    background: #f8f9fa;
    border-radius: 0;
    /* Let container adjust to image height naturally */
}

.product-image {
    width: 100%;
    height: auto; /* Let image determine its own height */
    object-fit: contain;
    display: block;
    transition: transform 0.3s ease;
    /* Remove all height constraints - let image be natural */
}

.product-card:hover .product-image {
    transform: scale(1.02); /* Reduced from 1.05 to prevent overflow */
}

.discount-badge {
    position: absolute;
    top: 15px;
    left: 15px;
    background: #ff69b4;
    color: white;
    padding: 6px 12px;
    font-size: 11px;
    font-weight: 600;
    letter-spacing: 0.5px;
    border-radius: 4px;
    z-index: 2;
}

.product-info {
    padding: 20px 0;
    text-align: center;
    flex-grow: 1;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
}

.product-title {
    font-size: 16px;
    font-weight: 400;
    color: #333;
    margin-bottom: 10px;
    line-height: 1.4;
    flex-grow: 1;
}

.product-pricing {
    margin-bottom: 15px;
}

.current-price {
    font-size: 14px;
    font-weight: 600;
    color: #333;
    margin-right: 8px;
}

.original-price {
    font-size: 14px;
    color: #999;
    text-decoration: line-through;
}

.add-to-bag-btn {
    background: transparent;
    border: 1px solid #333;
    color: #333;
    padding: 8px 20px;
    font-size: 12px;
    font-weight: 500;
    letter-spacing: 1px;
    cursor: pointer;
    transition: all 0.3s ease;
    text-transform: uppercase;
    min-height: 40px;
}

.add-to-bag-btn:hover:not(:disabled) {
    background: #333;
    color: white;
}

.add-to-bag-btn:disabled {
    background: #f5f5f5;
    border-color: #ddd;
    color: #999;
    cursor: not-allowed;
}

/* Keep existing carousel and navigation styles */
.navbar-brand {
    margin-right: auto;
}

.navbar-toggler {
    margin-left: 5px;
    order: 2;
}

.nav-login-button {
    background-color: #ffc0cb;
    color: #000;
    font-size: 14px;
    padding: 8px 20px;
    border-radius: 50px;
    text-decoration: none;
    transition: 0.3s;
}

.nav-login-button:hover {
    background-color: #ff8fab;
}

.navbar-toggler {
    border: none;
    font-size: 1.25rem;
}

.navbar-toggler:focus,
.btn-close:focus {
    box-shadow: none;
    outline: none;
}

.nav-link {
    color: #666777;
    font-weight: 500;
    position: relative;
}

.nav-link:hover,
.nav-link.active {
    color: #000;
}

.nav-link:before {
    content: "";
    position: absolute;
    bottom: 0;
    left: 50%;
    transform: translateX(-50%);
    width: 0;
    height: 2px;
    background-color: #ffc0cb;
    visibility: hidden;
    transition: 0.3s ease-in-out;
}

.nav-link:hover:before,
.nav-link.active::before {
    width: 100%;
    visibility: visible;
}

.home .home-banner-1,
.home .home-banner-2 {
    background-repeat: no-repeat;
    background-size: cover;
    background-position: 50% center;
    min-height: 650px;
    position: relative;
}

.home .home-banner .home-banner-text {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-110%, -50%);
}

.home .home-banner .home-banner-text h1 {
    font-size: 6rem;
}

.home .carousel-indicators [data-bs-target] {
    background-color: #000;
    width: 2.5rem;
    height: 0.15rem;
}

.home .carousel-control-next-icon,
.home .carousel-control-prev-icon {
    background: transparent;
}

.home .slider-icon {
    font-size: 3rem;
    font-weight: 600;
    color: #000;
}

.btn-carousel {
    background-color: #ffc0cb;
    color: #fff;
    font-size: 14px;
    padding: 8px 20px;
    border-radius: 8px;
    text-decoration: none;
    transition: 0.3s;
}

.btn-carousel:hover {
    background-color: #ff8fab;
}

img{
    max-width: 100%;
}

.products {
    padding-top: 1.1rem;
}

.headline h2::before {
    content: "";
    width: 21%;
    height: 0.125rem;
    background-color: #ffc0cb;
    position: absolute;
    bottom: 0;
    left: 50%;
    transform: translate(-50%, -50%);
    border-radius: 0.635rem;
}

/* Responsive adjustments */
@media (max-width: 1200px) {
    .container {
        max-width: 100%;
    }
}

@media (max-width: 768px) {
    .container {
        padding-left: 15px;
        padding-right: 15px;
    }
    
    .product-info {
        padding: 15px 0;
    }
    
    .product-title {
        font-size: 15px;
    }
    
    .home .home-banner .home-banner-text h1 {
        font-size: 4rem;
    }
}

@media (max-width: 576px) {
    .row {
        margin: 0 -10px;
    }
    
    .col-sm-12 {
        padding: 0 10px;
        margin-bottom: 30px;
    }
    
    .home .home-banner .home-banner-text h1 {
        font-size: 3rem;
    }
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add to cart functionality for homepage - using same logic as products page
    document.querySelectorAll('.add-to-bag-btn').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            
            if (this.disabled) return;
            
            const productId = this.getAttribute('data-product-id');
            const originalText = this.textContent;
            
            // Show loading state
            this.textContent = 'ADDING...';
            this.disabled = true;
            
            // Add your add to cart logic here
            fetch('/cart/add', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    product_id: productId,
                    quantity: 1
                })
            })
            .then(response => response.json())
            .then(data => {
                if(data.success) {
                    // Show success state
                    this.textContent = 'ADDED!';
                    this.style.background = '#28a745';
                    this.style.borderColor = '#28a745';
                    this.style.color = 'white';
                    
                    // Reset after 2 seconds
                    setTimeout(() => {
                        this.textContent = originalText;
                        this.disabled = false;
                        this.style.background = '';
                        this.style.borderColor = '';
                        this.style.color = '';
                    }, 2000);
                    
                    // Update cart count if you have one
                    updateCartCount();
                } else {
                    throw new Error(data.message || 'Failed to add product to bag.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred: ' + error.message);
                
                // Reset button
                this.textContent = originalText;
                this.disabled = false;
            });
        });
    });
});

// Function to update cart count (if you have a cart counter in your layout)
function updateCartCount() {
    fetch('/cart/count', {
        method: 'GET',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        const cartCounter = document.querySelector('.cart-count');
        if (cartCounter && data.count !== undefined) {
            cartCounter.textContent = data.count;
        }
    })
    .catch(error => {
        console.error('Error updating cart count:', error);
    });
}
</script>
@endpush
@endsection