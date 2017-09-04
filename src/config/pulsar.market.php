<?php

return [

    //******************************************************************************************************************
    //***   orders
    //******************************************************************************************************************
    'orderIdPrefix'                 => env('ORDER_ID_PREFIX', ''),

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
    'payPalMode'                    => env('PAYPAL_MODE', ''),

    // SANDBOX
    'payPalSandboxWebProfile'       => env('PAYPAL_SANDBOX_WEB_PROFILE', ''),
    'payPalSandboxClientId'         => env('PAYPAL_SANDBOX_CLIENT_ID', ''),
    'payPalSandboxSecret'           => env('PAYPAL_SANDBOX_SECRET', ''),

    // LIVE
    'payPalLiveWebProfile'          => env('PAYPAL_LIVE_WEB_PROFILE', ''),
    'payPalLiveClientId'            => env('PAYPAL_LIVE_CLIENT_ID', ''),
    'payPalLiveSecret'              => env('PAYPAL_LIVE_SECRET_KEY', ''),

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
    'redsysMode'                    => env('REDSYS_MODE', ''),

    // TEST
    'redsysTestMerchantName'        => env('REDSYS_TEST_MERCHANT_NAME', ''),
    'redsysTestMerchantCode'        => env('REDSYS_TEST_MERCHANT_CODE', ''),
    'redsysTestKey'                 => env('REDSYS_TEST_KEY', ''),

    // LIVE
    'redsysLiveMerchantName'        => env('REDSYS_LIVE_MERCHANT_NAME', ''),
    'redsysLiveMerchantCode'        => env('REDSYS_LIVE_MERCHANT_CODE', ''),
    'redsysLiveKey'                 => env('REDSYS_LIVE_KEY', ''),
];