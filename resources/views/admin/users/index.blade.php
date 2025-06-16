@extends('admin.layouts.app')

@section('title', 'Kelola User - Admin MiraTara')

@section('content')
    <div class="page-header mb-4">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="h3 mb-1">Kelola User</h1>
                <p class="text-muted mb-0">
                    Lihat, tambah, edit, dan hapus user yang terdaftar di sistem.
                </p>
            </div>
            <div>
                <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
                    <i class="fas fa-user-plus me-2"></i> Tambah User Baru
                </a>
            </div>
        </div>
    </div>

    <div id="alertContainer" class="mt-3"></div>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">
                <i class="fas fa-table me-2"></i> Daftar User
            </h5>
            <div class="input-group" style="max-width: 300px;">
                <input type="text" id="searchInput" class="form-control" placeholder="Cari user...">
                <button class="btn btn-outline-secondary" type="button" id="searchButton">
                    <i class="fas fa-search"></i>
                </button>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover table-striped" id="usersTable">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nama Lengkap</th>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Telepon</th>
                            <th>Status</th>
                            <th>Terdaftar Sejak</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="usersTableBody">
                        {{-- Data user akan dimuat di sini oleh JavaScript --}}
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-between align-items-center mt-3">
                <div id="paginationInfo"></div>
                <nav id="paginationLinks">
                    <ul class="pagination mb-0">
                        {{-- Link paginasi akan dimuat di sini oleh JavaScript --}}
                    </ul>
                </nav>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    {{-- Variabel JS global untuk URL data user --}}
    <script>
        window.usersApiUrl = "{{ route('admin.users.data') }}";
        window.editUserRoute = "{{ route('admin.users.edit', ':id') }}"; // Placeholder untuk ID
        window.deleteUserRoute = "{{ route('admin.users.destroy', ':id') }}"; // Placeholder untuk ID
    </script>
    <script src="{{ asset('js/admin/admin_users.js') }}"></script>
    {{-- Jika Anda memiliki JS lain untuk halaman ini, tambahkan di sini --}}
@endpush
