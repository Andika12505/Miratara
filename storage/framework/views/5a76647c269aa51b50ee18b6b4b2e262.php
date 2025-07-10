<?php $__env->startSection('title', $product->name . ' - MiraTara Fashion'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid px-4 py-4">
    
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?php echo e(route('homepage')); ?>">Home</a></li>
            <li class="breadcrumb-item"><a href="<?php echo e(route('products.index')); ?>">Products</a></li>
            <?php if($category): ?>
                <li class="breadcrumb-item"><a href="<?php echo e(route('products.index', ['category_id' => $category->id])); ?>"><?php echo e($category->name); ?></a></li>
            <?php endif; ?>
            <li class="breadcrumb-item active" aria-current="page"><?php echo e($product->name); ?></li>
        </ol>
    </nav>

    <div class="row">
        
        <div class="col-lg-6 col-md-12 mb-4">
            <div class="product-image-section">
                <div class="main-product-image">
                    <img src="<?php echo e($product->image ? asset('images/' . $product->image) : asset('images/placeholder.jpg')); ?>"
                         alt="<?php echo e($product->name); ?>"
                         class="img-fluid product-detail-image"
                         onerror="this.src='https://via.placeholder.com/600x800/f8f9fa/6c757d?text=No+Image'">
                </div>
                
                
                <div class="stock-badge">
                    <?php if($product->stock > 0): ?>
                        <span class="badge bg-success">In Stock (<?php echo e($product->stock); ?> available)</span>
                    <?php else: ?>
                        <span class="badge bg-danger">Out of Stock</span>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        
        <div class="col-lg-6 col-md-12">
            <div class="product-details-section">
                
                <div class="product-header mb-4">
                    <h1 class="product-detail-title"><?php echo e($product->name); ?></h1>
                    <div class="product-pricing mb-3">
                        <?php if(isset($product->metadata['is_discounted']) && $product->metadata['is_discounted'] && isset($product->metadata['original_price'])): ?>
                            <span class="current-price">Rp <?php echo e(number_format($product->price, 0, ',', '.')); ?></span>
                            <span class="original-price">Rp <?php echo e(number_format($product->metadata['original_price'], 0, ',', '.')); ?></span>
                            <span class="discount-badge">SALE</span>
                        <?php else: ?>
                            <span class="current-price">Rp <?php echo e(number_format($product->price, 0, ',', '.')); ?></span>
                        <?php endif; ?>
                    </div>
                    <?php if($category): ?>
                        <p class="product-category">
                            <small class="text-muted">Category: <a href="<?php echo e(route('products.index', ['category_id' => $category->id])); ?>"><?php echo e($category->name); ?></a></small>
                        </p>
                    <?php endif; ?>
                </div>

                
                <?php if($product->description): ?>
                <div class="product-description mb-4">
                    <h5>Description</h5>
                    <p><?php echo e($product->description); ?></p>
                </div>
                <?php endif; ?>

                
                <div class="size-selection mb-4">
                    <h5>Size</h5>
                    <?php if($product->hasSizes()): ?>
                        <div class="size-options">
                            <div class="size-buttons-grid">
                                <?php $__currentLoopData = $product->sizes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $size): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="size-option <?php echo e($size->pivot->stock <= 0 ? 'out-of-stock' : ''); ?>" 
                                         data-size-id="<?php echo e($size->id); ?>"
                                         data-size-name="<?php echo e($size->name); ?>"
                                         data-stock="<?php echo e($size->pivot->stock); ?>">
                                        <input type="radio" 
                                               name="size_id" 
                                               id="size_<?php echo e($size->id); ?>" 
                                               value="<?php echo e($size->id); ?>"
                                               class="size-radio"
                                               <?php echo e($size->pivot->stock <= 0 || !$size->pivot->is_available ? 'disabled' : ''); ?>

                                               <?php echo e($loop->first && $size->pivot->stock > 0 ? 'checked' : ''); ?>>
                                        <label for="size_<?php echo e($size->id); ?>" class="size-label">
                                            <span class="size-name"><?php echo e($size->name); ?></span>
                                            <span class="size-display"><?php echo e($size->display); ?></span>
                                            <?php if($size->pivot->stock <= 0): ?>
                                                <span class="stock-info out">Out of Stock</span>
                                            <?php elseif($size->pivot->stock <= 3): ?>
                                                <span class="stock-info low">Only <?php echo e($size->pivot->stock); ?> left</span>
                                            <?php else: ?>
                                                <span class="stock-info available">In Stock</span>
                                            <?php endif; ?>
                                        </label>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                            <div class="selected-size-info mt-2">
                                <small class="text-muted">Select a size to continue</small>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="size-options">
                            <div class="alert alert-info">
                                <small><i class="fas fa-info-circle me-2"></i>One size fits all. No size selection needed.</small>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>

                
                <div class="purchase-section mb-4">
                    <form action="<?php echo e(route('cart.add')); ?>" method="POST" class="add-to-cart-form">
                        <?php echo csrf_field(); ?>
                        <input type="hidden" name="id" value="<?php echo e($product->id); ?>">
                        <input type="hidden" name="size_id" id="selected_size_id" value="">
                        
                        <div class="quantity-section mb-3">
                            <label for="quantity" class="form-label">Quantity</label>
                            <div class="input-group quantity-input-group" style="width: 150px;">
                                <button type="button" class="btn btn-outline-secondary quantity-btn" onclick="decreaseQuantity()">-</button>
                                <input type="number" id="quantity" name="quantity" class="form-control text-center" value="1" min="1" max="<?php echo e($product->display_stock); ?>" <?php echo e($product->isInStock() ? '' : 'disabled'); ?>>
                                <button type="button" class="btn btn-outline-secondary quantity-btn" onclick="increaseQuantity()">+</button>
                            </div>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary add-to-cart-btn" id="addToCartBtn" <?php echo e($product->isInStock() ? '' : 'disabled'); ?>>
                                <i class="fas fa-shopping-cart me-2"></i>
                                <span id="addToCartText"><?php echo e($product->isInStock() ? 'ADD TO CART' : 'OUT OF STOCK'); ?></span>
                            </button>
                        </div>
                    </form>
                </div>

                
                <div class="product-attributes">
                    <?php if(!empty($vibeAttributes) || !empty($generalTags) || $origin): ?>
                    <h5>Product Details</h5>
                    <div class="attributes-grid">
                        <?php $__currentLoopData = $vibeAttributes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $attribute => $values): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php if(!empty($values)): ?>
                                <div class="attribute-item">
                                    <strong><?php echo e(ucfirst(str_replace('_', ' ', $attribute))); ?>:</strong>
                                    <span>
                                        <?php if(is_array($values)): ?>
                                            <?php echo e(implode(', ', array_map('ucfirst', $values))); ?>

                                        <?php else: ?>
                                            <?php echo e(ucfirst($values)); ?>

                                        <?php endif; ?>
                                    </span>
                                </div>
                            <?php endif; ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                        <?php if(!empty($generalTags)): ?>
                            <div class="attribute-item">
                                <strong>Features:</strong>
                                <span><?php echo e(implode(', ', array_map('ucfirst', $generalTags))); ?></span>
                            </div>
                        <?php endif; ?>

                        <?php if($origin): ?>
                            <div class="attribute-item">
                                <strong>Origin:</strong>
                                <span><?php echo e($origin); ?></span>
                            </div>
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    
    <?php if($relatedProducts->count() > 0): ?>
    <div class="related-products-section mt-5 pt-5">
        <div class="row">
            <div class="col-12">
                <h3 class="section-title mb-4">You Might Also Like</h3>
            </div>
        </div>
        
        <?php if (isset($component)) { $__componentOriginal4d695489bf05cd3a8e675a0f0518ee14 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal4d695489bf05cd3a8e675a0f0518ee14 = $attributes; } ?>
