<?php namespace Syscover\Market\Services;

use Illuminate\Support\Collection;
use Syscover\Market\Models\Product;
use Syscover\Market\Models\TaxRule;
use Syscover\ShoppingCart\Facades\CartProvider;
use Syscover\ShoppingCart\Cart;

/**
 * Class TaxRuleLibrary
 * @package Syscover\Market\Service
 */
class TaxRuleService
{
    const PRICE_WITHOUT_TAX = 1;
    const PRICE_WITH_TAX    = 2;

    /**
     * @param   float                               $price
     * @param   \Syscover\Market\Models\TaxRule[]   $taxRules
     * @return  array
     */
    public static function taxCalculateOverSubtotal($price, $taxRules)
    {
        $priority       = 0;
        $totalTax       = 0;
        $response       = collect();
        $priorityPrice  = $price;

        foreach ($taxRules as $taxRule)
        {
            $taxRate = $taxRule->tax_rate_zones->sum('tax_rate') / 100;

            // check priority
            if($taxRule->priority > $priority)
            {
                $priority       = $taxRule->priority;
                $priorityPrice  += $totalTax;
            }

            $taxAmount  = $priorityPrice * $taxRate;
            $totalTax   += $taxAmount;

            $response->push([
                'taxRule'   => $taxRule,
                'taxAmount' => $taxAmount
            ]);
        }

        return $response;
    }

    /**
     * @param   float                               $price
     * @param   \Syscover\Market\Models\TaxRule[]   $taxRules
     * @return  array
     */
    public static function taxCalculateOverTotal($price, $taxRules)
    {
        $priority       = 0;
        $totalTax       = 0;
        $response       = collect();
        $priorityPrice  = $price;

        foreach ($taxRules->reverse() as $taxRule)
        {
            $taxRate = $taxRule->tax_rate_zones->sum('tax_rate') / 100;

            // check priority
            if($taxRule->priority < $priority || ($priority === 0 && $taxRule->priority > 0))
            {
                $priority       = $taxRule->priority;
                $priorityPrice  -= $totalTax;
            }

            $taxAmount  = ($priorityPrice * $taxRate) / ($taxRate + 1);
            $totalTax   +=  $taxAmount;

            $response->push([
                'taxRule'   => $taxRule,
                'taxAmount' => $taxAmount
            ]);
        }

        return $response;
    }

    public static function taxCalculateOverShoppingCart($guard = 'crm')
    {
        // check if has product in shopping cart
        if(CartProvider::instance()->getCartItems()->count() > 0)
        {
            // get products
            $products = Product::builder()
                ->whereIn('id', CartProvider::instance()->getCartItems()->pluck('id'))
                ->get();

            // get tax rules
            $taxRules = TaxRule::builder()
                ->where('country_id', auth($guard)->check() && auth($guard)->user()->country_id ? auth($guard)->user()->country_id : config('pulsar-market.default_tax_country'))
                ->where('customer_class_tax_id', auth($guard)->check() && auth('crm')->user()->class_tax ? auth('crm')->user()->class_tax : config('pulsar-market.default_class_customer_tax'))
                ->whereIn('product_class_tax_id', $products->pluck('product_class_tax_id')->toArray())
                ->orderBy('priority', 'asc')
                ->get();

            // order tax rules
            $taxRules = $taxRules
                ->groupBy('product_class_tax_id')
                ->map(function($taxRule, $key){
                    return $taxRule->sortBy('priority');
                });

            // add tax to shopping cart
            foreach (CartProvider::instance()->getCartItems() as $item)
            {
                // reset tax rules from item
                $item->resetTaxRules();

                // if there ara any tax rule, and product with tax rule
                if(
                    $taxRules->count() > 0 &&
                    $products->where('id', $item->id)->count() > 0 &&
                    $taxRules->get($products->where('id', $item->id)->first()->product_class_tax_id) instanceof Collection &&
                    $taxRules->get($products->where('id', $item->id)->first()->product_class_tax_id)->count() > 0
                )
                {
                    // get tax rules from item
                    $itemTaxRules = $taxRules->get($products->where('id',$item->id)->first()->product_class_tax_id);

                    // add tax rules to item
                    foreach ($itemTaxRules as $itemTaxRule)
                    {
                        $item->addTaxRule($itemTaxRule->getTaxRuleShoppingCart());
                    }
                }

                // force to calculate amounts
                $item->calculateAmounts(Cart::PRICE_WITHOUT_TAX);
            }
        }
    }
}