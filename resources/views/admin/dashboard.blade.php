@extends('admin.layouts.app')

@section('title', 'Dashboard - Admin MiraTara')

@section('content')
    {{-- BAGIAN DASHBOARD UTAMA (DARI KODE PERTAMA) --}}
    <div class="page-header mb-4">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="h3 mb-1">Dashboard</h1>
                <p class="text-muted mb-0">
                    Selamat datang kembali di Panel Admin MiraTara.
                </p>
            </div>
        </div>
    </div>

    <div class="row">
        {{-- Card Contoh: Total User --}}
        <div class="col-md-6 col-lg-3 mb-4">
            <div class="card stats-card">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <h5 class="card-title text-muted mb-0">Total User</h5>
                        {{-- Ganti dengan data dinamis nanti --}}
                        <h2 class="mb-0 mt-1">{{ $totalUsers ?? '1,234' }}</h2>
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
                         {{-- Ganti dengan data dinamis nanti --}}
                        <h2 class="mb-0 mt-1">{{ $totalProducts ?? '567' }}</h2>
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
                         {{-- Ganti dengan data dinamis nanti --}}
                        <h2 class="mb-0 mt-1">{{ $newOrders ?? '89' }}</h2>
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
                         {{-- Ganti dengan data dinamis nanti --}}
                        <h2 class="mb-0 mt-1">Rp {{ number_format($todaysRevenue ?? 1234567, 0, ',', '.') }}</h2>
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
                     <p class="text-muted small">Grafik penjualan bulanan atau harian akan tampil di sini.</p>
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
                        {{-- Data Notifikasi dinamis dari controller --}}
                        <li class="list-group-item">Pesanan baru #12345 telah masuk.</li>
                        <li class="list-group-item">User baru 'Andika' telah mendaftar.</li>
                        <li class="list-group-item">Stok produk "Baju Merah" hampir habis.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <hr class="my-5">

    {{-- BAGIAN MANAJEMEN STOK (DARI KODE KEDUA) --}}
    <div class="page-header mb-4">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="h3 mb-1">Ringkasan Manajemen Stok</h1>
                <p class="text-muted mb-0">Monitor dan kelola inventaris Anda secara real-time.</p>
            </div>
            <div class="col-auto">
                <button class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i> Update Stok Cepat
                </button>
            </div>
        </div>
    </div>
    
    {{-- Kartu Status Stok --}}
    <div class="row">
        <div class="col-md-6 col-lg-3 mb-4">
            <div class="card stats-card">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <h5 class="card-title text-muted mb-0">Nilai Stok</h5>
                        <h2 class="mb-0 mt-1">Rp {{ number_format($stockSummary['total_stock_value'] ?? 0, 0, ',', '.') }}</h2>
                    </div>
                    <div class="stats-icon" style="background-color: #6f42c1;">
                        <i class="fas fa-dollar-sign"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-3 mb-4">
            <div class="card stats-card">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <h5 class="card-title text-muted mb-0">Stok Menipis</h5>
                        <h2 class="mb-0 mt-1">{{ $stockSummary['low_stock_count'] ?? 0 }}</h2>
                    </div>
                    <div class="stats-icon bg-warning">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-3 mb-4">
            <div class="card stats-card">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <h5 class="card-title text-muted mb-0">Stok Habis</h5>
                        <h2 class="mb-0 mt-1">{{ $stockSummary['out_of_stock_count'] ?? 0 }}</h2>
                    </div>
                    <div class="stats-icon bg-danger">
                        <i class="fas fa-times-circle"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Grafik Stok & Pergerakan Stok --}}
    <div class="row">
        <div class="col-lg-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-chart-pie me-2"></i>Distribusi Status Stok</h5>
                </div>
                <div class="card-body">
                    <div style="position: relative; height: 300px;">
                        <canvas id="stockStatusChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-chart-line me-2"></i>Pergerakan Stok Bulanan</h5>
                </div>
                <div class="card-body">
                    <div style="position: relative; height: 300px;">
                        <canvas id="monthlyMovementsChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Tabel Produk yang Perlu Perhatian --}}
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-box-open me-2"></i>Produk yang Perlu Perhatian</h5>
                    <a href="/admin/stock" class="btn btn-sm btn-outline-primary">Kelola Semua</a>
                </div>
                <div class="card-body">
                    @if(isset($lowStockProducts) && $lowStockProducts->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Produk</th>
                                        <th>Kategori</th>
                                        <th>Stok Saat Ini</th>
                                        <th>Min. Stok</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($lowStockProducts->take(10) as $product)
                                    <tr>
                                        <td>
                                            <h6 class="mb-0">{{ $product->name }}</h6>
                                            <small class="text-muted">{{ $product->sku ?? 'No SKU' }}</small>
                                        </td>
                                        <td>{{ $product->category->name ?? 'No Category' }}</td>
                                        <td>
                                            <span class="badge bg-{{ $product->stock == 0 ? 'danger' : ($product->stock <= $product->min_stock ? 'warning' : 'success') }}">
                                                {{ $product->stock }}
                                            </span>
                                        </td>
                                        <td>{{ $product->min_stock }}</td>
                                        <td>
                                            <span class="badge bg-{{ $product->stock_status == 'out_of_stock' ? 'danger' : ($product->stock_status == 'low_stock' ? 'warning' : 'success') }}">
                                                {{ ucfirst(str_replace('_', ' ', $product->stock_status)) }}
                                            </span>
                                        </td>
                                        <td>
                                            <a href="/admin/stock/product/{{ $product->id }}" class="btn btn-sm btn-outline-secondary">
                                                <i class="fas fa-cog"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-check-circle text-success fa-3x mb-3"></i>
                            <h5 class="text-muted">Semua stok produk aman!</h5>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    {{-- Pastikan Chart.js sudah dimuat di layout utama Anda atau gunakan CDN ini --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // --- SCRIPT UNTUK GRAFIK DARI KODE UTAMA ---
            const salesCtx = document.getElementById('salesChart');
            if (salesCtx) {
                new Chart(salesCtx, {
                    type: 'bar',
                    data: {
                        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun'],
                        datasets: [{
                            label: 'Penjualan Bulanan',
                            data: [12, 19, 3, 5, 2, 3], // Ganti dengan data dinamis
                            backgroundColor: 'rgba(75, 192, 192, 0.6)',
                            borderColor: 'rgba(75, 192, 192, 1)',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        scales: { y: { beginAtZero: true } }
                    }
                });
            }

            // --- SCRIPT UNTUK GRAFIK DARI KODE KEDUA (MANAJEMEN STOK) ---
            
            // 1. Grafik Distribusi Status Stok (Doughnut)
            const stockStatusCtx = document.getElementById('stockStatusChart');
            if(stockStatusCtx) {
                new Chart(stockStatusCtx, {
                    type: 'doughnut',
                    data: {
                        labels: ['Stok Aman', 'Stok Menipis', 'Stok Habis'],
                        datasets: [{
                            data: [
                                {{ $stockStatusDistribution['in_stock'] ?? 80 }},
                                {{ $stockStatusDistribution['low_stock'] ?? 15 }},
                                {{ $stockStatusDistribution['out_of_stock'] ?? 5 }}
                            ],
                            backgroundColor: ['#28a745', '#ffc107', '#dc3545'],
                            borderWidth: 0
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { position: 'bottom' } }
                    }
                });
            }

            // 2. Grafik Pergerakan Stok Bulanan (Line)
            const monthlyCtx = document.getElementById('monthlyMovementsChart');
            if(monthlyCtx) {
                new Chart(monthlyCtx, {
                    type: 'line',
                    data: {
                        labels: [
                            @if(isset($monthlyMovements))
                                @foreach($monthlyMovements as $movement) '{{ $movement->month }}', @endforeach
                            @else
                                'Jan', 'Feb', 'Mar', 'Apr' // Data contoh jika tidak ada
                            @endif
                        ],
                        datasets: [{
                            label: 'Stok Masuk',
                            data: [
                                @if(isset($monthlyMovements))
                                    @foreach($monthlyMovements as $movement) {{ $movement->stock_in }}, @endforeach
                                @else
                                    50, 60, 45, 70 // Data contoh jika tidak ada
                                @endif
                            ],
                            borderColor: '#28a745',
                            backgroundColor: 'rgba(40, 167, 69, 0.1)',
                            tension: 0.4,
                            fill: true,
                        }, {
                            label: 'Stok Keluar',
                            data: [
                                @if(isset($monthlyMovements))
                                    @foreach($monthlyMovements as $movement) {{ $movement->stock_out }}, @endforeach
                                @else
                                    30, 40, 25, 50 // Data contoh jika tidak ada
                                @endif
                            ],
                            borderColor: '#dc3545',
                            backgroundColor: 'rgba(220, 53, 69, 0.1)',
                            tension: 0.4,
                            fill: true,
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { position: 'top' } },
                        scales: { y: { beginAtZero: true } }
                    }
                });
            }
        });
    </script>
@endpush