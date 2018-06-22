<?php

//Route::group(['middleware' => ['no.csrf']], function () {
//    // PAYPAL
//    Route::get('api/v1/market/paypal/response',             'Syscover\Market\Controllers\PayPalController@paymentResponse')->name('market.paypal.response');
//});

/* REDSYS */
Route::get('redsys/payment/response/successful',        '\Syscover\Market\Controllers\RedsysController@successful')->name('pulsar.market.redsys_payment_successful');
Route::get('redsys/payment/response/failure',           '\Syscover\Market\Controllers\RedsysController@error')->name('pulsar.market.redsys_payment_error');

/* PAYPAL */
Route::post('paypal/payment/create',                    'Syscover\Market\Controllers\PayPalController@createPayment')->name('pulsar.market.paypal_create_payment');
Route::get('paypal/payment/response/successful',        '\Syscover\Market\Controllers\PayPalController@successful')->name('pulsar.market.redsys_payment_successful');
Route::get('paypal/payment/response/failure',           '\Syscover\Market\Controllers\PayPalController@error')->name('pulsar.market.redsys_payment_error');
