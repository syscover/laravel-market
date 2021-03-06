<?php namespace Syscover\Market\GraphQL\Services;

use Illuminate\Support\Facades\Auth;
use Syscover\Core\GraphQL\Services\CoreGraphQLService;
use Syscover\Market\Models\CartPriceRule;
use Syscover\Market\Services\CartPriceRuleService;
use Syscover\Market\Services\CouponService;

class CartPriceRuleGraphQLService extends CoreGraphQLService
{
    public function __construct(CartPriceRule $model, CartPriceRuleService $service)
    {
        $this->model = $model;
        $this->service = $service;
    }

    public function checkCoupon($root, array $args)
    {
        return response()->json(
            CouponService::checkCoupon(
                $args['coupon'],
                $args['lang_id'],
                Auth::guard($args['guard'] ?? 'crm'),
                $args['instance'] ?? 'default'
            ));
    }
}
