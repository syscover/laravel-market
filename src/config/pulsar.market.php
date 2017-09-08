<?php

return [

    //******************************************************************************************************************
    //***   orders
    //******************************************************************************************************************
    'orderIdSuffix'                 => env('MARKET_ORDER_ID_SUFFIX', ''),

    //******************************************************************************************************************
    //***   Type of product
    //******************************************************************************************************************
    'productTypes'                  => [
        (object)['id' => 1,      'name' => 'market::pulsar.downloadable'],
        (object)['id' => 2,      'name' => 'market::pulsar.transportable'],
        (object)['id' => 3,      'name' => 'market::pulsar.transportable_downloadable'],
        (object)['id' => 4,      'name' => 'market::pulsar.service'],
    ],

    //******************************************************************************************************************
    //***   Type prices of product
    //******************************************************************************************************************
    'priceTypes'                    => [
        (object)['id' => 1,      'name' => 'market::pulsar.single_price'],
        (object)['id' => 2,      'name' => 'market::pulsar.undefined_price']
    ],

    //******************************************************************************************************************
    //***   Tax values
    //******************************************************************************************************************

    // Tax default values
    'defaultTaxCountry'             => env('MARKET_DEFAULT_COUNTRY_TAX', 'ES'),        // default country tax to calculate prices
    'defaultClassCustomerTax'       => env('MARKET_DEFAULT_CLASS_CUSTOMER_TAX', 1),    // default customer tax class to calculate tax

    // Type prices for products
    'productPricesValues'           => [
        (object)['id' => 1,      'name' => 'market::pulsar.excluding_tax'],
        (object)['id' => 2,      'name' => 'market::pulsar.including_tax']
    ],
    'productTaxPrices'              => env('MARKET_PRODUCT_TAX_PRICES', 1),            // Product prices type
    'productTaxDisplayPrices'       => env('MARKET_PRODUCT_TAX_DISPLAY_PRICES', 1),    // How to display product prices

    // Type prices for shipping
    'shippingPricesValues'          => [
        (object)['id' => 1,      'name' => 'market::pulsar.excluding_tax'],
        (object)['id' => 2,      'name' => 'market::pulsar.including_tax']
    ],
    'taxShippingPrices'             => env('MARKET_TAX_SHIPPING_PRICES', 1),           // Shipping prices type
    'taxShippingDisplayPrices'      => env('MARKET_TAX_SHIPPING_DISPLAY_PRICES', 1),   // How to display shipping prices








    //******************************************************************************************************************
    //***   Discount type on shopping cart
    //******************************************************************************************************************
    'discountTypes'                => [
        (object)['id' => 1,      'name' => 'market::pulsar.without_discount'],
        (object)['id' => 2,      'name' => 'market::pulsar.discount_percentage_subtotal'],
        (object)['id' => 3,      'name' => 'market::pulsar.discount_fixed_amount_subtotal'],
        (object)['id' => 4,      'name' => 'market::pulsar.discount_percentage_total'],
        (object)['id' => 5,      'name' => 'market::pulsar.discount_fixed_amount_total'],
    ],

    //******************************************************************************************************************
    //***   Discounts rules families
    //******************************************************************************************************************
    'ruleFamilies'                => [
        (object)['id' => 1,      'name' => 'market::pulsar.cart_price_rule'],
        (object)['id' => 2,      'name' => 'market::pulsar.catalog_price_rule'],
        (object)['id' => 3,      'name' => 'market::pulsar.customer_price_rule'],
    ],

    //******************************************************************************************************************
    //***   PayPal settings
    //******************************************************************************************************************
    // PAYPAL MODE, sandbox | live
    'payPalMode'                    => env('MARKET_PAYPAL_MODE', ''),
    'payPalSuccessfulRoute'         => env('MARKET_PAYPAL_SUCCESSFUL_ROUTE', 'marketPayPalSuccessful'),
    'payPalErrorRoute'              => env('MARKET_PAYPAL_ERROR_ROUTE', 'marketPayPalError'),

    // SANDBOX
    'payPalSandboxWebProfile'       => env('MARKET_PAYPAL_SANDBOX_WEB_PROFILE', ''),
    'payPalSandboxClientId'         => env('MARKET_PAYPAL_SANDBOX_CLIENT_ID', ''),
    'payPalSandboxSecret'           => env('MARKET_PAYPAL_SANDBOX_SECRET', ''),

    // LIVE
    'payPalLiveWebProfile'          => env('MARKET_PAYPAL_LIVE_WEB_PROFILE', ''),
    'payPalLiveClientId'            => env('MARKET_PAYPAL_LIVE_CLIENT_ID', ''),
    'payPalLiveSecret'              => env('MARKET_PAYPAL_LIVE_SECRET_KEY', ''),

    // LADING PAGE TYPES TO PAYPAL WEB PROFILE
    'payPalLandingPageTypes'        => [
        (object)['id' => 'Billing',     'name' => 'market::pulsar.billing'],
        (object)['id' => 'Login',       'name' => 'market::pulsar.login'],
    ],

    'payPalShippingDataTypes'       => [
        (object)['id' => 0,      'name' => 'market::pulsar.display_shipping_address_fields'],
        (object)['id' => 1,      'name' => 'market::pulsar.not_display_shipping_address_fields'],
        (object)['id' => 2,      'name' => 'market::pulsar.get_shipping_address_from_buyer_profile'],
    ],

    'payPalDisplayShippingDataTypes' => [
        (object)['id' => 0,      'name' => 'market::pulsar.display_shipping_address'],
        (object)['id' => 1,      'name' => 'market::pulsar.not_display_shipping_address'],
    ],

    //******************************************************************************************************************
    //***   RedSys settings
    //******************************************************************************************************************
    // Redsys mode, test | live
    'redsysMode'                    => env('MARKET_REDSYS_MODE', ''),
    'redsysAsyncRoute'              => env('MARKET_REDSYS_ASYNC_ROUTE', 'marketRedsysNotification'),
    'redsysSuccessfulRoute'         => env('MARKET_REDSYS_SUCCESSFUL_ROUTE', 'marketRedsysSuccessful'),
    'redsysErrorRoute'              => env('MARKET_REDSYS_ERROR_ROUTE', 'marketRedsysError'),

    // TEST
    'redsysTestMerchantName'        => env('MARKET_REDSYS_TEST_MERCHANT_NAME', ''),
    'redsysTestDescriptionTrans'    => env('MARKET_REDSYS_TEST_DESCRIPTION_TRANS', 'market:pulsar.order_payment'),
    'redsysTestMerchantCode'        => env('MARKET_REDSYS_TEST_MERCHANT_CODE', ''),
    'redsysTestTerminal'            => env('MARKET_REDSYS_TEST_TERMINAL', '001'),
    'redsysTestCurrency'            => env('MARKET_REDSYS_TEST_CURRENCY', '978'),
    'redsysTestKey'                 => env('MARKET_REDSYS_TEST_KEY', ''),
    'redsysTestMethod'              => env('MARKET_REDSYS_TEST_METHOD', 'T'),
    'redsysTestTransactionType'     => env('MARKET_REDSYS_TEST_TRANSACTION_TYPE', '0'),
    'redsysTestVersion'             => env('MARKET_REDSYS_TEST_VERSION', 'HMAC_SHA256_V1'),

    // LIVE
    'redsysLiveMerchantName'        => env('MARKET_REDSYS_LIVE_MERCHANT_NAME', ''),
    'redsysLiveDescriptionTrans'    => env('MARKET_REDSYS_LIVE_DESCRIPTION_TRANS', 'market:pulsar.order_payment'),
    'redsysLiveMerchantCode'        => env('MARKET_REDSYS_LIVE_MERCHANT_CODE', ''),
    'redsysLiveTerminal'            => env('MARKET_REDSYS_LIVE_TERMINAL', '001'),
    'redsysLiveCurrency'            => env('MARKET_REDSYS_LIVE_CURRENCY', '978'),
    'redsysLiveKey'                 => env('MARKET_REDSYS_LIVE_KEY', ''),
    'redsysLiveMethod'              => env('MARKET_REDSYS_LIVE_METHOD', 'T'),
    'redsysLiveTransactionType'     => env('MARKET_REDSYS_LIVE_TRANSACTION_TYPE', '0'),
    'redsysLiveVersion'             => env('MARKET_REDSYS_LIVE_VERSION', 'HMAC_SHA256_V1'),
];