@extends('admin.layout.template')

@section('content')
    <div class="content-page">
        <div class="container-fluid">
            <div class="page-title-head d-flex align-items-center">
                <div class="flex-grow-1">
                    <h4 class="page-main-title m-0">Edit Subscription Plan</h4>
                </div>

                <div class="text-end">
                    <ol class="breadcrumb m-0 py-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">OTT Admin</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.subscription-plans.index') }}">Plans</a></li>
                        <li class="breadcrumb-item active">Edit Plan</li>
                    </ol>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-header justify-content-between">
                            <h4 class="card-title">Plan Information</h4>
                            <a href="{{ route('admin.subscription-plans.index') }}" class="btn btn-sm btn-secondary">
                                <i class="ti ti-arrow-left me-1"></i> Back to List
                            </a>
                        </div>

                        <div class="card-body">
                            @if ($errors->any())
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <strong>Whoops!</strong> There were some problems with your input.
                                    <ul class="mb-0 mt-2">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"
                                        aria-label="Close"></button>
                                </div>
                            @endif

                            <form action="{{ route('admin.subscription-plans.update', $plan->id) }}" method="POST">
                                @csrf
                                @method('PUT')

                                <div class="row">
                                    <!-- Platform Selection -->
                                    <div class="col-md-6 mb-3">
                                        <label for="platform_id" class="form-label">Platform <span
                                                class="text-danger">*</span></label>
                                        <select class="form-select @error('platform_id') is-invalid @enderror"
                                            id="platform_id" name="platform_id" required>
                                            <option value="">Select Platform</option>
                                            @foreach ($platforms as $platform)
                                                <option value="{{ $platform->id }}"
                                                    {{ old('platform_id', $plan->platform_id) == $platform->id ? 'selected' : '' }}>
                                                    {{ $platform->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('platform_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Plan Name -->
                                    <div class="col-md-6 mb-3">
                                        <label for="name" class="form-label">Plan Name <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                                            id="name" name="name" value="{{ old('name', $plan->name) }}"
                                            placeholder="e.g., Mobile, Basic, Premium" required>
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Duration -->
                                    <div class="col-md-6 mb-3">
                                        <label for="duration_months" class="form-label">Duration (Months) <span
                                                class="text-danger">*</span></label>
                                        <select class="form-select @error('duration_months') is-invalid @enderror"
                                            id="duration_months" name="duration_months" required>
                                            <option value="">Select Duration</option>
                                            <option value="1"
                                                {{ old('duration_months', $plan->duration_months) == 1 ? 'selected' : '' }}>
                                                1 Month</option>
                                            <option value="3"
                                                {{ old('duration_months', $plan->duration_months) == 3 ? 'selected' : '' }}>
                                                3 Months</option>
                                            <option value="6"
                                                {{ old('duration_months', $plan->duration_months) == 6 ? 'selected' : '' }}>
                                                6 Months</option>
                                            <option value="12"
                                                {{ old('duration_months', $plan->duration_months) == 12 ? 'selected' : '' }}>
                                                12 Months</option>
                                        </select>
                                        @error('duration_months')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Original Price -->
                                    <div class="col-md-6 mb-3">
                                        <label for="original_price" class="form-label">Original Price (₹) <span
                                                class="text-danger">*</span></label>
                                        <input type="number" step="0.01"
                                            class="form-control @error('original_price') is-invalid @enderror"
                                            id="original_price" name="original_price"
                                            value="{{ old('original_price', $plan->original_price) }}" placeholder="0.00"
                                            required>
                                        @error('original_price')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Selling Price -->
                                    <div class="col-md-6 mb-3">
                                        <label for="selling_price" class="form-label">Selling Price (₹) <span
                                                class="text-danger">*</span></label>
                                        <input type="number" step="0.01"
                                            class="form-control @error('selling_price') is-invalid @enderror"
                                            id="selling_price" name="selling_price"
                                            value="{{ old('selling_price', $plan->selling_price) }}" placeholder="0.00"
                                            required>
                                        @error('selling_price')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Discount Percentage -->
                                    <div class="col-md-6 mb-3">
                                        <label for="discount_percentage" class="form-label">Discount (%)</label>
                                        <input type="number" step="0.01"
                                            class="form-control @error('discount_percentage') is-invalid @enderror"
                                            id="discount_percentage" name="discount_percentage"
                                            value="{{ old('discount_percentage', $plan->discount_percentage) }}"
                                            placeholder="0.00" readonly>
                                        <small class="text-muted">Auto-calculated based on prices</small>
                                        @error('discount_percentage')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Max Screens -->
                                    <div class="col-md-6 mb-3">
                                        <label for="max_screens" class="form-label">Max Screens <span
                                                class="text-danger">*</span></label>
                                        <input type="number"
                                            class="form-control @error('max_screens') is-invalid @enderror"
                                            id="max_screens" name="max_screens"
                                            value="{{ old('max_screens', $plan->max_screens) }}" min="1"
                                            placeholder="1" required>
                                        @error('max_screens')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Quality -->
                                    <div class="col-md-6 mb-3">
                                        <label for="quality" class="form-label">Video Quality</label>
                                        <select class="form-select @error('quality') is-invalid @enderror" id="quality"
                                            name="quality">
                                            <option value="">Select Quality</option>
                                            <option value="SD"
                                                {{ old('quality', $plan->quality) == 'SD' ? 'selected' : '' }}>SD (480p)
                                            </option>
                                            <option value="HD"
                                                {{ old('quality', $plan->quality) == 'HD' ? 'selected' : '' }}>HD (720p)
                                            </option>
                                            <option value="Full HD"
                                                {{ old('quality', $plan->quality) == 'Full HD' ? 'selected' : '' }}>Full HD
                                                (1080p)</option>
                                            <option value="4K"
                                                {{ old('quality', $plan->quality) == '4K' ? 'selected' : '' }}>4K Ultra HD
                                            </option>
                                        </select>
                                        @error('quality')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Stock Available -->
                                    <div class="col-md-6 mb-3">
                                        <label for="stock_available" class="form-label">Stock Available <span
                                                class="text-danger">*</span></label>
                                        <input type="number"
                                            class="form-control @error('stock_available') is-invalid @enderror"
                                            id="stock_available" name="stock_available"
                                            value="{{ old('stock_available', $plan->stock_available) }}" min="0"
                                            placeholder="0" required>
                                        @error('stock_available')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Description -->
                                    <div class="col-md-12 mb-3">
                                        <label for="description" class="form-label">Description</label>
                                        <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description"
                                            rows="3" placeholder="Enter plan description">{{ old('description', $plan->description) }}</textarea>
                                        @error('description')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Features -->
                                    <div class="col-md-12 mb-3">
                                        <label class="form-label">Features</label>
                                        <div id="features-container">
                                            @php
                                                $features = old('features', $plan->features ?? []);
                                                if (is_string($features)) {
                                                    $features = json_decode($features, true) ?? [];
                                                }
                                            @endphp

                                            @if (count($features) > 0)
                                                @foreach ($features as $feature)
                                                    <div class="input-group mb-2 feature-item">
                                                        <input type="text" class="form-control" name="features[]"
                                                            value="{{ $feature }}" placeholder="Enter feature">
                                                        <button type="button" class="btn btn-danger remove-feature">
                                                            <i class="ti ti-x"></i>
                                                        </button>
                                                    </div>
                                                @endforeach
                                            @else
                                                <div class="input-group mb-2 feature-item">
                                                    <input type="text" class="form-control" name="features[]"
                                                        placeholder="Enter feature">
                                                    <button type="button" class="btn btn-danger remove-feature">
                                                        <i class="ti ti-x"></i>
                                                    </button>
                                                </div>
                                            @endif
                                        </div>
                                        <button type="button" class="btn btn-sm btn-outline-primary" id="add-feature">
                                            <i class="ti ti-plus me-1"></i> Add Feature
                                        </button>
                                    </div>

                                    <!-- Status -->
                                    <div class="col-md-6 mb-3">
                                        <label for="status" class="form-label">Status <span
                                                class="text-danger">*</span></label>
                                        <select class="form-select @error('status') is-invalid @enderror" id="status"
                                            name="status" required>
                                            <option value="active"
                                                {{ old('status', $plan->status) == 'active' ? 'selected' : '' }}>Active
                                            </option>
                                            <option value="inactive"
                                                {{ old('status', $plan->status) == 'inactive' ? 'selected' : '' }}>Inactive
                                            </option>
                                        </select>
                                        @error('status')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Is Active -->
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Is Active?</label>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="is_active"
                                                name="is_active" value="1"
                                                {{ old('is_active', $plan->is_active) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="is_active">
                                                Enable this plan
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div class="text-end">
                                    <a href="{{ route('admin.subscription-plans.index') }}"
                                        class="btn btn-secondary me-2">
                                        <i class="ti ti-x me-1"></i> Cancel
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="ti ti-device-floppy me-1"></i> Update Plan
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Price Summary</h4>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-muted">Original Price:</span>
                                    <span class="fw-semibold"
                                        id="summary-original">₹{{ number_format($plan->original_price, 2) }}</span>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-muted">Selling Price:</span>
                                    <span class="fw-semibold text-success"
                                        id="summary-selling">₹{{ number_format($plan->selling_price, 2) }}</span>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <span class="text-muted">You Save:</span>
                                    <span class="fw-semibold text-danger"
                                        id="summary-save">₹{{ number_format($plan->original_price - $plan->selling_price, 2) }}
                                        ({{ number_format($plan->discount_percentage, 0) }}%)</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Plan Statistics</h4>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <div class="d-flex justify-content-between mb-1">
                                    <span class="text-muted">Created:</span>
                                    <span class="fw-semibold">{{ $plan->created_at->format('d M, Y') }}</span>
                                </div>
                            </div>
                            <div class="mb-3">
                                <div class="d-flex justify-content-between mb-1">
                                    <span class="text-muted">Last Updated:</span>
                                    <span class="fw-semibold">{{ $plan->updated_at->format('d M, Y') }}</span>
                                </div>
                            </div>
                            <div class="mb-0">
                                <div class="d-flex justify-content-between mb-1">
                                    <span class="text-muted">Plan ID:</span>
                                    <span class="fw-semibold">#{{ $plan->id }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Quick Tips</h4>
                        </div>
                        <div class="card-body">
                            <ul class="list-unstyled mb-0">
                                <li class="mb-2">
                                    <i class="ti ti-check text-success me-2"></i>
                                    Set competitive pricing for better sales
                                </li>
                                <li class="mb-2">
                                    <i class="ti ti-check text-success me-2"></i>
                                    Add detailed features to attract customers
                                </li>
                                <li class="mb-2">
                                    <i class="ti ti-check text-success me-2"></i>
                                    Keep stock updated regularly
                                </li>
                                <li class="mb-0">
                                    <i class="ti ti-check text-success me-2"></i>
                                    Discount is auto-calculated from prices
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <!-- container -->

        <!-- Footer Start -->
        <footer class="footer">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-6 text-center text-md-start">
                        © {{ date('Y') }} OTT Subscription Platform
                    </div>
                    <div class="col-md-6">
                        <div class="text-md-end d-none d-md-block">
                            Powered by Laravel
                        </div>
                    </div>
                </div>
            </div>
        </footer>
        <!-- end Footer -->
    </div>
@endsection

@push('scripts')
    <script>
        // Calculate discount percentage
        function calculateDiscount() {
            const original = parseFloat(document.getElementById('original_price').value) || 0;
            const selling = parseFloat(document.getElementById('selling_price').value) || 0;

            if (original > 0 && selling > 0 && original > selling) {
                const discount = ((original - selling) / original) * 100;
                document.getElementById('discount_percentage').value = discount.toFixed(2);

                // Update summary
                document.getElementById('summary-original').textContent = '₹' + original.toFixed(2);
                document.getElementById('summary-selling').textContent = '₹' + selling.toFixed(2);
                document.getElementById('summary-save').textContent = '₹' + (original - selling).toFixed(2) + ' (' +
                    discount.toFixed(0) + '%)';
            } else {
                document.getElementById('discount_percentage').value = '0.00';
                document.getElementById('summary-original').textContent = '₹' + original.toFixed(2);
                document.getElementById('summary-selling').textContent = '₹' + selling.toFixed(2);
                document.getElementById('summary-save').textContent = '₹0.00 (0%)';
            }
        }

        document.getElementById('original_price').addEventListener('input', calculateDiscount);
        document.getElementById('selling_price').addEventListener('input', calculateDiscount);

        // Add feature
        document.getElementById('add-feature').addEventListener('click', function() {
            const container = document.getElementById('features-container');
            const newFeature = document.createElement('div');
            newFeature.className = 'input-group mb-2 feature-item';
            newFeature.innerHTML = `
                <input type="text" class="form-control" name="features[]" placeholder="Enter feature">
                <button type="button" class="btn btn-danger remove-feature">
                    <i class="ti ti-x"></i>
                </button>
            `;
            container.appendChild(newFeature);
        });

        // Remove feature
        document.addEventListener('click', function(e) {
            if (e.target.closest('.remove-feature')) {
                const featureItems = document.querySelectorAll('.feature-item');
                if (featureItems.length > 1) {
                    e.target.closest('.feature-item').remove();
                } else {
                    alert('At least one feature field is required');
                }
            }
        });

        // Initial calculation
        calculateDiscount();
    </script>
@endpush
