<?php namespace Syscover\Market\Models;

use Illuminate\Support\Facades\Validator;
use Syscover\Core\Models\CoreModel;

/**
 * Class Stock
 * @package Syscover\Market\Models
 */

class Stock extends CoreModel
{
	protected $table        = 'market_stock';
	protected $fillable     = ['id', 'warehouse_id', 'product_id', 'stock', 'minimum_stock'];
    protected $casts        = [
        'stock' => 'float',
        'minimum_stock' => 'float'
    ];
    public $with = [];
    private static $rules = [];

    public static function validate($data)
    {
        return Validator::make($data, static::$rules);
	}

    public function scopeBuilder($query)
    {
        return $query;
    }
}