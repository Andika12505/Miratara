
<div class="offcanvas offcanvas-end" tabindex="-1" id="cartOffcanvas" aria-labelledby="cartOffcanvasLabel">
    <div class="offcanvas-header border-bottom">
        <h5 class="offcanvas-title" id="cartOffcanvasLabel">
            <i class="fas fa-shopping-cart me-2"></i> Keranjang Belanja
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    
    <div class="offcanvas-body" id="offcanvasCartBody">
        
        <div class="text-center py-5">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p class="mt-3 text-muted">Memuat keranjang...</p>
        </div>
    </div>
    
    <div class="offcanvas-footer border-top p-3" style="display: none;" id="cartOffcanvasFooter">
        <div class="d-grid gap-2">
            <a href="<?php echo e(route('cart.index')); ?>" class="btn btn-outline-primary">
                <i class="fas fa-shopping-cart me-2"></i>Lihat Keranjang
            </a>
            <a href="#" class="btn btn-primary">
                <i class="fas fa-credit-card me-2"></i>Checkout
            </a>
        </div>
    </div>
</div>

<?php $__env->startPush('styles'); ?>
<style>
/* Make cart offcanvas wider for better readability */
#cartOffcanvas.offcanvas-end {
    width: 500px !important;
    max-width: 90vw;
}

/* Better spacing for cart content */
#cartOffcanvas .offcanvas-body {
    padding: 1.5rem;
    line-height: 1.6;
}

/* Ensure cart items have proper spacing */
#cartOffcanvas .cart-item {
    margin-bottom: 1.5rem;
    padding-bottom: 1.5rem;
    border-bottom: 1px solid #e9ecef;
}

#cartOffcanvas .cart-item:last-child {
    border-bottom: none;
    margin-bottom: 0;
}

/* Better button spacing in cart */
#cartOffcanvas .btn {
    padding: 0.75rem 1.25rem;
    font-size: 0.9rem;
}

