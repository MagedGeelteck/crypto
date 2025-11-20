<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\PasswordRecoveryController;

Route::get('/clear', function () {
    \Illuminate\Support\Facades\Artisan::call('optimize:clear');
});

// User Support Ticket
Route::controller('TicketController')->prefix('ticket')->name('ticket.')->group(function () {
    Route::get('/', 'supportTicket')->name('index');
    Route::get('new', 'openSupportTicket')->name('open');
    Route::post('create', 'storeSupportTicket')->name('store');
    Route::get('view/{ticket}', 'viewTicket')->name('view');
    Route::post('reply/{id}', 'replyTicket')->name('reply');
    Route::post('close/{id}', 'closeTicket')->name('close');
    Route::get('download/{attachment_id}', 'ticketDownload')->name('download');
});

//Add To Cart
Route::controller('SellController')->group(function () {
    Route::post('product/add-to-cart', 'addToCart')->name('add.to.cart');
    Route::post('product/remove-cart', 'removeCart')->name('remove.cart');
    Route::get('cart', 'cart')->name('cart');
});

Route::controller('SiteController')->middleware([\App\Http\Middleware\PreferPreviousRedirect::class])->group(function () {
    Route::get('/contact', 'contact')->name('contact');
    Route::post('/contact', 'contactSubmit');
    Route::get('/change/{lang?}', 'changeLanguage')->name('lang');

    Route::get('cookie-policy', 'cookiePolicy')->name('cookie.policy');

    Route::get('/cookie/accept', 'cookieAccept')->name('cookie.accept');

    Route::post('subscriber', 'subscriberStore')->name('subscriber.store');

    Route::get('blogs', 'blogs')->name('blog');
    Route::get('blog/{slug}', 'blogDetails')->name('blog.details');

    Route::get('products', 'products')->name('products');
    Route::get('product/{id}-{slug}', 'productDetails')->name('product.details');
    Route::get('load-more/rating', 'loadMoreRating')->name('load.more.rating');

    Route::get('product/filtered', 'productFilter')->name('product.filtered');
    Route::get('load-more/products', 'loadMoreProducts')->name('load.more.products');

    Route::get('subcategory/{id}', 'subcategorySearch')->name('subcategory.search');

    Route::get('policy/{slug}', 'policyPages')->name('policy.pages');

    Route::get('placeholder-image/{size}', 'placeholderImage')
        ->withoutMiddleware(['maintenance', \Illuminate\Session\Middleware\StartSession::class])
        ->name('placeholder.image');
    Route::get('maintenance-mode', 'maintenance')->withoutMiddleware('maintenance')->name('maintenance');

    Route::get('/{slug}', 'pages')->name('pages');
    Route::get('/', 'index')->name('home');
});

// Username + Recovery Word password reset
Route::controller(PasswordRecoveryController::class)->prefix('password')->name('user.password.')->group(function(){
    Route::get('recovery', 'show')->name('recovery');
    Route::post('recovery', 'recover')->name('recovery.submit');
});
