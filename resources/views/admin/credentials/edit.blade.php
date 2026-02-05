@extends('admin.layout.template')

@section('content')
    <div class="content-page">
        <div class="container-fluid">
            <div class="page-title-head d-flex align-items-center">
                <div class="flex-grow-1">
                    <h4 class="page-main-title m-0">Edit Credential</h4>
                </div>

                <div class="text-end">
                    <ol class="breadcrumb m-0 py-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">OTT Admin</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.credentials.index') }}">Credentials</a></li>
                        <li class="breadcrumb-item active">Edit Credential</li>
                    </ol>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-header justify-content-between">
                            <h4 class="card-title">Credential Information</h4>
                            <a href="{{ route('admin.credentials.show', $credential->id) }}"
                                class="btn btn-sm btn-secondary">
                                <i class="ti ti-arrow-left me-1"></i> Back to Details
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

                            <form action="{{ route('admin.credentials.update', $credential->id) }}" method="POST">
                                @csrf
                                @method('PUT')

                                <!-- Plan Selection -->
                                <h5 class="text-muted mb-3">Subscription Plan</h5>
                                <div class="row">
                                    <div class="col-md-12 mb-3">
                                        <label for="subscription_plan_id" class="form-label">Select Plan <span
                                                class="text-danger">*</span></label>
                                        <select class="form-select @error('subscription_plan_id') is-invalid @enderror"
                                            id="subscription_plan_id" name="subscription_plan_id" required>
                                            <option value="">Choose subscription plan...</option>
                                            @foreach ($plans as $plan)
                                                <option value="{{ $plan->id }}"
                                                    {{ old('subscription_plan_id', $credential->subscription_plan_id) == $plan->id ? 'selected' : '' }}>
                                                    {{ $plan->platform->name }} - {{ $plan->name }}
                                                    ({{ $plan->duration_months }}
                                                    {{ $plan->duration_months == 1 ? 'Month' : 'Months' }})
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('subscription_plan_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <hr class="my-4">

                                <!-- Login Credentials -->
                                <h5 class="text-muted mb-3">Login Credentials</h5>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="email" class="form-label">Email</label>
                                        <input type="email" class="form-control @error('email') is-invalid @enderror"
                                            id="email" name="email" value="{{ old('email', $credential->email) }}"
                                            placeholder="user@example.com">
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="password" class="form-label">Password</label>
                                        <div class="input-group">
                                            <input type="password"
                                                class="form-control @error('password') is-invalid @enderror" id="password"
                                                name="password" placeholder="Leave blank to keep current">
                                            <button class="btn btn-outline-secondary" type="button"
                                                onclick="togglePassword()">
                                                <i class="ti ti-eye" id="toggleIcon"></i>
                                            </button>
                                            @error('password')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <small class="text-muted">Leave blank to keep current password</small>
                                    </div>
                                </div>

                                <hr class="my-4">

                                <!-- Profile Details -->
                                <h5 class="text-muted mb-3">Profile Details</h5>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="profile_name" class="form-label">Profile Name</label>
                                        <input type="text"
                                            class="form-control @error('profile_name') is-invalid @enderror"
                                            id="profile_name" name="profile_name"
                                            value="{{ old('profile_name', $credential->profile_name) }}"
                                            placeholder="e.g., User 1, Main Profile" maxlength="100">
                                        @error('profile_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="pin" class="form-label">PIN</label>
                                        <div class="input-group">
                                            <input type="password" class="form-control @error('pin') is-invalid @enderror"
                                                id="pin" name="pin" placeholder="Leave blank to keep current"
                                                maxlength="10">
                                            <button class="btn btn-outline-secondary" type="button" onclick="togglePin()">
                                                <i class="ti ti-eye" id="togglePinIcon"></i>
                                            </button>
                                            @error('pin')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <small class="text-muted">Leave blank to keep current PIN</small>
                                    </div>
                                </div>

                                <hr class="my-4">

                                <!-- Status & Assignment -->
                                <h5 class="text-muted mb-3">Status & Assignment</h5>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="status" class="form-label">Status <span
                                                class="text-danger">*</span></label>
                                        <select class="form-select @error('status') is-invalid @enderror" id="status"
                                            name="status" required onchange="toggleAssignment()">
                                            <option value="available"
                                                {{ old('status', $credential->status) == 'available' ? 'selected' : '' }}>
                                                Available</option>
                                            <option value="assigned"
                                                {{ old('status', $credential->status) == 'assigned' ? 'selected' : '' }}>
                                                Assigned</option>
                                            <option value="expired"
                                                {{ old('status', $credential->status) == 'expired' ? 'selected' : '' }}>
                                                Expired</option>
                                            <option value="blocked"
                                                {{ old('status', $credential->status) == 'blocked' ? 'selected' : '' }}>
                                                Blocked</option>
                                        </select>
                                        @error('status')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3" id="assignmentField"
                                        style="display: {{ old('status', $credential->status) == 'assigned' ? 'block' : 'none' }};">
                                        <label for="assigned_to_user_id" class="form-label">Assign To User</label>
                                        <select class="form-select @error('assigned_to_user_id') is-invalid @enderror"
                                            id="assigned_to_user_id" name="assigned_to_user_id">
                                            <option value="">Select user...</option>
                                            @foreach ($users as $user)
                                                <option value="{{ $user->id }}"
                                                    {{ old('assigned_to_user_id', $credential->assigned_to_user_id) == $user->id ? 'selected' : '' }}>
                                                    {{ $user->name }} ({{ $user->email }})
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('assigned_to_user_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-12 mb-3">
                                        <label for="notes" class="form-label">Notes</label>
                                        <textarea class="form-control @error('notes') is-invalid @enderror" id="notes" name="notes" rows="3"
                                            placeholder="Add any additional notes...">{{ old('notes', $credential->notes) }}</textarea>
                                        @error('notes')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="text-end">
                                    <a href="{{ route('admin.credentials.show', $credential->id) }}"
                                        class="btn btn-secondary me-2">
                                        <i class="ti ti-x me-1"></i> Cancel
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="ti ti-device-floppy me-1"></i> Update Credential
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <!-- Current Info -->
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Current Information</h4>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label text-muted">Current Status:</label>
                                <div>
                                    @if ($credential->status === 'available')
                                        <span class="badge badge-soft-success px-3 py-2">Available</span>
                                    @elseif($credential->status === 'assigned')
                                        <span class="badge badge-soft-info px-3 py-2">Assigned</span>
                                    @elseif($credential->status === 'expired')
                                        <span class="badge badge-soft-warning px-3 py-2">Expired</span>
                                    @else
                                        <span class="badge badge-soft-danger px-3 py-2">Blocked</span>
                                    @endif
                                </div>
                            </div>

                            @if ($credential->assignedToUser)
                                <div class="mb-3">
                                    <label class="form-label text-muted">Currently Assigned To:</label>
                                    <div>
                                        <strong>{{ $credential->assignedToUser->name }}</strong><br>
                                        <small class="text-muted">{{ $credential->assignedToUser->email }}</small>
                                    </div>
                                </div>
                            @endif

                            <div class="mb-0">
                                <label class="form-label text-muted">Last Updated:</label>
                                <div>{{ $credential->updated_at->format('d M, Y H:i A') }}</div>
                            </div>
                        </div>
                    </div>

                    <!-- Security Info -->
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Security Information</h4>
                        </div>
                        <div class="card-body">
                            <div class="alert alert-warning mb-0">
                                <i class="ti ti-shield-lock me-2"></i>
                                <strong>Important:</strong> Passwords and PINs are encrypted. Leave fields blank to keep
                                current values.
                            </div>
                        </div>
                    </div>

                    <!-- Quick Tips -->
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Quick Tips</h4>
                        </div>
                        <div class="card-body">
                            <ul class="list-unstyled mb-0">
                                <li class="mb-2">
                                    <i class="ti ti-info-circle text-info me-2"></i>
                                    Only update password/PIN if changed
                                </li>
                                <li class="mb-2">
                                    <i class="ti ti-info-circle text-info me-2"></i>
                                    Status affects availability for assignment
                                </li>
                                <li class="mb-0">
                                    <i class="ti ti-info-circle text-info me-2"></i>
                                    Unassign by changing status from "Assigned"
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
        // Toggle password visibility
        function togglePassword() {
            const passwordField = document.getElementById('password');
            const icon = document.getElementById('toggleIcon');

            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                icon.classList.remove('ti-eye');
                icon.classList.add('ti-eye-off');
            } else {
                passwordField.type = 'password';
                icon.classList.remove('ti-eye-off');
                icon.classList.add('ti-eye');
            }
        }

        // Toggle PIN visibility
        function togglePin() {
            const pinField = document.getElementById('pin');
            const icon = document.getElementById('togglePinIcon');

            if (pinField.type === 'password') {
                pinField.type = 'text';
                icon.classList.remove('ti-eye');
                icon.classList.add('ti-eye-off');
            } else {
                pinField.type = 'password';
                icon.classList.remove('ti-eye-off');
                icon.classList.add('ti-eye');
            }
        }

        // Toggle assignment field based on status
        function toggleAssignment() {
            const status = document.getElementById('status').value;
            const assignmentField = document.getElementById('assignmentField');

            if (status === 'assigned') {
                assignmentField.style.display = 'block';
            } else {
                assignmentField.style.display = 'none';
                document.getElementById('assigned_to_user_id').value = '';
            }
        }

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            toggleAssignment();
        });
    </script>
@endpush
