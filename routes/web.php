<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\ProfileController; // ⬅ IMPORTANT
use App\Http\Controllers\Auth\PasswordResetLinkController;



/*
|--------------------------------------------------------------------------
| Storefront Routes
|--------------------------------------------------------------------------
*/
Route::get('/fix-admin', function () {
    $user = \App\Models\User::where('email', 'YOUR_ADMIN_EMAIL_HERE')->first();

    if (!$user) return 'Admin user not found.';
    
    $user->assignRole('admin');

    return 'Admin role assigned!';
});

Route::get('/', [ProductController::class, 'index'])->name('home');

// Products
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{slug}', [ProductController::class, 'show'])->name('products.show');

// Cart
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::post('/cart/update', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/{productId}', [CartController::class, 'remove'])->name('cart.remove');
// Show OTP form
Route::get('/forgot-password/verify-otp', [PasswordResetLinkController::class, 'showOtpForm'])
    ->name('password.verify-otp');

// Check OTP
Route::post('/forgot-password/check-otp', [PasswordResetLinkController::class, 'checkOtp'])
    ->name('password.check-otp');

// Show reset password form
Route::get('/reset-password', [PasswordResetLinkController::class, 'showResetForm'])
    ->name('password.reset.form');

// Save new password (THIS WAS MISSING)
Route::post('/reset-password/save', [PasswordResetLinkController::class, 'resetPassword'])
    ->name('password.reset.save');




/*
|--------------------------------------------------------------------------
| Authenticated Customer Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Profile (Breeze Official)
    |--------------------------------------------------------------------------
    */

    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');


    /*
    |--------------------------------------------------------------------------
    | Checkout
    |--------------------------------------------------------------------------
    */

    Route::get('/checkout', [CheckoutController::class, 'show'])->name('checkout.show');
    Route::post('/checkout', [CheckoutController::class, 'process'])->name('checkout.process');

    Route::get('/checkout/paypal/success', [CheckoutController::class, 'paypalSuccess'])
        ->name('checkout.paypal.success');

    Route::get('/checkout/paypal/cancel', [CheckoutController::class, 'paypalCancel'])
        ->name('checkout.paypal.cancel');


    /*
    |--------------------------------------------------------------------------
    | Customer Orders
    |--------------------------------------------------------------------------
    */

    Route::get('/my-orders', function () {
        $orders = auth()->user()->orders()->latest()->get();
        return view('storefront.orders.index', compact('orders'));
    })->name('orders.index');

    Route::get('/my-orders/{order:payment_reference}', function (\App\Models\Order $order) {
        abort_unless($order->user_id === auth()->id(), 403);

        $order->load('items.product');
        return view('storefront.orders.show', compact('order'));
    })->name('orders.show');
});



/*
|--------------------------------------------------------------------------
| Admin Routes (Spatie Role)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

    Route::view('/', 'admin.dashboard')->name('dashboard');

    Route::resource('products', AdminProductController::class);

    Route::get('orders', [AdminOrderController::class, 'index'])->name('orders.index');
    Route::get('orders/{order}', [AdminOrderController::class, 'show'])->name('orders.show');
    Route::patch('orders/{order}/status', [AdminOrderController::class, 'updateStatus'])
        ->name('orders.updateStatus');
});


/*
|--------------------------------------------------------------------------
| Authentication (Breeze)
|--------------------------------------------------------------------------
*/

require __DIR__.'/auth.php';
