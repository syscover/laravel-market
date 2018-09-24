<?php namespace Syscover\Market\GraphQL\Services;

use Syscover\Core\GraphQL\Services\CoreGraphQLService;
use Syscover\Market\Models\CustomerGroupCustomerClassTax;
use Syscover\Market\Services\CustomerGroupCustomerClassTaxService;

class CustomerGroupCustomerClassTaxGraphQLService extends CoreGraphQLService
{
    protected $model = CustomerGroupCustomerClassTax::class;
    protected $service = CustomerGroupCustomerClassTaxService::class;
}