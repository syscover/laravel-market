<?php namespace Syscover\Market\Models;

use Syscover\Core\Models\CoreModel;
use Illuminate\Support\Facades\Validator;
use Syscover\Admin\Traits\Translatable;

/**
 * Class Category
 * @package Syscover\Market\Models
 */

class Category extends CoreModel
{
    use Translatable;

	protected $table        = 'market_category';
    protected $primaryKey   = 'ix';
    protected $fillable     = ['ix', 'id' ,'lang_id', 'parent_id', 'name', 'slug', 'active', 'description', 'data_lang', 'data'];
    protected $casts        = [
        'active'    => 'boolean',
        'data_lang' => 'array',
        'data'      => 'array'
    ];
    public $with            = ['lang'];

    private static $rules   = [
        'name' => 'required|between:2,100'
    ];

    public static function validate($data)
    {
        return Validator::make($data, static::$rules);
	}

    public function children()
    {
        return $this->hasMany(
            Category::class,
            'parent_id',
            'id'
            )
            ->builder();
    }

    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id')
            ->builder();
    }
}