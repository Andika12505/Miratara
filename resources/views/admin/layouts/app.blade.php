<!DOCTYPE html>
<html lang="id">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>@yield('title', 'Admin MiraTara')</title>

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="stylesheet" href="{{ asset('css/bootstrap/bootstrap.min.css') }}" />
    <link
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
      rel="stylesheet"
    />
    <link rel="stylesheet" href="{{ asset('themify-icons/themify-icons.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/nav_carousel_style.css') }}" />
    <link href="{{ asset('css/admin/admin.css') }}" rel="stylesheet" />

    @stack('styles')
  </head>
  <body>
    <script src="{{ asset('js/bootstrap/bootstrap.bundle.min.js') }}"></script>

    <nav class="navbar navbar-expand-lg navbar-light bg-white fixed-top">
      <div class="container-fluid">
        <div
          class="navbar-brand-wrapper d-flex justify-content-center flex-grow-1"
        >
          <a
            class="navbar-brand d-flex align-items-center"
            href="{{ route('admin.dashboard') }}"
          >
            <img src="{{ asset('images/logo1.png') }}" alt="Logo" height="40" class="me-2" />
            <span class="admin-title">Admin Panel</span>
          </a>
        </div>

        <div class="navbar-nav position-absolute end-0 me-3">
          <div class="nav-item dropdown">
            <a
              class="nav-link dropdown-toggle admin-dropdown"
              href="#"
              role="button"
              data-bs-toggle="dropdown"
            >
              <i class="fas fa-user-shield me-1"></i>
              Admin
            </a>
            <ul class="dropdown-menu dropdown-menu-end">
              <li>
                <a class="dropdown-item" href="{{ route('homepage') }}">
                  <i class="fas fa-home me-2"></i>Lihat Website
                </a>
              </li>
              <li><hr class="dropdown-divider" /></li>
              <li>
                <a class="dropdown-item" href="#" onclick="event.preventDefault(); document.getElementById('admin-logout-form').submit();">
                  <i class="fas fa-sign-out-alt me-2"></i>Logout
                </a>
                <form id="admin-logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </nav>

    <div class="container-fluid mt-5 pt-3">
      <div class="row">
        <div class="col-md-3 col-lg-2 sidebar">
          <div class="sidebar-content">
            <h5 class="sidebar-title">Menu Admin</h5>

            <ul class="nav flex-column">
              <li class="nav-item">
                <a class="nav-link @if(Request::routeIs('admin.dashboard')) active @endif" href="{{ route('admin.dashboard') }}">
                  <i class="fas fa-tachometer-alt me-2"></i>
                  Dashboard
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link @if(Request::routeIs('admin.users.*')) active @endif" href="{{ route('admin.users.index_page') }}">
                  <i class="fas fa-users me-2"></i>
                  Kelola User
                </a>
              </li>
              <li class="nav-item @if(Request::routeIs('admin.categories.*')) active @endif">
                <a class="nav-link" href="{{ route('admin.categories.index_page') }}">
                  <i class="fas fa-tags me-2"></i> {{-- Menggunakan ikon tag --}}
                  Kelola Kategori
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link @if(Request::routeIs('admin.products.*')) active @endif" href="{{ route('admin.products.index_page') }}">
                  <i class="fas fa-tshirt me-2"></i>
                  Kelola Produk
                </a>
              </li>
              <li><hr class="dropdown-divider"></li>
              <li class="nav-item">
                <a class="nav-link" href="{{ route('homepage') }}">
                  <i class="fas fa-globe me-2"></i>
                  Lihat Website
                </a>
              </li>
            </ul>
          </div>
        </div>

        <div class="col-md-9 col-lg-10 main-content">
          @yield('content')
        </div>
      </div>
    </div>

    @stack('scripts')
  </body>
</html>