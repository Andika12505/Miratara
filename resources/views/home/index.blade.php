@extends('layouts.main')

@section('title', 'Home - MiraTara Fashion')

@section('content')

{{-- Bagian Carousel (Home Section) --}}
<section id="home" class="home overflow-hidden">
    <div id="carouselExampleIndicators" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-indicators">
            <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
            <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="1" aria-label="Slide 2"></button>
        </div>
        <div class="carousel-inner">
            <div class="carousel-item active">
                <div class="home-banner home-banner-1" style="background-image: url({{ asset('images/miaw1.png') }});">
                    <div class="home-banner-text">
                        {{-- Teks atau tombol bisa diletakkan di sini --}}
                    </div>
                </div>
            </div>
            {{-- Anda bisa menambahkan carousel-item lainnya di sini --}}
            <div class="carousel-item">
                <div class="home-banner home-banner-2" style="background-image: url({{ asset('images/miaw2.png') }});">
                    {{-- Ganti dengan gambar banner kedua Anda --}}
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

{{-- Bagian Produk (Products Section) --}}
<section id="our-products" class="container my-5">
    <div class="row text-center">
        <div class="col-lg-12 m-auto">
            <h2 class="pb-3 position-relative d-inline-block">Our Products</h2>
        </div>
    </div>
    <div class="row mt-4 g-4">
        @forelse($products as $product)
        <div class="col-lg-3 col-md-6 col-sm-12">
            <div class="product-card">
                <div class="product-image-container">
                    <img src="{{ $product->image ? asset('storage/' . $product->image) : 'https://via.placeholder.com/400x600/f8f9fa/6c757d?text=' . urlencode($product->name) }}" 
                         alt="{{ $product->name }}" 
                         class="product-image"
                         onerror="this.src='https://via.placeholder.com/400x600/f8f9fa/6c757d?text=No+Image'">
                </div>
                
                <div class="product-info">
                    <h3 class="product-title">{{ $product->name }}</h3>
                    
                    <div class="product-pricing">
                        <span class="current-price">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                    </div>
                    
                    <form action="{{ route('cart.add') }}" method="POST" class="d-grid add-to-cart-form">
                        @csrf
                        <input type="hidden" name="id" value="{{ $product->id }}">
                        <input type="hidden" name="quantity" value="1">
                        <button type="submit" class="add-to-bag-btn" {{ $product->stock <= 0 ? 'disabled' : '' }}>
                            {{ $product->stock <= 0 ? 'OUT OF STOCK' : 'ADD TO BAG' }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="text-center py-5">
                <p class="text-muted">Produk akan segera hadir!</p>
            </div>
        </div>
        @endforelse
    </div>

    @if($products->count() > 0)
    <div class="row mt-4 mb-4">
        <div class="col-12 text-center">
            <a href="{{-- route('products.index') --}}" class="btn-carousel">View All Products</a>
        </div>
    </div>
    @endif
</section>

@endsection


@push('styles')
<style>
/* Style umum dan produk card bisa diletakkan di sini atau di file CSS utama */
.product-card {
    border: none;
    transition: transform 0.3s ease;
}
.product-card:hover {
    transform: translateY(-5px);
}
.product-image {
    width: 100%;
    height: auto;
    object-fit: cover;
}
.product-info {
    padding: 20px 0;
    text-align: center;
}
.product-title {
    font-size: 1rem;
    font-weight: 400;
    color: #333;
    margin-bottom: 10px;
}
.current-price {
    font-size: 1rem;
    font-weight: 600;
}
.add-to-bag-btn {
    background: transparent;
    border: 1px solid #333;
    color: #333;
    padding: 10px;
    font-size: 0.8rem;
    font-weight: 500;
    letter-spacing: 1px;
    cursor: pointer;
    transition: all 0.3s ease;
    text-transform: uppercase;
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
/* Style Carousel */
.home .home-banner-1,
.home .home-banner-2 {
    background-repeat: no-repeat;
    background-size: cover;
    background-position: center center;
    min-height: 650px;
}
/* Style lainnya bisa Anda pindahkan ke file CSS terpisah untuk kerapian */
</style>
@endpush

{{-- Push script tidak diperlukan lagi karena logika Add to Cart sudah menyatu dengan form --}}