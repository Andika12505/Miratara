<div class="row g-4">
    @forelse($products as $product)
        <div class="col-lg-4 col-md-6 col-sm-12">
            <div class="product-card">
                <div class="product-image-container position-relative">
                    @if($showDiscount && isset($product->metadata['is_discounted']) && $product->metadata['is_discounted'])
                        <div class="discount-badge">
                            <span>DISKON</span>
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
                    
                    @if($useFormCart)
                        <form action="{{ route('cart.add') }}" method="POST" class="d-grid add-to-cart-form">
                            @csrf
                            <input type="hidden" name="id" value="{{ $product->id }}">
                            <input type="hidden" name="quantity" value="1">
                            <button type="submit" class="add-to-bag-btn" {{ $product->stock <= 0 ? 'disabled' : '' }}>
                                {{ $product->stock <= 0 ? $outOfStockText : $buttonText }}
                            </button>
                        </form>
                    @else
                        <button class="add-to-bag-btn"
                                data-product-id="{{ $product->id }}"
                                {{ $product->stock <= 0 ? 'disabled' : '' }}>
                            {{ $product->stock <= 0 ? $outOfStockText : $buttonText }}
                        </button>
                    @endif
                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="text-center py-5">
                <p class="text-muted">{{ $emptyMessage }}</p>
                <a href="{{ route('products.index') }}" class="{{ $emptyButtonClass }} mt-3">{{ $emptyButtonText }}</a>
            </div>
        </div>
    @endforelse
</div>