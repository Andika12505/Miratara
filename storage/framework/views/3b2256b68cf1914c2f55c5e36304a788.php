
<div class="cart-dropdown-container" id="cartDropdownContainer">
    <div class="cart-dropdown" id="cartDropdown">
        <div class="cart-dropdown-header">
            <h6 class="mb-0">
                <i class="fas fa-shopping-cart me-2"></i>Keranjang Belanja
            </h6>
            <button type="button" class="btn-close-cart" id="closeCartDropdown" aria-label="Close">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <div class="cart-dropdown-body" id="cartDropdownBody">
            
            <div class="text-center py-4">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <p class="mt-3 text-muted mb-0">Memuat keranjang...</p>
            </div>
        </div>
        
        <div class="cart-dropdown-footer" id="cartDropdownFooter" style="display: none;">
            <div class="d-grid gap-2">
                <a href="<?php echo e(route('cart.index')); ?>" class="btn btn-outline-primary btn-sm">
                    <i class="fas fa-shopping-cart me-2"></i>Lihat Keranjang
                </a>
                <a href="#" class="btn btn-primary btn-sm">
                    <i class="fas fa-credit-card me-2"></i>Checkout
                </a>
            </div>
        </div>
    </div>
</div>

<?php $__env->startPush('styles'); ?>
<style>
/* Cart Dropdown Styles */
.cart-dropdown-container {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    z-index: 1055;
    opacity: 0;
    visibility: hidden;
    transition: opacity 0.3s ease, visibility 0.3s ease;
    pointer-events: none;
}

.cart-dropdown-container.show {
    opacity: 1;
    visibility: visible;
    pointer-events: all;
}

.cart-dropdown {
    position: fixed;
    top: 80px; /* This will be overridden by JavaScript */
    right: 20px; /* This will be overridden by JavaScript */
    width: 350px;
    max-width: 90vw;
    max-height: 70vh;
    background-color: white;
    border: 1px solid #dee2e6;
    border-radius: 8px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
    display: flex;
    flex-direction: column;
    transform: translateY(-20px);
    transition: transform 0.3s ease;
    z-index: 1060; /* Higher than container */
}

.cart-dropdown-container.show .cart-dropdown {
    transform: translateY(0);
}

.cart-dropdown-header {
    padding: 1rem;
    border-bottom: 1px solid #dee2e6;
    display: flex;
    justify-content: between;
    align-items: center;
    background-color: #f8f9fa;
    border-top-left-radius: 8px;
    border-top-right-radius: 8px;
}

.cart-dropdown-header h6 {
    color: #495057;
    font-weight: 600;
}

.btn-close-cart {
    border: none;
    background: none;
    color: #6c757d;
    font-size: 1rem;
    cursor: pointer;
    padding: 0.25rem;
    border-radius: 4px;
    transition: all 0.2s ease;
}

.btn-close-cart:hover {
    color: #495057;
    background-color: #e9ecef;
}

.cart-dropdown-body {
    padding: 1rem;
    flex: 1;
    overflow-y: auto;
    max-height: 400px;
}

.cart-dropdown-body::-webkit-scrollbar {
    width: 6px;
}

.cart-dropdown-body::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 3px;
}

.cart-dropdown-body::-webkit-scrollbar-thumb {
    background: #c1c1c1;
    border-radius: 3px;
}

.cart-dropdown-body::-webkit-scrollbar-thumb:hover {
    background: #a8a8a8;
}

.cart-dropdown-footer {
    padding: 1rem;
    border-top: 1px solid #dee2e6;
    background-color: #f8f9fa;
    border-bottom-left-radius: 8px;
    border-bottom-right-radius: 8px;
}

/* Cart item styling within dropdown */
.cart-dropdown .cart-item {
    margin-bottom: 1rem;
    padding-bottom: 1rem;
    border-bottom: 1px solid #f0f0f0;
}

.cart-dropdown .cart-item:last-child {
    border-bottom: none;
    margin-bottom: 0;
}

.cart-dropdown .cart-item img {
    width: 50px;
    height: 50px;
    object-fit: cover;
    border-radius: 4px;
}

.cart-dropdown .cart-item-details {
    flex: 1;
    margin-left: 0.75rem;
}

.cart-dropdown .cart-item-name {
    font-size: 0.9rem;
    font-weight: 500;
    color: #495057;
    margin-bottom: 0.25rem;
}

.cart-dropdown .cart-item-price {
    font-size: 0.85rem;
    color: #007bff;
    font-weight: 600;
}

