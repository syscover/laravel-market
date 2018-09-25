<?php namespace Syscover\Market\GraphQL\Services;

use Syscover\Core\GraphQL\Services\CoreGraphQLService;
use Syscover\Market\Models\TaxRule;
use Syscover\Market\Services\TaxRuleService;

class TaxRuleGraphQLService extends CoreGraphQLService
{
    protected $model = TaxRule::class;
    protected $service = TaxRuleService::class;

    public function resolveCheckCustomerTaxRules($root, array $args)
    {
        TaxRuleService::checkCustomerTaxRules(
            $root['customer_class_tax_id'] ?? null,
            $root['country_id'] ?? null,
            $root['territorial_area_1_id'] ?? null,
            $root['territorial_area_2_id'] ?? null,
            $root['territorial_area_3_id'] ?? null,
            $root['zip'] ?? null,
            $root['guard'] ?? 'crm'
        );
    }
}