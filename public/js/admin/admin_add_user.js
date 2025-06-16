// JavaScript untuk Admin Add User Page
// File: public/js/admin/admin_add_user.js

// --- Variabel Global ---
// Diinisialisasi di DOMContentLoaded
let fields;
let passwordReqs; // Ini akan menjadi objek elemen DOM
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
    if (
        confirm(
            "Apakah Anda yakin ingin mereset form? Semua data yang diisi akan hilang."
        )
    ) {
        const form = document.getElementById("addUserForm");
        form.reset();

        // Reset validation states
        Object.keys(validationState).forEach((key) => {
            if (key === "phone") {
                validationState[key] = true;
            } else {
                validationState[key] = false;
            }
        });

        // Reset field styles
        const fieldsArray = Object.values(fields);
        fieldsArray.forEach((field) => {
            if (field) { // Tambahkan cek null
                field.classList.remove("is-valid", "is-invalid");
                if (field.nextElementSibling && field.nextElementSibling.classList.contains("invalid-feedback")) {
                    field.nextElementSibling.textContent = "";
                }
            }
        });

        // Reset password requirements
        const passwordReqsArray = Object.values(passwordReqs);
        passwordReqsArray.forEach((req) => {
            if (req) { // Tambahkan cek null
                req.classList.remove("valid", "invalid");
            }
        });

        clearAlert();
        updateSubmitButton();
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
    const form = document.getElementById("addUserForm");
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
    };

    // Password requirements elements (referensi ke elemen DOM, bukan mengevaluasi nilai)
    passwordReqs = { // Inisialisasi di sini
        length: document.getElementById("req-length"),
        capital: document.getElementById("req-capital"),
        number: document.getElementById("req-number"),
        special: document.getElementById("req-special"),
    };

    // Validation state
    validationState = { // Inisialisasi di sini
        fullName: false,
        username: false,
        email: false,
        phone: true, // Phone is optional
        password: false,
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
            field.classList.remove("is-valid");
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
            const response = await fetch("/api/admin/check-availability", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]') ? document.querySelector('meta[name="csrf-token"]').content : ''
                },
                body: JSON.stringify({ type: "username", value: username }),
            });

            const data = await response.json();

            if (data.available) {
                validationState.username = validateField(fields.username, true);
            } else {
                validationState.username = validateField(fields.username, false, "Username sudah digunakan");
            }
        } catch (error) {
            console.error("Error checking username:", error);
            validationState.username = validateField(fields.username, true);
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
            const response = await fetch("/api/admin/check-availability", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]') ? document.querySelector('meta[name="csrf-token"]').content : ''
                },
                body: JSON.stringify({ type: "email", value: email }),
            });

            const data = await response.json();

            if (data.available) {
                validationState.email = validateField(fields.email, true);
            } else {
                validationState.email = validateField(fields.email, false, "Email sudah terdaftar");
            }
        } catch (error) {
            console.error("Error checking email:", error);
            validationState.email = validateField(fields.email, true);
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
            length: value.length >= 8 && value.length <= 20,
            capital: /[A-Z]/.test(value),
            number: /\d/.test(value),
            special: /[@!$#%^<>?_-]/.test(value),
        };

        updatePasswordRequirement("length", requirements.length);
        updatePasswordRequirement("capital", requirements.capital);
        updatePasswordRequirement("number", requirements.number);
        updatePasswordRequirement("special", requirements.special);

        const allRequirementsMet = Object.values(requirements).every(Boolean);

        if (value === "") {
            validationState.password = validateField(fields.password, false, "Password harus diisi");
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

    // Form submission
    if (form) {
        form.addEventListener("submit", async function (e) {
            e.preventDefault();

            validateFullName();
            validateUsername();
            validateEmail();
            validatePhone();
            validatePassword();

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

                const response = await fetch("/api/admin/users", {
                    method: "POST",
                    headers: {
                        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]') ? document.querySelector('meta[name="csrf-token"]').content : ''
                    },
                    body: formData,
                });

                const data = await response.json();

                if (data.success) {
                    showAlert(data.message, "success");

                    setTimeout(() => {
                        window.location.href = "{{ route('admin.users.index_page') }}";
                    }, 2000);
                } else {
                    showAlert(data.message, "danger");
                }
            } catch (error) {
                console.error("Add user error:", error);
                showAlert("Terjadi kesalahan saat menambah user. Silakan coba lagi.", "danger");
            } finally {
                if (btnText) btnText.classList.remove("d-none");
                if (btnLoader) btnLoader.classList.add("d-none");
                if (submitBtn) submitBtn.disabled = false;
            }
        });
    }

    updateSubmitButton();
});