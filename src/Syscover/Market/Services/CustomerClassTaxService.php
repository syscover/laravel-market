<?php namespace Syscover\Market\Services;

use Syscover\Market\Models\CustomerClassTax;

class CustomerClassTaxService
{
    public static function create($object)
    {
        CustomerClassTaxService::checkCreate($object);
        return CustomerClassTax::create(CustomerClassTaxService::builder($object));
    }

    public static function update($object)
    {
        CustomerClassTaxService::checkUpdate($object);
        CustomerClassTax::where('id', $object['id'])->update(CustomerClassTaxService::builder($object));

        return CustomerClassTax::find($object['id']);
    }

    private static function builder($object)
    {
        $object = collect($object);
        return $object->only(['name'])->toArray();
    }

    private static function checkCreate($object)
    {
        if(empty($object['name'])) throw new \Exception('You have to define a name field to create a customer class tax');
    }

    private static function checkUpdate($object)
    {
        if(empty($object['id'])) throw new \Exception('You have to define a id field to update a customer class tax');
    }
}