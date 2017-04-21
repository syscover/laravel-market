<?php

use Illuminate\Database\Seeder;
use Syscover\Market\Models\GroupCustomerClassTax;

class MarketGroupCustomerClassTaxSeeder extends Seeder {

    public function run()
    {
        GroupCustomerClassTax::insert([
            ['group_id' => 1,     'customer_class_tax_id' => 1],
            ['group_id' => 2,     'customer_class_tax_id' => 2],
            ['group_id' => 3,     'customer_class_tax_id' => 3],
        ]);
    }
}

/*
 * Command to run:
 * php artisan db:seed --class="MarketGroupCustomerClassTaxSeeder"
 */