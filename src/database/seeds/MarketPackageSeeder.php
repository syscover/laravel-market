<?php

use Illuminate\Database\Seeder;
use Syscover\Admin\Models\Package;

class MarketPackageSeeder extends Seeder
{
    public function run()
    {
        Package::insert([
            ['id' => 150, 'name' => 'Market Package', 'root' => 'market', 'sort' => 150, 'active' => true]
        ]);
    }
}

/*
 * Command to run:
 * php artisan db:seed --class="MarketPackageSeeder"
 */