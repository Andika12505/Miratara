@extends('layouts.main')

@section('title', 'Keranjang Belanja - MiraTara Fashion')

@section('content')
<div class="container my-5">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('homepage') }}">Home</a></li>
                    <li class="breadcrumb-item active">Keranjang Belanja</li>
                </ol>
            </nav>
            <h2 class="page-title">Keranjang Belanja Anda</h2>
        </div>
    </div>

    <!-- Success Message -->
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    @if(Cart::count() > 0)
    <div class="row">
        <!-- Cart Items -->
        <div class="col-lg-8">
            <div class="cart-items">
                @foreach($cartItems as $item)
                <div class="cart-item-card" data-row-id="{{ $item->rowId }}">
                    <div class="row align-items-center">
                        <!-- Product Image & Info -->
                        <div class="col-md-6">
                            <div class="d-flex align-items-center">
                                <div class="product-image-wrapper">
                                    {{-- Adjust image path to match your system --}}
                                    <img src="{{ $item->options->image ? asset('images/' . $item->options->image) : 'https://via.placeholder.com/100x100/f8f9fa/6c757d?text=No+Image' }}" 
                                         alt="{{ $item->name }}" 
                                         class="cart-product-image"
                                         onerror="this.src='https://via.placeholder.com/100x100/f8f9fa/6c757d?text=No+Image'">
                                </div>
                                <div class="product-details">
                                    <h5 class="product-name">{{ $item->name }}</h5>
                                    <p class="product-price">Rp {{ number_format($item->price, 0, ',', '.') }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Quantity Controls -->
                        <div class="col-md-3">
                            <div class="quantity-controls">
                                <label class="form-label small text-muted">Kuantitas</label>
                                <div class="input-group">
                                    <button class="btn btn-outline-secondary btn-sm qty-btn" type="button" 
                                            onclick="updateQuantity('{{ $item->rowId }}', {{ $item->qty - 1 }})">
                                        <i class="fas fa-minus"></i>
                                    </button>
                                    <input type="number" class="form-control text-center qty-input" 
                                           value="{{ $item->qty }}" min="1" max="99" 
                                           onchange="updateQuantity('{{ $item->rowId }}', this.value)">
                                    <button class="btn btn-outline-secondary btn-sm qty-btn" type="button" 
                                            onclick="updateQuantity('{{ $item->rowId }}', {{ $item->qty + 1 }})">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Subtotal & Remove -->
                        <div class="col-md-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="subtotal">
                                    <span class="subtotal-label">Subtotal</span>
                                    <span class="subtotal-amount">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</span>
                                </div>
                                <button class="btn btn-outline-danger btn-sm remove-btn" 
                                        onclick="removeItem('{{ $item->rowId }}', '{{ $item->name }}')"
                                        title="Hapus item">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Cart Summary -->
        <div class="col-lg-4">
            <div class="cart-summary">
                <div class="summary-card">
                    <h5 class="summary-title">Ringkasan Belanja</h5>
                    
                    <div class="summary-details">
                        <div class="summary-row">
                            <span>Subtotal ({{ Cart::count() }} item{{ Cart::count() > 1 ? 's' : '' }})</span>
                            <span>Rp {{ Cart::subtotal(0, ',', '.') }}</span>
                        </div>
                        <div class="summary-row">
                            <span>Pajak</span>
                            <span>Rp {{ Cart::tax(0, ',', '.') }}</span>
                        </div>
                        <hr class="summary-divider">
                        <div class="summary-row total-row">
                            <span class="total-label">Total</span>
                            <span class="total-amount">Rp {{ Cart::total(0, ',', '.') }}</span>
                        </div>
                    </div>

                    <div class="summary-actions">
                        <a href="{{ route('checkout_page') }}" class="btn btn-primary btn-lg w-100 mb-3">
                            <i class="fas fa-credit-card me-2"></i>Lanjut ke Checkout
                        </a>
                        
                        <button class="btn btn-outline-danger w-100 mb-3" onclick="clearCart()">
                            <i class="fas fa-trash me-2"></i>Kosongkan Keranjang
                        </button>
                        
                        <a href="{{ route('products.index') }}" class="btn btn-outline-secondary w-100">
                            <i class="fas fa-arrow-left me-2"></i>Lanjut Belanja
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @else
    <!-- Empty Cart State -->
    <div class="empty-cart-state">
        <div class="text-center py-5">
            <div class="empty-cart-icon">
                <i class="fas fa-shopping-cart"></i>
            </div>
            <h4 class="empty-cart-title">Keranjang Belanja Kosong</h4>
            <p class="empty-cart-description">
                Anda belum menambahkan produk apapun ke keranjang belanja. 
                Mari mulai berbelanja dan temukan produk favorit Anda!
            </p>
            <a href="{{ route('products.index') }}" class="btn btn-primary btn-lg">
                <i class="fas fa-shopping-bag me-2"></i>Mulai Belanja
            </a>
        </div>
    </div>
    @endif
</div>

@push('styles')
<style>
/* Page styling */
.page-title {
    font-size: 2rem;
    font-weight: 300;
    letter-spacing: 1px;
    color: #333;
    margin-bottom: 0;
}

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
    color: #333;
}

