<?php namespace Syscover\Market\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Syscover\Core\Controllers\CoreController;
use Syscover\Market\Models\CartPriceRule;
use Syscover\Market\Services\CartPriceRuleService;
use Syscover\Market\Services\CouponService;

class CartPriceRuleController extends CoreController
{
    protected $model = CartPriceRule::class;

    public function store(Request $request)
    {
        $response['status'] = "success";
        $response['data']   = CartPriceRuleService::create($request->all());

        return response()->json($response);
    }

    public function update(Request $request)
    {
        $response['status'] = "success";
        $response['data']   = CartPriceRuleService::update($request->all());

        return response()->json($response);
    }

    public function checkCouponCode($guard = 'crm', $instance = 'default')
    {
        return response()->json(CouponService::checkCouponCode(request('coupon_code'), user_lang(), Auth::guard($guard), $instance));
    }
}
