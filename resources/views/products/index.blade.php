@extends('layouts.main')

@section('title', 'Products - MiraTara Fashion')

@section('content')
<div class="container-fluid px-4 py-4">
    <div class="row">
        {{-- Bagian Konten Produk Utama --}}
        <div class="col-12"> 
            <div class="d-flex justify-content-between align-items-center mb-4 mt-5">
                <h2 class="section-title">Our Products</h2>
                <div class="sorting-and-filter-options d-flex align-items-center">
                    {{-- Tombol untuk memicu Sidebar Filter --}}
                    <button class="btn btn-outline-secondary me-3" id="openFilterSidebarBtn">
                        <i class="fas fa-filter me-2"></i> Filter
                    </button>

                    <label for="sortSelect" class="form-label mb-0 me-2 d-none d-sm-inline-block">Sort by:</label>
                    <select id="sortSelect" class="form-select" onchange="handleSortChange()">
                        <option value="newest" {{ $sortBy == 'newest' ? 'selected' : '' }}>Newest</option>
                        <option value="price_asc" {{ $sortBy == 'price_asc' ? 'selected' : '' }}>Price: Lowest to Highest</option>
                        <option value="price_desc" {{ $sortBy == 'price_desc' ? 'selected' : '' }}>Price: Highest to Lowest</option>
                        <option value="name_asc" {{ $sortBy == 'name_asc' ? 'selected' : '' }}>Name: A-Z</option>
                        <option value="name_desc" {{ $sortBy == 'name_desc' ? 'selected' : '' }}>Name: Z-A</option>
                    </select>
                </div>
            </div>

            <x-product-grid 
                :products="$products"
                :show-discount="true"
                :use-form-cart="true"
                empty-message="No filtered products available at the moment."
                empty-button-text="Reset Filters"
                empty-button-class="btn btn-primary"
                button-text="ADD TO BAG"
                out-of-stock-text="OUT OF STOCK"
            />

            @if($products->hasPages())
            <div class="row mt-5">
                <div class="col-12 d-flex justify-content-center">
                    {{ $products->appends(request()->query())->links() }}
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

{{-- PARTIAL FILTER SIDEBAR DI SINI --}}
@include('partials.product_filter_sidebar', [
    'categories' => $categories,
    'availableVibeAttributes' => $availableVibeAttributes,
    'availableGeneralTags' => $availableGeneralTags,
    'availableOrigins' => $availableOrigins,
    'request' => $request,
    'sortBy' => $sortBy,
    'limit' => $limit
])

@push('styles')
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

/* === SHARED PRODUCT CARD STYLES === */
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
    padding: 20px 0;
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
}

.product-pricing {
    margin-bottom: 15px;
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

/* Grid layout for both pages */
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

/* --- Styling untuk Sidebar Pop-Out --- */
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
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const filterSidebar = document.getElementById('filterSidebar');
    const sidebarOverlay = document.getElementById('sidebarOverlay');
    const openFilterSidebarBtn = document.getElementById('openFilterSidebarBtn');
    const closeSidebarBtn = document.querySelector('.close-sidebar-btn');
    const filterFormSidebar = document.getElementById('filterFormSidebar');

    // Filter sidebar functionality (keep as is)
    if (openFilterSidebarBtn) {
        openFilterSidebarBtn.addEventListener('click', function() {
            filterSidebar.classList.add('open');
            sidebarOverlay.classList.add('active');
            document.body.style.overflow = 'hidden';
        });
    }

    function closeFilterSidebar() {
        filterSidebar.classList.remove('open');
        sidebarOverlay.classList.remove('active');
        document.body.style.overflow = '';
    }

    if (closeSidebarBtn) {
        closeSidebarBtn.addEventListener('click', closeFilterSidebar);
    }

    if (sidebarOverlay) {
        sidebarOverlay.addEventListener('click', closeFilterSidebar);
    }

    if (filterFormSidebar) {
        filterFormSidebar.addEventListener('change', function(event) {
            if (event.target.type === 'radio' && event.target.name === 'category_id') {
                this.submit();
            }
        });
    }

    // Sort functionality (keep as is)
    window.handleSortChange = function() {
        const sortSelect = document.getElementById('sortSelect');
        const currentUrl = new URL(window.location.href);

        currentUrl.searchParams.set('sort_by', sortSelect.value);
        currentUrl.searchParams.delete('page');

        window.location.href = currentUrl.toString();
    }

});
</script>
@endpush
@endsection