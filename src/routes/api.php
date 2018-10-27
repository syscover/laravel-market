<?php

Route::group(['middleware' => ['api']], function () {
    // REDSYS
    Route::post('api/v1/market/redsys/async/response', 'Syscover\Market\Controllers\RedsysController@asyncResponse')->name('api.market.redsys_async_response');
});