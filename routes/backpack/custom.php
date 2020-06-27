<?php

// --------------------------
// Custom Backpack Routes
// --------------------------
// This route file is loaded automatically by Backpack\Base.
// Routes you generate using Backpack\Generators will be placed here.

Route::group([
    'prefix'     => config('backpack.base.route_prefix', 'admin'),
    'middleware' => ['web','admin'],
    'namespace'  => 'App\Http\Controllers\Admin',
], function () { // custom admin routes
    Route::get('edit-account-info', 'MyAccountController@getAccountInfoForm')->name('backpack.account.info');
    Route::get('sms-settings', 'SmsSettingsCrudController@index')->name('backpack.sms.settings');
    Route::post('msg-settings', 'SmsSettingsCrudController@msg91')->name('msg.settings');
    Route::post('edit-account-info', 'MyAccountController@postAccountInfoForm');
    Route::post('change-password', 'MyAccountController@postChangePasswordForm')->name('backpack.account.password');

    Route::get('payment_pdf_settings', '\App\Http\Controllers\SettingsController@payment_pdf_settings')->name('payment.pdf.settings');
    Route::post('payment_pdf_settings', '\App\Http\Controllers\SettingsController@update_pdf_settings')->name('payment.pdf.settings');

    Route::crud('device', 'DeviceCrudController');
    Route::crud('invoice', 'InvoiceCrudController');
    Route::crud('payment', 'PaymentCrudController');
    Route::crud('staff', 'StaffCrudController');
    Route::crud('connection_method', 'ConnectionMethodCrudController');
    Route::crud('installed_place', 'InstalledPlaceCrudController');
    Route::crud('purchase_type', 'PurchaseTypeCrudController');
    Route::crud('billing_category', 'BillingCategoryCrudController');

}); // this should be the absolute last line of this file
