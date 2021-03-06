<?php

return [

    //******************************************************************************************************************
    //***   orders
    //******************************************************************************************************************
    'order_id_suffix' => env('MARKET_ORDER_ID_SUFFIX', ''),

    //******************************************************************************************************************
    //***   Class of product
    //******************************************************************************************************************
    'product_classes' => [
        (object)['id' => 1, 'name' => 'market::pulsar.downloadable'],
        (object)['id' => 2, 'name' => 'market::pulsar.transportable'],
        (object)['id' => 3, 'name' => 'market::pulsar.transportable_downloadable'],
        (object)['id' => 4, 'name' => 'market::pulsar.service'],
        (object)['id' => 5, 'name' => 'market::pulsar.event']
    ],

    //******************************************************************************************************************
    //***   Type prices of product
    //******************************************************************************************************************
    'price_types' => [
        (object)['id' => 1, 'name' => 'market::pulsar.single_price'],
        (object)['id' => 2, 'name' => 'market::pulsar.undefined_price']
    ],

    //******************************************************************************************************************
    //***   Discount types
    //******************************************************************************************************************
    'discount_types' => [
        (object)['id' => 1, 'name' => 'market::pulsar.without_discount'],
        (object)['id' => 2, 'name' => 'market::pulsar.discount_percentage_subtotal'],
        (object)['id' => 3, 'name' => 'market::pulsar.discount_fixed_amount_subtotal'],
        (object)['id' => 4, 'name' => 'market::pulsar.discount_percentage_total'],
        (object)['id' => 5, 'name' => 'market::pulsar.discount_fixed_amount_total'],
    ],

    //******************************************************************************************************************
    //***   Tax values
    //******************************************************************************************************************

    // Tax default values
    'default_country_tax'           => env('MARKET_DEFAULT_COUNTRY_TAX', 'ES'),        // default country tax to calculate prices
    'default_customer_class_tax'    => env('MARKET_DEFAULT_CUSTOMER_CLASS_TAX', 1),    // default customer tax class to calculate tax

    // Type prices for products
    'product_prices_values' => [
        (object)['id' => 1, 'name' => 'market::pulsar.excluding_tax'],
        (object)['id' => 2, 'name' => 'market::pulsar.including_tax']
    ],
    'product_tax_prices'            => env('MARKET_PRODUCT_TAX_PRICES', 1),            // Product prices type
    'product_tax_display_prices'    => env('MARKET_PRODUCT_TAX_DISPLAY_PRICES', 1),    // How to display product prices

    // Type prices for shipping
    'shipping_prices_values' => [
        (object)['id' => 1, 'name' => 'market::pulsar.excluding_tax'],
        (object)['id' => 2, 'name' => 'market::pulsar.including_tax']
    ],
    'tax_shipping_prices'           => env('MARKET_TAX_SHIPPING_PRICES', 1),           // Shipping prices type
    'tax_shipping_display_prices'   => env('MARKET_TAX_SHIPPING_DISPLAY_PRICES', 1),   // How to display shipping prices

    //******************************************************************************************************************
    //***   RedSys settings
    //******************************************************************************************************************
    // Redsys mode, test | live
    'redsys_mode'                   => env('MARKET_REDSYS_MODE', 'test'),
    'redsys_async_response_route'   => env('MARKET_REDSYS_ASYNC_RESPONSE_ROUTE', 'api.market.redsys_async_response'),
    'redsys_successful_route'       => env('MARKET_REDSYS_SUCCESSFUL_ROUTE', 'pulsar.market.redsys_payment_successful'),
    'redsys_error_route'            => env('MARKET_REDSYS_ERROR_ROUTE', 'pulsar.market.redsys_payment_error'),

    // TEST
    'redsys_test_merchant_name'     => env('MARKET_REDSYS_TEST_MERCHANT_NAME', ''),
    'redsys_test_description_trans' => env('MARKET_REDSYS_TEST_DESCRIPTION_TRANS', 'market::pulsar.order_payment'),
    'redsys_test_merchant_code'     => env('MARKET_REDSYS_TEST_MERCHANT_CODE', ''),
    'redsys_test_terminal'          => env('MARKET_REDSYS_TEST_TERMINAL', '001'),
    'redsys_test_currency'          => env('MARKET_REDSYS_TEST_CURRENCY', '978'),
    'redsys_test_key'               => env('MARKET_REDSYS_TEST_KEY', ''),
    'redsys_test_method'            => env('MARKET_REDSYS_TEST_METHOD', 'T'),
    'redsys_test_transaction_type'  => env('MARKET_REDSYS_TEST_TRANSACTION_TYPE', '0'),
    'redsys_test_version'           => env('MARKET_REDSYS_TEST_VERSION', 'HMAC_SHA256_V1'),

    // LIVE
    'redsys_live_merchant_name'     => env('MARKET_REDSYS_LIVE_MERCHANT_NAME', ''),
    'redsys_live_description_trans' => env('MARKET_REDSYS_LIVE_DESCRIPTION_TRANS', 'market::pulsar.order_payment'),
    'redsys_live_merchant_code'     => env('MARKET_REDSYS_LIVE_MERCHANT_CODE', ''),
    'redsys_live_terminal'          => env('MARKET_REDSYS_LIVE_TERMINAL', '001'),
    'redsys_live_currency'          => env('MARKET_REDSYS_LIVE_CURRENCY', '978'),
    'redsys_live_key'               => env('MARKET_REDSYS_LIVE_KEY', ''),
    'redsys_live_method'            => env('MARKET_REDSYS_LIVE_METHOD', 'T'),
    'redsys_live_transaction_type'  => env('MARKET_REDSYS_LIVE_TRANSACTION_TYPE', '0'),
    'redsys_live_version'           => env('MARKET_REDSYS_LIVE_VERSION', 'HMAC_SHA256_V1'),

    //******************************************************************************************************************
    //***   PayPal settings
    //******************************************************************************************************************
    // PAYPAL MODE, sandbox | live
    'paypal_mode'                   => env('MARKET_PAYPAL_MODE', 'sandbox'),
    'paypal_successful_route'       => env('MARKET_PAYPAL_SUCCESSFUL_ROUTE', 'pulsar.market.paypal_payment_successful'),
    'paypal_error_route'            => env('MARKET_PAYPAL_ERROR_ROUTE', 'pulsar.market.paypal_payment_error'),

    // SANDBOX
    'paypal_sandbox_web_profile'    => env('MARKET_PAYPAL_SANDBOX_WEB_PROFILE', ''),
    'paypal_sandbox_client_id'      => env('MARKET_PAYPAL_SANDBOX_CLIENT_ID', ''),
    'paypal_sandbox_secret'         => env('MARKET_PAYPAL_SANDBOX_SECRET', ''),

    // LIVE
    'paypal_live_web_profile'       => env('MARKET_PAYPAL_LIVE_WEB_PROFILE', ''),
    'paypal_live_client_id'         => env('MARKET_PAYPAL_LIVE_CLIENT_ID', ''),
    'paypal_live_secret'            => env('MARKET_PAYPAL_LIVE_SECRET_KEY', ''),

















    /**************************************************
     * Países y CP gratuitos
     **************************************************/
    'free_shipping' => [
        'ES' => [
            'country' => false,
            'territorial_area_1' => [],
            'territorial_area_2' => [],
            'territorial_area_3' => [],
            'zip' => [
                '01***',
                '02***',
                '03***',
                '04***',
                '05***',
                '06***',
                // '07***', // Islas Baleares
                '08***',
                '09***',
                '10***',
                '11***',
                '12***',
                '13***',
                '14***',
                '15***',
                '16***',
                '17***',
                '18***',
                '19***',
                '20***',
                '21***',
                '22***',
                '23***',
                '24***',
                '25***',
                '26***',
                '27***',
                '28***',
                '29***',
                '30***',
                '31***',
                '32***',
                '33***',
                '34***',
                // '35***', // Las Palmas
                '36***',
                '37***',
                // '38***', // Santa Cruz de Tenerife
                '39***',
                '40***',
                '41***',
                '42***',
                '43***',
                '44***',
                '45***',
                '46***',
                '47***',
                '48***',
                '49***',
                '50***',
                // '51***', // Ceuta
                // '52***' // Melilla
            ]
        ]
    ],



    //******************************************************************************************************************
    //***   Stripe settings
    //******************************************************************************************************************
    // STRIPE MODE, test | live
    'stripeMode'                    => env('MARKET_STRIPE_MODE', 'test'),

    // TEST
    'stripeTestPublishKey'          => env('MARKET_STRIPE_TEST_PUBLISH_KEY', ''),
    'stripeTestSecretKey'           => env('MARKET_STRIPE_TEST_SECRET_KEY', ''),

    // LIVE
    'stripeLivePublishKey'          => env('MARKET_STRIPE_LIVE_PUBLISH_KEY', ''),
    'stripeLiveSecretKey'           => env('MARKET_STRIPE_LIVE_SECRET_KEY', ''),

    //******************************************************************************************************************
    //***   PayPal settings
    //******************************************************************************************************************
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
    ]
];