<?php $component = App\View\Components\ProductGrid::resolve(['products' => $relatedProducts,'showDiscount' => true,'useFormCart' => true,'emptyMessage' => '','emptyButtonText' => '','emptyButtonClass' => '','buttonText' => 'ADD TO BAG','outOfStockText' => 'OUT OF STOCK'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('product-grid'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\ProductGrid::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal4d695489bf05cd3a8e675a0f0518ee14)): ?>
<?php $attributes = $__attributesOriginal4d695489bf05cd3a8e675a0f0518ee14; ?>
<?php unset($__attributesOriginal4d695489bf05cd3a8e675a0f0518ee14); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal4d695489bf05cd3a8e675a0f0518ee14)): ?>
<?php $component = $__componentOriginal4d695489bf05cd3a8e675a0f0518ee14; ?>
<?php unset($__componentOriginal4d695489bf05cd3a8e675a0f0518ee14); ?>
<?php endif; ?>
    </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
<style>
/* Product Detail Page Styles */
.container-fluid {
    max-width: 1400px;
    margin: 0 auto;
}

/* Breadcrumb */
.breadcrumb {
    background: none;
    padding: 0;
    margin-bottom: 1rem;
}

.breadcrumb-item a {
    color: #666;
    text-decoration: none;
}

.breadcrumb-item a:hover {
    color: #ffc0cb;
}

