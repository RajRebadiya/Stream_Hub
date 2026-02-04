@extends('admin.layout.template')

@section('content')
    <div class="content-page">
        <div class="container-fluid">
            <div class="page-title-head d-flex align-items-center">
                <div class="flex-grow-1">
                    <h4 class="page-main-title m-0">Platforms</h4>
                </div>

                <div class="text-end">
                    <ol class="breadcrumb m-0 py-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">OTT Admin</a></li>
                        <li class="breadcrumb-item active">Platforms</li>
                    </ol>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header justify-content-between">
                            <h4 class="card-title">All Platforms</h4>
                            {{-- <a href="{{ route('admin.platforms.create') }}" class="btn btn-sm btn-primary">
                                <i class="ti ti-plus me-1"></i> Add Platform
                            </a> --}}
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

                            <div class="table-responsive">
                                <table class="table table-custom table-centered table-hover mb-0">
                                    <thead class="bg-light bg-opacity-25">
                                        <tr>
                                            <th>#</th>
                                            <th>Logo</th>
                                            <th>Name</th>
                                            <th>Slug</th>
                                            <th>Description</th>
                                            <th>Sort Order</th>
                                            <th>Status</th>
                                            <th>Active</th>
                                            <th>Created</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($platforms as $platform)
                                            <tr>
                                                <td>{{ $loop->iteration + ($platforms->currentPage() - 1) * $platforms->perPage() }}
                                                </td>
                                                <td>
                                                    @if ($platform->logo)
                                                        <img src="{{ asset('storage/' . $platform->logo) }}"
                                                            alt="{{ $platform->name }}" height="40" class="rounded">
                                                    @else
                                                        <div class="avatar-sm">
                                                            <span
                                                                class="avatar-title bg-soft-secondary text-secondary rounded">
                                                                {{ substr($platform->name, 0, 2) }}
                                                            </span>
                                                        </div>
                                                    @endif
                                                </td>
                                                <td>
                                                    <h5 class="fs-14 mb-0">{{ $platform->name }}</h5>
                                                </td>
                                                <td>
                                                    <span class="badge badge-soft-info">{{ $platform->slug }}</span>
                                                </td>
                                                <td>
                                                    @if ($platform->description)
                                                        {{ Str::limit($platform->description, 50) }}
                                                    @else
                                                        <span class="text-muted">No description</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <span class="badge bg-secondary">{{ $platform->sort_order }}</span>
                                                </td>
                                                <td>
                                                    @if ($platform->status === 'active')
                                                        <span class="badge badge-soft-success">Active</span>
                                                    @else
                                                        <span class="badge badge-soft-danger">Inactive</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($platform->is_active)
                                                        <span class="badge badge-soft-success">
                                                            <i class="ti ti-check"></i> Yes
                                                        </span>
                                                    @else
                                                        <span class="badge badge-soft-danger">
                                                            <i class="ti ti-x"></i> No
                                                        </span>
                                                    @endif
                                                </td>
                                                <td>{{ $platform->created_at->format('d M, Y') }}</td>
                                                <td>
                                                    <div class="d-flex gap-1">
                                                        {{-- @dd($platform->token); --}}
                                                        {{-- Generate Token --}}
                                                        <button class="btn btn-sm btn-soft-success generate-token-btn"
                                                            data-id="{{ $platform->id }}">
                                                            <i class="ti ti-key"></i> Generate Token
                                                        </button>

                                                        {{-- Copy Token --}}

                                                        {{-- @dd(Auth::user()->user_token); --}}
                                                        <button class="btn btn-sm btn-soft-info copy-token-btn"
                                                            data-token="{{ Auth::user()->user_token ?? '' }}"
                                                            {{ empty(Auth::user()->user_token) ? 'disabled' : '' }}>
                                                            <i class="ti ti-copy"></i> Copy Token
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="10" class="text-center py-4">
                                                    <div class="py-4">
                                                        <i class="ti ti-database-off fs-1 text-muted"></i>
                                                        <p class="text-muted mb-0">No platforms found</p>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        @if ($platforms->hasPages())
                            <div class="card-footer">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        Showing {{ $platforms->firstItem() }} to {{ $platforms->lastItem() }} of
                                        {{ $platforms->total() }} entries
                                    </div>
                                    <div>
                                        {{ $platforms->links() }}
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

@push('script')
    <script>
        document.addEventListener('click', function(e) {
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
                                text: 'Your token has been generated successfully!',
                                timer: 2000,
                                showConfirmButton: false
                            });

                            let copyBtn = btn.parentElement.querySelector('.copy-token-btn');
                            copyBtn.dataset.token = data.token;
                            copyBtn.removeAttribute('disabled');

                        } else {
                            console.log('error', data);
                            
                            Swal.fire({
                                icon: 'error',
                                title: 'Token Generation Failed',
                                text: data.message || 'Unknown error'
                            });
                        }

                    });
            }

            if (e.target.closest('.copy-token-btn')) {
                console.log('click for copy');

                let token = e.target.closest('.copy-token-btn').dataset.token;
                console.log('token', token);

                // MODERN WAY
                if (navigator.clipboard && navigator.clipboard.writeText) {
                    navigator.clipboard.writeText(token)
                        .then(() => {
                            alert("Token Copied!");
                        })
                        .catch(err => {
                            console.log("Clipboard error:", err);
                            fallbackCopy(token);
                        });
                } else {
                    // FALLBACK
                    fallbackCopy(token);
                }
            }

            function fallbackCopy(text) {
                const temp = document.createElement("textarea");
                temp.value = text;
                document.body.appendChild(temp);
                temp.select();
                document.execCommand("copy");
                document.body.removeChild(temp);
                alert("Token Copied!");
            }

        });
    </script>
@endpush
