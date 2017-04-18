<?php

/*
|----------------------------------
| CATEGORIES
|----------------------------------
*/
Route::get('api/v1/market/categories/{lang?}',                             ['as' => 'category',                         'uses' => 'Syscover\Market\Controllers\CategoryController@index']);
Route::get('api/v1/market/categories/{id}/{lang}',                         ['as' => 'showCategory',                     'uses' => 'Syscover\Market\Controllers\CategoryController@show']);
Route::post('api/v1/market/categories/search',                             ['as' => 'searchCategory',                   'uses' => 'Syscover\Market\Controllers\CategoryController@search']);
Route::post('api/v1/market/categories',                                    ['as' => 'storeCategory',                    'uses' => 'Syscover\Market\Controllers\CategoryController@store']);
Route::put('api/v1/market/categories/{id}/{lang}',                         ['as' => 'updateCategory',                   'uses' => 'Syscover\Market\Controllers\CategoryController@update']);
Route::delete('api/v1/market/categories/{id}/{lang?}',                     ['as' => 'destroyCategory',                  'uses' => 'Syscover\Market\Controllers\CategoryController@destroy']);

