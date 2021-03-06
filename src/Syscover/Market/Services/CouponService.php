<?php namespace Syscover\Market\Services;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Syscover\Market\Models\CartPriceRule;
use Syscover\Market\Models\CustomerDiscountHistory;
use Syscover\ShoppingCart\Exceptions\ShoppingCartNotCombinablePriceRuleException;
use Syscover\ShoppingCart\Facades\CartProvider;
use Syscover\ShoppingCart\PriceRule;

class CouponService
{
    /**
     * @param   string                          $coupon
     * @param   int                             $langId         check coupon code from this language
     * @param   string                          $instance       cart instance
     * @param   \Illuminate\Auth\SessionGuard   $sessionGuard   request session guard to check if user is authenticated, for cases necessary
     * @return array
     */
    public static function checkCoupon($coupon, $lang, $sessionGuard = null, $instance = 'default')
    {
        // set app locale to get translations
        App::setLocale($lang);

        $shoppingCart   = CartProvider::instance($instance);
        $cartPriceRule  = CartPriceRule::builder($lang)
            ->where('coupon_code', 'like', $coupon)
            ->first();
        $errors         = [];

        // check if coupon exist
        if($cartPriceRule == null)
        {
            $errors[] = [
                'status'    => 'error',
                'code'      => 1,
                'message'   => 'This coupon code, does not exist',
                'trans'     => __('market::pulsar.error_coupon_code_01'),
                'data'      => [
                    'coupon' => $coupon
                ]
            ];
        }

        //check if this coupon has exceeded limit of use
        if($cartPriceRule != null && $cartPriceRule->coupon_uses != null && $cartPriceRule->total_uses >= $cartPriceRule->coupon_uses)
        {
            $errors[] = [
                'status'    => 'error',
                'code'      => 2,
                'message'   => 'This coupon has exceeded the limit of uses',
                'trans'     => trans('market::pulsar.error_coupon_code_02'),
                'data'      => [
                    'coupon'        => $coupon,
                    'couponUses'    => $cartPriceRule->coupon_uses,
                    'totalUses'     => $cartPriceRule->total_uses,
                ]
            ];
        }

        if($cartPriceRule != null && $sessionGuard != null && $cartPriceRule->customer_uses != null && $cartPriceRule->customer_uses > 0)
        {
            // need to be loged to use this coupon
            if($sessionGuard->guest())
            {
                $errors[] = [
                    'status'    => 'error',
                    'code'      => 3,
                    'message'   => 'User has to be authenticated to use this coupon code',
                    'trans'     => trans('market::pulsar.error_coupon_code_03'),
                    'data'      => [
                        'coupon' => $coupon
                    ]
                ];
            }
            elseif(CustomerDiscountHistory::builder()->where('market_customer_discount_history.customer_id', $sessionGuard->user()->id)->where('market_customer_discount_history.coupon_code', $coupon)->where('market_customer_discount_history.applied', true)->count() >= $cartPriceRule->customer_uses)
            {
                $errors[] = [
                    'status'    => 'error',
                    'code'      => 4,
                    'message'   => 'User has exceeded the limit of uses',
                    'trans'     => trans('market::pulsar.error_coupon_code_04'),
                    'data'      => [
                        'coupon' => $coupon
                    ]
                ];
            }
        }

        if($cartPriceRule != null && $cartPriceRule->enable_from != null && date('U') < strtotime($cartPriceRule->enable_from))
        {
            $errors[] = [
                'status'    => 'error',
                'code'      => 5,
                'message'   => 'This coupon is not yet in its period of validity',
                'trans'     => trans('market::pulsar.error_coupon_code_05'),
                'data'      => [
                    'coupon' => $coupon
                ]
            ];
        }



        if($cartPriceRule != null && $cartPriceRule->enable_to != null && date('U') > strtotime($cartPriceRule->enable_to))
        {
            $errors[] = [
                'status'    => 'error',
                'code'      => 6,
                'message'   => 'This coupon is expired',
                'trans'     => trans('market::pulsar.error_coupon_code_06'),
                'data'      => [
                    'coupon' => $coupon
                ]
            ];
        }

        if($cartPriceRule != null && $cartPriceRule->active == false)
        {
            $errors[] = [
                'status'    => 'error',
                'code'      => 7,
                'message'   => 'This coupon is inactive',
                'trans'     => trans('market::pulsar.error_coupon_code_07'),
                'data'      => [
                    'coupon' => $coupon
                ]
            ];
        }

        if($cartPriceRule != null && $cartPriceRule->combinable == false && $shoppingCart->hasCartPriceRuleNotCombinable() == true)
        {
            $errors[] = [
                'status'    => 'error',
                'code'      => 8,
                'message'   => 'This coupon is not combinable with other coupon',
                'trans'     => trans('market::pulsar.error_coupon_code_08'),
                'data'      => [
                    'coupon'                        => $coupon,
                    'priceRuleInCartNotCombinable'  => $shoppingCart->getCartPriceRulesNotCombinable()->toArray()
                ]
            ];
        }

        // check if exist this cart price rule in cart
        if(
            $cartPriceRule != null &&
            $shoppingCart
                ->getPriceRules()
                ->has(
                    md5($cartPriceRule->id . get_class($cartPriceRule) .  $cartPriceRule->discount_type_id . $cartPriceRule->combinable)
                )
        )
        {
            $errors[] = [
                'status'    => 'error',
                'code'      => 9,
                'message'   => 'This coupon already exist in cart',
                'trans'     => trans('market::pulsar.error_coupon_code_09'),
                'data'      => [
                    'coupon' => $coupon
                ]
            ];
        }

        // check if is a free shipping and there isn't shipping and cart price rule, haven't any discount
        if($cartPriceRule != null && $cartPriceRule->free_shipping && $cartPriceRule->discount_type_id == 1 && ! $shoppingCart->hasItemTransportable())
        {
            $errors[] = [
                'status'    => 'error',
                'code'      => 10,
                'message'   => 'There are no shipping costs, this coupon is not necessary',
                'trans'     => trans('market::pulsar.error_coupon_code_10'),
                'data'      => [
                    'coupon' =>  $coupon
                ]
            ];
        }

        if(count($errors) > 0)
        {
            return [
                'status'    => 'error',
                'errors'    => $errors
            ];
        }
        else
        {
            return [
                'status'        => 'success',
                'coupon'        => $coupon
            ];
        }
    }

