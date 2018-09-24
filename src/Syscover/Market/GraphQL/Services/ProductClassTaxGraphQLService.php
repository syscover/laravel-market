<?php namespace Syscover\Market\GraphQL\Services;

use Syscover\Core\GraphQL\Services\CoreGraphQLService;
use Syscover\Market\Models\ProductClassTax;
use Syscover\Market\Services\ProductClassTaxService;

class ProductClassTaxGraphQLService extends CoreGraphQLService
{
    protected $model = ProductClassTax::class;
    protected $service = ProductClassTaxService::class;
}