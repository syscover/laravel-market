<?php namespace Syscover\Market\Traits;

use Syscover\Market\Models\Category;
use Syscover\Market\Models\Product;
use Syscover\Market\Models\Section;
use Syscover\Market\Models\Stock;

trait Marketable
{
    public function products()
    {
        return $this->morphMany(Product::class, 'object', 'object_type', 'object_id', 'id');
    }

    public function categories()
    {
        return $this->belongsToMany(
            Category::class,
            'market_products_categories',
            'product_id',
            'category_id',
            'product_id',
            'id'
        );
    }

    public function sections()
    {
        return $this->belongsToMany(
            Section::class,
            'market_products_sections',
            'product_id',
            'section_id',
            'product_id',
            'id'
        );
    }

    public function stocks()
    {
        return $this->hasMany(
            Stock::class,
            'product_id',
            'product_id'
        );
    }
}
