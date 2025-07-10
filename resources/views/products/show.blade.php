{{-- resources/views/products/show.blade.php --}}
@extends('layouts.main')

@section('title', $product->name . ' - MiraTara Fashion')

@section('content')
<div class="container-fluid px-4 py-4">
    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('homepage') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('products.index') }}">Products</a></li>
            @if($category)
                <li class="breadcrumb-item"><a href="{{ route('products.index', ['category_id' => $category->id]) }}">{{ $category->name }}</a></li>
            @endif
            <li class="breadcrumb-item active" aria-current="page">{{ $product->name }}</li>
        </ol>
    </nav>

    <div class="row">
        {{-- Product Image Section --}}
        <div class="col-lg-6 col-md-12 mb-4">
            <div class="product-image-section">
                <div class="main-product-image">
                    <img src="{{ $product->image ? asset('images/' . $product->image) : asset('images/placeholder.jpg') }}"
                         alt="{{ $product->name }}"
                         class="img-fluid product-detail-image"
                         onerror="this.src='https://via.placeholder.com/600x800/f8f9fa/6c757d?text=No+Image'">
                </div>
                
                {{-- Stock Badge --}}
                <div class="stock-badge">
                    @if($product->stock > 0)
                        <span class="badge bg-success">In Stock ({{ $product->stock }} available)</span>
                    @else
                        <span class="badge bg-danger">Out of Stock</span>
                    @endif
                </div>
            </div>
        </div>

        {{-- Product Details Section --}}
        <div class="col-lg-6 col-md-12">
            <div class="product-details-section">
                {{-- Product Title & Price --}}
                <div class="product-header mb-4">
                    <h1 class="product-detail-title">{{ $product->name }}</h1>
                    <div class="product-pricing mb-3">
                        @if(isset($product->metadata['is_discounted']) && $product->metadata['is_discounted'] && isset($product->metadata['original_price']))
                            <span class="current-price">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                            <span class="original-price">Rp {{ number_format($product->metadata['original_price'], 0, ',', '.') }}</span>
                            <span class="discount-badge">SALE</span>
                        @else
                            <span class="current-price">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                        @endif
                    </div>
                    @if($category)
                        <p class="product-category">
                            <small class="text-muted">Category: <a href="{{ route('products.index', ['category_id' => $category->id]) }}">{{ $category->name }}</a></small>
                        </p>
                    @endif
                </div>

                {{-- Product Description --}}
                @if($product->description)
                <div class="product-description mb-4">
                    <h5>Description</h5>
                    <p>{{ $product->description }}</p>
                </div>
                @endif

                {{-- Size Selection (Placeholder for future) --}}
                <div class="size-selection mb-4">
                    <h5>Size</h5>
                    <div class="size-options">
                        <div class="alert alert-info">
                            <small><i class="fas fa-info-circle me-2"></i>Size selection will be available soon. For now, contact us for size information.</small>
                        </div>
                    </div>
                </div>

                {{-- Quantity & Add to Cart --}}
                <div class="purchase-section mb-4">
                    <form action="{{ route('cart.add') }}" method="POST" class="add-to-cart-form">
                        @csrf
                        <input type="hidden" name="id" value="{{ $product->id }}">
                        
                        <div class="quantity-section mb-3">
                            <label for="quantity" class="form-label">Quantity</label>
                            <div class="input-group quantity-input-group" style="width: 150px;">
                                <button type="button" class="btn btn-outline-secondary quantity-btn" onclick="decreaseQuantity()">-</button>
                                <input type="number" id="quantity" name="quantity" class="form-control text-center" value="1" min="1" max="{{ $product->stock }}" {{ $product->stock <= 0 ? 'disabled' : '' }}>
                                <button type="button" class="btn btn-outline-secondary quantity-btn" onclick="increaseQuantity()">+</button>
                            </div>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary add-to-cart-btn" {{ $product->stock <= 0 ? 'disabled' : '' }}>
                                <i class="fas fa-shopping-cart me-2"></i>
                                {{ $product->stock <= 0 ? 'OUT OF STOCK' : 'ADD TO CART' }}
                            </button>
                        </div>
                    </form>
                </div>

                {{-- Product Attributes --}}
                <div class="product-attributes">
                    @if(!empty($vibeAttributes) || !empty($generalTags) || $origin)
                    <h5>Product Details</h5>
                    <div class="attributes-grid">
                        @foreach($vibeAttributes as $attribute => $values)
                            @if(!empty($values))
                                <div class="attribute-item">
                                    <strong>{{ ucfirst(str_replace('_', ' ', $attribute)) }}:</strong>
                                    <span>
                                        @if(is_array($values))
                                            {{ implode(', ', array_map('ucfirst', $values)) }}
                                        @else
                                            {{ ucfirst($values) }}
                                        @endif
                                    </span>
                                </div>
                            @endif
                        @endforeach

                        @if(!empty($generalTags))
                            <div class="attribute-item">
                                <strong>Features:</strong>
                                <span>{{ implode(', ', array_map('ucfirst', $generalTags)) }}</span>
                            </div>
                        @endif

                        @if($origin)
                            <div class="attribute-item">
                                <strong>Origin:</strong>
                                <span>{{ $origin }}</span>
                            </div>
                        @endif
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Related Products Section --}}
    @if($relatedProducts->count() > 0)
    <div class="related-products-section mt-5 pt-5">
        <div class="row">
            <div class="col-12">
                <h3 class="section-title mb-4">You Might Also Like</h3>
            </div>
        </div>
        
        <x-product-grid
            :products="$relatedProducts"
            :show-discount="true"
            :use-form-cart="true"
            empty-message=""
            empty-button-text=""
            empty-button-class=""
            button-text="ADD TO BAG"
            out-of-stock-text="OUT OF STOCK"
        />
    </div>
    @endif
