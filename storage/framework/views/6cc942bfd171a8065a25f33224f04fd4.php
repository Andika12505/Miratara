<?php $__env->startSection('title', 'Home - MiraTara Fashion'); ?>

<?php $__env->startSection('content'); ?>
<?php if(auth()->guard()->check()): ?>
    <p>Anda login sebagai: <?php echo e(Auth::user()->email); ?></p>
    <p>Apakah admin: <?php echo e(Auth::user()->is_admin ? 'Ya' : 'Tidak'); ?></p>
<?php else: ?>
    <p>Anda belum login.</p>
<?php endif; ?>
    <section id="home" class="home overflow-hidden">
      <div
        id="carouselExampleIndicators"
        class="carousel slide"
        data-bs-ride="carousel"
      >
        <div class="carousel-indicators">
          <button
            type="button"
            data-bs-target="#carouselExampleIndicators"
            data-bs-slide-to="0"
            class="active"
            aria-current="true"
            aria-label="Slide 1"
          ></button>
          <button
            type="button"
            data-bs-target="#carouselExampleIndicators"
            data-bs-slide-to="1"
            aria-label="Slide 2"
          ></button>
        </div>
        <div class="carousel-inner">
          <div class="carousel-item active">
            <div class="home-banner home-banner-1" style="background-image: url('<?php echo e(asset('images/miaw1.png')); ?>');">
              <div class="home-banner-text">
                <a href="#" class="text-uppercase mt-4"></a>
              </div>
            </div>
          </div>

          <div class="carousel-item">
            <div class="home-banner home-banner-2" style="background-image: url('<?php echo e(asset('images/miaw2.png')); ?>');">
              <div class="home-banner-text">
                <h1>Miaw</h1>
                <h2>100% Discount For This All Day</h2>
                <a href="#" class="btn-carousel text-uppercase mt-4"
                  >Our Product</a
                >
              </div>
            </div>
          </div>
        </div>
        <button
          class="carousel-control-prev"
          type="button"
          data-bs-target="#carouselExampleIndicators"
          data-bs-slide="prev"
        >
          <span class="ti-angle-left slider-icon"></span>
          <span class="visually-hidden">Previous</span>
        </button>
        <button
          class="carousel-control-next"
          type="button"
          data-bs-target="#carouselExampleIndicators"
          data-bs-slide="next"
        >
        <span class="ti-angle-right slider-icon"></span>
          <span class="visually-hidden">Next</span>
        </button>
      </div>
    </section>

    <section id="products" class="products">
      <div class="container">
        <div class="row">
          <div class="col-sm-12">
            <div class="headline text-center mb-5">
              <h2 class="pb-3 position-relative d-idline-block">OUR PRODUCT</h2>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-sm-6 col-lg-4">
            <a href="#" class="d-block text-center mb-4">
              <div class="product-list">
                <div class="product-image position-relative">
                  <span class="sale">Discount</span>
                  <img
                    src="<?php echo e(asset('images/a1.png')); ?>"
                    alt="products"
                    class="img-fluid product-image-first"
                  />
                  <img
                    src="<?php echo e(asset('images/a2.png')); ?>"
                    alt="products"
                    class="img-fluid product-image-secondary"
                  />
                </div>
                <div class="product-name pt-3">
                  <h3 class="text-capitalize">Suvi Cotton Midi Dress</h3>
                  <p class="mb-0 amount">Rp 500.000 <del>Rp 760.000</del></p>
                  <div class="py-1"></div>
                  <button type="button" class="add_to_card">ADD TO BAG</button>
                </div>
              </div>
            </a>
          </div>

          <div class="col-sm-6 col-lg-4">
            <a href="#" class="d-block text-center mb-4">
              <div class="product-list">
                <div class="product-image position-relative">
                  <span class="sale">Discount</span>
                  <img
                    src="<?php echo e(asset('images/a3.png')); ?>"
                    alt="products"
                    class="img-fluid product-image-first"
                  />
                  <img
                    src="<?php echo e(asset('images/a4.png')); ?>"
                    alt="products"
                    class="img-fluid product-image-secondary"
                  />
                </div>
                <div class="product-name pt-3">
                  <h3 class="text-capitalize">Norma Maxi Dress</h3>
                  <p class="mb-0 amount">Rp 600.000 <del>Rp 900.000</del></p>
                  <div class="py-1"></div>
                  <button type="button" class="add_to_card">ADD TO BAG</button>
                </div>
              </div>
            </a>
          </div>

          <div class="col-sm-6 col-lg-4">
            <a href="#" class="d-block text-center mb-4">
              <div class="product-list">
                <div class="product-image position-relative">
                  <span class="sale">Discount</span>
                  <img
                    src="<?php echo e(asset('images/a5.png')); ?>"
                    alt="products"
                    class="img-fluid product-image-first"
                  />
                  <img
                    src="<?php echo e(asset('images/a6.png')); ?>"
                    alt="products"
                    class="img-fluid product-image-secondary"
                  />
                </div>
                <div class="product-name pt-3">
                  <h3 class="text-capitalize">
                    Chessie Heritage Cotton Maxi Dress
                  </h3>
                  <p class="mb-0 amount">Rp 650.000 <del>Rp 850.000</del></p>
                  <div class="py-1"></div>
                  <button type="button" class="add_to_card">ADD TO BAG</button>
                </div>
              </div>
            </a>
          </div>

          <div class="col-sm-6 col-lg-4">
            <a href="#" class="d-block text-center mb-4">
              <div class="product-list">
                <div class="product-image position-relative">
                  <img
                    src="<?php echo e(asset('images/a7.png')); ?>"
                    alt="products"
                    class="img-fluid product-image-first"
                  />
                  <img
                    src="<?php echo e(asset('images/a8.png')); ?>"
                    alt="products"
                    class="img-fluid product-image-secondary"
                  />
                </div>
                <div class="product-name pt-3">
                  <h3 class="text-capitalize">
                    Rialto Fragrance Print Maxi Dress
                  </h3>
                  <p class="mb-0 amount">Rp 950.000</p>
                  <div class="py-1"></div>
                  <button type="button" class="add_to_card">ADD TO BAG</button>
                </div>
              </div>
            </a>
          </div>

          <div class="col-sm-6 col-lg-4">
            <a href="#" class="d-block text-center mb-4">
              <div class="product-list">
                <div class="product-image position-relative">
                  <img
                    src="<?php echo e(asset('images/a9.png')); ?>"
                    alt="products"
                    class="img-fluid product-image-first"
                  />
                  <img
                    src="<?php echo e(asset('images/a10.png')); ?>"
                    alt="products"
                    class="img-fluid product-image-secondary"
                  />
                </div>
                <div class="product-name pt-3">
                  <h3 class="text-capitalize">Ryan Catalina Lace Maxi Dress</h3>
                  <p class="mb-0 amount">Rp 950.000</p>
                  <div class="py-1"></div>
                  <button type="button" class="add_to_card">ADD TO BAG</button>
                </div>
              </div>
            </a>
          </div>

          <div class="col-sm-6 col-lg-4">
            <a href="#" class="d-block text-center mb-4">
              <div class="product-list">
                <div class="product-image position-relative">
                  <img
                    src="<?php echo e(asset('images/a11.png')); ?>"
                    alt="products"
                    class="img-fluid product-image-first"
                  />
                  <img
                    src="<?php echo e(asset('images/a12.png')); ?>"
                    alt="products"
                    class="img-fluid product-image-secondary"
                  />
                </div>
                <div class="product-name pt-3">
                  <h3 class="text-capitalize">Rialto Pastel Maxi Dress</h3>
                  <p class="mb-0 amount">Rp 900.000</p>
                  <div class="py-1"></div>
                  <button type="button" class="add_to_card">ADD TO BAG</button>
                </div>
              </div>
            </a>
          </div>
        </div>
      </div>
    </section>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.main', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\RudalJawa\resources\views/home/index.blade.php ENDPATH**/ ?>