.cart-dropdown .cart-item-quantity {
    font-size: 0.8rem;
    color: #6c757d;
}

/* Empty cart state */
.cart-dropdown .empty-cart {
    text-align: center;
    padding: 2rem 1rem;
}

.cart-dropdown .empty-cart i {
    font-size: 3rem;
    color: #dee2e6;
    margin-bottom: 1rem;
}

.cart-dropdown .empty-cart p {
    color: #6c757d;
    margin-bottom: 1rem;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .cart-dropdown {
        top: 70px;
        right: 10px;
        width: 320px;
        max-width: 95vw;
    }
}

@media (max-width: 576px) {
    .cart-dropdown {
        top: 65px;
        right: 5px;
        width: 300px;
        max-width: 98vw;
    }
    
    .cart-dropdown-body {
        padding: 0.75rem;
    }
    
    .cart-dropdown-header,
    .cart-dropdown-footer {
        padding: 0.75rem;
    }
}

/* Animation for cart icon when dropdown is open */
.cart-icon-active {
    color: #007bff !important;
}
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const cartDropdownContainer = document.getElementById('cartDropdownContainer');
    const cartDropdown = document.getElementById('cartDropdown');
    const cartDropdownBody = document.getElementById('cartDropdownBody');
    const cartDropdownFooter = document.getElementById('cartDropdownFooter');
    const closeCartDropdown = document.getElementById('closeCartDropdown');
    const cartCountBadge = document.querySelector('.cart-count');
    
    // Find the cart trigger - try multiple selectors to be safe
    let cartTrigger = document.querySelector('#cartDropdownTrigger') || 
                     document.querySelector('[data-bs-toggle="offcanvas"][href="#cartOffcanvas"]') ||
                     document.querySelector('[title="Keranjang Belanja"]') ||
                     document.querySelector('.fa-shopping-cart').closest('a');

    // Find the cart trigger - try multiple selectors to be safe
    let cartTrigger = document.querySelector('#cartDropdownTrigger') || 
                     document.querySelector('[data-bs-toggle="offcanvas"][href="#cartOffcanvas"]') ||
                     document.querySelector('[title="Keranjang Belanja"]') ||
                     document.querySelector('.fa-shopping-cart').closest('a');

    if (cartTrigger) {
        // Clean up any existing offcanvas attributes
        cartTrigger.removeAttribute('data-bs-toggle');
        cartTrigger.removeAttribute('href');
        cartTrigger.href = '#';
        
        cartTrigger.addEventListener('click', function(e) {
            e.preventDefault();
            openCartDropdown();
        });
    }

    // FUNGSI UNTUK MEMBUKA DROPDOWN
    const openCartDropdown = () => {
        // Calculate position based on cart icon
        positionDropdown();
        cartDropdownContainer.classList.add('show');
        if (cartTrigger) {
            cartTrigger.classList.add('cart-icon-active');
        }
        fetchCartContent();
        
        // Focus trap for accessibility
        closeCartDropdown.focus();
    };

    // FUNGSI UNTUK MEMPOSISIKAN DROPDOWN BERDASARKAN IKON CART
    const positionDropdown = () => {
        if (cartTrigger) {
            const rect = cartTrigger.getBoundingClientRect();
            const scrollY = window.scrollY || document.documentElement.scrollTop;
            
            // Position dropdown below the cart icon
            cartDropdown.style.position = 'fixed';
            cartDropdown.style.top = (rect.bottom + 10) + 'px'; // 10px below cart icon
            cartDropdown.style.right = '20px'; // Keep it aligned to the right
            
            // Adjust for mobile
            if (window.innerWidth <= 576) {
                cartDropdown.style.right = '5px';
                cartDropdown.style.width = '300px';
            } else if (window.innerWidth <= 768) {
                cartDropdown.style.right = '10px';
                cartDropdown.style.width = '320px';
            } else {
                cartDropdown.style.right = '20px';
                cartDropdown.style.width = '350px';
            }
        }
    };

    // FUNGSI UNTUK MENUTUP DROPDOWN
    const closeCartDropdownFunc = () => {
        cartDropdownContainer.classList.remove('show');
        if (cartTrigger) {
            cartTrigger.classList.remove('cart-icon-active');
        }
    };

    // FUNGSI UNTUK MENGAMBIL ISI CART & MENAMPILKANNYA
    const fetchCartContent = async () => {
        // Tampilkan loading spinner
        cartDropdownBody.innerHTML = `
            <div class="text-center py-4">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <p class="mt-3 text-muted mb-0">Memuat keranjang...</p>
            </div>
        `;
        
        // Hide footer saat loading
        cartDropdownFooter.style.display = 'none';
        
        try {
            // Fetch cart content
            const response = await fetch('<?php echo e(route("cart.index")); ?>');
            const html = await response.text();
            
            // Parse and extract cart content
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, "text/html");
            const cartContainer = doc.querySelector('.container');
            
            if (cartContainer && cartContainer.innerHTML.trim()) {
                // Modify the content to fit dropdown format
                const cartContent = cartContainer.innerHTML;
                
                // Check if cart has items
                if (cartContent.includes('Keranjang Anda kosong') || cartContent.includes('cart-item') === false) {
                    cartDropdownBody.innerHTML = `
                        <div class="empty-cart">
                            <i class="fas fa-shopping-cart"></i>
                            <p class="mb-3">Keranjang Anda kosong</p>
                            <a href="<?php echo e(route('products.index')); ?>" class="btn btn-primary btn-sm">
                                <i class="fas fa-shopping-bag me-2"></i>Mulai Belanja
                            </a>
                        </div>
                    `;
                    cartDropdownFooter.style.display = 'none';
                } else {
                    cartDropdownBody.innerHTML = cartContent;
                    cartDropdownFooter.style.display = 'block';
                }
            } else {
                throw new Error('Cart content not found');
            }

        } catch (error) {
            console.error('Gagal memuat keranjang:', error);
            cartDropdownBody.innerHTML = `
                <div class="empty-cart">
                    <i class="fas fa-shopping-cart"></i>
                    <p class="mb-3">Keranjang Anda kosong</p>
                    <a href="<?php echo e(route('products.index')); ?>" class="btn btn-primary btn-sm">
                        <i class="fas fa-shopping-bag me-2"></i>Mulai Belanja
                    </a>
                </div>
            `;
            cartDropdownFooter.style.display = 'none';
        }
    };

    // Also listen for window resize to reposition dropdown
    window.addEventListener('resize', () => {
        if (cartDropdownContainer.classList.contains('show')) {
            positionDropdown();
        }
    });

    // EVENT LISTENERS
    
    // Close dropdown when clicking the close button
    closeCartDropdown.addEventListener('click', closeCartDropdownFunc);
    
    // Close dropdown when clicking outside
    cartDropdownContainer.addEventListener('click', function(e) {
        if (e.target === cartDropdownContainer) {
            closeCartDropdownFunc();
        }
    });
    
    // Close dropdown when pressing Escape
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && cartDropdownContainer.classList.contains('show')) {
            closeCartDropdownFunc();
        }
    });
    
    // Prevent dropdown from closing when clicking inside
    cartDropdown.addEventListener('click', function(e) {
        e.stopPropagation();
    });

    // FUNGSI UNTUK MENANGANI SUBMIT "ADD TO CART" (unchanged)
    const handleAddToCart = (form) => {
        const button = form.querySelector('button[type="submit"]');
        const originalButtonText = button.innerHTML;
        
        // Show loading state
        button.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Menambahkan...';
        button.disabled = true;

        const formData = new FormData(form);

        fetch('<?php echo e(route("cart.add")); ?>', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update badge di navbar
                if (cartCountBadge) {
                    cartCountBadge.textContent = data.cartCount > 0 ? data.cartCount : '';
                    cartCountBadge.style.display = data.cartCount > 0 ? 'inline' : 'none';
                }
                
                // Show success state
                button.innerHTML = '<i class="fas fa-check me-2"></i>Ditambahkan!';
                button.classList.add('btn-success');
                
                // Reset after delay
                setTimeout(() => {
                    button.innerHTML = originalButtonText;
                    button.disabled = false;
                    button.classList.remove('btn-success');
                }, 1500);

                // Show success toast
                showCartToast('Produk berhasil ditambahkan ke keranjang!', 'success');

                // Auto-open cart dropdown to show the added item
                setTimeout(() => {
                    openCartDropdown();
                }, 1000);

            } else {
                throw new Error(data.message || 'Gagal menambahkan produk');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showCartToast('Terjadi kesalahan. Silakan coba lagi.', 'error');
            
            // Reset button
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

    // Simple toast notification function
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
        
        // Auto remove after 3 seconds
        setTimeout(() => {
            toast.remove();
        }, 3000);
    };
});
</script>
<?php $__env->stopPush(); ?><?php /**PATH /Users/jerenovvidimy/Documents/MiraTaraTest/resources/views/components/cart-dropdown.blade.php ENDPATH**/ ?>