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

                {{-- Size Selection --}}
                <div class="size-selection mb-4">
                    <h5>Size</h5>
                    @if($product->hasSizes())
                        <div class="size-options">
                            <div class="size-buttons-grid">
                                @foreach($product->sizes as $size)
                                    <div class="size-option {{ $size->pivot->stock <= 0 ? 'out-of-stock' : '' }}" 
                                         data-size-id="{{ $size->id }}"
                                         data-size-name="{{ $size->name }}"
                                         data-stock="{{ $size->pivot->stock }}">
                                        <input type="radio" 
                                               name="size_id" 
                                               id="size_{{ $size->id }}" 
                                               value="{{ $size->id }}"
                                               class="size-radio"
                                               {{ $size->pivot->stock <= 0 || !$size->pivot->is_available ? 'disabled' : '' }}
                                               {{ $loop->first && $size->pivot->stock > 0 ? 'checked' : '' }}>
                                        <label for="size_{{ $size->id }}" class="size-label">
                                            <span class="size-name">{{ $size->name }}</span>
                                            <span class="size-display">{{ $size->display }}</span>
                                            @if($size->pivot->stock <= 0)
                                                <span class="stock-info out">Out of Stock</span>
                                            @elseif($size->pivot->stock <= 3)
                                                <span class="stock-info low">Only {{ $size->pivot->stock }} left</span>
                                            @else
                                                <span class="stock-info available">In Stock</span>
                                            @endif
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                            <div class="selected-size-info mt-2">
                                <small class="text-muted">Select a size to continue</small>
                            </div>
                        </div>
                    @else
                        <div class="size-options">
                            <div class="alert alert-info">
                                <small><i class="fas fa-info-circle me-2"></i>One size fits all. No size selection needed.</small>
                            </div>
                        </div>
                    @endif
                </div>

                {{-- Quantity & Add to Cart --}}
                <div class="purchase-section mb-4">
                    <form action="{{ route('cart.add') }}" method="POST" class="add-to-cart-form">
                        @csrf
                        <input type="hidden" name="id" value="{{ $product->id }}">
                        <input type="hidden" name="size_id" id="selected_size_id" value="">
                        
                        <div class="quantity-section mb-3">
                            <label for="quantity" class="form-label">Quantity</label>
                            <div class="input-group quantity-input-group" style="width: 150px;">
                                <button type="button" class="btn btn-outline-secondary quantity-btn" onclick="decreaseQuantity()">-</button>
                                <input type="number" id="quantity" name="quantity" class="form-control text-center" value="1" min="1" max="{{ $product->display_stock }}" {{ $product->isInStock() ? '' : 'disabled' }}>
                                <button type="button" class="btn btn-outline-secondary quantity-btn" onclick="increaseQuantity()">+</button>
                            </div>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary add-to-cart-btn" id="addToCartBtn" {{ $product->isInStock() ? '' : 'disabled' }}>
                                <i class="fas fa-shopping-cart me-2"></i>
                                <span id="addToCartText">{{ $product->isInStock() ? 'ADD TO CART' : 'OUT OF STOCK' }}</span>
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
.size-buttons-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
    gap: 10px;
    margin-bottom: 15px;
}

.size-option {
    position: relative;
}

.size-radio {
    position: absolute;
    opacity: 0;
    cursor: pointer;
    height: 0;
    width: 0;
}

.size-label {
    display: block;
    padding: 12px 15px;
    border: 2px solid #ddd;
    border-radius: 6px;
    text-align: center;
    cursor: pointer;
    transition: all 0.3s ease;
    background: white;
    user-select: none;
}

.size-label:hover {
    border-color: #ffc0cb;
    background: rgba(255, 192, 203, 0.05);
}

.size-radio:checked + .size-label {
    border-color: #ffc0cb;
    background: rgba(255, 192, 203, 0.1);
    color: #333;
}

.size-radio:disabled + .size-label {
    background: #f8f9fa;
    color: #999;
    border-color: #e9ecef;
    cursor: not-allowed;
    opacity: 0.6;
}

