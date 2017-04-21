<?php

use Illuminate\Database\Seeder;
use Syscover\Market\Models\TaxRateZone;

class MarketTaxRateZoneSeeder extends Seeder {

    public function run()
    {
        TaxRateZone::insert([
            ['id' => 1,     'name' => 'Spain - 21%',    'country_id' => 'ES',   'territorial_area_1_id' => null,    'territorial_area_2_id' => null,    'territorial_area_3_id' => null,    'cp' => null,    'tax_rate' => 21.00],
            ['id' => 2,     'name' => 'Spain - 10%',    'country_id' => 'ES',   'territorial_area_1_id' => null,    'territorial_area_2_id' => null,    'territorial_area_3_id' => null,    'cp' => null,    'tax_rate' => 10.00],
            ['id' => 3,     'name' => 'Spain - 4%',     'country_id' => 'ES',   'territorial_area_1_id' => null,    'territorial_area_2_id' => null,    'territorial_area_3_id' => null,    'cp' => null,    'tax_rate' => 4.00],
            ['id' => 4,     'name' => 'Spain - 0%',     'country_id' => 'ES',   'territorial_area_1_id' => null,    'territorial_area_2_id' => null,    'territorial_area_3_id' => null,    'cp' => null,    'tax_rate' => 0.00],
        ]);
    }
}

/*
 * Command to run:
 * php artisan db:seed --class="MarketTaxRateZoneSeeder"
 */