<?php namespace Syscover\Market\Models;

use Syscover\Core\Models\CoreModel;
use Illuminate\Support\Facades\Validator;
use Syscover\Admin\Models\Lang;

/**
 * Class Product
 * @package Syscover\Market\Models
 */

class ProductLang extends CoreModel
{
	protected $table        = 'product_lang';
    public $timestamps      = false;
    protected $fillable     = ['id', 'lang_id', 'name', 'slug', 'description'];
    private static $rules   = [];

    public static function validate($data)
    {
        return Validator::make($data, static::$rules);
	}

    public function scopeBuilder($query)
    {
        return $query;
    }

    public function lang()
    {
        return $this->belongsTo(Lang::class, 'lang_id');
    }
}