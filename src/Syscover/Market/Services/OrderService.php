<?php namespace Syscover\Market\Services;

use Syscover\Market\Models\Order;
use Syscover\ShoppingCart\Cart;
use Syscover\Crm\Models\Customer;

class OrderService
{
    /**
     * Function to create a order
     *
     * @param array $object
     * @param Cart|null $cart
     * @param Customer|null $customer
     * @param string|null $ip
     * @return  \Syscover\Market\Models\Order
     * @throws \Exception
     */
    public static function create(
        array $object,
        Cart $cart = null,
        Customer $customer = null,
        string $ip = null
    )
    {
        if(empty($object['payment_method_id'])) throw new \Exception('payment_method_id is required');
        if(empty($object['status_id']))         throw new \Exception('status_id is required');
        if(empty($object['customer_id']))       throw new \Exception('customer_id is required');
        if(empty($object['customer_group_id'])) throw new \Exception('customer_group_id is required');
        if(empty($object['customer_email']))    throw new \Exception('customer_email is required');

        // prevent not null values
//        if(array_key_exists('date', $object) && $object['date'] === null) unset($object['date']);
//        if(array_key_exists('discount_amount', $object) && $object['discount_amount'] === null) unset($object['discount_amount']);
//        if(array_key_exists('subtotal_with_discounts', $object) && $object['subtotal_with_discounts'] === null) unset($object['subtotal_with_discounts']);
//        if(array_key_exists('tax_amount', $object) && $object['tax_amount'] === null) unset($object['tax_amount']);
//        if(array_key_exists('cart_items_total_without_discounts', $object) && $object['cart_items_total_without_discounts'] === null) unset($object['cart_items_total_without_discounts']);
//        if(array_key_exists('subtotal', $object) && $object['subtotal'] === null) unset($object['subtotal']);
//        if(array_key_exists('shipping_amount', $object) && $object['shipping_amount'] === null) unset($object['shipping_amount']);
//        if(array_key_exists('total', $object) && $object['total'] === null) unset($object['total']);
//        if(array_key_exists('has_gift', $object) && $object['has_gift'] === null) unset($object['has_gift']);
//        if(array_key_exists('has_invoice', $object) && $object['has_invoice'] === null) unset($object['has_invoice']);
//        if(array_key_exists('invoiced', $object) && $object['invoiced'] === null) unset($object['invoiced']);
//        if(array_key_exists('has_shipping', $object) && $object['has_shipping'] === null) unset($object['has_shipping']);

        return Order::create(OrderService::builder($object, $cart, $customer, $ip));
    }

    /**
     * @param   array     $object     contain properties of section
     * @return  \Syscover\Market\Models\Order
     */
    public static function update($object)
    {
        // set group_ids field
        if(! empty($object['data'])) $object['data'] = json_encode($object['data']);

        Order::where('id', $object['id'])->update(OrderService::builder($object));

        return Order::find($object->get('id'));
    }

