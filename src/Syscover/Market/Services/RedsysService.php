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
    public static function createPayment($object)
    {
        return Stock::create($object);
    }
}