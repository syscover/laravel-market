<?php

use Illuminate\Database\Seeder;
use Syscover\Market\Models\PaymentMethod;

class MarketPaymentMethodSeeder extends Seeder
{
    public function run()
    {
        PaymentMethod::insert([
            ['object_id' => 1, 'lang_id' => 'es', 'name' => 'Tarjeta de crédito',      'order_status_successful_id' => 2, 'active' => false, 'data_lang' => '["es","en"]'],
            ['object_id' => 1, 'lang_id' => 'en', 'name' => 'Credit card',             'order_status_successful_id' => 2, 'active' => false, 'data_lang' => '["es","en"]'],
            ['object_id' => 2, 'lang_id' => 'es', 'name' => 'PayPal',                  'order_status_successful_id' => 2, 'active' => false, 'data_lang' => '["es","en"]'],
            ['object_id' => 2, 'lang_id' => 'en', 'name' => 'PayPal',                  'order_status_successful_id' => 2, 'active' => false, 'data_lang' => '["es","en"]'],
            ['object_id' => 3, 'lang_id' => 'es', 'name' => 'Transferencia bancaria',  'order_status_successful_id' => 1, 'active' => false, 'data_lang' => '["es","en"]'],
            ['object_id' => 3, 'lang_id' => 'en', 'name' => 'Wire transfer',           'order_status_successful_id' => 1, 'active' => false, 'data_lang' => '["es","en"]'],
        ]);
    }
}

/*
 * Command to run:
 * php artisan db:seed --class="MarketPaymentMethodSeeder"
 */