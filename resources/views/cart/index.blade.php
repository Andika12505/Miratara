@extends('layouts.main')

@section('title', 'Keranjang Belanja')

@section('content')
<div class="container" style="margin-top: 100px; margin-bottom: 50px;">
    <h2 class="mb-4">Keranjang Belanja Anda</h2>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(Cart::count() > 0)
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Produk</th>
                    <th>Harga</th>
                    <th width="120px">Kuantitas</th>
                    <th>Subtotal</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($cartItems as $item)
                <tr>
                    <td>
                        <div class="d-flex align-items-center">
                            {{-- Mengambil gambar dari options --}}
                            <img src="{{ asset('storage/' . $item->options->image) }}" width="60" class="me-3" alt="{{ $item->name }}">
                            <span>{{ $item->name }}</span>
                        </div>
                    </td>
                    <td>Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                    <td>
                        {{-- Form untuk Update, menggunakan $item->rowId --}}
                        <form action="{{ route('cart.update', $item->rowId) }}" method="POST">
                            @csrf
                            <input type="number" name="quantity" value="{{ $item->qty }}" min="1" class="form-control form-control-sm" onchange="this.form.submit()">
                        </form>
                    </td>
                    <td>Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                    <td>
                        {{-- Form untuk Remove, menggunakan $item->rowId --}}
                        <form action="{{ route('cart.remove', $item->rowId) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="row justify-content-end">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Total Belanja</h5>
                        {{-- Menggunakan Cart::total() untuk mendapat total harga --}}
                        <h3 class="fw-bold">Rp {{ Cart::total(0, ',', '.') }}</h3>
                        <div class="d-grid gap-2 mt-3">
                            <a href="{{ route('checkout_page') }}" class="btn btn-primary">Lanjut ke Checkout</a>
                            <form action="{{ route('cart.clear') }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-outline-danger w-100">Kosongkan Keranjang</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @else
        <p class="text-muted">Keranjang belanja Anda masih kosong.</p>
    @endif
</div>
@endsection