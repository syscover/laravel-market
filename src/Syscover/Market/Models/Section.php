<?php namespace Syscover\Market\Models;

use Illuminate\Support\Facades\Validator;
use Syscover\Core\Models\CoreModel;
use Syscover\Admin\Traits\Translatable;

/**
 * Class Section
 * @package Syscover\Market\Models
 */

class Section extends CoreModel
{
    use Translatable;

	protected $table        = 'market_section';
    protected $primaryKey   = 'ix';
    protected $fillable     = ['ix', 'id', 'lang_id', 'name', 'slug', 'data_lang'];
    protected $casts        = [
        'data_lang' => 'array'
    ];
    public $with            = ['lang'];

    private static $rules   = [];

    public static function validate($data)
    {
        return Validator::make($data, static::$rules);
	}
}