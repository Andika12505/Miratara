<?php $__env->startSection('title', 'Products - MiraTara Fashion'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid px-4 py-4">
    <div class="row">
        
        <div class="col-12"> 
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="section-title">Our Products</h2>
                <div class="sorting-and-filter-options d-flex align-items-center">
                    
                    <button class="btn btn-outline-secondary me-3" id="openFilterSidebarBtn">
                        <i class="fas fa-filter me-2"></i> Filter
                    </button>

                    <label for="sortSelect" class="form-label mb-0 me-2 d-none d-sm-inline-block">Sort by:</label>
                    <select id="sortSelect" class="form-select" onchange="handleSortChange()">
                        <option value="newest" <?php echo e($sortBy == 'newest' ? 'selected' : ''); ?>>Newest</option>
                        <option value="price_asc" <?php echo e($sortBy == 'price_asc' ? 'selected' : ''); ?>>Price: Lowest to Highest</option>
                        <option value="price_desc" <?php echo e($sortBy == 'price_desc' ? 'selected' : ''); ?>>Price: Highest to Lowest</option>
                        <option value="name_asc" <?php echo e($sortBy == 'name_asc' ? 'selected' : ''); ?>>Name: A-Z</option>
                        <option value="name_desc" <?php echo e($sortBy == 'name_desc' ? 'selected' : ''); ?>>Name: Z-A</option>
                    </select>
                </div>
            </div>

            <div class="row g-4">
                <?php $__empty_1 = true; $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="col-lg-4 col-md-6 col-sm-12">
                    <div class="product-card">
                        <div class="product-image-container position-relative">
                            <?php if(isset($product->metadata['is_discounted']) && $product->metadata['is_discounted']): ?>
                            <div class="discount-badge">
                                <span>DISKON</span>
                            </div>
                            <?php endif; ?>

                            <img src="<?php echo e($product->image ? asset('images/' . $product->image) : asset('images/placeholder.jpg')); ?>" 
                                 alt="<?php echo e($product->name); ?>" 
                                 class="product-image">
                        </div>

                        <div class="product-info">
                            <h3 class="product-title"><?php echo e($product->name); ?></h3>

                            <div class="product-pricing">
                                <?php if(isset($product->metadata['original_price']) && $product->metadata['original_price']): ?>
                                <span class="current-price">Rp <?php echo e(number_format($product->price, 0, ',', '.')); ?></span>
                                <span class="original-price">Rp <?php echo e(number_format($product->metadata['original_price'], 0, ',', '.')); ?></span>
                                <?php else: ?>
                                <span class="current-price">Rp <?php echo e(number_format($product->price, 0, ',', '.')); ?></span>
                                <?php endif; ?>
                            </div>

                            <button class="add-to-bag-btn" 
                                    data-product-id="<?php echo e($product->id); ?>"
                                    <?php echo e($product->stock <= 0 ? 'disabled' : ''); ?>>
                                <?php echo e($product->stock <= 0 ? 'HABIS' : 'TAMBAH KE KERANJANG'); ?>

                            </button>
                        </div>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="col-12">
                    <div class="text-center py-5">
                        <p class="text-muted">Tidak ada produk yang ditemukan dengan filter ini.</p>
                        <a href="<?php echo e(route('products.index')); ?>" class="btn btn-primary mt-3">Reset Semua Filter</a>
                    </div>
                </div>
                <?php endif; ?>
            </div>

            <?php if($products->hasPages()): ?>
            <div class="row mt-5">
                <div class="col-12 d-flex justify-content-center">
                    <?php echo e($products->appends(request()->query())->links()); ?>

                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>


<?php echo $__env->make('partials.product_filter_sidebar', [
    'categories' => $categories,
    'availableVibeAttributes' => $availableVibeAttributes,
    'availableGeneralTags' => $availableGeneralTags,
    'availableOrigins' => $availableOrigins,
    'request' => $request,
    'sortBy' => $sortBy,
    'limit' => $limit
], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<?php $__env->startPush('styles'); ?>
<style>
/* Global Container */
.container-fluid {
    max-width: 1400px;
    margin: 0 auto;
}

/* Section Title & Sorting */
.section-title {
    font-size: 24px;
    font-weight: 300;
    color: #333;
    margin-bottom: 0;
}

.sorting-options .form-select,
.sorting-and-filter-options .form-select {
    width: 200px;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 14px;
    padding: 8px 12px;
}

/* Product Card & Image Styles (Dipertahankan dari sebelumnya) */
.product-card .product-image-container {
    position: relative;
    overflow: hidden;
    background: #f8f9fa;
    border-radius: 0;
}
.product-card .product-image {
    width: 100%;
    height: auto;
    object-fit: contain;
    display: block;
    transition: transform 0.3s ease;
}
.product-card:hover .product-image {
    transform: scale(1.02);
}
.product-card {
    background: #fff;
    border-radius: 0;
    overflow: hidden;
    transition: transform 0.3s ease;
    border: none;
    box-shadow: none;
    height: auto;
    display: flex;
    flex-direction: column;
}
.product-card:hover {
    transform: translateY(-5px);
}
.product-info {
    padding: 20px 0;
    text-align: center;
    flex-shrink: 0;
}
.product-title {
    font-size: 16px;
    font-weight: 400;
    color: #333;
    margin-bottom: 10px;
    line-height: 1.4;
}
.product-pricing {
    margin-bottom: 15px;
}
.current-price {
    font-size: 14px;
    font-weight: 600;
    color: #333;
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
    display: block;
    margin: 0 auto;
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
/* Pagination */
.pagination {
    justify-content: center;
}
.pagination .page-link {
    color: #333;
    border: 1px solid #ddd;
    padding: 8px 12px;
}
.pagination .page-item.active .page-link {
    background-color: #333;
    border-color: #333;
}
.pagination .page-link:hover {
    color: #333;
    background-color: #f8f9fa;
    border-color: #ddd;
}

/* --- Styling untuk Sidebar Pop-Out BARU --- */
.filter-sidebar {
    position: fixed;
    top: 0;
    left: -320px; /* Sembunyikan di luar layar */
    width: 300px; /* Lebar sidebar */
    height: 100%;
    background-color: #fff;
    box-shadow: 2px 0 10px rgba(0, 0, 0, 0.2);
    z-index: 1040; /* Lebih tinggi dari navbar (1030) dan overlay */
    transition: left 0.3s ease-in-out;
    display: flex;
    flex-direction: column;
}

.filter-sidebar.open {
    left: 0; /* Tampilkan saat terbuka */
}

.sidebar-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5); /* Warna overlay */
    z-index: 1035; /* Di antara navbar dan sidebar */
    display: none; /* Sembunyikan secara default */
    opacity: 0;
    transition: opacity 0.3s ease-in-out;
}

.sidebar-overlay.active {
    display: block; /* Tampilkan saat sidebar terbuka */
    opacity: 1;
}

.sidebar-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 15px 20px;
    border-bottom: 1px solid #eee;
    background-color: #f8f9fa;
}
.sidebar-title {
    font-size: 1.1rem;
    font-weight: 600;
    margin-bottom: 0;
    color: #333;
}
.close-sidebar-btn {
    background: transparent;
    border: none;
    font-size: 1.8rem;
    color: #666;
    cursor: pointer;
    line-height: 1; /* Menghilangkan spasi ekstra */
}
.close-sidebar-btn:hover {
    color: #000;
}