    /**
     * @param array $object
     * @param Cart|null $cart
     * @param Customer|null $customer
     * @param string|null $ip
     * @return array
     */
    private static function builder(
        array $object,
        Cart $cart = null,
        Customer $customer = null,
        string $ip
    )
    {
        $object = collect($object);
        $data = [];

        if($object->has('date'))                                $data['date'] = $object->get('date');
        if($object->has('payment_method_id'))                   $data['payment_method_id'] = $object->get('payment_method_id');
        if($object->has('status_id'))                           $data['status_id'] = $object->get('status_id');
        if($ip)                                                      $data['ip'] = $ip;
        if($object->has('data'))                                $data['data'] = $object->get('data');
        if($object->has('comments'))                            $data['comments'] = $object->get('comments');

        // amounts
        if($cart)
        {
            $data['discount_amount']                        = $cart->discountAmount;                                    // total amount to discount, fixed plus percentage discounts
            $data['subtotal_with_discounts']                = $cart->subtotalWithDiscounts;                             // subtotal with discounts applied
            $data['tax_amount']                             = $cart->taxAmount;                                         // total tax amount
            $data['cart_items_total_without_discounts']     = $cart->cartItemsTotalWithoutDiscounts;                    // total of cart items. Amount with tax, without discount and without shipping
            $data['subtotal']                               = $cart->subtotal;                                          // amount without tax and without shipping
            $data['shipping_amount']                        = $cart->hasFreeShipping() ? 0 : $cart->shippingAmount;     // shipping amount
            $data['total']                                  = $cart->total;                                             // shipping amount
        }

        // gift
        if($object->has('has_gift'))                            $data['has_gift'] = $object->get('has_gift');
        if($object->has('gift_from'))                           $data['gift_from'] = $object->get('gift_from');
        if($object->has('gift_to'))                             $data['gift_to'] = $object->get('gift_to');
        if($object->has('gift_message'))                        $data['gift_message'] = $object->get('gift_message');

        // customer
        if($customer)
        {
            $data['customer_id']            = $customer->id;
            $data['customer_group_id']      = $customer->group_id;
            $data['customer_company']       = $customer->company;
            $data['customer_tin']           = $customer->tin;
            $data['customer_name']          = $customer->name;
            $data['customer_surname']       = $customer->surname;
            $data['customer_email']         = $customer->email;
            $data['customer_mobile']        = $customer->mobile;
            $data['customer_phone']         = $customer->phone;
        }

        // invoice
        if($object->has('has_invoice'))                         $data['has_invoice'] = $object->get('has_invoice');
        if($object->has('invoiced'))                            $data['invoiced'] = $object->get('invoiced');
        if($object->has('invoice_number'))                      $data['invoice_number'] = $object->get('invoice_number');
        if($object->has('invoice_company'))                     $data['invoice_company'] = $object->get('invoice_company');
        if($object->has('invoice_tin'))                         $data['invoice_tin'] = $object->get('invoice_tin');
        if($object->has('invoice_name'))                        $data['invoice_name'] = $object->get('invoice_name');
        if($object->has('invoice_surname'))                     $data['invoice_surname'] = $object->get('invoice_surname');
        if($object->has('invoice_email'))                       $data['invoice_email'] = $object->get('invoice_email');
        if($object->has('invoice_mobile'))                      $data['invoice_mobile'] = $object->get('invoice_mobile');
        if($object->has('invoice_phone'))                       $data['invoice_phone'] = $object->get('invoice_phone');
        if($object->has('invoice_country_id'))                  $data['invoice_country_id'] = $object->get('invoice_country_id');
        if($object->has('invoice_territorial_area_1_id'))       $data['invoice_territorial_area_1_id'] = $object->get('invoice_territorial_area_1_id');
        if($object->has('invoice_territorial_area_2_id'))       $data['invoice_territorial_area_2_id'] = $object->get('invoice_territorial_area_2_id');
        if($object->has('invoice_territorial_area_3_id'))       $data['invoice_territorial_area_3_id'] = $object->get('invoice_territorial_area_3_id');
        if($object->has('invoice_zip'))                         $data['invoice_zip'] = $object->get('invoice_zip');
        if($object->has('invoice_locality'))                    $data['invoice_locality'] = $object->get('invoice_locality');
        if($object->has('invoice_address'))                     $data['invoice_address'] = $object->get('invoice_address');
        if($object->has('invoice_latitude'))                    $data['invoice_latitude'] = $object->get('invoice_latitude');
        if($object->has('invoice_longitude'))                   $data['invoice_longitude'] = $object->get('invoice_longitude');
        if($object->has('invoice_comments'))                    $data['invoice_comments'] = $object->get('invoice_comments');

        // shipping
        if($object->has('has_shipping'))                        $data['has_shipping'] = $object->get('has_shipping');
        if($object->has('shipping_company'))                    $data['shipping_company'] = $object->get('shipping_company');
        if($object->has('shipping_name'))                       $data['shipping_name'] = $object->get('shipping_name');
        if($object->has('shipping_surname'))                    $data['shipping_surname'] = $object->get('shipping_surname');
        if($object->has('shipping_email'))                      $data['shipping_email'] = $object->get('shipping_email');
        if($object->has('shipping_mobile'))                     $data['shipping_mobile'] = $object->get('shipping_mobile');
        if($object->has('shipping_phone'))                      $data['shipping_phone'] = $object->get('shipping_phone');
        if($object->has('shipping_country_id'))                 $data['shipping_country_id'] = $object->get('shipping_country_id');
        if($object->has('shipping_territorial_area_1_id'))      $data['shipping_territorial_area_1_id'] = $object->get('shipping_territorial_area_1_id');
        if($object->has('shipping_territorial_area_2_id'))      $data['shipping_territorial_area_2_id'] = $object->get('shipping_territorial_area_2_id');
        if($object->has('shipping_territorial_area_3_id'))      $data['shipping_territorial_area_3_id'] = $object->get('shipping_territorial_area_3_id');
        if($object->has('shipping_zip'))                        $data['shipping_zip'] = $object->get('shipping_zip');
        if($object->has('shipping_locality'))                   $data['shipping_locality'] = $object->get('shipping_locality');
        if($object->has('shipping_address'))                    $data['shipping_address'] = $object->get('shipping_address');
        if($object->has('shipping_latitude'))                   $data['shipping_latitude'] = $object->get('shipping_latitude');
        if($object->has('shipping_longitude'))                  $data['shipping_longitude'] = $object->get('shipping_longitude');
        if($object->has('shipping_comments'))                   $data['shipping_comments'] = $object->get('shipping_comments');

        return $data;
    }
}