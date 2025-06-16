// JavaScript untuk Admin Edit User Page
// File: public/js/admin/admin_edit_user.js

// --- Variabel Global ---
// Diinisialisasi di DOMContentLoaded
let fields;
let passwordReqs;
let validationState;

// --- Fungsi Global (dapat dipanggil dari HTML onclick) ---

/**
 * Menampilkan atau menyembunyikan password input.
 * @param {string} fieldId ID dari input field password.
 */
window.togglePassword = function (fieldId) {
    const field = document.getElementById(fieldId);
    const toggle = field.parentNode.querySelector("button");
    const icon = toggle.querySelector("i");

    if (field.type === "password") {
        field.type = "text";
        icon.className = "fas fa-eye-slash";
    } else {
        field.type = "password";
        icon.className = "fas fa-eye";
    }
};

/**
 * Mereset form setelah konfirmasi.
 */
window.resetForm = function () {
    // Di halaman edit, reset berarti memuat ulang data awal dari server
    if (confirm("Apakah Anda yakin ingin mereset form? Semua perubahan yang belum disimpan akan hilang.")) {
        // Cukup refresh halaman untuk memuat ulang data asli dari server
        window.location.reload(); 
    }
};

/**
 * Menampilkan pesan alert di bagian atas main content.
 * @param {string} message Pesan yang akan ditampilkan.
 * @param {string} type Tipe alert (success, danger, warning).
 */
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

/**
 * Menghapus alert dari container.
 */
function clearAlert() {
    const alertContainer = document.getElementById("alertContainer");
    if (alertContainer) {
        alertContainer.innerHTML = "";
    }
}

