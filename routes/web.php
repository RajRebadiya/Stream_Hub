<?php
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PlatformController;
use App\Http\Controllers\SubscriptionPlanController;
use App\Http\Controllers\SubscriptionCredentialController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\UserSubscriptionController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\SupportTicketController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SettingController;

// Guest Routes (Jo login nahi hai)
// Route::middleware('guest')->group(function () {
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

Route::get('/login', function () {
    return view('welcome');
})->name('login');
Route::post('/login', [AuthController::class, 'login']);
// });

// Authenticated Routes (Jo login hai)
Route::middleware('auth')->group(function () {
    Route::any('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('admin/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});

Route::get('/', function () {
    return view('welcome');
})->name('home');


Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {

    // Platforms
    Route::prefix('platforms')->name('platforms.')->group(function () {
        Route::get('/', [PlatformController::class, 'index'])->name('index');
        Route::get('/create', [PlatformController::class, 'create'])->name('create');
        Route::post('/', [PlatformController::class, 'store'])->name('store');
        Route::get('/{platform}/edit', [PlatformController::class, 'edit'])->name('edit');
        Route::put('/{platform}', [PlatformController::class, 'update'])->name('update');
        Route::delete('/{platform}', [PlatformController::class, 'destroy'])->name('destroy');
        Route::post('/{platform}/toggle-status', [PlatformController::class, 'toggleStatus'])->name('toggle-status');
    });
    Route::post('platforms/{platform}/toggle-status', [PlatformController::class, 'toggleStatus'])->name('platforms.toggle-status');

    // Subscription Plans
    Route::prefix('plans')->name('subscription-plans.')->group(function () {
        Route::get('/', [SubscriptionPlanController::class, 'index'])->name('index');
        Route::get('/create', [SubscriptionPlanController::class, 'create'])->name('create');
        Route::post('/', [SubscriptionPlanController::class, 'store'])->name('store');
        Route::get('/{subscriptionPlan}', [SubscriptionPlanController::class, 'show'])->name('show');
        Route::get('/{subscriptionPlan}/edit', [SubscriptionPlanController::class, 'edit'])->name('edit');
        Route::put('/{subscriptionPlan}', [SubscriptionPlanController::class, 'update'])->name('update');
        Route::delete('/{subscriptionPlan}', [SubscriptionPlanController::class, 'destroy'])->name('destroy');
        Route::post('/{subscriptionPlan}/toggle-status', [SubscriptionPlanController::class, 'toggleStatus'])->name('toggle-status');
        Route::post('/{subscriptionPlan}/update-stock', [SubscriptionPlanController::class, 'updateStock'])->name('update-stock');
    });
    Route::post('subscription-plans/{subscriptionPlan}/toggle-status', [SubscriptionPlanController::class, 'toggleStatus'])->name('subscription-plans.toggle-status');
    Route::post('subscription-plans/{subscriptionPlan}/update-stock', [SubscriptionPlanController::class, 'updateStock'])->name('subscription-plans.update-stock');

    // Subscription Credentials
    Route::prefix('credentials')->name('credentials.')->group(function () {
        Route::get('/', [SubscriptionCredentialController::class, 'index'])->name('index');
        Route::get('/create', [SubscriptionCredentialController::class, 'create'])->name('create');
        Route::post('/', [SubscriptionCredentialController::class, 'store'])->name('store');
        Route::get('/{credential}/edit', [SubscriptionCredentialController::class, 'edit'])->name('edit');
        Route::put('/{credential}', [SubscriptionCredentialController::class, 'update'])->name('update');
        Route::delete('/{credential}', [SubscriptionCredentialController::class, 'destroy'])->name('destroy');
        Route::get('/available', [SubscriptionCredentialController::class, 'available'])->name('available');
        Route::get('/assigned', [SubscriptionCredentialController::class, 'assigned'])->name('assigned');
        Route::post('/{credential}/unassign', [SubscriptionCredentialController::class, 'unassign'])->name('unassign');
    });

    // Orders
    Route::prefix('orders')->name('orders.')->group(function () {
        Route::get('/', [OrderController::class, 'index'])->name('index');
        Route::get('/pending', [OrderController::class, 'pending'])->name('pending');
        Route::get('/completed', [OrderController::class, 'completed'])->name('completed');
        Route::get('/cancelled', [OrderController::class, 'cancelled'])->name('cancelled');
        Route::get('/{order}', [OrderController::class, 'show'])->name('show');
        Route::post('/{order}/update-status', [OrderController::class, 'updateStatus'])->name('update-status');
        Route::delete('/{order}', [OrderController::class, 'destroy'])->name('destroy');
    });

    // User Subscriptions
    Route::prefix('subscriptions')->name('subscriptions.')->group(function () {
        Route::get('/', [UserSubscriptionController::class, 'index'])->name('index');
        Route::get('/active', [UserSubscriptionController::class, 'active'])->name('active');
        Route::get('/expiring', [UserSubscriptionController::class, 'expiring'])->name('expiring');
        Route::get('/expired', [UserSubscriptionController::class, 'expired'])->name('expired');
        Route::get('/{subscription}', [UserSubscriptionController::class, 'show'])->name('show');
        Route::post('/{subscription}/assign-credentials', [UserSubscriptionController::class, 'assignCredentials'])->name('assign-credentials');
        Route::post('/{subscription}/extend', [UserSubscriptionController::class, 'extend'])->name('extend');
        Route::delete('/{subscription}', [UserSubscriptionController::class, 'destroy'])->name('destroy');
    });

    // Users
    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('index');
        Route::get('/create', [UserController::class, 'create'])->name('create');
        Route::post('/', [UserController::class, 'store'])->name('store');
        Route::get('/{user}', [UserController::class, 'show'])->name('show');
        Route::get('/{user}/edit', [UserController::class, 'edit'])->name('edit');
        Route::put('/{user}', [UserController::class, 'update'])->name('update');
        Route::delete('/{user}', [UserController::class, 'destroy'])->name('destroy');
        Route::post('/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('toggle-status');
    });

    // Coupons
    Route::prefix('coupons')->name('coupons.')->group(function () {
        Route::get('/', [CouponController::class, 'index'])->name('index');
        Route::get('/create', [CouponController::class, 'create'])->name('create');
        Route::post('/', [CouponController::class, 'store'])->name('store');
        Route::get('/{coupon}/edit', [CouponController::class, 'edit'])->name('edit');
        Route::put('/{coupon}', [CouponController::class, 'update'])->name('update');
        Route::delete('/{coupon}', [CouponController::class, 'destroy'])->name('destroy');
        Route::get('/usage', [CouponController::class, 'usage'])->name('usage');
        Route::post('/{coupon}/toggle-status', [CouponController::class, 'toggleStatus'])->name('toggle-status');
    });

    // Payments
    Route::prefix('payments')->name('payments.')->group(function () {
        Route::get('/', [PaymentController::class, 'index'])->name('index');
        Route::get('/{payment}', [PaymentController::class, 'show'])->name('show');
    });

    // Reviews
    Route::prefix('reviews')->name('reviews.')->group(function () {
        Route::get('/', [ReviewController::class, 'index'])->name('index');
        Route::get('/pending', [ReviewController::class, 'pending'])->name('pending');
        Route::get('/approved', [ReviewController::class, 'approved'])->name('approved');
        Route::post('/{review}/approve', [ReviewController::class, 'approve'])->name('approve');
        Route::post('/{review}/reject', [ReviewController::class, 'reject'])->name('reject');
        Route::delete('/{review}', [ReviewController::class, 'destroy'])->name('destroy');
    });

    // Support Tickets
    Route::prefix('tickets')->name('tickets.')->group(function () {
        Route::get('/', [SupportTicketController::class, 'index'])->name('index');
        Route::get('/open', [SupportTicketController::class, 'open'])->name('open');
        Route::get('/closed', [SupportTicketController::class, 'closed'])->name('closed');
        Route::get('/{ticket}', [SupportTicketController::class, 'show'])->name('show');
        Route::post('/{ticket}/reply', [SupportTicketController::class, 'reply'])->name('reply');
        Route::post('/{ticket}/close', [SupportTicketController::class, 'close'])->name('close');
        Route::delete('/{ticket}', [SupportTicketController::class, 'destroy'])->name('destroy');
    });

    // Reports
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/sales', [ReportController::class, 'sales'])->name('sales');
        Route::get('/subscriptions', [ReportController::class, 'subscriptions'])->name('subscriptions');
        Route::get('/revenue', [ReportController::class, 'revenue'])->name('revenue');
    });

    // Settings
    Route::prefix('settings')->name('settings.')->group(function () {
        Route::get('/general', [SettingController::class, 'general'])->name('general');
        Route::post('/general', [SettingController::class, 'updateGeneral'])->name('general.update');
        Route::get('/payment', [SettingController::class, 'payment'])->name('payment');
        Route::post('/payment', [SettingController::class, 'updatePayment'])->name('payment.update');
        Route::get('/email', [SettingController::class, 'email'])->name('email');
        Route::post('/email', [SettingController::class, 'updateEmail'])->name('email.update');
    });
});