{{-- Cart Offcanvas Component --}}
<div class="offcanvas offcanvas-end" tabindex="-1" id="cartOffcanvas" aria-labelledby="cartOffcanvasLabel">
    <div class="offcanvas-header border-bottom">
        <h5 class="offcanvas-title" id="cartOffcanvasLabel">
            <i class="fas fa-shopping-cart me-2"></i> Shopping Cart
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    
    <div class="offcanvas-body" id="offcanvasCartBody">
        {{-- Cart content will be loaded here by JavaScript --}}
        <div class="text-center py-5">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p class="mt-3 text-muted">Loading cart...</p>
        </div>
    </div>
    
    <div class="offcanvas-footer border-top p-3" style="display: none;" id="cartOffcanvasFooter">
        <div class="d-grid gap-2">
            <a href="{{ route('cart.index') }}" class="btn btn-outline-primary">
                <i class="fas fa-shopping-cart me-2"></i>View Cart
            </a>
            <a href="#" class="btn btn-primary">
                <i class="fas fa-credit-card me-2"></i>Checkout
            </a>
        </div>
    </div>
</div>

@push('styles')
<style>
/* Cart offcanvas styling */
#cartOffcanvas.offcanvas-end {
    width: 500px !important;
    max-width: 90vw;
}

#cartOffcanvas .offcanvas-body {
    padding: 1.5rem;
    line-height: 1.6;
}

#cartOffcanvas .cart-item {
    margin-bottom: 1.5rem;
    padding-bottom: 1.5rem;
    border-bottom: 1px solid #e9ecef;
}

#cartOffcanvas .cart-item:last-child {
    border-bottom: none;
    margin-bottom: 0;
}

#cartOffcanvas .btn {
    padding: 0.75rem 1.25rem;
    font-size: 0.9rem;
}

/* Responsive design */
@media (max-width: 768px) {
    #cartOffcanvas.offcanvas-end {
        width: 85vw !important;
    }
}

@media (max-width: 576px) {
    #cartOffcanvas.offcanvas-end {
        width: 95vw !important;
    }
    
    #cartOffcanvas .offcanvas-body {
        padding: 1rem;
    }
}

