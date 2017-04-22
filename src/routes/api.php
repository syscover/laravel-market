<?php

/*
|----------------------------------
| PRODUCTS
|----------------------------------
*/
Route::get('api/v1/market/products/{lang?}',                                ['as' => 'marketProduct',                         'uses' => 'Syscover\Market\Controllers\ProductController@index']);
Route::get('api/v1/market/products/{id}/{lang}',                            ['as' => 'showMarketProduct',                     'uses' => 'Syscover\Market\Controllers\ProductController@show']);
Route::post('api/v1/market/products/search',                                ['as' => 'searchMarketProduct',                   'uses' => 'Syscover\Market\Controllers\ProductController@search']);
Route::post('api/v1/market/products',                                       ['as' => 'storeMarketProduct',                    'uses' => 'Syscover\Market\Controllers\ProductController@store']);
Route::put('api/v1/market/products/{id}/{lang}',                            ['as' => 'updateMarketProduct',                   'uses' => 'Syscover\Market\Controllers\ProductController@update']);
Route::delete('api/v1/market/products/{id}/{lang?}',                        ['as' => 'destroyMarketProduct',                  'uses' => 'Syscover\Market\Controllers\ProductController@destroy']);

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

/*
|----------------------------------
| ORDER STATUS
|----------------------------------
*/
Route::get('api/v1/market/order-status/{lang?}',                             ['as' => 'marketOrderStatus',                         'uses' => 'Syscover\Market\Controllers\OrderStatusController@index']);
Route::get('api/v1/market/order-status/{id}/{lang}',                         ['as' => 'showMarketOrderStatus',                     'uses' => 'Syscover\Market\Controllers\OrderStatusController@show']);
Route::post('api/v1/market/order-status/search',                             ['as' => 'searchMarketOrderStatus',                   'uses' => 'Syscover\Market\Controllers\OrderStatusController@search']);
Route::post('api/v1/market/order-status',                                    ['as' => 'storeMarketOrderStatus',                    'uses' => 'Syscover\Market\Controllers\OrderStatusController@store']);
Route::put('api/v1/market/order-status/{id}/{lang}',                         ['as' => 'updateMarketOrderStatus',                   'uses' => 'Syscover\Market\Controllers\OrderStatusController@update']);
Route::delete('api/v1/market/order-status/{id}/{lang?}',                     ['as' => 'destroyMarketOrderStatus',                  'uses' => 'Syscover\Market\Controllers\OrderStatusController@destroy']);

/*
|----------------------------------
| PAYMENT METHOD
|----------------------------------
*/
Route::get('api/v1/market/payment-method/{lang?}',                             ['as' => 'marketPaymentMethod',                         'uses' => 'Syscover\Market\Controllers\PaymentMethodController@index']);
Route::get('api/v1/market/payment-method/{id}/{lang}',                         ['as' => 'showMarketPaymentMethod',                     'uses' => 'Syscover\Market\Controllers\PaymentMethodController@show']);
Route::post('api/v1/market/payment-method/search',                             ['as' => 'searchMarketPaymentMethod',                   'uses' => 'Syscover\Market\Controllers\PaymentMethodController@search']);
Route::post('api/v1/market/payment-method',                                    ['as' => 'storeMarketPaymentMethod',                    'uses' => 'Syscover\Market\Controllers\PaymentMethodController@store']);
Route::put('api/v1/market/payment-method/{id}/{lang}',                         ['as' => 'updateMarketPaymentMethod',                   'uses' => 'Syscover\Market\Controllers\PaymentMethodController@update']);
Route::delete('api/v1/market/payment-method/{id}/{lang?}',                     ['as' => 'destroyMarketPaymentMethod',                  'uses' => 'Syscover\Market\Controllers\PaymentMethodController@destroy']);