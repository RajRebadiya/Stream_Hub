@extends('admin.layout.template')

@section('content')
    <div class="content-page">
        <div class="container-fluid">
            <div class="page-title-head d-flex align-items-center">
                <div class="flex-grow-1">
                    <h4 class="page-main-title m-0">Plan Details</h4>
                </div>

                <div class="text-end">
                    <ol class="breadcrumb m-0 py-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">OTT Admin</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.subscription-plans.index') }}">Plans</a></li>
                        <li class="breadcrumb-item active">View Plan</li>
                    </ol>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-header justify-content-between">
                            <h4 class="card-title">{{ $plan->name }}</h4>
                            <div>
                                <a href="{{ route('admin.subscription-plans.edit', $plan->id) }}"
                                    class="btn btn-sm btn-primary me-1">
                                    <i class="ti ti-edit me-1"></i> Edit Plan
                                </a>
                                <a href="{{ route('admin.subscription-plans.index') }}" class="btn btn-sm btn-secondary">
                                    <i class="ti ti-arrow-left me-1"></i> Back to List
                                </a>
                            </div>
                        </div>

                        <div class="card-body">
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <h5 class="text-muted mb-3">Basic Information</h5>
                                    <table class="table table-borderless">
                                        <tbody>
                                            <tr>
                                                <td class="text-muted" width="40%">Platform:</td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        @if ($plan->platform->logo)
                                                            <img src="{{ asset('storage/' . $plan->platform->logo) }}"
                                                                alt="{{ $plan->platform->name }}" height="30"
                                                                class="rounded me-2">
                                                        @endif
                                                        <span class="fw-semibold">{{ $plan->platform->name }}</span>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="text-muted">Plan Name:</td>
                                                <td class="fw-semibold">{{ $plan->name }}</td>
                                            </tr>
                                            <tr>
                                                <td class="text-muted">Duration:</td>
                                                <td>
                                                    <span class="badge bg-info">{{ $plan->duration_months }}
                                                        {{ $plan->duration_months == 1 ? 'Month' : 'Months' }}</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="text-muted">Max Screens:</td>
                                                <td>
                                                    <span class="badge bg-secondary">{{ $plan->max_screens }}
                                                        Screen{{ $plan->max_screens > 1 ? 's' : '' }}</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="text-muted">Video Quality:</td>
                                                <td>
                                                    @if ($plan->quality)
                                                        <span class="badge badge-soft-primary">{{ $plan->quality }}</span>
                                                    @else
                                                        <span class="text-muted">Not specified</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>

                                <div class="col-md-6">
                                    <h5 class="text-muted mb-3">Pricing & Stock</h5>
                                    <table class="table table-borderless">
                                        <tbody>
                                            <tr>
                                                <td class="text-muted" width="40%">Original Price:</td>
                                                <td>
                                                    <span
                                                        class="fw-semibold text-decoration-line-through">₹{{ number_format($plan->original_price, 2) }}</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="text-muted">Selling Price:</td>
                                                <td>
                                                    <span
                                                        class="fw-bold text-success fs-5">₹{{ number_format($plan->selling_price, 2) }}</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="text-muted">Discount:</td>
                                                <td>
                                                    @if ($plan->discount_percentage > 0)
                                                        <span
                                                            class="badge badge-soft-success">{{ number_format($plan->discount_percentage, 0) }}%
                                                            OFF</span>
                                                        <small class="text-muted ms-2">(Save
                                                            ₹{{ number_format($plan->original_price - $plan->selling_price, 2) }})</small>
                                                    @else
                                                        <span class="text-muted">No discount</span>
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="text-muted">Stock Available:</td>
                                                <td>
                                                    @if ($plan->stock_available > 0)
                                                        <span
                                                            class="badge badge-soft-success">{{ $plan->stock_available }}</span>
                                                    @else
                                                        <span class="badge badge-soft-danger">Out of Stock</span>
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="text-muted">Status:</td>
                                                <td>
                                                    @if ($plan->status === 'active')
                                                        <span class="badge badge-soft-success">Active</span>
                                                    @else
                                                        <span class="badge badge-soft-danger">Inactive</span>
                                                    @endif
                                                    @if ($plan->is_active)
                                                        <span class="badge badge-soft-success ms-1">
                                                            <i class="ti ti-check"></i> Enabled
                                                        </span>
                                                    @else
                                                        <span class="badge badge-soft-danger ms-1">
                                                            <i class="ti ti-x"></i> Disabled
                                                        </span>
                                                    @endif
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            @if ($plan->description)
                                <div class="mb-4">
                                    <h5 class="text-muted mb-3">Description</h5>
                                    <p class="text-muted">{{ $plan->description }}</p>
                                </div>
                            @endif

                            @if ($plan->features && count($plan->features) > 0)
                                <div class="mb-4">
                                    <h5 class="text-muted mb-3">Features</h5>
                                    <div class="row">
                                        @foreach ($plan->features as $feature)
                                            <div class="col-md-6">
                                                <div class="d-flex align-items-center mb-2">
                                                    <i class="ti ti-check-circle text-success me-2"></i>
                                                    <span>{{ $feature }}</span>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Plan Card Preview</h4>
                        </div>
                        <div class="card-body">
                            <div class="border rounded p-3">
                                <div class="text-center mb-3">
                                    @if ($plan->platform->logo)
                                        <img src="{{ asset('storage/' . $plan->platform->logo) }}"
                                            alt="{{ $plan->platform->name }}" height="40" class="mb-2">
                                    @endif
                                    <h5 class="mb-1">{{ $plan->name }}</h5>
                                    <small class="text-muted">{{ $plan->duration_months }}
                                        {{ $plan->duration_months == 1 ? 'Month' : 'Months' }}</small>
                                </div>

                                <div class="text-center mb-3">
                                    @if ($plan->discount_percentage > 0)
                                        <div class="mb-1">
                                            <span
                                                class="text-muted text-decoration-line-through">₹{{ number_format($plan->original_price, 2) }}</span>
                                        </div>
                                    @endif
                                    <h3 class="mb-0 text-success">₹{{ number_format($plan->selling_price, 2) }}</h3>
                                    @if ($plan->discount_percentage > 0)
                                        <span
                                            class="badge badge-soft-success">{{ number_format($plan->discount_percentage, 0) }}%
                                            OFF</span>
                                    @endif
                                </div>

                                @if ($plan->features && count($plan->features) > 0)
                                    <hr>
                                    <div class="mb-2">
                                        @foreach (array_slice($plan->features, 0, 3) as $feature)
                                            <div class="d-flex align-items-start mb-2">
                                                <i class="ti ti-check text-success me-2 mt-1"></i>
                                                <small>{{ $feature }}</small>
                                            </div>
                                        @endforeach
                                        @if (count($plan->features) > 3)
                                            <small class="text-muted">+ {{ count($plan->features) - 3 }} more
                                                features</small>
                                        @endif
                                    </div>
                                @endif
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
                                    <span class="text-muted">Plan ID:</span>
                                    <span class="fw-semibold">#{{ $plan->id }}</span>
                                </div>
                            </div>
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
                                    <span class="text-muted">Total Orders:</span>
                                    <span class="fw-semibold">0</span>
                                    <!-- Add actual count if you have orders relationship -->
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Actions</h4>
                        </div>
                        <div class="card-body">
                            <div class="d-grid gap-2">
                                <a href="{{ route('admin.subscription-plans.edit', $plan->id) }}"
                                    class="btn btn-primary">
                                    <i class="ti ti-edit me-1"></i> Edit Plan
                                </a>
                                <form action="{{ route('admin.subscription-plans.destroy', $plan->id) }}" method="POST"
                                    onsubmit="return confirm('Are you sure you want to delete this plan? This action cannot be undone.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger w-100">
                                        <i class="ti ti-trash me-1"></i> Delete Plan
                                    </button>
                                </form>
                            </div>
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
