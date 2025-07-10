{{-- File path: resources/views/components/cart-offcanvas-content.blade.php --}}

@if($cartItems->isEmpty())
    <div class="text-center py-5">
        <i class="fas fa-shopping-cart fa-3x text-muted mb-3"></i>
        <h6 class="text-muted">Your cart is empty</h6>
        <p class="text-muted small">Start shopping and add products to your cart</p>
        <a href="{{ route('products.index') }}" class="btn btn-primary btn-sm mt-2">
            <i class="fas fa-shopping-bag me-2"></i>Start Shopping
        </a>
    </div>
@else
    {{-- Cart Items --}}
    <div class="cart-items">
        @foreach($cartItems as $item)
            <div class="cart-item d-flex align-items-center mb-3 pb-3 border-bottom">
                {{-- Product Image --}}
                <div class="flex-shrink-0 me-3">
                    @php
                        $imageUrl = '';
                        if (isset($item->options['image']) && !empty($item->options['image'])) {
                            $imageUrl = asset('images/' . $item->options['image']);
                        }
                    @endphp
                    
                    @if($imageUrl)
                        <img src="{{ $imageUrl }}" 
                             alt="{{ $item->name }}" 
                             class="rounded" 
                             style="width: 60px; height: 60px; object-fit: cover;"
                             onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                        <div class="bg-light rounded d-none align-items-center justify-content-center" 
                             style="width: 60px; height: 60px;">
                            <i class="fas fa-image text-muted"></i>
                        </div>
                    @else
                        <div class="bg-light rounded d-flex align-items-center justify-content-center" 
                             style="width: 60px; height: 60px;">
                            <i class="fas fa-image text-muted"></i>
                        </div>
                    @endif
                </div>

                {{-- Product Details --}}
                <div class="flex-grow-1">
                    <h6 class="mb-1 fw-bold" style="font-size: 0.9rem;">{{ $item->name }}</h6>
                    
                    {{-- Size Information --}}
                    @if(isset($item->options['has_size']) && $item->options['has_size'] && isset($item->options['size_name']))
                        <div class="small text-muted mb-1">
                            <span class="badge bg-light text-dark border">
                                Size: {{ $item->options['size_display'] ?? $item->options['size_name'] }}
                            </span>
                        </div>
                    @endif
                    
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <span class="text-primary fw-bold">Rp {{ number_format($item->price, 0, ',', '.') }}</span>
                            <div class="small text-muted">Qty: {{ $item->qty }}</div>
                        </div>
                        <div class="d-flex align-items-center">
                            {{-- Quantity Controls --}}
                            <div class="btn-group btn-group-sm me-2" role="group">
                                <button type="button" class="btn btn-outline-secondary update-cart-qty" 
                                        data-rowid="{{ $item->rowId }}" 
                                        data-action="decrease">
                                    <i class="fas fa-minus"></i>
                                </button>
                                <span class="btn btn-outline-secondary">{{ $item->qty }}</span>
                                <button type="button" class="btn btn-outline-secondary update-cart-qty" 
                                        data-rowid="{{ $item->rowId }}" 
                                        data-action="increase">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
                            {{-- Remove Button --}}
                            <button type="button" class="btn btn-outline-danger btn-sm remove-cart-item" 
                                    data-rowid="{{ $item->rowId }}">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    {{-- Cart Summary --}}
    <div class="cart-summary mt-4 pt-3 border-top">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <span class="fw-bold">Subtotal:</span>
            <span class="fw-bold text-primary">
                @php
                    // Handle the formatted string properly
                    $totalValue = str_replace([',', '.'], ['', ''], $cartTotal);
                    $totalNumeric = intval($totalValue) / 100; // Convert back from cents
                @endphp
                Rp {{ number_format($totalNumeric, 0, ',', '.') }}
            </span>
        </div>
        <div class="d-flex justify-content-between align-items-center mb-3">
            <small class="text-muted">{{ $cartCount }} item(s)</small>
            <button type="button" class="btn btn-outline-danger btn-sm clear-cart">
                <i class="fas fa-trash me-1"></i>Clear Cart
            </button>
        </div>
    </div>
@endif