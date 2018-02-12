<?php namespace Syscover\Market\Models;

use Syscover\Core\Models\CoreModel;
use Illuminate\Support\Facades\Validator;

/**
 * Class CartPriceRule
 * @package Syscover\Market\Models
 */

class CartPriceRule extends CoreModel
{
	protected $table        = 'market_cart_price_rule';
    protected $fillable     = ['id', 'names', 'descriptions', 'active', 'groups_id', 'combinable', 'priority', 'has_coupon', 'coupon_code', 'customer_uses', 'coupon_uses', 'total_uses', 'enable_from', 'enable_to', 'discount_type_id', 'discount_fixed_amount', 'discount_percentage', 'maximum_discount_amount', 'apply_shipping_amount', 'free_shipping', 'rules', 'data_lang'];
    public $timestamps      = false;

    private static $rules   = [
        'name'              => 'required',
        'discount_type_id'  => 'required'
    ];

    public static function validate($data)
    {
        return Validator::make($data, static::$rules);
	}

    public function scopeBuilder($query, $lang = null)
    {
        return $query;
    }
}