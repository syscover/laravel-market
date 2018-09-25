<?php namespace Syscover\Market\GraphQL\Services;

use Syscover\Core\GraphQL\Services\CoreGraphQLService;
use Syscover\Market\Models\TaxRule;
use Syscover\Market\Services\TaxRuleService;

class TaxRuleGraphQLService extends CoreGraphQLService
{
    protected $model = TaxRule::class;
    protected $service = TaxRuleService::class;

    public function resolveChangeTaxRules($root, array $args)
    {

    }
}