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
Route::post('notify-settings', 'SettingsController@change')->name('notify-settings');
Auth::routes();
Route::get('/test', function () {
    RobinCSamuel\LaravelMsg91\Facades\LaravelMsg91::message('380939606674', 'testing');
});

Route::group([
    'prefix'     => config('backpack.base.route_prefix', 'admin'),
    'middleware' => ['web'],
], function () {
//    Auth routes
    Route::get('/register', 'Auth\RegisterController@showRegistrationForm')->name('backpack.auth.register');
    Route::post('/register', 'Auth\RegisterController@register');
    Route::get('/login', 'Auth\LoginController@showLoginForm')->name('backpack.auth.login');
    Route::post('/login', 'Auth\LoginController@login');
    Route::match(['get', 'post'], 'logout', 'Auth\LoginController@logout');

//    Password routes
    Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('backpack.auth.password.reset');
    Route::post('password/phone', 'Auth\ForgotPasswordController@sendResetLinkOtp')->name('backpack.auth.password.phone');
    Route::post('password/reset', 'Auth\ResetPasswordController@resetPass');
    Route::get('password/reset/otp', 'Auth\ResetPasswordController@showResetForm')->name('backpack.auth.password.reset.token');

//SMS routes
    Route::post('/verify/otp', 'SmsController@verifyOtp')->name('verify.otp');
    Route::get('/send/verification/code', 'SmsController@send')->name('send.verification.code')->middleware('admin');

//Wallet Routes
    Route::get('wallet', 'WalletController@index')->name('wallet');
    Route::post('pay_invoice/{invoice}', 'WalletController@payInvoice')->name('invoice.pay');
    Route::post('payment_status', 'WalletController@paymentStatus')->name('payment.status');
    Route::get('pay/{invoice}', 'WalletController@pay')->name('pay');
    Route::get('walletDeposit', 'WalletController@walletDeposit')->name('walletDeposit');

}); // this should be the absolute last line of this file



Route::get('/home', 'HomeController@index')->name('home');
