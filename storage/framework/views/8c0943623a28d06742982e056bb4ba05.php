<?php $__env->startSection('title', 'Kelola User - Admin MiraTara'); ?>

<?php $__env->startSection('content'); ?>
    <div class="page-header mb-4">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="h3 mb-1">Kelola User</h1>
                <p class="text-muted mb-0">
                    Lihat, tambah, edit, dan hapus user yang terdaftar di sistem.
                </p>
            </div>
            <div>
                <a href="<?php echo e(route('admin.users.create')); ?>" class="btn btn-primary">
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
                        
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-between align-items-center mt-3">
                <div id="paginationInfo"></div>
                <nav id="paginationLinks">
                    <ul class="pagination mb-0">
                        
                    </ul>
                </nav>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
    
    <script>
        window.usersApiUrl = "<?php echo e(route('admin.users.data')); ?>";
        window.editUserRoute = "<?php echo e(route('admin.users.edit', ':id')); ?>"; // Placeholder untuk ID
        window.deleteUserRoute = "<?php echo e(route('admin.users.destroy', ':id')); ?>"; // Placeholder untuk ID
    </script>
    <script src="<?php echo e(asset('js/admin/admin_users.js')); ?>"></script>
    
<?php $__env->stopPush(); ?>

<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/jerenovvidimy/Documents/MiraTaraTest/resources/views/admin/users/index.blade.php ENDPATH**/ ?>