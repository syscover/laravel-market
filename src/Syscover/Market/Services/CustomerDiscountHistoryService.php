<?php namespace Syscover\Market\Services;

use Syscover\Market\Models\CustomerDiscountHistory;

class CustomerDiscountHistoryService
{
    /**
     * Function to create a order
     *
     * @param array $object
     * @return  \Syscover\Market\Models\CustomerDiscountHistory
     * @throws \Exception
     */
    public static function create(array $object)
    {
        if(empty($object['lang_id']))           throw new \Exception('You have to define a lang_id field to create a customer history discount');
        if(empty($object['customer_id']))       throw new \Exception('customer_id is required');
        if(empty($object['order_id']))          throw new \Exception('order_id is required');

        return CustomerDiscountHistory::create(CustomerDiscountHistoryService::builder($object));
    }

    /**
     * @param array $object
     * @return mixed
     */
    public static function update(array $object)
    {
        if(! empty($object['names'])) $object['names'] = json_encode($object['names']);
        if(! empty($object['data'])) $object['data'] = json_encode($object['data']);

        //Order::where('id', $object['id'])->update(OrderService::builder($object));

        return Order::find($object->get('id'));
    }

    /**
     * @param array $object
     * @return array
     */
    private static function builder(array $object)
    {
        $object = collect($object);
        $data = [];

        if($object->has('customer_id'))                         $data['customer_id'] = $object->get('customer_id');
        if($object->has('order_id'))                            $data['order_id'] = $object->get('order_id');
        if($object->has('applied'))                             $data['applied'] = $object->get('applied');
        if($object->has('rule_type'))                           $data['rule_type'] = $object->get('rule_type');
        if($object->has('rule_id'))                             $data['rule_id'] = $object->get('rule_id');
        if($object->has('has_coupon'))                          $data['has_coupon'] = $object->get('has_coupon');
        if($object->has('coupon_code'))                         $data['coupon_code'] = $object->get('coupon_code');
        if($object->has('names'))                               $data['names'] = $object->get('names');
        if($object->has('descriptions'))                        $data['descriptions'] = $object->get('descriptions');
        if($object->has('condition_rules'))                     $data['condition_rules'] = $object->get('condition_rules');
        if($object->has('discount_type_id'))                    $data['discount_type_id'] = $object->get('discount_type_id');
        if($object->has('minimum_amount'))                      $data['minimum_amount'] = $object->get('minimum_amount');
        if($object->has('discount_fixed_amount'))               $data['discount_fixed_amount'] = $object->get('discount_fixed_amount');
        if($object->has('discount_percentage'))                 $data['discount_percentage'] = $object->get('discount_percentage');
        if($object->has('maximum_discount_amount'))             $data['maximum_discount_amount'] = $object->get('maximum_discount_amount');
        if($object->has('discount_amount'))                     $data['discount_amount'] = $object->get('discount_amount');
        if($object->has('apply_shipping_amount'))               $data['apply_shipping_amount'] = $object->get('apply_shipping_amount');
        if($object->has('free_shipping'))                       $data['free_shipping'] = $object->get('free_shipping');
        if($object->has('product_rules'))                       $data['product_rules'] = $object->get('product_rules');
        if($object->has('data_lang'))                           $data['data_lang'] = $object->get('data_lang');

        return $data;
    }
}