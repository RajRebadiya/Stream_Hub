# Copilot Instructions for Stream_Hub (OTT Platform)

## Project Overview
Stream_Hub is an **Over-The-Top (OTT) streaming platform** built with Laravel 10. It manages subscription-based access to multiple streaming platforms with token-based authentication, order management, and admin controls.

## Architecture & Key Components

### 1. Authentication & Authorization
- **Primary**: Laravel Sanctum API tokens (`laravel/sanctum`)
- **User Tokens**: `UserToken` model tracks platform access with expiration and IP validation
- **Rate Limiting**: Token generation limited to 3 per user per day (see `PlatformController::generateToken()`)
- **Middleware**: `AdminMiddleware` for admin-only routes; `Authenticate` for protected endpoints

### 2. Core Domain Model
```
User → UserProfile, Orders, Subscriptions, UserTokens
  ↓
Platform (Netflix, Disney+, etc.)
  ├── SubscriptionPlan (pricing tiers, duration, max_screens)
  └── UserToken (access credentials with IP tracking)
  
Order → OrderItem, PaymentTransaction, CouponUsage
  ↓
UserSubscription (tracks user's active subscriptions)
```

**Key Files**: `app/Models/` (15+ models), `database/migrations/2026-02-*.php`

### 3. Data Persistence Patterns
- **Eloquent Models**: All models extend `Model`, use `HasFactory` for testing
- **Casting**: Money fields use `decimal:2` (Order, SubscriptionPlan); complex arrays use `array` (features in SubscriptionPlan)
- **Model Hooks**: Boot methods auto-generate slugs (Platform) and calculated fields (discount_percentage)
- **Scope Methods**: `Active()`, `Ordered()` for common queries; scope by status/boolean fields
- **Relationships**: Standard belongsTo/hasMany; define inverses explicitly

**Example**:
```php
// Platform auto-generates slug in boot()
protected static function boot() {
    parent::boot();
    static::creating(function ($platform) {
        if (empty($platform->slug)) $platform->slug = Str::slug($platform->name);
    });
}
```

### 4. API Controller Patterns
- **Location**: `app/Http/Controllers/`
- **Response Format**: Always return JSON with `success` boolean, `message`, and data
- **Validation**: Use `Validator::make()` with detailed error messages (422 status)
- **Error Handling**: Try-catch with 500 status; log exceptions via `Log` facade
- **Pagination**: Default 10 items per page; use `paginate()` for lists
- **Rate Limiting**: Check limits before processing (token generation, etc.)

**Pattern Example** (from AuthController):
```php
$validator = Validator::make($request->all(), [...]);
if ($validator->fails()) return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
try { /* create resource */ } catch (\Exception $e) { return response()->json(..., 500); }
```

### 5. Database Conventions
- **Timestamps**: All models include `created_at`, `updated_at` (default Laravel)
- **Status Columns**: Use enum-like strings: `"active"`, `"inactive"`, `"pending"`
- **Boolean Fields**: Prefix with `is_` (is_active, is_admin) and cast to boolean
- **Foreign Keys**: Use `user_id`, `platform_id`, etc.; defined in migrations with constraints
- **Migrations**: Located in `database/migrations/`, follow timestamp naming (2026_02_*.php)

## Development Workflows

### 1. Running the Application
```bash
php artisan serve              # Start dev server on localhost:8000
npm run dev                    # Build frontend with Vite
php artisan migrate            # Run pending migrations
```

### 2. Testing
```bash
php artisan test                      # Run all tests (PHPUnit)
php artisan test --filter=NameFilter  # Run specific test
```

### 3. Database Management
```bash
php artisan migrate:fresh --seed      # Reset and seed DB
php artisan tinker                    # Interactive shell for testing queries
php artisan make:migration             # Create new migration
```

### 4. Code Generation
```bash
php artisan make:model ModelName -m   # Model + migration
php artisan make:controller NameController   # Create controller
php artisan make:request StoreNameRequest    # Form request (validation)
```

### 5. Admin & Debugging
- **Authentication**: Uses `Auth::user()` in controllers; check `Auth::check()`
- **Token Verification**: `PlatformController::verifyToken()` is public endpoint
- **Admin Routes**: Protected by `AdminMiddleware`; check `$request->user()->is_admin`

## Project-Specific Conventions

### 1. Registration with Immediate Subscriptions (v2.0 - WORKING)
**Flow**: User selects platforms/plans during registration → Auto-order creation → Instant subscription activation

**Key Facts**:
- ✅ User registers with name, email, phone, password
- ✅ Must select at least 1 streaming platform/plan to register
- ✅ Order created with `payment_status: 'free'` and `status: 'completed'`
- ✅ Each selected plan creates: OrderItem + UserSubscription (active)
- ✅ All operations in one database transaction (atomic)
- ✅ Subscriptions start today, end after duration_months
- ✅ Credentials auto-assigned if available in pool
- ✅ Order number unique: `ORD-XXX-{timestamp}`
- ✅ Discount calculated and applied automatically

**Service**: `App\Services\RegistrationSubscriptionService`
- `createRegistrationOrder($user, [$planIds], 'free')` - Creates everything
- `markOrderAsPaid($order, $method, $txnId)` - For future payment gateway integration

**Database Impact on Registration**:
```
User Registration with Plan ID [1]:
  ↓ (All in 1 transaction)
  
1. CREATE users row
2. CREATE orders row (payment_status='free', status='completed')
3. CREATE order_items row (1 per plan)
4. CREATE user_subscriptions row (1 per plan, status='active')
5. UPDATE subscription_credentials (if available: mark 'assigned')

Result = Instant subscription activation!
```

