<?php namespace Syscover\Market\Services;

use Syscover\Market\Models\OrderStatus;

class OrderStatusService
{
    public static function create($object)
    {
        OrderStatusService::check($object);

        if(empty($object['id'])) $object['id'] = next_id(OrderStatus::class);

        $object['data_lang'] = OrderStatus::addDataLang($object['lang_id'], $object['id']);

        return OrderStatus::create(OrderStatusService::builder($object));
    }

    public static function update($object)
    {
        OrderStatusService::check($object);
        OrderStatus::where('ix', $object['ix'])->update(OrderStatusService::builder($object));
        OrderStatus::where('id', $object['id'])->update(OrderStatusService::builder($object, ['active']));

        return OrderStatus::find($object['ix']);
    }

    private static function builder($object, $filterKeys = null)
    {
        $object = collect($object);
        if($filterKeys) $object = $object->only($filterKeys);

        $data = [];

        if($object->has('id'))          $data['id'] = $object->get('id');
        if($object->has('lang_id'))     $data['lang_id'] = $object->get('lang_id');
        if($object->has('name'))        $data['name'] = $object->get('name');
        if($object->has('active'))      $data['active'] = $object->get('active');
        if($object->has('data_lang'))   $data['data_lang'] = $object->get('data_lang');

        return $data;
    }

    private static function check($object)
    {
        if(empty($object['lang_id']))   throw new \Exception('You have to define a lang_id field to create a order status');
        if(empty($object['name']))      throw new \Exception('You have to define a name field to create a order status');
        if(! isset($object['active']))  throw new \Exception('You have to define a active field to create a order status');
    }
}