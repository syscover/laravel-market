<?php

use Illuminate\Database\Seeder;
use Syscover\Market\Models\ProductClassTax;

class MarketProductClassTaxSeeder extends Seeder
{
    public function run()
    {
        ProductClassTax::insert([
            ['id' => 1, 'name' => 'Producto IVA General'],
            ['id' => 2, 'name' => 'Producto IVA Reducido'],
            ['id' => 3, 'name' => 'Producto IVA Superreducido'],
            ['id' => 4, 'name' => 'Producto Exento de IVA']
        ]);
    }
}

/*
 * Command to run:
 * php artisan db:seed --class="MarketProductClassTaxSeeder"
 */