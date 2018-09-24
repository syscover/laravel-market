<?php namespace Syscover\Market\Services;

use Syscover\Crm\Models\Customer;
use Syscover\Market\Models\CustomerDiscountHistory;
use Syscover\Market\Models\Order;
use Syscover\ShoppingCart\Facades\CartProvider;

class CustomerDiscountHistoryService
{
    public static function create(array $object)
    {
        self::checkCreate($object);
        return CustomerDiscountHistory::create(self::builder($object));
    }

    public static function insert(array $objects)
    {
        $discounts = [];
        foreach ($objects as $object)
        {
            self::checkCreate($object);

            if(! empty($object['names'])) $object['names'] = json_encode($object['names']);
            if(! empty($object['descriptions'])) $object['descriptions'] = json_encode($object['descriptions']);
            if(! empty($object['data_lang'])) $object['data_lang'] = json_encode($object['data_lang']);
            if(! empty($object['price_rule'])) $object['price_rule'] = json_encode($object['price_rule']);

            $discounts[] = self::builder($object);
        }

        return CustomerDiscountHistory::insert($discounts);
    }

    public static function update(array $object)
    {
        self::checkUpdate($object);

        if(! empty($object['names'])) $object['names'] = json_encode($object['names']);
        if(! empty($object['descriptions'])) $object['descriptions'] = json_encode($object['descriptions']);
        if(! empty($object['data_lang'])) $object['data_lang'] = json_encode($object['data_lang']);
        if(! empty($object['price_rule'])) $object['price_rule'] = json_encode($object['price_rule']);

        CustomerDiscountHistory::where('id', $object['id'])->update(self::builder($object));

        return CustomerDiscountHistory::find($object['id']);
    }

    private static function builder(array $object)
    {
        $object = collect($object);
        $data = [];

        if($object->has('customer_id'))                         $data['customer_id'] = $object->get('customer_id');
        if($object->has('order_id'))                            $data['order_id'] = $object->get('order_id');
        if($object->has('applied'))                             $data['applied'] = $object->get('applied');
        if($object->has('discount_amount'))                     $data['discount_amount'] = $object->get('discount_amount');
        if($object->has('rule_type'))                           $data['rule_type'] = $object->get('rule_type');
        if($object->has('rule_id'))                             $data['rule_id'] = $object->get('rule_id');
        if($object->has('names'))                               $data['names'] = $object->get('names');
        if($object->has('descriptions'))                        $data['descriptions'] = $object->get('descriptions');
        if($object->has('has_coupon'))                          $data['has_coupon'] = $object->get('has_coupon');
        if($object->has('coupon_code'))                         $data['coupon_code'] = $object->get('coupon_code');
        if($object->has('discount_type_id'))                    $data['discount_type_id'] = $object->get('discount_type_id');
        if($object->has('discount_fixed_amount'))               $data['discount_fixed_amount'] = $object->get('discount_fixed_amount');
        if($object->has('discount_percentage'))                 $data['discount_percentage'] = $object->get('discount_percentage');
        if($object->has('maximum_discount_amount'))             $data['maximum_discount_amount'] = $object->get('maximum_discount_amount');
        if($object->has('apply_shipping_amount'))               $data['apply_shipping_amount'] = $object->get('apply_shipping_amount');
        if($object->has('free_shipping'))                       $data['free_shipping'] = $object->get('free_shipping');
        if($object->has('data_lang'))                           $data['data_lang'] = $object->get('data_lang');
        if($object->has('price_rule'))                          $data['price_rule'] = $object->get('price_rule');

        return $data;
    }

    private static function checkCreate($object)
    {
        if(empty($object['data_lang']))         throw new \Exception('You have to define a data_lang field from CartPriceRule to create a customer history discount');
        if(empty($object['customer_id']))       throw new \Exception('You have to define a customer_id field to create a customer history discount');
        if(empty($object['order_id']))          throw new \Exception('You have to define a order_id field to create a customer history discount');
        if(empty($object['rule_type']))         throw new \Exception('You have to define a rule_type field to create a customer history discount');
        if(empty($object['price_rule']))        throw new \Exception('You have to define a price_rule field to create a customer history discount');
    }

    private static function checkUpdate($object)
    {
        if(empty($object['id'])) throw new \Exception('You have to define a id field to update a customer history discount');
    }

    public static function getDataCustomerDiscountHistory(Customer $customer, Order $order, bool $applied = true)
    {
        if(! $customer || empty($customer->id)) throw new \Exception('You have to define a customer object to get data customer history discount');
        if(! $order || empty($order->id)) throw new \Exception('You have to define a order object to get data customer history discount');

        $customerDiscountHistory = [];
        foreach (CartProvider::instance()->getPriceRules() as $discount)
        {
            $priceRule = $discount->options->priceRule;

            $dataAux = [
                'customer_id'                   => $customer->id,
                'order_id'                      => $order->id,

                // if order is canceled, you can deactivate discounts
                'applied'                       => $applied,

                // discount amount of this rule
                'discount_amount'               => $discount->discountAmount,

                // rule encode in json format
                'price_rule'                    => $priceRule,

                // name of discount model:
                // CartPriceRule
                // CatalogPriceRule
                // CustomerPriceRule
                'rule_type'                     => get_class($priceRule),
                'rule_id'                       => $priceRule->id,

                'names'                         => $priceRule->names,
                'descriptions'                  => $priceRule->descriptions,

                // data lang to know languages in names and descriptions fields
                'data_lang'                     => $priceRule->data_lang,

                'has_coupon'                    => $priceRule->has_coupon,
                'coupon_code'                   => $priceRule->coupon_code,

                // see config/pulsar-market.php section Discount type on shopping cart
                // 1 - without discount
                // 2 - discount percentage subtotal
                // 3 - discount fixed amount subtotal
                // 4 - discount percentage total
                // 5 - discount fixed amount total
                'discount_type_id'              => $priceRule->discount_type_id,

                // fixed amount to discount over shopping cart
                'discount_fixed_amount'         => $priceRule->discount_fixed_amount,

                // percentage to discount over shopping cart
                'discount_percentage'           => $priceRule->discount_percentage,

                // limit amount to discount, if the discount is a percentage
                'maximum_discount_amount'       => $priceRule->maximum_discount_amount,

                // check if apply discount to shipping amount
                'apply_shipping_amount'         => $priceRule->apply_shipping_amount,

                // check if this discount has free shipping
                'free_shipping'                 => $priceRule->free_shipping
            ];

            $customerDiscountHistory[] = $dataAux;
        }

        return $customerDiscountHistory;
    }
}