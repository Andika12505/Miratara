
<div class="row g-4">
    <?php $__empty_1 = true; $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <div class="col-lg-4 col-md-6 col-sm-12">
            <div class="product-card">
                
                <a href="<?php echo e(route('products.show', $product->slug)); ?>" class="product-link">
                    <div class="product-image-container position-relative">
                        <?php if($showDiscount && isset($product->metadata['is_discounted']) && $product->metadata['is_discounted']): ?>
                            <div class="discount-badge">
                                <span>DISKON</span>
                            </div>
                        <?php endif; ?>
                        <img src="<?php echo e($product->image ? asset('images/' . $product->image) : asset('images/placeholder.jpg')); ?>"
                             alt="<?php echo e($product->name); ?>"
                             class="product-image"
                             onerror="this.src='https://via.placeholder.com/400x600/f8f9fa/6c757d?text=No+Image'">
                    </div>
                    <div class="product-info">
                        <h3 class="product-title"><?php echo e($product->name); ?></h3>
                        <div class="product-pricing">
                            <?php if($showDiscount && isset($product->metadata['original_price']) && $product->metadata['original_price']): ?>
                                <span class="current-price">Rp <?php echo e(number_format($product->price, 0, ',', '.')); ?></span>
                                <span class="original-price">Rp <?php echo e(number_format($product->metadata['original_price'], 0, ',', '.')); ?></span>
                            <?php else: ?>
                                <span class="current-price">Rp <?php echo e(number_format($product->price, 0, ',', '.')); ?></span>
                            <?php endif; ?>
                        </div>
                    </div>
                </a>
                
                
                <div class="product-actions">
                    <?php if($useFormCart): ?>
                        <form action="<?php echo e(route('cart.add')); ?>" method="POST" class="d-grid add-to-cart-form">
                            <?php echo csrf_field(); ?>
                            <input type="hidden" name="id" value="<?php echo e($product->id); ?>">
                            <input type="hidden" name="quantity" value="1">
                            <button type="submit" class="add-to-bag-btn" <?php echo e($product->stock <= 0 ? 'disabled' : ''); ?>>
                                <?php echo e($product->stock <= 0 ? $outOfStockText : $buttonText); ?>

                            </button>
                        </form>
                    <?php else: ?>
                        <button class="add-to-bag-btn"
                                data-product-id="<?php echo e($product->id); ?>"
                                <?php echo e($product->stock <= 0 ? 'disabled' : ''); ?>>
                            <?php echo e($product->stock <= 0 ? $outOfStockText : $buttonText); ?>

                        </button>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <div class="col-12">
            <div class="text-center py-5">
                <p class="text-muted"><?php echo e($emptyMessage); ?></p>
                <a href="<?php echo e(route('products.index')); ?>" class="<?php echo e($emptyButtonClass); ?> mt-3"><?php echo e($emptyButtonText); ?></a>
            </div>
        </div>
    <?php endif; ?>
</div>

<style>
/* Product Grid Clickable Enhancement */
.product-link {
    text-decoration: none;
    color: inherit;
    display: block;
}

.product-link:hover {
    text-decoration: none;
    color: inherit;
}

.product-card {
    background: #fff;
    border-radius: 0;
    overflow: hidden;
    transition: transform 0.3s ease;
    border: none;
    box-shadow: none;
    height: 100%;
    display: flex;
    flex-direction: column;
}

.product-card:hover {
    transform: translateY(-5px);
}

.product-image-container {
    position: relative;
    overflow: hidden;
    background: #f8f9fa;
    border-radius: 0;
}

.product-image {
    width: 100%;
    height: auto;
    object-fit: contain;
    display: block;
    transition: transform 0.3s ease;
}

.product-card:hover .product-image {
    transform: scale(1.02);
}

.discount-badge {
    position: absolute;
    top: 15px;
    left: 15px;
    background: #ff69b4;
    color: white;
    padding: 6px 12px;
    font-size: 11px;
    font-weight: 600;
    letter-spacing: 0.5px;
    border-radius: 4px;
    z-index: 2;
}

.product-info {
    padding: 20px 0 15px 0;
    text-align: center;
    flex-grow: 1;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
}

.product-title {
    font-size: 16px;
    font-weight: 400;
    color: #333;
    margin-bottom: 10px;
    line-height: 1.4;
    flex-grow: 1;
    transition: color 0.3s ease;
}

.product-link:hover .product-title {
    color: #ffc0cb;
}

.product-pricing {
    margin-bottom: 0;
}

.current-price {
    font-size: 14px;
    font-weight: 600;
    color: #333;
    margin-right: 8px;
}

.original-price {
    font-size: 14px;
    color: #999;
    text-decoration: line-through;
}

.product-actions {
    padding: 0 20px 20px 20px;
}

.add-to-bag-btn {
    background: transparent;
    border: 1px solid #333;
    color: #333;
    padding: 8px 20px;
    font-size: 12px;
    font-weight: 500;
    letter-spacing: 1px;
    cursor: pointer;
    transition: all 0.3s ease;
    text-transform: uppercase;
    min-height: 40px;
    width: 100%;
}

.add-to-bag-btn:hover:not(:disabled) {
    background: #333;
    color: white;
}

.add-to-bag-btn:disabled {
    background: #f5f5f5;
    border-color: #ddd;
    color: #999;
    cursor: not-allowed;
}

/* Grid layout */
.row.g-4 {
    display: flex;
    flex-wrap: wrap;
}

.row.g-4 > [class*="col-"] {
    display: flex;
    margin-bottom: 2rem;
}

.row.g-4 .product-card {
    width: 100%;
}

/* Quick view button on hover (optional enhancement) */
.product-card {
    position: relative;
}

.quick-view-btn {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    background: rgba(255, 192, 203, 0.9);
    color: white;
    border: none;
    padding: 8px 16px;
    font-size: 12px;
    font-weight: 600;
    letter-spacing: 0.5px;
    border-radius: 4px;
    opacity: 0;
    transition: opacity 0.3s ease;
    z-index: 10;
    text-transform: uppercase;
}

.product-image-container:hover .quick-view-btn {
    opacity: 1;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .product-info {
        padding: 15px 0 10px 0;
    }
    
    .product-actions {
        padding: 0 15px 15px 15px;
    }
    
    .product-title {
        font-size: 15px;
    }
}

@media (max-width: 576px) {
    .row {
        margin: 0 -10px;
    }
    
    .col-sm-12 {
        padding: 0 10px;
        margin-bottom: 30px;
    }
}
</style><?php /**PATH /Users/jerenovvidimy/Documents/MiraTaraTest/resources/views/components/product-grid.blade.php ENDPATH**/ ?>