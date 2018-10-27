<?php namespace Syscover\Market\GraphQL\Services;

use Illuminate\Support\Facades\Auth;
use Syscover\Core\GraphQL\Services\CoreGraphQLService;
use Syscover\Market\Models\CartPriceRule;
use Syscover\Market\Services\CartPriceRuleService;
use Syscover\Market\Services\CouponService;

class CartPriceRuleGraphQLService extends CoreGraphQLService
{
    protected $modelClassName = CartPriceRule::class;
    protected $serviceClassName = CartPriceRuleService::class;

    public function checkCoupon($root, array $args)
    {
        return response()->json(
            CouponService::checkCoupon(
                $args['coupon'],
                user_lang(),
                Auth::guard($args['guard'] ?? 'crm'),
                $args['instance'] ?? 'default'
            ));
    }
}