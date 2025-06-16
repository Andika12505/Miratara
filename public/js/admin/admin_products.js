// JavaScript untuk Admin Products Page
// File: public/js/admin/admin_products.js

// --- Variabel Global ---
let currentPage = 1;
let currentSearch = "";
const perPage = 10;
let productSearchInput, productsTableBody, totalProductsBadge, paginationInfo, paginationNav; // Akan diinisialisasi di DOMContentLoaded

// --- Fungsi Global (dapat dipanggil dari HTML onclick atau antar script) ---

/** Menampilkan pesan alert */
function showAlert(message, type) {
    const alertContainer = document.getElementById("alertContainer");
    if (alertContainer) {
        const iconClass = type === "success" ? "check-circle" : (type === "warning" ? "exclamation-triangle" : "exclamation-circle");
        alertContainer.innerHTML = `
            <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                <i class="fas fa-${iconClass} me-2"></i>
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        `;
        setTimeout(() => {
            const alertElement = alertContainer.querySelector(".alert");
            if (alertElement) {
                alertElement.remove();
            }
        }, 5000);
    }
}

/** Memuat data produk dari API */
window.loadProducts = async function () {
    try {
        showLoading(); // Menampilkan indikator loading

        const params = new URLSearchParams({
            page: currentPage,
            limit: perPage,
        });
        if (currentSearch) {
            params.append("search", currentSearch);
        }

        // Panggil API Laravel
        const response = await fetch(`/api/admin/products?${params}`);
        const data = await response.json();

        if (response.ok) { // Check if HTTP status is 2xx
            displayProducts(data.data);
            updatePagination(data.pagination);
            updateTotalProductsBadge(data.pagination.total);
        } else {
            showAlert(data.message || "Gagal memuat data produk.", "danger");
        }
    } catch (error) {
        console.error("Error loading products:", error);
        showAlert("Terjadi kesalahan saat memuat produk. Silakan coba lagi.", "danger");
    }
};

/** Mengubah halaman paginasi */
window.changePage = function (page) {
    if (page >= 1) {
        currentPage = page;
        window.loadProducts();
    }
};

/** Menghapus kriteria pencarian */
window.clearSearch = function () {
    if (productSearchInput) productSearchInput.value = "";
    currentSearch = "";
    currentPage = 1;
    window.loadProducts();
};

/** Mengarahkan ke halaman edit produk */
window.editProduct = function (productId) {
    window.location.href = `/admin/products/${productId}/edit`; // Redirect ke halaman edit
};

/** Memicu modal konfirmasi penghapusan produk */
window.deleteProduct = function (productId, productName) {
    document.getElementById("productToDeleteName").textContent = productName;
    const modal = new bootstrap.Modal(document.getElementById("deleteProductModal"));
    modal.show();
    
    document.getElementById("confirmDeleteProduct").onclick = function () {
        window.performDeleteProduct(productId, productName);
    };
};

/** Melakukan permintaan penghapusan produk ke API */
window.performDeleteProduct = async function (productId, productName) {
    try {
        const response = await fetch(`/api/admin/products/${productId}`, {
            method: "DELETE",
            headers: {
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]') ? document.querySelector('meta[name="csrf-token"]').content : ''
            },
        });

        const data = await response.json();

        if (response.ok) {
            const modal = bootstrap.Modal.getInstance(document.getElementById("deleteProductModal"));
            if (modal) modal.hide();
            showAlert(data.message, "success");
            window.loadProducts();
        } else {
            showAlert(data.message || "Gagal menghapus produk.", "danger");
        }
    } catch (error) {
        console.error("Error deleting product:", error);
        showAlert("Terjadi kesalahan saat menghapus produk. Silakan coba lagi.", "danger");
    }
};


