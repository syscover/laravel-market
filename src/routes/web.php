<?php

Route::group(['middleware' => ['no.csrf']], function () {
    // REDSYS
    Route::post('api/v1/market/redsys/notification',        'Syscover\Market\Controllers\RedsysController@index')->name('market.redsys.notification');

    // PAYPAL
    Route::post('api/v1/market/paypal/create/payment',      'Syscover\Market\Controllers\PayPalController@createPayment')->name('market.paypal.create.payment');
    Route::get('api/v1/market/paypal/response',             'Syscover\Market\Controllers\PayPalController@paymentResponse')->name('market.paypal.response');
});