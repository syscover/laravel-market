<?php namespace Syscover\Market\Models;

use Syscover\Core\Models\CoreModel;
use Illuminate\Support\Facades\Validator;
use Syscover\Admin\Models\Lang;

/**
 * Class Category
 * @package Syscover\Market\Models
 */

class Category extends CoreModel
{
	protected $table        = 'category';
    public $timestamps      = false;
    private static $rules   = [];

    public static function validate($data)
    {
        return Validator::make($data, static::$rules);
	}

    public function scopeBuilder($query)
    {
        return $query->join('lang', 'category.lang_id', '=', 'lang.id');
    }

    public function lang()
    {
        return $this->belongsTo(Lang::class, 'lang_id');
    }
}