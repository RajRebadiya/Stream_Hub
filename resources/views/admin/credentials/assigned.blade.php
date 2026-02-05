@extends('admin.layout.template')

@section('content')
    <div class="content-page">
        <div class="container-fluid">
            <div class="page-title-head d-flex align-items-center">
                <div class="flex-grow-1">
                    <h4 class="page-main-title m-0">Assigned Credentials</h4>
                </div>

                <div class="text-end">
                    <ol class="breadcrumb m-0 py-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">OTT Admin</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.credentials.index') }}">Credentials</a></li>
                        <li class="breadcrumb-item active">Assigned</li>
                    </ol>
                </div>
            </div>

            <!-- Statistics Cards -->
            <div class="row mb-3">
                <div class="col-xl-3 col-md-6">
                    <div class="card card-h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-muted mb-1">Total Credentials</h6>
                                    <h3 class="mb-0">{{ $statistics['total'] ?? 0 }}</h3>
                                </div>
                                <div class="avatar-sm flex-shrink-0">
                                    <span class="avatar-title bg-soft-primary text-primary rounded-circle">
                                        <i class="ti ti-key fs-xxl"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6">
                    <div class="card card-h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-muted mb-1">Available</h6>
                                    <h3 class="mb-0">{{ $statistics['available'] ?? 0 }}</h3>
                                </div>
                                <div class="avatar-sm flex-shrink-0">
                                    <span class="avatar-title bg-soft-success text-success rounded-circle">
                                        <i class="ti ti-check fs-xxl"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6">
                    <div class="card card-h-100 border-info">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-muted mb-1">Assigned</h6>
                                    <h3 class="mb-0 text-info">{{ $statistics['assigned'] ?? 0 }}</h3>
                                </div>
                                <div class="avatar-sm flex-shrink-0">
                                    <span class="avatar-title bg-soft-info text-info rounded-circle">
                                        <i class="ti ti-user-check fs-xxl"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6">
                    <div class="card card-h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-muted mb-1">Blocked</h6>
                                    <h3 class="mb-0">{{ $statistics['blocked'] ?? 0 }}</h3>
                                </div>
                                <div class="avatar-sm flex-shrink-0">
                                    <span class="avatar-title bg-soft-danger text-danger rounded-circle">
                                        <i class="ti ti-ban fs-xxl"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header justify-content-between">
                            <h4 class="card-title">Assigned Credentials</h4>
                            <a href="{{ route('admin.credentials.create') }}" class="btn btn-sm btn-primary">
                                <i class="ti ti-plus me-1"></i> Add Credential
                            </a>
                        </div>

                        <div class="card-body p-0">
                            @if (session('success'))
                                <div class="alert alert-success alert-dismissible fade show m-3" role="alert">
                                    {{ session('success') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>
                            @endif

                            <!-- Filter Section -->
                            <div class="p-3 border-bottom">
                                <form action="{{ route('admin.credentials.assigned') }}" method="GET">
                                    <div class="row g-3">
                                        <div class="col-md-10">
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
                                        <div class="col-md-2">
                                            <label class="form-label d-block">&nbsp;</label>
                                            <button type="submit" class="btn btn-sm btn-primary w-100">
                                                <i class="ti ti-filter"></i> Filter
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-custom table-centered table-hover mb-0">
                                    <thead class="bg-light bg-opacity-25">
                                        <tr>
                                            <th>#</th>
                                            <th>Platform/Plan</th>
                                            <th>Email</th>
                                            <th>Profile Name</th>
                                            <th>Assigned To</th>
                                            <th>Assigned Date</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($credentials as $credential)
                                            <tr>
                                                <td>{{ $loop->iteration + ($credentials->currentPage() - 1) * $credentials->perPage() }}
                                                </td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        @if ($credential->subscriptionPlan->platform->logo)
                                                            <img src="{{ asset('storage/' . $credential->subscriptionPlan->platform->logo) }}"
                                                                alt="{{ $credential->subscriptionPlan->platform->name }}"
                                                                height="25" class="rounded me-2">
                                                        @endif
                                                        <div>
                                                            <h6 class="mb-0">
                                                                {{ $credential->subscriptionPlan->platform->name }}</h6>
                                                            <small
                                                                class="text-muted">{{ $credential->subscriptionPlan->name }}</small>
                                                        </div>
                                                    </div>
                                                </td>
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
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </td>
                                                <td>{{ $credential->profile_name ?? '-' }}</td>
                                                <td>
                                                    @if ($credential->assignedToUser)
                                                        <div>
                                                            <strong>{{ $credential->assignedToUser->name }}</strong><br>
                                                            <small
                                                                class="text-muted">{{ $credential->assignedToUser->email }}</small>
                                                        </div>
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    {{ $credential->assigned_at ? $credential->assigned_at->format('d M, Y') : '-' }}
                                                </td>
                                                <td>
                                                    <div class="d-flex gap-1">
                                                        <a href="{{ route('admin.credentials.show', $credential->id) }}"
                                                            class="btn btn-sm btn-soft-info" title="View">
                                                            <i class="ti ti-eye"></i>
                                                        </a>
                                                        <a href="{{ route('admin.credentials.edit', $credential->id) }}"
                                                            class="btn btn-sm btn-soft-primary" title="Edit">
                                                            <i class="ti ti-edit"></i>
                                                        </a>
                                                        <button class="btn btn-sm btn-soft-warning"
                                                            onclick="unassignCredential({{ $credential->id }})"
                                                            title="Unassign">
                                                            <i class="ti ti-user-minus"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="7" class="text-center py-4">
                                                    <div class="py-4">
                                                        <i class="ti ti-user-off fs-1 text-muted"></i>
                                                        <p class="text-muted mb-0">No assigned credentials found</p>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        @if ($credentials->hasPages())
                            <div class="card-footer">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        Showing {{ $credentials->firstItem() }} to {{ $credentials->lastItem() }} of
                                        {{ $credentials->total() }} entries
                                    </div>
                                    <div>{{ $credentials->links() }}</div>
                                </div>
                            </div>
                        @endif
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
        document.querySelectorAll('.copy-btn').forEach(button => {
            button.addEventListener('click', function() {
                const text = this.dataset.text;
                navigator.clipboard.writeText(text).then(() => {
                    const icon = this.querySelector('i');
                    icon.classList.remove('ti-copy');
                    icon.classList.add('ti-check');
                    setTimeout(() => {
                        icon.classList.remove('ti-check');
                        icon.classList.add('ti-copy');
                    }, 2000);
                });
            });
        });

        function unassignCredential(credentialId) {
            if (confirm('Are you sure you want to unassign this credential?')) {
                fetch(`/admin/credentials/${credentialId}/unassign`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json',
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            location.reload();
                        } else {
                            alert('Error unassigning credential');
                        }
                    })
                    .catch(error => {
                        alert('Error unassigning credential');
                    });
            }
        }
    </script>
@endpush
