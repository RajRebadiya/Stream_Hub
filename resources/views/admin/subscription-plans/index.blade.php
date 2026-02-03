@extends('admin.layout.template')

@section('content')
    <div class="content-page">
        <div class="container-fluid">
            <div class="page-title-head d-flex align-items-center">
                <div class="flex-grow-1">
                    <h4 class="page-main-title m-0">Subscription Plans</h4>
                </div>

                <div class="text-end">
                    <ol class="breadcrumb m-0 py-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">OTT Admin</a></li>
                        <li class="breadcrumb-item active">Subscription Plans</li>
                    </ol>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header justify-content-between">
                            <h4 class="card-title">All Subscription Plans</h4>
                            <a href="{{ route('admin.subscription-plans.create') }}" class="btn btn-sm btn-primary">
                                <i class="ti ti-plus me-1"></i> Add Plan
                            </a>
                        </div>

                        <div class="card-body p-0">
                            @if (session('success'))
                                <div class="alert alert-success alert-dismissible fade show m-3" role="alert">
                                    {{ session('success') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"
                                        aria-label="Close"></button>
                                </div>
                            @endif

                            @if (session('error'))
                                <div class="alert alert-danger alert-dismissible fade show m-3" role="alert">
                                    {{ session('error') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"
                                        aria-label="Close"></button>
                                </div>
                            @endif

                            <!-- Filter Section -->
                            <div class="p-3 border-bottom">
                                <form action="{{ route('admin.subscription-plans.index') }}" method="GET">
                                    <div class="row g-3">
                                        <div class="col-md-3">
                                            <label class="form-label">Platform</label>
                                            <select name="platform_id" class="form-select form-select-sm">
                                                <option value="">All Platforms</option>
                                                @foreach ($platforms as $platform)
                                                    <option value="{{ $platform->id }}"
                                                        {{ request('platform_id') == $platform->id ? 'selected' : '' }}>
                                                        {{ $platform->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label">Status</label>
                                            <select name="status" class="form-select form-select-sm">
                                                <option value="">All Status</option>
                                                <option value="active"
                                                    {{ request('status') == 'active' ? 'selected' : '' }}>
                                                    Active</option>
                                                <option value="inactive"
                                                    {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive
                                                </option>
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">Search</label>
                                            <input type="text" name="search" class="form-control form-control-sm"
                                                placeholder="Search by plan name..." value="{{ request('search') }}">
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label d-block">&nbsp;</label>
                                            <button type="submit" class="btn btn-sm btn-primary me-1">
                                                <i class="ti ti-filter"></i> Filter
                                            </button>
                                            <a href="{{ route('admin.subscription-plans.index') }}"
                                                class="btn btn-sm btn-secondary">
                                                <i class="ti ti-x"></i>
                                            </a>
                                        </div>
                                    </div>
                                </form>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-custom table-centered table-hover mb-0">
                                    <thead class="bg-light bg-opacity-25">
                                        <tr>
                                            <th>#</th>
                                            <th>Platform</th>
                                            <th>Plan Name</th>
                                            <th>Duration</th>
                                            <th>Pricing</th>
                                            <th>Discount</th>
                                            <th>Screens</th>
                                            <th>Quality</th>
                                            <th>Stock</th>
                                            <th>Status</th>
                                            <th>Active</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($plans as $plan)
                                            <tr>
                                                <td>{{ $loop->iteration + ($plans->currentPage() - 1) * $plans->perPage() }}
                                                </td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        @if ($plan->platform->logo)
                                                            <img src="{{ asset('storage/' . $plan->platform->logo) }}"
                                                                alt="{{ $plan->platform->name }}" height="25"
                                                                class="rounded me-2">
                                                        @endif
                                                        <span class="fw-semibold">{{ $plan->platform->name }}</span>
                                                    </div>
                                                </td>
                                                <td>
                                                    <h5 class="fs-14 mb-0">{{ $plan->name }}</h5>
                                                </td>
                                                <td>
                                                    <span class="badge bg-info">{{ $plan->duration_months }}
                                                        {{ $plan->duration_months == 1 ? 'Month' : 'Months' }}</span>
                                                </td>
                                                <td>
                                                    <div>
                                                        <span
                                                            class="text-success fw-bold">₹{{ number_format($plan->selling_price, 2) }}</span>
                                                        @if ($plan->original_price > $plan->selling_price)
                                                            <br>
                                                            <small
                                                                class="text-muted text-decoration-line-through">₹{{ number_format($plan->original_price, 2) }}</small>
                                                        @endif
                                                    </div>
                                                </td>
                                                <td>
                                                    @if ($plan->discount_percentage > 0)
                                                        <span
                                                            class="badge badge-soft-success">{{ number_format($plan->discount_percentage, 0) }}%
                                                            OFF</span>
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <span class="badge bg-secondary">{{ $plan->max_screens }}
                                                        Screen{{ $plan->max_screens > 1 ? 's' : '' }}</span>
                                                </td>
                                                <td>
                                                    @if ($plan->quality)
                                                        <span class="badge badge-soft-primary">{{ $plan->quality }}</span>
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($plan->stock_available > 0)
                                                        <span
                                                            class="badge badge-soft-success">{{ $plan->stock_available }}</span>
                                                    @else
                                                        <span class="badge badge-soft-danger">Out of Stock</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($plan->status === 'active')
                                                        <span class="badge badge-soft-success">Active</span>
                                                    @else
                                                        <span class="badge badge-soft-danger">Inactive</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($plan->is_active)
                                                        <span class="badge badge-soft-success">
                                                            <i class="ti ti-check"></i> Yes
                                                        </span>
                                                    @else
                                                        <span class="badge badge-soft-danger">
                                                            <i class="ti ti-x"></i> No
                                                        </span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="d-flex gap-1">
                                                        <a href="{{ route('admin.subscription-plans.show', $plan->id) }}"
                                                            class="btn btn-sm btn-soft-info" title="View">
                                                            <i class="ti ti-eye"></i>
                                                        </a>
                                                        <a href="{{ route('admin.subscription-plans.edit', $plan->id) }}"
                                                            class="btn btn-sm btn-soft-primary" title="Edit">
                                                            <i class="ti ti-edit"></i>
                                                        </a>
                                                        <form
                                                            action="{{ route('admin.subscription-plans.destroy', $plan->id) }}"
                                                            method="POST" class="d-inline"
                                                            onsubmit="return confirm('Are you sure you want to delete this plan?');">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-soft-danger"
                                                                title="Delete">
                                                                <i class="ti ti-trash"></i>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="12" class="text-center py-4">
                                                    <div class="py-4">
                                                        <i class="ti ti-database-off fs-1 text-muted"></i>
                                                        <p class="text-muted mb-0">No subscription plans found</p>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        @if ($plans->hasPages())
                            <div class="card-footer">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        Showing {{ $plans->firstItem() }} to {{ $plans->lastItem() }} of
                                        {{ $plans->total() }} entries
                                    </div>
                                    <div>
                                        {{ $plans->links() }}
                                    </div>
                                </div>
                            </div>
                        @endif
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
