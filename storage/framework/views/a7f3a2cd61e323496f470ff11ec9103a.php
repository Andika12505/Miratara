<!-- resources/views/home/index.blade.php -->


<?php $__env->startSection('title', 'Home - MiraTara Fashion'); ?>

<?php $__env->startSection('content'); ?>
<section id="home" class="home overflow-hidden">
    <div id="carouselExampleIndicators" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-indicators">
            <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="0"
                class="active" aria-current="true" aria-label="Slide 1"></button>
            <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="1"
                aria-label="Slide 2"></button>
        </div>
        <div class="carousel-inner">
            <div class="carousel-item active">
                <div class="home-banner home-banner-1" style="background-image: url('<?php echo e(asset('images/miaw1.png')); ?>');">
                    <div class="home-banner-text">
                        <a href="<?php echo e(route('products.index')); ?>" class="text-uppercase mt-4"></a>
                    </div>
                </div>
            </div>
            <div class="carousel-item">
                <div class="home-banner home-banner-2" style="background-image: url('<?php echo e(asset('images/miaw2.png')); ?>');">
                    <div class="home-banner-text">
                        <img src="<?php echo e(asset('images/logo1.png')); ?>" alt="Logo" height="90" />
                        <h2> ~70% Discount on all items today</h2>
                        <a href="<?php echo e(route('products.index')); ?>" class="btn-carousel text-uppercase mt-4" style="margin-left: 20px">Our Products</a>
                    </div>
                </div>
            </div>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
            <span class="ti-angle-left slider-icon"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
            <span class="ti-angle-right slider-icon"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>
</section>

<!-- NEW: Discover Your Vibe Section -->
<section id="vibe-discovery" class="vibe-discovery">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="vibe-header text-center mb-5">
                    <h2 class="vibe-main-title">Discover Your Vibe</h2>
                    <p class="vibe-subtitle">Find your perfect style in seconds. Each vibe is carefully curated to match your mood and occasion.</p>
                </div>
            </div>
        </div>
        
        <div class="row g-4" style="position: relative; z-index: 2;">
            <!-- Beach Getaway Vibe -->
            <div class="col-lg-6 col-md-12">
                <div class="vibe-card vibe-beach" onclick="location.href='<?php echo e(route('products.index', ['vibe_name' => 'beach_getaway'])); ?>'">
                    <div class="vibe-card-content">
                        <div class="vibe-icon">
                            <i class="fas fa-umbrella-beach"></i>
                        </div>
                        <h3 class="vibe-title">Beach Getaway</h3>
                        <p class="vibe-description">Casual & breezy pieces perfect for vacation vibes. Think cotton, linen, and relaxed silhouettes.</p>
                        <div class="vibe-tags">
                            <span class="vibe-tag">Casual</span>
                            <span class="vibe-tag">Bohemian</span>
                            <span class="vibe-tag">Bright Colors</span>
                        </div>
                        <div class="vibe-arrow">
                            <i class="fas fa-arrow-right"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Elegant Evening Vibe -->
            <div class="col-lg-6 col-md-12">
                <div class="vibe-card vibe-elegant" onclick="location.href='<?php echo e(route('products.index', ['vibe_name' => 'elegant_evening'])); ?>'">
                    <div class="vibe-card-content">
                        <div class="vibe-icon">
                            <i class="fas fa-cocktail"></i>
                        </div>
                        <h3 class="vibe-title">Elegant Evening</h3>
                        <p class="vibe-description">Sophisticated pieces for special occasions. Luxurious fabrics and timeless silhouettes.</p>
                        <div class="vibe-tags">
                            <span class="vibe-tag">Elegant</span>
                            <span class="vibe-tag">Glamour</span>
                            <span class="vibe-tag">Dark Tones</span>
                        </div>
                        <div class="vibe-arrow">
                            <i class="fas fa-arrow-right"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sporty Active Vibe -->
            <div class="col-lg-6 col-md-12">
                <div class="vibe-card vibe-sporty" onclick="location.href='<?php echo e(route('products.index', ['vibe_name' => 'sporty_active'])); ?>'">
                    <div class="vibe-card-content">
                        <div class="vibe-icon">
                            <i class="fas fa-dumbbell"></i>
                        </div>
                        <h3 class="vibe-title">Sporty Active</h3>
                        <p class="vibe-description">Performance-ready pieces that transition from gym to street. Comfort meets style.</p>
                        <div class="vibe-tags">
                            <span class="vibe-tag">Sporty</span>
                            <span class="vibe-tag">Water-resistant</span>
                            <span class="vibe-tag">Bright</span>
                        </div>
                        <div class="vibe-arrow">
                            <i class="fas fa-arrow-right"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Daily Casual Vibe -->
            <div class="col-lg-6 col-md-12">
                <div class="vibe-card vibe-casual" onclick="location.href='<?php echo e(route('products.index', ['vibe_name' => 'daily_casual'])); ?>'">
                    <div class="vibe-card-content">
                        <div class="vibe-icon">
                            <i class="fas fa-coffee"></i>
                        </div>
                        <h3 class="vibe-title">Daily Casual</h3>
                        <p class="vibe-description">Effortless everyday essentials. Comfortable pieces that work from home to office.</p>
                        <div class="vibe-tags">
                            <span class="vibe-tag">Everyday</span>
                            <span class="vibe-tag">Office</span>
                            <span class="vibe-tag">Earthy</span>
                        </div>
                        <div class="vibe-arrow">
                            <i class="fas fa-arrow-right"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- CTA to Products -->
        <div class="row mt-5" style="position: relative; z-index: 2;">
            <div class="col-12 text-center">
                <p class="browse-all-text">Can't decide? <a href="<?php echo e(route('products.index')); ?>" class="browse-all-link">Browse all products</a> or use our advanced filters.</p>
            </div>
        </div>
    </div>
