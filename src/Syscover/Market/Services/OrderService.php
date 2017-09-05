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
                'customer_id'                                   => $object->get('customer_id'),
                'customer_group_id'                             => $object->get('customer_group_id'),
                'customer_company'                              => $object->get('customer_company'),
                'customer_tin'                                  => $object->get('customer_tin'),
                'customer_name'                                 => $object->get('customer_name'),
                'customer_surname'                              => $object->get('customer_surname'),
                'customer_email'                                => $object->get('customer_email'),
                'customer_mobile'                               => $object->get('customer_mobile'),
                'customer_phone'                                => $object->get('customer_phone'),

                //****************
                //* invoice data
                //****************
                'has_invoice'                                   => $object->get('has_invoice'),
                'invoiced'                                      => $object->get('invoiced'),
                'invoice_number'                                => $object->get('invoice_number'),
                'invoice_company'                               => $object->get('invoice_company'),
                'invoice_tin'                                   => $object->get('invoice_tin'),
                'invoice_name'                                  => $object->get('invoice_name'),
                'invoice_surname'                               => $object->get('invoice_surname'),
                'invoice_email'                                 => $object->get('invoice_email'),
                'invoice_mobile'                                => $object->get('invoice_mobile'),
                'invoice_phone'                                 => $object->get('invoice_phone'),
                'invoice_country_id'                            => $object->get('invoice_country_id'),
                'invoice_territorial_area_1_id'                 => $object->get('invoice_territorial_area_1_id'),
                'invoice_territorial_area_2_id'                 => $object->get('invoice_territorial_area_2_id'),
                'invoice_territorial_area_3_id'                 => $object->get('invoice_territorial_area_3_id'),
                'invoice_cp'                                    => $object->get('invoice_cp'),
                'invoice_locality'                              => $object->get('invoice_locality'),
                'invoice_address'                               => $object->get('invoice_address'),
                'invoice_latitude'                              => $object->get('invoice_latitude'),
                'invoice_longitude'                             => $object->get('invoice_longitude'),
                'invoice_comments'                              => $object->get('invoice_comments'),

                //****************
                //* shipping data
                //****************
                'has_shipping'                                  => $object->get('has_shipping'),
                'shipping_company'                              => $object->get('shipping_company'),
                'shipping_name'                                 => $object->get('shipping_name'),
                'shipping_surname'                              => $object->get('shipping_surname'),
                'shipping_email'                                => $object->get('shipping_email'),
                'shipping_mobile'                               => $object->get('shipping_mobile'),
                'shipping_phone'                                => $object->get('shipping_phone'),
                'shipping_country_id'                           => $object->get('shipping_country_id'),
                'shipping_territorial_area_1_id'                => $object->get('shipping_territorial_area_1_id'),
                'shipping_territorial_area_2_id'                => $object->get('shipping_territorial_area_2_id'),
                'shipping_territorial_area_3_id'                => $object->get('shipping_territorial_area_3_id'),
                'shipping_cp'                                   => $object->get('shipping_cp'),
                'shipping_locality'                             => $object->get('shipping_locality'),
                'shipping_address'                              => $object->get('shipping_address'),
                'shipping_latitude'                             => $object->get('shipping_latitude'),
                'shipping_longitude'                            => $object->get('shipping_longitude'),
                'shipping_comments'                             => $object->get('shipping_comments'),
            ]);

        return Order::find($object->get('id'));
    }
}