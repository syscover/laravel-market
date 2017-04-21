<?php

/*
|----------------------------------
| CATEGORIES
|----------------------------------
*/
Route::get('api/v1/market/categories/{lang?}',                             ['as' => 'marketCategory',                         'uses' => 'Syscover\Market\Controllers\CategoryController@index']);
Route::get('api/v1/market/categories/{id}/{lang}',                         ['as' => 'showMarketCategory',                     'uses' => 'Syscover\Market\Controllers\CategoryController@show']);
Route::post('api/v1/market/categories/search',                             ['as' => 'searchMarketCategory',                   'uses' => 'Syscover\Market\Controllers\CategoryController@search']);
Route::post('api/v1/market/categories',                                    ['as' => 'storeMarketCategory',                    'uses' => 'Syscover\Market\Controllers\CategoryController@store']);
Route::put('api/v1/market/categories/{id}/{lang}',                         ['as' => 'updateMarketCategory',                   'uses' => 'Syscover\Market\Controllers\CategoryController@update']);
Route::delete('api/v1/market/categories/{id}/{lang?}',                     ['as' => 'destroyMarketCategory',                  'uses' => 'Syscover\Market\Controllers\CategoryController@destroy']);

/*
|----------------------------------
| CUSTOMER CLASS TAX
|----------------------------------
*/
Route::get('api/v1/market/customer-class-tax',                               ['as' => 'marketCustomerClassTax',               'uses' => 'Syscover\Market\Controllers\CustomerClassTaxController@index']);
Route::get('api/v1/market/customer-class-tax/{id}',                          ['as' => 'showMarketCustomerClassTax',           'uses' => 'Syscover\Market\Controllers\CustomerClassTaxController@show']);
Route::post('api/v1/market/customer-class-tax',                              ['as' => 'storeMarketCustomerClassTax',          'uses' => 'Syscover\Market\Controllers\CustomerClassTaxController@store']);
Route::post('api/v1/market/customer-class-tax/search',                       ['as' => 'searchMarketCustomerClassTax',         'uses' => 'Syscover\Market\Controllers\CustomerClassTaxController@search']);
Route::put('api/v1/market/customer-class-tax/{id}',                          ['as' => 'updateMarketCustomerClassTax',         'uses' => 'Syscover\Market\Controllers\CustomerClassTaxController@update']);
Route::delete('api/v1/market/customer-class-tax/{id}',                       ['as' => 'destroyMarketCustomerClassTax',        'uses' => 'Syscover\Market\Controllers\CustomerClassTaxController@destroy']);

/*
|----------------------------------
| GROUP CUSTOMER CLASS TAX
|----------------------------------
*/
Route::get('api/v1/market/group-customer-class-tax',                            ['as' => 'marketGroupCustomerClassTax',               'uses' => 'Syscover\Market\Controllers\GroupCustomerClassTaxController@index']);
Route::get('api/v1/market/group-customer-class-tax/{id}',                       ['as' => 'showMarketGroupCustomerClassTax',           'uses' => 'Syscover\Market\Controllers\GroupCustomerClassTaxController@show']);
Route::post('api/v1/market/group-customer-class-tax',                           ['as' => 'storeMarketGroupCustomerClassTax',          'uses' => 'Syscover\Market\Controllers\GroupCustomerClassTaxController@store']);
Route::post('api/v1/market/group-customer-class-tax/search',                    ['as' => 'searchMarketGroupCustomerClassTax',         'uses' => 'Syscover\Market\Controllers\GroupCustomerClassTaxController@search']);
Route::put('api/v1/market/group-customer-class-tax/{id}',                       ['as' => 'updateMarketGroupCustomerClassTax',         'uses' => 'Syscover\Market\Controllers\GroupCustomerClassTaxController@update']);
Route::delete('api/v1/market/group-customer-class-tax/{id}',                    ['as' => 'destroyMarketGroupCustomerClassTax',        'uses' => 'Syscover\Market\Controllers\GroupCustomerClassTaxController@destroy']);

/*
|----------------------------------
| PRODUCT CLASS TAX
|----------------------------------
*/
Route::get('api/v1/market/product-class-tax',                               ['as' => 'marketProductClassTax',                 'uses' => 'Syscover\Market\Controllers\ProductClassTaxController@index']);
Route::get('api/v1/market/product-class-tax/{id}',                          ['as' => 'showMarketProductClassTax',             'uses' => 'Syscover\Market\Controllers\ProductClassTaxController@show']);
Route::post('api/v1/market/product-class-tax',                              ['as' => 'storeMarketProductClassTax',            'uses' => 'Syscover\Market\Controllers\ProductClassTaxController@store']);
Route::post('api/v1/market/product-class-tax/search',                       ['as' => 'searchMarketProductClassTax',           'uses' => 'Syscover\Market\Controllers\ProductClassTaxController@search']);
Route::put('api/v1/market/product-class-tax/{id}',                          ['as' => 'updateMarketProductClassTax',           'uses' => 'Syscover\Market\Controllers\ProductClassTaxController@update']);
Route::delete('api/v1/market/product-class-tax/{id}',                       ['as' => 'destroyMarketProductClassTax',          'uses' => 'Syscover\Market\Controllers\ProductClassTaxController@destroy']);

/*
|----------------------------------
| TAX RATE ZONE
|----------------------------------
*/
Route::get('api/v1/market/tax-rate-zone',                                   ['as' => 'marketTaxRateZone',                 'uses' => 'Syscover\Market\Controllers\TaxRateZoneController@index']);
Route::get('api/v1/market/tax-rate-zone/{id}',                              ['as' => 'showMarketTaxRateZone',             'uses' => 'Syscover\Market\Controllers\TaxRateZoneController@show']);
Route::post('api/v1/market/tax-rate-zone',                                  ['as' => 'storeMarketTaxRateZone',            'uses' => 'Syscover\Market\Controllers\TaxRateZoneController@store']);
Route::post('api/v1/market/tax-rate-zone/search',                           ['as' => 'searchMarketTaxRateZone',           'uses' => 'Syscover\Market\Controllers\TaxRateZoneController@search']);
Route::put('api/v1/market/tax-rate-zone/{id}',                              ['as' => 'updateMarketTaxRateZone',           'uses' => 'Syscover\Market\Controllers\TaxRateZoneController@update']);
Route::delete('api/v1/market/tax-rate-zone/{id}',                           ['as' => 'destroyMarketTaxRateZone',          'uses' => 'Syscover\Market\Controllers\TaxRateZoneController@destroy']);