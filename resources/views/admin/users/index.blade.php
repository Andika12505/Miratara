@extends('admin.layouts.app')

@section('title', 'Kelola User - Admin MiraTara')

@section('content')
    <div class="page-header mb-4">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="h3 mb-1">Kelola User</h1>
                <p class="text-muted mb-0">
                    Lihat dan kelola semua user terdaftar
                </p>
            </div>
            <div>
                <a href="{{ route('admin.users.create_page') }}" class="btn btn-primary">
                    <i class="fas fa-user-plus me-2"></i>
                    Tambah User Baru
                </a>
            </div>
        </div>
    </div>

    <div id="alertContainer" class="mt-3"></div>

    <div class="card mb-4">
        <div class="card-body">
            <div class="row">
                <div class="col-md-8">
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-search"></i></span>
                        <input type="text" class="form-control" placeholder="Cari berdasarkan nama, username, atau email..." id="searchInput">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">
                    <i class="fas fa-users me-2"></i>
                    Daftar User
                </h5>
                <span class="badge bg-primary" id="totalBadge">Total: 0 user</span>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Nama Lengkap</th>
                            <th scope="col">Username</th>
                            <th scope="col">Email</th>
                            <th scope="col">Telepon</th>
                            <th scope="col">Terdaftar</th>
                            <th scope="col">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="tableBody">
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
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card-footer">
            <div class="d-flex justify-content-between align-items-center">
                <small class="text-muted" id="paginationInfo">Menampilkan 0 dari 0 data</small>
                <nav>
                    <ul class="pagination pagination-sm mb-0" id="paginationNav">
                        </ul>
                </nav>
            </div>
        </div>
    </div>
    
    <div class="modal fade" id="deleteModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-exclamation-triangle text-warning me-2"></i> Konfirmasi Hapus</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin menghapus user <strong id="userToDelete"></strong>?</p>
                    <div class="alert alert-warning d-flex align-items-center">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <div><strong>Peringatan:</strong> Tindakan ini tidak dapat dibatalkan. Semua data user akan dihapus secara permanen.</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="fas fa-times me-2"></i> Batal</button>
                    <button type="button" class="btn btn-danger" id="confirmDelete"><i class="fas fa-trash me-2"></i> Ya, Hapus User</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('js/admin/admin_users.js') }}"></script>
@endpush