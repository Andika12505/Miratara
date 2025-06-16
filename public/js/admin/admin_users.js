// JavaScript untuk Admin Users Page
// File: public/js/admin/admin_users.js

// --- Variabel Global ---
let currentPage = 1;
let currentSearch = "";
const perPage = 10;
let searchInput, tableBody, totalBadge, paginationInfo, paginationNav;

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

/** Memuat data user dari API. */
window.loadUsers = async function () {
    try {
        showLoading();

        const params = new URLSearchParams({
            page: currentPage,
            limit: perPage,
        });
        if (currentSearch) {
            params.append("search", currentSearch);
        }

        const response = await fetch(`/admin/users-data?${params}`);
        const data = await response.json();

        if (response.ok) {
            displayUsers(data.data);
            updatePagination(data.pagination);
            updateTotalBadge(data.pagination.total_users);
        } else {
            showAlert(data.message || "Gagal memuat data user.", "danger");
        }
    } catch (error) {
        console.error("Error loading users:", error);
        showAlert("Terjadi kesalahan saat memuat user. Silakan coba lagi.", "danger");
    }
};

/** Mengubah halaman paginasi */
window.changePage = function (page) {
    if (page >= 1) {
        currentPage = page;
        window.loadUsers();
    }
};

/** Menghapus kriteria pencarian */
window.clearSearch = function () {
    if (searchInput) searchInput.value = "";
    currentSearch = "";
    currentPage = 1;
    window.loadUsers();
};

/** Mengarahkan ke halaman edit user */
window.editUser = function (userId) {
    alert(`Edit user ID: ${userId} - Fitur belum diimplementasi`);
};

/** Memicu modal konfirmasi penghapusan user */
window.deleteUser = function (userId, userName) {
    document.getElementById("userToDelete").textContent = userName;
    const modal = new bootstrap.Modal(document.getElementById("deleteModal"));
    modal.show();
    
    document.getElementById("confirmDelete").onclick = function () {
        window.performDelete(userId, userName);
    };
};

/** Melakukan permintaan penghapusan user ke API */
window.performDelete = async function (userId, userName) {
    try {
        const response = await fetch("/api/admin/users/delete", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]') ? document.querySelector('meta[name="csrf-token"]').content : ''
            },
            body: JSON.stringify({ user_id: userId }),
        });

        const data = await response.json();

        if (response.ok) {
            const modal = bootstrap.Modal.getInstance(document.getElementById("deleteModal"));
            if (modal) modal.hide();
            showAlert(`User "${userName}" berhasil dihapus`, "success");
            window.loadUsers();
        } else {
            showAlert(data.message || "Gagal menghapus user.", "danger");
        }
    } catch (error) {
        console.error("Error deleting user:", error);
        showAlert("Terjadi kesalahan saat menghapus user. Silakan coba lagi.", "danger");
    }
};

function displayUsers(users) {
    if (!tableBody) return;

    if (users.length === 0) {
        showEmptyState();
        return;
    }

    let html = "";
    users.forEach((user, index) => {
        const rowNumber = (currentPage - 1) * perPage + index + 1;
        html += `
            <tr>
                <td>${rowNumber}</td>
                <td>
                    <div class="d-flex align-items-center">
                        <div class="user-avatar me-2">
                            <i class="fas fa-user-circle fa-2x text-muted"></i>
                           </div>
                        <div>
                            <div class="fw-medium">${user.full_name}</div>
                        </div>
                    </div>
                </td>
                <td>
                    <code class="text-primary">${user.username}</code>
                </td>
                <td>${user.email}</td>
                <td>${user.phone}</td>
                <td>
                    <small class="text-muted">${user.created_at}</small>
                </td>
                <td>
                    <div class="btn-group btn-group-sm" role="group">
                        <button type="button" class="btn btn-outline-warning" 
                                onclick="editUser(${user.id})" 
                                title="Edit User">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button type="button" class="btn btn-outline-danger" 
                                onclick="deleteUser(${user.id}, '${user.full_name}')" 
                                title="Hapus User">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </td>
            </tr>
        `;
    });
    tableBody.innerHTML = html;
}

