{{-- resources/views/components/product-grid.blade.php --}}
<div class="row g-4">
    @forelse($products as $product)
        <div class="col-lg-4 col-md-6 col-sm-12">
            <div class="product-card">
                {{-- Clickable Product Image and Info --}}
                <a href="{{ route('products.show', $product->slug) }}" class="product-link">
                    <div class="product-image-container position-relative">
                        @if($showDiscount && isset($product->metadata['is_discounted']) && $product->metadata['is_discounted'])
                            <div class="discount-badge">
                                <span>DISCOUNT</span>
                            </div>
                        @endif
                        <img src="{{ $product->image ? asset('images/' . $product->image) : asset('images/placeholder.jpg') }}"
                             alt="{{ $product->name }}"
                             class="product-image"
                             onerror="this.src='https://via.placeholder.com/400x600/f8f9fa/6c757d?text=No+Image'">
                    </div>
                    <div class="product-info">
                        <h3 class="product-title">{{ $product->name }}</h3>
                        <div class="product-pricing">
                            @if($showDiscount && isset($product->metadata['original_price']) && $product->metadata['original_price'])
                                <span class="current-price">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                                <span class="original-price">Rp {{ number_format($product->metadata['original_price'], 0, ',', '.') }}</span>
                            @else
                                <span class="current-price">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                            @endif
                        </div>
                    </div>
                </a>
                
                {{-- Add to Cart Button (outside the link) --}}
                <div class="product-actions">
                    @if($useFormCart)
                        @php
                            // Check if product has sizes and get first available size
                            $hasSize = $product->hasSizes();
                            $firstAvailableSize = null;
                            $isInStock = false;
                            
                            if ($hasSize) {
                                $firstAvailableSize = $product->availableSizes()->first();
                                $isInStock = $firstAvailableSize !== null;
                            } else {
                                $isInStock = $product->stock > 0;
                            }
                        @endphp
                        
                        @if($isInStock)
                            <form action="{{ route('cart.add') }}" method="POST" class="d-grid add-to-cart-form">
                                @csrf
                                <input type="hidden" name="id" value="{{ $product->id }}">
                                <input type="hidden" name="quantity" value="1">
                                
                                {{-- For products with sizes, include default size --}}
                                @if($hasSize && $firstAvailableSize)
                                    <input type="hidden" name="size_id" value="{{ $firstAvailableSize->id }}">
                                    <input type="hidden" name="default_size_id" value="{{ $firstAvailableSize->id }}">
                                @endif
                                
                                <button type="submit" class="add-to-bag-btn">
                                    @if($hasSize && $firstAvailableSize)
                                        {{ $buttonText }} (Size {{ $firstAvailableSize->name }})
                                    @else
                                        {{ $buttonText }}
                                    @endif
                                </button>
                            </form>
                        @else
                            {{-- Out of stock or no available sizes --}}
                            <button class="add-to-bag-btn" disabled>
                                {{ $outOfStockText }}
                            </button>
                        @endif
                        
                        {{-- Size info for products with multiple sizes --}}
                        @if($hasSize && $product->availableSizes()->count() > 1)
                            <div class="size-info mt-1">
                                <small class="text-muted">
                                    <i class="fas fa-info-circle me-1"></i>
                                    More sizes available - <a href="{{ route('products.show', $product->slug) }}" class="text-decoration-none">view details</a>
                                </small>
                            </div>
                        @endif
                    @else
                        <button class="add-to-bag-btn"
                                data-product-id="{{ $product->id }}"
                                {{ !$isInStock ? 'disabled' : '' }}>
                            {{ !$isInStock ? $outOfStockText : $buttonText }}
                        </button>
                    @endif
                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="text-center py-5">
                <p class="text-muted">{{ $emptyMessage }}</p>
                @if($emptyButtonText)
                    <a href="{{ route('products.index') }}" class="{{ $emptyButtonClass }} mt-3">{{ $emptyButtonText }}</a>
                @endif
            </div>
        </div>
    @endforelse
</div>

<style>
/* Product Grid Clickable Enhancement */
.product-link {
    text-decoration: none;
    color: inherit;
    display: block;
}

.product-link:hover {
    text-decoration: none;
    color: inherit;
}

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
}

.product-image {
    width: 100%;
    height: auto;
    object-fit: contain;
    display: block;
    transition: transform 0.3s ease;
}

.product-card:hover .product-image {
    transform: scale(1.02);
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
    padding: 20px 0 15px 0;
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
    transition: color 0.3s ease;
}

.product-link:hover .product-title {
    color: #ffc0cb;
}

.product-pricing {
    margin-bottom: 0;
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

.product-actions {
    padding: 0 20px 20px 20px;
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
    width: 100%;
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

/* Size info styling */
.size-info {
    text-align: center;
}

.size-info a {
    color: #ffc0cb;
    font-weight: 500;
}

.size-info a:hover {
    color: #ff8fab;
}

/* Grid layout */
.row.g-4 {
    display: flex;
    flex-wrap: wrap;
}

.row.g-4 > [class*="col-"] {
    display: flex;
    margin-bottom: 2rem;
}

.row.g-4 .product-card {
    width: 100%;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .product-info {
        padding: 15px 0 10px 0;
    }
    
    .product-actions {
        padding: 0 15px 15px 15px;
    }
    
    .product-title {
        font-size: 15px;
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
}
</style>