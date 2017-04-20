<?php

use Illuminate\Database\Seeder;
use Syscover\Admin\Models\Package;

class MarketPackageSeeder extends Seeder
{
    public function run()
    {
        Package::insert([
            ['id' => '12', 'name' => 'Market Package', 'folder' => 'market', 'sorting' => 12, 'active' => false]
        ]);
    }
}

/*
 * Command to run:
 * php artisan db:seed --class="MarketPackageSeeder"
 */