<?php namespace Syscover\Market\Services;

use Carbon\Carbon;
use Syscover\Crm\Models\Customer;
use Syscover\Market\Models\Order;
use Syscover\ShoppingCart\Facades\CartProvider;

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
            $object = $object->only([
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
            ]);
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

    public static function log($id, $message)
    {
        $order = Order::find($id);

        if(! $order) return null;

        if(! is_array($order->data)) $order->data = [];
        if(! isset($order->data['logs']) || ! is_array($order->data['logs'])) $order->data = array_merge($order->data, ['logs' => []]);

        // get data field
        $data = $order->data;

        // add log object to logs array
        array_unshift($data['logs'], [
            'time'      => Carbon::now(config('app.timezone'))->toDateTimeString(),
            'status_id' => $order->status_id,
            'message'   => $message
        ]);

        $order->data = $data;
        $order->save();
    }

    public static function getDataOrder(Customer $customer, string $instance = null)
    {
        // get cart instance
        $cart = CartProvider::instance($instance);

        // set all input from request
        $data                                           = request()->all();

        // set customer IP
        $data['ip']                                     = request()->ip();

        // customer
        $data['customer_id']                            = $customer->id;
        $data['customer_group_id']                      = $customer->group_id;
        $data['customer_company']                       = $customer->company;
        $data['customer_tin']                           = $customer->tin;
        $data['customer_name']                          = $customer->name;
        $data['customer_surname']                       = $customer->surname;
        $data['customer_email']                         = $customer->email;
        $data['customer_mobile']                        = $customer->mobile;
        $data['customer_phone']                         = $customer->phone;

        // cart
        $data['cart_items_total_without_discounts']     = $cart->cartItemsTotalWithoutDiscounts;                    // total of cart items. Amount with tax, without discount and without shipping
        $data['subtotal']                               = $cart->subtotal;                                          // amount without tax and without shipping
        $data['discount_amount']                        = $cart->discountAmount;                                    // total amount to discount, fixed plus percentage discounts
        $data['subtotal_with_discounts']                = $cart->subtotalWithDiscounts;                             // subtotal with discounts applied
        $data['tax_amount']                             = $cart->taxAmount;                                         // total tax amount
        $data['shipping_amount']                        = $cart->hasFreeShipping() ? 0 : $cart->shippingAmount;     // shipping amount
        $data['total']                                  = $cart->total;                                             // shipping amount

        return $data;
    }
}