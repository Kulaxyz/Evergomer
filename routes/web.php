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

Route::get('/', function () {
    return view('welcome');
});
Auth::routes();
Route::get('inv', 'Api\InvoiceController@create');

Route::group([
    'prefix'     => config('backpack.base.route_prefix', 'admin'),
    'middleware' => ['web'],
], function () {
    Route::get('/register', 'Auth\RegisterController@showRegistrationForm')->name('backpack.auth.register');
    Route::post('/register', 'Auth\RegisterController@register');
    Route::get('/login', 'Auth\LoginController@showLoginForm')->name('backpack.auth.login');
    Route::post('/login', 'Auth\LoginController@login');
    Route::match(['get', 'post'], 'logout', 'Auth\LoginController@logout');

    Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('backpack.auth.password.reset');
    Route::post('password/reset', 'Auth\ResetPasswordController@reset');
    Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('backpack.auth.password.reset.token');
    Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('backpack.auth.password.email');
    Route::get('wallet', 'WalletController@index');
    Route::get('pay_invoice/{id}', 'WalletController@payInvoice')->name('invoice.pay');
}); // this should be the absolute last line of this file



Route::get('/home', 'HomeController@index')->name('home');
