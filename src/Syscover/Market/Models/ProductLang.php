<?php namespace Syscover\Market\Models;

use Illuminate\Support\Facades\Validator;
use Syscover\Admin\Traits\CustomizableValues;
use Syscover\Core\Models\CoreModel;
use Syscover\Admin\Traits\Translatable;

/**
 * Class ProductLang
 * @package Syscover\Market\Models
 */

class ProductLang extends CoreModel
{
    use CustomizableValues, Translatable;

	protected $table        = 'market_product_lang';
    protected $primaryKey   = 'ix';
    protected $fillable     = ['id', 'lang_id', 'name', 'slug', 'description', 'data'];
    public $incrementing    = false;
    protected $casts        = [
        'data' => 'array'
    ];

    private static $rules   = [];

    public static function validate($data)
    {
        return Validator::make($data, static::$rules);
	}
}