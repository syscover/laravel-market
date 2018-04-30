<?php namespace Syscover\Market\Services;

use Syscover\Market\Models\OrderStatus;

class OrderStatusService
{
    public static function create($object)
    {
        OrderStatusService::checkCreate($object);

        if(empty($object['id'])) $object['id'] = next_id(OrderStatus::class);

        $object['data_lang'] = OrderStatus::addDataLang($object['lang_id'], $object['id']);

        return OrderStatus::create(OrderStatusService::builder($object));
    }

    public static function update($object)
    {
        OrderStatusService::checkUpdate($object);
        OrderStatus::where('ix', $object['ix'])->update(OrderStatusService::builder($object));
        OrderStatus::where('id', $object['id'])->update(OrderStatusService::builder($object, ['active']));

        return OrderStatus::find($object['ix']);
    }

    private static function builder($object, $filterKeys = null)
    {
        $object = collect($object);
        if($filterKeys) $object = $object->only($filterKeys);

        return $object->only('id', 'lang_id', 'name', 'active', 'data_lang')->toArray();
    }

    private static function checkCreate($object)
    {
        if(empty($object['lang_id']))   throw new \Exception('You have to define a lang_id field to create a order status');
        if(empty($object['name']))      throw new \Exception('You have to define a name field to create a order status');
        if(! isset($object['active']))  throw new \Exception('You have to define a active field to create a order status');
    }

    private static function checkUpdate($object)
    {
        if(empty($object['ix'])) throw new \Exception('You have to define a ix field to update a order status');
        if(empty($object['id'])) throw new \Exception('You have to define a id field to update a order status');
    }
}