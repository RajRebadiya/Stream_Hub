@extends('admin.layout.template')

@section('content')
    <div class="content-page">
        <div class="container-fluid">
            <!-- Page Header -->
            <div class="page-title-head d-flex align-items-center">
                <div class="flex-grow-1">
                    <h4 class="page-main-title m-0">User Details</h4>
                </div>

                <div class="text-end">
                    <ol class="breadcrumb m-0 py-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.users.index') }}">Users</a></li>
                        <li class="breadcrumb-item active">Details</li>
                    </ol>
                </div>
            </div>

            <!-- User Details -->
            <div class="row">
                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-body text-center">
                            <div class="avatar-xl mx-auto mb-3">
                                <span class="avatar-title bg-primary rounded-circle fs-1">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </span>
                            </div>

                            <h4 class="mb-1">{{ $user->name }}</h4>
                            <p class="text-muted mb-3">{{ $user->email }}</p>

                            <span class="badge bg-{{ $user->role == 'admin' ? 'primary' : 'secondary' }} fs-6">
                                {{ ucfirst($user->role) }}
                            </span>

                            <hr class="my-4">

                            <div class="row text-start">
                                <div class="col-6">
                                    <p class="text-muted mb-1">User ID</p>
                                    <h5 class="fw-semibold">#{{ $user->id }}</h5>
                                </div>
                                <div class="col-6">
                                    <p class="text-muted mb-1">Joined</p>
                                    <h5 class="fw-semibold">{{ $user->created_at->format('Y-m-d') }}</h5>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">User Information</h4>
                        </div>

                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label text-muted">Full Name</label>
                                        <p class="fw-semibold">{{ $user->name }}</p>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label text-muted">Email Address</label>
                                        <p class="fw-semibold">{{ $user->email }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label text-muted">Account Role</label>
                                        <p>
                                            <span class="badge bg-{{ $user->role == 'admin' ? 'primary' : 'secondary' }}">
                                                {{ ucfirst($user->role) }}
                                            </span>
                                        </p>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label text-muted">Account Created</label>
                                        <p class="fw-semibold">{{ $user->created_at->format('F d, Y') }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label text-muted">Last Updated</label>
                                        <p class="fw-semibold">{{ $user->updated_at->format('F d, Y') }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex gap-2 mt-4">
                                <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-primary">
                                    <i class="ti ti-edit me-1"></i> Edit User
                                </a>
                                <a href="{{ route('admin.users.index') }}" class="btn btn-light">
                                    Back to List
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
