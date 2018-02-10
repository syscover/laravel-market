<?php namespace Syscover\Market\Services;

use Carbon\Carbon;
use Syscover\Market\Models\CartPriceRule;
use Syscover\Market\Models\Category;

class CartPriceRuleService
{
    /**
     * Function to create a cart price rule
     * @param   array $object
     * @return  \Syscover\Market\Models\CartPriceRule
     * @throws  \Exception
     */
    public static function create($object)
    {
        return CartPriceRule::create(CartPriceRuleService::builder($object));
    }

    /**
     * @param   array     $object     contain properties of cart price rule
     * @return  \Syscover\Market\Models\CartPriceRule
     */
    public static function update($object, $id)
    {
        CartPriceRule::where('id', CartPriceRuleService::builder($object));

        return Category::where('id', $id)->first();
    }

    private static function builder($object)
    {
        $object = collect($object);
        $data = [];

        if($object->has('active'))                      $data['active'] = $object->get('active');
        if($object->has('combinable'))                  $data['combinable'] = $object->get('combinable');
        if($object->has('has_coupon'))                  $data['has_coupon'] = $object->get('has_coupon');
        if($object->has('coupon_code'))                 $data['coupon_code'] = $object->get('coupon_code');
        if($object->has('used_customer'))               $data['used_customer'] = $object->get('used_customer');
        if($object->has('used_coupon'))                 $data['used_coupon'] = $object->get('used_coupon');
        if($object->has('total_used'))                  $data['total_used'] = $object->get('total_used');
        // use preg_replace to format date from Google Chrome, attach (Hota de verano romance) string
        if($object->has('enable_from'))                 $data['enable_from'] = (new Carbon(preg_replace('/\(.*\)/','', $object['enable_from']), config('app.timezone')))->toDateTimeString();
        if($object->has('enable_to'))                   $data['enable_to'] = (new Carbon(preg_replace('/\(.*\)/','', $object['enable_to']), config('app.timezone')))->toDateTimeString();
        if($object->has('discount_type_id'))            $data['discount_type_id'] = $object->get('discount_type_id');
        if($object->has('discount_fixed_amount'))       $data['discount_fixed_amount'] = $object->get('discount_fixed_amount');
        if($object->has('discount_percentage'))         $data['discount_percentage'] = $object->get('discount_percentage');
        if($object->has('maximum_discount_amount'))     $data['maximum_discount_amount'] = $object->get('maximum_discount_amount');
        if($object->has('apply_shipping_amount'))       $data['apply_shipping_amount'] = $object->get('apply_shipping_amount');
        if($object->has('free_shipping'))               $data['free_shipping'] = $object->get('free_shipping');

        return $data;
    }
}