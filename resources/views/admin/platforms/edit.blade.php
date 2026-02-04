@extends('admin.layout.template')

@section('content')
    <div class="content-page">
        <div class="container-fluid">
            <div class="page-title-head d-flex align-items-center">
                <div class="flex-grow-1">
                    <h4 class="page-main-title m-0">Edit Platform</h4>
                </div>

                <div class="text-end">
                    <ol class="breadcrumb m-0 py-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">OTT Admin</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.platforms.index') }}">Platforms</a></li>
                        <li class="breadcrumb-item active">Edit Platform</li>
                    </ol>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-header justify-content-between">
                            <h4 class="card-title">Platform Information</h4>
                            <a href="{{ route('admin.platforms.index') }}" class="btn btn-sm btn-secondary">
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

                            <form action="{{ route('admin.platforms.update', $platform->id) }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                @method('PUT')

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="name" class="form-label">Platform Name <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                                            id="name" name="name" value="{{ old('name', $platform->name) }}"
                                            placeholder="Enter platform name" required>
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="slug" class="form-label">Slug <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('slug') is-invalid @enderror"
                                            id="slug" name="slug" value="{{ old('slug', $platform->slug) }}"
                                            placeholder="platform-slug" required>
                                        <small class="text-muted">URL friendly version (auto-generated from name)</small>
                                        @error('slug')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-12 mb-3">
                                        <label for="description" class="form-label">Description</label>
                                        <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description"
                                            rows="4" placeholder="Enter platform description">{{ old('description', $platform->description) }}</textarea>
                                        @error('description')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-12 mb-3">
                                        <label for="token" class="form-label">Token</label>
                                        <textarea class="form-control @error('token') is-invalid @enderror" id="token" name="token" rows="4"
                                            placeholder="Enter platform token">{{ old('token', $platform->token) }}</textarea>
                                        @error('token')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="logo" class="form-label">Platform Logo</label>
                                        <input type="file" class="form-control @error('logo') is-invalid @enderror"
                                            id="logo" name="logo" accept="image/*">
                                        <small class="text-muted">Recommended: PNG or JPG, max 2MB. Leave blank to keep
                                            current logo.</small>
                                        @error('logo')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="sort_order" class="form-label">Sort Order</label>
                                        <input type="number" class="form-control @error('sort_order') is-invalid @enderror"
                                            id="sort_order" name="sort_order"
                                            value="{{ old('sort_order', $platform->sort_order) }}" min="0"
                                            placeholder="0">
                                        <small class="text-muted">Lower numbers appear first</small>
                                        @error('sort_order')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="status" class="form-label">Status <span
                                                class="text-danger">*</span></label>
                                        <select class="form-select @error('status') is-invalid @enderror" id="status"
                                            name="status" required>
                                            <option value="active"
                                                {{ old('status', $platform->status) == 'active' ? 'selected' : '' }}>
                                                Active</option>
                                            <option value="inactive"
                                                {{ old('status', $platform->status) == 'inactive' ? 'selected' : '' }}>
                                                Inactive</option>
                                        </select>
                                        @error('status')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Is Active?</label>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="is_active"
                                                name="is_active" value="1"
                                                {{ old('is_active', $platform->is_active) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="is_active">
                                                Enable this platform
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div class="text-end">
                                    <a href="{{ route('admin.platforms.index') }}" class="btn btn-secondary me-2">
                                        <i class="ti ti-x me-1"></i> Cancel
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="ti ti-device-floppy me-1"></i> Update Platform
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Current Logo</h4>
                        </div>
                        <div class="card-body text-center">
                            @if ($platform->logo)
                                <img id="logoPreview" src="{{ asset('storage/' . $platform->logo) }}"
                                    alt="{{ $platform->name }}" class="img-thumbnail" style="max-height: 200px;">
                            @else
                                <img id="logoPreview" src="{{ asset('assets/images/placeholder.png') }}" alt="No Logo"
                                    class="img-thumbnail" style="max-height: 200px;">
                            @endif
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Platform Statistics</h4>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <div class="d-flex justify-content-between mb-1">
                                    <span class="text-muted">Created:</span>
                                    <span class="fw-semibold">{{ $platform->created_at->format('d M, Y') }}</span>
                                </div>
                            </div>
                            <div class="mb-3">
                                <div class="d-flex justify-content-between mb-1">
                                    <span class="text-muted">Last Updated:</span>
                                    <span class="fw-semibold">{{ $platform->updated_at->format('d M, Y') }}</span>
                                </div>
                            </div>
                            <div class="mb-0">
                                <div class="d-flex justify-content-between mb-1">
                                    <span class="text-muted">Platform ID:</span>
                                    <span class="fw-semibold">#{{ $platform->id }}</span>
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
                                    Use clear and recognizable platform names
                                </li>
                                <li class="mb-2">
                                    <i class="ti ti-check text-success me-2"></i>
                                    Upload high-quality logos for better appearance
                                </li>
                                <li class="mb-2">
                                    <i class="ti ti-check text-success me-2"></i>
                                    Slug will be auto-generated from the name
                                </li>
                                <li class="mb-0">
                                    <i class="ti ti-check text-success me-2"></i>
                                    Set appropriate sort order for display
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
        // Auto-generate slug from name
        document.getElementById('name').addEventListener('input', function() {
            const name = this.value;
            const slug = name.toLowerCase()
                .replace(/[^a-z0-9]+/g, '-')
                .replace(/^-+|-+$/g, '');
            document.getElementById('slug').value = slug;
        });

        // Image preview
        document.getElementById('logo').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('logoPreview').src = e.target.result;
                }
                reader.readAsDataURL(file);
            }
        });
    </script>
@endpush
