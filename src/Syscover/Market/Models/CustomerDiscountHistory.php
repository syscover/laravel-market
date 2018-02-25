<?php namespace Syscover\Market\Models;

use Syscover\Core\Models\CoreModel;
use Illuminate\Support\Facades\Validator;

/**
 * Class CustomerDiscountHistory
 * @package Syscover\Market\Models
 */

class CustomerDiscountHistory extends CoreModel
{
	protected $table        = 'market_customer_discount_history';
    protected $fillable     = ['id', 'customer_id', 'order_id', 'applied', 'rule_type', 'rule_id', 'has_coupon', 'coupon_code', 'names', 'descriptions', 'condition_rules', 'discount_type_id', 'minimum_amount', 'discount_fixed_amount', 'discount_percentage', 'maximum_discount_amount', 'discount_amount', 'apply_shipping_amount', 'free_shipping', 'product_rules', 'data_lang'];
    protected $casts        = [
        'names'             => 'array',
        'descriptions'      => 'array',
        'data_lang'         => 'array',
        'price_rule'        => 'array'
    ];
    private static $rules   = [];

    public static function validate($data)
    {
        return Validator::make($data, static::$rules);
	}

    public function scopeBuilder($query)
    {
        return $query->join('customer', 'customer_discount_history.customer_id', '=', 'customer.id')
            ->join('order', 'customer_discount_history.order_id', '=', 'order.id');
    }
}