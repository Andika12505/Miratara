@extends('layouts.main')

@section('title', $title . ' - MiraTara Fashion')

@section('content')
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="text-center">
                <h1 class="mb-4">{{ $title }}</h1>
                <p class="lead text-muted mb-4">{{ $description }}</p>
                
                <div class="placeholder-content">
                    <div class="row">
                        <div class="col-md-6 mx-auto">
                            <div class="card border-0 shadow-sm">
                                <div class="card-body p-4">
                                    <h5 class="card-title">Coming Soon</h5>
                                    <p class="card-text">This page is currently under development. We're working hard to bring you the best experience.</p>
                                    <a href="{{ route('homepage') }}" class="btn btn-primary">Back to Home</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="mt-5">
                    <h6 class="text-muted">Need help right now?</h6>
                    <p class="small">
                        <a href="mailto:info@miratara.com" class="text-decoration-none">Email us at info@miratara.com</a> 
                        or 
                        <a href="tel:+62-xxx-xxx-xxx" class="text-decoration-none">call +62-xxx-xxx-xxx</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.placeholder-content {
    min-height: 300px;
    display: flex;
    align-items: center;
}

.card {
    transition: transform 0.3s ease;
}

.card:hover {
    transform: translateY(-5px);
}

h1 {
    font-size: 2.5rem;
    font-weight: 300;
    letter-spacing: 2px;
    color: #333;
}

.lead {
    font-size: 1.1rem;
    line-height: 1.6;
}
</style>
@endsection