// --- DOMContentLoaded Listener (Kode utama yang berjalan setelah DOM siap) ---
document.addEventListener("DOMContentLoaded", function () {
    // Form elements
    const form = document.getElementById("editUserForm");
    const submitBtn = document.getElementById("submitBtn");
    const btnText = submitBtn ? submitBtn.querySelector(".btn-text") : null;
    const btnLoader = submitBtn ? submitBtn.querySelector(".btn-loader") : null;

    // Input fields
    fields = { // Inisialisasi di sini
        fullName: document.getElementById("fullName"),
        username: document.getElementById("username"),
        email: document.getElementById("email"),
        phone: document.getElementById("phone"),
        password: document.getElementById("password"),
        isAdmin: document.getElementById("is_admin"), // Tambahkan ini
    };

    // Password requirements elements (referensi ke elemen DOM, bukan mengevaluasi nilai)
    passwordReqs = { // Inisialisasi di sini
        length: document.getElementById("req-length"),
        capital: document.getElementById("req-capital"),
        number: document.getElementById("req-number"),
        special: document.getElementById("req-special"),
    };

    // Validation state
    // Initial validation state, username/email/phone dimulai sebagai valid
    // karena mereka sudah ada di database (kecuali kosong/berubah menjadi duplikat)
    validationState = { // Inisialisasi di sini
        fullName: true, // Asumsi nama sudah benar dari DB
        username: true,
        email: true,
        phone: true, 
        password: true, // Password opsional saat update, jadi true by default
        isAdmin: true, // Default true
    };

    // Debounce function untuk API calls
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

    // Validation helper functions
    function validateField(field, isValid, message = "") {
        if (!field) return;
        if (isValid) {
            field.classList.remove("is-invalid");
            field.classList.add("is-valid");
            if (field.nextElementSibling && field.nextElementSibling.classList.contains("invalid-feedback")) {
                field.nextElementSibling.textContent = "";
            }
        } else {
            field.classList.remove("is-invalid");
            field.classList.add("is-invalid");
            if (field.nextElementSibling && field.nextElementSibling.classList.contains("invalid-feedback")) {
                field.nextElementSibling.textContent = message;
            }
        }
        return isValid;
    }

    // Full Name validation
    function validateFullName() {
        if (!fields.fullName) return;
        const value = fields.fullName.value.trim();
        const nameRegex = /^[a-zA-Z\s]{2,50}$/;

        if (value === "") {
            validationState.fullName = validateField(fields.fullName, false, "Nama lengkap harus diisi");
        } else if (!nameRegex.test(value)) {
            validationState.fullName = validateField(fields.fullName, false, "Nama lengkap hanya boleh huruf dan spasi (2-50 karakter)");
        } else {
            validationState.fullName = validateField(fields.fullName, true);
        }
        updateSubmitButton();
    }

    // Username validation with availability check
    const checkUsernameAvailability = debounce(async function (username) {
        if (!fields.username) return;
        if (username.length < 3) {
            validationState.username = validateField(fields.username, false, "Username minimal 3 karakter");
            updateSubmitButton();
            return;
        }

        try {
            // Mengambil token terbaru setiap kali sebelum permintaan
            const csrfToken = document.querySelector('meta[name="csrf-token"]').content; 
            const response = await fetch("/admin/check-availability", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": csrfToken 
                },
                body: JSON.stringify({ type: "username", value: username, ignore_id: window.currentUserId }), 
            });

            const data = await response.json();

            if (data.available) {
                validationState.username = validateField(fields.username, true);
            } else {
                validationState.username = validateField(fields.username, false, "Username sudah digunakan");
            }
        } catch (error) {
            console.error("Error checking username:", error);
            validationState.username = validateField(fields.username, true); // Asumsi valid jika error API
        }
        updateSubmitButton();
    }, 500);

    function validateUsername() {
        if (!fields.username) return;
        const value = fields.username.value.trim();
        const usernameRegex = /^[a-zA-Z0-9_]{3,20}$/;

        if (value === "") {
            validationState.username = validateField(fields.username, false, "Username harus diisi");
        } else if (!usernameRegex.test(value)) {
            validationState.username = validateField(fields.username, false, "Username harus 3-20 karakter, huruf, angka, dan underscore saja");
        } else {
            checkUsernameAvailability(value);
            return;
        }
        updateSubmitButton();
    }

    // Email validation with availability check
    const checkEmailAvailability = debounce(async function (email) {
        if (!fields.email) return;
        try {
            const csrfToken = document.querySelector('meta[name="csrf-token"]').content; // Mengambil token terbaru
            const response = await fetch("/admin/check-availability", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": csrfToken 
                },
                body: JSON.stringify({ type: "email", value: email, ignore_id: window.currentUserId }), 
            });

            const data = await response.json();

            if (data.available) {
                validationState.email = validateField(fields.email, true);
            } else {
                validationState.email = validateField(fields.email, false, "Email sudah terdaftar");
            }
        } catch (error) {
            console.error("Error checking email:", error);
            validationState.email = validateField(fields.email, true); // Asumsi valid jika error API
        }
        updateSubmitButton();
    }, 500);

    function validateEmail() {
        if (!fields.email) return;
        const value = fields.email.value.trim();
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

        if (value === "") {
            validationState.email = validateField(fields.email, false, "Email harus diisi");
        } else if (!emailRegex.test(value)) {
            validationState.email = validateField(fields.email, false, "Format email tidak valid");
        } else {
            checkEmailAvailability(value);
            return;
        }
        updateSubmitButton();
    }

    // Phone validation (optional)
    function validatePhone() {
        if (!fields.phone) return;
        const value = fields.phone.value.trim();

        if (value === "") {
            validationState.phone = validateField(fields.phone, true);
        } else {
            const phoneRegex = /^(\+62|62|0)[2-9]\d{7,11}$/;
            if (!phoneRegex.test(value)) {
                validationState.phone = validateField(fields.phone, false, "Format nomor telepon Indonesia tidak valid");
            } else {
                validationState.phone = validateField(fields.phone, true);
            }
        }
        updateSubmitButton();
    }

    // Password validation and strength check
    function validatePassword() {
        if (!fields.password) return;
        const value = fields.password.value; // Dapatkan nilai password di sini
        const requirements = {
            length: value.length === 0 || (value.length >= 8 && value.length <= 20), // Optional if empty
            capital: value.length === 0 || /[A-Z]/.test(value),
            number: value.length === 0 || /\d/.test(value),
            special: value.length === 0 || /[@!$#%^<>?_-]/.test(value),
        };

        updatePasswordRequirement("length", requirements.length);
        updatePasswordRequirement("capital", requirements.capital);
        updatePasswordRequirement("number", requirements.number); // FIX: Corrected typo
        updatePasswordRequirement("special", requirements.special);

        const allRequirementsMet = Object.values(requirements).every(Boolean);

        if (value === "") { // Password is optional for edit, so if empty it's valid for this check
            validationState.password = validateField(fields.password, true);
        } else if (!allRequirementsMet) {
            validationState.password = validateField(fields.password, false, "Password belum memenuhi semua persyaratan");
        } else {
            validationState.password = validateField(fields.password, true);
        }
        updateSubmitButton();
    }

    // Memperbarui indikator persyaratan password
    function updatePasswordRequirement(type, isValid) {
        const element = passwordReqs[type];
        if (!element) return;

        if (isValid) {
            element.classList.add("valid");
            element.classList.remove("invalid");
        } else {
            element.classList.remove("valid");
            element.classList.add("invalid");
        }
    }

    // Update submit button state
    function updateSubmitButton() {
        if (!submitBtn) return;
        const allValid = Object.values(validationState).every(Boolean);
        submitBtn.disabled = !allValid;
    }

    // Event listeners for input fields
    if (fields.fullName) fields.fullName.addEventListener("input", validateFullName);
    if (fields.fullName) fields.fullName.addEventListener("blur", validateFullName);

    if (fields.username) fields.username.addEventListener("input", validateUsername);
    if (fields.username) fields.username.addEventListener("blur", validateUsername);

    if (fields.email) fields.email.addEventListener("input", validateEmail);
    if (fields.email) fields.email.addEventListener("blur", validateEmail);

    if (fields.phone) fields.phone.addEventListener("input", validatePhone);
    if (fields.phone) fields.phone.addEventListener("blur", validatePhone);

    if (fields.password) fields.password.addEventListener("input", validatePassword);
    if (fields.password) fields.password.addEventListener("blur", validatePassword);

    // Initial validation check on load (to enable/disable submit button)
    validateFullName();
    validateUsername();
    validateEmail();
    validatePhone();
    validatePassword(); // Re-evaluate password requirements on load
    updateSubmitButton(); // Call explicitly to set initial state

    // Form submission
    if (form) {
        form.addEventListener("submit", async function (e) {
            e.preventDefault();

            // Re-validate all fields before submit
            validateFullName();
            validateUsername();
            validateEmail();
            validatePhone();
            validatePassword();
            // isAdmin is a checkbox, no complex validation needed here unless specific rules apply

            const allValid = Object.values(validationState).every(Boolean);

            if (!allValid) {
                showAlert("Mohon lengkapi semua field yang wajib diisi dengan benar.", "warning");
                return;
            }

            if (btnText) btnText.classList.add("d-none");
            if (btnLoader) btnLoader.classList.remove("d-none");
            if (submitBtn) submitBtn.disabled = true;
            clearAlert();

            try {
                const formData = new FormData(form);
                
                // PENTING: Untuk checkbox 'is_admin', FormData hanya mengirim 'on' jika dicentang.
                // Kita perlu secara eksplisit mengirim 1 atau 0 untuk validasi 'boolean' Laravel.
                // Ini akan memastikan is_admin selalu 0 jika checkbox tidak dicentang, atau 1 jika dicentang.
                formData.set('is_admin', fields.isAdmin.checked ? '1' : '0'); 

                // Mengambil CSRF token terbaru sebelum setiap permintaan
                const csrfToken = document.querySelector('meta[name="csrf-token"]').content; 

                // Menambahkan CSRF token ke FormData sebagai fallback (penting untuk 419)
                formData.set('_token', csrfToken); // Tambahkan ini


                // Fetch API untuk PUT method
                // URL diambil dari action form (sudah di set di Blade, misal: /admin/users/{id})
                const url = form.action; 

                const response = await fetch(url, {
                    method: "POST", // Method fetch akan dikirim sebagai POST, lalu Laravel akan membaca @method('PUT')
                    headers: {
                        "X-CSRF-TOKEN": csrfToken, // Menggunakan token yang baru diambil
                        // "Content-Type": "application/json" TIDAK DIGUNAKAN dengan FormData karena pakai FormData
                    },
                    body: formData,
                });

                // Penting: Handle respons 419 (Page Expired) atau non-JSON
                if (response.status === 419) {
                    showAlert("Sesi Anda telah kedaluwarsa. Mohon refresh halaman dan coba lagi.", "danger");
                    // Opsi: refresh halaman secara otomatis
                    setTimeout(() => { window.location.reload(); }, 3000);
                    return; // Hentikan eksekusi lebih lanjut
                }

                // Coba parse JSON, tapi tangkap jika bukan JSON (misal, halaman error HTML)
                const contentType = response.headers.get("content-type");
                if (contentType && contentType.indexOf("application/json") !== -1) {
                    const data = await response.json();

                    if (data.success) {
                        showAlert(data.message, "success");
                        // Tambahkan log untuk debugging
                        console.log("Preparing to redirect. Target URL:", window.adminUserIndexUrl); 

                        setTimeout(() => {
                            console.log("Executing redirect to:", window.adminUserIndexUrl); // Tambahkan log di sini
                            window.location.href = window.adminUserIndexUrl; // Redirect ke halaman index
                        }, 2000);
                    } else {
                        // Jika ada error validasi spesifik dari backend (HTTP 422)
                        if (response.status === 422 && data.errors) {
                            for (const fieldName in data.errors) {
                                // Coba temukan field di objek `fields`
                                if (fields[fieldName]) {
                                    validateField(fields[fieldName], false, data.errors[fieldName][0]);
                                }
                                // Tambahan: jika error bukan dari field yang langsung divalidasi (misal error server general)
                                else {
                                    console.error(`Error for unknown field: ${fieldName}`, data.errors[fieldName]);
                                }
                            }
                            showAlert(data.message || "Validasi gagal dari server. Periksa kembali input Anda.", "danger");
                        } else {
                            showAlert(data.message || "Terjadi kesalahan saat menyimpan perubahan. Silakan coba lagi.", "danger");
                        }
                    }
                } else {
                    // Respons bukan JSON, kemungkinan halaman error HTML
                    const textError = await response.text();
                    console.error("Non-JSON response received:", textError);
                    showAlert("Terjadi kesalahan server. Mohon periksa konsol untuk detailnya.", "danger");
                }

            } catch (error) {
                console.error("Edit user error:", error);
                showAlert("Terjadi kesalahan jaringan saat menyimpan perubahan. Silakan coba lagi.", "danger");
            } finally {
                if (btnText) btnText.classList.remove("d-none");
                if (btnLoader) btnLoader.classList.add("d-none");
                if (submitBtn) submitBtn.disabled = false;
            }
        });
    }

    // Set initial validation state based on current field values
    // This makes sure the submit button is correctly enabled/disabled on page load
    // These functions also handle initial styling for valid/invalid
    validateFullName();
    validateUsername();
    validateEmail();
    validatePhone();
    validatePassword(); // Re-evaluate password requirements on load
    updateSubmitButton(); // Call explicitly to set initial state

});
