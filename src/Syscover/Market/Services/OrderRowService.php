<?php namespace Syscover\Market\Services;

use Carbon\Carbon;
use Syscover\Market\Models\OrderRow;

class OrderRowService
{
    /**
     * Function to create a order rows
     * @param $id               int     ID of order where row will be related
     * @param $shoppingCart     \Syscover\ShoppingCart\Cart
     * @return \Syscover\Market\Models\Order
     */
    public static function create($id, $shoppingCart)
    {
        // create rows from shopping cart
        $items = [];
        foreach ($shoppingCart->getCartItems() as $item) {
            $itemAux = [
                'lang_id'                                   => user_lang(),
                'order_id'                                  => $id,
                'product_id'                                => $item->id,

                //****************
                //* Product
                //****************
                'name'                                      => $item->name,
                'description'                               => $item->options->product->description,
                'data'                                      => json_encode(['product' => $item->options->product]),

                //****************
                //* amounts
                //****************
                'price'                                     => $item->price,                    // unit price without tax
                'quantity'                                  => $item->quantity,                 // number of units
                'subtotal'                                  => $item->subtotal,                 // subtotal without tax
                'total_without_discounts'                   => $item->totalWithoutDiscounts,    // total from row without discounts

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
                'subtotal_with_discounts'                   => $item->subtotalWithDiscounts,      // subtotal without tax and with discounts

                //****************
                //* taxes
                //****************
                'tax_rules'                                 => json_encode($item->taxRules->values()),
                'tax_amount'                                => $item->taxAmount,

                //****************
                //* total
                //****************
                'total'                                     => $item->total,        // total with tax and discounts

                //****************
                //* gift
                //****************
                'has_gift'                                  => $item->getGift()->has('has_gift'),
                'gift_from'                                 => $item->getGift()->get('from'),
                'gift_to'                                   => $item->getGift()->get('to'),
                'gift_message'                              => $item->getGift()->get('message'),
                'gift_comments'                             => $item->getGift()->get('comments'),
            ];

            // add item to array
            $items[] = $itemAux;
        }

        return OrderRow::insert($items);
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