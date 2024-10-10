<?php

use App\Http\Livewire\MidtransPaymentPage;
use App\Livewire\Auth\ForgotPasswordPage;
use App\Livewire\Auth\LoginPage;
use App\Livewire\Auth\RegisterPage;
use App\Livewire\Auth\ResetPasswordPage;
use App\Livewire\CancelPage;
use App\Livewire\CartPage;
use App\Livewire\CategoriesPage;
use App\Livewire\CheckoutPage;
use App\Livewire\HomePage;
use App\Livewire\MidtransWebhookPage;
use App\Livewire\MyAccountPage;
use App\Livewire\MyOrderDetailPage;
use App\Livewire\OrdersPage;
use App\Livewire\ProductDetailPage;
use App\Livewire\ProductRating;
use App\Livewire\ProductsPage;
use App\Livewire\StripePaymentPage;
use App\Livewire\SuccessPage;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get("/", HomePage::class);
Route::get("/cart", CartPage::class);
Route::get("/categories", CategoriesPage::class);
Route::get("/products", ProductsPage::class);
Route::get("/products/{slug}", ProductDetailPage ::class);



Route::middleware('guest')->group(function (){
    Route::get("/login", LoginPage::class)->name('login');
    Route::get("/register", RegisterPage::class);
    Route::get("/forgot", ForgotPasswordPage::class)->name('password.request');
    Route::get("/reset/{token}", ResetPasswordPage::class)->name('password.reset');
});

Route::middleware('auth')->group(function (){
    Route::get("/logout", function(){
        auth()->logout();
        return redirect("/");
    });
    Route::get("/checkout", CheckoutPage::class);
    Route::get("/my-orders", OrdersPage::class);
    Route::get("/my-orders/{order_id}", MyOrderDetailPage::class)->name('my-orders.show');
    Route::get('/checkout', CheckoutPage::class)->name('checkout');
    Route::get('/success', SuccessPage::class)->name('success');
    Route::get("/cancel", CancelPage::class)->name("cancel");

    //Stripe
    Route::get('/stripe/success', [CheckoutPage::class, 'handleStripeSuccess'])->name('stripe.success');
    Route::get('/stripe/success', [CheckoutPage::class, 'handleStripeSuccess'])->name('stripe.success');

    //Midtrans
    Route::get('/midtrans/callback', [CheckoutPage::class, 'handleMidtransCallback'])->name('midtrans.callback');

    //Rating
    // Route::get('/rate-product/{id}', [ProductRating::class, 'showRatingForm'])->name('rate.product');
    Route::get('/rate-product/{productId}/{orderId}', ProductRating::class)->name('rate.product');

    //Account
    Route::get('/my-account', MyAccountPage::class)->name('my-account');
});
