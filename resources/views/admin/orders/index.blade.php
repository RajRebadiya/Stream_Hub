@extends('admin.layout.template')

@section('content')
    <div class="content-page">
        <div class="container-fluid">
            <div class="page-title-head d-flex align-items-center">
                <div class="flex-grow-1">
                    <h4 class="page-main-title m-0">Orders</h4>
                </div>

                <div class="text-end">
                    <ol class="breadcrumb m-0 py-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">OTT Admin</a></li>
                        <li class="breadcrumb-item active">Orders</li>
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
                                    <h6 class="text-muted mb-1">Total Orders</h6>
                                    <h3 class="mb-0">{{ $statistics['total_orders'] ?? 0 }}</h3>
                                </div>
                                <div class="avatar-sm flex-shrink-0">
                                    <span class="avatar-title bg-soft-primary text-primary rounded-circle">
                                        <i class="ti ti-shopping-cart fs-xxl"></i>
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
                                    <h6 class="text-muted mb-1">Paid Orders</h6>
                                    <h3 class="mb-0">{{ $statistics['paid_orders'] ?? 0 }}</h3>
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
                    <div class="card card-h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-muted mb-1">Pending Orders</h6>
                                    <h3 class="mb-0">{{ $statistics['pending_orders'] ?? 0 }}</h3>
                                </div>
                                <div class="avatar-sm flex-shrink-0">
                                    <span class="avatar-title bg-soft-warning text-warning rounded-circle">
                                        <i class="ti ti-clock fs-xxl"></i>
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
                                    <h6 class="text-muted mb-1">Total Revenue</h6>
                                    <h3 class="mb-0">₹{{ number_format($statistics['total_revenue'] ?? 0, 2) }}</h3>
                                </div>
                                <div class="avatar-sm flex-shrink-0">
                                    <span class="avatar-title bg-soft-info text-info rounded-circle">
                                        <i class="ti ti-currency-rupee fs-xxl"></i>
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
                            <h4 class="card-title">All Orders</h4>
                            <a href="{{ route('admin.orders.create') }}" class="btn btn-sm btn-primary">
                                <i class="ti ti-plus me-1"></i> Create Order
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
                                <form action="{{ route('admin.orders.index') }}" method="GET">
                                    <div class="row g-3">
                                        <div class="col-md-2">
                                            <label class="form-label">Payment Status</label>
                                            <select name="payment_status" class="form-select form-select-sm">
                                                <option value="">All</option>
                                                <option value="pending"
                                                    {{ request('payment_status') == 'pending' ? 'selected' : '' }}>Pending
                                                </option>
                                                <option value="paid"
                                                    {{ request('payment_status') == 'paid' ? 'selected' : '' }}>Paid
                                                </option>
                                                <option value="failed"
                                                    {{ request('payment_status') == 'failed' ? 'selected' : '' }}>Failed
                                                </option>
                                                <option value="refunded"
                                                    {{ request('payment_status') == 'refunded' ? 'selected' : '' }}>
                                                    Refunded</option>
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label">Order Status</label>
                                            <select name="status" class="form-select form-select-sm">
                                                <option value="">All</option>
                                                <option value="pending"
                                                    {{ request('status') == 'pending' ? 'selected' : '' }}>Pending
                                                </option>
                                                <option value="processing"
                                                    {{ request('status') == 'processing' ? 'selected' : '' }}>Processing
                                                </option>
                                                <option value="completed"
                                                    {{ request('status') == 'completed' ? 'selected' : '' }}>Completed
                                                </option>
                                                <option value="cancelled"
                                                    {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled
                                                </option>
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label">Date From</label>
                                            <input type="date" name="date_from" class="form-control form-control-sm"
                                                value="{{ request('date_from') }}">
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label">Date To</label>
                                            <input type="date" name="date_to" class="form-control form-control-sm"
                                                value="{{ request('date_to') }}">
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label">Search</label>
                                            <input type="text" name="search" class="form-control form-control-sm"
                                                placeholder="Order #, customer name..." value="{{ request('search') }}">
                                        </div>
                                        <div class="col-md-1">
                                            <label class="form-label d-block">&nbsp;</label>
                                            <button type="submit" class="btn btn-sm btn-primary w-100">
                                                <i class="ti ti-filter"></i>
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
                                            <th>Order Number</th>
                                            <th>Customer</th>
                                            <th>Items</th>
                                            <th>Amount</th>
                                            <th>Payment</th>
                                            <th>Status</th>
                                            <th>Date</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($orders as $order)
                                            <tr>
                                                <td>{{ $loop->iteration + ($orders->currentPage() - 1) * $orders->perPage() }}
                                                </td>
                                                <td>
                                                    <a href="{{ route('admin.orders.show', $order->id) }}"
                                                        class="fw-semibold text-primary">
                                                        #{{ $order->order_number }}
                                                    </a>
                                                </td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="avatar-sm flex-shrink-0 me-2">
                                                            <span
                                                                class="avatar-title bg-soft-primary text-primary rounded-circle">
                                                                {{ strtoupper(substr($order->user->name, 0, 2)) }}
                                                            </span>
                                                        </div>
                                                        <div>
                                                            <h5 class="fs-14 mb-0">{{ $order->user->name }}</h5>
                                                            <small class="text-muted">{{ $order->user->email }}</small>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <span class="badge bg-info">{{ $order->orderItems->count() }}
                                                        {{ $order->orderItems->count() == 1 ? 'Item' : 'Items' }}</span>
                                                </td>
                                                <td>
                                                    <div>
                                                        <span
                                                            class="fw-bold text-success">₹{{ number_format($order->final_amount, 2) }}</span>
                                                        @if ($order->discount_amount > 0)
                                                            <br>
                                                            <small class="text-muted">Discount:
                                                                ₹{{ number_format($order->discount_amount, 2) }}</small>
                                                        @endif
                                                    </div>
                                                </td>
                                                <td>
                                                    @if ($order->payment_status === 'paid')
                                                        <span class="badge badge-soft-success">Paid</span>
                                                    @elseif($order->payment_status === 'pending')
                                                        <span class="badge badge-soft-warning">Pending</span>
                                                    @elseif($order->payment_status === 'failed')
                                                        <span class="badge badge-soft-danger">Failed</span>
                                                    @else
                                                        <span class="badge badge-soft-info">Refunded</span>
                                                    @endif
                                                    @if ($order->payment_method)
                                                        <br><small class="text-muted">{{ $order->payment_method }}</small>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($order->status === 'completed')
                                                        <span class="badge badge-soft-success">Completed</span>
                                                    @elseif($order->status === 'processing')
                                                        <span class="badge badge-soft-info">Processing</span>
                                                    @elseif($order->status === 'cancelled')
                                                        <span class="badge badge-soft-danger">Cancelled</span>
                                                    @else
                                                        <span class="badge badge-soft-warning">Pending</span>
                                                    @endif
                                                </td>
                                                <td>{{ $order->created_at->format('d M, Y') }}</td>
                                                <td>
                                                    <div class="d-flex gap-1">
                                                        <a href="{{ route('admin.orders.show', $order->id) }}"
                                                            class="btn btn-sm btn-soft-info" title="View">
                                                            <i class="ti ti-eye"></i>
                                                        </a>
                                                        <a href="{{ route('admin.orders.edit', $order->id) }}"
                                                            class="btn btn-sm btn-soft-primary" title="Edit">
                                                            <i class="ti ti-edit"></i>
                                                        </a>
                                                        <form action="{{ route('admin.orders.destroy', $order->id) }}"
                                                            method="POST" class="d-inline"
                                                            onsubmit="return confirm('Are you sure you want to delete this order?');">
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
                                                <td colspan="9" class="text-center py-4">
                                                    <div class="py-4">
                                                        <i class="ti ti-shopping-cart-off fs-1 text-muted"></i>
                                                        <p class="text-muted mb-0">No orders found</p>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        @if ($orders->hasPages())
                            <div class="card-footer">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        Showing {{ $orders->firstItem() }} to {{ $orders->lastItem() }} of
                                        {{ $orders->total() }} entries
                                    </div>
                                    <div>
                                        {{ $orders->links() }}
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
