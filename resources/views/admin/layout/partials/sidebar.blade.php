<!-- Sidenav Menu Start -->
<div class="sidenav-menu">
    <!-- Brand Logo -->
    <a href="index.html" class="logo">
        <span class="logo logo-light">
            <span class="logo-lg"><img src="{{ asset('admin/assets/images/logo.png') }}" alt="logo" /></span>
            <span class="logo-sm"><img src="{{ asset('admin/assets/images/logo-sm.png') }}" alt="small logo" /></span>
        </span>

        <span class="logo logo-dark">
            <span class="logo-lg"><img src="{{ asset('admin/assets/images/logo-black.png') }}" alt="dark logo" /></span>
            <span class="logo-sm"><img src="{{ asset('admin/assets/images/logo-sm.png') }}" alt="small logo" /></span>
        </span>
    </a>

    <!-- Sidebar Hover Menu Toggle Button -->
    <button class="button-on-hover">
        <i class="ti ti-circle align-middle"></i>
    </button>

    <!-- Full Sidebar Menu Close Button -->
    <button class="button-close-offcanvas">
        <i class="ti ti-menu-4 align-middle"></i>
    </button>

    <div class="scrollbar" data-simplebar="">
        <div id="user-profile-settings" class="sidenav-user" style="background: url(assets/images/user-bg-pattern.svg)">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <a href="#!" class="link-reset">
                        <img src="{{ asset('admin/assets/images/users/user-1.jpg') }}" alt="user-image"
                            class="rounded-circle mb-2 avatar-md" />
                        <span class="sidenav-user-name fw-bold">David Dev</span>
                        <span class="fs-12 fw-semibold" data-lang="user-role">Art Director</span>
                    </a>
                </div>
                <div>
                    <a class="dropdown-toggle drop-arrow-none link-reset sidenav-user-set-icon"
                        data-bs-toggle="dropdown" data-bs-offset="0,12" href="#!" aria-haspopup="false"
                        aria-expanded="false">
                        <i class="ti ti-settings fs-24 align-middle ms-1"></i>
                    </a>

                    <div class="dropdown-menu">
                        <!-- Header -->
                        <div class="dropdown-header noti-title">
                            <h6 class="text-overflow m-0">Welcome back!</h6>
                        </div>

                        <!-- My Profile -->
                        <a href="#!" class="dropdown-item">
                            <i class="ti ti-user-circle me-1 fs-lg align-middle"></i>
                            <span class="align-middle">Profile</span>
                        </a>

                        <!-- Settings -->
                        <a href="javascript:void(0);" class="dropdown-item">
                            <i class="ti ti-settings-2 me-1 fs-lg align-middle"></i>
                            <span class="align-middle">Account Settings</span>
                        </a>

                        <!-- Lock -->
                        <a href="auth-lock-screen.html" class="dropdown-item">
                            <i class="ti ti-lock me-1 fs-lg align-middle"></i>
                            <span class="align-middle">Lock Screen</span>
                        </a>

                        <!-- Logout -->
                        <a href="javascript:void(0);" class="dropdown-item text-danger fw-semibold">
                            <i class="ti ti-logout me-1 fs-lg align-middle"></i>
                            <span class="align-middle">Log Out</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!--- Sidenav Menu -->
        <div id="sidenav-menu">
            <ul class="side-nav">
                <!-- Admin Sidebar Menu Structure -->

                @if(Auth::user()->is_admin == 1)
                <li class="side-nav-item">
                    <a href="{{ route('dashboard') }}" class="side-nav-link">
                        <span class="menu-icon"><i class="ti ti-dashboard"></i></span>
                        <span class="menu-text">Dashboard</span>
                    </a>
                </li>

                <!-- Platforms Management -->
                <li class="side-nav-item">
                    <a data-bs-toggle="collapse" href="#sidebarPlatforms" aria-expanded="false"
                        aria-controls="sidebarPlatforms" class="side-nav-link">
                        <span class="menu-icon"><i class="ti ti-device-tv"></i></span>
                        <span class="menu-text">Platforms</span>
                        <span class="menu-arrow"></span>
                    </a>
                    <div class="collapse" id="sidebarPlatforms">
                        <ul class="sub-menu">
                            <li class="side-nav-item active">
                                <a href="{{ route('admin.platforms.index') }}" class="side-nav-link">All Platforms</a>
                            </li>
                            <li class="side-nav-item active">
                                <a href="{{ route('admin.platforms.create') }}" class="side-nav-link">Add New
                                    Platform</a>
                            </li>
                        </ul>
                    </div>
                </li>
                @endif

                <li class="side-nav-item">
                    <a href="{{ route('admin.access') }}" class="side-nav-link">
                        <span class="menu-icon"><i class="ti ti-key"></i></span>
                        <span class="menu-text">My Access</span>
                    </a>
                </li>

                @if(Auth::user()->is_admin == 1)
                <!-- Subscription Plans -->
                <li class="side-nav-item">
                    <a data-bs-toggle="collapse" href="#sidebarPlans" aria-expanded="false" aria-controls="sidebarPlans"
                        class="side-nav-link">
                        <span class="menu-icon"><i class="ti ti-package"></i></span>
                        <span class="menu-text">Subscription Plans</span>
                        <span class="menu-arrow"></span>
                    </a>
                    <div class="collapse" id="sidebarPlans">
                        <ul class="sub-menu">
                            <li class="side-nav-item active">
                                <a href="{{ route('admin.subscription-plans.index') }}" class="side-nav-link">All
                                    Plans</a>
                            </li>
                            <li class="side-nav-item active">
                                <a href="{{ route('admin.subscription-plans.create') }}" class="side-nav-link">Add
                                    New
                                    Plan</a>
                            </li>
                        </ul>
                    </div>
                </li>

                <!-- Credentials Management -->
                <li class="side-nav-item">
                    <a data-bs-toggle="collapse" href="#sidebarCredentials" aria-expanded="false"
                        aria-controls="sidebarCredentials" class="side-nav-link">
                        <span class="menu-icon"><i class="ti ti-key"></i></span>
                        <span class="menu-text">Credentials</span>
                        <span class="menu-arrow"></span>
                    </a>
                    <div class="collapse" id="sidebarCredentials">
                        <ul class="sub-menu">
                            <li class="side-nav-item active">
                                <a href="{{ route('admin.credentials.index') }}" class="side-nav-link">All
                                    Credentials</a>
                            </li>
                            <li class="side-nav-item active">
                                <a href="{{ route('admin.credentials.create') }}" class="side-nav-link">Add New
                                    Credential</a>
                            </li>
                            <li class="side-nav-item active">
                                <a href="{{ route('admin.credentials.available') }}"
                                    class="side-nav-link">Available</a>
                            </li>
                            <li class="side-nav-item active">
                                <a href="{{ route('admin.credentials.assigned') }}"
                                    class="side-nav-link">Assigned</a>
                            </li>
                        </ul>
                    </div>
                </li>

                <!-- Orders Management -->
                <li class="side-nav-item">
                    <a data-bs-toggle="collapse" href="#sidebarOrders" aria-expanded="false"
                        aria-controls="sidebarOrders" class="side-nav-link">
                        <span class="menu-icon"><i class="ti ti-shopping-cart"></i></span>
                        <span class="menu-text">Orders</span>
                        <span class="menu-arrow"></span>
                    </a>
                    <div class="collapse" id="sidebarOrders">
                        <ul class="sub-menu">
                            <li class="side-nav-item active">
                                <a href="{{ route('admin.orders.index') }}" class="side-nav-link">All Orders</a>
                            </li>
                            <li class="side-nav-item active">
                                <a href="{{ route('admin.orders.create') }}" class="side-nav-link">New Orders</a>
                            </li>
                            {{-- <li class="side-nav-item active">
                                <a href="{{ route('admin.orders.pending') }}" class="side-nav-link">Pending
                                    Orders</a>
                            </li class="side-nav-item active">
                            <li class="side-nav-item active">
                                <a href="{{ route('admin.orders.completed') }}" class="side-nav-link">Completed
                                    Orders</a>
                            </li>
                            <li class="side-nav-item active">
                                <a href="{{ route('admin.orders.cancelled') }}" class="side-nav-link">Cancelled
                                    Orders</a>
                            </li> --}}
                        </ul>
                    </div>
                </li>

                <!-- User Subscriptions -->
                <li class="side-nav-item">
                    <a data-bs-toggle="collapse" href="#sidebarSubscriptions" aria-expanded="false"
                        aria-controls="sidebarSubscriptions" class="side-nav-link">
                        <span class="menu-icon"><i class="ti ti-calendar-check"></i></span>
                        <span class="menu-text">User Subscriptions</span>
                        <span class="menu-arrow"></span>
                    </a>
                    <div class="collapse" id="sidebarSubscriptions">
                        <ul class="sub-menu">
                            <li class="side-nav-item active">
                                <a href="{{ route('admin.subscriptions.index') }}" class="side-nav-link">All
                                    Subscriptions</a>
                            </li>
                            <li class="side-nav-item active">
                                <a href="{{ route('admin.subscriptions.active') }}" class="side-nav-link">Active</a>
                            </li>
                            <li class="side-nav-item active">
                                <a href="{{ route('admin.subscriptions.expiring') }}" class="side-nav-link">Expiring
                                    Soon</a>
                            </li>
                            <li class="side-nav-item active">
                                <a href="{{ route('admin.subscriptions.expired') }}"
                                    class="side-nav-link">Expired</a>
                            </li>
                        </ul>
                    </div>
                </li>

                <!-- Users Management -->
                <li class="side-nav-item">
                    <a data-bs-toggle="collapse" href="#sidebarUsers" aria-expanded="false"
                        aria-controls="sidebarUsers" class="side-nav-link">
                        <span class="menu-icon"><i class="ti ti-users"></i></span>
                        <span class="menu-text">Users</span>
                        <span class="menu-arrow"></span>
                    </a>
                    <div class="collapse" id="sidebarUsers">
                        <ul class="sub-menu">
                            <li class="side-nav-item active">
                                <a href="{{ route('admin.users.index') }}" class="side-nav-link">All Users</a>
                            </li>
                            <li class="side-nav-item active">
                                <a href="{{ route('admin.users.create') }}" class="side-nav-link">Add New User</a>
                            </li>
                        </ul>
                    </div>
                </li>
                <!-- Users Management -->
                <li class="side-nav-item">
                    <a data-bs-toggle="collapse" href="#sidebarUsers" aria-expanded="false"
                        aria-controls="sidebarUsers" class="side-nav-link">
                        <span class="menu-icon"><i class="ti ti-users"></i></span>
                        <span class="menu-text">Users Profile</span>
                        <span class="menu-arrow"></span>
                    </a>
                    <div class="collapse" id="sidebarUsers">
                        <ul class="sub-menu">
                            <li class="side-nav-item active">
                                <a href="{{ route('admin.user-profiles.index') }}" class="side-nav-link">All Users
                                    Profile</a>
                            </li>
                            <li class="side-nav-item active">
                                <a href="{{ route('admin.user-profiles.create') }}" class="side-nav-link">Add New
                                    User Profile</a>
                            </li>
                        </ul>
                    </div>
                </li>

                <!-- Coupons Management -->
                <li class="side-nav-item">
                    <a data-bs-toggle="collapse" href="#sidebarCoupons" aria-expanded="false"
                        aria-controls="sidebarCoupons" class="side-nav-link">
                        <span class="menu-icon"><i class="ti ti-discount"></i></span>
                        <span class="menu-text">Coupons</span>
                        <span class="menu-arrow"></span>
                    </a>
                    <div class="collapse" id="sidebarCoupons">
                        <ul class="sub-menu">
                            <li class="side-nav-item active">
                                <a href="{{ route('admin.coupons.index') }}" class="side-nav-link">All Coupons</a>
                            </li>
                            <li class="side-nav-item active">
                                <a href="{{ route('admin.coupons.create') }}" class="side-nav-link">Add New
                                    Coupon</a>
                            </li>
                            <li class="side-nav-item active">
                                <a href="{{ route('admin.coupons.usage') }}" class="side-nav-link">Coupon Usage</a>
                            </li>
                        </ul>
                    </div>
                </li>

                <!-- Payments -->
                <li class="side-nav-item">
                    <a href="{{ route('admin.payments.index') }}" class="side-nav-link">
                        <span class="menu-icon"><i class="ti ti-credit-card"></i></span>
                        <span class="menu-text">Payments</span>
                    </a>
                </li>

                <!-- Reviews Management -->
                <li class="side-nav-item">
                    <a data-bs-toggle="collapse" href="#sidebarReviews" aria-expanded="false"
                        aria-controls="sidebarReviews" class="side-nav-link">
                        <span class="menu-icon"><i class="ti ti-star"></i></span>
                        <span class="menu-text">Reviews</span>
                        <span class="menu-arrow"></span>
                    </a>
                    <div class="collapse" id="sidebarReviews">
                        <ul class="sub-menu">
                            <li class="side-nav-item active">
                                <a href="{{ route('admin.reviews.index') }}" class="side-nav-link">All Reviews</a>
                            </li>
                            <li class="side-nav-item active">
                                <a href="{{ route('admin.reviews.pending') }}" class="side-nav-link">Pending
                                    Approval</a>
                            </li>
                            <li class="side-nav-item active">
                                <a href="{{ route('admin.reviews.approved') }}" class="side-nav-link">Approved</a>
                            </li>
                        </ul>
                    </div>
                </li>

                <!-- Support Tickets -->
                <li class="side-nav-item">
                    <a data-bs-toggle="collapse" href="#sidebarSupport" aria-expanded="false"
                        aria-controls="sidebarSupport" class="side-nav-link">
                        <span class="menu-icon"><i class="ti ti-headset"></i></span>
                        <span class="menu-text">Support Tickets</span>
                        <span class="menu-arrow"></span>
                    </a>
                    <div class="collapse" id="sidebarSupport">
                        <ul class="sub-menu">
                            <li class="side-nav-item active">
                                <a href="{{ route('admin.tickets.index') }}" class="side-nav-link">All Tickets</a>
                            </li>
                            <li class="side-nav-item active">
                                <a href="{{ route('admin.tickets.open') }}" class="side-nav-link">Open Tickets</a>
                            </li>
                            <li class="side-nav-item active">
                                <a href="{{ route('admin.tickets.closed') }}" class="side-nav-link">Closed
                                    Tickets</a>
                            </li>
                        </ul>
                    </div>
                </li>

                <!-- Reports -->
                <li class="side-nav-item">
                    <a data-bs-toggle="collapse" href="#sidebarReports" aria-expanded="false"
                        aria-controls="sidebarReports" class="side-nav-link">
                        <span class="menu-icon"><i class="ti ti-chart-bar"></i></span>
                        <span class="menu-text">Reports</span>
                        <span class="menu-arrow"></span>
                    </a>
                    <div class="collapse" id="sidebarReports">
                        <ul class="sub-menu">
                            <li class="side-nav-item active">
                                <a href="{{ route('admin.reports.sales') }}" class="side-nav-link">Sales Report</a>
                            </li>
                            <li class="side-nav-item active">
                                <a href="{{ route('admin.reports.subscriptions') }}"
                                    class="side-nav-link">Subscription Report</a>
                            </li>
                            <li class="side-nav-item active">
                                <a href="{{ route('admin.reports.revenue') }}" class="side-nav-link">Revenue
                                    Report</a>
                            </li>
                        </ul>
                    </div>
                </li>

                <!-- Settings -->
                <li class="side-nav-item">
                    <a data-bs-toggle="collapse" href="#sidebarSettings" aria-expanded="false"
                        aria-controls="sidebarSettings" class="side-nav-link">
                        <span class="menu-icon"><i class="ti ti-settings"></i></span>
                        <span class="menu-text">Settings</span>
                        <span class="menu-arrow"></span>
                    </a>
                    <div class="collapse" id="sidebarSettings">
                        <ul class="sub-menu">
                            <li class="side-nav-item active">
                                <a href="{{ route('admin.settings.general') }}" class="side-nav-link">General
                                    Settings</a>
                            </li>
                            <li class="side-nav-item active">
                                <a href="{{ route('admin.settings.payment') }}" class="side-nav-link">Payment
                                    Settings</a>
                            </li>
                            <li class="side-nav-item active">
                                <a href="{{ route('admin.settings.email') }}" class="side-nav-link">Email
                                    Settings</a>
                            </li>
                        </ul>
                    </div>
                </li>
                @endif

                <!-- Logout -->
                <li class="side-nav-item">
                    <a href="{{ route('logout') }}" class="side-nav-link">
                        <span class="menu-icon"><i class="ti ti-logout"></i></span>
                        <span class="menu-text">Logout</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>
<!-- Sidenav Menu End -->