/* Cart Items Styling */
.cart-items {
    background: #fff;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.cart-item-card {
    padding: 1.5rem;
    border-bottom: 1px solid #f0f0f0;
    transition: background-color 0.3s ease;
}

.cart-item-card:hover {
    background-color: #f8f9fa;
}

.cart-item-card:last-child {
    border-bottom: none;
}

.product-image-wrapper {
    width: 100px;
    height: 100px;
    border-radius: 8px;
    overflow: hidden;
    background: #f8f9fa;
    margin-right: 1rem;
    flex-shrink: 0;
}

.cart-product-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.product-details {
    flex-grow: 1;
}

.product-name {
    font-size: 1.1rem;
    font-weight: 500;
    color: #333;
    margin-bottom: 0.5rem;
    line-height: 1.4;
}

.product-price {
    color: #666;
    margin-bottom: 0;
    font-size: 0.95rem;
}

/* Quantity Controls */
.quantity-controls .input-group {
    width: 120px;
}

.qty-btn {
    width: 35px;
    height: 35px;
    padding: 0;
    display: flex;
    align-items: center;
    justify-content: center;
}

.qty-input {
    height: 35px;
    border-left: none;
    border-right: none;
    font-weight: 500;
}

.qty-input:focus {
    box-shadow: none;
    border-color: #ced4da;
}

/* Subtotal & Remove */
.subtotal {
    display: flex;
    flex-direction: column;
    align-items: flex-start;
}

.subtotal-label {
    font-size: 0.85rem;
    color: #666;
    margin-bottom: 0.25rem;
}

.subtotal-amount {
    font-weight: 600;
    color: #333;
    font-size: 1.05rem;
}

.remove-btn {
    width: 35px;
    height: 35px;
    padding: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-left: 1rem;
}

/* Cart Summary */
.summary-card {
    background: #fff;
    border-radius: 8px;
    padding: 1.5rem;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    position: sticky;
    top: 100px;
}

.summary-title {
    font-size: 1.25rem;
    font-weight: 500;
    color: #333;
    margin-bottom: 1.5rem;
    padding-bottom: 1rem;
    border-bottom: 1px solid #f0f0f0;
}

.summary-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 0.75rem;
    font-size: 0.95rem;
}

.summary-row span:first-child {
    color: #666;
}

.summary-row span:last-child {
    font-weight: 500;
    color: #333;
}

.summary-divider {
    margin: 1.5rem 0;
    border-color: #e9ecef;
}

.total-row {
    font-size: 1.1rem;
    margin-bottom: 0;
}

