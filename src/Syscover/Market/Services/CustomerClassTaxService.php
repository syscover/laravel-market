<?php namespace Syscover\Market\Services;

use Syscover\Market\Models\CustomerClassTax;

class CustomerClassTaxService
{
    public static function create($object)
    {
        CustomerClassTaxService::check($object);
        return CustomerClassTax::create(CustomerClassTaxService::builder($object));
    }

    public static function update($object)
    {
        CustomerClassTaxService::check($object);
        CustomerClassTax::where('id', $object['id'])->update(CustomerClassTaxService::builder($object));

        return CustomerClassTax::find($object['id']);
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
        if(empty($object['name'])) throw new \Exception('You have to define a name field to create a customer class tax');
    }
}