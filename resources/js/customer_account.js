// JavaScript untuk Halaman Akun Pengguna
// File: resources/js/customer_account.js

// --- Global Functions (didefinisikan di <script> di resources/views/customer/account/view.blade.php) ---
// showAlert, clearAlert, togglePassword, debounce, validateField are already defined in the Blade file
// No need to redefine them here if they are in the <script> block before this file is loaded.

document.addEventListener("DOMContentLoaded", function () {
    // --- Section Navigation Logic ---
    const navLinks = document.querySelectorAll('.list-group-item-action[data-section]');
    const sections = {
        biodata: document.getElementById('biodataSectionContent'),
        transaction: document.getElementById('transactionSectionContent'),
        cart: document.getElementById('cartSectionContent'),
        password: document.getElementById('passwordSectionContent')
    };

    function showSection(sectionId) {
        for (const id in sections) {
            if (sections[id]) {
                sections[id].style.display = 'none';
            }
        }
        if (sections[sectionId]) {
            sections[sectionId].style.display = 'block';
        }

        navLinks.forEach(link => {
            link.classList.remove('active');
            if (link.dataset.section === sectionId) {
                link.classList.add('active');
            }
        });
        // Optional: Update URL hash for direct linking to sections
        if (history.pushState) {
            history.pushState(null, null, '#' + sectionId);
        } else {
            location.hash = sectionId;
        }
    }

    navLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            showSection(this.dataset.section);
        });
    });

    // Show initial section based on URL hash if present
    const initialHash = window.location.hash.substring(1);
    if (initialHash && sections[initialHash]) {
        showSection(initialHash);
    } else {
        showSection('biodata'); // Default to biodata section
    }


    // --- Biodata Form Logic ---
    const profileForm = document.getElementById("profileForm");
    const updateProfileBtn = document.getElementById("updateProfileBtn");
    const profileFields = {
        fullName: document.getElementById("full_name"),
        username: document.getElementById("username"),
        email: document.getElementById("email"),
        phone: document.getElementById("phone"),
    };
    let profileValidationState = {
        fullName: true, username: true, email: true, phone: true // Assume valid on load
    };

    function updateProfileSubmitButton() {
        updateProfileBtn.disabled = !Object.values(profileValidationState).every(Boolean);
    }

    // Username & Email availability check (re-use from admin_edit_user.js concept)
    const checkUsernameAvailability = debounce(async function(username) {
        if (!profileFields.username) return;
        if (username.length < 3) { 
            profileValidationState.username = validateField(profileFields.username, false, "Username minimal 3 karakter"); 
            updateProfileSubmitButton(); 
            return; 
        }
        try {
            const response = await fetch(window.checkAvailabilityUrl, { // Menggunakan URL global
                method: "POST", headers: { "Content-Type": "application/json", "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content },
                body: JSON.stringify({ type: "username", value: username, ignore_id: window.currentUserId }),
            });
            const data = await response.json();
            profileValidationState.username = validateField(profileFields.username, data.available, "Username sudah digunakan");
        } catch (error) { 
            console.error("Error checking username:", error); 
            profileValidationState.username = validateField(profileFields.username, true); // Assume valid if API errors
        }
        updateProfileSubmitButton();
    }, 500);

    const checkEmailAvailability = debounce(async function(email) {
        if (!profileFields.email) return;
        try {
            const response = await fetch(window.checkAvailabilityUrl, { // Menggunakan URL global
                method: "POST", headers: { "Content-Type": "application/json", "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content },
                body: JSON.stringify({ type: "email", value: email, ignore_id: window.currentUserId }),
            });
            const data = await response.json();
            profileValidationState.email = validateField(profileFields.email, data.available, "Email sudah terdaftar");
        } catch (error) { 
            console.error("Error checking email:", error); 
            profileValidationState.email = validateField(profileFields.email, true); // Assume valid if API errors
        }
        updateProfileSubmitButton();
    }, 500);

    // Basic validation functions for profile form
    if (profileFields.fullName) profileFields.fullName.addEventListener('input', () => { 
        profileValidationState.fullName = validateField(profileFields.fullName, profileFields.fullName.value.trim().length >= 2, "Nama lengkap harus diisi (min 2 karakter)"); 
        updateProfileSubmitButton(); 
    });
    if (profileFields.username) profileFields.username.addEventListener('input', () => { 
        profileValidationState.username = validateField(profileFields.username, profileFields.username.value.trim().length >= 3 && /^[a-zA-Z0-9_]{3,20}$/.test(profileFields.username.value.trim()), "Username min 3 karakter, huruf, angka, underscore");
        if(profileValidationState.username) checkUsernameAvailability(profileFields.username.value.trim()); // Only check availability if basic format is valid
        updateProfileSubmitButton();
    });
    if (profileFields.email) profileFields.email.addEventListener('input', () => { 
        profileValidationState.email = validateField(profileFields.email, /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(profileFields.email.value.trim()), "Format email tidak valid"); 
        if(profileValidationState.email) checkEmailAvailability(profileFields.email.value.trim()); // Only check availability if basic format is valid
        updateProfileSubmitButton(); 
    });
    if (profileFields.phone) profileFields.phone.addEventListener('input', () => { 
        profileValidationState.phone = validateField(profileFields.phone, profileFields.phone.value.trim() === '' || /^(\+62|62|0)[2-9]\d{7,11}$/.test(profileFields.phone.value.trim()), "Format nomor telepon tidak valid"); 
        updateProfileSubmitButton(); 
    });

    // Initial validation check for profile form on load
    if (profileFields.fullName) profileFields.fullName.dispatchEvent(new Event('input'));
    if (profileFields.username) profileFields.username.dispatchEvent(new Event('input'));
    if (profileFields.email) profileFields.email.dispatchEvent(new Event('input'));
    if (profileFields.phone) profileFields.phone.dispatchEvent(new Event('input'));
    updateProfileSubmitButton(); // Call explicitly to set initial state

    // Profile form submission
    if (profileForm) {
        profileForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            clearAlert();
            updateProfileBtn.disabled = true;

            // Re-validate all fields before submit
            profileFields.fullName.dispatchEvent(new Event('input'));
            profileFields.username.dispatchEvent(new Event('input'));
            profileFields.email.dispatchEvent(new Event('input'));
            profileFields.phone.dispatchEvent(new Event('input'));

            if (!Object.values(profileValidationState).every(Boolean)) {
                showAlert("Mohon lengkapi semua field biodata dengan benar.", "warning");
                updateProfileBtn.disabled = false;
                return;
            }

            try {
                const formData = new FormData(profileForm);
                formData.set('_token', document.querySelector('meta[name="csrf-token"]').content); // Add CSRF token

                const response = await fetch(window.customerAccountUpdateProfileUrl, {
                    method: 'POST', // Laravel will handle @method('PUT') implicitly if using forms for update
                    headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
                    body: formData,
                });

                if (response.status === 419) { showAlert("Sesi Anda telah kedaluwarsa. Mohon refresh halaman dan coba lagi.", "danger"); setTimeout(() => { window.location.reload(); }, 3000); return; }
                
                const contentType = response.headers.get("content-type");
                if (contentType && contentType.indexOf("application/json") !== -1) {
                    const data = await response.json();
                    if (data.success) { showAlert(data.message, "success"); } 
                    else { 
                        if (response.status === 422 && data.errors) {
                            for (const fieldName in data.errors) {
                                if (profileFields[fieldName]) { validateField(profileFields[fieldName], false, data.errors[fieldName][0]); }
                            }
                        }
                        showAlert(data.message || "Gagal memperbarui biodata. Silakan coba lagi.", "danger"); 
                    }
                } else { throw new Error('Received non-JSON response'); }

            } catch (error) {
                console.error("Profile update error:", error);
                showAlert("Terjadi kesalahan jaringan saat memperbarui biodata. Silakan coba lagi.", "danger");
            } finally {
                updateProfileBtn.disabled = false;
            }
        });
    }

    // --- Password Form Logic ---
    const passwordForm = document.getElementById("passwordForm");
    const updatePasswordBtn = document.getElementById("updatePasswordBtn");
    const passwordFields = {
                current_password: document.getElementById("current_password"),
                password: document.getElementById("password"),
                password_confirmation: document.getElementById("password_confirmation"),
            };
            const passwordReqsElements = {
                length: document.getElementById("req-length"),
                capital: document.getElementById("req-capital"),
                number: document.getElementById("req-number"),
                special: document.getElementById("req-special"),
            };
            let passwordValidationState = {
                current_password: false, password: false, password_confirmation: false
            };

            function updatePasswordSubmitButton() {
                updatePasswordBtn.disabled = !Object.values(passwordValidationState).every(Boolean);
            }

            function validateNewPassword() {
                const value = passwordFields.password.value;
                const requirements = {
                    length: value.length >= 8 && value.length <= 20,
                    capital: /[A-Z]/.test(value),
                    number: /\d/.test(value),
                    special: /[@!$#%^<>?_-]/.test(value),
                };

                passwordReqsElements.length.classList.toggle('valid', requirements.length); passwordReqsElements.length.classList.toggle('invalid', !requirements.length);
                passwordReqsElements.capital.classList.toggle('valid', requirements.capital); passwordReqsElements.capital.classList.toggle('invalid', !requirements.capital);
                passwordReqsElements.number.classList.toggle('valid', requirements.number); passwordReqsElements.number.classList.toggle('invalid', !requirements.number);
                passwordReqsElements.special.classList.toggle('valid', requirements.special); passwordReqsElements.special.classList.toggle('invalid', !requirements.special);

                const allRequirementsMet = Object.values(requirements).every(Boolean);
                passwordValidationState.password = validateField(passwordFields.password, allRequirementsMet, "Password baru belum memenuhi semua persyaratan");
                
                // Also validate confirmation if new password is valid
                validatePasswordConfirmation();
                updatePasswordSubmitButton();
            }

            function validatePasswordConfirmation() {
                const isConfirmed = passwordFields.password.value === passwordFields.password_confirmation.value && passwordFields.password_confirmation.value.length > 0;
                passwordValidationState.password_confirmation = validateField(passwordFields.password_confirmation, isConfirmed, "Konfirmasi password tidak cocok");
                updatePasswordSubmitButton();
            }

            // Event listeners for password form
            if (passwordFields.current_password) passwordFields.current_password.addEventListener('input', () => { passwordValidationState.current_password = validateField(passwordFields.current_password, passwordFields.current_password.value.length > 0, "Password lama harus diisi"); updatePasswordSubmitButton(); });
            if (passwordFields.password) passwordFields.password.addEventListener('input', validateNewPassword);
            if (passwordFields.password_confirmation) passwordFields.password_confirmation.addEventListener('input', validatePasswordConfirmation);


            if (passwordForm) {
                passwordForm.addEventListener('submit', async function(e) {
                    e.preventDefault();
                    clearAlert();
                    updatePasswordBtn.disabled = true;

                    // Re-validate all password fields
                    passwordValidationState.current_password = validateField(passwordFields.current_password, passwordFields.current_password.value.length > 0, "Password lama harus diisi");
                    validateNewPassword();
                    validatePasswordConfirmation();

                    if (!Object.values(passwordValidationState).every(Boolean)) {
                        showAlert("Mohon lengkapi semua field password dengan benar.", "warning");
                        updatePasswordBtn.disabled = false;
                        return;
                    }

                    try {
                        const formData = new FormData(passwordForm);
                        formData.set('_token', document.querySelector('meta[name="csrf-token"]').content);

                        const response = await fetch(window.customerAccountUpdatePasswordUrl, {
                            method: 'POST', // Laravel akan membaca @method('PUT')
                            headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
                            body: formData,
                        });

                        if (response.status === 419) { showAlert("Sesi Anda telah kedaluwarsa. Mohon refresh halaman dan coba lagi.", "danger"); setTimeout(() => { window.location.reload(); }, 3000); return; }

                        const contentType = response.headers.get("content-type");
                        if (contentType && contentType.indexOf("application/json") !== -1) {
                            const data = await response.json();
                            if (data.success) { 
                                showAlert(data.message, "success"); 
                                passwordForm.reset(); // Clear form on success
                                // Reset validation styling after successful password change
                                for (const key in passwordFields) {
                                    if (passwordFields[key]) {
                                        passwordFields[key].classList.remove("is-valid", "is-invalid");
                                        if (passwordFields[key].nextElementSibling && passwordFields[key].nextElementSibling.classList.contains("invalid-feedback")) {
                                            passwordFields[key].nextElementSibling.textContent = "";
                                        }
                                    }
                                }
                                // Reset password requirements display
                                for (const key in passwordReqsElements) {
                                    if (passwordReqsElements[key]) {
                                        passwordReqsElements[key].classList.remove('valid', 'invalid');
                                    }
                                }

                                // Re-evaluate submit button state
                                passwordValidationState = { current_password: false, password: false, password_confirmation: false };
                                updatePasswordSubmitButton();

                            } else { 
                                if (response.status === 403 || (response.status === 422 && data.message === 'Password lama tidak cocok.')) {
                                    validateField(passwordFields.current_password, false, data.message); // Specific error for current password
                                }
                                else if (response.status === 422 && data.errors) {
                                    for (const fieldName in data.errors) {
                                        if (passwordFields[fieldName]) { validateField(passwordFields[fieldName], false, data.errors[fieldName][0]); }
                                    }
                                }
                                showAlert(data.message || "Gagal memperbarui password. Silakan coba lagi.", "danger"); 
                            }
                        } else { throw new Error('Received non-JSON response'); }
                    } catch (error) {
                        console.error("Password update error:", error);
                        showAlert("Terjadi kesalahan jaringan saat memperbarui password. Silakan coba lagi.", "danger");
                    } finally {
                        updatePasswordBtn.disabled = false;
                    }
                });
            }

            // Initial checks to set button state on page load
            updateProfileSubmitButton();
            updatePasswordSubmitButton();
            validateNewPassword(); // To show password requirements initially
        });
    </script>
</body>
</html>