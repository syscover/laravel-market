<?php namespace Syscover\Market\Models;

use Syscover\Core\Models\CoreModel;
use Illuminate\Support\Facades\Validator;

/**
 * Class ProductClassTax
 * @package Syscover\Market\Models
 */

class ProductClassTax extends CoreModel
{
	protected $table        = 'market_product_class_tax';
    protected $fillable     = ['id', 'name'];

    private static $rules   = [
        'name' => 'required|between:2,100'
    ];

    public static function validate($data)
    {
        return Validator::make($data, static::$rules);
	}
}