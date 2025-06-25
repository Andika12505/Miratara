<?php $__env->startSection('title', 'Products - MiraTara Fashion'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid px-4">
    <!-- Sorting Options -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2 class="section-title">Our Products</h2>
                <div class="sorting-options">
                    <select id="sortSelect" class="form-select" onchange="handleSortChange()">
                        <option value="newest" <?php echo e($sortBy == 'newest' ? 'selected' : ''); ?>>Newest</option>
                        <option value="price_asc" <?php echo e($sortBy == 'price_asc' ? 'selected' : ''); ?>>Price: Low to High</option>
                        <option value="price_desc" <?php echo e($sortBy == 'price_desc' ? 'selected' : ''); ?>>Price: High to Low</option>
                        <option value="name_asc" <?php echo e($sortBy == 'name_asc' ? 'selected' : ''); ?>>Name: A to Z</option>
                        <option value="name_desc" <?php echo e($sortBy == 'name_desc' ? 'selected' : ''); ?>>Name: Z to A</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <!-- Products Grid -->
    <div class="row g-4">
        <?php $__empty_1 = true; $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <div class="col-lg-4 col-md-6 col-sm-12">
            <div class="product-card">
                <div class="product-image-container position-relative">
                    <!-- Add discount badge conditionally based on metadata or other logic -->
                    <?php if(isset($product->metadata['is_discounted']) && $product->metadata['is_discounted']): ?>
                    <div class="discount-badge">
                        <span>DISCOUNT</span>
                    </div>
                    <?php endif; ?>
                    
                    <img src="<?php echo e($product->image ? asset('images/' . $product->image) : asset('images/placeholder.jpg')); ?>" 
                         alt="<?php echo e($product->name); ?>" 
                         class="product-image">
                </div>
                
                <div class="product-info">
                    <h3 class="product-title"><?php echo e($product->name); ?></h3>
                    
                    <div class="product-pricing">
                        <?php if(isset($product->metadata['original_price']) && $product->metadata['original_price']): ?>
                        <span class="current-price">Rp <?php echo e(number_format($product->price, 0, ',', '.')); ?></span>
                        <span class="original-price">Rp <?php echo e(number_format($product->metadata['original_price'], 0, ',', '.')); ?></span>
                        <?php else: ?>
                        <span class="current-price">Rp <?php echo e(number_format($product->price, 0, ',', '.')); ?></span>
                        <?php endif; ?>
                    </div>
                    
                    <button class="add-to-bag-btn" 
                            data-product-id="<?php echo e($product->id); ?>"
                            <?php echo e($product->stock <= 0 ? 'disabled' : ''); ?>>
                        <?php echo e($product->stock <= 0 ? 'OUT OF STOCK' : 'ADD TO BAG'); ?>

                    </button>
                </div>
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <div class="col-12">
            <div class="text-center py-5">
                <p class="text-muted">No products available</p>
            </div>
        </div>
        <?php endif; ?>
    </div>

    <!-- Pagination -->
    <?php if($products->hasPages()): ?>
    <div class="row mt-5">
        <div class="col-12 d-flex justify-content-center">
            <?php echo e($products->appends(request()->query())->links()); ?>

        </div>
    </div>
    <?php endif; ?>
</div>

<?php $__env->startPush('styles'); ?>
<style>
/* Container adjustments */
.container-fluid {
    max-width: 1400px;
    margin: 0 auto;
}

.section-title {
    font-size: 24px;
    font-weight: 300;
    color: #333;
    margin-bottom: 0;
}

.sorting-options .form-select {
    width: 200px;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 14px;
    padding: 8px 12px;
}

/* Fix image styling for products page - Natural container height */
.product-card .product-image-container {
    position: relative;
    overflow: hidden;
    background: #f8f9fa;
    border-radius: 0;
    /* Container will naturally adjust to image height */
}

.product-card .product-image {
    width: 100%;
    height: auto; /* Natural image height */
    object-fit: contain; /* Show full image without cropping */
    display: block;
    transition: transform 0.3s ease;
    /* No height constraints - let image determine container height */
}

.product-card:hover .product-image {
    transform: scale(1.02); /* Subtle scale effect */
}

/* Flexible card layout */
.product-card {
    background: #fff;
    border-radius: 0;
    overflow: hidden;
    transition: transform 0.3s ease;
    border: none;
    box-shadow: none;
    height: auto; /* Let card adjust to content */
    display: flex;
    flex-direction: column;
}

.product-card:hover {
    transform: translateY(-5px);
}

.product-info {
    padding: 20px 0;
    text-align: center;
    flex-shrink: 0; /* Don't compress product info */
}

.product-title {
    font-size: 16px;
    font-weight: 400;
    color: #333;
    margin-bottom: 10px;
    line-height: 1.4;
}

.product-pricing {
    margin-bottom: 15px;
}

.current-price {
    font-size: 14px;
    font-weight: 600;
    color: #333;
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
    width: 100%; /* Make button full width */
    display: block; /* Ensure block display */
    margin: 0 auto; /* Center if needed */

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

/* Grid alignment - make columns equal height despite different image sizes */
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

/* Pagination styles */
.pagination {
    justify-content: center;
}

.pagination .page-link {
    color: #333;
    border: 1px solid #ddd;
    padding: 8px 12px;
}

.pagination .page-item.active .page-link {
    background-color: #333;
    border-color: #333;
}

.pagination .page-link:hover {
    color: #333;
    background-color: #f8f9fa;
    border-color: #ddd;
}

/* Responsive adjustments */
@media (max-width: 1200px) {
    .container-fluid {
        max-width: 100%;
    }
}

@media (max-width: 768px) {
    .container-fluid {
        padding-left: 15px;
        padding-right: 15px;
    }
    
    .d-flex.justify-content-between {
        flex-direction: column;
        gap: 15px;
    }
    
    .sorting-options .form-select {
        width: 100%;
    }
    
    .product-info {
        padding: 15px 0;
    }
    
    .product-title {
        font-size: 15px;
    }
    
    .section-title {
        font-size: 20px;
        text-align: center;
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
    
    .section-title {
        font-size: 18px;
    }
}
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add to bag functionality
    document.querySelectorAll('.add-to-bag-btn').forEach(button => {
        button.addEventListener('click', function() {
            if (this.disabled) return;
            
            const productId = this.getAttribute('data-product-id');
            const originalText = this.textContent;
            
            // Show loading state
            this.textContent = 'ADDING...';
            this.disabled = true;
            
            // Add your add to cart logic here
            fetch('/cart/add', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    product_id: productId,
                    quantity: 1
                })
            })
            .then(response => response.json())
            .then(data => {
                if(data.success) {
                    // Show success state
                    this.textContent = 'ADDED!';
                    this.style.background = '#28a745';
                    this.style.borderColor = '#28a745';
                    this.style.color = 'white';
                    
                    // Reset after 2 seconds
                    setTimeout(() => {
                        this.textContent = originalText;
                        this.disabled = false;
                        this.style.background = '';
                        this.style.borderColor = '';
                        this.style.color = '';
                    }, 2000);
                    
                    // Update cart count if you have one
                    updateCartCount();
                } else {
                    throw new Error(data.message || 'Failed to add product to bag.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred: ' + error.message);
                
                // Reset button
                this.textContent = originalText;
                this.disabled = false;
            });
        });
    });
});

// Handle sort change
function handleSortChange() {
    const sortSelect = document.getElementById('sortSelect');
    const currentUrl = new URL(window.location);
    
    // Update the sort_by parameter
    currentUrl.searchParams.set('sort_by', sortSelect.value);
    
    // Remove page parameter to go back to page 1
    currentUrl.searchParams.delete('page');
    
    // Navigate to new URL
    window.location.href = currentUrl.toString();
}

// Function to update cart count (if you have a cart counter in your layout)
function updateCartCount() {
    fetch('/cart/count', {
        method: 'GET',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        const cartCounter = document.querySelector('.cart-count');
        if (cartCounter && data.count !== undefined) {
            cartCounter.textContent = data.count;
        }
    })
    .catch(error => {
        console.error('Error updating cart count:', error);
    });
}

// Add smooth scroll for pagination if needed
document.addEventListener('click', function(e) {
    if (e.target.closest('.pagination a')) {
        // Optional: scroll to top when pagination is clicked
        setTimeout(() => {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        }, 100);
    }
});
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.main', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/jerenovvidimy/Documents/Shibal/MiraTara/resources/views/products/index.blade.php ENDPATH**/ ?>