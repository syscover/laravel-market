<?php

Route::group(['middleware' => ['no.csrf']], function () {


    // PAYPAL
    Route::post('api/v1/market/paypal/create/payment',      'Syscover\Market\Controllers\PayPalController@createPayment')->name('market.paypal.create_payment');
    Route::get('api/v1/market/paypal/response',             'Syscover\Market\Controllers\PayPalController@paymentResponse')->name('market.paypal.response');
});