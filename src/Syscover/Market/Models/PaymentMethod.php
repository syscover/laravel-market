<?php namespace Syscover\Market\Models;

use Syscover\Core\Models\CoreModel;
use Illuminate\Support\Facades\Validator;
use Syscover\Admin\Traits\Translatable;

/**
 * Class PaymentMethod
 * @package Syscover\Market\Models
 */

class PaymentMethod extends CoreModel
{
    use Translatable;

	protected $table        = 'market_payment_method';
    protected $primaryKey   = 'ix';
    protected $fillable     = ['ix', 'id', 'lang_id', 'name', 'order_status_successful_id', 'minimum_price', 'maximum_price', 'instructions', 'sort', 'active', 'data_lang'];
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
}