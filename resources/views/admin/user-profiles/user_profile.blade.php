@extends('admin.layout.template')

@section('content')
    <div class="content-page">
        <div class="container-fluid">
            <div class="page-title-head d-flex align-items-center">
                <div class="flex-grow-1">
                    <h4 class="page-main-title m-0">My Profile</h4>
                </div>

                <div class="text-end">
                    <ol class="breadcrumb m-0 py-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">OTT Admin</a></li>
                        <li class="breadcrumb-item active">My Profile</li>
                    </ol>
                </div>
            </div>

            <div class="row">
                <!-- Profile Overview Card -->
                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-body text-center">
                            <div class="mb-3">
                                <img src="{{ asset('admin/assets/images/users/user-1.jpg') }}" alt="profile-image"
                                    class="rounded-circle avatar-xl" />
                            </div>

                            <h5 class="mb-1">{{ $user->name }}</h5>
                            <p class="text-muted mb-3">{{ $profile?->designation ?? 'Not Specified' }}</p>

                            <div class="d-grid gap-2">
                                <a href="{{ route('admin.user-profiles.edit', $user->id) }}"
                                    class="btn btn-primary btn-sm">
                                    <i class="ti ti-edit me-1"></i> Edit Profile
                                </a>
                            </div>

                            <hr class="my-4" />

                            <!-- Quick Stats -->
                            <div class="row">
                                <div class="col-6 border-end">
                                    <h6 class="mb-2">Orders</h6>
                                    <h4 class="mb-0">{{ $user->orders()->count() }}</h4>
                                </div>
                                <div class="col-6">
                                    <h6 class="mb-2">Subscriptions</h6>
                                    <h4 class="mb-0">{{ $user->subscriptions()->where('status', 'active')->count() }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Profile Completion -->
                    @if ($profile)
                        <div class="card mt-3">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Profile Completion</h5>
                            </div>
                            <div class="card-body">
                                @php
                                    $fields = ['phone', 'address', 'city', 'state', 'pincode'];
                                    $completed = collect($fields)->filter(fn($field) => !empty($profile->$field))->count();
                                    $percentage = ($completed / count($fields)) * 100;
                                @endphp
                                <div class="progress" role="progressbar" aria-valuenow="{{ (int) $percentage }}"
                                    aria-valuemin="0" aria-valuemax="100">
                                    <div class="progress-bar"
                                        style="width: {{ $percentage }}%; background-color: {{ $percentage >= 80 ? '#51cf66' : ($percentage >= 50 ? '#ffc107' : '#dc3545') }}">
                                    </div>
                                </div>
                                <p class="mt-2 mb-0 text-muted">{{ (int) $percentage }}% Complete</p>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Profile Details -->
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Account Information</h5>
                        </div>
                        <div class="card-body">
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <h6 class="text-muted mb-2">Full Name</h6>
                                    <h5 class="mb-0">{{ $user->name }}</h5>
                                </div>
                                <div class="col-md-6">
                                    <h6 class="text-muted mb-2">Email</h6>
                                    <h5 class="mb-0">
                                        {{ $user->email }}
                                        @if ($user->email_verified_at)
                                            <span class="badge badge-soft-success ms-2">
                                                <i class="ti ti-check"></i> Verified
                                            </span>
                                        @else
                                            <span class="badge badge-soft-warning ms-2">
                                                <i class="ti ti-alert"></i> Not Verified
                                            </span>
                                        @endif
                                    </h5>
                                </div>
                            </div>

                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <h6 class="text-muted mb-2">Mobile Number</h6>
                                    <h5 class="mb-0">{{ $user->mobile ?? 'Not Provided' }}</h5>
                                </div>
                                <div class="col-md-6">
                                    <h6 class="text-muted mb-2">Joined Date</h6>
                                    <h5 class="mb-0">{{ $user->created_at->format('d M, Y') }}</h5>
                                </div>
                            </div>

                            <hr class="my-4" />

                            @if ($profile)
                                <h5 class="mb-4">Additional Information</h5>

                                <div class="row mb-4">
                                    <div class="col-md-6">
                                        <h6 class="text-muted mb-2">Designation</h6>
                                        <h5 class="mb-0">{{ $profile->designation ?? 'Not Specified' }}</h5>
                                    </div>
                                    <div class="col-md-6">
                                        <h6 class="text-muted mb-2">Phone</h6>
                                        <h5 class="mb-0">{{ $profile->phone ?? 'Not Provided' }}</h5>
                                    </div>
                                </div>

                                <div class="row mb-4">
                                    <div class="col-md-6">
                                        <h6 class="text-muted mb-2">Address</h6>
                                        <h5 class="mb-0">{{ $profile->address ?? 'Not Provided' }}</h5>
                                    </div>
                                    <div class="col-md-6">
                                        <h6 class="text-muted mb-2">City</h6>
                                        <h5 class="mb-0">{{ $profile->city ?? 'Not Specified' }}</h5>
                                    </div>
                                </div>

                                <div class="row mb-4">
                                    <div class="col-md-6">
                                        <h6 class="text-muted mb-2">State</h6>
                                        <h5 class="mb-0">{{ $profile->state ?? 'Not Specified' }}</h5>
                                    </div>
                                    <div class="col-md-6">
                                        <h6 class="text-muted mb-2">Pincode</h6>
                                        <h5 class="mb-0">{{ $profile->pincode ?? 'Not Provided' }}</h5>
                                    </div>
                                </div>

                                <div class="row mb-4">
                                    <div class="col-md-12">
                                        <h6 class="text-muted mb-2">About</h6>
                                        <p class="mb-0">
                                            {{ $profile->about ?? 'No information provided' }}
                                        </p>
                                    </div>
                                </div>

                                <div class="row mb-4">
                                    <div class="col-md-6">
                                        <h6 class="text-muted mb-2">Status</h6>
                                        <h5 class="mb-0">
                                            @if ($profile->status === 'active')
                                                <span class="badge badge-soft-success">
                                                    <i class="ti ti-circle-check me-1"></i>Active
                                                </span>
                                            @else
                                                <span class="badge badge-soft-danger">
                                                    <i class="ti ti-circle-x me-1"></i>Inactive
                                                </span>
                                            @endif
                                        </h5>
                                    </div>
                                    <div class="col-md-6">
                                        <h6 class="text-muted mb-2">Profile Updated</h6>
                                        <h5 class="mb-0">{{ $profile->updated_at->format('d M, Y H:i') }}</h5>
                                    </div>
                                </div>
                            @else
                                <div class="alert alert-info" role="alert">
                                    <i class="ti ti-info-circle me-2"></i>
                                    Your profile details haven't been set up yet.
                                    <a href="{{ route('admin.user-profiles.create') }}" class="alert-link">Create
                                        Profile</a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Activity & Stats Section -->
            <div class="row mt-4">
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Recent Orders</h5>
                        </div>
                        <div class="card-body">
                            @php $orders = $user->orders()->latest()->take(5)->get(); @endphp
                            @if ($orders->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-sm table-hover mb-0">
                                        <thead class="bg-light">
                                            <tr>
                                                <th>Order ID</th>
                                                <th>Amount</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($orders as $order)
                                                <tr>
                                                    <td>#{{ $order->id }}</td>
                                                    <td>₹{{ number_format($order->total_amount ?? 0, 2) }}</td>
                                                    <td>
                                                        <span
                                                            class="badge badge-soft-{{ $order->status === 'completed' ? 'success' : ($order->status === 'pending' ? 'warning' : 'danger') }}">
                                                            {{ ucfirst($order->status) }}
                                                        </span>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <p class="text-muted mb-0">No orders yet</p>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Active Subscriptions</h5>
                        </div>
                        <div class="card-body">
                            @php $subscriptions = $user->subscriptions()->where('status', 'active')->latest()->take(5)->get(); @endphp
                            @if ($subscriptions->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-sm table-hover mb-0">
                                        <thead class="bg-light">
                                            <tr>
                                                <th>Plan</th>
                                                <th>Expires</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($subscriptions as $sub)
                                                <tr>
                                                    <td>{{ $sub->subscriptionPlan->name ?? 'N/A' }}</td>
                                                    <td>{{ $sub->end_date->format('d M, Y') }}</td>
                                                    <td>
                                                        <span
                                                            class="badge badge-soft-{{ $sub->end_date->isFuture() ? 'success' : 'danger' }}">
                                                            {{ $sub->end_date->isFuture() ? 'Active' : 'Expired' }}
                                                        </span>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <p class="text-muted mb-0">No active subscriptions</p>
                            @endif
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
