<?php namespace Syscover\Market\Services;

use Syscover\Market\Models\OrderRow;

class OrderRowService
{
    public static function create(array $object)
    {
        return OrderRow::create(OrderRowService::builder($object));
    }

    public static function insert(array $objects)
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

    private static function builder($object, $filterKeys = null)
    {
        $object = collect($object);
        if($filterKeys)
        {
            $object = $object->only($filterKeys);
        }
        else
        {
            $object = $object->only(
                'lang_id',
                'order_id',
                'product_id',

                // product
                'name',
                'description',
                'data',

                // amounts
                'price',
                'quantity',
                'subtotal',
                'total_without_discounts',

                // discounts
                'discount_subtotal_percentage',
                'discount_total_percentage',
                'discount_subtotal_percentage_amount',
                'discount_total_percentage_amount',
                'discount_subtotal_fixed_amount',
                'discount_total_fixed_amount',
                'discount_amount',

                // subtotal with discounts
                'subtotal_with_discounts',

                // taxes
                'tax_rules',
                'tax_amount',

                // total
                'total',

                // gift
                'has_gift',
                'gift_from',
                'gift_to',
                'gift_message',
                'gift_comments'
            );
        }

        return $object->toArray();
    }
}