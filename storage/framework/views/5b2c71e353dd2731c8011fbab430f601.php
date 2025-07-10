<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Akun Saya - MiraTara</title>
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="<?php echo e(asset('css/app.css')); ?>" rel="stylesheet">
    <style>
        body { padding-top: 56px; }
        .navbar-brand img { height: 30px; }
        .account-header { text-align: center; margin-bottom: 5px; }
        .account-header h1 { font-weight: 700; color: #333; }
        .profile-card { border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.08); }
        .profile-card .card-header { background-color: #f8f9fa; border-radius: 12px 12px 0 0 !important; }
        .btn-primary { background-color: #ffc0cb; border-color: #ffc0cb; transition: background-color 0.3s ease; }
        .btn-primary:hover { background-color: #ff8fab; border-color: #ff8fab; }
        .invalid-feedback { display: block; font-size: 0.85rem; }
        .alert { border-radius: 8px; }
        .password-requirements { font-size: 0.85rem; color: #6c757d; }
        .password-requirements ul { list-style: none; padding-left: 0; }
        .password-requirements li { margin-bottom: 3px; }
        .password-requirements li.valid { color: green; }
        .password-requirements li.invalid { color: red; }
    </style>
</head>
<body>
    <div class="container py-4">
        <div class="account-header mt-5">
            <h1>Akun Saya</h1>
            <p class="text-muted">Kelola informasi profil, transaksi, dan keranjang belanja Anda.</p>
        </div>

        <div id="alertContainer" class="mt-3"></div>

        <div class="row">
            <div class="col-md-3">
                <div class="list-group profile-card">
                    <a href="#" class="list-group-item list-group-item-action active" data-section="biodata"><i class="fas fa-user me-2"></i> Biodata</a>
                    <a href="#" class="list-group-item list-group-item-action" data-section="transaction"><i class="fas fa-history me-2"></i> Riwayat Transaksi</a>
                    <a href="#" class="list-group-item list-group-item-action" data-section="cart" ><i class="fas fa-shopping-cart me-2"></i> Keranjang Belanja</a>
                    <a href="#" class="list-group-item list-group-item-action" data-section="password"><i class="fas fa-key me-2"></i> Ubah Password</a>
                    <a href="#" class="list-group-item list-group-item-action text-danger" onclick="event.preventDefault(); document.getElementById('logout-form-customer').submit();"><i class="fas fa-sign-out-alt me-2"></i> Logout</a>
                </div>
            </div>

            <div class="col-md-9">
                <div class="card profile-card" id="biodataSectionContent">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i> Detail Biodata</h5>
                    </div>
                    <div class="card-body">
                        <form id="profileForm" enctype="multipart/form-data">
                            <?php echo csrf_field(); ?>
                            
                            <div class="text-center mb-4">
                                <img id="photoPreview" src="<?php echo e($user->profile_photo_path ? asset('storage/' . $user->profile_photo_path) : asset('images/default-avatar.png')); ?>" alt="Foto Profil" class="img-thumbnail rounded-circle" style="width: 150px; height: 150px; object-fit: cover;">
                            </div>
                            <div class="mb-3">
                                <label for="photo" class="form-label">Ubah Foto Profil</label>
                                <input class="form-control" type="file" id="photo" name="photo" accept="image/*">
                                <div class="invalid-feedback"></div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="full_name" class="form-label">Nama Lengkap</label>
                                <input type="text" class="form-control" id="full_name" name="full_name" value="<?php echo e(old('full_name', $user->full_name)); ?>" required>
                                <div class="invalid-feedback"></div>
                            </div>
                            <div class="mb-3">
                                <label for="username" class="form-label">Username</label>
                                <input type="text" class="form-control" id="username" name="username" value="<?php echo e(old('username', $user->username)); ?>" required>
                                <div class="invalid-feedback"></div>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" value="<?php echo e(old('email', $user->email)); ?>" required>
                                <div class="invalid-feedback"></div>
                            </div>
                            <div class="mb-3">
                                <label for="phone" class="form-label">Telepon (Opsional)</label>
                                <input type="text" class="form-control" id="phone" name="phone" value="<?php echo e(old('phone', $user->phone)); ?>">
                                <div class="invalid-feedback"></div>
                            </div>
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary" id="updateProfileBtn">Simpan Biodata</button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="card profile-card mb-4" id="passwordSectionContent" style="display: none;">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-key me-2"></i> Ubah Password</h5>
                    </div>
                    <div class="card-body">
                        <form id="passwordForm">
                            <?php echo csrf_field(); ?>
                            <div class="mb-3">
                                <label for="current_password" class="form-label">Password Lama</label>
                                <input type="password" class="form-control" id="current_password" name="current_password" required>
                                <div class="invalid-feedback"></div>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Password Baru</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                                <div class="invalid-feedback"></div>
                                <div class="password-requirements mt-2">
                                    <small class="text-muted">Password harus mengandung:</small>
                                    <ul class="list-unstyled small">
                                        <li id="req-length">8-20 karakter</li>
                                        <li id="req-capital">1 huruf kapital (A-Z)</li>
                                        <li id="req-number">1 angka (0-9)</li>
                                    </ul>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="password_confirmation" class="form-label">Konfirmasi Password Baru</label>
                                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                                <div class="invalid-feedback"></div>
                            </div>
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary" id="updatePasswordBtn">Ubah Password</button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="card profile-card mb-4" id="transactionSectionContent" style="display: none;">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-history me-2"></i> Riwayat Transaksi</h5>
                    </div>
                    <div class="card-body">
                        <?php if($transactionHistory->isEmpty()): ?>
                            <p class="text-muted">Belum ada riwayat transaksi.</p>
                        <?php else: ?>
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>ID Transaksi</th>
                                        <th>Tanggal</th>
                                        <th>Total</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr><td>#1001</td><td>2025-06-01</td><td>Rp 150.000</td><td>Selesai</td><td><a href="#" class="btn btn-sm btn-outline-primary">Detail</a></td></tr>
                                    <tr><td>#1002</td><td>2025-06-05</td><td>Rp 220.000</td><td>Diproses</td><td><a href="#" class="btn btn-sm btn-outline-primary">Detail</a></td></tr>
                                </tbody>
                            </table>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="card profile-card" id="cartSectionContent" style="display: none;">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-shopping-cart me-2"></i> Keranjang Belanja</h5>
                    </div>
                    <div class="card-body">
                        <?php if($shoppingCart->isEmpty()): ?>
                            <p class="text-muted">Keranjang belanja Anda kosong.</p>
                        <?php else: ?>
                            <ul class="list-group">
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    Produk A - Rp 50.000 x 2
                                    <span class="badge bg-primary rounded-pill">Rp 100.000</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    Produk B - Rp 75.000 x 1
                                    <span class="badge bg-primary rounded-pill">Rp 75.000</span>
                                </li>
                            </ul>
                        <?php endif; ?>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        window.currentUserId = <?php echo e(Auth::id()); ?>;
        window.customerAccountUpdateProfileUrl = "<?php echo e(route('customer.account.update_profile')); ?>";
        window.customerAccountUpdatePasswordUrl = "<?php echo e(route('customer.account.update_password')); ?>";
        window.checkAvailabilityUrl = "<?php echo e(route('admin.check-availability')); ?>";
    </script>
    <script>
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
                alertContainer.scrollIntoView({ behavior: "smooth", block: "start" });
            }
        }
        function clearAlert() {
            const alertContainer = document.getElementById("alertContainer");
            if (alertContainer) { alertContainer.innerHTML = ''; }
        }

        window.togglePassword = function (fieldId) {
            const field = document.getElementById(fieldId);
            const toggle = field.parentNode.querySelector("button");
            const icon = toggle.querySelector("i");
            if (field.type === "password") { field.type = "text"; icon.className = "fas fa-eye-slash"; } 
            else { field.type = "password"; icon.className = "fas fa-eye"; }
        };

        function debounce(func, wait) {
            let timeout;
            return function executedFunction(...args) {
                const later = () => { clearTimeout(timeout); func(...args); };
                clearTimeout(timeout);
                timeout = setTimeout(later, wait);
            };
        }

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

        document.addEventListener("DOMContentLoaded", function () {
            
            const photoInput = document.getElementById('photo');
            const photoPreview = document.getElementById('photoPreview');

            if (photoInput) {
                photoInput.addEventListener('change', function(event) {
                    const file = event.target.files[0];
                    if (file) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            photoPreview.src = e.target.result;
                        }
                        reader.readAsDataURL(file);
                    }
                });
            }

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
            }

            navLinks.forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    showSection(this.dataset.section);
                });
            });

            const initialHash = window.location.hash.substring(1);
            if (initialHash && sections[initialHash]) {
                showSection(initialHash);
            } else {
                showSection('biodata');
            }

            const profileForm = document.getElementById("profileForm");
            const updateProfileBtn = document.getElementById("updateProfileBtn");
            const profileFields = {
                fullName: document.getElementById("full_name"),
                username: document.getElementById("username"),
                email: document.getElementById("email"),
                phone: document.getElementById("phone"),
            };
            let profileValidationState = {
                fullName: true, username: true, email: true, phone: true
            };

            function updateProfileSubmitButton() {
                updateProfileBtn.disabled = !Object.values(profileValidationState).every(Boolean);
            }

            const checkUsernameAvailability = debounce(async function(username) {
                if (!profileFields.username) return;
                if (username.length < 3) { 
                    profileValidationState.username = validateField(profileFields.username, false, "Username minimal 3 karakter"); 
                    updateProfileSubmitButton(); 
                    return; 
                }
                try {
                    const response = await fetch(window.checkAvailabilityUrl, {
                        method: "POST", headers: { "Content-Type": "application/json", "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content },
                        body: JSON.stringify({ type: "username", value: username, ignore_id: window.currentUserId }),
                    });
                    const data = await response.json();
                    profileValidationState.username = validateField(profileFields.username, data.available, "Username sudah digunakan");
                } catch (error) { 
                    console.error("Error checking username:", error); 
                    profileValidationState.username = validateField(profileFields.username, true);
                }
                updateProfileSubmitButton();
            }, 500);

            const checkEmailAvailability = debounce(async function(email) {
                if (!profileFields.email) return;
                try {
                    const response = await fetch(window.checkAvailabilityUrl, {
                        method: "POST", headers: { "Content-Type": "application/json", "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content },
                        body: JSON.stringify({ type: "email", value: email, ignore_id: window.currentUserId }),
                    });
                    const data = await response.json();
                    profileValidationState.email = validateField(profileFields.email, data.available, "Email sudah terdaftar");
                } catch (error) { 
                    console.error("Error checking email:", error); 
                    profileValidationState.email = validateField(profileFields.email, true);
                }
                updateProfileSubmitButton();
            }, 500);

            if (profileFields.fullName) profileFields.fullName.addEventListener('input', () => { 
                profileValidationState.fullName = validateField(profileFields.fullName, profileFields.fullName.value.trim().length >= 2, "Nama lengkap harus diisi (min 2 karakter)"); 
                updateProfileSubmitButton(); 
            });
            if (profileFields.username) profileFields.username.addEventListener('input', () => { 
                profileValidationState.username = validateField(profileFields.username, profileFields.username.value.trim().length >= 3 && /^[a-zA-Z0-9_]{3,20}$/.test(profileFields.username.value.trim()), "Username min 3 karakter, huruf, angka, underscore");
                if(profileValidationState.username) checkUsernameAvailability(profileFields.username.value.trim());
                updateProfileSubmitButton();
            });
            if (profileFields.email) profileFields.email.addEventListener('input', () => { 
                profileValidationState.email = validateField(profileFields.email, /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(profileFields.email.value.trim()), "Format email tidak valid"); 
                if(profileValidationState.email) checkEmailAvailability(profileFields.email.value.trim());
                updateProfileSubmitButton(); 
            });
            if (profileFields.phone) profileFields.phone.addEventListener('input', () => { 
                profileValidationState.phone = validateField(profileFields.phone, profileFields.phone.value.trim() === '' || /^(\+62|62|0)[2-9]\d{7,11}$/.test(profileFields.phone.value.trim()), "Format nomor telepon tidak valid"); 
                updateProfileSubmitButton(); 
            });

            if (profileFields.fullName) profileFields.fullName.dispatchEvent(new Event('input'));
            if (profileFields.username) profileFields.username.dispatchEvent(new Event('input'));
            if (profileFields.email) profileFields.email.dispatchEvent(new Event('input'));
            if (profileFields.phone) profileFields.phone.dispatchEvent(new Event('input'));
            updateProfileSubmitButton();

            if (profileForm) {
                profileForm.addEventListener('submit', async function(e) {
                    e.preventDefault();
                    clearAlert();
                    updateProfileBtn.disabled = true;

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
                        
                        const response = await fetch(window.customerAccountUpdateProfileUrl, {
                            method: 'POST',
                            headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'Accept': 'application/json' },
                            body: formData,
                        });

                        if (response.status === 419) { showAlert("Sesi Anda telah kedaluwarsa. Mohon refresh halaman dan coba lagi.", "danger"); setTimeout(() => { window.location.reload(); }, 3000); return; }

                        const contentType = response.headers.get("content-type");
                        if (contentType && contentType.indexOf("application/json") !== -1) {
                            const data = await response.json();
                            if (data.success) { 
                                showAlert(data.message, "success"); 
                                
                                const navbarUsername = document.querySelector('#navbarDropdownUser');
                                if (navbarUsername && profileFields.username.value) {
                                    navbarUsername.innerHTML = `<i class="fas fa-user-circle"></i> ${profileFields.username.value}`;
                                }
                                
                                if (data.newPhotoUrl) {
                                    document.getElementById('photoPreview').src = data.newPhotoUrl;
                                }

                            } else { 
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
                };

                passwordReqsElements.length.classList.toggle('valid', requirements.length); passwordReqsElements.length.classList.toggle('invalid', !requirements.length);
                passwordReqsElements.capital.classList.toggle('valid', requirements.capital); passwordReqsElements.capital.classList.toggle('invalid', !requirements.capital);
                passwordReqsElements.number.classList.toggle('valid', requirements.number); passwordReqsElements.number.classList.toggle('invalid', !requirements.number);
                passwordReqsElements.special.classList.toggle('valid', requirements.special); passwordReqsElements.special.classList.toggle('invalid', !requirements.special);

                const allRequirementsMet = Object.values(requirements).every(Boolean);
                passwordValidationState.password = validateField(passwordFields.password, allRequirementsMet, "Password baru belum memenuhi semua persyaratan");
                
                validatePasswordConfirmation();
                updatePasswordSubmitButton();
            }

            function validatePasswordConfirmation() {
                const isConfirmed = passwordFields.password.value === passwordFields.password_confirmation.value && passwordFields.password_confirmation.value.length > 0;
                passwordValidationState.password_confirmation = validateField(passwordFields.password_confirmation, isConfirmed, "Konfirmasi password tidak cocok");
                updatePasswordSubmitButton();
            }

            if (passwordFields.current_password) passwordFields.current_password.addEventListener('input', () => { passwordValidationState.current_password = validateField(passwordFields.current_password, passwordFields.current_password.value.length > 0, "Password lama harus diisi"); updatePasswordSubmitButton(); });
            if (passwordFields.password) passwordFields.password.addEventListener('input', validateNewPassword);
            if (passwordFields.password_confirmation) passwordFields.password_confirmation.addEventListener('input', validatePasswordConfirmation);


            if (passwordForm) {
                passwordForm.addEventListener('submit', async function(e) {
                    e.preventDefault();
                    clearAlert();
                    updatePasswordBtn.disabled = true;

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
                        
                        const response = await fetch(window.customerAccountUpdatePasswordUrl, {
                            method: 'POST',
                            headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
                            body: formData,
                        });

                        if (response.status === 419) { showAlert("Sesi Anda telah kedaluwarsa. Mohon refresh halaman dan coba lagi.", "danger"); setTimeout(() => { window.location.reload(); }, 3000); return; }

                        const contentType = response.headers.get("content-type");
                        if (contentType && contentType.indexOf("application/json") !== -1) {
                            const data = await response.json();
                            if (data.success) { 
                                showAlert(data.message, "success"); 
                                passwordForm.reset();
                                
                                for (const key in passwordFields) {
                                    if (passwordFields[key]) {
                                        passwordFields[key].classList.remove("is-valid", "is-invalid");
                                        if (passwordFields[key].nextElementSibling && passwordFields[key].nextElementSibling.classList.contains("invalid-feedback")) {
                                            passwordFields[key].nextElementSibling.textContent = "";
                                        }
                                    }
                                }
                                for (const key in passwordReqsElements) {
                                    if (passwordReqsElements[key]) {
                                        passwordReqsElements[key].classList.remove('valid', 'invalid');
                                    }
                                }

                                passwordValidationState = { current_password: false, password: false, password_confirmation: false };
                                updatePasswordSubmitButton();

                            } else { 
                                if (response.status === 403 || (response.status === 422 && data.message === 'Password lama tidak cocok.')) {
                                    validateField(passwordFields.current_password, false, data.message);
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

            updateProfileSubmitButton();
            updatePasswordSubmitButton();
            validateNewPassword();
        });
    </script>
</body>
</html>
<?php echo $__env->make('layouts.main', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/jerenovvidimy/Documents/MiraTaraTest/resources/views/customer/account/view.blade.php ENDPATH**/ ?>