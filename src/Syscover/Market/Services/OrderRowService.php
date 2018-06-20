<?php namespace Syscover\Market\Services;

use Syscover\Market\Models\OrderRow;

class OrderRowService
{
    public static function create(array $object)
    {
        OrderRowService::checkCreate($object);
        return OrderRow::create(OrderRowService::builder($object));
    }

    public static function update(array $object)
    {
        OrderRowService::checkUpdate($object);

        if(! empty($object['data'])) $object['data'] = json_encode($object['data']);
        if(! empty($object['tax_rules'])) $object['tax_rules'] = json_encode($object['tax_rules']);

        OrderRow::where('id', $object['id'])->update(OrderRow::builder($object));

        return OrderRow::find($object['id']);
    }

    public static function insert(array $objects)
    {
        $rows = [];
        foreach ($objects as $object)
        {
            OrderRowService::checkCreate($object);

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
            $object = $object->only([
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
            ]);
        }

        return $object->toArray();
    }

    private static function checkCreate($object)
    {
        if(empty($object['lang_id']))                               throw new \Exception('You have to define a lang_id field to create a order row');
        if(empty($object['order_id']))                              throw new \Exception('You have to define a order_id field to create a order row');
        if(empty($object['price']))                                 throw new \Exception('You have to define a price field to create a order row');
        if(empty($object['quantity']))                              throw new \Exception('You have to define a quantity field to create a order row');
        if(empty($object['subtotal']))                              throw new \Exception('You have to define a subtotal field to create a order row');
        if(empty($object['total_without_discounts']))               throw new \Exception('You have to define a total_without_discounts field to create a order row');
        if(empty($object['discount_subtotal_percentage']))          throw new \Exception('You have to define a discount_subtotal_percentage field to create a order row');
        if(empty($object['discount_total_percentage']))             throw new \Exception('You have to define a discount_total_percentage field to create a order row');
        if(empty($object['discount_subtotal_percentage_amount']))   throw new \Exception('You have to define a discount_subtotal_percentage_amount field to create a order row');
        if(empty($object['discount_total_percentage_amount']))      throw new \Exception('You have to define a discount_total_percentage_amount field to create a order row');
        if(empty($object['discount_subtotal_fixed_amount']))        throw new \Exception('You have to define a discount_subtotal_fixed_amount field to create a order row');
        if(empty($object['discount_total_fixed_amount']))           throw new \Exception('You have to define a discount_total_fixed_amount field to create a order row');
        if(empty($object['discount_amount']))                       throw new \Exception('You have to define a discount_amount field to create a order row');
        if(empty($object['subtotal_with_discounts']))               throw new \Exception('You have to define a subtotal_with_discounts field to create a order row');
        if(empty($object['tax_rules']))                             throw new \Exception('You have to define a tax_rules field to create a order row');
        if(empty($object['tax_amount']))                            throw new \Exception('You have to define a tax_amount field to create a order row');
        if(empty($object['total']))                                 throw new \Exception('You have to define a total field to create a order row');
    }

    private static function checkUpdate($object)
    {
        if(empty($object['id'])) throw new \Exception('You have to define a id field to update a order row');
    }
}