.size-option.out-of-stock .size-label {
    background: #f8f9fa;
    color: #999;
    border-color: #e9ecef;
    cursor: not-allowed;
    position: relative;
}

.size-option.out-of-stock .size-label::after {
    content: '';
    position: absolute;
    top: 50%;
    left: 10%;
    right: 10%;
    height: 1px;
    background: #999;
    transform: translateY(-50%);
}

.size-name {
    display: block;
    font-weight: 600;
    font-size: 1rem;
    margin-bottom: 2px;
}

.size-display {
    display: block;
    font-size: 0.8rem;
    color: #666;
    margin-bottom: 4px;
}

.stock-info {
    display: block;
    font-size: 0.7rem;
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.stock-info.available {
    color: #28a745;
}

.stock-info.low {
    color: #ffc107;
}

.stock-info.out {
    color: #dc3545;
}

.selected-size-info {
    text-align: center;
    min-height: 20px;
}

.size-error {
    color: #dc3545;
    font-size: 0.85rem;
    display: none;
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

.related-products-section .product-info {
    text-align: center;
}

.related-products-section .product-pricing {
    justify-content: center;
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
// Size and quantity management
document.addEventListener('DOMContentLoaded', function() {
    const quantityInput = document.getElementById('quantity');
    const addToCartForm = document.querySelector('.add-to-cart-form');
    const addToCartBtn = document.getElementById('addToCartBtn');
    const addToCartText = document.getElementById('addToCartText');
    const selectedSizeInput = document.getElementById('selected_size_id');
    const selectedSizeInfo = document.querySelector('.selected-size-info small');
    const sizeRadios = document.querySelectorAll('.size-radio');
    const hasSize = {{ $product->hasSizes() ? 'true' : 'false' }};

    // Size selection handling
    sizeRadios.forEach(radio => {
        radio.addEventListener('change', function() {
            if (this.checked) {
                const sizeOption = this.closest('.size-option');
                const sizeName = sizeOption.dataset.sizeName;
                const stock = parseInt(sizeOption.dataset.stock);
                
                // Update hidden input
                selectedSizeInput.value = this.value;
                
                // Update quantity max based on selected size stock
                quantityInput.setAttribute('max', stock);
                if (parseInt(quantityInput.value) > stock) {
                    quantityInput.value = Math.min(stock, 1);
                }
                
                // Update selected size info
                selectedSizeInfo.textContent = `Size ${sizeName} selected`;
                selectedSizeInfo.className = 'text-success';
                
                // Enable add to cart if size selected and has stock
                updateAddToCartButton();
            }
        });
    });

    // Initial setup
    function updateAddToCartButton() {
        if (!hasSize) {
            // No sizes required, button should work
            return;
        }
        
        const selectedSize = document.querySelector('.size-radio:checked');
        if (!selectedSize) {
            addToCartBtn.disabled = true;
            addToCartText.textContent = 'SELECT SIZE';
            selectedSizeInfo.textContent = 'Select a size to continue';
            selectedSizeInfo.className = 'text-muted';
        } else {
            const sizeOption = selectedSize.closest('.size-option');
            const stock = parseInt(sizeOption.dataset.stock);
            
            if (stock > 0) {
                addToCartBtn.disabled = false;
                addToCartText.textContent = 'ADD TO CART';
            } else {
                addToCartBtn.disabled = true;
                addToCartText.textContent = 'OUT OF STOCK';
            }
        }
    }

    // Initial button state
    updateAddToCartButton();

    // Form submission validation
    addToCartForm.addEventListener('submit', function(e) {
        if (hasSize && !selectedSizeInput.value) {
            e.preventDefault();
            alert('Please select a size before adding to cart.');
            return false;
        }
        
        const submitBtn = this.querySelector('.add-to-cart-btn');
        const originalText = submitBtn.innerHTML;
        
        // Show loading state
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Adding...';
        submitBtn.disabled = true;
        
        // Re-enable button after 3 seconds in case of issues
        setTimeout(() => {
            submitBtn.innerHTML = originalText;
            updateAddToCartButton();
        }, 3000);
    });
});

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
</script>
@endpush