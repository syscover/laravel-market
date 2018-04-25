<?php namespace Syscover\Market\Services;

use Syscover\Market\Models\ProductClassTax;

class ProductClassTaxService
{
    public static function create($object)
    {
        ProductClassTaxService::check($object);
        return ProductClassTax::create(ProductClassTaxService::builder($object));
    }

    public static function update($object)
    {
        ProductClassTaxService::check($object);
        ProductClassTax::where('id', $object['id'])->update(ProductClassTaxService::builder($object));

        return ProductClassTax::find($object['id']);
    }

    private static function builder($object)
    {
        $object = collect($object);
        $data = [];

        if($object->has('id'))      $data['id'] = $object->get('id');
        if($object->has('name'))    $data['name'] = $object->get('name');

        return $data;
    }

    private static function check($object)
    {
        if(empty($object['name'])) throw new \Exception('You have to define a name field to create a product class tax');
    }
}