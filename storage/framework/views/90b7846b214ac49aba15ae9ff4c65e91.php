

<div id="filterSidebar" class="filter-sidebar">
    <div class="sidebar-header">
        <h5 class="sidebar-title">Product Filters</h5>
        <button type="button" class="close-sidebar-btn" aria-label="Close Filter">
            &times;
        </button>
    </div>
    <div class="sidebar-body">
        
        <form id="filterFormSidebar" method="GET" action="<?php echo e(route('products.index')); ?>">
            
            <input type="hidden" name="sort_by" value="<?php echo e($sortBy); ?>">
            <input type="hidden" name="limit" value="<?php echo e($limit); ?>">
            
            

            
            <div class="mb-4">
                <h6 class="filter-heading">Category</h6>
                <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="category_id" id="sidebar_category_<?php echo e($category->id); ?>" value="<?php echo e($category->id); ?>"
                               <?php echo e(($request->query('category_id') == $category->id) ? 'checked' : ''); ?>>
                        <label class="form-check-label" for="sidebar_category_<?php echo e($category->id); ?>">
                            <?php echo e($category->name); ?>

                        </label>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php if($request->query('category_id')): ?>
                    <a href="<?php echo e(route('products.index', array_diff_key($request->query(), ['category_id' => '']))); ?>" class="clear-filter-link">Clear Category</a>
                <?php endif; ?>
            </div>

            
            <div class="mb-4">
                <h6 class="filter-heading">Price (Rp)</h6>
                <div class="row g-2">
                    <div class="col-6">
                        <input type="number" name="price_min" class="form-control form-control-sm" placeholder="Min" value="<?php echo e($request->query('price_min')); ?>">
                    </div>
                    <div class="col-6">
                        <input type="number" name="price_max" class="form-control form-control-sm" placeholder="Max" value="<?php echo e($request->query('price_max')); ?>">
                    </div>
                </div>
            </div>

            
            <?php $__currentLoopData = $availableVibeAttributes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $attributeKey => $options): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php if(!empty($options)): ?>
                    <div class="mb-4">
                        <h6 class="filter-heading"><?php echo e(ucfirst(str_replace('_', ' ', $attributeKey))); ?></h6>
                        <?php $__currentLoopData = $options; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $option): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="<?php echo e($attributeKey); ?>[]" id="sidebar_<?php echo e($attributeKey); ?>_<?php echo e($option); ?>" value="<?php echo e($option); ?>"
                                       <?php echo e(in_array($option, (array)$request->query($attributeKey, [])) ? 'checked' : ''); ?>>
                                <label class="form-check-label" for="sidebar_<?php echo e($attributeKey); ?>_<?php echo e($option); ?>">
                                    <?php echo e(ucfirst(str_replace('_', ' ', $option))); ?>

                                </label>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php if(!empty($request->query($attributeKey))): ?>
                            <a href="<?php echo e(route('products.index', array_diff_key($request->query(), [$attributeKey => '']))); ?>" class="clear-filter-link">Clear <?php echo e(ucfirst(str_replace('_', ' ', $attributeKey))); ?></a>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

            
            <?php if(!empty($availableGeneralTags)): ?>
                <div class="mb-4">
                    <h6 class="filter-heading">Other Tags</h6>
                    <?php $__currentLoopData = $availableGeneralTags; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tagOption): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="general_tags[]" id="sidebar_tag_<?php echo e($tagOption); ?>" value="<?php echo e($tagOption); ?>"
                                   <?php echo e(in_array($tagOption, (array)$request->query('general_tags', [])) ? 'checked' : ''); ?>>
                            <label class="form-check-label" for="sidebar_tag_<?php echo e($tagOption); ?>">
                                <?php echo e(ucfirst(str_replace('_', ' ', $tagOption))); ?>

                            </label>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php if(!empty($request->query('general_tags'))): ?>
                        <a href="<?php echo e(route('products.index', array_diff_key($request->query(), ['general_tags' => '']))); ?>" class="clear-filter-link">Clear Tags</a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            
            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-primary">Apply Filters</button>
                <a href="<?php echo e(route('products.index', ['sort_by' => $sortBy, 'limit' => $limit])); ?>" class="btn btn-outline-secondary">Reset All Filters</a>
            </div>
        </form>

        
        <div class="card shadow-sm border-0 mt-4 bg-light-pink text-center py-4">
            <div class="card-body">
                <h5 class="card-title">Find Your Vibe!</h5>
                <p class="card-text text-muted small">Answer a few questions to find your perfect style.</p>
                <div class="d-grid gap-2 mt-3">
                    <a href="<?php echo e(route('products.index', ['vibe_name' => 'beach_getaway'])); ?>" class="btn btn-vibe-primary">Beach Getaway Vibe</a>
                    <a href="<?php echo e(route('products.index', ['vibe_name' => 'elegant_evening'])); ?>" class="btn btn-vibe-primary">Elegant Evening Vibe</a>
                    <a href="<?php echo e(route('products.index', ['vibe_name' => 'sporty_active'])); ?>" class="btn btn-vibe-primary">Sporty Vibe</a>
                    <a href="<?php echo e(route('products.index', ['vibe_name' => 'daily_casual'])); ?>" class="btn btn-vibe-primary">Daily Casual Vibe</a>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="sidebarOverlay" class="sidebar-overlay"></div><?php /**PATH /Users/jerenovvidimy/Documents/MiraTaraTest/resources/views/partials/product_filter_sidebar.blade.php ENDPATH**/ ?>