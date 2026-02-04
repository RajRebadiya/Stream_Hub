@extends('admin.layout.template')

@section('content')
    <div class="content-page">
        <div class="container-fluid">
            <div class="page-title-head d-flex align-items-center">
                <div class="flex-grow-1">
                    <h4 class="page-main-title m-0">Edit User</h4>
                </div>

                <div class="text-end">
                    <ol class="breadcrumb m-0 py-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">OTT Admin</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.user-profiles.index') }}">Users</a></li>
                        <li class="breadcrumb-item active">Edit User</li>
                    </ol>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-header justify-content-between">
                            <h4 class="card-title">User Information</h4>
                            <a href="{{ route('admin.user-profiles.index') }}" class="btn btn-sm btn-secondary">
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

                            <form action="{{ route('admin.user-profiles.update', $user->id) }}" method="POST">
                                @csrf
                                @method('PUT')

                                <!-- Account Information -->
                                <h5 class="text-muted mb-3">Account Information</h5>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="name" class="form-label">Full Name <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                                            id="name" name="name" value="{{ old('name', $user->name) }}"
                                            placeholder="Enter full name" required>
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="email" class="form-label">Email Address <span
                                                class="text-danger">*</span></label>
                                        <input type="email" class="form-control @error('email') is-invalid @enderror"
                                            id="email" name="email" value="{{ old('email', $user->email) }}"
                                            placeholder="user@example.com" required>
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="password" class="form-label">Password</label>
                                        <input type="password" class="form-control @error('password') is-invalid @enderror"
                                            id="password" name="password" placeholder="Leave blank to keep current">
                                        <small class="text-muted">Only fill if you want to change password</small>
                                        @error('password')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="password_confirmation" class="form-label">Confirm Password</label>
                                        <input type="password" class="form-control" id="password_confirmation"
                                            name="password_confirmation" placeholder="Re-enter new password">
                                    </div>
                                </div>

                                <hr class="my-4">

                                <!-- Profile Information -->
                                <h5 class="text-muted mb-3">Profile Information</h5>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="phone" class="form-label">Phone Number</label>
                                        <input type="text" class="form-control @error('phone') is-invalid @enderror"
                                            id="phone" name="phone"
                                            value="{{ old('phone', $user->profile->phone ?? '') }}"
                                            placeholder="+91 9876543210" maxlength="15">
                                        @error('phone')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="status" class="form-label">Status <span
                                                class="text-danger">*</span></label>
                                        <select class="form-select @error('status') is-invalid @enderror" id="status"
                                            name="status" required>
                                            <option value="active"
                                                {{ old('status', $user->profile->status ?? 'active') == 'active' ? 'selected' : '' }}>
                                                Active</option>
                                            <option value="inactive"
                                                {{ old('status', $user->profile->status ?? 'active') == 'inactive' ? 'selected' : '' }}>
                                                Inactive</option>
                                        </select>
                                        @error('status')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-12 mb-3">
                                        <label for="address" class="form-label">Address</label>
                                        <textarea class="form-control @error('address') is-invalid @enderror" id="address" name="address" rows="2"
                                            placeholder="Enter complete address">{{ old('address', $user->profile->address ?? '') }}</textarea>
                                        @error('address')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <label for="city" class="form-label">City</label>
                                        <input type="text" class="form-control @error('city') is-invalid @enderror"
                                            id="city" name="city"
                                            value="{{ old('city', $user->profile->city ?? '') }}"
                                            placeholder="Enter city" maxlength="100">
                                        @error('city')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <label for="state" class="form-label">State</label>
                                        <input type="text" class="form-control @error('state') is-invalid @enderror"
                                            id="state" name="state"
                                            value="{{ old('state', $user->profile->state ?? '') }}"
                                            placeholder="Enter state" maxlength="100">
                                        @error('state')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <label for="pincode" class="form-label">Pincode</label>
                                        <input type="text" class="form-control @error('pincode') is-invalid @enderror"
                                            id="pincode" name="pincode"
                                            value="{{ old('pincode', $user->profile->pincode ?? '') }}"
                                            placeholder="Enter pincode" maxlength="10">
                                        @error('pincode')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="text-end">
                                    <a href="{{ route('admin.user-profiles.index') }}" class="btn btn-secondary me-2">
                                        <i class="ti ti-x me-1"></i> Cancel
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="ti ti-device-floppy me-1"></i> Update User
                                    </button>
                                </div>
                            </form>
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
                                <span class="avatar-title bg-soft-primary text-primary rounded-circle fs-1"
                                    id="avatar-preview">
                                    {{ strtoupper(substr($user->name, 0, 2)) }}
                                </span>
                            </div>
                            <h5 class="mb-1">{{ $user->name }}</h5>
                            <p class="text-muted mb-0">{{ $user->email }}</p>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">User Statistics</h4>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <div class="d-flex justify-content-between mb-1">
                                    <span class="text-muted">User ID:</span>
                                    <span class="fw-semibold">#{{ $user->id }}</span>
                                </div>
                            </div>
                            <div class="mb-3">
                                <div class="d-flex justify-content-between mb-1">
                                    <span class="text-muted">Joined:</span>
                                    <span class="fw-semibold">{{ $user->created_at->format('d M, Y') }}</span>
                                </div>
                            </div>
                            <div class="mb-3">
                                <div class="d-flex justify-content-between mb-1">
                                    <span class="text-muted">Last Updated:</span>
                                    <span class="fw-semibold">{{ $user->updated_at->format('d M, Y') }}</span>
                                </div>
                            </div>
                            <div class="mb-0">
                                <div class="d-flex justify-content-between mb-1">
                                    <span class="text-muted">Email Verified:</span>
                                    @if ($user->email_verified_at)
                                        <span class="badge badge-soft-success">
                                            <i class="ti ti-check"></i> Yes
                                        </span>
                                    @else
                                        <span class="badge badge-soft-warning">
                                            <i class="ti ti-x"></i> No
                                        </span>
                                    @endif
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
                                    Leave password blank to keep current
                                </li>
                                <li class="mb-2">
                                    <i class="ti ti-check text-success me-2"></i>
                                    Email must be unique in the system
                                </li>
                                <li class="mb-2">
                                    <i class="ti ti-check text-success me-2"></i>
                                    Profile information is optional
                                </li>
                                <li class="mb-0">
                                    <i class="ti ti-check text-success me-2"></i>
                                    Status controls user access
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
        // Update avatar preview based on name input
        document.getElementById('name').addEventListener('input', function() {
            const name = this.value.trim();
            const preview = document.getElementById('avatar-preview');
            if (name.length > 0) {
                preview.textContent = name.substring(0, 2).toUpperCase();
            } else {
                preview.textContent = 'U';
            }
        });
    </script>
@endpush
