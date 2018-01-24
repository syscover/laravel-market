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

        // prevent not null values
        if(array_key_exists('date', $object) && $object['date'] === null) unset($object['date']);
        if(array_key_exists('discount_amount', $object) && $object['discount_amount'] === null) unset($object['discount_amount']);
        if(array_key_exists('subtotal_with_discounts', $object) && $object['subtotal_with_discounts'] === null) unset($object['subtotal_with_discounts']);
        if(array_key_exists('tax_amount', $object) && $object['tax_amount'] === null) unset($object['tax_amount']);
        if(array_key_exists('cart_items_total_without_discounts', $object) && $object['cart_items_total_without_discounts'] === null) unset($object['cart_items_total_without_discounts']);
        if(array_key_exists('subtotal', $object) && $object['subtotal'] === null) unset($object['subtotal']);
        if(array_key_exists('shipping_amount', $object) && $object['shipping_amount'] === null) unset($object['shipping_amount']);
        if(array_key_exists('total', $object) && $object['total'] === null) unset($object['total']);
        if(array_key_exists('has_gift', $object) && $object['has_gift'] === null) unset($object['has_gift']);
        if(array_key_exists('has_invoice', $object) && $object['has_invoice'] === null) unset($object['has_invoice']);
        if(array_key_exists('invoiced', $object) && $object['invoiced'] === null) unset($object['invoiced']);
        if(array_key_exists('has_shipping', $object) && $object['has_shipping'] === null) unset($object['has_shipping']);

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
                'invoice_zip'                                   => $object->get('invoice_zip'),
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
                'shipping_zip'                                  => $object->get('shipping_zip'),
                'shipping_locality'                             => $object->get('shipping_locality'),
                'shipping_address'                              => $object->get('shipping_address'),
                'shipping_latitude'                             => $object->get('shipping_latitude'),
                'shipping_longitude'                            => $object->get('shipping_longitude'),
                'shipping_comments'                             => $object->get('shipping_comments'),
            ]);

        return Order::find($object->get('id'));
    }

    public static function orderBuilder($data, $customer, $cart, $shipping, $invoice, $ip)
    {
        $data = collect($data);

        if(! $data->get('payment_method_id')) throw new \Exception('You must set payment_method_id field');
        if(! $data->get('status_id')) throw new \Exception('You must set status_id field');

        return [
            'date'                                          => $data->get('date'),                                 // if date not exist create current date automatically
            'payment_method_id'                             => $data->get('payment_method_id'),
            'status_id'                                     => $data->get('status_id'),
            'ip'                                            => $ip,
            'data'                                          => $data->get('data'),
            'comments'                                      => $data->get('comments'),

            //****************
            //* amounts
            //****************
            'discount_amount'                               => $cart->discountAmount,                                   // total amount to discount, fixed plus percentage discounts
            'subtotal_with_discounts'                       => $cart->subtotalWithDiscounts,                            // subtotal with discounts applied
            'tax_amount'                                    => $cart->taxAmount,                                        // total tax amount
            'cart_items_total_without_discounts'            => $cart->cartItemsTotalWithoutDiscounts,                   // total of cart items. Amount with tax, without discount and without shipping
            'subtotal'                                      => $cart->subtotal,                                         // amount without tax and without shipping
            'shipping_amount'                               => $cart->hasFreeShipping()? 0 :  $cart->shippingAmount,    // shipping amount
            'total'                                         => $cart->total,

            //****************
            //* gift
            //****************
            'has_gift'                                      => $data->has('has_gift'),
            'gift_from'                                     => $data->get('gift_from'),
            'gift_to'                                       => $data->get('gift_to'),
            'gift_message'                                  => $data->get('gift_message'),

            //****************
            //* customer
            //****************
            'customer_id'                                   => $customer->id,
            'customer_group_id'                             => $customer->group_id,
            'customer_company'                              => $customer->company,
            'customer_tin'                                  => $customer->tin,
            'customer_name'                                 => $customer->name,
            'customer_surname'                              => $customer->surname,
            'customer_email'                                => $customer->email,
            'customer_mobile'                               => $customer->mobile,
            'customer_phone'                                => $customer->phone,

            //****************
            //* invoice data
            //****************
            'has_invoice'                                   => $invoice->get('has_invoice'),
            'invoiced'                                      => $invoice->get('invoiced'),
            'invoice_number'                                => $invoice->get('number'),
            'invoice_company'                               => $invoice->get('company'),
            'invoice_tin'                                   => $invoice->get('tin'),
            'invoice_name'                                  => $invoice->get('name'),
            'invoice_surname'                               => $invoice->get('surname'),
            'invoice_email'                                 => $invoice->get('email'),
            'invoice_mobile'                                => $invoice->get('mobile'),
            'invoice_phone'                                 => $invoice->get('phone'),
            'invoice_country_id'                            => $invoice->get('country_id'),
            'invoice_territorial_area_1_id'                 => $invoice->get('territorial_area_1_id'),
            'invoice_territorial_area_2_id'                 => $invoice->get('territorial_area_2_id'),
            'invoice_territorial_area_3_id'                 => $invoice->get('territorial_area_3_id'),
            'invoice_zip'                                   => $invoice->get('zip'),
            'invoice_locality'                              => $invoice->get('locality'),
            'invoice_address'                               => $invoice->get('address'),
            'invoice_latitude'                              => $invoice->get('latitude'),
            'invoice_longitude'                             => $invoice->get('longitude'),
            'invoice_comments'                              => $invoice->get('comments'),

            //****************
            //* shipping data
            //****************
            'has_shipping'                                  => $shipping->get('has_shipping'),
            'shipping_company'                              => $shipping->get('company'),
            'shipping_name'                                 => $shipping->get('name'),
            'shipping_surname'                              => $shipping->get('surname'),
            'shipping_email'                                => $shipping->get('email'),
            'shipping_mobile'                               => $shipping->get('mobile'),
            'shipping_phone'                                => $shipping->get('phone'),
            'shipping_country_id'                           => $shipping->get('country_id'),
            'shipping_territorial_area_1_id'                => $shipping->get('territorial_area_1_id'),
            'shipping_territorial_area_2_id'                => $shipping->get('territorial_area_2_id'),
            'shipping_territorial_area_3_id'                => $shipping->get('territorial_area_3_id'),
            'shipping_zip'                                  => $shipping->get('zip'),
            'shipping_locality'                             => $shipping->get('locality'),
            'shipping_address'                              => $shipping->get('address'),
            'shipping_latitude'                             => $shipping->get('latitude'),
            'shipping_longitude'                            => $shipping->get('longitude'),
            'shipping_comments'                             => $shipping->get('comments'),
        ];
    }
}