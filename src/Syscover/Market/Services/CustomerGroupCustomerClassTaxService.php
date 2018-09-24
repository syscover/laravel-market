<?php namespace Syscover\Market\Services;

use Syscover\Market\Models\CustomerGroupCustomerClassTax;

class CustomerGroupCustomerClassTaxService
{
    public static function create($object)
    {
        self::checkCreate($object);
        return CustomerGroupCustomerClassTax::create(self::builder($object));
    }

    public static function update($object)
    {
        self::checkUpdate($object);
        CustomerGroupCustomerClassTax::where('id', $object['id'])->update(self::builder($object));

        return CustomerGroupCustomerClassTax::find($object['id']);
    }

    private static function builder($object)
    {
        $object = collect($object);
        return $object->only(['customer_group_id', 'customer_class_tax_id'])->toArray();
    }

    private static function checkCreate($object)
    {
        if(empty($object['customer_group_id'])) throw new \Exception('You have to define a customer_group_id field to create a customer group customer class tax');
        if(empty($object['customer_class_tax_id'])) throw new \Exception('You have to define a customer_class_tax_id field to create a customer group customer class tax');
    }

    private static function checkUpdate($object)
    {
        if(empty($object['customer_group_id'])) throw new \Exception('You have to define a customer_group_id field to update a customer group customer class tax');
        if(empty($object['customer_class_tax_id'])) throw new \Exception('You have to define a customer_class_tax_id field to update a customer group customer class tax');
    }
}