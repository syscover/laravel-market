<?php namespace Syscover\Market\Models;

use Syscover\Core\Models\CoreModel;
use Illuminate\Support\Facades\Validator;

/**
 * Class ProductsCategories
 * @package Syscover\Market\Models
 */

class ProductsCategories extends CoreModel
{
	protected $table        = 'market_products_categories';
    protected $primaryKey   = 'product_id';
    protected $fillable     = ['product_id', 'category_id'];
    public $timestamps      = false;

    private static $rules   = [];

    public static function validate($data)
    {
        return Validator::make($data, static::$rules);
	}

    public function scopeBuilder($query, $lang = null)
    {
        return $query->join('market_product', 'market_products_categories.product_id', '=', 'market_product.id')
            ->leftJoin('market_product_lang', function($join) use ($lang) {
                $join->on('market_product.id', '=', 'market_product_lang.id');
                if($lang !== null)  $join->where('market_product_lang.lang_id', '=', $lang);
            })
            ->join('market_category', function($join) use ($lang) {
                $join->on('market_products_categories.category_id', '=', 'market_category.id');
                if($lang !== null)  $join->where('market_category.lang_id', '=', $lang);
            });
    }
}