document.addEventListener("DOMContentLoaded", function () {
    // Inisialisasi elemen DOM
    productSearchInput = document.getElementById("productSearchInput");
    productsTableBody = document.getElementById("productsTableBody");
    totalProductsBadge = document.getElementById("totalProductsBadge");
    paginationInfo = document.getElementById("paginationInfo");
    paginationNav = document.getElementById("paginationNav");

    window.loadProducts();

    let searchTimeout;
    if (productSearchInput) {
        productSearchInput.addEventListener("input", function () {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                currentSearch = this.value.trim();
                currentPage = 1;
                window.loadProducts();
            }, 500);
        });
    }

    // --- Fungsi Pembantu Internal ---

    function displayProducts(products) {
        if (!productsTableBody) return;

        if (products.length === 0) {
            showEmptyState();
            return;
        }

        let html = "";
        products.forEach((product, index) => {
            const rowNumber = (currentPage - 1) * perPage + index + 1;
            const imageUrl1 = product.image_url_1 ? `/storage/products/${product.image_url_1}` : 'https://via.placeholder.com/50?text=No+Image'; // Placeholder jika tidak ada gambar
            const price = parseFloat(product.price).toLocaleString('id-ID', { style: 'currency', currency: 'IDR' });
            const discountPrice = product.discount_price ? parseFloat(product.discount_price).toLocaleString('id-ID', { style: 'currency', currency: 'IDR' }) : '-';
            const displayPrice = product.discount_price ? `<s>${price}</s> ${discountPrice}` : price;
            const statusClass = product.is_active ? 'badge bg-success' : 'badge bg-secondary';
            const statusText = product.is_active ? 'Aktif' : 'Tidak Aktif';

            html += `
                <tr>
                    <td>${rowNumber}</td>
                    <td><img src="${imageUrl1}" alt="${product.name}" style="width: 50px; height: 50px; object-fit: cover; border-radius: 5px;"></td>
                    <td>${product.name}</td>
                    <td>${product.category || '-'}</td>
                    <td>${displayPrice}</td>
                    <td>${product.discount_price ? discountPrice : '-'}</td>
                    <td>${product.stock}</td>
                    <td><span class="${statusClass}">${statusText}</span></td>
                    <td>
                        <div class="btn-group btn-group-sm" role="group">
                            <button type="button" class="btn btn-outline-warning" 
                                    onclick="editProduct(${product.id})" 
                                    title="Edit Produk">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button type="button" class="btn btn-outline-danger" 
                                    onclick="deleteProduct(${product.id}, '${product.name}')" 
                                    title="Hapus Produk">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            `;
        });
        productsTableBody.innerHTML = html;
    }

    function showEmptyState() {
        if (!productsTableBody) return;
        const message = currentSearch
            ? `Tidak ada produk ditemukan untuk pencarian "${currentSearch}"`
            : "Belum ada produk terdaftar";
        const actionButton = currentSearch
            ? `<button type="button" class="btn btn-secondary" onclick="clearSearch()"><i class="fas fa-times me-2"></i> Hapus Pencarian</button>`
            : `<a href="/admin/products/create" class="btn btn-primary"><i class="fas fa-plus me-2"></i> Tambah Produk Pertama</a>`;

        productsTableBody.innerHTML = `
            <tr>
                <td colspan="9" class="text-center py-5">
                    <div class="empty-state">
                        <i class="fas fa-box fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">${message}</h5>
                        <p class="text-muted">Produk yang Anda tambahkan akan muncul di sini</p>
                        ${actionButton}
                    </div>
                </td>
            </tr>
        `;
    }

    function showLoading() {
        if (!productsTableBody) return;
        productsTableBody.innerHTML = `
            <tr>
                <td colspan="9" class="text-center py-5">
                    <div class="loading-state">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="mt-3 text-muted">Memuat data produk...</p>
                    </div>
                </td>
            </tr>
        `;
    }

    function updatePagination(pagination) {
        if (!paginationInfo || !paginationNav) return;

        paginationInfo.textContent = `Menampilkan ${pagination.from || 0} sampai ${pagination.to || 0} dari ${pagination.total || 0} data`;

        let paginationHtml = "";
        const prevDisabled = pagination.current_page <= 1 ? "disabled" : "";
        paginationHtml += `<li class="page-item ${prevDisabled}"><a class="page-link" href="#" onclick="changePage(${pagination.current_page - 1})">Previous</a></li>`;

        const startPage = Math.max(1, pagination.current_page - 2);
        const endPage = Math.min(pagination.last_page, pagination.current_page + 2);

        if (startPage > 1) {
            paginationHtml += `<li class="page-item"><a class="page-link" href="#" onclick="changePage(1)">1</a></li>`;
            if (startPage > 2) { paginationHtml += `<li class="page-item disabled"><span class="page-link">...</span></li>`; }
        }

        for (let i = startPage; i <= endPage; i++) {
            const active = i === pagination.current_page ? "active" : "";
            paginationHtml += `<li class="page-item ${active}"><a class="page-link" href="#" onclick="changePage(${i})">${i}</a></li>`;
        }

        if (endPage < pagination.last_page) {
            if (endPage < pagination.last_page - 1) { paginationHtml += `<li class="page-item disabled"><span class="page-link">...</span></li>`; }
            paginationHtml += `<li class="page-item"><a class="page-link" href="#" onclick="changePage(${pagination.last_page})">${pagination.last_page}</a></li>`;
        }

        const nextDisabled = pagination.current_page >= pagination.last_page ? "disabled" : "";
        paginationHtml += `<li class="page-item ${nextDisabled}"><a class="page-link" href="#" onclick="changePage(${pagination.current_page + 1})">Next</a></li>`;
        
        paginationNav.innerHTML = paginationHtml;
    }

    function updateTotalProductsBadge(total) {
        if (totalProductsBadge) totalProductsBadge.textContent = `Total: ${total} produk`;
    }
});