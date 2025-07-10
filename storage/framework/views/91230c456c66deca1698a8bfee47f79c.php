
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    
    <title><?php echo $__env->yieldContent('title', 'Admin MiraTara'); ?></title>
    
    
    <link rel="stylesheet" href="<?php echo e(asset('css/bootstrap/bootstrap.min.css')); ?>">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo e(asset('themify-icons/themify-icons.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('css/admin/admin.css')); ?>">
    
    
    <style>
        body {
            background-color: #f8f9fa;
        }
        
        .admin-navbar {
            background-color: #fff !important;
            border-bottom: 1px solid #e9ecef;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            z-index: 1030;
        }
        
        .admin-sidebar {
            background-color: #343a40;
            min-height: calc(100vh - 56px);
            position: fixed;
            top: 56px;
            left: 0;
            width: 250px;
            z-index: 1020;
            transition: all 0.3s;
        }
        
        .admin-sidebar.collapsed {
            margin-left: -250px;
        }
        
        .admin-main-content {
            margin-left: 250px;
            padding: 20px;
            min-height: calc(100vh - 56px);
            transition: all 0.3s;
        }
        
        .admin-main-content.expanded {
            margin-left: 0;
        }
        
        .sidebar-brand {
            padding: 1rem;
            border-bottom: 1px solid #495057;
            color: #fff;
            text-decoration: none;
        }
        
        .sidebar-brand:hover {
            color: #fff;
            text-decoration: none;
        }
        
        .sidebar-nav {
            padding: 0;
            margin: 0;
            list-style: none;
        }
        
        .sidebar-nav .nav-item {
            border-bottom: 1px solid #495057;
        }
        
        .sidebar-nav .nav-link {
            display: block;
            padding: 0.75rem 1rem;
            color: #adb5bd;
            text-decoration: none;
            transition: all 0.3s;
        }
        
        .sidebar-nav .nav-link:hover,
        .sidebar-nav .nav-link.active {
            background-color: #495057;
            color: #fff;
        }
        
        .sidebar-nav .nav-link i {
            margin-right: 0.5rem;
            width: 16px;
            text-align: center;
        }
        
        .admin-dropdown-toggle {
            background: none;
            border: none;
            color: #6c757d;
            font-size: 1.1rem;
        }
        
        .admin-dropdown-toggle:hover {
            color: #343a40;
        }
        
        .content-header {
            background-color: #fff;
            padding: 1rem 1.5rem;
            margin-bottom: 1rem;
            border-radius: 0.375rem;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        }
        
        .content-header h1 {
            margin: 0;
            font-size: 1.5rem;
            color: #343a40;
        }
        
        .breadcrumb-nav {
            background: none;
            padding: 0;
            margin: 0;
        }
        
        .breadcrumb-nav .breadcrumb-item {
            font-size: 0.875rem;
            color: #6c757d;
        }
        
        .breadcrumb-nav .breadcrumb-item.active {
            color: #343a40;
        }
        
        .sidebar-toggle {
            background: none;
            border: none;
            color: #6c757d;
            font-size: 1.25rem;
            margin-right: 1rem;
        }
        
        .sidebar-toggle:hover {
            color: #343a40;
        }
        
        @media (max-width: 768px) {
            .admin-sidebar {
                margin-left: -250px;
            }
            
            .admin-sidebar.show {
                margin-left: 0;
            }
            
            .admin-main-content {
                margin-left: 0;
            }
            
            .sidebar-backdrop {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background-color: rgba(0, 0, 0, 0.5);
                z-index: 1019;
                display: none;
            }
            
            .sidebar-backdrop.show {
                display: block;
            }
        }
        
        .alert {
            border: none;
            border-radius: 0.375rem;
        }
        
        .btn {
            border-radius: 0.375rem;
        }
        
        .card {
            border: none;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            border-radius: 0.375rem;
        }
        
        .table {
            background-color: #fff;
        }
    </style>
    
    <?php echo $__env->yieldPushContent('styles'); ?>
</head>
<body>
    
    <nav class="navbar navbar-expand-lg admin-navbar fixed-top">
        <div class="container-fluid">
            
            <button class="sidebar-toggle d-lg-none" type="button" id="sidebarToggle">
                <i class="fas fa-bars"></i>
            </button>
            
            
            <a class="navbar-brand d-flex align-items-center" href="<?php echo e(route('admin.dashboard')); ?>">
                <img src="<?php echo e(asset('images/logo1.png')); ?>" alt="Logo" height="32" class="me-2">
                <span class="fw-bold">Admin Panel</span>
            </a>
            
            
            <div class="navbar-nav ms-auto">
                
                <div class="nav-item dropdown">
                    <button class="admin-dropdown-toggle dropdown-toggle d-flex align-items-center" 
                            type="button" 
                            id="adminUserDropdown" 
                            data-bs-toggle="dropdown" 
                            aria-expanded="false">
                        <?php if(Auth::check() && Auth::user()->profile_photo_path): ?>
                            <img src="<?php echo e(asset('storage/' . Auth::user()->profile_photo_path)); ?>" 
                                 alt="Profile" 
                                 class="rounded-circle me-2" 
                                 style="width: 32px; height: 32px; object-fit: cover;">
                        <?php else: ?>
                            <i class="fas fa-user-shield me-2"></i>
                        <?php endif; ?>
                        <span><?php echo e(Auth::user()->username ?? 'Admin'); ?></span>
                    </button>
                    
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="adminUserDropdown">
                        <li>
                            <a class="dropdown-item" href="<?php echo e(route('homepage')); ?>" target="_blank">
                                <i class="fas fa-external-link-alt me-2"></i>
                                Lihat Website
                            </a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form id="admin-logout-form" action="<?php echo e(route('logout')); ?>" method="POST" style="display: none;">
                                <?php echo csrf_field(); ?>
                            </form>
                            <a class="dropdown-item" 
                               href="#" 
                               onclick="event.preventDefault(); document.getElementById('admin-logout-form').submit();">
                                <i class="fas fa-sign-out-alt me-2"></i>
                                Logout
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>
    
    
    <aside class="admin-sidebar" id="adminSidebar">
        
        
        <nav class="sidebar-nav">
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link <?php if(Request::routeIs('admin.dashboard')): ?> active <?php endif; ?>" 
                       href="<?php echo e(route('admin.dashboard')); ?>">
                        <i class="fas fa-tachometer-alt"></i>
                        Dashboard
                    </a>
                </li>
                
                <li class="nav-item">
                    <a class="nav-link <?php if(Request::routeIs('admin.users.*')): ?> active <?php endif; ?>" 
                       href="<?php echo e(route('admin.users.index')); ?>">
                        <i class="fas fa-users"></i>
                        Kelola User
                    </a>
                </li>
                
                <li class="nav-item">
                    <a class="nav-link <?php if(Request::routeIs('admin.categories.*')): ?> active <?php endif; ?>" 
                       href="<?php echo e(route('admin.categories.index')); ?>">
                        <i class="fas fa-tags"></i>
                        Kelola Kategori
                    </a>
                </li>
                
                <li class="nav-item">
                    <a class="nav-link <?php if(Request::routeIs('admin.products.*')): ?> active <?php endif; ?>" 
                       href="<?php echo e(route('admin.products.index')); ?>">
                        <i class="fas fa-box-open"></i>
                        Kelola Produk
                    </a>
                </li>
                
                
                <li class="nav-item">
                    <a class="nav-link" href="#">
                        <i class="fas fa-shopping-cart"></i>
                        Kelola Pesanan
                    </a>
                </li>
                
                <li class="nav-item">
                    <a class="nav-link" href="#">
                        <i class="fas fa-ticket-alt"></i>
                        Customer Service
                    </a>
                </li>
                
                <li class="nav-item">
                    <a class="nav-link" href="#">
                        <i class="fas fa-chart-bar"></i>
                        Laporan
                    </a>
                </li>
                
                
                <li class="nav-item" style="border-bottom: 2px solid #495057; margin: 0.5rem 0;"></li>
                
                <li class="nav-item">
                    <a class="nav-link" href="#">
                        <i class="fas fa-cog"></i>
                        Pengaturan
                    </a>
                </li>
            </ul>
        </nav>
    </aside>
    
    
    <div class="sidebar-backdrop" id="sidebarBackdrop"></div>
    
    
    <main class="admin-main-content" id="adminMainContent">
        
        <?php if(!isset($hideContentHeader) || !$hideContentHeader): ?>
        <div class="content-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1><?php echo $__env->yieldContent('page-title', 'Dashboard'); ?></h1>
                    <?php if(isset($breadcrumbs) && count($breadcrumbs) > 0): ?>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb breadcrumb-nav">
                                <?php $__currentLoopData = $breadcrumbs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $breadcrumb): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php if($loop->last): ?>
                                        <li class="breadcrumb-item active" aria-current="page">
                                            <?php echo e($breadcrumb['title']); ?>

                                        </li>
                                    <?php else: ?>
                                        <li class="breadcrumb-item">
                                            <a href="<?php echo e($breadcrumb['url']); ?>"><?php echo e($breadcrumb['title']); ?></a>
                                        </li>
                                    <?php endif; ?>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </ol>
                        </nav>
                    <?php endif; ?>
                </div>
                
                <?php echo $__env->yieldContent('content-header-actions'); ?>
            </div>
        </div>
        <?php endif; ?>
        
        
        <?php if(session('success')): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>
                <?php echo e(session('success')); ?>

                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
        
        <?php if(session('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i>
                <?php echo e(session('error')); ?>

                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
        
        <?php if(session('warning')): ?>
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-triangle me-2"></i>
                <?php echo e(session('warning')); ?>

                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
        
        <?php if(session('info')): ?>
            <div class="alert alert-info alert-dismissible fade show" role="alert">
                <i class="fas fa-info-circle me-2"></i>
                <?php echo e(session('info')); ?>

                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
        
        
        <?php echo $__env->yieldContent('content'); ?>
    </main>
    
    
    <script src="<?php echo e(asset('js/bootstrap/bootstrap.bundle.min.js')); ?>"></script>
    
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebarToggle = document.getElementById('sidebarToggle');
            const sidebar = document.getElementById('adminSidebar');
            const mainContent = document.getElementById('adminMainContent');
            const backdrop = document.getElementById('sidebarBackdrop');
            
            // Toggle sidebar on mobile
            if (sidebarToggle) {
                sidebarToggle.addEventListener('click', function() {
                    sidebar.classList.toggle('show');
                    backdrop.classList.toggle('show');
                    document.body.style.overflow = sidebar.classList.contains('show') ? 'hidden' : '';
                });
            }
            
            // Close sidebar when clicking backdrop
            if (backdrop) {
                backdrop.addEventListener('click', function() {
                    sidebar.classList.remove('show');
                    backdrop.classList.remove('show');
                    document.body.style.overflow = '';
                });
            }
            
            // Auto-hide alerts after 5 seconds
            const alerts = document.querySelectorAll('.alert:not(.alert-permanent)');
            alerts.forEach(function(alert) {
                setTimeout(function() {
                    if (alert.parentNode) {
                        alert.classList.remove('show');
                        setTimeout(function() {
                            if (alert.parentNode) {
                                alert.remove();
                            }
                        }, 150);
                    }
                }, 5000);
            });
        });
    </script>
    
    
    <?php if(Request::routeIs('admin.products.*')): ?>
        <script src="<?php echo e(asset('js/admin/product-form-enhancements.js')); ?>"></script>
    <?php endif; ?>
    
    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html><?php /**PATH /Users/jerenovvidimy/Documents/MiraTaraTest/resources/views/admin/layouts/app.blade.php ENDPATH**/ ?>