.sidebar-body {
    padding: 20px;
    overflow-y: auto; /* Aktifkan scroll jika konten filter banyak */
    flex-grow: 1;
}

/* Styling untuk filter di dalam sidebar (sesuaikan dengan Bootstrap) */
.filter-heading {
    font-size: 1rem;
    font-weight: 600;
    margin-bottom: 10px;
    color: #333;
    border-bottom: 1px solid #eee;
    padding-bottom: 5px;
}
.form-check {
    margin-bottom: 8px;
}
.form-check-input {
    margin-top: 0.25em;
}
.form-check-label {
    font-size: 0.9rem;
    color: #555;
}
.form-control-sm {
    font-size: 0.85rem;
    padding: 0.4rem 0.6rem;
}
.btn-outline-secondary {
    border-color: #ddd;
    color: #555;
}
.btn-outline-secondary:hover {
    background-color: #f8f9fa;
    color: #333;
}
.clear-filter-link {
    font-size: 0.8rem;
    color: #ff8fab;
    text-decoration: none;
    margin-top: 5px;
    display: block;
}
.clear-filter-link:hover {
    text-decoration: underline;
}

/* Styling untuk Vibe Search CTA di dalam sidebar */
.bg-light-pink {
    background-color: #fff0f5; /* Light pink background */
}
.btn-vibe-primary {
    background-color: #ffc0cb; /* MiraTara Pink */
    color: white;
    font-weight: 500;
    letter-spacing: 0.5px;
    transition: all 0.3s ease;
    padding: 10px 15px;
    border-radius: 8px;
    text-decoration: none;
    display: block;
}
.btn-vibe-primary:hover {
    background-color: #ff8fab;
    transform: translateY(-2px);
}

