<?php

use Illuminate\Database\Seeder;
use Syscover\Market\Models\Category;

class MarketCategoriesSeeder extends Seeder
{
    public function run()
    {
        Category::insert([
            ['id' => 1, 'lang_id' => 'es', 'name' => 'Categoría 1', 'slug' => 'categoria-1',    'active' => true, 'data_lang' => '["es","en"]'],
            ['id' => 1, 'lang_id' => 'en', 'name' => 'Category 1',  'slug' => 'category-1',     'active' => true, 'data_lang' => '["es","en"]'],
            ['id' => 2, 'lang_id' => 'es', 'name' => 'Categoría 2', 'slug' => 'categoria-2',    'active' => true, 'data_lang' => '["es","en"]'],
            ['id' => 2, 'lang_id' => 'en', 'name' => 'Category 2',  'slug' => 'category-2',     'active' => true, 'data_lang' => '["es","en"]'],
        ]);
    }
}

/*
 * Command to run:
 * php artisan db:seed --class="MarketCategoriesSeeder"
 */