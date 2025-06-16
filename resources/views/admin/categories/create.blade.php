// resources/views/admin/categories/create.blade.php

@extends('admin.layouts.app') {{-- SESUAIKAN DENGAN LOKASI LAYOUT UTAMA ADMIN KAMU --}}

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Tambah Kategori Baru</h1>

    <div class="card shadow mb-4">
        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.categories.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="name" class="form-label">Nama Kategori</label>
                    <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required>
                </div>
                <div class="mb-3">
                    <label for="slug" class="form-label">Slug</label>
                    <input type="text" class="form-control" id="slug" name="slug" value="{{ old('slug') }}" required>
                    <small class="form-text text-muted">Akan digunakan di URL, contoh: elektronik-terbaru</small>
                </div>
                <button type="submit" class="btn btn-primary">Simpan Kategori</button>
                <a href="{{ route('admin.categories.index_page') }}" class="btn btn-secondary">Batal</a>
            </form>
        </div>
    </div>
</div>
@endsection