<?php namespace Syscover\Market\GraphQL\Services;

use Syscover\Core\GraphQL\Services\CoreGraphQLService;
use Syscover\Market\Models\CustomerClassTax;
use Syscover\Market\Services\CustomerClassTaxService;

class CustomerClassTaxGraphQLService extends CoreGraphQLService
{
    protected $modelClassName = CustomerClassTax::class;
    protected $serviceClassName = CustomerClassTaxService::class;
}