<footer class="site-footer">
    <!-- Newsletter Section -->
    <div class="newsletter-section">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-6 col-md-8">
                    <div class="newsletter-content text-center">
                        <h3 class="newsletter-title">NEWSLETTER</h3>
                        <p class="newsletter-desc">Receive news about MiraTara collections, new arrivals, events and sales.</p>
                        
                        <form class="newsletter-form" action="#" method="POST">
                            <?php echo csrf_field(); ?>
                            <div class="input-group">
                                <input type="email" 
                                       class="form-control newsletter-input" 
                                       placeholder="EMAIL" 
                                       name="email" 
                                       required>
                                <button type="submit" class="btn newsletter-btn">SUBSCRIBE</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Footer Content -->
    <div class="footer-main">
        <div class="container">
            <div class="row">
                <!-- Contact Us -->
                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="footer-section">
                        <h4 class="footer-title">CONTACT US</h4>
                        <ul class="footer-links">
                            <li><a href="#" class="footer-link">Live chat <span class="status offline">Offline</span></a></li>
                            <li><a href="tel:+62-xxx-xxx-xxx" class="footer-link">Call <span class="status offline">Offline</span></a></li>
                            <li><a href="mailto:info@miratara.com" class="footer-link">Email</a></li>
                        </ul>
                    </div>
                </div>

                <!-- Help -->
                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="footer-section">
                        <h4 class="footer-title">HELP</h4>
                        <ul class="footer-links">
                            <li><a href="#" class="footer-link">Contact us</a></li>
                            <li><a href="#" class="footer-link">Order status</a></li>
                            <li><a href="#" class="footer-link">Register a return</a></li>
                            <li><a href="#" class="footer-link">FAQs</a></li>
                        </ul>
                    </div>
                </div>

                <!-- Client Services -->
                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="footer-section">
                        <h4 class="footer-title">CLIENT SERVICES</h4>
                        <ul class="footer-links">
                            <li><a href="#" class="footer-link">MiraTara Services</a></li>
                            <li><a href="#" class="footer-link">Account</a></li>
                            <li><a href="#" class="footer-link">Find a store</a></li>
                            <li><a href="#" class="footer-link">Product care</a></li>
                            <li><a href="#" class="footer-link">Gift Cards</a></li>
                        </ul>
                    </div>
                </div>

                <!-- Company -->
                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="footer-section">
                        <h4 class="footer-title">COMPANY</h4>
                        <ul class="footer-links">
                            <li><a href="#" class="footer-link">About</a></li>
                            <li><a href="#" class="footer-link">Press</a></li>
                            <li><a href="#" class="footer-link">Careers</a></li>
                            <li><a href="#" class="footer-link">Sustainability</a></li>
                            <li><a href="#" class="footer-link">Legal & Privacy</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer Bottom -->
    <div class="footer-bottom">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-4 col-md-6">
                    <div class="footer-copyright">
                        <p>&copy; <?php echo e(date('Y')); ?> MIRATARA</p>
                        <a href="#" class="footer-link">Cookie settings</a>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6 text-center">
                    <div class="footer-social">
                        <a href="#" class="social-link" target="_blank">IG</a>
                        <a href="#" class="social-link" target="_blank">FB</a>
                        <a href="#" class="social-link" target="_blank">TW</a>
                        <a href="#" class="social-link" target="_blank">YT</a>
                        <a href="#" class="social-link" target="_blank">TK</a>
                        <a href="#" class="social-link" target="_blank">WA</a>
                    </div>
                </div>

                <div class="col-lg-4 col-md-12 text-lg-end text-center">
                    <div class="footer-shipping">
                        <a href="#" class="shipping-link">SHIPPING TO INDONESIA (BAHASA)</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>

<style>
/* Footer Styles */
.site-footer {
    background: #f8f9fa;
    border-top: 1px solid #e9ecef;
    margin-top: auto;
}

/* Newsletter Section */
.newsletter-section {
    padding: 60px 0;
    border-bottom: 1px solid #e9ecef;
}

