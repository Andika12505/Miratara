// JavaScript untuk Admin User Management Page
// File: public/js/admin/admin_users.js

document.addEventListener("DOMContentLoaded", function () {
    const usersTableBody = document.getElementById("usersTableBody");
    const paginationLinks = document.querySelector("#paginationLinks .pagination");
    const paginationInfo = document.getElementById("paginationInfo");
    const searchInput = document.getElementById("searchInput");
    const searchButton = document.getElementById("searchButton");
    let currentPage = 1;
    let currentSearch = '';

    function showAlert(message, type = "danger") {
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
            document.querySelector(".main-content").scrollIntoView({ behavior: "smooth", block: "start" });
        }
    }

    function clearAlert() {
        const alertContainer = document.getElementById("alertContainer");
        if (alertContainer) {
            alertContainer.innerHTML = "";
        }
    }

    async function fetchUsers(page = 1, search = '') {
        clearAlert();
        usersTableBody.innerHTML = `<tr><td colspan="8" class="text-center">Memuat data...</td></tr>`;
        paginationLinks.innerHTML = '';
        paginationInfo.textContent = '';

        try {
            // Menggunakan URL dari Blade: window.usersApiUrl
            const response = await fetch(`${window.usersApiUrl}?page=${page}&search=${search}`);
            const result = await response.json();

            if (result.success) {
                renderUsers(result.data);
                renderPagination(result.pagination);
            } else {
                showAlert(result.message || 'Gagal memuat data user.', 'danger');
                usersTableBody.innerHTML = `<tr><td colspan="8" class="text-center">Gagal memuat data.</td></tr>`;
            }
        } catch (error) {
            console.error("Error fetching users:", error);
            showAlert("Terjadi kesalahan saat memuat data user.", 'danger');
            usersTableBody.innerHTML = `<tr><td colspan="8" class="text-center">Terjadi kesalahan.</td></tr>`;
        }
    }

    function renderUsers(users) {
        usersTableBody.innerHTML = ''; // Kosongkan tabel sebelum mengisi
        if (users.length === 0) {
            usersTableBody.innerHTML = `<tr><td colspan="8" class="text-center">Tidak ada user ditemukan.</td></tr>`;
            return;
        }

        users.forEach(user => {
            const row = document.createElement('tr');
            // Logika untuk menampilkan status Admin/User dengan badge yang berbeda
            const statusBadge = user.is_admin === 1 
                ? '<span class="badge bg-primary">Admin</span>' 
                : '<span class="badge bg-secondary">User</span>';

            row.innerHTML = `
                <td>${user.id}</td>
                <td>${user.full_name}</td>
                <td>${user.username}</td>
                <td>${user.email}</td>
                <td>${user.phone}</td>
                <td>${statusBadge}</td>
                <td>${user.created_at}</td>
                <td>
                    <a href="${window.editUserRoute.replace(':id', user.id)}" class="btn btn-sm btn-info me-1" title="Edit User">
                        <i class="fas fa-edit"></i>
                    </a>
                    <button type="button" class="btn btn-sm btn-danger delete-btn" data-id="${user.id}" data-name="${user.full_name}" title="Hapus User">
                        <i class="fas fa-trash-alt"></i>
                    </button>
                </td>
            `;
            usersTableBody.appendChild(row);
        });
    }

    function renderPagination(pagination) {
        paginationLinks.innerHTML = '';
        paginationInfo.textContent = `Menampilkan ${pagination.showing_from} sampai ${pagination.showing_to} dari ${pagination.total_users} user.`;

        // Previous button
        const prevClass = pagination.current_page === 1 ? 'disabled' : '';
        paginationLinks.innerHTML += `<li class="page-item ${prevClass}"><a class="page-link" href="#" data-page="${pagination.current_page - 1}">Previous</a></li>`;

        // Page numbers
        for (let i = 1; i <= pagination.total_pages; i++) {
            const activeClass = i === pagination.current_page ? 'active' : '';
            paginationLinks.innerHTML += `<li class="page-item ${activeClass}"><a class="page-link" href="#" data-page="${i}">${i}</a></li>`;
        }

        // Next button
        const nextClass = pagination.current_page === pagination.total_pages ? 'disabled' : '';
        paginationLinks.innerHTML += `<li class="page-item ${nextClass}"><a class="page-link" href="#" data-page="${pagination.current_page + 1}">Next</a></li>`;

        // Event listeners for pagination links
        paginationLinks.querySelectorAll('.page-link').forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const page = parseInt(this.dataset.page);
                if (!isNaN(page) && page > 0 && page <= pagination.total_pages) {
                    currentPage = page;
                    fetchUsers(currentPage, currentSearch);
                }
            });
        });
    }

    // Search functionality
    searchButton.addEventListener('click', function() {
        currentSearch = searchInput.value;
        currentPage = 1; // Reset to first page on new search
        fetchUsers(currentPage, currentSearch);
    });

    searchInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            searchButton.click();
        }
    });

    // Delete functionality (Event delegation)
    usersTableBody.addEventListener('click', async function(e) {
        if (e.target.closest('.delete-btn')) {
            const deleteButton = e.target.closest('.delete-btn');
            const userId = deleteButton.dataset.id;
            const userName = deleteButton.dataset.name;

            if (confirm(`Apakah Anda yakin ingin menghapus user '${userName}' (ID: ${userId})?`)) {
                try {
                    // Menggunakan URL dari Blade: window.deleteUserRoute
                    const response = await fetch(window.deleteUserRoute.replace(':id', userId), {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Content-Type': 'application/json' // Tambahkan ini jika backend butuh
                        },
                        // Tidak perlu body jika ID ada di URL untuk DELETE
                    });

                    const result = await response.json();

                    if (result.success) {
                        showAlert(result.message, 'success');
                        fetchUsers(currentPage, currentSearch); // Refresh table
                    } else {
                        showAlert(result.message || 'Gagal menghapus user.', 'danger');
                    }
                } catch (error) {
                    console.error("Error deleting user:", error);
                    showAlert("Terjadi kesalahan saat menghapus user. Silakan coba lagi.", 'danger');
                }
            }
        }
    });

    // Initial load
    fetchUsers(currentPage, currentSearch);
});