<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\CardController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\OfferController;
use App\Http\Controllers\MembershipPlanController;
use App\Http\Controllers\SettingController;

Route::group(['prefix' => 'v1'], function () {
    // Auth
    Route::post('/auth/login', ['App\Http\Controllers\AuthController', 'login']);
    Route::post('/auth/otp-login', ['App\Http\Controllers\AuthController', 'loginWithOtp']);

    // Auth
    Route::post('/auth/logout', ['App\Http\Controllers\AuthController', 'logout'])->middleware(['auth:api']);
    Route::post('/auth/reset-password', ['App\Http\Controllers\AuthController', 'resetPassowrd'])->name('password.reset')->middleware(['auth:api']);

    #product types
    Route::get('/product-types', ['App\Http\Controllers\ProductTypeController', 'index']);

    // App Configuration
    Route::get('/app-config', ['App\Http\Controllers\AppConfigController', 'index']);

    // FAQs
    Route::get('/faqs', ['App\Http\Controllers\FaqController', 'index']);

    // Welcome Pages
    Route::get('/welcome-pages', ['App\Http\Controllers\WelcomePageController', 'index']);

    // Host
    Route::post('/hosts/signup', ['App\Http\Controllers\HostController', 'signup']);



    // OTP
    Route::post('/otp/resend', ['App\Http\Controllers\OtpController', 'resend']);
    Route::post('/otp/verify', ['App\Http\Controllers\OtpController', 'verify']);

    // Categories
    Route::get('/categories', ['App\Http\Controllers\CategoryController', 'index']);
    Route::get('/categories/{category}', ['App\Http\Controllers\CategoryController', 'show']);

    // Payment Methods
    Route::get('/payment-methods', [\App\Http\Controllers\PaymentMethodController::class, 'index']);
    Route::get('/payment-methods/{paymentMethod}', [\App\Http\Controllers\PaymentMethodController::class, 'show']);

    // Payments - WordlNet
    //Route::post('/payments/create', [\App\Http\Controllers\FinanceController::class, 'createPayment']); //on-off payments with option to store card details provided by hosted pages on worldnet
    Route::post('/payments/create-secure-token', [\App\Http\Controllers\FinanceController::class, 'createSecurePaymentToken']); //this is to secure token and save card for subscriptions and future payments

    # Policies
    Route::get("/policies", ['App\Http\Controllers\PolicyController', 'index']);
    Route::get("/policies/{policy}", ['App\Http\Controllers\PolicyController', 'show']);

    Route::get("/videos/{handle}", ['App\Http\Controllers\VideoController', 'index']);
});

