<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'HomeController@index');
Route::get('/get-city-register/{province}', 'Auth\RegisterController@get_city');
Route::get('/get-notification', 'NotificationController@notification');
Route::get('/get-notification-dashboard', 'NotificationController@notification_dashboard');
Route::get('/update-notification', 'NotificationController@fetch');
Route::get('/kontak', function(){
  return view('buyer.kontak');
});

Route::middleware('isPembeli')->group(function () {

    Route::get('/checkout', 'CheckoutController@fill_form')->name('checkout');
    Route::get('/get-city/{province}', 'CheckoutController@get_city');
    Route::post('/get-price', 'CheckoutController@get_expedition');
    Route::post('/check-voucher', 'CheckoutController@check_voucher');
    Route::post('/proses-checkout', 'CheckoutController@proses_checkout');

    Route::resource('cart', 'CartController')->only([
        'index', 'store', 'destroy', 'update'
    ]);

    Route::get('/transaksi', 'TransaksiBuyerController@index');
    Route::post('/transaksi/terima', 'TransaksiBuyerController@terima');
    Route::post('/transaksi/cancel', 'TransaksiBuyerController@cancel');

    Route::get('/bayar/{code}', 'PembayaranController@index');
    Route::post('/upload-pembayaran', 'PembayaranController@upload_pembayaran');
    Route::post('/update-pembayaran', 'PembayaranController@upload_pembayaran');
    Route::get('/verifikasi/{code}', 'VerifikasiBuyerController@index');

});

Route::get('/produk', 'ProductController@index');
Route::post('/search', 'ProductController@search');

Route::get('/toko/{id}', 'StoreController@index');
Route::post('/hubungi-toko', 'ChatController@hubungi_toko');

Route::get('/detail-produk/{id}', 'ProductController@detail')->name('detail-produk');
Route::get('/get-stock-status/{id}/{size}', 'ProductController@get_stock');
Route::post('/input-review', 'ProductController@review')->middleware('auth');

Route::post('/proses-wishlist', 'WishlistController@proses_wishlist');
Route::get('/wishlist', 'WishlistController@index');

Auth::routes();
Route::get('auth/{provider}', 'Auth\AuthController@redirectToProvider');
Route::get('auth/{provider}/callback', 'Auth\AuthController@handleProviderCallback');

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/profile', 'ProfileController@index');
Route::post('/profile/edit', 'ProfileController@edit');
Route::post('/profile/ubah-password', 'ProfileController@password');
Route::post('/profile/avatar', 'ProfileController@avatar');

Route::middleware('isSuperAdmin')->group(function () {
    Route::get('/manajemen-admin', 'ManajemenAdminController@index');
    Route::post('/manajemen-admin/tambah', 'ManajemenAdminController@tambah');
    Route::post('/manajemen-admin/delete', 'ManajemenAdminController@delete');
    Route::post('/manajemen-admin/edit', 'ManajemenAdminController@edit');
    Route::post('/manajemen-admin/get-edit-data', 'ManajemenAdminController@get');
    Route::get('/manajemen-admin/search', 'ManajemenAdminController@search')->name('admin.search');
});

