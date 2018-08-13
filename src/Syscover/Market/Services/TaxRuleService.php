<?php namespace Syscover\Market\Services;

use Illuminate\Support\Collection;
use Syscover\Market\Models\Product;
use Syscover\Market\Models\TaxRule;
use Syscover\ShoppingCart\Facades\CartProvider;
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
        // group by priority, the taxRules are sort by priority previously
        $taxRulesGrouped    = $taxRules->groupBy('priority');
        $taxAmount          = 0;
        $response           = collect();

        foreach ($taxRulesGrouped as $taxRuleGroup)
        {
            // if is different priority, calculate tax amount over sum of price and previous tax amount
            $price          += $taxAmount;

            // reset tax amount to the next tax  rule priority
            $taxAmount      = 0;

            foreach ($taxRuleGroup as $taxRule)
            {
                // calculate tax rate of tax rule of same priority
                $taxRate        = $taxRule->tax_rate / 100;

                // get tax amount from tax rate of same priority
                $taxAmount      += ($price * $taxRate);

                $response->push([
                    'tax_rule'      => $taxRule,
                    'tax_amount'    => $taxAmount
                ]);
            }
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
        // group by priority, the taxRules are sort by priority previously
        $taxRulesGrouped    = $taxRules->groupBy('priority');
        $taxAmount          = 0;
        $response           = collect();

        foreach ($taxRulesGrouped->reverse() as $taxRuleGroup)
        {
            // if is different priority, calculate tax amount over sum of price and previous tax amount
            $price          -= $taxAmount;

            // reset tax amount to the next tax  rule priority
            $taxAmount      = 0;

            foreach ($taxRuleGroup as $taxRule)
            {
                // calculate tax rate of tax rule of same priority
                $taxRate        = $taxRule->tax_rate / 100;

                // get tax amount from tax rate of same priority
                $taxAmount      += ($price * $taxRate) / ($taxRate + 1);

                $response->push([
                    'tax_rule'      => $taxRule,
                    'tax_amount'    => $taxAmount
                ]);
            }
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
                ->where('country_id', auth($guard)->check() && auth($guard)->user()->country_id ? auth($guard)->user()->country_id : config('pulsar-market.default_country_tax'))
                ->where('customer_class_tax_id', auth($guard)->check() && auth($guard)->user()->class_tax ? auth($guard)->user()->class_tax : config('pulsar-market.default_customer_class_tax'))
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

                    if($targetItemTaxRules->count() > 0)
                    {
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
                }

                // force to calculate amounts
                $item->calculateAmounts(config('pulsar-shopping_cart.product_tax_prices'));
            }
        }
    }

    public static function getCustomerTaxRules(
        $customer_class_tax_id = null,
        $country_id = null,
        $territorial_area_1_id = null,
        $territorial_area_2_id = null,
        $territorial_area_3_id = null,
        $zip = null
    )
    {
        // get tax rules for all product_class_tax_id
        $taxRules = TaxRule::builder()
            ->where('country_id', $country_id ?? config('pulsar-market.default_country_tax'))
            ->where('customer_class_tax_id', $customer_class_tax_id ?? config('pulsar-market.default_customer_class_tax'))
            ->orderBy('priority', 'asc')
            ->get();


        // filter by tas rules
        $taxRules = TaxRuleService::filterTaxRuleByTerritorialArea($taxRules, $territorial_area_1_id, 'territorial_area_1_id');
        $taxRules = TaxRuleService::filterTaxRuleByTerritorialArea($taxRules, $territorial_area_2_id, 'territorial_area_2_id');
        $taxRules = TaxRuleService::filterTaxRuleByTerritorialArea($taxRules, $territorial_area_3_id, 'territorial_area_3_id');
        $taxRules = TaxRuleService::filterTaxRuleByTerritorialArea($taxRules, $zip, 'zip', true);

        // return with index reset
        return $taxRules->values();
    }

    private static function filterTaxRuleByTerritorialArea($taxRules, $territorialArea, $field, $isZip = false)
    {
        // loop to filter tax rules
        $results = $taxRules->filter(function ($taxRule, $key) use ($territorialArea, $field, $isZip) {

            // if customer has territorial area
            if ($territorialArea)
            {
                if($isZip)
                {
                    return TaxRuleService::checkZip($taxRule->zip, $territorialArea);
                }
                else
                {
                    // return tax rules which are equal to customer territorial area
                    return $taxRule->{$field} === $territorialArea;
                }
            }
            else
            {
                // if customer has not territorial area, return tax rules with territorial areas equal to null
                return is_null($taxRule->{$field});
            }
        });

        // after to do the filter and customer has territorial area
        if ($territorialArea)
        {
            // if has results, return this one
            if($results->count() > 0)
            {
                return $results;
            }
            // if has not results, return tax rules with territorial area equal to null
            else
            {
                return $taxRules->where($field, null);
            }
        }
        else
        {
            // if customer has not territorial area return those rules that has a null by territorial area, what are the filtered results.
            return $results;
        }
    }

    private static function checkZip(string $zipPattern = null, string $zip = null): bool
    {
        if(! $zipPattern || ! $zip) return false;

        $zipPatternArray    = str_split($zipPattern);
        $zipArray           = str_split($zip);

        foreach ($zipPatternArray as $key => $zipPatternArrayItem)
        {
            if (! isset($zipArray[$key]))
            {
                return false;
            }

            if ($zipPatternArrayItem !== $zipArray[$key] && $zipPatternArrayItem !== '*')
            {
                return false;
            }
        }
        return true;
    }
}