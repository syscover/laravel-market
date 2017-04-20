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

/*
|----------------------------------
| CUSTOMER CLASS TAX
|----------------------------------
*/
Route::get('api/v1/market/customer-class-tax',                               ['as' => 'customerClassTax',               'uses' => 'Syscover\Market\Controllers\CustomerClassTaxController@index']);
Route::get('api/v1/market/customer-class-tax/{id}',                          ['as' => 'showCustomerClassTax',           'uses' => 'Syscover\Market\Controllers\CustomerClassTaxController@show']);
Route::post('api/v1/market/customer-class-tax',                              ['as' => 'storeCustomerClassTax',          'uses' => 'Syscover\Market\Controllers\CustomerClassTaxController@store']);
Route::post('api/v1/market/customer-class-tax/search',                       ['as' => 'searchCustomerClassTax',         'uses' => 'Syscover\Market\Controllers\CustomerClassTaxController@search']);
Route::put('api/v1/market/customer-class-tax/{id}',                          ['as' => 'updateCustomerClassTax',         'uses' => 'Syscover\Market\Controllers\CustomerClassTaxController@update']);
Route::delete('api/v1/market/customer-class-tax/{id}',                       ['as' => 'destroyCustomerClassTax',        'uses' => 'Syscover\Market\Controllers\CustomerClassTaxController@destroy']);

/*
|----------------------------------
| GROUP CUSTOMER CLASS TAX
|----------------------------------
*/
Route::get('api/v1/market/group-customer-class-tax',                            ['as' => 'groupCustomerClassTax',               'uses' => 'Syscover\Market\Controllers\GroupCustomerClassTaxController@index']);
Route::get('api/v1/market/group-customer-class-tax/{id}',                       ['as' => 'showGroupCustomerClassTax',           'uses' => 'Syscover\Market\Controllers\GroupCustomerClassTaxController@show']);
Route::post('api/v1/market/group-customer-class-tax',                           ['as' => 'storeGroupCustomerClassTax',          'uses' => 'Syscover\Market\Controllers\GroupCustomerClassTaxController@store']);
Route::post('api/v1/market/group-customer-class-tax/search',                    ['as' => 'searchGroupCustomerClassTax',         'uses' => 'Syscover\Market\Controllers\GroupCustomerClassTaxController@search']);
Route::put('api/v1/market/group-customer-class-tax/{id}',                       ['as' => 'updateGroupCustomerClassTax',         'uses' => 'Syscover\Market\Controllers\GroupCustomerClassTaxController@update']);
Route::delete('api/v1/market/group-customer-class-tax/{id}',                    ['as' => 'destroyGroupCustomerClassTax',        'uses' => 'Syscover\Market\Controllers\GroupCustomerClassTaxController@destroy']);

/*
|----------------------------------
| PRODUCT CLASS TAX
|----------------------------------
*/
Route::get('api/v1/market/product-class-tax',                               ['as' => 'productClassTax',                 'uses' => 'Syscover\Market\Controllers\ProductClassTaxController@index']);
Route::get('api/v1/market/product-class-tax/{id}',                          ['as' => 'showProductClassTax',             'uses' => 'Syscover\Market\Controllers\ProductClassTaxController@show']);
Route::post('api/v1/market/product-class-tax',                              ['as' => 'storeProductClassTax',            'uses' => 'Syscover\Market\Controllers\ProductClassTaxController@store']);
Route::post('api/v1/market/product-class-tax/search',                       ['as' => 'searchProductClassTax',           'uses' => 'Syscover\Market\Controllers\ProductClassTaxController@search']);
Route::put('api/v1/market/product-class-tax/{id}',                          ['as' => 'updateProductClassTax',           'uses' => 'Syscover\Market\Controllers\ProductClassTaxController@update']);
Route::delete('api/v1/market/product-class-tax/{id}',                       ['as' => 'destroyProductClassTax',          'uses' => 'Syscover\Market\Controllers\ProductClassTaxController@destroy']);