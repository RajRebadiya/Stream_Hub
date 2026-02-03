@extends('admin.layout.template')

@section('content')
    <div class="content-page">
        <div class="container-fluid">
            <div class="page-title-head d-flex align-items-center">
                <div class="flex-grow-1">
                    <h4 class="page-main-title m-0">Dashboard</h4>
                </div>

                <div class="text-end">
                    <ol class="breadcrumb m-0 py-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">OTT Admin</a></li>
                        <li class="breadcrumb-item active">Dashboard</li>
                    </ol>
                </div>
            </div>
            {{-- @dd($stats); --}}
            <!-- Statistics Cards -->
            <div class="row">
                <!-- Total Revenue -->
                <div class="col-xxl-3 col-xl-6">
                    <div class="card card-h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start flex-wrap">
                                <div>
                                    <h4 class="fs-13 mb-2 fw-bold text-uppercase text-muted">Total Revenue</h4>
                                    <div class="d-flex align-items-center gap-2 mb-2 py-1">
                                        <div class="avatar-md flex-shrink-0">
                                            <span class="avatar-title text-bg-success rounded-circle">
                                                <i class="ti ti-currency-rupee fs-xxl"></i>
                                            </span>
                                        </div>
                                        <h3 class="mb-0">₹<span>{{ number_format($stats['total_revenue'], 2) }}</span>
                                        </h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Total Orders -->
                <div class="col-xxl-3 col-xl-6">
                    <div class="card card-h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start flex-wrap">
                                <div>
                                    <h4 class="fs-13 mb-2 fw-bold text-uppercase text-muted">Total Orders</h4>
                                    <div class="d-flex align-items-center gap-2 mb-2 py-1">
                                        <div class="avatar-md flex-shrink-0">
                                            <span class="avatar-title text-bg-info rounded-circle">
                                                <i class="ti ti-shopping-cart fs-xxl"></i>
                                            </span>
                                        </div>
                                        <h3 class="mb-0"><span>{{ $stats['total_orders'] }}</span></h3>
                                    </div>
                                </div>
                            </div>
                            <p class="text-muted mb-0">
                                <span class="badge badge-soft-warning">{{ $stats['pending_orders'] }}</span> Pending
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Active Subscriptions -->
                <div class="col-xxl-3 col-xl-6">
                    <div class="card card-h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start flex-wrap">
                                <div>
                                    <h4 class="fs-13 mb-2 fw-bold text-uppercase text-muted">Active Subscriptions</h4>
                                    <div class="d-flex align-items-center gap-2 mb-2 py-1">
                                        <div class="avatar-md flex-shrink-0">
                                            <span class="avatar-title text-bg-primary rounded-circle">
                                                <i class="ti ti-calendar-check fs-xxl"></i>
                                            </span>
                                        </div>
                                        <h3 class="mb-0"><span>{{ $stats['active_subscriptions'] }}</span></h3>
                                    </div>
                                </div>
                            </div>
                            <p class="text-muted mb-0">
                                <span class="badge badge-soft-danger">{{ $stats['expiring_soon'] }}</span> Expiring Soon
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Total Users -->
                <div class="col-xxl-3 col-xl-6">
                    <div class="card card-h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start flex-wrap">
                                <div>
                                    <h4 class="fs-13 mb-2 fw-bold text-uppercase text-muted">Total Users</h4>
                                    <div class="d-flex align-items-center gap-2 mb-2 py-1">
                                        <div class="avatar-md flex-shrink-0">
                                            <span class="avatar-title text-bg-secondary rounded-circle">
                                                <i class="ti ti-users fs-xxl"></i>
                                            </span>
                                        </div>
                                        <h3 class="mb-0"><span>{{ $stats['total_users'] }}</span></h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Orders & Popular Platforms -->
            <div class="row">
                <!-- Recent Orders -->
                <div class="col-xl-8">
                    <div class="card">
                        <div class="card-header justify-content-between">
                            <h4 class="card-title">Recent Orders</h4>
                            <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-primary">
                                View All <i class="ti ti-arrow-right ms-1"></i>
                            </a>
                        </div>

                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-custom table-centered table-hover mb-0">
                                    <thead class="bg-light bg-opacity-25">
                                        <tr>
                                            <th>Order ID</th>
                                            <th>Customer</th>
                                            <th>Platform</th>
                                            <th>Amount</th>
                                            <th>Status</th>
                                            <th>Date</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($recent_orders as $order)
                                            <tr>
                                                <td>#{{ $order->order_number }}</td>
                                                <td>{{ $order->user->name }}</td>
                                                <td>
                                                    @foreach ($order->orderItems as $item)
                                                        <span
                                                            class="badge badge-soft-info">{{ $item->subscriptionPlan->platform->name }}</span>
                                                    @endforeach
                                                </td>
                                                <td>₹{{ number_format($order->final_amount, 2) }}</td>
                                                <td>
                                                    @if ($order->payment_status === 'paid')
                                                        <span class="badge badge-soft-success">Paid</span>
                                                    @elseif($order->payment_status === 'pending')
                                                        <span class="badge badge-soft-warning">Pending</span>
                                                    @else
                                                        <span
                                                            class="badge badge-soft-danger">{{ ucfirst($order->payment_status) }}</span>
                                                    @endif
                                                </td>
                                                <td>{{ $order->created_at->format('d M, Y') }}</td>
                                                <td>
                                                    <a href="{{ route('admin.orders.show', $order->id) }}"
                                                        class="btn btn-sm btn-light">
                                                        <i class="ti ti-eye"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="7" class="text-center py-4">No orders found</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Popular Platforms -->
                <div class="col-xl-4">
                    <div class="card">
                        <div class="card-header justify-content-between">
                            <h4 class="card-title">Popular Platforms</h4>
                        </div>

                        <div class="card-body">
                            @forelse($popular_platforms as $platform)
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <div>
                                        <h5 class="mb-0">{{ $platform->name }}</h5>
                                        <p class="text-muted mb-0 fs-13">{{ $platform->total_sales }} sales</p>
                                    </div>
                                    <div>
                                        @if ($platform->logo)
                                            <img src="{{ asset('storage/' . $platform->logo) }}"
                                                alt="{{ $platform->name }}" height="30">
                                        @endif
                                    </div>
                                </div>
                            @empty
                                <p class="text-center text-muted">No data available</p>
                            @endforelse
                        </div>
                    </div>

                    <!-- Active Subscriptions by Platform -->
                    <div class="card">
                        <div class="card-header justify-content-between">
                            <h4 class="card-title">Subscriptions by Platform</h4>
                        </div>

                        <div class="card-body">
                            @forelse($subscriptions_by_platform as $sub)
                                <div class="mb-3">
                                    <div class="d-flex justify-content-between mb-1">
                                        <span>{{ $sub->name }}</span>
                                        <span class="fw-semibold">{{ $sub->total }}</span>
                                    </div>
                                    <div class="progress progress-sm">
                                        <div class="progress-bar bg-primary"
                                            style="width: {{ ($sub->total / $stats['active_subscriptions']) * 100 }}%">
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <p class="text-center text-muted">No data available</p>
                            @endforelse
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
