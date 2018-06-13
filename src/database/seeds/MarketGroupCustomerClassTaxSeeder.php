<?php

use Illuminate\Database\Seeder;
use Syscover\Market\Models\CustomerGroupCustomerClassTax;

class MarketGroupCustomerClassTaxSeeder extends Seeder {

    public function run()
    {
        CustomerGroupCustomerClassTax::insert([
            ['customer_group_id' => 1,     'customer_class_tax_id' => 1],
            ['customer_group_id' => 2,     'customer_class_tax_id' => 2],
            ['customer_group_id' => 3,     'customer_class_tax_id' => 3],
        ]);
    }
}

/*
 * Command to run:
 * php artisan db:seed --class="MarketGroupCustomerClassTaxSeeder"
 */