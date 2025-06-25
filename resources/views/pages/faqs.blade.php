@extends('layouts.main')

@section('title', 'FAQs - MiraTara Fashion')

@section('content')
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="text-center mb-5">
                <h1 class="mb-3">FREQUENTLY ASKED QUESTIONS</h1>
                <p class="lead text-muted">Find answers to commonly asked questions</p>
            </div>

            <div class="faq-content">
                <div class="accordion" id="faqAccordion">
                    
                    <!-- Shipping & Delivery -->
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="shipping">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseShipping">
                                Shipping & Delivery
                            </button>
                        </h2>
                        <div id="collapseShipping" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                <strong>How long does delivery take?</strong><br>
                                Standard delivery within Indonesia takes 3-7 business days. Express delivery is available for 1-2 business days in major cities.
                                <br><br>
                                <strong>Do you ship internationally?</strong><br>
                                Currently, we only ship within Indonesia. International shipping will be available soon.
                                <br><br>
                                <strong>What are the shipping costs?</strong><br>
                                Shipping costs vary by location and delivery method. Free shipping is available for orders over Rp 500,000.
                            </div>
                        </div>
                    </div>

                    <!-- Returns & Exchanges -->
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="returns">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseReturns">
                                Returns & Exchanges
                            </button>
                        </h2>
                        <div id="collapseReturns" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                <strong>What is your return policy?</strong><br>
                                We accept returns within 30 days of purchase. Items must be unworn, with tags attached, and in original condition.
                                <br><br>
                                <strong>How do I return an item?</strong><br>
                                Contact our customer service team to initiate a return. We'll provide you with a prepaid return label and instructions.
                                <br><br>
                                <strong>Can I exchange for a different size?</strong><br>
                                Yes, exchanges are available subject to stock availability. The exchange process typically takes 5-10 business days.
                            </div>
                        </div>
                    </div>

                    <!-- Sizing & Fit -->
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="sizing">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSizing">
                                Sizing & Fit
                            </button>
                        </h2>
                        <div id="collapseSizing" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                <strong>How do I find my size?</strong><br>
                                Refer to our size chart available on each product page. If you're between sizes, we recommend sizing up.
                                <br><br>
                                <strong>Do your clothes run small or large?</strong><br>
                                Our sizing is generally true to size. Each product page includes specific fit information and model measurements.
                                <br><br>
                                <strong>Can I get sizing advice?</strong><br>
                                Absolutely! Contact our customer service team for personalized sizing recommendations.
                            </div>
                        </div>
                    </div>

                    <!-- Payment & Security -->
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="payment">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapsePayment">
                                Payment & Security
                            </button>
                        </h2>
                        <div id="collapsePayment" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                <strong>What payment methods do you accept?</strong><br>
                                We accept all major credit cards, bank transfers, and popular e-wallet services like GoPay, OVO, and DANA.
                                <br><br>
                                <strong>Is my payment information secure?</strong><br>
                                Yes, we use industry-standard SSL encryption to protect your payment information. We never store your credit card details.
                                <br><br>
                                <strong>Can I pay in installments?</strong><br>
                                Yes, installment options are available through selected payment partners for purchases above a certain amount.
                            </div>
                        </div>
                    </div>

                    <!-- Product Care -->
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="care">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseCare">
                                Product Care
                            </button>
                        </h2>
                        <div id="collapseCare" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                <strong>How should I care for my MiraTara pieces?</strong><br>
                                Care instructions are included with each item and on the product page. Generally, we recommend gentle washing and air drying.
                                <br><br>
                                <strong>Can I dry clean my items?</strong><br>
                                Many of our pieces are suitable for dry cleaning. Check the care label on each garment for specific instructions.
                                <br><br>
                                <strong>How do I remove stains?</strong><br>
                                Treat stains immediately with cold water. For persistent stains, consult a professional cleaner. Contact us for specific advice.
                            </div>
                        </div>
                    </div>

                </div>

                <div class="text-center mt-5">
                    <h5 class="mb-3">Still have questions?</h5>
                    <p class="text-muted mb-4">Our customer service team is here to help</p>
                    <a href="{{ route('contact') }}" class="btn btn-primary me-3">CONTACT US</a>
                    <a href="mailto:info@miratara.com" class="btn btn-outline-secondary">EMAIL US</a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.accordion-item {
    border: none;
    border-bottom: 1px solid #e9ecef;
}

.accordion-button {
    background: transparent;
    border: none;
    font-weight: 600;
    font-size: 1rem;
    letter-spacing: 0.5px;
    color: #333;
    padding: 1.5rem 0;
}

.accordion-button:not(.collapsed) {
    background: transparent;
    color: #333;
    box-shadow: none;
}

.accordion-button:focus {
    box-shadow: none;
    border: none;
}

.accordion-button::after {
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16' fill='%23333'%3e%3cpath fill-rule='evenodd' d='M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z'/%3e%3c/svg%3e");
}

.accordion-body {
    padding: 1.5rem 0;
    font-size: 0.9rem;
    line-height: 1.6;
    color: #666;
}

.btn-outline-secondary {
    border: 1px solid #333;
    color: #333;
    background: transparent;
}

.btn-outline-secondary:hover {
    background: #333;
    border-color: #333;
    color: white;
}
</style>
@endsection