</section>

<section id="products" class="products">
    <div class="container">
        <!-- Header Section -->
        <div class="row">
            <div class="col-sm-12">
                <div class="headline text-center mb-5">
                    <h2 class="pb-3 position-relative d-inline-block">Featured Products</h2>
                </div>
            </div>
        </div>
        
        <!-- Product Grid Component -->
        <?php if (isset($component)) { $__componentOriginal4d695489bf05cd3a8e675a0f0518ee14 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal4d695489bf05cd3a8e675a0f0518ee14 = $attributes; } ?>
<?php $component = App\View\Components\ProductGrid::resolve(['products' => $products,'showDiscount' => false,'useFormCart' => true,'emptyMessage' => 'No featured products available at the moment.','emptyButtonText' => 'View All Products','emptyButtonClass' => 'btn-carousel','buttonText' => 'ADD TO BAG','outOfStockText' => 'OUT OF STOCK'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
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
        
        <!-- View All Products Link (only show if products exist) -->
        <?php if($products->count() > 0): ?>
            <div class="row mt-4">
                <div class="col-12 text-center">
                    <a href="<?php echo e(route('products.index')); ?>" class="btn-carousel">View All Products</a>
                </div>
            </div>
        <?php endif; ?>
    </div>
</section>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
<style>
/* Homepage specific container adjustments */
.container {
    max-width: 1400px;
    margin: 0 auto;
}

/* === VIBE DISCOVERY SECTION === */
.vibe-discovery {
    padding: 40px 0;
    background-image: url('<?php echo e(asset('images/miaw7.jpeg')); ?>');
    background-repeat: no-repeat;
    background-size: cover;
    background-position: center;
    position: relative;
}

.vibe-discovery::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(255, 255, 255, 0.1);
    z-index: 1;
}

.vibe-header {
    position: relative;
    z-index: 2;
}

.vibe-main-title {
    font-size: 2.5rem;
    font-weight: 500;
    color: #fff;
    margin-bottom: 10px;
    position: relative;
    display: inline-block;
}

.vibe-main-title::before {
    content: "";
    width: 21%;
    height: 0.125rem;
    background-color: #ffc0cb;
    position: absolute;
    bottom: -10px;
    left: 50%;
    transform: translate(-50%, 0);
    border-radius: 0.635rem;
}

.vibe-main-title::after {
    content: '';
    position: absolute;
    bottom: -30px;
    left: 50%;
    transform: translateX(-50%);
    width: 200px;
    height: 40px;
    background: radial-gradient(ellipse at center, rgba(255, 192, 203, 0.3) 0%, rgba(255, 192, 203, 0.1) 50%, transparent 70%);
    z-index: -1;
}

.vibe-subtitle {
    font-size: 1rem;
    color: #fff;
    max-width: 600px;
    margin: 0 auto;
    line-height: 1.6;
    margin-top: 5px;
    margin-bottom: 10px;
    position: relative;
}

.vibe-subtitle::after {
    content: '';
    position: absolute;
    bottom: -20px;
    left: 50%;
    transform: translateX(-50%);
    width: 300px;
    height: 30px;
    background: radial-gradient(ellipse at center, rgba(255, 192, 203, 0.2) 0%, rgba(255, 192, 203, 0.05) 60%, transparent 80%);
    z-index: -1;
}

.vibe-card {
    background: rgba(255,255,255,0.9);
    border-radius: 4px;
    padding: 30px 25px;
    height: 240px;
    width: 97%;
    cursor: pointer;
    transition: all 0.3s ease;
    box-shadow: 0 8px 25px rgba(0,0,0,0.12), 0 4px 8px rgba(0,0,0,0.06);
    position: relative;
    overflow: hidden;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    margin-bottom: 2px;
    border: 1px solid #f0f0f0;
    z-index: 2;
}

.vibe-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.vibe-beach::before {
    background: linear-gradient(135deg, rgba(135, 206, 235, 0.15) 0%, rgba(32, 178, 170, 0.15) 100%);
}

.vibe-elegant::before {
    background: linear-gradient(135deg, rgba(44, 62, 80, 0.15) 0%, rgba(142, 68, 173, 0.15) 100%);
}

.vibe-sporty::before {
    background: linear-gradient(135deg, rgba(255, 107, 107, 0.15) 0%, rgba(78, 205, 196, 0.15) 100%);
}

.vibe-casual::before {
    background: linear-gradient(135deg, rgba(212, 175, 55, 0.15) 0%, rgba(143, 188, 143, 0.15) 100%);
}

.vibe-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 20px 40px rgba(0,0,0,0.18), 0 8px 16px rgba(0,0,0,0.08);
    border-color: #ffc0cb;
}