/* Loading states */
.btn-loading {
    pointer-events: none;
    opacity: 0.6;
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const cartOffcanvasEl = document.getElementById('cartOffcanvas');
    const offcanvasCartBody = document.getElementById('offcanvasCartBody');
    const cartOffcanvasFooter = document.getElementById('cartOffcanvasFooter');
    const cartCountBadge = document.querySelector('.cart-count');

    // Fetch cart content
    const fetchCartContent = async () => {
        console.log('Fetching cart content...');
        showCartLoading();
        
        try {
            const response = await fetch('{{ route("cart.offcanvas.content") }}', {
                method: 'GET',
                credentials: 'same-origin',
                headers: {
                    'Accept': 'text/html',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });
            
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            
            const html = await response.text();
            offcanvasCartBody.innerHTML = html;
            
            const hasItems = !html.includes('Your cart is empty');
            cartOffcanvasFooter.style.display = hasItems ? 'block' : 'none';
            
            attachCartEventListeners();

        } catch (error) {
            console.error('Failed to load cart:', error);
            showEmptyCart();
        }
    };

    // Attach event listeners to cart content (ONLY for cart management, NOT form submissions)
    const attachCartEventListeners = () => {
        console.log('Attaching cart management event listeners...');
        
        // Quantity update buttons
        document.querySelectorAll('.update-cart-qty').forEach(button => {
            button.addEventListener('click', async function(e) {
                e.preventDefault();
                
                const rowId = this.dataset.rowid;
                const action = this.dataset.action;
                const currentQtySpan = this.parentElement.querySelector('span');
                const currentQty = parseInt(currentQtySpan.textContent);
                
                let newQty = action === 'increase' ? currentQty + 1 : currentQty - 1;
                if (newQty < 0) newQty = 0;
                
                await updateCartQuantity(rowId, newQty);
            });
        });

        // Remove item buttons
        document.querySelectorAll('.remove-cart-item').forEach(button => {
            button.addEventListener('click', async function(e) {
                e.preventDefault();
                await removeCartItem(this.dataset.rowid);
            });
        });

        // Clear cart button
        const clearCartBtn = document.querySelector('.clear-cart');
        if (clearCartBtn) {
            clearCartBtn.addEventListener('click', async function(e) {
                e.preventDefault();
                if (confirm('Are you sure you want to clear your cart?')) {
                    await clearCart();
                }
            });
        }
    };

    // Update cart quantity
    const updateCartQuantity = async (rowId, quantity) => {
        try {
            const response = await fetch(`{{ url('/cart/update') }}/${rowId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({ quantity: quantity })
            });

            const data = await response.json();
            
            if (data.success) {
                updateCartBadge(data.cartCount);
                fetchCartContent();
                
                const message = data.removed ? 'Product removed successfully!' : 'Cart updated successfully!';
                showCartToast(message, 'success');
            } else {
                throw new Error(data.message || 'Failed to update cart');
            }
        } catch (error) {
            console.error('Error updating cart:', error);
            showCartToast('Failed to update cart', 'error');
        }
    };

    // Remove cart item
    const removeCartItem = async (rowId) => {
        try {
            const response = await fetch(`{{ url('/cart/remove') }}/${rowId}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            const data = await response.json();
            
            if (data.success) {
                updateCartBadge(data.cartCount);
                fetchCartContent();
                showCartToast('Product removed successfully!', 'success');
            } else {
                throw new Error(data.message || 'Failed to remove item');
            }
        } catch (error) {
            console.error('Error removing item:', error);
            showCartToast('Failed to remove product', 'error');
        }
    };

    // Clear cart
    const clearCart = () => {
        window.clearCartUniversal('offcanvas');
    };

    // Helper functions
    const showCartLoading = () => {
        offcanvasCartBody.innerHTML = `
            <div class="text-center py-5">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <p class="mt-3 text-muted">Loading cart...</p>
            </div>
        `;
        cartOffcanvasFooter.style.display = 'none';
    };

    const showEmptyCart = () => {
        offcanvasCartBody.innerHTML = `
            <div class="text-center py-5">
                <i class="fas fa-shopping-cart fa-3x text-muted mb-3"></i>
                <h6 class="text-muted">Your cart is empty</h6>
                <p class="text-muted small">Error loading cart content</p>
                <a href="{{ route('products.index') }}" class="btn btn-primary btn-sm mt-2">
                    <i class="fas fa-shopping-bag me-2"></i>Start Shopping
                </a>
            </div>
        `;
        cartOffcanvasFooter.style.display = 'none';
    };

    const updateCartBadge = (count) => {
        if (cartCountBadge) {
            cartCountBadge.textContent = count > 0 ? count : '';
            cartCountBadge.style.display = count > 0 ? 'inline' : 'none';
        }
    };

    // Toast notifications
    const showCartToast = (message, type = 'success') => {
        // Remove existing toasts
        const existingToast = document.querySelector('.cart-toast');
        if (existingToast) {
            existingToast.remove();
        }
        
        const toast = document.createElement('div');
        toast.className = `alert alert-${type === 'success' ? 'success' : 'danger'} position-fixed cart-toast`;
        toast.style.cssText = `
            top: 100px; 
            right: 20px; 
            z-index: 9999; 
            min-width: 320px;
            max-width: 420px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
            border-radius: 12px;
            border: none;
            font-weight: 500;
        `;
        
        const iconClass = type === 'success' ? 'check-circle' : 'exclamation-triangle';
        
        toast.innerHTML = `
            <div class="d-flex align-items-center">
                <i class="fas fa-${iconClass} me-3" style="font-size: 1.2rem;"></i>
                <div class="flex-grow-1">
                    <div class="fw-bold mb-1">${type === 'success' ? 'Success!' : 'Error'}</div>
                    <div style="font-size: 0.9rem; opacity: 0.9;">${message}</div>
                </div>
                <button type="button" class="btn-close ms-2" onclick="this.parentElement.parentElement.remove()" style="font-size: 0.8rem;"></button>
            </div>
        `;
        
        document.body.appendChild(toast);
        
        // Animation
        toast.style.transform = 'translateX(100%)';
        toast.style.transition = 'transform 0.3s ease-out';
        
        setTimeout(() => {
            toast.style.transform = 'translateX(0)';
        }, 10);
        
        // Auto-remove after 4 seconds
        setTimeout(() => {
            if (toast.parentNode) {
                toast.style.transform = 'translateX(100%)';
                setTimeout(() => {
                    if (toast.parentNode) {
                        toast.remove();
                    }
                }, 300);
            }
        }, 4000);
    };

    // Event listener for opening offcanvas
    if (cartOffcanvasEl) {
        cartOffcanvasEl.addEventListener('show.bs.offcanvas', function () {
            fetchCartContent();
        });
    }

    // GLOBAL FUNCTION: Refresh cart when product is added from other components
    window.refreshCartOffcanvas = function() {
        // Only refresh if offcanvas is currently open
        const cartOffcanvas = document.getElementById('cartOffcanvas');
        if (cartOffcanvas && cartOffcanvas.classList.contains('show')) {
            fetchCartContent();
        }
    };

    // GLOBAL FUNCTION: Update cart badge from other components
    window.updateCartBadgeGlobal = function(count) {
        updateCartBadge(count);
    };
});
</script>
@endpush