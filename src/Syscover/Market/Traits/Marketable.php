<?php namespace Syscover\Market\Traits;

use Syscover\Market\Models\Category;
use Syscover\Market\Models\Product;
use Syscover\Market\Models\Section;
use Syscover\Market\Models\Stock;
use Syscover\Market\Services\TaxRuleService;

trait Marketable
{
    public function scopeSectionsProducts($query, $sections)
    {
        return $query
            ->builder()
            ->whereIn('market_product.id', function($query) use ($sections) {
                $query
                    ->select('product_id')
                    ->from('market_products_sections')
                    ->whereIn('section_id', $sections)
                    ->groupBy('product_id')
                    ->get();
            });
    }

    public function scopeCategoriesProducts($query, $categories)
    {
        return $query
            ->builder()
            ->whereIn('market_product.id', function($query) use ($categories) {
                $query
                    ->select('product_id')
                    ->from('market_products_categories')
                    ->whereIn('category_id', $categories)
                    ->groupBy('product_id')
                    ->get();
            });
    }

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

    public function __get($name)
    {
        // price of product
        if($name === 'price')
        {
            if(config('pulsar-market.product_tax_display_prices') == TaxRuleService::PRICE_WITHOUT_TAX)
            {
                return $this->subtotal;
            }
            elseif(config('pulsar-market.product_tax_display_prices') == TaxRuleService::PRICE_WITH_TAX)
            {
                return $this->total; // call magic method
            }
        }

        // total price
        if($name === 'total')
        {
            if($this->tax_amount !== null)
            {
                return $this->subtotal + $this->tax_amount;
            }

            $taxes              = TaxRuleService::taxCalculateOverSubtotal($this->subtotal, $this->tax_rules);
            $this->tax_amount   = $taxes->sum('tax_amount');
            $this->total        = $this->subtotal + $this->tax_amount;

            return $this->total;
        }

        // taxAmount property
        if($name === 'tax_amount')
        {
            $taxes = TaxRuleService::taxCalculateOverSubtotal($this->subtotal, $this->tax_rules);
            return $taxes->sum('tax_amount');
        }

        if($name === 'tax_rules')
        {
            return TaxRuleService::getTaxRules()->where('product_class_tax_id', $this->product_class_tax_id);
        }

        return parent::__get($name);
    }
}