.vibe-card:hover::before {
    opacity: 1;
}

.vibe-card:hover .vibe-icon i {
    transform: scale(1.1);
}

.vibe-card:hover .vibe-arrow {
    transform: translateX(8px);
    opacity: 1;
}

.vibe-card-content {
    position: relative;
    z-index: 3;
    width: 100%;
}

.vibe-icon {
    margin-bottom: 20px;
}

.vibe-icon i {
    font-size: 2.5rem;
    transition: all 0.3s ease;
}

.vibe-beach .vibe-icon i { color: #5DADE2; }
.vibe-elegant .vibe-icon i { color: #AF7AC5; }
.vibe-sporty .vibe-icon i { color: #F1948A; }
.vibe-casual .vibe-icon i { color: #A9DFBF; }

.vibe-title {
    font-size: 1.5rem;
    font-weight: 600;
    color: #333;
    margin-bottom: 12px;
    transition: none;
}

.vibe-description {
    font-size: 0.9rem;
    color: #666;
    line-height: 1.5;
    margin-bottom: 15px;
    transition: none;
}

.vibe-tags {
    display: flex;
    flex-wrap: wrap;
    gap: 6px;
    margin-bottom: 10px;
}

.vibe-tag {
    background: rgba(255, 192, 203, 0.2);
    color: #666;
    padding: 4px 10px;
    border-radius: 4px;
    font-size: 0.75rem;
    font-weight: 500;
    border: 1px solid rgba(255, 192, 203, 0.3);
}

.vibe-arrow {
    position: absolute;
    bottom: 25px;
    right: 25px;
    opacity: 0;
    transition: all 0.3s ease;
}

.vibe-arrow i {
    font-size: 1.2rem;
    color: #ffc0cb;
}

.browse-all-text {
    font-size: 1rem;
    color: #fff;
}

.browse-all-link {
    color: #ffc0cb;
    text-decoration: none;
    font-weight: 500;
    transition: color 0.3s ease;
}

.browse-all-link:hover {
    color: #ff8fab;
    text-decoration: underline;
}

/* === SHARED PRODUCT CARD STYLES === */
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
    padding: 20px 0;
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
}

.product-pricing {
    margin-bottom: 15px;
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

/* Grid layout for both pages */
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

/* Keep existing carousel and navigation styles */
.navbar-brand {
    margin-right: auto;
}

.navbar-toggler {
    margin-left: 5px;
    order: 2;
}

.nav-login-button {
    background-color: #ffc0cb;
    color: #000;
    font-size: 14px;
    padding: 8px 20px;
    border-radius: 50px;
    text-decoration: none;
    transition: 0.3s;
}

.nav-login-button:hover {
    background-color: #ff8fab;
}

.navbar-toggler {
    border: none;
    font-size: 1.25rem;
}

.navbar-toggler:focus,
.btn-close:focus {
    box-shadow: none;
    outline: none;
}

.nav-link {
    color: #666777;
    font-weight: 500;
    position: relative;
}

.nav-link:hover,
.nav-link.active {
    color: #000;
}

.nav-link:before {
    content: "";
    position: absolute;
    bottom: 0;
    left: 50%;
    transform: translateX(-50%);
    width: 0;
    height: 2px;
    background-color: #ffc0cb;
    visibility: hidden;
    transition: 0.3s ease-in-out;
}

.nav-link:hover:before,
.nav-link.active::before {
    width: 100%;
    visibility: visible;
}

.home .home-banner-1,
.home .home-banner-2 {
    background-repeat: no-repeat;
    background-size: cover;
    background-position: 50% center;
    min-height: 650px;
    position: relative;
}

.home .home-banner .home-banner-text {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-110%, -50%);
}

.home .home-banner .home-banner-text h1 {
    font-size: 6rem;
}

.home .carousel-indicators [data-bs-target] {
    background-color: #000;
    width: 2.5rem;
    height: 0.15rem;
}

.home .carousel-control-next-icon,
.home .carousel-control-prev-icon {
    background: transparent;
}

.home .slider-icon {
    font-size: 3rem;
    font-weight: 600;
    color: #000;
}

.btn-carousel {
    background-color: #ffc0cb;
    color: #fff;
    font-size: 14px;
    padding: 8px 20px;
    border-radius: 4px;
    text-decoration: none;
    transition: 0.3s;
}

.btn-carousel:hover {
    background-color: #ff8fab;
}

img{
    max-width: 100%;
}

.products {
    padding-top: 1.1rem;
}

.headline h2::before {
    content: "";
    width: 21%;
    height: 0.125rem;
    background-color: #ffc0cb;
    position: absolute;
    bottom: 0;
    left: 50%;
    transform: translate(-50%, -50%);
    border-radius: 0.635rem;
}

/* Responsive adjustments */
@media (max-width: 1200px) {
    .container {
        max-width: 100%;
    }
}

@media (max-width: 991px) {
    .vibe-main-title {
        font-size: 2rem;
    }
    
    .vibe-card {
        height: 240px;
        padding: 25px 20px;
    }
    
    .vibe-title {
        font-size: 1.3rem;
    }
    
    .vibe-description {
        font-size: 0.85rem;
    }
}

@media (max-width: 768px) {
    .container {
        padding-left: 15px;
        padding-right: 15px;
    }
    
    .vibe-discovery {
        padding: 50px 0;
    }
    
    .vibe-main-title {
        font-size: 1.8rem;
    }
    
    .vibe-subtitle {
        font-size: 0.9rem;
    }
    
    .vibe-card {
        height: auto;
        min-height: 200px;
        padding: 20px 18px;
        margin-bottom: 20px;
    }
    
    .vibe-title {
        font-size: 1.2rem;
        margin-bottom: 10px;
    }
    
    .vibe-description {
        font-size: 0.8rem;
        margin-bottom: 12px;
    }
    
    .vibe-icon i {
        font-size: 2rem;
    }
    
    .product-info {
        padding: 15px 0;
    }
    
    .product-title {
        font-size: 15px;
    }
    
    .home .home-banner .home-banner-text h1 {
        font-size: 4rem;
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
    
    .vibe-discovery {
        padding: 40px 0;
    }
    
    .vibe-main-title {
        font-size: 1.5rem;
    }
    
    .vibe-card {
        padding: 18px 15px;
        min-height: 180px;
    }
    
    .vibe-icon i {
        font-size: 1.8rem;
    }
    
    .vibe-title {
        font-size: 1.1rem;
        margin-bottom: 8px;
    }
    
    .vibe-description {
        font-size: 0.75rem;
    }
    
    .vibe-tag {
        font-size: 0.7rem;
        padding: 3px 8px;
    }
    
    .home .home-banner .home-banner-text h1 {
        font-size: 3rem;
    }
}
</style>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.main', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/jerenovvidimy/Documents/MiraTaraTest/resources/views/home/index.blade.php ENDPATH**/ ?>