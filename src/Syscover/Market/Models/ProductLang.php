<?php namespace Syscover\Market\Models;

use Syscover\Core\Models\CoreModel;
use Illuminate\Support\Facades\Validator;
use Syscover\Admin\Traits\Translatable;

/**
 * Class Product
 * @package Syscover\Market\Models
 */

class ProductLang extends CoreModel
{
    use Translatable;

	protected $table        = 'market_product_lang';
    public $timestamps      = false;
    protected $casts        = [
        'data' => 'array'
    ];
    protected $fillable     = ['id', 'lang_id', 'name', 'slug', 'description', 'data'];

    private static $rules   = [];

    public static function validate($data)
    {
        return Validator::make($data, static::$rules);
	}

    public function scopeBuilder($query)
    {
        return $query;
    }
}