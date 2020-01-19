<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/login', 'Api\AuthController@login')->name('login.api');

Route::prefix('/product')->group(function () {
    Route::resource('/images', 'Api\ProductImageController');
    Route::resource('/sizes', 'Api\ProductDetailController');
    Route::post('/search', 'Api\ProductController@search');
});

Route::prefix('/category')->group(function () {
    Route::resource('/status', 'Api\StatusCategoryController');
});

Route::resources([
    '/pesanan' => 'Api\PesananController',
    '/users' => 'Api\UsersController',
    '/category' => 'Api\CategoryController',
    '/product' => 'Api\ProductController',
    '/voucher' => 'Api\VoucherController',
    '/wishlist' => 'Api\WishlistController',
    '/cart' => 'Api\CartController',
    '/transaction' => 'Api\TransactionController',
    '/store' => 'Api\StoreController',
]);

Route::middleware('auth:api')->group(function () {
    Route::get('/logout', 'Api\AuthController@logout')->name('logout');
    Route::get('/profile', 'Api\AuthController@profile')->name('profile.api');
    Route::get('/dashboard', 'Api\DashboardSellerController')->name('dashboard.seller.api');
});
