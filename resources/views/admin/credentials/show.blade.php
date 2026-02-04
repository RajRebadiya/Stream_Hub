@extends('admin.layout.template')

@section('content')
    <div class="content-page">
        <div class="container-fluid">
            <div class="page-title-head d-flex align-items-center">
                <div class="flex-grow-1">
                    <h4 class="page-main-title m-0">Credential Details</h4>
                </div>

                <div class="text-end">
                    <ol class="breadcrumb m-0 py-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">OTT Admin</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.credentials.index') }}">Credentials</a></li>
                        <li class="breadcrumb-item active">View</li>
                    </ol>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-header justify-content-between">
                            <h4 class="card-title">Credential Information</h4>
                            <div>
                                <a href="{{ route('admin.credentials.edit', $credential->id) }}"
                                    class="btn btn-sm btn-primary me-1">
                                    <i class="ti ti-edit me-1"></i> Edit
                                </a>
                                <a href="{{ route('admin.credentials.index') }}" class="btn btn-sm btn-secondary">
                                    <i class="ti ti-arrow-left me-1"></i> Back
                                </a>
                            </div>
                        </div>

                        <div class="card-body">
                            <div class="row mb-4">
                                <div class="col-md-12">
                                    <h5 class="text-muted mb-3">Platform & Plan</h5>
                                    <div class="d-flex align-items-center mb-3">
                                        @if ($credential->subscriptionPlan->platform->logo)
                                            <img src="{{ asset('storage/' . $credential->subscriptionPlan->platform->logo) }}"
                                                alt="{{ $credential->subscriptionPlan->platform->name }}" height="40"
                                                class="rounded me-3">
                                        @endif
                                        <div>
                                            <h5 class="mb-0">{{ $credential->subscriptionPlan->platform->name }}</h5>
                                            <p class="text-muted mb-0">{{ $credential->subscriptionPlan->name }} -
                                                {{ $credential->subscriptionPlan->duration_months }}
                                                {{ $credential->subscriptionPlan->duration_months == 1 ? 'Month' : 'Months' }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <hr>

                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <h5 class="text-muted mb-3">Login Credentials</h5>
                                    <table class="table table-borderless">
                                        <tbody>
                                            <tr>
                                                <td class="text-muted" width="40%">Email:</td>
                                                <td>
                                                    @if ($credential->email)
                                                        <div class="d-flex align-items-center">
                                                            <span class="me-2">{{ $credential->email }}</span>
                                                            <button class="btn btn-sm btn-soft-info copy-btn"
                                                                data-text="{{ $credential->email }}">
                                                                <i class="ti ti-copy"></i>
                                                            </button>
                                                        </div>
                                                    @else
                                                        <span class="text-muted">Not provided</span>
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="text-muted">Password:</td>
                                                <td>
                                                    @if ($credential->password)
                                                        <div class="d-flex align-items-center">
                                                            <span class="me-2" id="password-display">••••••••</span>
                                                            <button class="btn btn-sm btn-soft-warning reveal-password"
                                                                data-id="{{ $credential->id }}">
                                                                <i class="ti ti-eye"></i>
                                                            </button>
                                                            <button class="btn btn-sm btn-soft-info copy-password ms-1"
                                                                style="display: none;">
                                                                <i class="ti ti-copy"></i>
                                                            </button>
                                                        </div>
                                                    @else
                                                        <span class="text-muted">Not provided</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>

                                <div class="col-md-6">
                                    <h5 class="text-muted mb-3">Profile Details</h5>
                                    <table class="table table-borderless">
                                        <tbody>
                                            <tr>
                                                <td class="text-muted" width="40%">Profile Name:</td>
                                                <td>
                                                    @if ($credential->profile_name)
                                                        {{ $credential->profile_name }}
                                                    @else
                                                        <span class="text-muted">Not provided</span>
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="text-muted">PIN:</td>
                                                <td>
                                                    @if ($credential->pin)
                                                        <div class="d-flex align-items-center">
                                                            <span class="me-2" id="pin-display">••••</span>
                                                            <button class="btn btn-sm btn-soft-warning reveal-pin"
                                                                data-id="{{ $credential->id }}">
                                                                <i class="ti ti-eye"></i>
                                                            </button>
                                                            <button class="btn btn-sm btn-soft-info copy-pin ms-1"
                                                                style="display: none;">
                                                                <i class="ti ti-copy"></i>
                                                            </button>
                                                        </div>
                                                    @else
                                                        <span class="text-muted">Not provided</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            @if ($credential->notes)
                                <hr>
                                <div class="mb-4">
                                    <h5 class="text-muted mb-3">Notes</h5>
                                    <p class="text-muted">{{ $credential->notes }}</p>
                                </div>
                            @endif

                            <hr>

                            <div class="row">
                                <div class="col-md-6">
                                    <h5 class="text-muted mb-3">Status & Assignment</h5>
                                    <table class="table table-borderless">
                                        <tbody>
                                            <tr>
                                                <td class="text-muted" width="40%">Status:</td>
                                                <td>
                                                    @if ($credential->status === 'available')
                                                        <span class="badge badge-soft-success px-3 py-2">Available</span>
                                                    @elseif($credential->status === 'assigned')
                                                        <span class="badge badge-soft-info px-3 py-2">Assigned</span>
                                                    @elseif($credential->status === 'expired')
                                                        <span class="badge badge-soft-warning px-3 py-2">Expired</span>
                                                    @else
                                                        <span class="badge badge-soft-danger px-3 py-2">Blocked</span>
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="text-muted">Assigned To:</td>
                                                <td>
                                                    @if ($credential->assignedToUser)
                                                        <div>
                                                            <strong>{{ $credential->assignedToUser->name }}</strong><br>
                                                            <small
                                                                class="text-muted">{{ $credential->assignedToUser->email }}</small>
                                                        </div>
                                                    @else
                                                        <span class="text-muted">Not assigned</span>
                                                    @endif
                                                </td>
                                            </tr>
                                            @if ($credential->assigned_at)
                                                <tr>
                                                    <td class="text-muted">Assigned At:</td>
                                                    <td>{{ $credential->assigned_at->format('d M, Y H:i A') }}</td>
                                                </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>

                                <div class="col-md-6">
                                    <h5 class="text-muted mb-3">Timestamps</h5>
                                    <table class="table table-borderless">
                                        <tbody>
                                            <tr>
                                                <td class="text-muted" width="40%">Created:</td>
                                                <td>{{ $credential->created_at->format('d M, Y H:i A') }}</td>
                                            </tr>
                                            <tr>
                                                <td class="text-muted">Last Updated:</td>
                                                <td>{{ $credential->updated_at->format('d M, Y H:i A') }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Quick Actions</h4>
                        </div>
                        <div class="card-body">
                            <div class="d-grid gap-2">
                                @if ($credential->isAvailable())
                                    <button class="btn btn-info" onclick="showAssignModal()">
                                        <i class="ti ti-user-plus me-1"></i> Assign to User
                                    </button>
                                @endif

                                @if ($credential->isAssigned())
                                    <button class="btn btn-warning" onclick="unassignCredential()">
                                        <i class="ti ti-user-minus me-1"></i> Unassign
                                    </button>
                                @endif

                                @if (!$credential->isBlocked())
                                    <button class="btn btn-danger" onclick="blockCredential()">
                                        <i class="ti ti-ban me-1"></i> Block Credential
                                    </button>
                                @else
                                    <button class="btn btn-success" onclick="makeAvailable()">
                                        <i class="ti ti-check me-1"></i> Make Available
                                    </button>
                                @endif

                                <a href="{{ route('admin.credentials.edit', $credential->id) }}" class="btn btn-primary">
                                    <i class="ti ti-edit me-1"></i> Edit Credential
                                </a>

                                <form action="{{ route('admin.credentials.destroy', $credential->id) }}" method="POST"
                                    onsubmit="return confirm('Are you sure you want to delete this credential?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger w-100">
                                        <i class="ti ti-trash me-1"></i> Delete Credential
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Security Notice</h4>
                        </div>
                        <div class="card-body">
                            <div class="alert alert-warning mb-0">
                                <i class="ti ti-alert-triangle me-2"></i>
                                <strong>Important:</strong> Credentials are encrypted. Click the eye icon to reveal
                                sensitive information.
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

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
    </div>
@endsection

@push('scripts')
    <script>
        let actualPassword = null;
        let actualPin = null;

        // Copy to clipboard
        document.querySelectorAll('.copy-btn').forEach(button => {
            button.addEventListener('click', function() {
                const text = this.dataset.text;
                copyToClipboard(text, this);
            });
        });

        // Reveal password
        document.querySelector('.reveal-password')?.addEventListener('click', async function() {
            const credentialId = this.dataset.id;

            if (actualPassword) {
                document.getElementById('password-display').textContent = '••••••••';
                actualPassword = null;
                this.innerHTML = '<i class="ti ti-eye"></i>';
                document.querySelector('.copy-password').style.display = 'none';
            } else {
                try {
                    const response = await fetch(`/admin/credentials/${credentialId}/reveal-password`, {
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    });
                    const data = await response.json();

                    actualPassword = data.password;
                    document.getElementById('password-display').textContent = actualPassword;
                    this.innerHTML = '<i class="ti ti-eye-off"></i>';
                    document.querySelector('.copy-password').style.display = 'inline-block';
                } catch (error) {
                    alert('Error revealing password');
                }
            }
        });

        // Copy password
        document.querySelector('.copy-password')?.addEventListener('click', function() {
            if (actualPassword) {
                copyToClipboard(actualPassword, this);
            }
        });

        // Reveal PIN
        document.querySelector('.reveal-pin')?.addEventListener('click', async function() {
            const credentialId = this.dataset.id;

            if (actualPin) {
                document.getElementById('pin-display').textContent = '••••';
                actualPin = null;
                this.innerHTML = '<i class="ti ti-eye"></i>';
                document.querySelector('.copy-pin').style.display = 'none';
            } else {
                try {
                    const response = await fetch(`/admin/credentials/${credentialId}/reveal-pin`, {
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    });
                    const data = await response.json();

                    actualPin = data.pin;
                    document.getElementById('pin-display').textContent = actualPin;
                    this.innerHTML = '<i class="ti ti-eye-off"></i>';
                    document.querySelector('.copy-pin').style.display = 'inline-block';
                } catch (error) {
                    alert('Error revealing PIN');
                }
            }
        });

        // Copy PIN
        document.querySelector('.copy-pin')?.addEventListener('click', function() {
            if (actualPin) {
                copyToClipboard(actualPin, this);
            }
        });

        function copyToClipboard(text, button) {
            navigator.clipboard.writeText(text).then(() => {
                const icon = button.querySelector('i');
                const originalClass = icon.className;
                icon.className = 'ti ti-check';

                setTimeout(() => {
                    icon.className = originalClass;
                }, 2000);
            });
        }

        function showAssignModal() {
            alert('Assign modal - To be implemented with user selection');
        }

        function unassignCredential() {
            if (confirm('Unassign this credential?')) {
                alert('Unassign feature - To be implemented');
            }
        }

        function blockCredential() {
            if (confirm('Block this credential?')) {
                alert('Block feature - To be implemented');
            }
        }

        function makeAvailable() {
            if (confirm('Make this credential available?')) {
                alert('Make available feature - To be implemented');
            }
        }
    </script>
@endpush
