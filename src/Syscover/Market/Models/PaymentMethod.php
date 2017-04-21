<?php namespace Syscover\Market\Models;

use Syscover\Core\Models\CoreModel;
use Illuminate\Support\Facades\Validator;
use Syscover\Admin\Models\Lang;

/**
 * Class PaymentMethod
 * @package Syscover\Market\Models
 */

class PaymentMethod extends CoreModel
{
	protected $table        = 'payment_method';
    protected $fillable     = ['id', 'lang_id', 'name', 'order_status_successful_id', 'minimum_price', 'maximum_price', 'instructions', 'sort', 'active', 'data_lang'];
    public $timestamps      = false;
    protected $maps         = [];
    private static $rules   = [
        'name' => 'required|between:2,255'
    ];

    public static function validate($data)
    {
        return Validator::make($data, static::$rules);
	}

    public function scopeBuilder($query)
    {
        return $query->join('lang', 'payment_method.lang_id', '=', 'lang.id');
    }

    public function lang()
    {
        return $this->belongsTo(Lang::class, 'lang_id');
    }
}