Route::group(['prefix' => 'v1', 'middleware' => ['role:host', 'auth:api']], function () {

    #Host
    Route::get('/host/profile', ['App\Http\Controllers\HostController', 'getProfle']);
    Route::put('/host/profile', ['App\Http\Controllers\HostController', 'updateProfile']);
    Route::get('hosts/media', ['App\Http\Controllers\HostController', 'getMedia']);
    Route::post('hosts/media', ['App\Http\Controllers\HostController', 'storeMedia']);

    // Contact Us
    Route::post('/contact-us', ['App\Http\Controllers\ContactUsController', 'store']);

    // Product Resource
    Route::get('products/selectable', ['App\Http\Controllers\ProductController', 'selectable']);
    Route::get('products/{product}/media', ['App\Http\Controllers\ProductController', 'getMedia']);
    Route::post('products/{product}/media', ['App\Http\Controllers\ProductController', 'storeMedia']);
    Route::apiResource('products', App\Http\Controllers\ProductController::class);

    // Product archive
    Route::put('/products/{product}/archive', ['App\Http\Controllers\ProductController', 'archive']);
    Route::put('/products/{product}/restore', ['App\Http\Controllers\ProductController', 'restore']);

    // activity-types routes
    Route::get('/activity-types', ['App\Http\Controllers\ActivityTypeController', 'index']);

    # Form & Types
    Route::get('/forms', ['App\Http\Controllers\FormController', 'index']);
    Route::get('/forms/{form}', ['App\Http\Controllers\FormController', 'getFormTypes']);


    # Schedule Routes
    Route::apiResource('schedules', App\Http\Controllers\ScheduleController::class);
    Route::put('schedules/weekly/{weeklySchedule}/rename', ['App\Http\Controllers\ScheduleController', 'renameWeeklySchedule']);

    #activity
    Route::post('activities/cancel', ['App\Http\Controllers\ActivityController', 'cancel']);
    Route::get('activities/upcoming', ['App\Http\Controllers\ActivityController', 'upcoming']);
    Route::get('activities/{activity}/attendees', ['App\Http\Controllers\ActivityController', 'attendees']);
    Route::apiResource('activities', App\Http\Controllers\ActivityController::class);

    #Time-off
    Route::prefix('timeoff')->group(function () {
        Route::post('/', ['App\Http\Controllers\ActivityController', 'setTimeOff']);
        Route::put('/{timeoff}', ['App\Http\Controllers\ActivityController', 'updateTimeOff']);
        Route::delete('/{timeoff}', ['App\Http\Controllers\ActivityController', 'deleteTimeOff']);
    });
    //TODO - POST ACTIVITY: conflict detection api for product activites overlap

    # Media 
    Route::delete('media/{media}', ['App\Http\Controllers\MediaController', 'delete']);

    #Booking 
    Route::prefix('bookings')->group(function () {
        Route::post('/walk-in', [BookingController::class, 'bookWalkIns']);
        Route::get('/upcoming', [BookingController::class, 'upcoming']);
        Route::get('/cancelled', [BookingController::class, 'cancelled']);
        Route::get('/search', [BookingController::class, 'searchByClient']);
        Route::get('{booking}', [BookingController::class, 'show']);

        # booking attendees
        Route::delete('{booking}/attendee', [BookingController::class, 'deleteAttendee']);
    });

    #Client routes
    Route::apiResource('clients', ClientController::class);
    Route::prefix('clients')->group(function () {
        Route::post('/{client}/archive', [ClientController::class, 'archive']);
        Route::post('/{client}/restore', [ClientController::class, 'restore']);
        Route::post('/invite', [ClientController::class, 'invite']);
        Route::get('/{client}/bookings', [ClientController::class, 'bookings']);
    });

    # Reports routes
    Route::prefix('reports')->group(function () {
        Route::get('/dashboard/clients', [App\Http\Controllers\ReportController::class, 'getDashboardClients']);
        Route::get('/dashboard/bookings', [App\Http\Controllers\ReportController::class, 'getDashboardBookings']);
    });

    # Voucher Types
    Route::get('/voucher-types', [App\Http\Controllers\VoucherTypeController::class, 'index']);
    Route::get('/voucher-types/{voucherType}', [App\Http\Controllers\VoucherTypeController::class, 'show']);


    # Vouchers
    Route::apiResource('vouchers', App\Http\Controllers\VoucherController::class);

    # Modules
    Route::get('/modules', [App\Http\Controllers\ModuleController::class, 'index']);
    Route::get('/modules/{module}', [App\Http\Controllers\ModuleController::class, 'show']);

    # Offer Types
    Route::get('/offer-types', [App\Http\Controllers\OfferTypeController::class, 'index']);
    Route::get('/offer-types/{offerType}', [App\Http\Controllers\OfferTypeController::class, 'show']);

    Route::apiResource('offers', OfferController::class);
    Route::get('offers/download/{offer}', [OfferController::class, 'download'])->name('offers.download');

    // Membership Plans
    Route::get('membership-plans/benefits', [MembershipPlanController::class, 'getMembershipBenefits']);
    Route::match(['put', 'post'], 'membership-plans/{membershipPlan}/archive', [MembershipPlanController::class, 'archive']);
    Route::match(['put', 'post'], 'membership-plans/{membershipPlan}/restore', [MembershipPlanController::class, 'restore']);
    Route::apiResource('membership-plans', MembershipPlanController::class);

    // Settings
    Route::group(['prefix' => 'settings'], function () {
        Route::get('/', [SettingController::class, 'index']);
        Route::get('/{key}', [SettingController::class, 'show']);
        Route::match(['put', 'post'], '/', [SettingController::class, 'store']);
        Route::delete('/{key}', [SettingController::class, 'destroy']);
    });

    // cards 
    Route::apiResource('/cards', CardController::class)->only(['index', 'show']);
});

// Super Admin Routes
Route::group(['prefix' => 'v1', 'middleware' => ['role:super-admin', 'auth:api']], function () {
    // Contact Us
    Route::get('/contact-us', ['App\Http\Controllers\ContactUsController', 'index']);
    Route::get('/contact-us/{contactUs}', ['App\Http\Controllers\ContactUsController', 'show']);
    Route::put('/contact-us/{contactUs}', ['App\Http\Controllers\ContactUsController', 'update']);
});
