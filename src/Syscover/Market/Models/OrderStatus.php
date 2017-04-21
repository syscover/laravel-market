<?php namespace Syscover\Market\Models;

use Syscover\Core\Models\CoreModel;
use Illuminate\Support\Facades\Validator;
use Syscover\Admin\Models\Lang;

/**
 * Class PaymentMethod
 * @package Syscover\Market\Models
 */

class OrderStatus extends CoreModel
{
	protected $table        = 'order_status';
    protected $fillable     = ['id', 'lang_id', 'name', 'active', 'data_lang'];
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
        return $query->join('lang', 'order_status.lang_id', '=', 'lang.id');
    }

    public function lang()
    {
        return $this->belongsTo(Lang::class, 'lang_id');
    }
}