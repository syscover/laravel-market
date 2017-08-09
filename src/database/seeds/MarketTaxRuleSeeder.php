<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Syscover\Market\Models\TaxRule;

class MarketTaxRuleSeeder extends Seeder {

    public function run()
    {
        TaxRule::insert([
            ['id' => 1, 'name' =>  'IVA 21 - Particular customer', 'translation' => 'web.iva', 'priority' => 0, 'sort' => 0]
        ]);

        DB::table('market_tax_rules_customer_class_taxes')->insert([
            'tax_rule_id' => 1,
            'customer_class_tax_id' => 1
        ]);

        DB::table('market_tax_rules_product_class_taxes')->insert([
            'tax_rule_id' => 1,
            'product_class_tax_id' => 1
        ]);

        DB::table('market_tax_rules_tax_rates_zones')->insert([
            'tax_rule_id' => 1,
            'tax_rate_zone_id' => 1
        ]);

    }
}

/*
 * Command to run:
 * php artisan db:seed --class="MarketTaxRuleSeeder"
 */