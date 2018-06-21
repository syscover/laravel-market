<?php

Route::group(['middleware' => ['no.csrf']], function () {
    // PAYPAL
    Route::post('api/v1/market/paypal/create/payment',      'Syscover\Market\Controllers\PayPalController@createPayment')->name('market.paypal.create_payment');
    Route::get('api/v1/market/paypal/response',             'Syscover\Market\Controllers\PayPalController@paymentResponse')->name('market.paypal.response');
});

/* REDSYS */
Route::get('redsys/payment/response/successful',        'Syscover\Market\Controllers\RedsysController@successful')->name('pulsar.market.redsys_payment_successful');
Route::get('redsys/payment/response/failure',           'Syscover\Market\Controllers\RedsysController@error')->name('pulsar.market.redsys_payment_error');

/* PAYPAL */