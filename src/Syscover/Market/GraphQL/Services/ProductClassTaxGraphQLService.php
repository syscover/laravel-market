<?php namespace Syscover\Market\GraphQL\Services;

use Syscover\Core\GraphQL\Services\CoreGraphQLService;
use Syscover\Market\Models\ProductClassTax;
use Syscover\Market\Services\ProductClassTaxService;

class ProductClassTaxGraphQLService extends CoreGraphQLService
{
    protected $modelClassName = ProductClassTax::class;
    protected $serviceClassName = ProductClassTaxService::class;
}