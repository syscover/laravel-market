<?php

Route::group(['middleware' => ['no.csrf']], function () {
    // REDSYS
    Route::post('api/v1/market/redsys/notification',                                    'Syscover\Market\Controllers\RedsysController@index')->name('marketRedsysNotification');

    // PAYPAL
    Route::post('api/v1/market/paypal/create/payment',                                  'Syscover\Market\Controllers\PayPalController@createPayment')->name('marketPayPalCreatePayment');
    Route::get('api/v1/market/paypal/response',                                         'Syscover\Market\Controllers\PayPalController@paymentResponse')->name('marketPayPalResponse');
});