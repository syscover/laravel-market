<?php namespace Syscover\Market\Models;

use Illuminate\Support\Facades\DB;
use Syscover\Core\Models\CoreModel;
use Illuminate\Support\Facades\Validator;

/**
 * Class CustomerDiscountHistory
 * @package Syscover\Market\Models
 */

class CustomerDiscountHistory extends CoreModel
{
	protected $table        = 'market_customer_discount_history';
    protected $fillable     = ['id', 'customer_id', 'order_id', 'applied', 'rule_type', 'rule_id', 'names', 'descriptions', 'has_coupon', 'coupon_code', 'discount_type_id', 'discount_fixed_amount', 'discount_percentage', 'maximum_discount_amount', 'apply_shipping_amount', 'free_shipping', 'discount_amount', 'data_lang', 'price_rule'];
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
        return $query
            ->join('crm_customer', 'market_customer_discount_history.customer_id', '=', 'crm_customer.id')
            ->join('market_order', 'market_customer_discount_history.order_id', '=', 'market_order.id');
    }
}