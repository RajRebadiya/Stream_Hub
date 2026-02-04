@extends('admin.layout.template')

@section('content')
    <div class="content-page">
        <div class="container-fluid">
            <div class="page-title-head d-flex align-items-center">
                <div class="flex-grow-1">
                    <h4 class="page-main-title m-0">Edit Order</h4>
                </div>

                <div class="text-end">
                    <ol class="breadcrumb m-0 py-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">OTT Admin</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.orders.index') }}">Orders</a></li>
                        <li class="breadcrumb-item active">Edit Order</li>
                    </ol>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-header justify-content-between">
                            <h4 class="card-title">Order #{{ $order->order_number }}</h4>
                            <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-sm btn-secondary">
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

                            <form action="{{ route('admin.orders.update', $order->id) }}" method="POST">
                                @csrf
                                @method('PUT')

                                <!-- Order Information (Read-only) -->
                                <h5 class="text-muted mb-3">Order Information</h5>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Order Number</label>
                                        <input type="text" class="form-control" value="{{ $order->order_number }}"
                                            readonly>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Customer</label>
                                        <input type="text" class="form-control" value="{{ $order->user->name }}"
                                            readonly>
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Total Amount</label>
                                        <input type="text" class="form-control"
                                            value="₹{{ number_format($order->total_amount, 2) }}" readonly>
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Discount</label>
                                        <input type="text" class="form-control"
                                            value="₹{{ number_format($order->discount_amount, 2) }}" readonly>
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Final Amount</label>
                                        <input type="text" class="form-control fw-bold"
                                            value="₹{{ number_format($order->final_amount, 2) }}" readonly>
                                    </div>
                                </div>

                                <hr class="my-4">

                                <!-- Payment Information -->
                                <h5 class="text-muted mb-3">Payment Information</h5>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="payment_status" class="form-label">Payment Status <span
                                                class="text-danger">*</span></label>
                                        <select class="form-select @error('payment_status') is-invalid @enderror"
                                            id="payment_status" name="payment_status" required>
                                            <option value="pending"
                                                {{ old('payment_status', $order->payment_status) == 'pending' ? 'selected' : '' }}>
                                                Pending</option>
                                            <option value="paid"
                                                {{ old('payment_status', $order->payment_status) == 'paid' ? 'selected' : '' }}>
                                                Paid</option>
                                            <option value="failed"
                                                {{ old('payment_status', $order->payment_status) == 'failed' ? 'selected' : '' }}>
                                                Failed</option>
                                            <option value="refunded"
                                                {{ old('payment_status', $order->payment_status) == 'refunded' ? 'selected' : '' }}>
                                                Refunded</option>
                                        </select>
                                        @error('payment_status')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="payment_method" class="form-label">Payment Method</label>
                                        <select class="form-select @error('payment_method') is-invalid @enderror"
                                            id="payment_method" name="payment_method">
                                            <option value="">Select method...</option>
                                            <option value="UPI"
                                                {{ old('payment_method', $order->payment_method) == 'UPI' ? 'selected' : '' }}>
                                                UPI</option>
                                            <option value="Card"
                                                {{ old('payment_method', $order->payment_method) == 'Card' ? 'selected' : '' }}>
                                                Credit/Debit Card</option>
                                            <option value="Net Banking"
                                                {{ old('payment_method', $order->payment_method) == 'Net Banking' ? 'selected' : '' }}>
                                                Net Banking</option>
                                            <option value="Wallet"
                                                {{ old('payment_method', $order->payment_method) == 'Wallet' ? 'selected' : '' }}>
                                                Wallet</option>
                                            <option value="Cash"
                                                {{ old('payment_method', $order->payment_method) == 'Cash' ? 'selected' : '' }}>
                                                Cash</option>
                                        </select>
                                        @error('payment_method')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-12 mb-3">
                                        <label for="transaction_id" class="form-label">Transaction ID</label>
                                        <input type="text"
                                            class="form-control @error('transaction_id') is-invalid @enderror"
                                            id="transaction_id" name="transaction_id"
                                            value="{{ old('transaction_id', $order->transaction_id) }}"
                                            placeholder="Enter transaction ID">
                                        @error('transaction_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <hr class="my-4">

                                <!-- Order Status -->
                                <h5 class="text-muted mb-3">Order Status</h5>
                                <div class="row">
                                    <div class="col-md-12 mb-3">
                                        <label for="status" class="form-label">Order Status <span
                                                class="text-danger">*</span></label>
                                        <select class="form-select @error('status') is-invalid @enderror" id="status"
                                            name="status" required>
                                            <option value="pending"
                                                {{ old('status', $order->status) == 'pending' ? 'selected' : '' }}>Pending
                                            </option>
                                            <option value="processing"
                                                {{ old('status', $order->status) == 'processing' ? 'selected' : '' }}>
                                                Processing</option>
                                            <option value="completed"
                                                {{ old('status', $order->status) == 'completed' ? 'selected' : '' }}>
                                                Completed</option>
                                            <option value="cancelled"
                                                {{ old('status', $order->status) == 'cancelled' ? 'selected' : '' }}>
                                                Cancelled</option>
                                        </select>
                                        @error('status')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="text-muted">Note: Changing to "Cancelled" will restore the stock for
                                            all items.</small>
                                    </div>
                                </div>

                                <hr class="my-4">

                                <!-- Order Items (Read-only) -->
                                <h5 class="text-muted mb-3">Order Items</h5>
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead class="bg-light">
                                            <tr>
                                                <th>#</th>
                                                <th>Platform</th>
                                                <th>Plan</th>
                                                <th>Quantity</th>
                                                <th>Price</th>
                                                <th>Subtotal</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($order->orderItems as $item)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $item->subscriptionPlan->platform->name }}</td>
                                                    <td>{{ $item->subscriptionPlan->name }}</td>
                                                    <td>{{ $item->quantity }}</td>
                                                    <td>₹{{ number_format($item->price, 2) }}</td>
                                                    <td>₹{{ number_format($item->subtotal, 2) }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                                <div class="text-end">
                                    <a href="{{ route('admin.orders.show', $order->id) }}"
                                        class="btn btn-secondary me-2">
                                        <i class="ti ti-x me-1"></i> Cancel
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="ti ti-device-floppy me-1"></i> Update Order
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <!-- Order Summary -->
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Order Summary</h4>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-muted">Order Number:</span>
                                    <span class="fw-semibold">#{{ $order->order_number }}</span>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-muted">Order Date:</span>
                                    <span class="fw-semibold">{{ $order->created_at->format('d M, Y') }}</span>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-muted">Customer:</span>
                                    <span class="fw-semibold">{{ $order->user->name }}</span>
                                </div>
                                <hr>
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-muted">Items:</span>
                                    <span class="fw-semibold">{{ $order->orderItems->count() }}</span>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-muted">Subtotal:</span>
                                    <span class="fw-semibold">₹{{ number_format($order->total_amount, 2) }}</span>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-muted">Discount:</span>
                                    <span
                                        class="fw-semibold text-danger">-₹{{ number_format($order->discount_amount, 2) }}</span>
                                </div>
                                <hr>
                                <div class="d-flex justify-content-between">
                                    <span class="fw-bold">Total:</span>
                                    <span
                                        class="fw-bold text-success fs-5">₹{{ number_format($order->final_amount, 2) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Current Status -->
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Current Status</h4>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label">Payment Status:</label>
                                <div>
                                    @if ($order->payment_status === 'paid')
                                        <span class="badge badge-soft-success px-3 py-2">Paid</span>
                                    @elseif($order->payment_status === 'pending')
                                        <span class="badge badge-soft-warning px-3 py-2">Pending</span>
                                    @elseif($order->payment_status === 'failed')
                                        <span class="badge badge-soft-danger px-3 py-2">Failed</span>
                                    @else
                                        <span class="badge badge-soft-info px-3 py-2">Refunded</span>
                                    @endif
                                </div>
                            </div>

                            <div class="mb-0">
                                <label class="form-label">Order Status:</label>
                                <div>
                                    @if ($order->status === 'completed')
                                        <span class="badge badge-soft-success px-3 py-2">Completed</span>
                                    @elseif($order->status === 'processing')
                                        <span class="badge badge-soft-info px-3 py-2">Processing</span>
                                    @elseif($order->status === 'cancelled')
                                        <span class="badge badge-soft-danger px-3 py-2">Cancelled</span>
                                    @else
                                        <span class="badge badge-soft-warning px-3 py-2">Pending</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tips -->
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Important Notes</h4>
                        </div>
                        <div class="card-body">
                            <ul class="list-unstyled mb-0">
                                <li class="mb-2">
                                    <i class="ti ti-alert-circle text-warning me-2"></i>
                                    Order items cannot be modified
                                </li>
                                <li class="mb-2">
                                    <i class="ti ti-alert-circle text-warning me-2"></i>
                                    Cancelling restores stock automatically
                                </li>
                                <li class="mb-0">
                                    <i class="ti ti-info-circle text-info me-2"></i>
                                    Update transaction ID when payment is received
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
