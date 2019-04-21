<?php namespace Syscover\Market\Services;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
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

    public static function create($object)
    {
        self::checkCreate($object);

        $taxRule = TaxRule::create(self::builder($object));

        $taxRule->tax_rate_zones()->sync($object['tax_rate_zones_id']);
        $taxRule->customer_class_taxes()->sync($object['customer_class_taxes_id']);
        $taxRule->product_class_taxes()->sync($object['product_class_taxes_id']);

        return $taxRule;
    }

    public static function update($object)
    {
        self::checkUpdate($object);

        TaxRule::where('id', $object['id'])->update(self::builder($object));

        $taxRule = TaxRule::find($object['id']);

        $taxRule->tax_rate_zones()->sync($object['tax_rate_zones_id']);
        $taxRule->customer_class_taxes()->sync($object['customer_class_taxes_id']);
        $taxRule->product_class_taxes()->sync($object['product_class_taxes_id']);

        return $taxRule;
    }

    private static function builder($object, $filterKeys = null)
    {
        $object = collect($object);
        if($filterKeys) return $object->only($filterKeys)->toArray();

        return  $object->only(['id', 'name', 'translation', 'priority', 'sort'])->toArray();
    }

    private static function checkCreate($object)
    {
        if(empty($object['name']))                                                                      throw new \Exception('You have to define a name field to create a tax rule');
        if(empty($object['priority']))                                                                  throw new \Exception('You have to define a priority field to create a tax rule');
        if(empty($object['sort']))                                                                      throw new \Exception('You have to define a sort field to create a tax rule');
        if(! is_array($object['tax_rate_zones_id']) || empty($object['tax_rate_zones_id']))             throw new \Exception('You have to define a tax_rate_zones_id array field with elements to create a tax rule');
        if(! is_array($object['customer_class_taxes_id']) || empty($object['customer_class_taxes_id'])) throw new \Exception('You have to define a customer_class_taxes_id array field with elements to create a tax rule');
        if(! is_array($object['product_class_taxes_id']) || empty($object['product_class_taxes_id']))   throw new \Exception('You have to define a product_class_taxes_id array field with elements to create a tax rule');
    }

    private static function checkUpdate($object)
    {
        if(empty($object['id']))                                                                        throw new \Exception('You have to define a id field to update a tax rule');
        if(! is_array($object['tax_rate_zones_id']) || empty($object['tax_rate_zones_id']))             throw new \Exception('You have to define a tax_rate_zones_id array field with elements to update a tax rule');
        if(! is_array($object['customer_class_taxes_id']) || empty($object['customer_class_taxes_id'])) throw new \Exception('You have to define a customer_class_taxes_id array field with elements to update a tax rule');
        if(! is_array($object['product_class_taxes_id']) || empty($object['product_class_taxes_id']))   throw new \Exception('You have to define a product_class_taxes_id array field with elements to update a tax rule');
    }

    public static function getTaxRules(): Collection
    {
        if(session('pulsar-market.tax_rules'))
        {
            return session('pulsar-market.tax_rules');
        }
        else
        {
            return self::getCustomerTaxRules();
        }
    }

    public static function setTaxRules($taxRules)
    {
        session(['pulsar-market.tax_rules' => $taxRules]);
    }

    public static function checkCustomerTaxRules(
        int $customer_class_tax_id = null,
        string $country_id = null,
        string $territorial_area_1_id = null,
        string $territorial_area_2_id = null,
        string $territorial_area_3_id = null,
        string $zip = null,
        string $guard = 'crm'
    )
    {
        // save tax rules in customer instance
        $taxRules = self::getCustomerTaxRules(
            $customer_class_tax_id,
            $country_id,
            $territorial_area_1_id,
            $territorial_area_2_id,
            $territorial_area_3_id,
            $zip
        );

        // set tax rules in Auth guard
        if(Auth::guard($guard)->check()) Auth::guard($guard)->user()->tax_rules = $taxRules;

        // calculate prices in shopping cart
        self::taxCalculateOverShoppingCart($taxRules);

        // set pulsar-market.tax_rules session variable, to calculate prices
        self::setTaxRules($taxRules);

        return $taxRules;
    }

    public static function getShoppingCartTaxRules(int $productClassTaxId = null)
    {
        $taxRules = self::getTaxRules();

        // filter tax rule by product class tax id
        if($productClassTaxId)
        {
            $taxRules = $taxRules->where('product_class_tax_id', $productClassTaxId);
        }

        // add tax rules
        $shoppingCartTaxRules = [];
        foreach ($taxRules as $taxRule)
        {
            $shoppingCartTaxRules[] = new ShoppingCartTaxRule(
                __($taxRule->translation),
                $taxRule->tax_rate,
                $taxRule->priority,
                $taxRule->sort
            );
        }

        return $shoppingCartTaxRules;
    }

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

    public static function taxCalculateOverShoppingCart($taxRules)
    {
        // check if has product in shopping cart
        if(CartProvider::instance()->getCartItems()->count() > 0)
        {
            // get products
            $products = Product::builder()
                ->where('lang_id', user_lang())
                ->whereIn('market_product.id', CartProvider::instance()->getCartItems()->pluck('id'))
                ->get();

            $taxRules = $taxRules
                // filter tax rules by products that are in shopping cart
                ->whereIn('product_class_tax_id', $products->pluck('product_class_tax_id')->toArray())
                // group tax rules by products class tax
                ->groupBy('product_class_tax_id')
                // and sor by priority each group of product class tax
                ->map(function($taxRule, $key) {
                    return $taxRule->sortBy('priority');
                });

            // add tax to each item from shopping cart
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
                    $itemTaxRules = $taxRules->get($products->where('id', $item->id)->first()->product_class_tax_id);

                    if($itemTaxRules->count() > 0)
                    {
                        // add tax rules
                        foreach ($itemTaxRules as $taxRule)
                        {
                            $item->addTaxRule(
                                new ShoppingCartTaxRule(
                                    __($taxRule->translation),
                                    $taxRule->tax_rate,
                                    $taxRule->priority,
                                    $taxRule->sort
                                )
                            );
                        }
                    }
                }

                $item->calculateAmounts();
            }
        }
    }

    public static function getCustomerTaxRules(
        int $customer_class_tax_id = null,
        string $country_id = null,
        string $territorial_area_1_id = null,
        string $territorial_area_2_id = null,
        string $territorial_area_3_id = null,
        string $zip = null
    )
    {
        // get tax rules for all product_class_tax_id
        $taxRules = TaxRule::builder()
            ->where('country_id', $country_id ?? config('pulsar-market.default_country_tax'))
            ->where('customer_class_tax_id', $customer_class_tax_id ?? config('pulsar-market.default_customer_class_tax'))
            ->orderBy('priority', 'asc')
            ->get();

        // filter by tas rules
        $taxRules = self::filterTaxRuleByTerritorialArea($taxRules, $territorial_area_1_id, 'territorial_area_1_id');
        $taxRules = self::filterTaxRuleByTerritorialArea($taxRules, $territorial_area_2_id, 'territorial_area_2_id');
        $taxRules = self::filterTaxRuleByTerritorialArea($taxRules, $territorial_area_3_id, 'territorial_area_3_id');
        $taxRules = self::filterTaxRuleByTerritorialArea($taxRules, $zip, 'zip', true);

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
                    return self::checkZip($taxRule->zip, $territorialArea);
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