.newsletter-title {
    font-size: 14px;
    font-weight: 600;
    letter-spacing: 2px;
    margin-bottom: 15px;
    color: #333;
}

.newsletter-desc {
    font-size: 13px;
    color: #666;
    margin-bottom: 30px;
    line-height: 1.6;
}

.newsletter-form .input-group {
    max-width: 400px;
    margin: 0 auto;
}

.newsletter-input {
    border: none;
    border-bottom: 1px solid #333;
    border-radius: 0;
    background: transparent;
    padding: 10px 0;
    font-size: 12px;
    letter-spacing: 1px;
    text-transform: uppercase;
}

.newsletter-input:focus {
    box-shadow: none;
    border-bottom: 2px solid #333;
    background: transparent;
}

.newsletter-btn {
    background: transparent;
    border: none;
    border-bottom: 1px solid #333;
    border-radius: 0;
    color: #333;
    font-size: 12px;
    font-weight: 600;
    letter-spacing: 1px;
    padding: 10px 15px;
    cursor: pointer;
    transition: all 0.3s ease;
}

.newsletter-btn:hover {
    background: #333;
    color: white;
}

/* Main Footer */
.footer-main {
    padding: 60px 0;
}

.footer-section {
    margin-bottom: 40px;
}

.footer-title {
    font-size: 12px;
    font-weight: 600;
    letter-spacing: 2px;
    margin-bottom: 25px;
    color: #333;
    text-transform: uppercase;
}

.footer-links {
    list-style: none;
    padding: 0;
    margin: 0;
}

.footer-links li {
    margin-bottom: 12px;
}

.footer-link {
    color: #666;
    text-decoration: none;
    font-size: 13px;
    transition: color 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.footer-link:hover {
    color: #333;
    text-decoration: underline;
}

.status {
    font-size: 11px;
    padding: 2px 8px;
    border-radius: 10px;
    background: #f5f5f5;
    color: #999;
}

.status.offline {
    background: #f5f5f5;
    color: #999;
}

.status.online {
    background: #d4edda;
    color: #155724;
}

/* Footer Bottom */
.footer-bottom {
    background: #fff;
    border-top: 1px solid #e9ecef;
    padding: 25px 0;
}

.footer-copyright p {
    margin: 0;
    font-size: 11px;
    color: #999;
    letter-spacing: 1px;
}

.footer-copyright .footer-link {
    font-size: 11px;
    color: #666;
    text-decoration: underline;
    margin-top: 5px;
    display: inline-block;
}

.footer-social {
    display: flex;
    justify-content: center;
    gap: 20px;
    flex-wrap: wrap;
}

.social-link {
    color: #666;
    text-decoration: none;
    font-size: 12px;
    font-weight: 500;
    letter-spacing: 1px;
    transition: color 0.3s ease;
}

.social-link:hover {
    color: #333;
    text-decoration: underline;
}

.shipping-link {
    color: #666;
    text-decoration: none;
    font-size: 11px;
    letter-spacing: 1px;
    transition: color 0.3s ease;
    text-transform: uppercase;
}

.shipping-link:hover {
    color: #333;
    text-decoration: underline;
}

/* Responsive */
@media (max-width: 768px) {
    .newsletter-section {
        padding: 40px 0;
    }
    
    .footer-main {
        padding: 40px 0;
    }
    
    .footer-section {
        margin-bottom: 30px;
    }
    
    .footer-social {
        margin: 20px 0;
    }
    
    .footer-bottom .row > div {
        text-align: center !important;
        margin-bottom: 15px;
    }
}

@media (max-width: 576px) {
    .newsletter-form .input-group {
        flex-direction: column;
    }
    
    .newsletter-btn {
        margin-top: 15px;
        border: 1px solid #333;
        border-radius: 0;
    }
}
</style><?php /**PATH C:\Users\Putu Aditya\Herd\Miratara\resources\views/components/footer.blade.php ENDPATH**/ ?>