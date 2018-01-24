<?php namespace Syscover\Market\Models;

use Illuminate\Support\Facades\Validator;
use Syscover\Core\Models\CoreModel;

/**
 * Class Warehouse
 * @package Syscover\Market\Models
 */

class Warehouse extends CoreModel
{
	protected $table        = 'market_warehouse';
	protected $fillable     = ['id', 'name', 'country_id', 'territorial_area_1_id', 'territorial_area_2_id', 'territorial_area_3_id', 'zip', 'locality', 'address', 'latitude', 'longitude', 'active'];
    protected $casts        = [
        'active' => 'boolean'
    ];
    public $with = [];
    private static $rules       = [
        'name' => 'required'
    ];

    public static function validate($data)
    {
        return Validator::make($data, static::$rules);
	}

    public function scopeBuilder($query)
    {
        return $query;
    }
}