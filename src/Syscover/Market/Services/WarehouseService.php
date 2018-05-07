<?php namespace Syscover\Market\Services;

use Syscover\Market\Models\Warehouse;

class WarehouseService
{
    public static function create($object)
    {
        WarehouseService::checkCreate($object);
        return Warehouse::create($object);
    }

    public static function update($object)
    {
        WarehouseService::checkUpdate($object);
        Warehouse::where('id', $object['id'])->update(WarehouseService::builder($object));
        return Warehouse::find($object['id']);
    }

    private static function builder($object)
    {
        $object = collect($object);
        return $object->only('name', 'country_id', 'territorial_area_1_id', 'territorial_area_2_id', 'territorial_area_3_id', 'zip', 'locality', 'address', 'latitude', 'longitude', 'active')->toArray();
    }

    private static function checkCreate($object)
    {
        if(empty($object['name'])) throw new \Exception('You have to define a name field to create a warehouse');
    }

    private static function checkUpdate($object)
    {
        if(empty($object['id'])) throw new \Exception('You have to define a id field to update a warehouse');
    }
}