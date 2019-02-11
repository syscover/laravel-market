<?php namespace Syscover\Market\GraphQL\Services;

use Syscover\Core\GraphQL\Services\CoreGraphQLService;
use Syscover\Market\Models\CustomerClassTax;
use Syscover\Market\Services\CustomerClassTaxService;

class CustomerClassTaxGraphQLService extends CoreGraphQLService
{
    public function __construct(CustomerClassTax $model, CustomerClassTaxService $service)
    {
        $this->model = $model;
        $this->service = $service;
    }
}
