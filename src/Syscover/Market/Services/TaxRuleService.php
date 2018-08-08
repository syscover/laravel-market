<?php namespace Syscover\Market\Services;

use Illuminate\Support\Collection;
use Syscover\Market\Models\Product;
use Syscover\Market\Models\TaxRule;
use Syscover\ShoppingCart\Facades\CartProvider;
use Syscover\ShoppingCart\Cart;
use Syscover\ShoppingCart\TaxRule as ShoppingCartTaxRule;

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

    public static function taxCalculateOverShoppingCart(
        $guard = 'crm',
        $country_id = null,
        $territorial_area_1_id = null,
        $territorial_area_2_id = null,
        $territorial_area_3_id = null,
        $zip = null
    )
    {
        // check if has product in shopping cart
        if(CartProvider::instance()->getCartItems()->count() > 0)
        {
            // get products
            $products = Product::builder()
                ->whereIn('market_product.id', CartProvider::instance()->getCartItems()->pluck('id'))
                ->get();

            // get tax rules
            $taxRules = TaxRule::builder()
                ->where('country_id', auth($guard)->check() && auth($guard)->user()->country_id ? auth($guard)->user()->country_id : config('pulsar-market.default_tax_country'))
                ->where('customer_class_tax_id', auth($guard)->check() && auth($guard)->user()->class_tax ? auth($guard)->user()->class_tax : config('pulsar-market.default_class_customer_tax'))
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

                // if there are any tax rule, and product with tax rule
                if(
                    $taxRules->count() > 0 &&
                    $products->where('id', $item->id)->count() > 0 &&
                    $taxRules->get($products->where('id', $item->id)->first()->product_class_tax_id) instanceof Collection &&
                    $taxRules->get($products->where('id', $item->id)->first()->product_class_tax_id)->count() > 0
                )
                {
                    // get tax rules from item
                    $itemTaxRules = $taxRules->get($products->where('id',$item->id)->first()->product_class_tax_id);

                    // TaxRules that target according geolocation filters parameters
                    $targetItemTaxRules = collect();

                    // check tax rules to item
                    foreach ($itemTaxRules as $itemTaxRule)
                    {
                        // if tax rule territorial property is null, the tax is applied
                        // if tax rule territorial property is not null and is not equal to territorial parameter, the tax is not applied
                        $isVerify = true;
                        if ($itemTaxRule->country_id && $itemTaxRule->country_id !== $country_id) $isVerify = false;
                        if ($itemTaxRule->territorial_area_1_id && $itemTaxRule->territorial_area_1_id !== $territorial_area_1_id) $isVerify = false;
                        if ($itemTaxRule->territorial_area_2_id && $itemTaxRule->territorial_area_2_id !== $territorial_area_2_id) $isVerify = false;
                        if ($itemTaxRule->territorial_area_3_id && $itemTaxRule->territorial_area_3_id !== $territorial_area_3_id) $isVerify = false;
                        // if tax rule zip property is not null and is not target pattern to zip parameter, the tax is not applied
                        if ($itemTaxRule->zip && ! TaxRuleService::checkZip($itemTaxRule->zip, $zip)) $isVerify = false;

                        if ($isVerify) $targetItemTaxRules->push($itemTaxRule);
                    }

                    // if has various tax rules with different priorities, take last one with higher priority
                    $targetItemTaxRules = $targetItemTaxRules
                        ->sortBy('priority')
                        ->groupBy('priority')
                        ->last();

                    // in tax rules with the same priorities, apply tax rules accord your sort property
                    if(count($targetItemTaxRules) > 1)
                    {
                        $targetItemTaxRules = collect($targetItemTaxRules)->sortBy('sort');
                    }

                    // add tax rules
                    foreach ($targetItemTaxRules as $itemTaxRule)
                    {
                        $item->addTaxRule(
                            new ShoppingCartTaxRule(
                                __($itemTaxRule->translation),
                                $itemTaxRule->tax_rate,
                                $itemTaxRule->priority,
                                $itemTaxRule->sort
                            )
                        );
                    }
                }

                // force to calculate amounts
                $item->calculateAmounts(config('pulsar-shopping_cart.product_tax_prices'));
            }
        }
    }

    private static function checkZip(string $zipPattern, string $zip): bool
    {
        if(! $zip) return false;

        foreach (str_split($zipPattern) as $zipPatternItem)
        {
            foreach (str_split($zip) as $zipItem)
            {
                if ($zipPatternItem !== $zipItem && $zipPatternItem !== '*')
                {
                    return false;
                }
            }
        }
        return true;
    }
}