<?php

use Illuminate\Database\Seeder;
use Syscover\Market\Models\OrderStatus;

class MarketOrderStatusSeeder extends Seeder
{
    public function run()
    {
        OrderStatus::insert([
            ['object_id' => 1, 'lang_id' => 'es', 'name' => 'Pendiente de pago',           'active' => 0, 'data_lang' => '["es","en"]'],
            ['object_id' => 1, 'lang_id' => 'en', 'name' => 'Outstanding',                 'active' => 0, 'data_lang' => '["es","en"]'],
            ['object_id' => 2, 'lang_id' => 'es', 'name' => 'Pago confirmado',             'active' => 0, 'data_lang' => '["es","en"]'],
            ['object_id' => 2, 'lang_id' => 'en', 'name' => 'Payment Confirmed',           'active' => 0, 'data_lang' => '["es","en"]'],
            ['object_id' => 3, 'lang_id' => 'es', 'name' => 'A la espera de existencias',  'active' => 0, 'data_lang' => '["es","en"]'],
            ['object_id' => 3, 'lang_id' => 'en', 'name' => 'Pending stocks',              'active' => 0, 'data_lang' => '["es","en"]'],
            ['object_id' => 4, 'lang_id' => 'es', 'name' => 'Preparando',                  'active' => 0, 'data_lang' => '["es","en"]'],
            ['object_id' => 4, 'lang_id' => 'en', 'name' => 'Preparing',                   'active' => 0, 'data_lang' => '["es","en"]'],
            ['object_id' => 5, 'lang_id' => 'es', 'name' => 'Enviado',                     'active' => 0, 'data_lang' => '["es","en"]'],
            ['object_id' => 5, 'lang_id' => 'en', 'name' => 'Dispatched',                  'active' => 0, 'data_lang' => '["es","en"]'],
            ['object_id' => 6, 'lang_id' => 'es', 'name' => 'Completado',                  'active' => 0, 'data_lang' => '["es","en"]'],
            ['object_id' => 6, 'lang_id' => 'en', 'name' => 'Completed',                   'active' => 0, 'data_lang' => '["es","en"]'],
            ['object_id' => 7, 'lang_id' => 'es', 'name' => 'Cancelado',                   'active' => 0, 'data_lang' => '["es","en"]'],
            ['object_id' => 7, 'lang_id' => 'en', 'name' => 'Cancel',                      'active' => 0, 'data_lang' => '["es","en"]'],
        ]);
    }
}

/*
 * Command to run:
 * php artisan db:seed --class="MarketOrderStatusSeeder"
 */