.total-label {
    font-weight: 600;
    color: #333 !important;
}

.total-amount {
    font-weight: 700;
    color: #333 !important;
    font-size: 1.25rem;
}

.summary-actions {
    margin-top: 2rem;
}

/* Empty Cart State */
.empty-cart-state {
    min-height: 400px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.empty-cart-icon {
    font-size: 4rem;
    color: #ddd;
    margin-bottom: 1.5rem;
}

.empty-cart-title {
    font-size: 1.5rem;
    font-weight: 500;
    color: #333;
    margin-bottom: 1rem;
}

.empty-cart-description {
    color: #666;
    max-width: 400px;
    line-height: 1.6;
    margin: 0 auto 2rem;
}

/* Button Styling */
.btn-primary {
    background: #333;
    border-color: #333;
    color: white;
    font-weight: 500;
}

.btn-primary:hover {
    background: #555;
    border-color: #555;
}

.btn-outline-danger:hover {
    background: #dc3545;
    border-color: #dc3545;
}

.btn-outline-secondary {
    border-color: #666;
    color: #666;
}

.btn-outline-secondary:hover {
    background: #666;
    border-color: #666;
    color: white;
}

/* Responsive Design */
@media (max-width: 768px) {
    .cart-item-card {
        padding: 1rem;
    }
    
    .cart-item-card .row {
        flex-direction: column;
        gap: 1rem;
    }
    
    .product-image-wrapper {
        width: 80px;
        height: 80px;
        margin-right: 0.75rem;
    }
    
    .quantity-controls,
    .subtotal {
        text-align: center;
    }
    
    .summary-card {
        position: static;
        margin-top: 2rem;
    }
    
    .page-title {
        font-size: 1.5rem;
    }
}

/* Loading states */
.loading {
    opacity: 0.6;
    pointer-events: none;
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Update quantity function
    window.updateQuantity = async function(rowId, newQuantity) {
        // Allow quantity to be 0 (which will remove the item)
        if (newQuantity < 0) {
            newQuantity = 0;
        }
        
        const cartItem = document.querySelector(`[data-row-id="${rowId}"]`);
        cartItem.classList.add('loading');
        
        try {
            const formData = new FormData();
            formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
            formData.append('quantity', newQuantity);
            
            // Use proper Laravel route with parameter
            const response = await fetch(`/cart/update/${rowId}`, {
                method: 'POST',
                body: formData
            });
            
            if (response.ok) {
                const result = await response.json();
                
                if (result.success) {
                    // Show success message
                    if (newQuantity === 0 || result.removed) {
                        alert('Produk berhasil dihapus dari keranjang!');
                    } else {
                        alert('Jumlah produk berhasil diupdate!');
                    }
                    
                    // Reload to update the view
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
        
        cartItem.classList.remove('loading');
    };

    // Remove item function
    window.removeItem = async function(rowId, productName) {
        if (!confirm(`Apakah Anda yakin ingin menghapus "${productName}" dari keranjang?`)) {
            return;
        }
        
        const cartItem = document.querySelector(`[data-row-id="${rowId}"]`);
        cartItem.classList.add('loading');
        
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
        
        cartItem.classList.remove('loading');
    };

    // Clear cart function
    window.clearCart = async function() {
        if (!confirm('Apakah Anda yakin ingin mengosongkan seluruh keranjang belanja?')) {
            return;
        }
        
        try {
            const response = await fetch('/cart/clear', {
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
                    alert('Keranjang berhasil dikosongkan!');
                    location.reload();
                } else {
                    throw new Error(result.message || 'Failed to clear cart');
                }
            } else {
                throw new Error('Failed to clear cart');
            }
        } catch (error) {
            console.error('Error clearing cart:', error);
            alert('Gagal mengosongkan keranjang. Silakan coba lagi.');
        }
    };
});
</script>
@endpush
@endsection