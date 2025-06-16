@extends('admin.layouts.app')

@section('title', 'Dashboard - Admin MiraTara')

@section('content')
    <div class="page-header mb-4">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="h3 mb-1">Dashboard</h1>
                <p class="text-muted mb-0">
                    Selamat datang kembali di Panel Admin MiraTara.
                </p>
            </div>
            {{-- Anda bisa menambahkan tombol atau elemen lain di sini jika diperlukan --}}
        </div>
    </div>

    <div class="welcome-header mb-4">
        <h1>Selamat datang, Admin!</h1>
        <p>Ringkasan aktivitas sistem Anda.</p>
    </div>

    <div class="row">
        {{-- Card Contoh: Total User --}}
        <div class="col-md-6 col-lg-3 mb-4">
            <div class="card stats-card">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <h5 class="card-title text-muted mb-0">Total User</h5>
                        <h2 class="mb-0 mt-1">1,234</h2> {{-- Ganti dengan data dinamis nanti --}}
                    </div>
                    <div class="stats-icon bg-primary">
                        <i class="fas fa-users"></i>
                    </div>
                </div>
            </div>
        </div>

        {{-- Card Contoh: Total Produk --}}
        <div class="col-md-6 col-lg-3 mb-4">
            <div class="card stats-card">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <h5 class="card-title text-muted mb-0">Total Produk</h5>
                        <h2 class="mb-0 mt-1">567</h2> {{-- Ganti dengan data dinamis nanti --}}
                    </div>
                    <div class="stats-icon bg-success">
                        <i class="fas fa-box"></i>
                    </div>
                </div>
            </div>
        </div>

        {{-- Card Contoh: Pesanan Baru --}}
        <div class="col-md-6 col-lg-3 mb-4">
            <div class="card stats-card">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <h5 class="card-title text-muted mb-0">Pesanan Baru</h5>
                        <h2 class="mb-0 mt-1">89</h2> {{-- Ganti dengan data dinamis nanti --}}
                    </div>
                    <div class="stats-icon bg-warning">
                        <i class="fas fa-shopping-cart"></i>
                    </div>
                </div>
            </div>
        </div>

        {{-- Card Contoh: Pendapatan --}}
        <div class="col-md-6 col-lg-3 mb-4">
            <div class="card stats-card">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <h5 class="card-title text-muted mb-0">Pendapatan Hari Ini</h5>
                        <h2 class="mb-0 mt-1">Rp 1.234.567</h2> {{-- Ganti dengan data dinamis nanti --}}
                    </div>
                    <div class="stats-icon bg-info">
                        <i class="fas fa-dollar-sign"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-chart-line me-2"></i> Grafik Penjualan
                    </h5>
                </div>
                <div class="card-body">
                    <p>Grafik penjualan bulanan atau harian akan tampil di sini.</p>
                    {{-- Placeholder untuk grafik (Anda bisa menggunakan Chart.js atau sejenisnya) --}}
                    <canvas id="salesChart" style="max-height: 250px;"></canvas>
                </div>
            </div>
        </div>
        <div class="col-lg-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-bell me-2"></i> Notifikasi Terbaru
                    </h5>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item">Pesanan baru #12345 telah masuk.</li>
                        <li class="list-group-item">User baru 'Andika' telah mendaftar.</li>
                        <li class="list-group-item">Stok produk "Baju Merah" hampir habis.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-cogs me-2"></i> Quick Actions
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-flex flex-wrap gap-2">
                        <a href="{{ route('admin.users.create') }}" class="btn btn-outline-primary">
                            <i class="fas fa-user-plus me-2"></i> Tambah User Baru
                        </a>
                        <a href="{{ route('admin.products.create') }}" class="btn btn-outline-success">
                            <i class="fas fa-plus-circle me-2"></i> Tambah Produk Baru
                        </a>
                        <button class="btn btn-outline-info">
                            <i class="fas fa-file-invoice-dollar me-2"></i> Lihat Laporan
                        </button>
                        <button class="btn btn-outline-danger">
                            <i class="fas fa-cogs me-2"></i> Pengaturan
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    {{-- Anda bisa menambahkan Chart.js di sini untuk grafik --}}
    {{-- <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Contoh sederhana Chart.js
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('salesChart');
            if (ctx) {
                new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun'],
                        datasets: [{
                            label: 'Penjualan Bulanan',
                            data: [12, 19, 3, 5, 2, 3],
                            backgroundColor: 'rgba(75, 192, 192, 0.6)',
                            borderColor: 'rgba(75, 192, 192, 1)',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });
            }
        });
    </script> --}}
@endpush
