<?php namespace Syscover\Market\Services;

use Syscover\Market\Models\ProductClassTax;

class ProductClassTaxService
{
    public static function create($object)
    {
        ProductClassTaxService::checkCreate($object);
        return ProductClassTax::create(ProductClassTaxService::builder($object));
    }

    public static function update($object)
    {
        ProductClassTaxService::checkUpdate($object);
        ProductClassTax::where('id', $object['id'])->update(ProductClassTaxService::builder($object));

        return ProductClassTax::find($object['id']);
    }

    private static function builder($object)
    {
        $object = collect($object);
        return $object->only('name')->toArray();
    }

    private static function checkCreate($object)
    {
        if(empty($object['name'])) throw new \Exception('You have to define a name field to create a product class tax');
    }

    private static function checkUpdate($object)
    {
        if(empty($object['id'])) throw new \Exception('You have to define a id field to update a product class tax');
    }
}