<?php namespace Syscover\Market\Services;

use Syscover\Market\Models\Stock;

class StockService
{
    /**
     * Function to create a stock
     * @param   array                           $object
     * @return  \Syscover\Market\Models\Stock
     * @throws  \Exception
     */
    public static function create($object)
    {
        return Stock::create($object);
    }

    /**
     * @param   array     $object     contain properties of section
     * @param   int       $id         id of stock
     * @return  \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|null|static|static[]
     */
    public static function update($object, $id)
    {
        // pass object to collection
        $object = collect($object);

        Stock::where('id', $id)
            ->update([
                'stock'             => $object->get('stock'),
                'minimum_stock'     => $object->get('minimum_stock')
            ]);

        return Stock::find($object->get('id'));
    }
}