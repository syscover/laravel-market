<?php namespace Syscover\Market\Services;

use Syscover\Market\Models\Order;
use Syscover\Market\Models\OrderRow;
use Syscover\ShoppingCart\Facades\CartProvider;

class OrderRowService
{
    public static function create(array $object)
    {
        self::checkCreate($object);
        return OrderRow::create(self::builder($object));
    }

    public static function update(array $object)
    {
        self::checkUpdate($object);

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
            self::checkCreate($object);

            if(! empty($object['data'])) $object['data'] = json_encode($object['data']);
            if(! empty($object['tax_rules'])) $object['tax_rules'] = json_encode($object['tax_rules']);

            $rows[] = self::builder($object);
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

                // costs
                'cost',
                'total_cost',

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
        if(empty($object['lang_id']))                                       throw new \Exception('You have to define a lang_id field to create a order row');
        if(empty($object['order_id']))                                      throw new \Exception('You have to define a order_id field to create a order row');
        if(! is_numeric($object['price']))                                  throw new \Exception('You have to define a price field to create a order row');
        if(! is_numeric($object['quantity']))                               throw new \Exception('You have to define a quantity field to create a order row');
        if(! is_numeric($object['subtotal']))                               throw new \Exception('You have to define a subtotal field to create a order row');
        if(! is_numeric($object['total_without_discounts']))                throw new \Exception('You have to define a total_without_discounts field to create a order row');
        if(! is_numeric($object['discount_subtotal_percentage']))           throw new \Exception('You have to define a discount_subtotal_percentage field to create a order row');
        if(! is_numeric($object['discount_total_percentage']))              throw new \Exception('You have to define a discount_total_percentage field to create a order row');
        if(! is_numeric($object['discount_subtotal_percentage_amount']))    throw new \Exception('You have to define a discount_subtotal_percentage_amount field to create a order row');
        if(! is_numeric($object['discount_total_percentage_amount']))       throw new \Exception('You have to define a discount_total_percentage_amount field to create a order row');
        if(! is_numeric($object['discount_subtotal_fixed_amount']))         throw new \Exception('You have to define a discount_subtotal_fixed_amount field to create a order row');
        if(! is_numeric($object['discount_total_fixed_amount']))            throw new \Exception('You have to define a discount_total_fixed_amount field to create a order row');
        if(! is_numeric($object['discount_amount']))                        throw new \Exception('You have to define a discount_amount field to create a order row');
        if(! is_numeric($object['subtotal_with_discounts']))                throw new \Exception('You have to define a subtotal_with_discounts field to create a order row');
        if(empty($object['tax_rules']))                                     throw new \Exception('You have to define a tax_rules field to create a order row');
        if(! is_numeric($object['tax_amount']))                             throw new \Exception('You have to define a tax_amount field to create a order row');
        if(! is_numeric($object['total']))                                  throw new \Exception('You have to define a total field to create a order row');
    }

    private static function checkUpdate($object)
    {
        if(empty($object['id'])) throw new \Exception('You have to define a id field to update a order row');
    }

    public static function getDataOrderRow(Order $order, string $instance = null, $resolvers = [])
    {
        $items = [];

        // get cart instance
        foreach (CartProvider::instance($instance)->getCartItems() as $item)
        {
            $itemAux = [
                'order_id'                              => $order->id,
                'lang_id'                               => user_lang(),

                // product
                'product_id'                            => $item->id,
                'name'                                  => $resolvers['name']($item) ?? $item->name,
                'description'                           => $item->options->product->description,
                'data'                                  => ['product' => $item->options->product],

                // costs
                'cost'                                  => $item->cost,
                'total_cost'                            => $item->totalCost,

                // amounts
                'price'                                 => $item->price,                    // unit price without tax
                'quantity'                              => $item->quantity,                 // number of units
                'subtotal'                              => $item->subtotal,                 // subtotal without tax
                'total_without_discounts'               => $item->totalWithoutDiscounts,    // total from row without discounts

                // discounts
                'discount_subtotal_percentage'          => $item->discountSubtotalPercentage,
                'discount_total_percentage'             => $item->discountTotalPercentage,
                'discount_subtotal_percentage_amount'   => $item->discountSubtotalPercentageAmount,
                'discount_total_percentage_amount'      => $item->discountTotalPercentageAmount,
                'discount_subtotal_fixed_amount'        => $item->discountSubtotalFixedAmount,
                'discount_total_fixed_amount'           => $item->discountTotalFixedAmount,
                'discount_amount'                       => $item->discountAmount,

                // subtotal with discounts
                'subtotal_with_discounts'               => $item->subtotalWithDiscounts,      // subtotal without tax and with discounts

                // taxes
                'tax_rules'                             => $item->taxRules->values(),
                'tax_amount'                            => $item->taxAmount,

                // total
                'total'                                 => $item->total,        // total with tax and discounts

                // gift fields
                // to set gift, create array in options with gift key, and keys: from, to, message
                'has_gift'                              => $item->options->gift != null ? true : false,
                'gift_from'                             => isset($item->options->gift['from']) ? $item->options->gift['from'] : null,
                'gift_to'                               => isset($item->options->gift['to']) ? $item->options->gift['to'] : null,
                'gift_message'                          => isset($item->options->gift['message']) ? $item->options->gift['message'] : null,
                'gift_comments'                         => isset($item->options->gift['comments']) ? $item->options->gift['comments'] : null
            ];

            // add item to array
            $items[] = $itemAux;
        }

        return $items;
    }
}
