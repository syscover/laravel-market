<?php namespace Syscover\Market\GraphQL\Services;

use Syscover\Core\GraphQL\Services\CoreGraphQLService;
use Syscover\Market\Models\CartPriceRule;
use Syscover\Market\Services\CartPriceRuleService;

class CartPriceRuleGraphQLService extends CoreGraphQLService
{
    protected $model = CartPriceRule::class;
    protected $service = CartPriceRuleService::class;
}