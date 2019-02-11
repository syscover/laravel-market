<?php namespace Syscover\Market\GraphQL\Services;

use Syscover\Core\GraphQL\Services\CoreGraphQLService;
use Syscover\Market\Models\ProductClassTax;
use Syscover\Market\Services\ProductClassTaxService;

class ProductClassTaxGraphQLService extends CoreGraphQLService
{
    public function __construct(ProductClassTax $model, ProductClassTaxService $service)
    {
        $this->model = $model;
        $this->service = $service;
    }
}
