<?php

use Illuminate\Database\Seeder;
use Syscover\Market\Models\CustomerClassTax;

class MarketCustomerClassTaxSeeder extends Seeder
{
    public function run()
    {
        CustomerClassTax::insert([
            ['id' => 1, 'name' => 'Particular customer'],
            ['id' => 2, 'name' => 'European society'],
            ['id' => 3, 'name' => 'No European society']
        ]);
    }
}

/*
 * Command to run:
 * php artisan db:seed --class="MarketCustomerClassTaxSeeder"
 */