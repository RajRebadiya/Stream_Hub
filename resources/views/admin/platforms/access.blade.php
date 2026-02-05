@extends('admin.layout.template')

@section('content')
    <div class="content-page">
        <div class="container-fluid">
            <div class="page-title-head d-flex align-items-center">
                <div class="flex-grow-1">
                    <h4 class="page-main-title m-0">My Access & Tokens</h4>
                </div>

                <div class="text-end">
                    <ol class="breadcrumb m-0 py-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">OTT Admin</a></li>
                        <li class="breadcrumb-item active">My Access</li>
                    </ol>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header justify-content-between">
                            <h4 class="card-title">Generate Token</h4>
                        </div>

                        <div class="card-body">
                            @if (session('success'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    {{ session('success') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"
                                        aria-label="Close"></button>
                                </div>
                            @endif

                            @if (session('error'))
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    {{ session('error') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"
                                        aria-label="Close"></button>
                                </div>
                            @endif

                            <div class="table-responsive">
                                <table class="table table-custom table-centered table-hover mb-0">
                                    <thead class="bg-light bg-opacity-25">
                                        <tr>
                                            <th>#</th>
                                            <th>Platform</th>
                                            <th>Token Status</th>
                                            <th>IP Address</th>
                                            <th>Created</th>
                                            <th>Expires</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($tokens as $token)
                                            <tr>
                                                <td>{{ $loop->iteration + ($tokens->currentPage() - 1) * $tokens->perPage() }}
                                                </td>
                                                <td>
                                                    <div class="d-flex align-items-center gap-2">
                                                        @if ($token->platform->logo)
                                                            <img src="{{ asset('storage/' . $token->platform->logo) }}"
                                                                alt="{{ $token->platform->name }}" height="32"
                                                                class="rounded">
                                                        @else
                                                            <div class="avatar-sm">
                                                                <span
                                                                    class="avatar-title bg-soft-secondary text-secondary rounded">
                                                                    {{ substr($token->platform->name, 0, 2) }}
                                                                </span>
                                                            </div>
                                                        @endif
                                                        <div>
                                                            <h5 class="fs-14 mb-0">{{ $token->platform->name }}</h5>
                                                            <span class="fs-12 text-muted">{{ $token->platform->slug }}</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    @if ($token->status === 'active')
                                                        <span class="badge badge-soft-success">
                                                            <i class="ti ti-check-circle me-1"></i>Active
                                                        </span>
                                                    @else
                                                        <span class="badge badge-soft-danger">
                                                            <i class="ti ti-circle-x me-1"></i>Inactive
                                                        </span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <span class="fs-13">{{ $token->ip_address ?? 'N/A' }}</span>
                                                </td>
                                                <td>
                                                    <span class="fs-13">{{ $token->created_at->format('d M, Y H:i') }}</span>
                                                </td>
                                                <td>
                                                    @if ($token->expires_at)
                                                        @if ($token->expires_at->isPast())
                                                            <span class="badge badge-soft-danger">Expired</span>
                                                        @elseif ($token->expires_at->diffInDays(now()) <= 3)
                                                            <span class="badge badge-soft-warning">
                                                                {{ $token->expires_at->format('d M, Y') }}
                                                            </span>
                                                        @else
                                                            <span class="fs-13">{{ $token->expires_at->format('d M, Y') }}</span>
                                                        @endif
                                                    @else
                                                        <span class="text-muted">N/A</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="d-flex gap-1">
                                                        @if ($token->status === 'active')
                                                            <button class="btn btn-sm btn-soft-info copy-token-btn"
                                                                data-token="{{ $token->token }}"
                                                                title="Copy Token">
                                                                <i class="ti ti-copy"></i>
                                                            </button>
                                                            <button class="btn btn-sm btn-soft-danger revoke-token-btn"
                                                                data-id="{{ $token->id }}"
                                                                title="Revoke Token">
                                                                <i class="ti ti-trash"></i>
                                                            </button>
                                                        @else
                                                            <span class="text-muted fs-12">Revoked</span>
                                                        @endif
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="7" class="text-center py-4">
                                                    <div class="py-4">
                                                        <i class="ti ti-key-off fs-1 text-muted"></i>
                                                        <p class="text-muted mb-0">No tokens generated yet</p>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        @if ($tokens->hasPages())
                            <div class="card-footer">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        Showing {{ $tokens->firstItem() }} to {{ $tokens->lastItem() }} of
                                        {{ $tokens->total() }} entries
                                    </div>
                                    <div>
                                        {{ $tokens->links() }}
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header justify-content-between">
                            <h4 class="card-title">Platform Access</h4>
                        </div>

                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-custom table-centered table-hover mb-0">
                                    <thead class="bg-light bg-opacity-25">
                                        <tr>
                                            <th>#</th>
                                            <th>Platform</th>
                                            <th>Description</th>
                                            <th>Status</th>
                                            <th>Daily Limit</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($platforms as $platform)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>
                                                    <div class="d-flex align-items-center gap-2">
                                                        @if ($platform->logo)
                                                            <img src="{{ asset('storage/' . $platform->logo) }}"
                                                                alt="{{ $platform->name }}" height="32"
                                                                class="rounded">
                                                        @else
                                                            <div class="avatar-sm">
                                                                <span
                                                                    class="avatar-title bg-soft-secondary text-secondary rounded">
                                                                    {{ substr($platform->name, 0, 2) }}
                                                                </span>
                                                            </div>
                                                        @endif
                                                        <div>
                                                            <h5 class="fs-14 mb-0">{{ $platform->name }}</h5>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    @if ($platform->description)
                                                        {{ Str::limit($platform->description, 40) }}
                                                    @else
                                                        <span class="text-muted fs-12">No description</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($platform->status === 'active' && $platform->is_active)
                                                        <span class="badge badge-soft-success">Available</span>
                                                    @else
                                                        <span class="badge badge-soft-danger">Inactive</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <span class="badge bg-info">3 / Day</span>
                                                </td>
                                                <td>
                                                    <button class="btn btn-sm btn-soft-success generate-token-btn"
                                                        data-id="{{ $platform->id }}">
                                                        <i class="ti ti-key"></i> Generate Token
                                                    </button>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6" class="text-center py-4">
                                                    <div class="py-4">
                                                        <i class="ti ti-database-off fs-1 text-muted"></i>
                                                        <p class="text-muted mb-0">No platforms available</p>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
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

@push('script')
    <script>
        document.addEventListener('click', function(e) {
            // Generate Token
            if (e.target.closest('.generate-token-btn')) {
                let btn = e.target.closest('.generate-token-btn');
                let id = btn.dataset.id;

                fetch(`/admin/platforms/${id}/generate-token`, {
                        method: "POST",
                        headers: {
                            "X-CSRF-TOKEN": "{{ csrf_token() }}",
                            "Accept": "application/json"
                        }
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.status) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Token Generated',
                                text: `Token generated successfully! Remaining: ${data.remaining}/3`,
                                timer: 3000,
                                showConfirmButton: false
                            }).then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Failed',
                                text: data.message || 'Unknown error'
                            });
                        }
                    });
            }

            // Copy Token
            if (e.target.closest('.copy-token-btn')) {
                let token = e.target.closest('.copy-token-btn').dataset.token;

                if (navigator.clipboard && navigator.clipboard.writeText) {
                    navigator.clipboard.writeText(token)
                        .then(() => {
                            Swal.fire({
                                icon: 'success',
                                title: 'Copied!',
                                text: 'Token copied to clipboard',
                                timer: 1500,
                                showConfirmButton: false
                            });
                        })
                        .catch(err => {
                            console.log("Clipboard error:", err);
                            fallbackCopy(token);
                        });
                } else {
                    fallbackCopy(token);
                }
            }

            // Revoke Token
            if (e.target.closest('.revoke-token-btn')) {
                let btn = e.target.closest('.revoke-token-btn');
                let id = btn.dataset.id;

                Swal.fire({
                    title: 'Revoke Token?',
                    text: 'This token will be deactivated and cannot be used anymore.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, Revoke it!',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        fetch(`/admin/tokens/${id}/revoke`, {
                                method: "POST",
                                headers: {
                                    "X-CSRF-TOKEN": "{{ csrf_token() }}",
                                    "Accept": "application/json"
                                }
                            })
                            .then(res => res.json())
                            .then(data => {
                                if (data.status) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Revoked!',
                                        text: 'Token has been revoked successfully',
                                        timer: 1500,
                                        showConfirmButton: false
                                    }).then(() => {
                                        location.reload();
                                    });
                                } else {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Failed',
                                        text: data.message || 'Unknown error'
                                    });
                                }
                            });
                    }
                });
            }

            function fallbackCopy(text) {
                const temp = document.createElement("textarea");
                temp.value = text;
                document.body.appendChild(temp);
                temp.select();
                document.execCommand("copy");
                document.body.removeChild(temp);
                Swal.fire({
                    icon: 'success',
                    title: 'Copied!',
                    text: 'Token copied to clipboard',
                    timer: 1500,
                    showConfirmButton: false
                });
            }
        });
    </script>
@endpush
