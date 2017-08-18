<?php namespace Syscover\Market\Services;

use Carbon\Carbon;
use Syscover\Market\Models\Order;

class OrderService
{
    /**
     * Function to create a order
     * @param $shoppingCart     \Syscover\ShoppingCart\Cart
     * @param $customer         \Syscover\Crm\Models\Customer
     * @param $ip               string
     * @return \Syscover\Market\Models\Order
     */
    public static function create($shoppingCart, $customer, $ip)
    {
        // create data order
        $orderDate = Carbon::now(config('app.timezone'))->toDateTimeString();
        $order = [
            'date'                                      => $orderDate,
            'payment_method_id'                         => $shoppingCart->paymentMethod,
            'status_id'                                 => 1,                                                       // Pending
            'ip'                                        => $ip,                                                     // customer IP
            'data'                                      => null,
            'comments'                                  => $shoppingCart->comments,

            //****************
            //* amounts
            //****************
            'discount_amount'                           => $shoppingCart->discountAmount,                           // total amount to discount, fixed plus percentage discounts
            'subtotal_with_discounts'                   => $shoppingCart->subtotalWithDiscounts,                    // subtotal with discounts applied
            'tax_amount'                                => $shoppingCart->taxAmount,                                // total tax amount
            'cart_items_total_without_discounts'        => $shoppingCart->cartItemsTotalWithoutDiscounts,           // total of cart items. Amount with tax, without discount and without shipping
            'subtotal'                                  => $shoppingCart->subtotal,                                 // amount without tax and without shipping
            'shipping_amount'                           => $shoppingCart->shippingAmount,                           // shipping amount, by default is 0
            'total'                                     => $shoppingCart->total,                                    // subtotal and shipping amount with tax

            //****************
            //* gift
            //****************
            'has_gift'                                  => $shoppingCart->getGift()->has('has_gift'),
            'gift_from'                                 => $shoppingCart->getGift()->get('from'),
            'gift_to'                                   => $shoppingCart->getGift()->get('to'),
            'gift_message'                              => $shoppingCart->getGift()->get('message'),
            'gift_comments'                             => $shoppingCart->getGift()->get('comments'),

            //****************
            //* customer
            //****************
            'customer_id'                               => $customer->id,
            'customer_group_id'                         => $customer->group_id,
            'customer_company'                          => $customer->company,
            'customer_tin'                              => $customer->tin,
            'customer_name'                             => $customer->name,
            'customer_surname'                          => $customer->surname,
            'customer_email'                            => $customer->email,
            'customer_mobile'                           => $customer->mobile,
            'customer_phone'                            => $customer->phone,

            //****************
            //* invoice data
            //****************
            'has_invoice'                               => $shoppingCart->getInvoice()->has('has_invoice'),
            'invoiced'                                  => $shoppingCart->getInvoice()->has('invoiced'),
            'invoice_number'                            => $shoppingCart->getInvoice()->get('number'),

            'invoice_company'                           => $shoppingCart->getInvoice()->get('company'),
            'invoice_tin'                               => $shoppingCart->getInvoice()->get('tin'),
            'invoice_name'                              => $shoppingCart->getInvoice()->get('name'),
            'invoice_surname'                           => $shoppingCart->getInvoice()->get('surname'),
            'invoice_email'                             => $shoppingCart->getInvoice()->get('email'),
            'invoice_mobile'                            => $shoppingCart->getInvoice()->get('mobile'),
            'invoice_phone'                             => $shoppingCart->getInvoice()->get('phone'),
            'invoice_country_id'                        => $shoppingCart->getInvoice()->get('country_id'),
            'invoice_territorial_area_1_id'             => $shoppingCart->getInvoice()->get('territorial_area_1_id'),
            'invoice_territorial_area_2_id'             => $shoppingCart->getInvoice()->get('territorial_area_2_id'),
            'invoice_territorial_area_3_id'             => $shoppingCart->getInvoice()->get('territorial_area_3_id'),
            'invoice_cp'                                => $shoppingCart->getInvoice()->get('cp'),
            'invoice_locality'                          => $shoppingCart->getInvoice()->get('locality'),
            'invoice_address'                           => $shoppingCart->getInvoice()->get('address'),
            'invoice_latitude'                          => $shoppingCart->getInvoice()->get('latitude'),
            'invoice_longitude'                         => $shoppingCart->getInvoice()->get('longitude'),
            'invoice_comments'                          => $shoppingCart->getInvoice()->get('comments'),

            //****************
            //* shipping data
            //****************
            'has_shipping'                              => $shoppingCart->getShipping()->get('has_shipping'),
            'shipping_company'                          => $shoppingCart->getShipping()->get('company'),
            'shipping_name'                             => $shoppingCart->getShipping()->get('name'),
            'shipping_surname'                          => $shoppingCart->getShipping()->get('surname'),
            'shipping_email'                            => $shoppingCart->getShipping()->get('email'),
            'shipping_mobile'                           => $shoppingCart->getShipping()->get('mobile'),
            'shipping_phone'                            => $shoppingCart->getShipping()->get('phone'),
            'shipping_country_id'                       => $shoppingCart->getShipping()->get('country_id'),
            'shipping_territorial_area_1_id'            => $shoppingCart->getShipping()->get('territorial_area_1_id'),
            'shipping_territorial_area_2_id'            => $shoppingCart->getShipping()->get('territorial_area_2_id'),
            'shipping_territorial_area_3_id'            => $shoppingCart->getShipping()->get('territorial_area_3_id'),
            'shipping_cp'                               => $shoppingCart->getShipping()->get('cp'),
            'shipping_locality'                         => $shoppingCart->getShipping()->get('locality'),
            'shipping_address'                          => $shoppingCart->getShipping()->get('address'),
            'shipping_latitude'                         => $shoppingCart->getShipping()->get('latitude'),
            'shipping_longitude'                        => $shoppingCart->getShipping()->get('longitude'),
            'shipping_comments'                         => $shoppingCart->getShipping()->get('comments'),
        ];

        return Order::create($order);
    }

    /**
     * @param   array     $object     contain properties of section
     * @param   int       $id         id of category
     * @param   string    $lang       lang of category
     * @return  \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|null|static|static[]
     */
    public static function update($object, $id, $lang)
    {
        // pass object to collection
        $object = collect($object);

//        Category::where('id', $id)
//            ->where('lang_id', $lang)
//            ->update([
//                'parent_id'             => $object->get('parent_id'),
//                'name'                  => $object->get('name'),
//                'slug'                  => $object->get('slug'),
//                'active'                => $object->get('active'),
//                'description'           => $object->get('description'),
//            ]);
//
//        return Category::find($object->get('id'));
    }
}