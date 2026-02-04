@extends('admin.layout.template')

@section('content')
    <div class="content-page">
        <div class="container-fluid">
            <div class="page-title-head d-flex align-items-center">
                <div class="flex-grow-1">
                    <h4 class="page-main-title m-0">User Profiles</h4>
                </div>

                <div class="text-end">
                    <ol class="breadcrumb m-0 py-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">OTT Admin</a></li>
                        <li class="breadcrumb-item active">User Profiles</li>
                    </ol>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header justify-content-between">
                            <h4 class="card-title">All Users</h4>
                            <a href="{{ route('admin.user-profiles.create') }}" class="btn btn-sm btn-primary">
                                <i class="ti ti-plus me-1"></i> Add User
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
                                <form action="{{ route('admin.user-profiles.index') }}" method="GET">
                                    <div class="row g-3">
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
                                        <div class="col-md-3">
                                            <label class="form-label">State</label>
                                            <input type="text" name="state" class="form-control form-control-sm"
                                                placeholder="Filter by state..." value="{{ request('state') }}">
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">Search</label>
                                            <input type="text" name="search" class="form-control form-control-sm"
                                                placeholder="Search by name, email, phone..."
                                                value="{{ request('search') }}">
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label d-block">&nbsp;</label>
                                            <button type="submit" class="btn btn-sm btn-primary me-1">
                                                <i class="ti ti-filter"></i> Filter
                                            </button>
                                            <a href="{{ route('admin.user-profiles.index') }}"
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
                                            <th>User</th>
                                            <th>Email</th>
                                            <th>Phone</th>
                                            <th>Location</th>
                                            <th>Status</th>
                                            <th>Joined</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($users as $user)
                                            <tr>
                                                <td>{{ $loop->iteration + ($users->currentPage() - 1) * $users->perPage() }}
                                                </td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="avatar-sm flex-shrink-0 me-2">
                                                            <span
                                                                class="avatar-title bg-soft-primary text-primary rounded-circle">
                                                                {{ strtoupper(substr($user->name, 0, 2)) }}
                                                            </span>
                                                        </div>
                                                        <div>
                                                            <h5 class="fs-14 mb-0">{{ $user->name }}</h5>
                                                            @if ($user->profile)
                                                                <small class="text-muted">ID: #{{ $user->id }}</small>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <span class="text-muted">{{ $user->email }}</span>
                                                    @if ($user->email_verified_at)
                                                        <i class="ti ti-circle-check text-success ms-1"
                                                            title="Verified"></i>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($user->profile && $user->profile->phone)
                                                        <span
                                                            class="badge badge-soft-info">{{ $user->profile->phone }}</span>
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($user->profile)
                                                        @if ($user->profile->city || $user->profile->state)
                                                            <div class="text-muted">
                                                                @if ($user->profile->city)
                                                                    {{ $user->profile->city }}
                                                                @endif
                                                                @if ($user->profile->city && $user->profile->state)
                                                                    ,
                                                                @endif
                                                                @if ($user->profile->state)
                                                                    {{ $user->profile->state }}
                                                                @endif
                                                            </div>
                                                            @if ($user->profile->pincode)
                                                                <small
                                                                    class="text-muted">{{ $user->profile->pincode }}</small>
                                                            @endif
                                                        @else
                                                            <span class="text-muted">-</span>
                                                        @endif
                                                    @else
                                                        <span class="text-muted">No profile</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($user->profile)
                                                        @if ($user->profile->status === 'active')
                                                            <span class="badge badge-soft-success">Active</span>
                                                        @else
                                                            <span class="badge badge-soft-danger">Inactive</span>
                                                        @endif
                                                    @else
                                                        <span class="badge badge-soft-warning">No Profile</span>
                                                    @endif
                                                </td>
                                                <td>{{ $user->created_at->format('d M, Y') }}</td>
                                                <td>
                                                    <div class="d-flex gap-1">
                                                        <a href="{{ route('admin.user-profiles.show', $user->id) }}"
                                                            class="btn btn-sm btn-soft-info" title="View">
                                                            <i class="ti ti-eye"></i>
                                                        </a>
                                                        <a href="{{ route('admin.user-profiles.edit', $user->id) }}"
                                                            class="btn btn-sm btn-soft-primary" title="Edit">
                                                            <i class="ti ti-edit"></i>
                                                        </a>
                                                        <form
                                                            action="{{ route('admin.user-profiles.destroy', $user->id) }}"
                                                            method="POST" class="d-inline"
                                                            onsubmit="return confirm('Are you sure you want to delete this user? This action cannot be undone.');">
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
                                                <td colspan="8" class="text-center py-4">
                                                    <div class="py-4">
                                                        <i class="ti ti-users-off fs-1 text-muted"></i>
                                                        <p class="text-muted mb-0">No users found</p>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        @if ($users->hasPages())
                            <div class="card-footer">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        Showing {{ $users->firstItem() }} to {{ $users->lastItem() }} of
                                        {{ $users->total() }} entries
                                    </div>
                                    <div>
                                        {{ $users->links() }}
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
