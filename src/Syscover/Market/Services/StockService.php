<?php namespace Syscover\Market\Services;

use Syscover\Market\Models\Stock;

class StockService
{
    public static function set($object)
    {
        if(Stock::where('warehouse_id', $object['warehouse_id'])
            ->where('product_id', $object['product_id'])
            ->count() > 0
        )
        {
            return self::update($object);
        }
        else
        {
            return self::create($object);
        }
    }

    public static function create($object)
    {
        self::checkCreate($object);
        return Stock::create(self::builder($object));
    }

    public static function update($object)
    {
        self::checkUpdate($object);
        Stock::where('warehouse_id', $object['warehouse_id'])
            ->where('product_id', $object['product_id'])
            ->update(self::builder($object));

        return Stock::where('warehouse_id', $object['warehouse_id'])
            ->where('product_id', $object['product_id'])->first();
    }

    private static function builder($object, $filterKeys = null)
    {
        $object = collect($object);
        if($filterKeys) return $object->only($filterKeys)->toArray();

        return  $object->only(['warehouse_id', 'product_id', 'stock', 'minimum_stock'])->toArray();
    }

    private static function checkCreate($object)
    {
        if(empty($object['warehouse_id']))  throw new \Exception('You have to define a warehouse_id field to create a stock');
        if(empty($object['product_id']))    throw new \Exception('You have to define a product_id field to create a stock');
    }

    private static function checkUpdate($object)
    {
        if(empty($object['warehouse_id'])) throw new \Exception('You have to define a warehouse_id field to update a stock');
        if(empty($object['product_id'])) throw new \Exception('You have to define a product_id field to update a stock');
    }
}