<?php namespace Syscover\Market\Services;

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
            $taxRate = $taxRule->taxRateZones->sum('tax_rate') / 100;

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
            $taxRate = $taxRule->taxRateZones->sum('tax_rate') / 100;

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
}