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

        CustomerGroupCustomerClassTax::where('customer_group_id', $object['customer_group_id'])
            ->where('customer_class_tax_id', $object['customer_class_tax_id'])
            ->update(self::builder($object['object']));

        return CustomerGroupCustomerClassTax::where('customer_group_id', $object['object']['customer_group_id'])
            ->where('customer_class_tax_id', $object['object']['customer_class_tax_id'])
            ->first();
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
        if(empty($object['object'])) throw new \Exception('You have to define a object field that contain the customer group customer class tax that will be updated');
    }
}