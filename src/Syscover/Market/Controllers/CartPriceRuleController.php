<?php namespace Syscover\Market\Controllers;

use Illuminate\Support\Facades\Auth;
use Syscover\Core\Controllers\CoreController;
use Syscover\Market\Models\CartPriceRule;
use Syscover\Market\Services\CartPriceRuleService;
use Syscover\Market\Services\CouponService;

class CartPriceRuleController extends CoreController
{
    public function __construct(CartPriceRule $model, CartPriceRuleService $service)
    {
        $this->model = $model;
        $this->service = $service;
    }

    public function checkCoupon($guard = 'crm', $instance = 'default')
    {
        return response()->json(CouponService::checkCoupon(request('coupon_code'), user_lang(), Auth::guard($guard), $instance));
    }
}
