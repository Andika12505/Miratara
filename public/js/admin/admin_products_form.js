// JavaScript untuk Admin Product Form (Add/Edit Product)
// File: public/js/admin/admin_product_form.js

// --- Variabel Global ---
let fields = {};
let validationState = {};
let productForm, submitProductBtn, btnText, btnLoader;

// --- Fungsi Global (dapat dipanggil dari HTML onclick) ---

/** Menampilkan pesan alert */
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

/** Mereset form setelah konfirmasi. */
window.resetProductForm = function () {
    if (confirm("Apakah Anda yakin ingin mereset form? Semua data yang diisi akan hilang.")) {
        if (productForm) {
            productForm.reset();
            Object.values(fields).forEach(field => {
                if(field) {
                    field.classList.remove("is-valid", "is-invalid");
                    if (field.nextElementSibling && field.nextElementSibling.classList.contains("invalid-feedback")) {
                        field.nextElementSibling.textContent = "";
                    }
                }
            });
            updateSubmitButton();
        }
    }
};

// --- DOMContentLoaded Listener ---
document.addEventListener("DOMContentLoaded", function () {
    productForm = document.getElementById("productForm");
    submitProductBtn = document.getElementById("submitProductBtn");
    btnText = submitProductBtn ? submitProductBtn.querySelector(".btn-text") : null;
    btnLoader = submitProductBtn ? submitProductBtn.querySelector(".btn-loader") : null;

    fields = {
        name: document.getElementById("name"),
        slug: document.getElementById("slug"),
        description: document.getElementById("description"),
        category: document.getElementById("category"),
        price: document.getElementById("price"),
        discount_price: document.getElementById("discount_price"),
        stock: document.getElementById("stock"),
        image1: document.getElementById("image1"),
        image2: document.getElementById("image2"),
        isActive: document.getElementById("isActive"),
    };

    validationState = {
        name: false, slug: false, category: false, price: false, stock: false, image1: false,
        description: true, discount_price: true, image2: true, isActive: true
    };

    const productId = productForm ? productForm.getAttribute('data-product-id') : null;

    function validateField(field, isValid, message = "") {
        if (!field) return;
        if (isValid) {
            field.classList.remove("is-invalid");
            field.classList.add("is-valid");
            if (field.nextElementSibling && field.nextElementSibling.classList.contains("invalid-feedback")) {
                field.nextElementSibling.textContent = "";
            }
        } else {
            field.classList.remove("is-valid");
            field.classList.add("is-invalid");
            if (field.nextElementSibling && field.nextElementSibling.classList.contains("invalid-feedback")) {
                field.nextElementSibling.textContent = message;
            }
        }
        return isValid;
    }

    if (fields.name && fields.slug) {
        fields.name.addEventListener('input', function() {
            if (!productId) {
                fields.slug.value = fields.name.value.toLowerCase()
                                .trim()
                                .replace(/[^\w\s-]/g, '')
                                .replace(/[\s_-]+/g, '-')
                                .replace(/^-+|-+$/g, '');
                validateSlug();
            }
        });
        fields.slug.addEventListener('input', validateSlug);
        fields.slug.addEventListener('blur', validateSlug);
    }
    
    const checkSlugAvailability = debounce(async function (slug) {
        if (!slug) return;
        try {
            const response = await fetch("/api/admin/check-slug-availability", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]') ? document.querySelector('meta[name="csrf-token"]').content : ''
                },
                body: JSON.stringify({ slug: slug, id: productId }),
            });
            const data = await response.json();
            if (data.available) {
                validationState.slug = validateField(fields.slug, true);
            } else {
                validationState.slug = validateField(fields.slug, false, "Slug sudah digunakan");
            }
        } catch (error) {
            console.error("Error checking slug:", error);
            validationState.slug = validateField(fields.slug, true);
        }
        updateSubmitButton();
    }, 500);

    function validateSlug() {
        if (!fields.slug) return;
        const value = fields.slug.value.trim();
        if (value === "") {
            validationState.slug = validateField(fields.slug, false, "Slug harus diisi");
        } else if (!/^[a-z0-9-]+$/.test(value)) {
            validationState.slug = validateField(fields.slug, false, "Slug hanya boleh huruf kecil, angka, dan tanda hubung");
        } else {
            checkSlugAvailability(value);
            return;
        }
        updateSubmitButton();
    }

    if (fields.name) fields.name.addEventListener('input', () => { validationState.name = validateField(fields.name, fields.name.value.trim() !== "", "Nama produk harus diisi"); updateSubmitButton(); });
    if (fields.category) fields.category.addEventListener('input', () => { validationState.category = validateField(fields.category, fields.category.value.trim() !== "", "Kategori harus diisi"); updateSubmitButton(); });
    if (fields.price) fields.price.addEventListener('input', () => { validationState.price = validateField(fields.price, fields.price.value.trim() !== "" && parseFloat(fields.price.value) >= 0, "Harga harus diisi dan >= 0"); updateSubmitButton(); });
    if (fields.stock) fields.stock.addEventListener('input', () => { validationState.stock = validateField(fields.stock, fields.stock.value.trim() !== "" && parseInt(fields.stock.value) >= 0, "Stok harus diisi dan >= 0"); updateSubmitButton(); });
    
    if (fields.image1) {
        fields.image1.addEventListener('change', () => {
            if (!productId) {
                validationState.image1 = validateField(fields.image1, fields.image1.files.length > 0, "Gambar utama harus diisi");
            } else {
                validationState.image1 = true;
            }
            updateSubmitButton();
        });
    } else {
        validationState.image1 = true;
    }

    function updateSubmitButton() {
        if (!submitProductBtn) return;
        const allValid = Object.values(validationState).every(Boolean);
        submitProductBtn.disabled = !allValid;
    }

    function debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }

    if (productForm) {
        productForm.addEventListener("submit", async function (e) {
            e.preventDefault();

            validationState.name = validateField(fields.name, fields.name.value.trim() !== "", "Nama produk harus diisi");
            validateSlug();
            validationState.category = validateField(fields.category, fields.category.value.trim() !== "", "Kategori harus diisi");
            validationState.price = validateField(fields.price, fields.price.value.trim() !== "" && parseFloat(fields.price.value) >= 0, "Harga harus diisi dan >= 0");
            validationState.stock = validateField(fields.stock, fields.stock.value.trim() !== "" && parseInt(fields.stock.value) >= 0, "Stok harus diisi dan >= 0");
            if (!productId) {
                validationState.image1 = validateField(fields.image1, fields.image1.files && fields.image1.files.length > 0, "Gambar utama harus diisi");
            } else {
                validationState.image1 = true;
            }

            await new Promise(resolve => setTimeout(resolve, 600));

            const allValid = Object.values(validationState).every(Boolean);

            if (!allValid) {
                showAlert("Mohon lengkapi semua field yang wajib diisi dengan benar.", "warning");
                return;
            }

            if (btnText) btnText.classList.add("d-none");
            if (btnLoader) btnLoader.classList.remove("d-none");
            if (submitProductBtn) submitProductBtn.disabled = true;
            
            try {
                const formData = new FormData(productForm);
                formData.set('is_active', fields.isActive.checked ? '1' : '0');

                let url = "/api/admin/products";
                let method = "POST";

                if (productId) {
                    url = `/api/admin/products/${productId}`;
                    method = "POST"; // For FormData, always POST, then spoof method
                    formData.append('_method', 'PUT'); // Spoof PUT method for update
                }
                
                const response = await fetch(url, {
                    method: method,
                    headers: {
                        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]') ? document.querySelector('meta[name="csrf-token"]').content : ''
                    },
                    body: formData,
                });

                const data = await response.json();

                if (data.success) {
                    showAlert(data.message, "success");

                    setTimeout(() => {
                        window.location.href = "{{ route('admin.products.index_page') }}";
                    }, 2000);
                } else {
                    showAlert(data.message, "danger");
                }
            } catch (error) {
                console.error("Product form submission error:", error);
                showAlert("Terjadi kesalahan saat menyimpan produk. Silakan coba lagi.", "danger");
            } finally {
                if (btnText) btnText.classList.remove("d-none");
                if (btnLoader) btnLoader.classList.add("d-none");
                if (submitProductBtn) submitProductBtn.disabled = false;
            }
        });
    }

    if (!productId && fields.image1 && fields.image1.files) {
        validationState.image1 = fields.image1.files.length > 0;
    } else if (productId) {
        validationState.image1 = true;
    }
    updateSubmitButton();

    for (const key in fields) {
        if (fields[key] && typeof fields[key].addEventListener === 'function' && key !== 'name' && key !== 'slug') {
            fields[key].addEventListener('blur', () => {
                if (key === 'price') validationState.price = validateField(fields.price, fields.price.value.trim() !== "" && parseFloat(fields.price.value) >= 0, "Harga harus diisi dan >= 0");
                if (key === 'stock') validationState.stock = validateField(fields.stock, fields.stock.value.trim() !== "" && parseInt(fields.stock.value) >= 0, "Stok harus diisi dan >= 0");
                if (key === 'category') validationState.category = validateField(fields.category, fields.category.value.trim() !== "", "Kategori harus diisi");
                if (key === 'image1' && !productId) validationState.image1 = validateField(fields.image1, fields.image1.files && fields.image1.files.length > 0, "Gambar utama harus diisi");
                
                updateSubmitButton();
            });
        }
    }
});