.breadcrumb-item.active {
    color: #333;
}

/* Product Image Section */
.product-image-section {
    position: relative;
}

.main-product-image {
    background: #f8f9fa;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

.product-detail-image {
    width: 100%;
    height: auto;
    object-fit: contain;
    display: block;
}

.stock-badge {
    position: absolute;
    top: 15px;
    right: 15px;
    z-index: 10;
}

/* Product Details Section */
.product-details-section {
    padding-left: 20px;
}

.product-detail-title {
    font-size: 2rem;
    font-weight: 400;
    color: #333;
    margin-bottom: 10px;
    line-height: 1.3;
}

.product-pricing {
    display: flex;
    align-items: center;
    gap: 15px;
    flex-wrap: wrap;
}

.current-price {
    font-size: 1.5rem;
    font-weight: 600;
    color: #333;
}

.original-price {
    font-size: 1.2rem;
    color: #999;
    text-decoration: line-through;
}

.discount-badge {
    background: #ff69b4;
    color: white;
    padding: 4px 8px;
    font-size: 0.75rem;
    font-weight: 600;
    letter-spacing: 0.5px;
    border-radius: 4px;
}

.product-category a {
    color: #ffc0cb;
    text-decoration: none;
}

.product-category a:hover {
    text-decoration: underline;
}

/* Product Description */
.product-description h5,
.size-selection h5,
.product-attributes h5 {
    font-size: 1.1rem;
    font-weight: 600;
    color: #333;
    margin-bottom: 15px;
    border-bottom: 1px solid #eee;
    padding-bottom: 5px;
}

.product-description p {
    color: #666;
    line-height: 1.6;
    margin-bottom: 0;
}

/* Size Selection */
.size-buttons-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
    gap: 10px;
    margin-bottom: 15px;
}

.size-option {
    position: relative;
}

.size-radio {
    position: absolute;
    opacity: 0;
    cursor: pointer;
    height: 0;
    width: 0;
}

.size-label {
    display: block;
    padding: 12px 15px;
    border: 2px solid #ddd;
    border-radius: 6px;
    text-align: center;
    cursor: pointer;
    transition: all 0.3s ease;
    background: white;
    user-select: none;
}

.size-label:hover {
    border-color: #ffc0cb;
    background: rgba(255, 192, 203, 0.05);
}

.size-radio:checked + .size-label {
    border-color: #ffc0cb;
    background: rgba(255, 192, 203, 0.1);
    color: #333;
}

.size-radio:disabled + .size-label {
    background: #f8f9fa;
    color: #999;
    border-color: #e9ecef;
    cursor: not-allowed;
    opacity: 0.6;
}

.size-option.out-of-stock .size-label {
    background: #f8f9fa;
    color: #999;
    border-color: #e9ecef;
    cursor: not-allowed;
    position: relative;
}

.size-option.out-of-stock .size-label::after {
    content: '';
    position: absolute;
    top: 50%;
    left: 10%;
    right: 10%;
    height: 1px;
    background: #999;
    transform: translateY(-50%);
}

.size-name {
    display: block;
    font-weight: 600;
    font-size: 1rem;
    margin-bottom: 2px;
}

.size-display {
    display: block;
    font-size: 0.8rem;
    color: #666;
    margin-bottom: 4px;
}