    /**
     * @param \Syscover\ShoppingCart\Cart           $shoppingCart
     * @param string                                $coupon
     * @param string                                $lang           add coupon code from this language
     * @param \Illuminate\Auth\SessionGuard         $sessionGuard   request session guard to check if user is authenticated, for cases necessary
     * @return null | \Syscover\Market\Models\CartPriceRule
     * @throws \Exception
     */
    public static function addCoupon($shoppingCart, $coupon, $lang, $sessionGuard = null)
    {
        $response       = self::checkCoupon($coupon, $lang, $sessionGuard);
        $cartPriceRule  = null;

        // check that rule its ok
        if($response['status'] == 'success')
        {
            // get price rule from database
            $cartPriceRule = CartPriceRule::builder($lang)->where('coupon_code', 'like', $coupon)->first();

            if($cartPriceRule != null)
            {
                try
                {
                    $shoppingCart->addCartPriceRule(
                        new PriceRule(
                            $cartPriceRule->id,
                            get_class($cartPriceRule),
                            collect($cartPriceRule->names)->where('id', $lang)->first() ? collect($cartPriceRule->names)->where('id', $lang)->first()['value'] : __('core::common.undefined'),
                            collect($cartPriceRule->descriptions)->where('id', $lang)->first() ? collect($cartPriceRule->descriptions)->where('id', $lang)->first()['value'] : __('core::common.undefined'),
                            $cartPriceRule->discount_type_id,
                            $cartPriceRule->free_shipping,
                            $cartPriceRule->discount_fixed_amount,
                            $cartPriceRule->discount_percentage,
                            $cartPriceRule->maximum_discount_amount,
                            $cartPriceRule->apply_shipping_amount,
                            $cartPriceRule->combinable,
                            [
                                'priceRule' => $cartPriceRule
                            ]
                        )
                    );
                }
                catch (ShoppingCartNotCombinablePriceRuleException $e)
                {
                    Log::error($e->getMessage());
                    throw new \Exception($e->getMessage());
                }
                catch (\Exception $e)
                {
                    Log::error($e->getMessage());
                    throw new \Exception($e->getMessage());
                }
            }
            else
            {
                Log::error('This coupon code, does not exist');
                throw new \Exception('This coupon code, does not exist');
            }
        }

        return $cartPriceRule;
    }
}
