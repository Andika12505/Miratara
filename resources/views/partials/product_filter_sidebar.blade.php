{{-- This sidebar will receive variables: $categories, $availableVibeAttributes, $availableGeneralTags, $availableOrigins, $request, $sortBy, $limit --}}

<div id="filterSidebar" class="filter-sidebar">
    <div class="sidebar-header">
        <h5 class="sidebar-title">Product Filters</h5>
        <button type="button" class="close-sidebar-btn" aria-label="Close Filter">
            &times;
        </button>
    </div>
    <div class="sidebar-body">
        {{-- Form Filter Standard & Vibe Attributes --}}
        <form id="filterFormSidebar" method="GET" action="{{ route('products.index') }}">
            {{-- Preserve existing sorting and pagination parameters --}}
            <input type="hidden" name="sort_by" value="{{ $sortBy }}">
            <input type="hidden" name="limit" value="{{ $limit }}">
            {{-- If you add text search feature in the future --}}
            {{-- <input type="hidden" name="search" value="{{ $request->query('search') }}"> --}}

            {{-- Category Filter --}}
            <div class="mb-4">
                <h6 class="filter-heading">Category</h6>
                @foreach ($categories as $category)
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="category_id" id="sidebar_category_{{ $category->id }}" value="{{ $category->id }}"
                               {{ ($request->query('category_id') == $category->id) ? 'checked' : '' }}>
                        <label class="form-check-label" for="sidebar_category_{{ $category->id }}">
                            {{ $category->name }}
                        </label>
                    </div>
                @endforeach
                @if ($request->query('category_id'))
                    <a href="{{ route('products.index', array_diff_key($request->query(), ['category_id' => ''])) }}" class="clear-filter-link">Clear Category</a>
                @endif
            </div>

            {{-- Price Filter --}}
            <div class="mb-4">
                <h6 class="filter-heading">Price (Rp)</h6>
                <div class="row g-2">
                    <div class="col-6">
                        <input type="number" name="price_min" class="form-control form-control-sm" placeholder="Min" value="{{ $request->query('price_min') }}">
                    </div>
                    <div class="col-6">
                        <input type="number" name="price_max" class="form-control form-control-sm" placeholder="Max" value="{{ $request->query('price_max') }}">
                    </div>
                </div>
            </div>

            {{-- Vibe Attributes Filter (from JSON metadata) --}}
            @foreach ($availableVibeAttributes as $attributeKey => $options)
                @if (!empty($options))
                    <div class="mb-4">
                        <h6 class="filter-heading">{{ ucfirst(str_replace('_', ' ', $attributeKey)) }}</h6>
                        @foreach ($options as $option)
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="{{ $attributeKey }}[]" id="sidebar_{{ $attributeKey }}_{{ $option }}" value="{{ $option }}"
                                       {{ in_array($option, (array)$request->query($attributeKey, [])) ? 'checked' : '' }}>
                                <label class="form-check-label" for="sidebar_{{ $attributeKey }}_{{ $option }}">
                                    {{ ucfirst(str_replace('_', ' ', $option)) }}
                                </label>
                            </div>
                        @endforeach
                        @if (!empty($request->query($attributeKey)))
                            <a href="{{ route('products.index', array_diff_key($request->query(), [$attributeKey => ''])) }}" class="clear-filter-link">Clear {{ ucfirst(str_replace('_', ' ', $attributeKey)) }}</a>
                        @endif
                    </div>
                @endif
            @endforeach

            {{-- General Tags Filter (from JSON metadata) --}}
            @if (!empty($availableGeneralTags))
                <div class="mb-4">
                    <h6 class="filter-heading">Other Tags</h6>
                    @foreach ($availableGeneralTags as $tagOption)
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="general_tags[]" id="sidebar_tag_{{ $tagOption }}" value="{{ $tagOption }}"
                                   {{ in_array($tagOption, (array)$request->query('general_tags', [])) ? 'checked' : '' }}>
                            <label class="form-check-label" for="sidebar_tag_{{ $tagOption }}">
                                {{ ucfirst(str_replace('_', ' ', $tagOption)) }}
                            </label>
                        </div>
                    @endforeach
                    @if (!empty($request->query('general_tags')))
                        <a href="{{ route('products.index', array_diff_key($request->query(), ['general_tags' => ''])) }}" class="clear-filter-link">Clear Tags</a>
                    @endif
                </div>
            @endif

            {{-- Apply Filters Button --}}
            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-primary">Apply Filters</button>
                <a href="{{ route('products.index', ['sort_by' => $sortBy, 'limit' => $limit]) }}" class="btn btn-outline-secondary">Reset All Filters</a>
            </div>
        </form>

        {{-- Find Your Vibe Section (Tier 2 CTA) --}}
        <div class="card shadow-sm border-0 mt-4 bg-light-pink text-center py-4">
            <div class="card-body">
                <h5 class="card-title">Find Your Vibe!</h5>
                <p class="card-text text-muted small">Answer a few questions to find your perfect style.</p>
                <div class="d-grid gap-2 mt-3">
                    <a href="{{ route('products.index', ['vibe_name' => 'beach_getaway']) }}" class="btn btn-vibe-primary">Beach Getaway Vibe</a>
                    <a href="{{ route('products.index', ['vibe_name' => 'elegant_evening']) }}" class="btn btn-vibe-primary">Elegant Evening Vibe</a>
                    <a href="{{ route('products.index', ['vibe_name' => 'sporty_active']) }}" class="btn btn-vibe-primary">Sporty Vibe</a>
                    <a href="{{ route('products.index', ['vibe_name' => 'daily_casual']) }}" class="btn btn-vibe-primary">Daily Casual Vibe</a>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="sidebarOverlay" class="sidebar-overlay"></div>