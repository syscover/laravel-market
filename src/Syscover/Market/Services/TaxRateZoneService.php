<?php namespace Syscover\Market\Services;

use Syscover\Market\Models\TaxRateZone;

class TaxRateZoneService
{
    public static function create($object)
    {
        self::checkCreate($object);
        return TaxRateZone::create(self::builder($object));
    }

    public static function update($object)
    {
        self::checkUpdate($object);
        TaxRateZone::where('id', $object['id'])->update(self::builder($object));

        return TaxRateZone::find($object['id']);
    }

    private static function builder($object)
    {
        $object = collect($object);
        return $object->only(['name', 'country_id', 'territorial_area_1_id', 'territorial_area_2_id', 'territorial_area_3_id', 'zip', 'tax_rate'])->toArray();
    }

    private static function checkCreate($object)
    {
        if(empty($object['name'])) throw new \Exception('You have to define a name field to create a tax rate zone');
        if(empty($object['country_id'])) throw new \Exception('You have to define a country_id field to create a tax rate zone');
    }

    private static function checkUpdate($object)
    {
        if(empty($object['id'])) throw new \Exception('You have to define a id field to update a customer class tax rate zone');
    }
}