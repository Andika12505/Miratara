@extends('layouts.main')

@section('title', 'Coming Soon - MiraTara Fashion')

@section('content')
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-lg-6 col-md-8">
            <div class="text-center">
                <h1 class="mb-4">COMING SOON</h1>
                <p class="lead text-muted mb-4">This page is currently under development.</p>
                
                <div class="coming-soon-content">
                    <div class="icon-wrapper mb-4">
                        <svg width="80" height="80" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="text-muted">
                            <circle cx="12" cy="12" r="10"></circle>
                            <polyline points="12,6 12,12 16,14"></polyline>
                        </svg>
                    </div>
                    
                    <h5 class="mb-3">We're Working On It</h5>
                    <p class="text-muted mb-4">
                        We're crafting something beautiful for you. This page will be available soon with amazing content and features.
                    </p>
                    
                    <div class="action-buttons">
                        <a href="{{ route('homepage') }}" class="btn btn-primary me-3">BACK TO HOME</a>
                        <a href="#" onclick="history.back()" class="btn btn-outline-secondary">GO BACK</a>
                    </div>
                </div>
                
                <div class="mt-5">
                    <h6 class="text-muted mb-3">Stay Updated</h6>
                    <p class="small text-muted">
                        Follow us on social media for the latest updates and announcements.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.coming-soon-content {
    min-height: 400px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 40px 0;
}

.icon-wrapper {
    opacity: 0.7;
}

h1 {
    font-size: 2.5rem;
    font-weight: 300;
    letter-spacing: 3px;
    color: #333;
}

h5 {
    font-weight: 500;
    letter-spacing: 1px;
    color: #333;
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

.action-buttons {
    margin: 30px 0;
}

@media (max-width: 768px) {
    h1 {
        font-size: 2rem;
        letter-spacing: 2px;
    }
    
    .action-buttons .btn {
        margin-bottom: 10px;
        width: 100%;
    }
    
    .action-buttons .me-3 {
        margin-right: 0 !important;
    }
}
</style>
@endsection