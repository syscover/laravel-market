<?php namespace Syscover\Market\GraphQL\Services;

use Syscover\Core\GraphQL\Services\CoreGraphQLService;
use Syscover\Core\Services\SQLService;
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
          'cart'      => CartProvider::instance($args['instance'] ?? null)->toArray()
        ];
    }

    public function paginate($root, array $args)
    {
        return (Object) [
            'query' => TaxRule::calculateFoundRows()->paginationBuilder()
        ];
    }

    public function delete($root, array $args)
    {
        $object = SQLService::destroyRecord($args['id'], $this->model);

        $object->tax_rate_zones()->detach();
        $object->customer_class_taxes()->detach();
        $object->product_class_taxes()->detach();

        return $object;
    }
}