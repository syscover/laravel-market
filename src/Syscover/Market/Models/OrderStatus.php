<?php namespace Syscover\Market\Models;

use Syscover\Core\Models\CoreModel;
use Illuminate\Support\Facades\Validator;
use Syscover\Admin\Traits\Translatable;

/**
 * Class PaymentMethod
 * @package Syscover\Market\Models
 */

class OrderStatus extends CoreModel
{
    use Translatable;

	protected $table        = 'market_order_status';
    protected $fillable     = ['id', 'lang_id', 'name', 'active', 'data_lang'];
    protected $casts        = [
        'active'    => 'boolean',
        'data_lang' => 'array'
    ];
    public $with            = ['lang'];

    private static $rules   = [
        'name' => 'required|between:2,255'
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