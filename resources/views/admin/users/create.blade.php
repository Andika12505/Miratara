@extends('admin.layouts.app')

@section('title', 'Tambah User - Admin MiraTara')

@section('content')
    <div class="page-header mb-4">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="h3 mb-1">Tambah User Baru</h1>
                <p class="text-muted mb-0">
                    Buat akun user baru untuk MiraTara
                </p>
            </div>
            <div>
                <a href="{{ route('admin.users.index_page') }}" class="btn btn-outline-secondary">
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
                        <i class="fas fa-user-plus me-2"></i>
                        Informasi User Baru
                    </h5>
                </div>
                <div class="card-body">
                    <form id="addUserForm" action="#" method="POST" novalidate>
                        @csrf

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="fullName" class="form-label">
                                    Nama Lengkap <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-user"></i>
                                    </span>
                                    <input type="text" class="form-control" id="fullName" name="full_name" required />
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
                                    <input type="text" class="form-control" id="username" name="username" required />
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
                                    <input type="email" class="form-control" id="email" name="email" required />
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
                                    <input type="tel" class="form-control" id="phone" name="phone" placeholder="08123456789" />
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="password" class="form-label">
                                    Password <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-lock"></i>
                                    </span>
                                    <input type="password" class="form-control" id="password" name="password" required />
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
                        </div>

                        <div class="d-flex justify-content-between">
                            <div>
                                <button type="button" class="btn btn-secondary" onclick="resetForm()">
                                    <i class="fas fa-undo me-2"></i> Reset Form
                                </button>
                            </div>
                            <div>
                                <a href="{{ route('admin.users.index_page') }}" class="btn btn-outline-secondary me-2">
                                    <i class="fas fa-times me-2"></i> Batal
                                </a>
                                <button type="submit" class="btn btn-primary" id="submitBtn">
                                    <span class="btn-text">
                                        <i class="fas fa-user-plus me-2"></i> Tambah User
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

            <div class="card mt-4">
                <div class="card-body">
                    <h6 class="card-title">
                        <i class="fas fa-info-circle text-info me-2"></i>
                        Informasi Tambahan
                    </h6>
                    <div class="row">
                        <div class="col-md-6">
                            <ul class="list-unstyled small text-muted">
                                <li>
                                    • Field bertanda
                                    <span class="text-danger">*</span> wajib diisi
                                </li>
                                <li>• Username harus unik (tidak boleh sama)</li>
                                <li>• Email harus unik dan valid</li>
                                <li>• Nomor telepon bersifat opsional</li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <ul class="list-unstyled small text-muted">
                                <li>• Password akan di-hash untuk keamanan</li>
                                <li>• User dapat login langsung setelah dibuat</li>
                                <li>• Data dapat diubah setelah user dibuat</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endsection

    @push('scripts')
        <script src="{{ asset('js/admin/admin_add_user.js') }}"></script>
    @endpush