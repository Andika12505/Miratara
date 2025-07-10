<?php $__env->startSection('title', 'Edit Produk: ' . $product->name); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Edit Produk: <?php echo e($product->name); ?></h4>
                </div>
                <div class="card-body">
                    <?php if($errors->any()): ?>
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <li><?php echo e($error); ?></li>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <form action="<?php echo e(route('admin.products.update', $product)); ?>" method="POST" enctype="multipart/form-data" id="productForm">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('PUT'); ?>
                        
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="category_id" class="form-label">Kategori</label>
                                    <select class="form-select" id="category_id" name="category_id" required>
                                        <option value="">Pilih Kategori</option>
                                        <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($category->id); ?>" 
                                                <?php echo e(old('category_id', $product->category_id) == $category->id ? 'selected' : ''); ?>>
                                                <?php echo e($category->name); ?>

                                            </option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Nama Produk</label>
                                    <input type="text" class="form-control" id="name" name="name" 
                                           value="<?php echo e(old('name', $product->name)); ?>" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="slug" class="form-label">Slug</label>
                                    <input type="text" class="form-control" id="slug" name="slug" 
                                           value="<?php echo e(old('slug', $product->slug)); ?>" required>
                                    <small class="form-text text-muted">Akan digunakan di URL, contoh: t-shirt-keren</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="origin" class="form-label">Asal Produk</label>
                                    <select class="form-select" id="origin" name="origin">
                                        <option value="">Pilih Asal</option>
                                        <?php $__currentLoopData = ['Indonesia', 'China', 'Vietnam', 'India', 'USA', 'Turkey', 'Bangladesh']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $origin): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($origin); ?>" 
                                                <?php echo e(old('origin', $product->metadata['origin'] ?? '') == $origin ? 'selected' : ''); ?>>
                                                <?php echo e($origin); ?>

                                            </option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Deskripsi</label>
                            <textarea class="form-control" id="description" name="description" rows="3"><?php echo e(old('description', $product->description)); ?></textarea>
                        </div>

                        
                        <div class="mb-3">
                            <label for="image" class="form-label">Gambar Produk Saat Ini</label>
                            <?php if($product->image): ?>
                                <div class="mb-2">
                                    <img src="<?php echo e(asset('storage/products/' . $product->image)); ?>" 
                                         alt="<?php echo e($product->name); ?>" width="150" class="img-thumbnail">
                                    <p class="text-muted mt-1">File saat ini: <?php echo e($product->image); ?></p>
                                </div>
                                <div class="form-check mb-2">
                                    <input type="checkbox" class="form-check-input" id="clear_image" name="clear_image" value="1">
                                    <label class="form-check-label" for="clear_image">Hapus Gambar</label>
                                </div>
                            <?php else: ?>
                                <p class="text-muted">Tidak ada gambar saat ini.</p>
                            <?php endif; ?>
                            <input type="file" class="form-control" id="image" name="image" accept=".png">
                            <small class="form-text text-muted">Pilih file PNG baru untuk mengganti yang lama. Maksimal 2MB.</small>
                        </div>

                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="price" class="form-label">Harga</label>
                                    <input type="number" class="form-control" id="price" name="price" 
                                           value="<?php echo e(old('price', $product->price)); ?>" step="0.01" min="0" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="stock" class="form-label">Stok</label>
                                    <input type="number" class="form-control" id="stock" name="stock" 
                                           value="<?php echo e(old('stock', $product->stock)); ?>" min="0" required>
                                </div>
                            </div>
                        </div>

                        <?php
                            // Extract existing metadata for form population
                            $vibeAttributes = $product->metadata['vibe_attributes'] ?? [];
                            $generalTags = $product->metadata['general_tags'] ?? [];
                        ?>

                        
                        <div class="card mt-4">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Vibe Attributes (untuk pencarian vibe)</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Occasion</label>
                                        <div class="checkbox-group">
                                            <?php $__currentLoopData = ['casual', 'formal', 'party', 'work', 'sport', 'vacation', 'daily']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $option): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="vibe_occasion[]" 
                                                           value="<?php echo e($option); ?>" id="occasion_<?php echo e($option); ?>"
                                                           <?php echo e(in_array($option, old('vibe_occasion', $vibeAttributes['occasion'] ?? [])) ? 'checked' : ''); ?>>
                                                    <label class="form-check-label" for="occasion_<?php echo e($option); ?>">
                                                        <?php echo e(ucfirst($option)); ?>

                                                    </label>
                                                </div>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </div>
                                    </div>

                                    
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Style</label>
                                        <div class="checkbox-group">
                                            <?php $__currentLoopData = ['vintage', 'modern', 'classic', 'trendy', 'minimalist', 'bohemian', 'streetwear']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $option): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="vibe_style[]" 
                                                           value="<?php echo e($option); ?>" id="style_<?php echo e($option); ?>"
                                                           <?php echo e(in_array($option, old('vibe_style', $vibeAttributes['style'] ?? [])) ? 'checked' : ''); ?>>
                                                    <label class="form-check-label" for="style_<?php echo e($option); ?>">
                                                        <?php echo e(ucfirst($option)); ?>

                                                    </label>
                                                </div>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Material</label>
                                        <div class="checkbox-group">
                                            <?php $__currentLoopData = ['cotton', 'polyester', 'wool', 'silk', 'linen', 'denim', 'leather', 'viscose']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $option): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="vibe_material[]" 
                                                           value="<?php echo e($option); ?>" id="material_<?php echo e($option); ?>"
                                                           <?php echo e(in_array($option, old('vibe_material', $vibeAttributes['material'] ?? [])) ? 'checked' : ''); ?>>
                                                    <label class="form-check-label" for="material_<?php echo e($option); ?>">
                                                        <?php echo e(ucfirst($option)); ?>

                                                    </label>
                                                </div>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </div>
                                    </div>

                                    
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Color Tone</label>
                                        <div class="checkbox-group">
                                            <?php $__currentLoopData = ['bright', 'pastel', 'dark', 'neutral', 'earth', 'neon', 'metallic']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $option): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="vibe_color_tone[]" 
                                                           value="<?php echo e($option); ?>" id="color_tone_<?php echo e($option); ?>"
                                                           <?php echo e(in_array($option, old('vibe_color_tone', $vibeAttributes['color_tone'] ?? [])) ? 'checked' : ''); ?>>
                                                    <label class="form-check-label" for="color_tone_<?php echo e($option); ?>">
                                                        <?php echo e(ucfirst(str_replace('_', ' ', $option))); ?>

                                                    </label>
                                                </div>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Fit</label>
                                        <div class="checkbox-group">
                                            <?php $__currentLoopData = ['slim', 'regular', 'loose', 'oversized', 'fitted', 'relaxed']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $option): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="vibe_fit[]" 
                                                           value="<?php echo e($option); ?>" id="fit_<?php echo e($option); ?>"
                                                           <?php echo e(in_array($option, old('vibe_fit', $vibeAttributes['fit'] ?? [])) ? 'checked' : ''); ?>>
                                                    <label class="form-check-label" for="fit_<?php echo e($option); ?>">
                                                        <?php echo e(ucfirst($option)); ?>

                                                    </label>
                                                </div>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </div>
                                    </div>

                                    
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Pattern</label>
                                        <div class="checkbox-group">
                                            <?php $__currentLoopData = ['solid', 'striped', 'floral', 'geometric', 'abstract', 'polka_dots', 'checkered']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $option): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="vibe_pattern[]" 
                                                           value="<?php echo e($option); ?>" id="pattern_<?php echo e($option); ?>"
                                                           <?php echo e(in_array($option, old('vibe_pattern', $vibeAttributes['pattern'] ?? [])) ? 'checked' : ''); ?>>
                                                    <label class="form-check-label" for="pattern_<?php echo e($option); ?>">
                                                        <?php echo e(ucfirst(str_replace('_', ' ', $option))); ?>

                                                    </label>
                                                </div>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Neckline</label>
                                        <div class="checkbox-group">
                                            <?php $__currentLoopData = ['round', 'v_neck', 'crew', 'scoop', 'high_neck', 'off_shoulder', 'halter']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $option): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="vibe_neckline[]" 
                                                           value="<?php echo e($option); ?>" id="neckline_<?php echo e($option); ?>"
                                                           <?php echo e(in_array($option, old('vibe_neckline', $vibeAttributes['neckline'] ?? [])) ? 'checked' : ''); ?>>
                                                    <label class="form-check-label" for="neckline_<?php echo e($option); ?>">
                                                        <?php echo e(ucfirst(str_replace('_', ' ', $option))); ?>

                                                    </label>
                                                </div>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </div>
                                    </div>

                                    
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Sleeve Length</label>
                                        <div class="checkbox-group">
                                            <?php $__currentLoopData = ['sleeveless', 'short_sleeve', 'long_sleeve', '3_quarter', 'cap_sleeve']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $option): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="vibe_sleeve_length[]" 
                                                           value="<?php echo e($option); ?>" id="sleeve_length_<?php echo e($option); ?>"
                                                           <?php echo e(in_array($option, old('vibe_sleeve_length', $vibeAttributes['sleeve_length'] ?? [])) ? 'checked' : ''); ?>>
                                                    <label class="form-check-label" for="sleeve_length_<?php echo e($option); ?>">
                                                        <?php echo e(ucfirst(str_replace('_', ' ', $option))); ?>

                                                    </label>
                                                </div>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        
                        <div class="card mt-4">
                            <div class="card-header">
                                <h5 class="card-title mb-0">General Tags</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="checkbox-group">
                                            <?php $__currentLoopData = ['comfortable', 'elegant', 'sporty', 'sexy', 'professional', 'casual_wear', 'evening_wear']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $option): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="checkbox" name="general_tags[]" 
                                                           value="<?php echo e($option); ?>" id="general_tag_<?php echo e($option); ?>"
                                                           <?php echo e(in_array($option, old('general_tags', $generalTags)) ? 'checked' : ''); ?>>
                                                    <label class="form-check-label" for="general_tag_<?php echo e($option); ?>">
                                                        <?php echo e(ucfirst(str_replace('_', ' ', $option))); ?>

                                                    </label>
                                                </div>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        
                        <div class="mt-4">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1" 
                                       <?php echo e(old('is_active', $product->is_active) ? 'checked' : ''); ?>>
                                <label class="form-check-label" for="is_active">Produk Aktif</label>
                            </div>
                        </div>

                        
                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary">Update Produk</button>
                            <a href="<?php echo e(route('admin.products.index')); ?>" class="btn btn-secondary">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.checkbox-group {
    max-height: 200px;
    overflow-y: auto;
    border: 1px solid #dee2e6;
    border-radius: 0.375rem;
    padding: 0.5rem;
    background-color: #f8f9fa;
}

.checkbox-group .form-check {
    margin-bottom: 0.25rem;
}

.card-header {
    background-color: #e3f2fd;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-generate slug from product name (optional for edit)
    const nameInput = document.getElementById('name');
    const slugInput = document.getElementById('slug');
    
    nameInput.addEventListener('input', function() {
        const slug = this.value
            .toLowerCase()
            .replace(/[^a-z0-9\s-]/g, '')
            .replace(/\s+/g, '-')
            .replace(/-+/g, '-')
            .trim('-');
        slugInput.value = slug;
    });
});
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/jerenovvidimy/Documents/MiraTaraTest/resources/views/admin/products/edit.blade.php ENDPATH**/ ?>