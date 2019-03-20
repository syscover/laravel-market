<?php

Route::group(['middleware' => ['api']], function () {
    // REDSYS
    Route::post('api/v1/market/redsys/async/response', 'Syscover\Market\Controllers\RedsysController@asyncResponse')->name('api.market.redsys_async_response');
});

// Route::group(['prefix' => 'api/v1', 'middleware' => ['api', 'client']], function () {
Route::group(['prefix' => 'api/v1/market', 'middleware' => ['api']], function () {

    // Sections
    Route::get('section',                                     'Syscover\Market\Controllers\SectionController@index')->name('api.market_section');
    Route::get('section/{id}',                                'Syscover\Market\Controllers\SectionController@show')->name('api.market_show_section');
    Route::post('section',                                    'Syscover\Market\Controllers\SectionController@store')->name('api.market_store_section');
    Route::post('section/search',                             'Syscover\Market\Controllers\SectionController@search')->name('api.market_search_section');
    Route::put('section/{id}',                                'Syscover\Market\Controllers\SectionController@update')->name('api.market_update_section');
    Route::delete('section/{id}',                             'Syscover\Market\Controllers\SectionController@destroy')->name('api.market_destroy_section');
});
