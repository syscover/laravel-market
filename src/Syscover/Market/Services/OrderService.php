<?php namespace Syscover\Market\Services;

use Syscover\Market\Models\Order;

class OrderService
{
    /**
     * Function to create a order
     *
     * @param $object
     * @return $this|\Illuminate\Database\Eloquent\Model
     * @throws \Exception
     */
    public static function create($object)
    {
        if(empty($object['payment_method_id'])) throw new \Exception('payment_method_id is required');
        if(empty($object['status_id']))         throw new \Exception('status_id is required');
        if(empty($object['customer_id']))       throw new \Exception('customer_id is required');
        if(empty($object['customer_group_id'])) throw new \Exception('customer_group_id is required');
        if(empty($object['customer_email']))    throw new \Exception('customer_email is required');

        return Order::create($object);
    }

    /**
     * @param   array     $object     contain properties of section
     * @param   int       $id         id of order
     * @return  \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|null|static|static[]
     */
    public static function update($object, $id)
    {
        // pass object to collection
        $object = collect($object);

        Order::where('id', $id)
            ->update([
                'date'                                          => $object->get('date'),
                'payment_method_id'                             => $object->get('payment_method_id'),
                'status_id'                                     => $object->get('status_id'),
                'ip'                                            => $object->get('ip'),
                'data'                                          => json_encode($object->get('data')),
                'comments'                                      => $object->get('comments'),

                'transaction_id'                                => $object->get('transaction_id'),

                //****************
                //* amounts
                //****************
                'discount_amount'                               => $object->get('discount_amount'),
                'subtotal_with_discounts'                       => $object->get('subtotal_with_discounts'),
                'tax_amount'                                    => $object->get('tax_amount'),
                'cart_items_total_without_discounts'            => $object->get('cart_items_total_without_discounts'),
                'subtotal'                                      => $object->get('subtotal'),
                'shipping_amount'                               => $object->get('shipping_amount'),
                'total'                                         => $object->get('total'),

                //****************
                //* gift
                //****************
                'has_gift'                                      => $object->get('has_gift'),
                'gift_from'                                     => $object->get('gift_from'),
                'gift_to'                                       => $object->get('gift_to'),
                'gift_message'                                  => $object->get('gift_message'),
                'gift_comments'                                 => $object->get('gift_comments'),

                //****************
                //* customer
                //****************

            ]);

        return Order::find($object->get('id'));
    }
}