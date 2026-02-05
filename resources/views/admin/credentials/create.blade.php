@extends('admin.layout.template')

@section('content')
    <div class="content-page">
        <div class="container-fluid">
            <div class="page-title-head d-flex align-items-center">
                <div class="flex-grow-1">
                    <h4 class="page-main-title m-0">Add Credential</h4>
                </div>

                <div class="text-end">
                    <ol class="breadcrumb m-0 py-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">OTT Admin</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.credentials.index') }}">Credentials</a></li>
                        <li class="breadcrumb-item active">Add Credential</li>
                    </ol>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-header justify-content-between">
                            <h4 class="card-title">Credential Information</h4>
                            <a href="{{ route('admin.credentials.index') }}" class="btn btn-sm btn-secondary">
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

                            <form action="{{ route('admin.credentials.store') }}" method="POST">
                                @csrf

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
                                                    {{ old('subscription_plan_id') == $plan->id ? 'selected' : '' }}>
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
                                            id="email" name="email" value="{{ old('email') }}"
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
                                                name="password" placeholder="Enter password">
                                            <button class="btn btn-outline-secondary" type="button"
                                                onclick="togglePassword()">
                                                <i class="ti ti-eye" id="toggleIcon"></i>
                                            </button>
                                            @error('password')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <small class="text-muted">Will be encrypted automatically</small>
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
                                            id="profile_name" name="profile_name" value="{{ old('profile_name') }}"
                                            placeholder="e.g., User 1, Main Profile" maxlength="100">
                                        @error('profile_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="pin" class="form-label">PIN</label>
                                        <div class="input-group">
                                            <input type="password" class="form-control @error('pin') is-invalid @enderror"
                                                id="pin" name="pin" placeholder="Enter PIN" maxlength="10">
                                            <button class="btn btn-outline-secondary" type="button" onclick="togglePin()">
                                                <i class="ti ti-eye" id="togglePinIcon"></i>
                                            </button>
                                            @error('pin')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <small class="text-muted">Will be encrypted automatically</small>
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
                                                {{ old('status', 'available') == 'available' ? 'selected' : '' }}>Available
                                            </option>
                                            <option value="assigned" {{ old('status') == 'assigned' ? 'selected' : '' }}>
                                                Assigned</option>
                                            <option value="expired" {{ old('status') == 'expired' ? 'selected' : '' }}>
                                                Expired</option>
                                            <option value="blocked" {{ old('status') == 'blocked' ? 'selected' : '' }}>
                                                Blocked</option>
                                        </select>
                                        @error('status')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3" id="assignmentField"
                                        style="display: {{ old('status') == 'assigned' ? 'block' : 'none' }};">
                                        <label for="assigned_to_user_id" class="form-label">Assign To User</label>
                                        <select class="form-select @error('assigned_to_user_id') is-invalid @enderror"
                                            id="assigned_to_user_id" name="assigned_to_user_id">
                                            <option value="">Select user...</option>
                                            @foreach ($users as $user)
                                                <option value="{{ $user->id }}"
                                                    {{ old('assigned_to_user_id') == $user->id ? 'selected' : '' }}>
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
                                            placeholder="Add any additional notes...">{{ old('notes') }}</textarea>
                                        @error('notes')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="text-end">
                                    <a href="{{ route('admin.credentials.index') }}" class="btn btn-secondary me-2">
                                        <i class="ti ti-x me-1"></i> Cancel
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="ti ti-device-floppy me-1"></i> Save Credential
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <!-- Security Info -->
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Security Information</h4>
                        </div>
                        <div class="card-body">
                            <div class="alert alert-info mb-3">
                                <i class="ti ti-shield-lock me-2"></i>
                                <strong>Encrypted Storage</strong><br>
                                Passwords and PINs are automatically encrypted before being stored in the database.
                            </div>
                            <ul class="list-unstyled mb-0">
                                <li class="mb-2">
                                    <i class="ti ti-check text-success me-2"></i>
                                    AES-256 encryption used
                                </li>
                                <li class="mb-2">
                                    <i class="ti ti-check text-success me-2"></i>
                                    Passwords never stored in plain text
                                </li>
                                <li class="mb-0">
                                    <i class="ti ti-check text-success me-2"></i>
                                    Secure credential management
                                </li>
                            </ul>
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
                                    <i class="ti ti-bulb text-warning me-2"></i>
                                    Email and password are optional
                                </li>
                                <li class="mb-2">
                                    <i class="ti ti-bulb text-warning me-2"></i>
                                    Profile name helps identify the credential
                                </li>
                                <li class="mb-2">
                                    <i class="ti ti-bulb text-warning me-2"></i>
                                    Set status to "Assigned" to link with a user
                                </li>
                                <li class="mb-0">
                                    <i class="ti ti-bulb text-warning me-2"></i>
                                    Use notes for additional information
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