/* Responsive behavior for smaller screens */
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
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const cartOffcanvasEl = document.getElementById('cartOffcanvas');
    const offcanvasCartBody = document.getElementById('offcanvasCartBody');
    const cartOffcanvasFooter = document.getElementById('cartOffcanvasFooter');
    const cartCountBadge = document.querySelector('.cart-count');

    // FETCH CART CONTENT
    const fetchCartContent = async () => {
        showCartLoading();
        
        try {
            const response = await fetch('<?php echo e(route("cart.offcanvas.content")); ?>', {
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
            
            const hasItems = !html.includes('Keranjang Anda kosong');
            cartOffcanvasFooter.style.display = hasItems ? 'block' : 'none';
            
            // IMPORTANT: Attach event listeners after content is loaded
            attachCartEventListeners();

        } catch (error) {
            console.error('Failed to load cart:', error);
            showEmptyCart();
        }
    };

    // ATTACH EVENT LISTENERS to cart content
    const attachCartEventListeners = () => {
        console.log('Attaching cart event listeners...');
        
        // Quantity update buttons
        document.querySelectorAll('.update-cart-qty').forEach(button => {
            button.addEventListener('click', async function(e) {
                e.preventDefault();
                console.log('Quantity button clicked:', this.dataset.action);
                
                const rowId = this.dataset.rowid;
                const action = this.dataset.action;
                const currentQtySpan = this.parentElement.querySelector('span');
                const currentQty = parseInt(currentQtySpan.textContent);
                
                let newQty = action === 'increase' ? currentQty + 1 : currentQty - 1;
                
                // Don't allow negative quantities
                if (newQty < 0) newQty = 0;
                
                console.log(`Updating ${rowId} from ${currentQty} to ${newQty}`);
                await updateCartQuantity(rowId, newQty);
            });
        });

        // Remove item buttons
        document.querySelectorAll('.remove-cart-item').forEach(button => {
            button.addEventListener('click', async function(e) {
                e.preventDefault();
                console.log('Remove button clicked for:', this.dataset.rowid);
                
                const rowId = this.dataset.rowid;
                await removeCartItem(rowId);
            });
        });

        // Clear cart button
        const clearCartBtn = document.querySelector('.clear-cart');
        if (clearCartBtn) {
            clearCartBtn.addEventListener('click', async function(e) {
                e.preventDefault();
                if (confirm('Apakah Anda yakin ingin mengosongkan keranjang?')) {
                    await clearCart();
                }
            });
        }
    };

    // UPDATE CART QUANTITY
    const updateCartQuantity = async (rowId, quantity) => {
        try {
            console.log(`Making request to update ${rowId} to quantity ${quantity}`);
            
            const response = await fetch(`<?php echo e(url('/cart/update')); ?>/${rowId}`, {
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
            console.log('Update response:', data);
            
            if (data.success) {
                updateCartBadge(data.cartCount);
                fetchCartContent(); // Reload cart content
                
                if (data.removed) {
                    showCartToast('Produk berhasil dihapus!', 'success');
                } else {
                    showCartToast('Keranjang berhasil diupdate!', 'success');
                }
            } else {
                throw new Error(data.message || 'Failed to update cart');
            }
        } catch (error) {
            console.error('Error updating cart:', error);
            showCartToast('Gagal mengupdate keranjang', 'error');
        }
    };

    // REMOVE CART ITEM
    const removeCartItem = async (rowId) => {
        try {
            console.log(`Making request to remove ${rowId}`);
            
            const response = await fetch(`<?php echo e(url('/cart/remove')); ?>/${rowId}`, {
                method: 'POST', // Using POST instead of DELETE for better compatibility
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            const data = await response.json();
            console.log('Remove response:', data);
            
            if (data.success) {
                updateCartBadge(data.cartCount);
                fetchCartContent(); // Reload cart content
                showCartToast('Produk berhasil dihapus!', 'success');
            } else {
                throw new Error(data.message || 'Failed to remove item');
            }
        } catch (error) {
            console.error('Error removing item:', error);
            showCartToast('Gagal menghapus produk', 'error');
        }
    };

    // CLEAR CART
    const clearCart = async () => {
        try {
            const response = await fetch('<?php echo e(route("cart.clear")); ?>', {
                method: 'POST', // Using POST instead of DELETE
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            const data = await response.json();
            
            if (data.success) {
                updateCartBadge(0);
                fetchCartContent(); // Reload cart content
                showCartToast('Keranjang berhasil dikosongkan!', 'success');
            } else {
                throw new Error(data.message || 'Failed to clear cart');
            }
        } catch (error) {
            console.error('Error clearing cart:', error);
            showCartToast('Gagal mengosongkan keranjang', 'error');
        }
    };

    // Helper functions
    const showCartLoading = () => {
        offcanvasCartBody.innerHTML = `
            <div class="text-center py-5">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <p class="mt-3 text-muted">Memuat keranjang...</p>
            </div>
        `;
        cartOffcanvasFooter.style.display = 'none';
    };

    const showEmptyCart = () => {
        offcanvasCartBody.innerHTML = `
            <div class="text-center py-5">
                <i class="fas fa-shopping-cart fa-3x text-muted mb-3"></i>
                <h6 class="text-muted">Keranjang Anda kosong</h6>
                <p class="text-muted small">Terjadi kesalahan saat memuat keranjang</p>
                <a href="<?php echo e(route('products.index')); ?>" class="btn btn-primary btn-sm mt-2">
                    <i class="fas fa-shopping-bag me-2"></i>Mulai Belanja
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

    const showCartToast = (message, type = 'success') => {
        const toast = document.createElement('div');
        toast.className = `alert alert-${type === 'success' ? 'success' : 'danger'} position-fixed`;
        toast.style.cssText = `
            top: 100px; 
            right: 20px; 
            z-index: 9999; 
            min-width: 300px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        `;
        toast.innerHTML = `
            <div class="d-flex align-items-center">
                <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'} me-2"></i>
                ${message}
            </div>
        `;
        
        document.body.appendChild(toast);
        setTimeout(() => toast.remove(), 3000);
    };

    // Event listener untuk membuka offcanvas
    if (cartOffcanvasEl) {
        cartOffcanvasEl.addEventListener('show.bs.offcanvas', function () {
            fetchCartContent();
        });
    }

    // HANDLE ADD TO CART FORM SUBMISSION (existing code)
    const handleAddToCart = (form) => {
        const button = form.querySelector('button[type="submit"]');
        const originalButtonText = button.innerHTML;
        
        button.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Menambahkan...';
        button.disabled = true;

        const formData = new FormData(form);

        fetch('<?php echo e(route("cart.add")); ?>', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                updateCartBadge(data.cartCount);
                
                button.innerHTML = '<i class="fas fa-check me-2"></i>Ditambahkan!';
                button.classList.add('btn-success');
                
                setTimeout(() => {
                    button.innerHTML = originalButtonText;
                    button.disabled = false;
                    button.classList.remove('btn-success');
                }, 1500);

                showCartToast('Produk berhasil ditambahkan ke keranjang!', 'success');
            } else {
                throw new Error(data.message || 'Gagal menambahkan produk');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showCartToast('Terjadi kesalahan. Silakan coba lagi.', 'error');
            
            button.innerHTML = originalButtonText;
            button.disabled = false;
        });
    };

    // Event delegation untuk form add to cart
    document.addEventListener('submit', function(e) {
        if (e.target.classList.contains('add-to-cart-form')) {
            e.preventDefault();
            handleAddToCart(e.target);
        }
    });
});
</script>
<?php $__env->stopPush(); ?><?php /**PATH /Users/jerenovvidimy/Documents/MiraTaraTest/resources/views/components/cart-offcanvas.blade.php ENDPATH**/ ?>