/* Responsive adjustments */
@media (max-width: 991.98px) { /* Adjust for smaller desktops/tablets */
    .sorting-and-filter-options {
        flex-direction: column;
        align-items: flex-start !important;
        gap: 15px;
    }
    .sorting-options .form-select,
    .sorting-and-filter-options .form-select {
        width: 100%; /* Full width for dropdowns */
    }
    #openFilterSidebarBtn {
        width: 100%; /* Full width for filter button */
    }
    .section-title {
        text-align: center;
        width: 100%;
    }
}
@media (max-width: 767.98px) { /* Mobile specific adjustments */
    .filter-sidebar {
        width: 85%; /* Lebih lebar di mobile */
        left: -86%; /* Sembunyikan lebih jauh */
    }
}
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const filterSidebar = document.getElementById('filterSidebar');
    const sidebarOverlay = document.getElementById('sidebarOverlay');
    const openFilterSidebarBtn = document.getElementById('openFilterSidebarBtn');
    const closeSidebarBtn = document.querySelector('.close-sidebar-btn');
    const filterFormSidebar = document.getElementById('filterFormSidebar'); // Form di dalam sidebar

    // Fungsi untuk membuka sidebar
    if (openFilterSidebarBtn) {
        openFilterSidebarBtn.addEventListener('click', function() {
            filterSidebar.classList.add('open');
            sidebarOverlay.classList.add('active');
            document.body.style.overflow = 'hidden'; // Mencegah scroll body saat sidebar terbuka
        });
    }

    // Fungsi untuk menutup sidebar
    function closeFilterSidebar() {
        filterSidebar.classList.remove('open');
        sidebarOverlay.classList.remove('active');
        document.body.style.overflow = ''; // Mengizinkan scroll body kembali
    }

    if (closeSidebarBtn) {
        closeSidebarBtn.addEventListener('click', closeFilterSidebar);
    }

    if (sidebarOverlay) {
        sidebarOverlay.addEventListener('click', closeFilterSidebar); // Tutup saat klik overlay
    }

    // Tangani perubahan pada input form filter di sidebar
    // Gunakan event delegation pada form untuk performa yang lebih baik
    if (filterFormSidebar) {
        filterFormSidebar.addEventListener('change', function(event) {
            // Periksa apakah perubahan terjadi pada radio button kategori
            if (event.target.type === 'radio' && event.target.name === 'category_id') {
                this.submit(); // Langsung submit form jika kategori dipilih
            }
            // Checkbox dan input harga akan disubmit melalui tombol "Terapkan Filter"
        });
    }

    // Handle sort change (fungsi yang sudah ada)
    window.handleSortChange = function() {
        const sortSelect = document.getElementById('sortSelect');
        const currentUrl = new URL(window.location.href);

        currentUrl.searchParams.set('sort_by', sortSelect.value);
        currentUrl.searchParams.delete('page'); // Reset halaman ke 1 saat sorting berubah

        window.location.href = currentUrl.toString();
    }

    // Add to bag functionality (sudah ada di kode Anda)
    // Pastikan fungsi updateCartCount() ada di global scope atau diakses dengan benar
    document.querySelectorAll('.add-to-bag-btn').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            if (this.disabled) return;
            const productId = this.getAttribute('data-product-id');
            const originalText = this.textContent;
            this.textContent = 'ADDING...';
            this.disabled = true;

            fetch('/cart/add', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ product_id: productId, quantity: 1 })
            })
            .then(response => response.json())
            .then(data => {
                if(data.success) {
                    this.textContent = 'ADDED!';
                    this.style.background = '#28a745';
                    this.style.borderColor = '#28a745';
                    this.style.color = 'white';
                    setTimeout(() => {
                        this.textContent = originalText;
                        this.disabled = false;
                        this.style.background = '';
                        this.style.borderColor = '';
                        this.style.color = '';
                    }, 2000);
                    if (typeof updateCartCount === 'function') { // Check if updateCartCount exists
                        updateCartCount();
                    }
                } else {
                    throw new Error(data.message || 'Failed to add product to bag.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred: ' + error.message);
                this.textContent = originalText;
                this.disabled = false;
            });
        });
    });
});

// Dummy function for updateCartCount if it's not defined in layouts/main.blade.php
// If you have a global script for this, you can remove this.
if (typeof updateCartCount === 'undefined') {
    function updateCartCount() {
        console.log('Cart count updated placeholder.');
        // Implement actual AJAX call to get and update cart count
    }
}
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.main', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/jerenovvidimy/Documents/MiraTaraTest/resources/views/products/index.blade.php ENDPATH**/ ?>