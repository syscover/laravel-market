<?php

use Illuminate\Database\Seeder;
use Syscover\Admin\Models\Resource;

class MarketResourceSeeder extends Seeder {

    public function run()
    {
        Resource::insert([
            ['id' => 'market',                          'name' => 'Market Package',                     'package_id' => 150],
            ['id' => 'market-cart-price-rule',          'name' => 'Marketing -- Cart price rule',       'package_id' => 150],
            ['id' => 'market-category',                 'name' => 'Categories',                         'package_id' => 150],
            ['id' => 'market-order',                    'name' => 'Orders',                             'package_id' => 150],
            ['id' => 'market-order-status',             'name' => 'Order status',                       'package_id' => 150],
            ['id' => 'market-payment-method',           'name' => 'Payment methods',                    'package_id' => 150],
            ['id' => 'market-product',                  'name' => 'Products',                           'package_id' => 150],
            ['id' => 'market-tax',                      'name' => 'Taxes',                              'package_id' => 150],
            ['id' => 'market-tax-customer',             'name' => 'Taxes -- Customer class tax',        'package_id' => 150],
            ['id' => 'market-tax-customer-group',       'name' => 'Taxes -- Group Customer class tax',  'package_id' => 150],
            ['id' => 'market-tax-product',              'name' => 'Taxes -- Product class tax',         'package_id' => 150],
            ['id' => 'market-tax-rate-zone',            'name' => 'Taxes -- Tax rate zone',             'package_id' => 150],
            ['id' => 'market-tax-rule',                 'name' => 'Taxes -- Tax rule',                  'package_id' => 150],
            ['id' => 'market-tpv',                      'name' => 'TPVs',                               'package_id' => 150],
            ['id' => 'market-tpv-paypal',               'name' => 'TPVs -- PayPal',                     'package_id' => 150],
            ['id' => 'market-tpv-paypal-setting',       'name' => 'TPVs -- PayPal -- Setting',          'package_id' => 150],
            ['id' => 'market-tpv-paypal-web-profile',   'name' => 'TPVs -- PayPal -- Web profile',      'package_id' => 150],

        ]);
    }
}

/*
 * Command to run:
 * php artisan db:seed --class="MarketResourceSeeder"
 */