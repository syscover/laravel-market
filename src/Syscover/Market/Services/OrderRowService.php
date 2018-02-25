<?php namespace Syscover\Market\Services;

use Carbon\Carbon;
use Syscover\Market\Models\OrderRow;

class OrderRowService
{
    /**
     * @param $item
     * @return $this|\Illuminate\Database\Eloquent\Model
     */
    public static function create($item)
    {
        return OrderRow::create($item);
    }

    /**
     * @param $items
     * @return bool
     */
    public static function insert($items)
    {
        foreach ($items as &$item)
        {
            // prevent not null values
            if(array_key_exists('has_gift', $item) && $item['has_gift'] === null) unset($item['has_gift']);

            if($item['data'] !== null) $item['data'] = json_encode($item['data']);
            $item['tax_rules'] = json_encode($item['tax_rules']);
        }

        return OrderRow::insert($items);
    }

    /**
     * @param   array     $object     contain properties of section
     * @param   int       $id         id of category
     * @param   string    $lang       lang of category
     * @return  \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|null|static|static[]
     */
    public static function update($id, $lang, $object)
    {

    }

    private static function builder(int $orderId, string $langId, $object)
    {
        $object = collect($object);
        $data = [];

        if($orderId)                                 $data['order_id'] = $orderId;
        if($langId)                                  $data['lang_id'] = $langId;
        if($object->has('product_id'))          $data['product_id'] = $object->get('product_id');
        if($object->has('name'))                $data['name'] = $object->get('name');
        // if($object->has('description'))                  $data['description'] = $object->get('description');
        // if($object->has('data'))                         $data['data'] = $object->get('data');
        if($object->has('price'))                       $data['price'] = $object->get('price');
        if($object->has('quantity'))                    $data['quantity'] = $object->get('quantity');
        if($object->has('subtotal'))                    $data['subtotal'] = $object->get('subtotal');
        if($object->has('total_without_discounts'))     $data['total_without_discounts'] = $object->get('total_without_discounts');

        if($object->has('total'))              $data['total'] = $object->get('total');
    }

    public static function orderRowBuilder($lang, $orderId, $items)
    {
        $orderRows = [];
        foreach ($items as $item)
        {
            $orderRows[] = [
                'lang_id'                                   => $lang,
                'order_id'                                  => $orderId,
                'product_id'                                => $item->id,

                //****************
                //* Product
                //****************
                'name'                                      => $item->name,
                'description'                               => $item->options->product->description,
                'data'                                      => ['product' => $item->options->product],

                //****************
                //* amounts
                //****************
                'price'                                     => $item->price,                                            // unit price without tax
                'quantity'                                  => $item->quantity,                                         // number of units
                'subtotal'                                  => $item->subtotal,                                         // subtotal without tax
                'total_without_discounts'                   => $item->totalWithoutDiscounts,                            // total from row without discounts

                //****************
                //* discounts
                //****************
                'discount_subtotal_percentage'              => $item->discountSubtotalPercentage,
                'discount_total_percentage'                 => $item->discountTotalPercentage,
                'discount_subtotal_percentage_amount'       => $item->discountSubtotalPercentageAmount,
                'discount_total_percentage_amount'          => $item->discountTotalPercentageAmount,
                'discount_subtotal_fixed_amount'            => $item->discountSubtotalFixedAmount,
                'discount_total_fixed_amount'               => $item->discountTotalFixedAmount,
                'discount_amount'                           => $item->discountAmount,

                //***************************
                //* subtotal with discounts
                //***************************
                'subtotal_with_discounts'                   => $item->subtotalWithDiscounts,                            // subtotal without tax and with discounts

                //****************
                //* taxes
                //****************
                'tax_rules'                                 => $item->taxRules->values(),
                'tax_amount'                                => $item->taxAmount,

                //****************
                //* total
                //****************
                'total'                                     => $item->total,                                            // total with tax and discounts

                //****************
                //* gift
                //****************
                // to set gift, create array in options Shopping Cart, with gift key, and keys: from, to, message
                'has_gift'                              => $item->options->gift != null? true : false,
                'gift_from'                             => isset($item->options->gift['from'])? $item->options->gift['from'] : null,
                'gift_to'                               => isset($item->options->gift['to'])? $item->options->gift['to'] : null,
                'gift_message'                          => isset($item->options->gift['message'])? $item->options->gift['message'] : null,
                'gift_comments'                         => isset($item->options->gift['comments'])? $item->options->gift['comments'] : null
            ];
        }

        return $orderRows;
    }
}