@extends('admin.layout.template')

@section('content')
    <div class="content-page">
        <div class="container-fluid">
            <div class="page-title-head d-flex align-items-center">
                <div class="flex-grow-1">
                    <h4 class="page-main-title m-0">Order Details</h4>
                </div>

                <div class="text-end">
                    <ol class="breadcrumb m-0 py-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">OTT Admin</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.orders.index') }}">Orders</a></li>
                        <li class="breadcrumb-item active">Order #{{ $order->order_number }}</li>
                    </ol>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-8">
                    <!-- Order Information -->
                    <div class="card">
                        <div class="card-header justify-content-between">
                            <h4 class="card-title">Order #{{ $order->order_number }}</h4>
                            <div>
                                <a href="{{ route('admin.orders.edit', $order->id) }}" class="btn btn-sm btn-primary me-1">
                                    <i class="ti ti-edit me-1"></i> Edit Order
                                </a>
                                <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-secondary">
                                    <i class="ti ti-arrow-left me-1"></i> Back to List
                                </a>
                            </div>
                        </div>

                        <div class="card-body">
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <h5 class="text-muted mb-3">Order Information</h5>
                                    <table class="table table-borderless">
                                        <tbody>
                                            <tr>
                                                <td class="text-muted" width="40%">Order Number:</td>
                                                <td class="fw-semibold">#{{ $order->order_number }}</td>
                                            </tr>
                                            <tr>
                                                <td class="text-muted">Order Date:</td>
                                                <td>{{ $order->created_at->format('d M, Y H:i A') }}</td>
                                            </tr>
                                            <tr>
                                                <td class="text-muted">Order Status:</td>
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
                                            </tr>
                                            <tr>
                                                <td class="text-muted">Payment Status:</td>
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
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>

                                <div class="col-md-6">
                                    <h5 class="text-muted mb-3">Payment Information</h5>
                                    <table class="table table-borderless">
                                        <tbody>
                                            <tr>
                                                <td class="text-muted" width="40%">Payment Method:</td>
                                                <td>
                                                    @if ($order->payment_method)
                                                        <span
                                                            class="badge badge-soft-info">{{ $order->payment_method }}</span>
                                                    @else
                                                        <span class="text-muted">Not specified</span>
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="text-muted">Transaction ID:</td>
                                                <td>
                                                    @if ($order->transaction_id)
                                                        <code>{{ $order->transaction_id }}</code>
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="text-muted">Total Amount:</td>
                                                <td class="fw-bold">₹{{ number_format($order->total_amount, 2) }}</td>
                                            </tr>
                                            <tr>
                                                <td class="text-muted">Discount:</td>
                                                <td class="text-danger">-₹{{ number_format($order->discount_amount, 2) }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="text-muted fw-bold">Final Amount:</td>
                                                <td class="fw-bold text-success fs-5">
                                                    ₹{{ number_format($order->final_amount, 2) }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <hr>

                            <!-- Customer Information -->
                            <div class="mb-4">
                                <h5 class="text-muted mb-3">Customer Information</h5>
                                <div class="d-flex align-items-center">
                                    <div class="avatar-md flex-shrink-0 me-3">
                                        <span class="avatar-title bg-soft-primary text-primary rounded-circle fs-3">
                                            {{ strtoupper(substr($order->user->name, 0, 2)) }}
                                        </span>
                                    </div>
                                    <div>
                                        <h5 class="mb-1">{{ $order->user->name }}</h5>
                                        <p class="text-muted mb-0">{{ $order->user->email }}</p>
                                        @if ($order->user->profile && $order->user->profile->phone)
                                            <p class="text-muted mb-0">{{ $order->user->profile->phone }}</p>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <hr>

                            <!-- Order Items -->
                            <div class="mb-4">
                                <h5 class="text-muted mb-3">Order Items</h5>
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead class="bg-light">
                                            <tr>
                                                <th>#</th>
                                                <th>Platform</th>
                                                <th>Plan</th>
                                                <th>Duration</th>
                                                <th>Quantity</th>
                                                <th>Price</th>
                                                <th>Subtotal</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($order->orderItems as $item)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            @if ($item->subscriptionPlan->platform->logo)
                                                                <img src="{{ asset('storage/' . $item->subscriptionPlan->platform->logo) }}"
                                                                    alt="{{ $item->subscriptionPlan->platform->name }}"
                                                                    height="25" class="rounded me-2">
                                                            @endif
                                                            {{ $item->subscriptionPlan->platform->name }}
                                                        </div>
                                                    </td>
                                                    <td>{{ $item->subscriptionPlan->name }}</td>
                                                    <td>
                                                        <span
                                                            class="badge bg-info">{{ $item->subscriptionPlan->duration_months }}
                                                            {{ $item->subscriptionPlan->duration_months == 1 ? 'Month' : 'Months' }}</span>
                                                    </td>
                                                    <td>{{ $item->quantity }}</td>
                                                    <td>₹{{ number_format($item->price, 2) }}</td>
                                                    <td class="fw-bold">₹{{ number_format($item->subtotal, 2) }}</td>
                                                    <td>
                                                        @if ($item->status === 'delivered')
                                                            <span class="badge badge-soft-success">Delivered</span>
                                                        @elseif($item->status === 'processing')
                                                            <span class="badge badge-soft-info">Processing</span>
                                                        @elseif($item->status === 'cancelled')
                                                            <span class="badge badge-soft-danger">Cancelled</span>
                                                        @else
                                                            <span class="badge badge-soft-warning">Pending</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td colspan="6" class="text-end fw-bold">Total:</td>
                                                <td colspan="2" class="fw-bold">
                                                    ₹{{ number_format($order->total_amount, 2) }}</td>
                                            </tr>
                                            @if ($order->discount_amount > 0)
                                                <tr>
                                                    <td colspan="6" class="text-end fw-bold">Discount:</td>
                                                    <td colspan="2" class="fw-bold text-danger">
                                                        -₹{{ number_format($order->discount_amount, 2) }}</td>
                                                </tr>
                                            @endif
                                            <tr class="table-active">
                                                <td colspan="6" class="text-end fw-bold fs-5">Final Amount:</td>
                                                <td colspan="2" class="fw-bold text-success fs-5">
                                                    ₹{{ number_format($order->final_amount, 2) }}</td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <!-- Quick Actions -->
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Quick Actions</h4>
                        </div>
                        <div class="card-body">
                            <div class="d-grid gap-2">
                                @if ($order->payment_status !== 'paid')
                                    <button class="btn btn-success" onclick="markAsPaid({{ $order->id }})">
                                        <i class="ti ti-check me-1"></i> Mark as Paid
                                    </button>
                                @endif

                                @if ($order->status !== 'completed')
                                    <button class="btn btn-info" onclick="markAsCompleted({{ $order->id }})">
                                        <i class="ti ti-circle-check me-1"></i> Mark as Completed
                                    </button>
                                @endif

                                @if ($order->status !== 'cancelled')
                                    <button class="btn btn-warning" onclick="cancelOrder({{ $order->id }})">
                                        <i class="ti ti-x me-1"></i> Cancel Order
                                    </button>
                                @endif

                                <a href="{{ route('admin.orders.edit', $order->id) }}" class="btn btn-primary">
                                    <i class="ti ti-edit me-1"></i> Edit Order
                                </a>

                                <button class="btn btn-secondary" onclick="window.print()">
                                    <i class="ti ti-printer me-1"></i> Print Invoice
                                </button>

                                <form action="{{ route('admin.orders.destroy', $order->id) }}" method="POST"
                                    onsubmit="return confirm('Are you sure you want to delete this order? This action cannot be undone.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger w-100">
                                        <i class="ti ti-trash me-1"></i> Delete Order
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Order Timeline -->
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Order Timeline</h4>
                        </div>
                        <div class="card-body">
                            <div class="timeline">
                                <div class="timeline-item">
                                    <div class="timeline-marker bg-success"></div>
                                    <div class="timeline-content">
                                        <h6 class="mb-0">Order Created</h6>
                                        <small class="text-muted">{{ $order->created_at->format('d M, Y H:i A') }}</small>
                                    </div>
                                </div>

                                @if ($order->payment_status === 'paid')
                                    <div class="timeline-item">
                                        <div class="timeline-marker bg-success"></div>
                                        <div class="timeline-content">
                                            <h6 class="mb-0">Payment Received</h6>
                                            <small
                                                class="text-muted">{{ $order->updated_at->format('d M, Y H:i A') }}</small>
                                        </div>
                                    </div>
                                @endif

                                @if ($order->status === 'processing')
                                    <div class="timeline-item">
                                        <div class="timeline-marker bg-info"></div>
                                        <div class="timeline-content">
                                            <h6 class="mb-0">Processing</h6>
                                            <small
                                                class="text-muted">{{ $order->updated_at->format('d M, Y H:i A') }}</small>
                                        </div>
                                    </div>
                                @endif

                                @if ($order->status === 'completed')
                                    <div class="timeline-item">
                                        <div class="timeline-marker bg-success"></div>
                                        <div class="timeline-content">
                                            <h6 class="mb-0">Completed</h6>
                                            <small
                                                class="text-muted">{{ $order->updated_at->format('d M, Y H:i A') }}</small>
                                        </div>
                                    </div>
                                @endif

                                @if ($order->status === 'cancelled')
                                    <div class="timeline-item">
                                        <div class="timeline-marker bg-danger"></div>
                                        <div class="timeline-content">
                                            <h6 class="mb-0">Cancelled</h6>
                                            <small
                                                class="text-muted">{{ $order->updated_at->format('d M, Y H:i A') }}</small>
                                        </div>
                                    </div>
                                @endif
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

@push('styles')
    <style>
        .timeline {
            position: relative;
            padding-left: 30px;
        }

        .timeline-item {
            position: relative;
            padding-bottom: 20px;
        }

        .timeline-item:before {
            content: '';
            position: absolute;
            left: -23px;
            top: 5px;
            bottom: -15px;
            width: 2px;
            background: #e9ecef;
        }

        .timeline-item:last-child:before {
            display: none;
        }

        .timeline-marker {
            position: absolute;
            left: -30px;
            top: 0;
            width: 14px;
            height: 14px;
            border-radius: 50%;
            border: 3px solid #fff;
        }
    </style>
@endpush

@push('scripts')
    <script>
        function markAsPaid(orderId) {
            if (confirm('Mark this order as paid?')) {
                // Implement AJAX call to update payment status
                alert('Feature to be implemented');
            }
        }

        function markAsCompleted(orderId) {
            if (confirm('Mark this order as completed?')) {
                // Implement AJAX call to update order status
                alert('Feature to be implemented');
            }
        }

        function cancelOrder(orderId) {
            if (confirm('Are you sure you want to cancel this order?')) {
                // Implement AJAX call to cancel order
                alert('Feature to be implemented');
            }
        }
    </script>
@endpush
