<?php namespace Syscover\Market\Services;

use Syscover\Market\Models\OrderRow;
use Syscover\ShoppingCart\Facades\CartProvider;

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

    public static function getDataOrderRow(int $orderId, string $instance = null)
    {
        // get cart instance
        $items = CartProvider::instance($instance)->getCartItems();

        $data = [];
        foreach ($items as $item)
        {
            $dataAux = [];
            $dataAux['order_id']                                = $orderId;
            $dataAux['lang_id']                                 = user_lang();

            // product
            $dataAux['product_id']                              = $item->id;
            $dataAux['name']                                    = $item->name;
            $dataAux['description']                             = $item->options->product->description;
            $dataAux['data']                                    = ['product' => $item->options->product];

            // amounts
            $dataAux['price']                                   = $item->price;
            $dataAux['quantity']                                = $item->quantity;
            $dataAux['subtotal']                                = $item->subtotal;
            $dataAux['total_without_discounts']                 = $item->totalWithoutDiscounts;

            // discounts
            $dataAux['discount_subtotal_percentage']            = $item->discountSubtotalPercentage;
            $dataAux['discount_total_percentage']               = $item->discountTotalPercentage;
            $dataAux['discount_subtotal_percentage_amount']     = $item->discountSubtotalPercentageAmount;
            $dataAux['discount_total_percentage_amount']        = $item->discountTotalPercentageAmount;
            $dataAux['discount_subtotal_fixed_amount']          = $item->discountSubtotalFixedAmount;
            $dataAux['discount_total_fixed_amount']             = $item->discountTotalFixedAmount;
            $dataAux['discount_amount']                         = $item->discountAmount;

            // subtotal with discounts
            $dataAux['subtotal_with_discounts']                 = $item->subtotalWithDiscounts;

            // taxes
            $dataAux['tax_rules']                               = $item->taxRules->values();
            $dataAux['tax_amount']                              = $item->taxAmount;

            // total
            $dataAux['total']                                   = $item->total;

            // gift
            $dataAux['has_gift']                                = $item->options->gift != null? true : false;
            $dataAux['gift_from']                               = isset($item->options->gift['from'])? $item->options->gift['from'] : null;
            $dataAux['gift_to']                                 = isset($item->options->gift['to'])? $item->options->gift['to'] : null;
            $dataAux['gift_message']                            = isset($item->options->gift['message'])? $item->options->gift['message'] : null;
            $dataAux['gift_comments']                           = isset($item->options->gift['comments'])? $item->options->gift['comments'] : null;

            $data[] = $dataAux;
        }

        return $data;
    }
}