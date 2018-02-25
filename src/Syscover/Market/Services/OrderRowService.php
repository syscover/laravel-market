<?php namespace Syscover\Market\Services;

use Syscover\Market\Models\OrderRow;

class OrderRowService
{
    /**
     * @param array $object
     * @return \Syscover\Market\Models\OrderRow
     */
    public static function create(array $object)
    {
        return OrderRow::create(OrderRowService::builder($object));
    }

    /**
     * @param   array $objects
     * @return  bool
     */
    public static function insert($objects)
    {
        $rows = [];
        foreach ($objects as $object)
        {
            if(! empty($object['data'])) $object['data'] = json_encode($object['data']);
            if(! empty($object['tax_rules'])) $object['tax_rules'] = json_encode($object['tax_rules']);

            $rows[] = OrderRowService::builder($object);
        }

        return OrderRow::insert($rows);
    }

    private static function builder($object)
    {
        $object = collect($object);
        $data = [];

        if($object->has('order_id'))                                $data['order_id'] = $object->get('order_id');
        if($object->has('lang_id'))                                 $data['lang_id'] = $object->get('lang_id');
        if($object->has('product_id'))                              $data['product_id'] = $object->get('product_id');

        // product
        if($object->has('name'))                                    $data['name'] = $object->get('name');
        if($object->has('description'))                             $data['description'] = $object->get('description');
        if($object->has('data'))                                    $data['data'] = $object->get('data');

        // amounts
        if($object->has('price'))                                   $data['price'] = $object->get('price');
        if($object->has('quantity'))                                $data['quantity'] = $object->get('quantity');
        if($object->has('subtotal'))                                $data['subtotal'] = $object->get('subtotal');
        if($object->has('total_without_discounts'))                 $data['total_without_discounts'] = $object->get('total_without_discounts');

        // discounts
        if($object->has('discount_subtotal_percentage'))            $data['discount_subtotal_percentage'] = $object->get('discount_subtotal_percentage');
        if($object->has('discount_total_percentage'))               $data['discount_total_percentage'] = $object->get('discount_total_percentage');
        if($object->has('discount_subtotal_percentage_amount'))     $data['discount_subtotal_percentage_amount'] = $object->get('discount_subtotal_percentage_amount');
        if($object->has('discount_total_percentage_amount'))        $data['discount_total_percentage_amount'] = $object->get('discount_total_percentage_amount');
        if($object->has('discount_subtotal_fixed_amount'))          $data['discount_subtotal_fixed_amount'] = $object->get('discount_subtotal_fixed_amount');
        if($object->has('discount_total_fixed_amount'))             $data['discount_total_fixed_amount'] = $object->get('discount_total_fixed_amount');
        if($object->has('discount_amount'))                         $data['discount_amount'] = $object->get('discount_amount');

        // subtotal with discounts
        if($object->has('subtotal_with_discounts'))                 $data['subtotal_with_discounts'] = $object->get('subtotal_with_discounts');

        // taxes
        if($object->has('tax_rules'))                               $data['tax_rules'] = $object->get('tax_rules');
        if($object->has('tax_amount'))                              $data['tax_amount'] = $object->get('tax_amount');

        // total
        if($object->has('total'))                                   $data['total'] = $object->get('total');

        // gift
        if($object->has('has_gift'))                                $data['has_gift'] = $object->get('has_gift');
        if($object->has('gift_from'))                               $data['gift_from'] = $object->get('gift_from');
        if($object->has('gift_to'))                                 $data['gift_to'] = $object->get('gift_to');
        if($object->has('gift_message'))                            $data['gift_message'] = $object->get('gift_message');
        if($object->has('gift_comments'))                           $data['gift_comments'] = $object->get('gift_comments');

        return $object;
    }
}