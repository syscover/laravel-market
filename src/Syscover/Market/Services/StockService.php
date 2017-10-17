<?php namespace Syscover\Market\Services;

use Syscover\Market\Models\Stock;

class StockService
{
    /**
     * @param   array     $object     contain properties of section
     * @return  \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|null|static|static[]
     */
    public static function set($object)
    {
        // pass object to collection
        $object = collect($object);

        if(Stock::where('warehouse_id', $object->get('warehouse_id'))
            ->where('product_id', $object->get('product_id'))
            ->count() > 0
        )
        {
            Stock::where('warehouse_id', $object->get('warehouse_id'))
                ->where('product_id', $object->get('product_id'))
                ->update([
                    'stock'             => $object->get('stock'),
                    'minimum_stock'     => $object->get('minimum_stock')
                ]);

            return Stock::where('warehouse_id', $object->get('warehouse_id'))
                ->where('product_id', $object->get('product_id'))
                ->first();
        }
        else
        {
            return Stock::create($object->toArray());
        }
    }
}