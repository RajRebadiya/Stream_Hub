@extends('admin.layout.template')

@section('content')
    <div class="content-page">
        <div class="container-fluid">
            <div class="page-title-head d-flex align-items-center">
                <div class="flex-grow-1">
                    <h4 class="page-main-title m-0">User Details</h4>
                </div>

                <div class="text-end">
                    <ol class="breadcrumb m-0 py-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">OTT Admin</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.user-profiles.index') }}">Users</a></li>
                        <li class="breadcrumb-item active">User Details</li>
                    </ol>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-8">
                    <!-- Account Information -->
                    <div class="card">
                        <div class="card-header justify-content-between">
                            <h4 class="card-title">Account Information</h4>
                            <div>
                                <a href="{{ route('admin.user-profiles.edit', $user->id) }}"
                                    class="btn btn-sm btn-primary me-1">
                                    <i class="ti ti-edit me-1"></i> Edit User
                                </a>
                                <a href="{{ route('admin.user-profiles.index') }}" class="btn btn-sm btn-secondary">
                                    <i class="ti ti-arrow-left me-1"></i> Back to List
                                </a>
                            </div>
                        </div>

                        <div class="card-body">
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <table class="table table-borderless">
                                        <tbody>
                                            <tr>
                                                <td class="text-muted" width="40%">User ID:</td>
                                                <td class="fw-semibold">#{{ $user->id }}</td>
                                            </tr>
                                            <tr>
                                                <td class="text-muted">Full Name:</td>
                                                <td class="fw-semibold">{{ $user->name }}</td>
                                            </tr>
                                            <tr>
                                                <td class="text-muted">Email Address:</td>
                                                <td>
                                                    {{ $user->email }}
                                                    @if ($user->email_verified_at)
                                                        <span class="badge badge-soft-success ms-1">
                                                            <i class="ti ti-check"></i> Verified
                                                        </span>
                                                    @else
                                                        <span class="badge badge-soft-warning ms-1">
                                                            <i class="ti ti-x"></i> Not Verified
                                                        </span>
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="text-muted">Account Status:</td>
                                                <td>
                                                    @if ($user->profile && $user->profile->status === 'active')
                                                        <span class="badge badge-soft-success">Active</span>
                                                    @elseif($user->profile)
                                                        <span class="badge badge-soft-danger">Inactive</span>
                                                    @else
                                                        <span class="badge badge-soft-warning">No Profile</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>

                                <div class="col-md-6">
                                    <table class="table table-borderless">
                                        <tbody>
                                            <tr>
                                                <td class="text-muted" width="40%">Joined Date:</td>
                                                <td class="fw-semibold">{{ $user->created_at->format('d M, Y') }}</td>
                                            </tr>
                                            <tr>
                                                <td class="text-muted">Last Updated:</td>
                                                <td class="fw-semibold">{{ $user->updated_at->format('d M, Y') }}</td>
                                            </tr>
                                            <tr>
                                                <td class="text-muted">Email Verified At:</td>
                                                <td>
                                                    @if ($user->email_verified_at)
                                                        {{ $user->email_verified_at->format('d M, Y H:i A') }}
                                                    @else
                                                        <span class="text-muted">Not verified</span>
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="text-muted">Member Since:</td>
                                                <td>{{ $user->created_at->diffForHumans() }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Profile Information -->
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Profile Information</h4>
                        </div>

                        <div class="card-body">
                            @if ($user->profile)
                                <div class="row">
                                    <div class="col-md-6">
                                        <table class="table table-borderless">
                                            <tbody>
                                                <tr>
                                                    <td class="text-muted" width="40%">Phone Number:</td>
                                                    <td>
                                                        @if ($user->profile->phone)
                                                            <span
                                                                class="badge badge-soft-info">{{ $user->profile->phone }}</span>
                                                        @else
                                                            <span class="text-muted">Not provided</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="text-muted">City:</td>
                                                    <td>
                                                        @if ($user->profile->city)
                                                            {{ $user->profile->city }}
                                                        @else
                                                            <span class="text-muted">Not provided</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="text-muted">State:</td>
                                                    <td>
                                                        @if ($user->profile->state)
                                                            {{ $user->profile->state }}
                                                        @else
                                                            <span class="text-muted">Not provided</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>

                                    <div class="col-md-6">
                                        <table class="table table-borderless">
                                            <tbody>
                                                <tr>
                                                    <td class="text-muted" width="40%">Pincode:</td>
                                                    <td>
                                                        @if ($user->profile->pincode)
                                                            {{ $user->profile->pincode }}
                                                        @else
                                                            <span class="text-muted">Not provided</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="text-muted" style="vertical-align: top;">Address:</td>
                                                    <td>
                                                        @if ($user->profile->address)
                                                            {{ $user->profile->address }}
                                                        @else
                                                            <span class="text-muted">Not provided</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                @if ($user->profile->address || $user->profile->city || $user->profile->state)
                                    <div class="mt-3">
                                        <h6 class="text-muted mb-2">Complete Address:</h6>
                                        <div class="border rounded p-3 bg-light">
                                            @if ($user->profile->address)
                                                {{ $user->profile->address }}<br>
                                            @endif
                                            @if ($user->profile->city)
                                                {{ $user->profile->city }}
                                            @endif
                                            @if ($user->profile->state)
                                                @if ($user->profile->city)
                                                    ,
                                                @endif{{ $user->profile->state }}
                                            @endif
                                            @if ($user->profile->pincode)
                                                - {{ $user->profile->pincode }}
                                            @endif
                                        </div>
                                    </div>
                                @endif
                            @else
                                <div class="text-center py-4">
                                    <i class="ti ti-user-exclamation fs-1 text-muted"></i>
                                    <p class="text-muted mb-0 mt-2">No profile information available</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Activity / Subscriptions (if available) -->
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Activity Summary</h4>
                        </div>
                        <div class="card-body">
                            <div class="row text-center">
                                <div class="col-md-4">
                                    <div class="border rounded p-3">
                                        <h3 class="mb-1 text-primary">0</h3>
                                        <p class="text-muted mb-0">Total Orders</p>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="border rounded p-3">
                                        <h3 class="mb-1 text-success">0</h3>
                                        <p class="text-muted mb-0">Active Subscriptions</p>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="border rounded p-3">
                                        <h3 class="mb-1 text-info">₹0.00</h3>
                                        <p class="text-muted mb-0">Total Spent</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">User Avatar</h4>
                        </div>
                        <div class="card-body text-center">
                            <div class="avatar-xl mx-auto mb-3">
                                <span class="avatar-title bg-soft-primary text-primary rounded-circle fs-1">
                                    {{ strtoupper(substr($user->name, 0, 2)) }}
                                </span>
                            </div>
                            <h5 class="mb-1">{{ $user->name }}</h5>
                            <p class="text-muted mb-3">{{ $user->email }}</p>

                            @if ($user->profile)
                                @if ($user->profile->status === 'active')
                                    <span class="badge badge-soft-success px-3 py-2">
                                        <i class="ti ti-circle-check me-1"></i> Active User
                                    </span>
                                @else
                                    <span class="badge badge-soft-danger px-3 py-2">
                                        <i class="ti ti-circle-x me-1"></i> Inactive User
                                    </span>
                                @endif
                            @endif
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Quick Stats</h4>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-muted">Profile Completed:</span>
                                    <span class="fw-semibold">
                                        @php
                                            $completion = 0;
                                            if ($user->profile) {
                                                if ($user->profile->phone) {
                                                    $completion += 20;
                                                }
                                                if ($user->profile->address) {
                                                    $completion += 20;
                                                }
                                                if ($user->profile->city) {
                                                    $completion += 20;
                                                }
                                                if ($user->profile->state) {
                                                    $completion += 20;
                                                }
                                                if ($user->profile->pincode) {
                                                    $completion += 20;
                                                }
                                            }
                                        @endphp
                                        {{ $completion }}%
                                    </span>
                                </div>
                                <div class="progress progress-sm">
                                    <div class="progress-bar bg-primary" style="width: {{ $completion }}%"></div>
                                </div>
                            </div>

                            <hr>

                            <div class="mb-2">
                                <div class="d-flex justify-content-between mb-1">
                                    <span class="text-muted">Account Age:</span>
                                    <span class="fw-semibold">{{ $user->created_at->diffForHumans() }}</span>
                                </div>
                            </div>
                            <div class="mb-0">
                                <div class="d-flex justify-content-between mb-1">
                                    <span class="text-muted">Last Login:</span>
                                    <span class="fw-semibold text-muted">N/A</span>
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
                                <a href="{{ route('admin.user-profiles.edit', $user->id) }}" class="btn btn-primary">
                                    <i class="ti ti-edit me-1"></i> Edit User
                                </a>

                                @if ($user->profile && $user->profile->status === 'active')
                                    <button class="btn btn-warning" onclick="alert('Deactivate user functionality')">
                                        <i class="ti ti-user-pause me-1"></i> Deactivate User
                                    </button>
                                @else
                                    <button class="btn btn-success" onclick="alert('Activate user functionality')">
                                        <i class="ti ti-user-check me-1"></i> Activate User
                                    </button>
                                @endif

                                <form action="{{ route('admin.user-profiles.destroy', $user->id) }}" method="POST"
                                    onsubmit="return confirm('Are you sure you want to delete this user? This action cannot be undone and will delete all associated data.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger w-100">
                                        <i class="ti ti-trash me-1"></i> Delete User
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