</div>
@endsection

@push('styles')
<style>
/* Product Detail Page Styles */
.container-fluid {
    max-width: 1400px;
    margin: 0 auto;
}

/* Breadcrumb */
.breadcrumb {
    background: none;
    padding: 0;
    margin-bottom: 1rem;
}

.breadcrumb-item a {
    color: #666;
    text-decoration: none;
}

.breadcrumb-item a:hover {
    color: #ffc0cb;
}

.breadcrumb-item.active {
    color: #333;
}

/* Product Image Section */
.product-image-section {
    position: relative;
}

.main-product-image {
    background: #f8f9fa;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

.product-detail-image {
    width: 100%;
    height: auto;
    object-fit: contain;
    display: block;
}

.stock-badge {
    position: absolute;
    top: 15px;
    right: 15px;
    z-index: 10;
}

/* Product Details Section */
.product-details-section {
    padding-left: 20px;
}

.product-detail-title {
    font-size: 2rem;
    font-weight: 400;
    color: #333;
    margin-bottom: 10px;
    line-height: 1.3;
}

.product-pricing {
    display: flex;
    align-items: center;
    gap: 15px;
    flex-wrap: wrap;
}

.current-price {
    font-size: 1.5rem;
    font-weight: 600;
    color: #333;
}

.original-price {
    font-size: 1.2rem;
    color: #999;
    text-decoration: line-through;
}

.discount-badge {
    background: #ff69b4;
    color: white;
    padding: 4px 8px;
    font-size: 0.75rem;
    font-weight: 600;
    letter-spacing: 0.5px;
    border-radius: 4px;
}

.product-category a {
    color: #ffc0cb;
    text-decoration: none;
}

.product-category a:hover {
    text-decoration: underline;
}

/* Product Description */
.product-description h5,
.size-selection h5,
.product-attributes h5 {
    font-size: 1.1rem;
    font-weight: 600;
    color: #333;
    margin-bottom: 15px;
    border-bottom: 1px solid #eee;
    padding-bottom: 5px;
}

.product-description p {
    color: #666;
    line-height: 1.6;
    margin-bottom: 0;
}

/* Size Selection */
.size-options .alert {
    border: 1px solid #ffc0cb;
    background: rgba(255, 192, 203, 0.1);
    color: #666;
    margin-bottom: 0;
}

/* Purchase Section */
.quantity-input-group {
    max-width: 150px;
}

.quantity-btn {
    border-color: #ddd;
    color: #666;
    font-weight: 600;
    width: 40px;
}

.quantity-btn:hover {
    background-color: #f8f9fa;
    border-color: #ffc0cb;
    color: #333;
}

.quantity-input-group input {
    border-left: none;
    border-right: none;
    font-weight: 600;
}

.add-to-cart-btn {
    background-color: #ffc0cb;
    border-color: #ffc0cb;
    color: white;
    font-weight: 600;
    padding: 12px 0;
    letter-spacing: 1px;
    transition: all 0.3s ease;
}

.add-to-cart-btn:hover:not(:disabled) {
    background-color: #ff8fab;
    border-color: #ff8fab;
    transform: translateY(-2px);
}

.add-to-cart-btn:disabled {
    background-color: #f5f5f5;
    border-color: #ddd;
    color: #999;
    cursor: not-allowed;
}

/* Product Attributes */
.attributes-grid {
    display: grid;
    gap: 10px;
}

.attribute-item {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    padding: 8px 0;
    border-bottom: 1px solid #f0f0f0;
}

.attribute-item:last-child {
    border-bottom: none;
}

.attribute-item strong {
    color: #333;
    min-width: 120px;
    font-weight: 600;
}

.attribute-item span {
    color: #666;
    flex: 1;
}

/* Related Products Section */
.related-products-section {
    border-top: 1px solid #eee;
}

.section-title {
    font-size: 1.8rem;
    font-weight: 400;
    color: #333;
    text-align: center;
    position: relative;
    display: inline-block;
    width: 100%;
}

.section-title::after {
    content: "";
    width: 60px;
    height: 2px;
    background-color: #ffc0cb;
    position: absolute;
    bottom: -8px;
    left: 50%;
    transform: translateX(-50%);
    border-radius: 2px;
}

/* Responsive Design */
@media (max-width: 991.98px) {
    .product-details-section {
        padding-left: 0;
        margin-top: 30px;
    }
    
    .product-detail-title {
        font-size: 1.7rem;
    }
    
    .current-price {
        font-size: 1.3rem;
    }
}

@media (max-width: 767.98px) {
    .product-detail-title {
        font-size: 1.5rem;
    }
    
    .current-price {
        font-size: 1.2rem;
    }
    
    .original-price {
        font-size: 1rem;
    }
    
    .product-pricing {
        flex-direction: column;
        align-items: flex-start;
        gap: 8px;
    }
    
    .attribute-item {
        flex-direction: column;
        gap: 5px;
    }
    
    .attribute-item strong {
        min-width: auto;
    }
}

@media (max-width: 575.98px) {
    .container-fluid {
        padding-left: 15px;
        padding-right: 15px;
    }
    
    .product-detail-title {
        font-size: 1.3rem;
    }
}
</style>
@endpush

@push('scripts')
<script>
// Quantity controls
function increaseQuantity() {
    const input = document.getElementById('quantity');
    const currentValue = parseInt(input.value);
    const maxValue = parseInt(input.getAttribute('max'));
    
    if (currentValue < maxValue) {
        input.value = currentValue + 1;
    }
}

function decreaseQuantity() {
    const input = document.getElementById('quantity');
    const currentValue = parseInt(input.value);
    const minValue = parseInt(input.getAttribute('min'));
    
    if (currentValue > minValue) {
        input.value = currentValue - 1;
    }
}

// Add to cart form handling
document.addEventListener('DOMContentLoaded', function() {
    const addToCartForm = document.querySelector('.add-to-cart-form');
    
    if (addToCartForm) {
        addToCartForm.addEventListener('submit', function(e) {
            const submitBtn = this.querySelector('.add-to-cart-btn');
            const originalText = submitBtn.innerHTML;
            
            // Show loading state
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Adding...';
            submitBtn.disabled = true;
            
            // Re-enable button after 3 seconds in case of issues
            setTimeout(() => {
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            }, 3000);
        });
    }
});
</script>
@endpush