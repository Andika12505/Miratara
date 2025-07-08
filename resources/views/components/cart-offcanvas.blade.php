{{-- Cart Offcanvas Component --}}
<div class="offcanvas offcanvas-end" tabindex="-1" id="cartOffcanvas" aria-labelledby="cartOffcanvasLabel">
    <div class="offcanvas-header border-bottom">
        <h5 class="offcanvas-title" id="cartOffcanvasLabel">
            <i class="fas fa-shopping-cart me-2"></i> Keranjang Belanja
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    
    <div class="offcanvas-body" id="offcanvasCartBody">
        {{-- Konten keranjang akan dimuat di sini oleh JavaScript --}}
        <div class="text-center py-5">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p class="mt-3 text-muted">Memuat keranjang...</p>
        </div>
    </div>
    
    <div class="offcanvas-footer border-top p-3" style="display: none;" id="cartOffcanvasFooter">
        <div class="d-grid gap-2">
            <a href="{{ route('cart.index') }}" class="btn btn-outline-primary">
                <i class="fas fa-shopping-cart me-2"></i>Lihat Keranjang
            </a>
            <a href="#" class="btn btn-primary">
                <i class="fas fa-credit-card me-2"></i>Checkout
            </a>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const cartOffcanvasEl = document.getElementById('cartOffcanvas');
    const offcanvasCartBody = document.getElementById('offcanvasCartBody');
    const cartOffcanvasFooter = document.getElementById('cartOffcanvasFooter');
    const cartCountBadge = document.querySelector('.cart-count');

    // FUNGSI UNTUK MENGAMBIL ISI CART & MENAMPILKANNYA
    const fetchCartContent = async () => {
        // Tampilkan loading spinner
        offcanvasCartBody.innerHTML = `
            <div class="text-center py-5">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <p class="mt-3 text-muted">Memuat keranjang...</p>
            </div>
        `;
        
        // Hide footer saat loading
        cartOffcanvasFooter.style.display = 'none';
        
        try {
            // Fetch cart content - you might want to create a specific API endpoint for this
            const response = await fetch('{{ route("cart.index") }}');
            const html = await response.text();
            
            // Parse and extract cart content
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, "text/html");
            const cartContainer = doc.querySelector('.container');
            
            if (cartContainer) {
                offcanvasCartBody.innerHTML = cartContainer.innerHTML;
                // Show footer if cart has items
                cartOffcanvasFooter.style.display = 'block';
            } else {
                throw new Error('Cart content not found');
            }

        } catch (error) {
            console.error('Gagal memuat keranjang:', error);
            offcanvasCartBody.innerHTML = `
                <div class="text-center py-5">
                    <i class="fas fa-shopping-cart fa-3x text-muted mb-3"></i>
                    <p class="text-muted">Keranjang Anda kosong</p>
                    <a href="{{ route('products.index') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-shopping-bag me-2"></i>Mulai Belanja
                    </a>
                </div>
            `;
            cartOffcanvasFooter.style.display = 'none';
        }
    };

    // Event listener untuk membuka offcanvas
    if (cartOffcanvasEl) {
        cartOffcanvasEl.addEventListener('show.bs.offcanvas', function () {
            fetchCartContent();
        });
    }

    // FUNGSI UNTUK MENANGANI SUBMIT "ADD TO CART"
    const handleAddToCart = (form) => {
        const button = form.querySelector('button[type="submit"]');
        const originalButtonText = button.innerHTML;
        
        // Show loading state
        button.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Menambahkan...';
        button.disabled = true;

        const formData = new FormData(form);

        fetch('{{ route("cart.add") }}', {
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

                // Show success toast (optional)
                showCartToast('Produk berhasil ditambahkan ke keranjang!', 'success');

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
@endpush