Route::middleware('isAdmin')->group(function () {

    Route::get('/mengelola-voucher', 'VoucherController@index');
    Route::get('/mengelola-voucher/search', 'VoucherController@search')->name('voucher.search');
    Route::post('/mengelola-voucher/tambah', 'VoucherController@tambah');
    Route::post('/mengelola-voucher/delete', 'VoucherController@delete');
    Route::post('/mengelola-voucher/edit', 'VoucherController@edit');
    Route::post('/mengelola-voucher/get-edit-data', 'VoucherController@get');

    Route::get('/mengelola-kategori', 'KategoriController@index');
    Route::get('/mengelola-kategori/searcj', 'KategoriController@search')->name('kategori.search');
    Route::post('/mengelola-kategori/get-edit-data', 'KategoriController@get');
    Route::post('/mengelola-kategori/konfirmasi', 'KategoriController@konfirmasi');
    Route::post('/mengelola-kategori/tambah', 'KategoriController@tambah');
    Route::post('/mengelola-kategori/delete', 'KategoriController@delete');
    Route::post('/mengelola-kategori/edit', 'KategoriController@edit');

    Route::get('/mengelola-transaksi', 'TransaksiController@index')->name('transaction.index');
    Route::post('/mengelola-transaksi/verification', 'TransaksiController@verification')->name('transaction.verification');
    Route::post('/mengelola-transaksi/cancel', 'TransaksiController@cancel')->name('transaction.cancel');

    Route::get('/manajemen-penjual', 'ManajemenPenjualController@index');
    Route::get('/manajemen-penjual/saerch', 'ManajemenPenjualController@search')->name('penjual.search');
    Route::post('/manajemen-penjual/tambah', 'ManajemenPenjualController@tambah');
    Route::post('/manajemen-penjual/delete', 'ManajemenPenjualController@delete');
    Route::post('/manajemen-penjual/edit', 'ManajemenPenjualController@edit');
    Route::post('/manajemen-penjual/get-edit-data', 'ManajemenPenjualController@get');

    Route::get('/mengelola-toko', 'MengelolaTokoController@index');
    Route::get('/mengelola-toko/search', 'MengelolaTokoController@search')->name('toko.search');
    Route::post('/mengelola-toko/tambah', 'MengelolaTokoController@tambah');
    Route::post('/mengelola-toko/delete', 'MengelolaTokoController@delete');
    Route::post('/mengelola-toko/edit', 'MengelolaTokoController@edit');
    Route::post('/mengelola-toko/get-edit-data', 'MengelolaTokoController@get');

    Route::get('/mengelola-produk-admin', 'MengelolaProdukController@index');
    Route::get('/mengelola-produk-admin/search', 'MengelolaProdukController@search')->name('admin.produk.search');
    Route::post('/mengelola-produk-admin/tambah', 'MengelolaProdukController@tambah');
    Route::post('/mengelola-produk-admin/delete', 'MengelolaProdukController@delete');
    Route::post('/mengelola-produk-admin/edit', 'MengelolaProdukController@edit');
    Route::post('/mengelola-produk-admin/get-edit-data', 'MengelolaProdukController@get');
    Route::post('/mengelola-produk-admin/add-images', 'MengelolaProdukController@add_images');
    Route::post('/mengelola-produk-admin/delete-images', 'MengelolaProdukController@delete_images');
    Route::post('/mengelola-produk-admin/get-category-status', 'MengelolaProdukController@get_category_status')->name('category.status.admin');

});

Route::get('/chat', 'ChatController@index');
Route::post('/chat/send-message', 'ChatController@send_message');
Route::post('/chat/pilih-user', 'ChatController@pilih_user');

Route::middleware('isPenjual')->group(function () {

    Route::get('/mengelola-produk', 'MengelolaProdukController@index');
    Route::get('/mengelola-produk/search', 'MengelolaProdukController@search')->name('produk.search');
    Route::post('/mengelola-produk/tambah', 'MengelolaProdukController@tambah');
    Route::post('/mengelola-produk/delete', 'MengelolaProdukController@delete');
    Route::post('/mengelola-produk/edit', 'MengelolaProdukController@edit');
    Route::post('/mengelola-produk/get-edit-data', 'MengelolaProdukController@get');
    Route::post('/mengelola-produk/add-images', 'MengelolaProdukController@add_images')->name('add-images.penjual');
    Route::post('/mengelola-produk/delete-images', 'MengelolaProdukController@delete_images');
    Route::post('/mengelola-produk/get-category-status', 'MengelolaProdukController@get_category_status')->name('category.status');

    Route::get('/list-pesanan', 'TransaksiController@transaction_list')->name('transaction.list');
    Route::post('/mengelola-transaksi/verification-delivery', 'TransaksiController@verification_delivery')->name('transaction.verification_delivery');

    Route::get('/kelola-pendapatan', function () {
        return view('seller.pendapatan');
    });

});

Route::post('/mengelola-transaksi/get-detail-transaction', 'TransaksiController@get_detail_transaction')->name('transaction.detail');
Route::post('/mengelola-transaksi/get-proof-transaction', 'TransaksiController@get_proof_transaction')->name('transaction.proof');
