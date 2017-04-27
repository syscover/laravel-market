<?php namespace Syscover\Market\Models;

use Syscover\Core\Models\CoreModel;
use Illuminate\Support\Facades\Validator;

/**
 * Class CustomerDiscountHistory
 * @package Syscover\Market\Models
 */

class CustomerDiscountHistory extends CoreModel
{
	protected $table        = 'customer_discount_history';
    protected $fillable     = ['id', 'date', 'customer_id', 'order_id', 'rule_family_id', 'has_coupon', 'coupon_code', 'rule_id', 'discount', 'name_text_id', 'description_text_id', 'name_text_value', 'description_text_value', 'discount_type_id', 'discount_fixed_amount', 'discount_percentage', 'maximum_discount_amount', 'apply_shipping_amount', 'free_shipping', 'rules'];
    public $timestamps      = false;

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