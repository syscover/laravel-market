<?php namespace Syscover\Market\GraphQL\Services;

use Syscover\Core\GraphQL\Services\CoreGraphQLService;
use Syscover\Market\Models\TaxRule;
use Syscover\Market\Services\TaxRuleService;
use Syscover\ShoppingCart\Facades\CartProvider;

class TaxRuleGraphQLService extends CoreGraphQLService
{
    protected $model = TaxRule::class;
    protected $service = TaxRuleService::class;

    public function resolveCheckCustomerTaxRules($root, array $args)
    {
        $taxRules = TaxRuleService::checkCustomerTaxRules(
                $args['customer_class_tax_id'] ?? null,
                $args['country_id'] ?? null,
                $args['territorial_area_1_id'] ?? null,
                $args['territorial_area_2_id'] ?? null,
                $args['territorial_area_3_id'] ?? null,
                $args['zip'] ?? null,
                $args['guard'] ?? 'crm'
            );

        return [
          'tax_rules' => $taxRules,
          'cart'      => CartProvider::instance($args['guard'] ?? null)->toArray()
        ];
    }
}