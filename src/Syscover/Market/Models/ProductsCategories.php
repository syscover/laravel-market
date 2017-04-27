<?php namespace Syscover\Market\Models;

use Syscover\Core\Models\CoreModel;
use Illuminate\Support\Facades\Validator;

/**
 * Class ProductsCategories
 * @package Syscover\Market\Models
 */

class ProductsCategories extends CoreModel
{
	protected $table        = 'products_categories';
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
        return $query->join('product', 'products_categories.product_id', '=', 'product.id')
            ->leftJoin('product_lang', function($join) use ($lang) {
                $join->on('product.id', '=', 'product_lang.id');
                if($lang !== null)  $join->where('product_lang.lang_id', '=', $lang);
            })
            ->join('category', function($join) use ($lang) {
                $join->on('products_categories.category_id', '=', 'category.id');
                if($lang !== null)  $join->where('category.lang_id', '=', $lang);
            });
    }
}