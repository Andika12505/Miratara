<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?php echo $__env->yieldContent('title', 'MiraTara Fashion'); ?></title>

    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

    <link rel="stylesheet" href="<?php echo e(asset('css/bootstrap/bootstrap.min.css')); ?>" />
    <link rel="stylesheet" href="<?php echo e(asset('themify-icons/themify-icons.css')); ?>" />
    <link rel="stylesheet" href="<?php echo e(asset('css/nav_carousel_style.css')); ?>" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    
    <?php echo $__env->yieldPushContent('styles'); ?>
    <style>
        /* Custom styles for account icon and spacing in navbar */
        /* Anda bisa memindahkan style ini ke file CSS eksternal jika diinginkan */
        .navbar .nav-login-button {
            border: 1px solid #ffc0cb; /* MiraTara pink border */
            border-radius: 5px;
            padding: 8px 15px;
            text-decoration: none;
            color: #ffc0cb;
            transition: all 0.3s ease;
            white-space: nowrap; /* Prevent wrapping */
        }
        .navbar .nav-login-button:hover {
            background-color: #ffc0cb;
            color: white;
        }
        .navbar .nav-login-button.btn-primary { /* For Register button */
            background-color: #ffc0cb;
            color: white;
        }
        .navbar .nav-login-button.btn-primary:hover {
            background-color: #ff8fab;
        }
        .navbar-nav .nav-item.dropdown .nav-link {
            display: flex;
            align-items: center;
        }
        .navbar-nav .nav-item.dropdown .nav-link i {
            margin-right: 5px;
        }
        /* Penyesuaian umum untuk navbar agar ikon dan tombol berada di paling kanan */
        .navbar-collapse {
            justify-content: flex-end !important; /* Dorong item ke kanan */
        }
        .navbar-nav {
            margin-left: auto !important; /* Pastikan menu utama didorong ke kiri */
        }
        .d-flex.align-items-center {
            margin-left: 1rem; /* Spasi antara menu utama dan tombol login/akun */
        }
        .navbar-toggler {
            margin-left: 5px;
            order: 2; /* Agartoggler tetap di kanan setelah tombol/ikon akun */
        }
        .navbar-brand {
            margin-right: auto; /* Untuk logo tetap di kiri */
        }
    </style>
</head>

<body>
    <script src="<?php echo e(asset('js/bootstrap/bootstrap.bundle.min.js')); ?>"></script>

    <section id="header">
        <nav class="navbar navbar-expand-lg fixed-top bg-white">
            <div class="container-fluid">
                <a class="navbar-brand me-auto" href="<?php echo e(route('homepage')); ?>">
                    <img src="<?php echo e(asset('images/logo1.png')); ?>" alt="Logo" height="40" />
                </a>

                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse justify-content-center" id="navbarSupportedContent">
                    <ul class="navbar-nav justify-content-center mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a class="nav-link mx-lg-2 <?php if(Request::routeIs('homepage')): ?> active <?php endif; ?>" aria-current="page" href="<?php echo e(route('homepage')); ?>">Home</a>
                        </li>

                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle mx-lg-2" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Women's
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="#">Dresses</a></li>
                                <li><a class="dropdown-item" href="#">Skirt</a></li>
                                <li><a class="dropdown-item" href="#">Sweaters</a></li>
                                <li><a class="dropdown-item" href="#">Jackets</a></li>
                            </ul>
                        </li>

                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle mx-lg-2" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Accessories
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="#">Jewelry</a></li>
                                <li><a class="dropdown-item" href="#">Shoes</a></li>
                                <li><a class="dropdown-item" href="#">Bags</a></li>
                            </ul>
                        </li>
                    </ul>

                    
                    <div class="d-flex align-items-center ms-auto"> 
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
</div
                        <?php else: ?> 
                        <a href="<?php echo e(route('login_page')); ?>" class="nav-login-button me-2">Login</a>
                        <a href="<?php echo e(route('register_page')); ?>" class="nav-login-button btn-primary">Register</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </nav>
    </section>

    <?php echo $__env->yieldContent('content'); ?>

    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html><?php /**PATH /Users/andika/Documents/Miratara/resources/views/layouts/main.blade.php ENDPATH**/ ?>