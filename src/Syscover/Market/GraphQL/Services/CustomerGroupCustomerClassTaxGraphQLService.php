<?php namespace Syscover\Market\GraphQL\Services;

use Syscover\Core\GraphQL\Services\CoreGraphQLService;
use Syscover\Market\Models\CustomerGroupCustomerClassTax;
use Syscover\Market\Services\CustomerGroupCustomerClassTaxService;

class CustomerGroupCustomerClassTaxGraphQLService extends CoreGraphQLService
{
    protected $modelClassName = CustomerGroupCustomerClassTax::class;
    protected $serviceClassName = CustomerGroupCustomerClassTaxService::class;

    public function update($root, array $args)
    {
        return $this->service->update($args);
    }

    public function delete($root, array $args)
    {
        // Custom delete a single record
        $object = $this->model->builder()
            ->where('customer_group_id', $args['customer_group_id'])
            ->where('customer_class_tax_id', $args['customer_class_tax_id'])
            ->first();

        $object->delete();

        return $object;
    }
}