.stock-info {
    display: block;
    font-size: 0.7rem;
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.stock-info.available {
    color: #28a745;
}

.stock-info.low {
    color: #ffc107;
}

.stock-info.out {
    color: #dc3545;
}

.selected-size-info {
    text-align: center;
    min-height: 20px;
}

.size-error {
    color: #dc3545;
    font-size: 0.85rem;
    display: none;
}

/* Purchase Section */
.quantity-input-group {
    max-width: 150px;
}

.quantity-btn {
    border-color: #ddd;
    color: #666;
    font-weight: 600;
    width: 40px;
}

.quantity-btn:hover {
    background-color: #f8f9fa;
    border-color: #ffc0cb;
    color: #333;
}

.quantity-input-group input {
    border-left: none;
    border-right: none;
    font-weight: 600;
}

.add-to-cart-btn {
    background-color: #ffc0cb;
    border-color: #ffc0cb;
    color: white;
    font-weight: 600;
    padding: 12px 0;
    letter-spacing: 1px;
    transition: all 0.3s ease;
}

.add-to-cart-btn:hover:not(:disabled) {
    background-color: #ff8fab;
    border-color: #ff8fab;
    transform: translateY(-2px);
}

.add-to-cart-btn:disabled {
    background-color: #f5f5f5;
    border-color: #ddd;
    color: #999;
    cursor: not-allowed;
}

/* Product Attributes */
.attributes-grid {
    display: grid;
    gap: 10px;
}

.attribute-item {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    padding: 8px 0;
    border-bottom: 1px solid #f0f0f0;
}

.attribute-item:last-child {
    border-bottom: none;
}

.attribute-item strong {
    color: #333;
    min-width: 120px;
    font-weight: 600;
}

.attribute-item span {
    color: #666;
    flex: 1;
}

/* Related Products Section */
.related-products-section {
    border-top: 1px solid #eee;
}

.related-products-section .product-info {
    text-align: center;
}

.related-products-section .product-pricing {
    justify-content: center;
}

.section-title {
    font-size: 1.8rem;
    font-weight: 400;
    color: #333;
    text-align: center;
    position: relative;
    display: inline-block;
    width: 100%;
}

.section-title::after {
    content: "";
    width: 60px;
    height: 2px;
    background-color: #ffc0cb;
    position: absolute;
    bottom: -8px;
    left: 50%;
    transform: translateX(-50%);
    border-radius: 2px;
}

/* Responsive Design */
@media (max-width: 991.98px) {
    .product-details-section {
        padding-left: 0;
        margin-top: 30px;
    }
    
    .product-detail-title {
        font-size: 1.7rem;
    }
    
    .current-price {
        font-size: 1.3rem;
    }
}

@media (max-width: 767.98px) {
    .product-detail-title {
        font-size: 1.5rem;
    }
    
    .current-price {
        font-size: 1.2rem;
    }
    
    .original-price {
        font-size: 1rem;
    }
    
    .product-pricing {
        flex-direction: column;
        align-items: flex-start;
        gap: 8px;
    }
    
    .attribute-item {
        flex-direction: column;
        gap: 5px;
    }
    
    .attribute-item strong {
        min-width: auto;
    }
}

@media (max-width: 575.98px) {
    .container-fluid {
        padding-left: 15px;
        padding-right: 15px;
    }
    
    .product-detail-title {
        font-size: 1.3rem;
    }
}
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
// Size and quantity management
document.addEventListener('DOMContentLoaded', function() {
    const quantityInput = document.getElementById('quantity');
    const addToCartForm = document.querySelector('.add-to-cart-form');
    const addToCartBtn = document.getElementById('addToCartBtn');
    const addToCartText = document.getElementById('addToCartText');
    const selectedSizeInput = document.getElementById('selected_size_id');
    const selectedSizeInfo = document.querySelector('.selected-size-info small');
    const sizeRadios = document.querySelectorAll('.size-radio');
    const hasSize = <?php echo e($product->hasSizes() ? 'true' : 'false'); ?>;

    // Size selection handling
    sizeRadios.forEach(radio => {
        radio.addEventListener('change', function() {
            if (this.checked) {
                const sizeOption = this.closest('.size-option');
                const sizeName = sizeOption.dataset.sizeName;
                const stock = parseInt(sizeOption.dataset.stock);
                
                // Update hidden input
                selectedSizeInput.value = this.value;
                
                // Update quantity max based on selected size stock
                quantityInput.setAttribute('max', stock);
                if (parseInt(quantityInput.value) > stock) {
                    quantityInput.value = Math.min(stock, 1);
                }
                
                // Update selected size info
                selectedSizeInfo.textContent = `Size ${sizeName} selected (${stock} available)`;
                selectedSizeInfo.className = 'text-success';
                
                // Enable add to cart if size selected and has stock
                updateAddToCartButton();
            }
        });
    });

    // Initial setup
    function updateAddToCartButton() {
        if (!hasSize) {
            // No sizes required, button should work if product has stock
            const productStock = <?php echo e($product->display_stock); ?>;
            if (productStock > 0) {
                addToCartBtn.disabled = false;
                addToCartText.textContent = 'ADD TO CART';
            } else {
                addToCartBtn.disabled = true;
                addToCartText.textContent = 'OUT OF STOCK';
            }
            return;
        }
        
        const selectedSize = document.querySelector('.size-radio:checked');
        if (!selectedSize) {
            addToCartBtn.disabled = true;
            addToCartText.textContent = 'SELECT SIZE';
            selectedSizeInfo.textContent = 'Select a size to continue';
            selectedSizeInfo.className = 'text-muted';
        } else {
            const sizeOption = selectedSize.closest('.size-option');
            const stock = parseInt(sizeOption.dataset.stock);
            
            if (stock > 0) {
                addToCartBtn.disabled = false;
                addToCartText.textContent = 'ADD TO CART';
            } else {
                addToCartBtn.disabled = true;
                addToCartText.textContent = 'OUT OF STOCK';
            }
        }
    }

    // Initial button state
    updateAddToCartButton();

    // Enhanced form submission with size validation
    addToCartForm.addEventListener('submit', function(e) {
        e.preventDefault(); // Always prevent default and use AJAX
        
        // Validate size selection for products with sizes
        if (hasSize && !selectedSizeInput.value) {
            showValidationError('Please select a size before adding to cart.');
            return false;
        }
        
        // Validate quantity
        const quantity = parseInt(quantityInput.value);
        const maxQuantity = parseInt(quantityInput.getAttribute('max'));
        
        if (quantity <= 0) {
            showValidationError('Please select a valid quantity.');
            return false;
        }
        
        if (quantity > maxQuantity) {
            showValidationError(`Only ${maxQuantity} items available.`);
            return false;
        }
        
        // Submit via AJAX
        submitCartForm(this);
    });

    // AJAX form submission
    function submitCartForm(form) {
        const submitBtn = form.querySelector('.add-to-cart-btn');
        const originalText = submitBtn.innerHTML;
        
        // Show loading state
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Adding...';
        submitBtn.disabled = true;
        
        // Prepare form data
        const formData = new FormData(form);
        
        fetch('<?php echo e(route("cart.add")); ?>', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update cart badge if global function exists
                if (window.updateCartBadgeGlobal) {
                    window.updateCartBadgeGlobal(data.cartCount);
                }
                
                // Refresh offcanvas if it's open and function exists
                if (window.refreshCartOffcanvas) {
                    window.refreshCartOffcanvas();
                }
                
                // Show success state
                submitBtn.innerHTML = '<i class="fas fa-check me-2"></i>Added to Cart!';
                submitBtn.classList.add('btn-success');
                
                // Show success message with size info
                let message = data.message;
                showSuccessToast(message);
                
                // Reset button after delay
                setTimeout(() => {
                    submitBtn.innerHTML = originalText;
                    submitBtn.disabled = false;
                    submitBtn.classList.remove('btn-success');
                    updateAddToCartButton();
                }, 2000);
                
            } else {
                throw new Error(data.message || 'Failed to add product to cart');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showErrorToast(error.message || 'An error occurred. Please try again.');
            
            // Reset button
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
            updateAddToCartButton();
        });
    }

    // Helper functions
    function showValidationError(message) {
        showErrorToast(message);
        
        // Highlight the relevant field
        if (hasSize && !selectedSizeInput.value) {
            selectedSizeInfo.textContent = message;
            selectedSizeInfo.className = 'text-danger';
        }
    }

    function showSuccessToast(message) {
        showToast(message, 'success');
    }

    function showErrorToast(message) {
        showToast(message, 'error');
    }

    function showToast(message, type = 'success') {
        // Remove any existing toasts
        const existingToast = document.querySelector('.product-detail-toast');
        if (existingToast) {
            existingToast.remove();
        }
        
        const toast = document.createElement('div');
        toast.className = `alert alert-${type === 'success' ? 'success' : 'danger'} position-fixed product-detail-toast`;
        toast.style.cssText = `
            top: 100px; 
            right: 20px; 
            z-index: 9999; 
            min-width: 300px;
            max-width: 400px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            border-radius: 8px;
        `;
        toast.innerHTML = `
            <div class="d-flex align-items-center">
                <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'} me-2"></i>
                <span>${message}</span>
                <button type="button" class="btn-close ms-auto" onclick="this.parentElement.parentElement.remove()"></button>
            </div>
        `;
        
        document.body.appendChild(toast);
        
        // Auto-remove after 4 seconds
        setTimeout(() => {
            if (toast.parentNode) {
                toast.remove();
            }
        }, 4000);
    }
});

// Quantity controls (keep existing functions)
function increaseQuantity() {
    const input = document.getElementById('quantity');
    const currentValue = parseInt(input.value);
    const maxValue = parseInt(input.getAttribute('max'));
    
    if (currentValue < maxValue) {
        input.value = currentValue + 1;
    }
}

function decreaseQuantity() {
    const input = document.getElementById('quantity');
    const currentValue = parseInt(input.value);
    const minValue = parseInt(input.getAttribute('min'));
    
    if (currentValue > minValue) {
        input.value = currentValue - 1;
    }
}
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.main', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/jerenovvidimy/Documents/MiraTaraTest/resources/views/products/show.blade.php ENDPATH**/ ?>