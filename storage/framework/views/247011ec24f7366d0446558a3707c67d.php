<!DOCTYPE html>
<html lang="id">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?php echo $__env->yieldContent('title', 'Admin MiraTara'); ?></title>

    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

    <link rel="stylesheet" href="<?php echo e(asset('css/bootstrap/bootstrap.min.css')); ?>" />
    <link
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
      rel="stylesheet"
    />
    <link rel="stylesheet" href="<?php echo e(asset('themify-icons/themify-icons.css')); ?>" />
    <link rel="stylesheet" href="<?php echo e(asset('css/nav_carousel_style.css')); ?>" />
    <link href="<?php echo e(asset('css/admin/admin.css')); ?>" rel="stylesheet" />

    <?php echo $__env->yieldPushContent('styles'); ?>
  </head>
  <body>
    <script src="<?php echo e(asset('js/bootstrap/bootstrap.bundle.min.js')); ?>"></script>

    <nav class="navbar navbar-expand-lg navbar-light bg-white fixed-top">
      <div class="container-fluid">
        <div
          class="navbar-brand-wrapper d-flex justify-content-center flex-grow-1"
        >
          <a
            class="navbar-brand d-flex align-items-center"
            href="<?php echo e(route('admin.dashboard')); ?>"
          >
            <img src="<?php echo e(asset('images/logo1.png')); ?>" alt="Logo" height="40" class="me-2" />
            <span class="admin-title">Admin Panel</span>
          </a>
        </div>

<?php if(auth()->guard()->check()): ?> 
<div class="nav-item dropdown">
    <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="navbarDropdownUser" role="button" data-bs-toggle="dropdown" aria-expanded="false">
        <?php if(Auth::user()->profile_photo_path): ?>
            <img src="<?php echo e(asset('storage/' . Auth::user()->profile_photo_path)); ?>" alt="Foto" class="rounded-circle me-2" style="width: 25px; height: 25px; object-fit: cover;">
        <?php else: ?>
            <i class="fas fa-user-circle fa-lg me-2"></i>
        <?php endif; ?>
        <?php echo e(Auth::user()->username); ?>

    </a>
    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdownUser">
        <li><a class="dropdown-item" href="<?php echo e(route('customer.account.view')); ?>">Lihat Akun</a></li>
        <li><hr class="dropdown-divider"></li>
        <li>
            <form id="logout-form-customer" action="<?php echo e(route('logout')); ?>" method="POST" style="display: none;">
                <?php echo csrf_field(); ?>
            </form>
            <a class="dropdown-item" href="#" onclick="event.preventDefault(); document.getElementById('logout-form-customer').submit();">Logout</a>
        </li>
    </ul>
</div>
<?php else: ?> 
<a href="<?php echo e(route('login_page')); ?>" class="nav-login-button me-2">Login</a>
<a href="<?php echo e(route('register_page')); ?>" class="nav-login-button btn-primary">Register</a>
<?php endif; ?>

    <div class="container-fluid mt-5 pt-3">
      <div class="row">
        <div class="col-md-3 col-lg-2 sidebar">
          <div class="sidebar-content">
            <h5 class="sidebar-title">Menu Admin</h5>

            <ul class="nav flex-column">
              <li class="nav-item">
                <a class="nav-link <?php if(Request::routeIs('admin.users.*')): ?> active <?php endif; ?>" href="<?php echo e(route('admin.users.index')); ?>">
                  <i class="fas fa-users me-2"></i>
                  Kelola User
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link <?php if(Request::routeIs('admin.users.*')): ?> active <?php endif; ?>" href="<?php echo e(route('admin.users.index')); ?>">
                  <i class="fas fa-users me-2"></i>
                  Kelola User
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link <?php if(Request::routeIs('admin.products.*')): ?> active <?php endif; ?>" href="<?php echo e(route('admin.products.index')); ?>">
                <i class="fas fa-box-open me-2"></i> Kelola Produk
                </a>
              </li>
              <li><hr class="dropdown-divider"></li>
              <li class="nav-item">
                <a class="nav-link" href="<?php echo e(route('homepage')); ?>">
                  <i class="fas fa-globe me-2"></i>
                  Lihat Website
                </a>
              </li>
            </ul>
          </div>
        </div>

        <div class="col-md-9 col-lg-10 main-content">
          <?php echo $__env->yieldContent('content'); ?>
        </div>
      </div>
    </div>

    <?php echo $__env->yieldPushContent('scripts'); ?>
  </body>
</html><?php /**PATH /Users/jerenovvidimy/Documents/MiraTaraTest/resources/views/layouts/app.blade.php ENDPATH**/ ?>