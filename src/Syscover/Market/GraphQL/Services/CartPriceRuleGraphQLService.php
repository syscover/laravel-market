<?php namespace Syscover\Market\GraphQL\Services;

use Illuminate\Support\Facades\Auth;
use Syscover\Core\GraphQL\Services\CoreGraphQLService;
use Syscover\Market\Models\CartPriceRule;
use Syscover\Market\Services\CartPriceRuleService;
use Syscover\Market\Services\CouponService;

class CartPriceRuleGraphQLService extends CoreGraphQLService
{
    protected $model = CartPriceRule::class;
    protected $service = CartPriceRuleService::class;

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