function showEmptyState() {
    if (!tableBody) return;
    const message = currentSearch
        ? `Tidak ada user yang ditemukan untuk pencarian "${currentSearch}"`
        : "Belum ada user terdaftar";

    const actionButton = currentSearch
        ? `<button type="button" class="btn btn-secondary" onclick="clearSearch()">
            <i class="fas fa-times me-2"></i>
            Hapus Pencarian
        </button>`
        : `<a href="/admin/users/create" class="btn btn-primary">
            <i class="fas fa-user-plus me-2"></i>
            Tambah User Pertama
        </a>`;

    tableBody.innerHTML = `
        <tr>
            <td colspan="7" class="text-center py-5">
                <div class="empty-state">
                    <i class="fas fa-users fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">${message}</h5>
                    <p class="text-muted">Data user akan muncul disini setelah ada yang mendaftar</p>
                    ${actionButton}
                </div>
            </td>
        </tr>
    `;
}

function showLoading() {
    if (!tableBody) return;
    tableBody.innerHTML = `
        <tr>
            <td colspan="7" class="text-center py-5">
                <div class="loading-state">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-3 text-muted">Memuat data user...</p>
                </div>
            </td>
        </tr>
    `;
}

function updatePagination(pagination) {
    if (!paginationInfo || !paginationNav) return;

    paginationInfo.textContent = `Menampilkan ${pagination.showing_from || 0} sampai ${pagination.showing_to || 0} dari ${pagination.total_users || 0} data`;

    let paginationHtml = "";
    const prevDisabled = pagination.current_page <= 1 ? "disabled" : "";
    paginationHtml += `<li class="page-item ${prevDisabled}"><a class="page-link" href="#" onclick="changePage(${pagination.current_page - 1})">Previous</a></li>`;

    const startPage = Math.max(1, pagination.current_page - 2);
    const endPage = Math.min(pagination.total_pages, pagination.current_page + 2);

    if (startPage > 1) {
        paginationHtml += `<li class="page-item"><a class="page-link" href="#" onclick="changePage(1)">1</a></li>`;
        if (startPage > 2) { paginationHtml += `<li class="page-item disabled"><span class="page-link">...</span></li>`; }
    }

    for (let i = startPage; i <= endPage; i++) {
        const active = i === pagination.current_page ? "active" : "";
        paginationHtml += `<li class="page-item ${active}"><a class="page-link" href="#" onclick="changePage(${i})">${i}</a></li>`;
    }

    if (endPage < pagination.total_pages) {
        if (endPage < pagination.total_pages - 1) { paginationHtml += `<li class="page-item disabled"><span class="page-link">...</span></li>`; }
        paginationHtml += `<li class="page-item"><a class="page-link" href="#" onclick="changePage(${pagination.total_pages})">${pagination.total_pages}</a></li>`;
    }

    const nextDisabled = pagination.current_page >= pagination.total_pages ? "disabled" : "";
    paginationHtml += `<li class="page-item ${nextDisabled}"><a class="page-link" href="#" onclick="changePage(${pagination.current_page + 1})">Next</a></li>`;
        
    paginationNav.innerHTML = paginationHtml;
}

function updateTotalBadge(total) {
    if (totalBadge) totalBadge.textContent = `Total: ${total} user`;
}

document.addEventListener("DOMContentLoaded", function () {
    searchInput = document.querySelector('input[placeholder*="Cari berdasarkan"]');
    tableBody = document.getElementById("tableBody"); // Menggunakan ID yang benar
    totalBadge = document.getElementById("totalBadge"); // Menggunakan ID yang benar
    paginationInfo = document.getElementById("paginationInfo");
    paginationNav = document.getElementById("paginationNav");

    window.loadUsers();

    let searchTimeout;
    if (searchInput) {
        searchInput.addEventListener("input", function () {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                currentSearch = this.value.trim();
                currentPage = 1;
                window.loadUsers();
            }, 500);
        });
    }

});