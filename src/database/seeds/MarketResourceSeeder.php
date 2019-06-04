<?php

use Illuminate\Database\Seeder;
use Syscover\Admin\Models\Resource;

class MarketResourceSeeder extends Seeder
{
    public function run()
    {
        Resource::insert([
            ['id' => 'market',                          'name' => 'Market Package',                                 'package_id' => 150],

            // Sales
            ['id' => 'market-sale',                     'name' => 'Sales',                                          'package_id' => 150],
            ['id' => 'market-order',                    'name' => 'Orders',                                         'package_id' => 150],

            // Catalog
            ['id' => 'market-catalog',                  'name' => 'Catalog',                                        'package_id' => 150],
            ['id' => 'market-product',                  'name' => 'Products',                                       'package_id' => 150],
            ['id' => 'market-category',                 'name' => 'Categories',                                     'package_id' => 150],
            ['id' => 'market-section',                  'name' => 'Sections',                                       'package_id' => 150],
            ['id' => 'market-warehouse',                'name' => 'Warehouse',                                      'package_id' => 150],

            // Marketing
            ['id' => 'market-marketing',                'name' => 'Marketing',                                      'package_id' => 150],
            ['id' => 'market-cart-price-rule',          'name' => 'Marketing -- Cart price rule',                   'package_id' => 150],

            // Payment Gateway
            ['id' => 'market-tpv',                      'name' => 'Payment Gateway',                               'package_id' => 150],
            ['id' => 'market-tpv-paypal-web-profile',   'name' => 'Payment Gateway -- PayPal -- Web profile',      'package_id' => 150],

            // Taxes
            ['id' => 'market-tax',                      'name' => 'Taxes',                                          'package_id' => 150],
            ['id' => 'market-tax-rule',                 'name' => 'Taxes -- Tax rule',                              'package_id' => 150],
            ['id' => 'market-tax-rate-zone',            'name' => 'Taxes -- Tax rate zone',                         'package_id' => 150],
            ['id' => 'market-tax-product',              'name' => 'Taxes -- Product class tax',                     'package_id' => 150],
            ['id' => 'market-tax-customer',             'name' => 'Taxes -- Customer class tax',                    'package_id' => 150],
            ['id' => 'market-tax-customer-group',       'name' => 'Taxes -- Group Customer class tax',              'package_id' => 150],

            // Preference
            ['id' => 'market-preference',               'name' => 'Preferences',                                    'package_id' => 150],
            ['id' => 'market-payment-method',           'name' => 'Payment methods',                                'package_id' => 150],
            ['id' => 'market-order-status',             'name' => 'Order status',                                   'package_id' => 150],
        ]);
    }
}

/*
 * Command to run:
 * php artisan db:seed --class="MarketResourceSeeder"
 */
