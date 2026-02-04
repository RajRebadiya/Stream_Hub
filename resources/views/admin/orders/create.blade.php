@extends('admin.layout.template')

@section('content')
    <div class="content-page">
        <div class="container-fluid">
            <div class="page-title-head d-flex align-items-center">
                <div class="flex-grow-1">
                    <h4 class="page-main-title m-0">Create Order</h4>
                </div>

                <div class="text-end">
                    <ol class="breadcrumb m-0 py-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">OTT Admin</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.orders.index') }}">Orders</a></li>
                        <li class="breadcrumb-item active">Create Order</li>
                    </ol>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-header justify-content-between">
                            <h4 class="card-title">Order Information</h4>
                            <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-secondary">
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

                            <form action="{{ route('admin.orders.store') }}" method="POST" id="orderForm">
                                @csrf

                                <!-- Customer Selection -->
                                <h5 class="text-muted mb-3">Customer Information</h5>
                                <div class="row">
                                    <div class="col-md-12 mb-3">
                                        <label for="user_id" class="form-label">Select Customer <span
                                                class="text-danger">*</span></label>
                                        <select class="form-select @error('user_id') is-invalid @enderror" id="user_id"
                                            name="user_id" required>
                                            <option value="">Choose customer...</option>
                                            @foreach ($users as $user)
                                                <option value="{{ $user->id }}"
                                                    {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                                    {{ $user->name }} ({{ $user->email }})
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('user_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <hr class="my-4">

                                <!-- Order Items -->
                                <h5 class="text-muted mb-3">Order Items</h5>
                                <div id="orderItems">
                                    <div class="order-item mb-3 p-3 border rounded" data-item-index="0">
                                        <div class="row align-items-end">
                                            <div class="col-md-6 mb-2">
                                                <label class="form-label">Subscription Plan <span
                                                        class="text-danger">*</span></label>
                                                <select class="form-select plan-select"
                                                    name="items[0][subscription_plan_id]" required>
                                                    <option value="">Select plan...</option>
                                                    @foreach ($plans as $plan)
                                                        <option value="{{ $plan->id }}"
                                                            data-price="{{ $plan->selling_price }}"
                                                            data-platform="{{ $plan->platform->name }}"
                                                            data-duration="{{ $plan->duration_months }}"
                                                            data-stock="{{ $plan->stock_available }}">
                                                            {{ $plan->platform->name }} - {{ $plan->name }}
                                                            ({{ $plan->duration_months }}
                                                            {{ $plan->duration_months == 1 ? 'Month' : 'Months' }}) -
                                                            ₹{{ number_format($plan->selling_price, 2) }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-3 mb-2">
                                                <label class="form-label">Quantity <span
                                                        class="text-danger">*</span></label>
                                                <input type="number" class="form-control quantity-input"
                                                    name="items[0][quantity]" value="1" min="1" required>
                                            </div>
                                            <div class="col-md-2 mb-2">
                                                <label class="form-label">Price</label>
                                                <input type="text" class="form-control item-price" readonly
                                                    value="₹0.00">
                                            </div>
                                            <div class="col-md-1 mb-2">
                                                <button type="button" class="btn btn-danger remove-item w-100"
                                                    onclick="removeItem(this)" disabled>
                                                    <i class="ti ti-x"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <small class="text-muted stock-info"></small>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <button type="button" class="btn btn-sm btn-outline-primary mb-3" onclick="addItem()">
                                    <i class="ti ti-plus me-1"></i> Add Another Item
                                </button>

                                <hr class="my-4">

                                <!-- Payment & Pricing -->
                                <h5 class="text-muted mb-3">Payment & Pricing</h5>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="payment_method" class="form-label">Payment Method</label>
                                        <select class="form-select @error('payment_method') is-invalid @enderror"
                                            id="payment_method" name="payment_method">
                                            <option value="">Select method...</option>
                                            <option value="UPI" {{ old('payment_method') == 'UPI' ? 'selected' : '' }}>
                                                UPI</option>
                                            <option value="Card" {{ old('payment_method') == 'Card' ? 'selected' : '' }}>
                                                Credit/Debit Card</option>
                                            <option value="Net Banking"
                                                {{ old('payment_method') == 'Net Banking' ? 'selected' : '' }}>Net Banking
                                            </option>
                                            <option value="Wallet"
                                                {{ old('payment_method') == 'Wallet' ? 'selected' : '' }}>Wallet</option>
                                            <option value="Cash" {{ old('payment_method') == 'Cash' ? 'selected' : '' }}>
                                                Cash</option>
                                        </select>
                                        @error('payment_method')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="discount_amount" class="form-label">Discount Amount (₹)</label>
                                        <input type="number" step="0.01"
                                            class="form-control @error('discount_amount') is-invalid @enderror"
                                            id="discount_amount" name="discount_amount"
                                            value="{{ old('discount_amount', 0) }}" min="0" placeholder="0.00">
                                        @error('discount_amount')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="payment_status" class="form-label">Payment Status <span
                                                class="text-danger">*</span></label>
                                        <select class="form-select @error('payment_status') is-invalid @enderror"
                                            id="payment_status" name="payment_status" required>
                                            <option value="pending"
                                                {{ old('payment_status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                            <option value="paid"
                                                {{ old('payment_status') == 'paid' ? 'selected' : '' }}>
                                                Paid</option>
                                            <option value="failed"
                                                {{ old('payment_status') == 'failed' ? 'selected' : '' }}>Failed</option>
                                        </select>
                                        @error('payment_status')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="status" class="form-label">Order Status <span
                                                class="text-danger">*</span></label>
                                        <select class="form-select @error('status') is-invalid @enderror" id="status"
                                            name="status" required>
                                            <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>
                                                Pending</option>
                                            <option value="processing"
                                                {{ old('status') == 'processing' ? 'selected' : '' }}>Processing</option>
                                            <option value="completed"
                                                {{ old('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                                        </select>
                                        @error('status')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="text-end">
                                    <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary me-2">
                                        <i class="ti ti-x me-1"></i> Cancel
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="ti ti-device-floppy me-1"></i> Create Order
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
                                    <span class="text-muted">Items:</span>
                                    <span class="fw-semibold" id="summary-items">0</span>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-muted">Subtotal:</span>
                                    <span class="fw-semibold" id="summary-subtotal">₹0.00</span>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-muted">Discount:</span>
                                    <span class="fw-semibold text-danger" id="summary-discount">-₹0.00</span>
                                </div>
                                <hr>
                                <div class="d-flex justify-content-between">
                                    <span class="fw-bold">Total:</span>
                                    <span class="fw-bold text-success fs-5" id="summary-total">₹0.00</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tips -->
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Quick Tips</h4>
                        </div>
                        <div class="card-body">
                            <ul class="list-unstyled mb-0">
                                <li class="mb-2">
                                    <i class="ti ti-check text-success me-2"></i>
                                    Select customer before adding items
                                </li>
                                <li class="mb-2">
                                    <i class="ti ti-check text-success me-2"></i>
                                    Check stock availability before ordering
                                </li>
                                <li class="mb-2">
                                    <i class="ti ti-check text-success me-2"></i>
                                    Discount is optional
                                </li>
                                <li class="mb-0">
                                    <i class="ti ti-check text-success me-2"></i>
                                    Payment method is optional
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
        let itemIndex = 1;

        // Add new item
        function addItem() {
            const itemsContainer = document.getElementById('orderItems');
            const newItem = document.querySelector('.order-item').cloneNode(true);

            newItem.setAttribute('data-item-index', itemIndex);
            newItem.querySelector('.plan-select').name = `items[${itemIndex}][subscription_plan_id]`;
            newItem.querySelector('.plan-select').value = '';
            newItem.querySelector('.quantity-input').name = `items[${itemIndex}][quantity]`;
            newItem.querySelector('.quantity-input').value = '1';
            newItem.querySelector('.item-price').value = '₹0.00';
            newItem.querySelector('.stock-info').textContent = '';
            newItem.querySelector('.remove-item').disabled = false;

            itemsContainer.appendChild(newItem);
            itemIndex++;
            calculateTotal();
        }

        // Remove item
        function removeItem(button) {
            const items = document.querySelectorAll('.order-item');
            if (items.length > 1) {
                button.closest('.order-item').remove();
                calculateTotal();
            }
        }

        // Update price when plan is selected
        document.addEventListener('change', function(e) {
            if (e.target.classList.contains('plan-select')) {
                const selectedOption = e.target.options[e.target.selectedIndex];
                const price = selectedOption.dataset.price || 0;
                const stock = selectedOption.dataset.stock || 0;
                const platform = selectedOption.dataset.platform || '';
                const duration = selectedOption.dataset.duration || '';

                const itemContainer = e.target.closest('.order-item');
                itemContainer.querySelector('.item-price').value = '₹' + parseFloat(price).toFixed(2);

                if (stock > 0) {
                    itemContainer.querySelector('.stock-info').innerHTML =
                        `<i class="ti ti-check-circle text-success"></i> Stock available: ${stock}`;
                } else {
                    itemContainer.querySelector('.stock-info').innerHTML =
                        `<i class="ti ti-alert-circle text-danger"></i> Out of stock!`;
                }

                calculateTotal();
            }
        });

        // Update total when quantity changes
        document.addEventListener('input', function(e) {
            if (e.target.classList.contains('quantity-input') || e.target.id === 'discount_amount') {
                calculateTotal();
            }
        });

        // Calculate total
        function calculateTotal() {
            let subtotal = 0;
            let itemCount = 0;

            document.querySelectorAll('.order-item').forEach(item => {
                const select = item.querySelector('.plan-select');
                const quantity = parseInt(item.querySelector('.quantity-input').value) || 0;

                if (select.value) {
                    const price = parseFloat(select.options[select.selectedIndex].dataset.price) || 0;
                    subtotal += price * quantity;
                    itemCount += quantity;
                }
            });

            const discount = parseFloat(document.getElementById('discount_amount').value) || 0;
            const total = subtotal - discount;

            document.getElementById('summary-items').textContent = itemCount;
            document.getElementById('summary-subtotal').textContent = '₹' + subtotal.toFixed(2);
            document.getElementById('summary-discount').textContent = '-₹' + discount.toFixed(2);
            document.getElementById('summary-total').textContent = '₹' + total.toFixed(2);
        }

        // Initial calculation
        calculateTotal();
    </script>
@endpush
