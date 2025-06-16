@extends('admin.layouts.app')

@section('title', 'Edit User - Admin MiraTara')

@section('content')
    <div class="page-header mb-4">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="h3 mb-1">Edit User</h1>
                <p class="text-muted mb-0">
                    Perbarui informasi user {{ $user->full_name }}.
                </p>
            </div>
            <div>
                <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>
                    Kembali ke Daftar User
                </a>
            </div>
        </div>
    </div>

    <div id="alertContainer" class="mt-3"></div>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-user-edit me-2"></i>
                        Informasi User
                    </h5>
                </div>
                <div class="card-body">
                    {{-- action akan menunjuk ke route update, dengan method PUT/PATCH --}}
                    <form id="editUserForm" action="{{ route('admin.users.update', $user->id) }}" method="POST" novalidate>
                        @csrf
                        @method('PUT') {{-- Penting untuk method PUT/PATCH --}}

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="fullName" class="form-label">
                                    Nama Lengkap <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-user"></i>
                                    </span>
                                    <input type="text" class="form-control" id="fullName" name="full_name" value="{{ old('full_name', $user->full_name) }}" required />
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label for="username" class="form-label">
                                    Username <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-at"></i>
                                    </span>
                                    <input type="text" class="form-control" id="username" name="username" value="{{ old('username', $user->username) }}" required />
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="email" class="form-label">
                                    Email <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-envelope"></i>
                                    </span>
                                    <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $user->email) }}" required />
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label for="phone" class="form-label">
                                    Nomor Telepon
                                    <span class="text-muted">(Opsional)</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-phone"></i>
                                    </span>
                                    <input type="tel" class="form-control" id="phone" name="phone" value="{{ old('phone', $user->phone) }}" placeholder="08123456789" />
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="password" class="form-label">
                                    Password (Kosongkan jika tidak ingin diubah)
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-lock"></i>
                                    </span>
                                    <input type="password" class="form-control" id="password" name="password" />
                                    <button type="button" class="btn btn-outline-secondary" onclick="togglePassword('password')">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <div class="invalid-feedback"></div>
                                </div>

                                <div class="password-requirements mt-2">
                                    <small class="text-muted">Password harus mengandung:</small>
                                    <ul class="requirements-list">
                                        <li id="req-length" class="req-item">8-20 karakter</li>
                                        <li id="req-capital" class="req-item">1 huruf kapital (A-Z)</li>
                                        <li id="req-number" class="req-item">1 angka (0-9)</li>
                                        <li id="req-special" class="req-item">1 karakter special (@!$#%^&lt;&gt;?_-)</li>
                                    </ul>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="is_admin" class="form-label">Status</label>
                                <div class="form-check form-switch mt-2">
                                    <input class="form-check-input" type="checkbox" id="is_admin" name="is_admin" value="1" {{ old('is_admin', $user->is_admin) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_admin">Jadikan Admin</label>
                                </div>
                                <small class="text-muted">Centang untuk memberikan hak akses admin.</small>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <div>
                                <button type="button" class="btn btn-secondary" onclick="resetForm()">
                                    <i class="fas fa-undo me-2"></i> Reset Form
                                </button>
                            </div>
                            <div>
                                <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-arrow-left me-2"></i>
                                    Kembali ke Daftar User
                                </a>
                                <button type="submit" class="btn btn-primary" id="submitBtn">
                                    <span class="btn-text">
                                        <i class="fas fa-save me-2"></i> Simpan Perubahan
                                    </span>
                                    <span class="btn-loader d-none">
                                        <i class="fas fa-spinner fa-spin me-2"></i> Menyimpan...
                                    </span>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    {{-- Ini akan menjadi JavaScript khusus untuk halaman edit user --}}
    <script>
        // Variabel JS global untuk URL redirect setelah sukses
        window.adminUserIndexUrl = "{{ route('admin.users.index') }}";
        // Variabel JS global untuk ID user yang sedang diedit (untuk checkAvailability)
        window.currentUserId = {{ $user->id }};
    </script>
    <script src="{{ asset('js/admin/admin_edit_user.js') }}"></script>
    {{-- Anda juga bisa menggunakan admin_add_user.js jika logikanya sama persis dan bisa di-re-use --}}
@endpush