**Example from Real Test**:
```
User: Test User 125930 (ID: 8)
Order: ORD-POS-1770724770
  - Total: ₹80 | Discount: ₹40 | Final: ₹80
  - Status: completed | Payment: free

Order Items: 1 item (Jio Hotstar Basic Plan)

User Subscriptions: 1 active subscription
  - Plan: Basic
  - Period: 2026-02-10 to 2026-03-10 (1 month)
  - Credentials: Ready to assign
```

**API Endpoints**:
```
POST   /api/register
{
  "name": "John Doe",
  "email": "john@example.com", 
  "mobile": "9876543210",
  "password": "securepass123",
  "subscription_plan_ids": [1, 3, 5]  // REQUIRED
}

GET    /api/registration-plans
// Returns: All active platforms with their active plans

Response includes order details:
{
  "success": true,
  "message": "Registration successful! Subscriptions activated.",
  "order": {
    "order_number": "ORD-POS-1770724770",
    "total_amount": "80.00",
    "discount_amount": "40.00", 
    "payment_status": "free",
    "status": "completed",
    "items_count": 1
  }
}
```

### 1. Token Generation Flow (Critical)
- User requests token for specific platform
- System validates: user active, platform active, rate limit (3/day)
- Token = base64(platform_token + '|' + email + '|' + ip + '|' + timestamp)
- Token stored with expiration_date, status, use_status
- External platforms verify token via `/api/verify-token` endpoint (public)

### 2. Subscription Management
- User purchases SubscriptionPlan via Order
- OrderItem links order to platform subscription
- UserSubscription tracks active subscriptions (start/end dates)
- CouponUsage tracks discount redemptions
- PaymentTransaction records all payments

### 3. Asset Handling
- Logo uploads stored via Storage facade (config in `config/filesystems.php`)
- Public storage path: `storage/app/public/` → symlink in `public/storage`
- Use `Storage::disk('public')->put()` for user uploads

### 4. Error Response Pattern
- **Validation**: 422 Unprocessable Entity with `errors` field
- **Not Found**: 404 (use `findOrFail()`)
- **Server Error**: 500 with `message` only (no stack traces in API)
- **Rate Limited**: 429 Too Many Requests

## Key Integration Points

### 1. Mail/Notifications
- `Notification` model stores app notifications
- Use `Notification` model for in-app messages; Laravel Mail for emails
- Config in `config/mail.php`

### 2. CMS/Admin Panel
- `SettingController` manages app-wide settings (config, descriptions)
- `ReportController` generates analytics/reports
- Dashboard at `/admin` route group (not shown in api.php)

### 3. Support System
- `SupportTicket` + `TicketMessage` for help requests
- Each ticket tracks status and messages
- Admins respond via `TicketMessage` creation

### 4. Reviews & Ratings
- `Review` model: platform-specific, user-authored
- Rating scale typically 1-5 (stored as integer/decimal)

## Common Tasks & Patterns

**Adding New Feature:**
1. Create Model + Migration: `php artisan make:model FeatureName -m`
2. Define relationships in Model
3. Create Controller: `php artisan make:controller FeatureController`
4. Add routes in `routes/api.php`
5. Add validation in Controller or Form Request
6. Return consistent JSON response (success, message, data)

**Querying Across Models:**
```php
// User's active subscriptions with platform details
$subs = UserSubscription::where('user_id', auth()->id())
    ->with('platform', 'subscriptionPlan')
    ->where('end_date', '>=', now())
    ->get();
```

**Testing Models:**
- Use `UserFactory` for creating test users
- Tests in `tests/Feature/` and `tests/Unit/`
- Run: `php artisan test`

## Payment Gateway Integration (Future)

**Current State**: Free registration (no payment required)

**To Integrate Payment Gateway**:
1. Create new Controller: `PaymentController`
2. On registration, pass `payment_status: 'pending'` to `createRegistrationOrder()`
3. Redirect to payment page with `order_id`
4. After successful payment from gateway, call:
   ```php
   $this->subscriptionService->markOrderAsPaid(
       $order,
       'card', // or 'upi', 'wallet'
       $transactionId
   );
   ```
5. This marks order as `paid`, activates all subscriptions, assigns credentials

**Example Flow**:
```php
// In PaymentController after gateway success callback
public function handlePaymentSuccess(Request $request)
{
    $order = Order::findOrFail($request->order_id);
    $this->subscriptionService->markOrderAsPaid(
        $order,
        $request->payment_method,
        $request->transaction_id
    );
    // User subscriptions now active
}
```

## Debugging & Troubleshooting

- **500 Errors**: Check `storage/logs/laravel.log` for stack trace
- **DB Errors**: Verify migrations ran; use `php artisan migrate:status`
- **Token Issues**: Inspect `user_tokens` table; check expiration and status
- **Env Config**: Copy `.env.example` to `.env`, run `php artisan key:generate`

## File Structure Reference
- **Routes**: `routes/api.php` (API endpoints)
- **Controllers**: `app/Http/Controllers/` (business logic)
- **Models**: `app/Models/` (db + relationships)
- **Migrations**: `database/migrations/` (schema)
- **Config**: `config/` (app settings, db, mail, etc.)
- **Tests**: `tests/Feature/`, `tests/Unit/`
- **Views**: `resources/views/` (admin panel templates)

---

**Questions?** Refer to Laravel 10 docs: https://laravel.com/docs/10.x
