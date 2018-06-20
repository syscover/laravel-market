<?php namespace Syscover\Market\Services;

use Syscover\Market\Models\Order;

class OrderService
{
    public static function create(array $object)
    {
        OrderService::checkCreate($object);
        return Order::create(OrderService::builder($object));
    }

    public static function update(array $object)
    {
        OrderService::checkUpdate($object);

        if(! empty($object['data'])) $object['data'] = json_encode($object['data']);

        Order::where('id', $object['id'])->update(OrderService::builder($object));

        return Order::find($object['id']);
    }

    private static function builder($object, $filterKeys = null)
    {
        $object = collect($object);

        if($filterKeys)
        {
            return $object->only($filterKeys);
        }
        else
        {
            $object = $object->only(
                'date',
                'payment_method_id',
                'status_id',
                'ip',
                'data',
                'comments',
                'transaction_id',

                'cart_items_total_without_discounts',
                'subtotal',
                'discount_amount',
                'subtotal_with_discounts',
                'tax_amount',
                'shipping_amount',
                'total',

                'customer_id',
                'customer_group_id',
                'customer_company',
                'customer_tin',
                'customer_name',
                'customer_surname',
                'customer_email',
                'customer_mobile',
                'customer_phone',

                'has_shipping',
                'shipping_tracking_id',
                'shipping_company',
                'shipping_name',
                'shipping_surname',
                'shipping_email',
                'shipping_mobile',
                'shipping_phone',
                'shipping_country_id',
                'shipping_territorial_area_1_id',
                'shipping_territorial_area_2_id',
                'shipping_territorial_area_3_id',
                'shipping_zip',
                'shipping_locality',
                'shipping_address',
                'shipping_latitude',
                'shipping_longitude',
                'shipping_comments',

                'has_invoice',
                'invoiced',
                'invoice_number',
                'invoice_company',
                'invoice_tin',
                'invoice_name',
                'invoice_surname',
                'invoice_email',
                'invoice_mobile',
                'invoice_phone',
                'invoice_country_id',
                'invoice_territorial_area_1_id',
                'invoice_territorial_area_2_id',
                'invoice_territorial_area_3_id',
                'invoice_zip',
                'invoice_locality',
                'invoice_address',
                'invoice_latitude',
                'invoice_longitude',
                'invoice_comments',

                'has_gift',
                'gift_from',
                'gift_to',
                'gift_message',
                'gift_comments'
            );
        }

        return $object->toArray();
    }

    private static function checkCreate($object)
    {
        if(empty($object['payment_method_id'])) throw new \Exception('You have to define a payment_method_id field to create a order');
        if(empty($object['status_id']))         throw new \Exception('You have to define a status_id field to create a order');
        if(empty($object['customer_id']))       throw new \Exception('You have to define a customer_id field to create a order');
        if(empty($object['customer_group_id'])) throw new \Exception('You have to define a customer_group_id field to create a order');
        if(empty($object['customer_email']))    throw new \Exception('You have to define a customer_email field to create a order');
    }

    private static function checkUpdate($object)
    {
        if(empty($object['id'])) throw new \Exception('You have to define a id field to update a order');
    }
}