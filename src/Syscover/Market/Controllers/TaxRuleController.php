<?php namespace Syscover\Market\Controllers;

use Illuminate\Http\Request;
use Syscover\Core\Controllers\CoreController;
use Syscover\Market\Models\TaxRule;
use Syscover\Market\Services\TaxRuleService;

/**
 * Class TaxRuleController
 * @package Syscover\Market\Controllers
 */

class TaxRuleController extends CoreController
{
    protected $model = TaxRule::class;

    public function store(Request $request)
    {
        $object = TaxRule::create([
            'name'          => $request->input('name'),
            'translation'   => $request->input('translation'),
            'priority'      => $request->input('priority'),
            'sort'          => $request->input('sort')
        ]);

        $object->tax_rate_zones()->sync($request->input('tax_rate_zones_id'));
        $object->customer_class_taxes()->sync($request->input('customer_class_taxes_id'));
        $object->product_class_taxes()->sync($request->input('product_class_taxes_id'));

        $response['status'] = "success";
        $response['data']   = $object;

        return response()->json($response);
    }
    
    public function update(Request $request, $id)
    {
        TaxRule::where('id', $id)->update([
            'name'          => $request->input('name'),
            'translation'   => $request->input('translation'),
            'priority'      => $request->input('priority'),
            'sort'          => $request->input('sort')
        ]);

        $object = TaxRule::find($id);

        $object->tax_rate_zones()->sync($request->input('tax_rate_zones_id'));
        $object->customer_class_taxes()->sync($request->input('customer_class_taxes_id'));
        $object->product_class_taxes()->sync($request->input('product_class_taxes_id'));

        $response['status'] = "success";
        $response['data']   = $object;

        return response()->json($response);
    }

    public function getProductTaxes(Request $request)
    {
        // Set variable data
        $parameters         = $request->input('parameters');
        $price              = $parameters['price'];
        $productClassTax    = $parameters['productClassTax'];


        $taxRules = TaxRule::builder()
            ->where('country_id', config('pulsar-market.default_country_tax'))
            ->where('customer_class_tax_id', config('pulsar-market.default_customer_class_tax'))
            ->where('product_class_tax_id', $productClassTax)
            ->orderBy('priority', 'asc')
            ->get();

        if((int) config('pulsar-market.product_tax_prices') == TaxRuleService::PRICE_WITHOUT_TAX)
        {
            $taxes      = TaxRuleService::taxCalculateOverSubtotal($price, $taxRules);
            $taxAmount  = $taxes->sum('tax_amount');
            $subtotal   = $price;
            $total      = $subtotal + $taxAmount;

        }
        elseif ((int) config('pulsar-market.product_tax_prices') == TaxRuleService::PRICE_WITH_TAX)
        {
            $taxes      = TaxRuleService::taxCalculateOverTotal($price, $taxRules);
            $taxAmount  = $taxes->sum('tax_amount');
            $total      = $price;
            $subtotal   = $total - $taxAmount;
        }

        $response['status'] = "success";
        $response['data']   = [
            'taxes'             => $taxes,
            'subtotal'          => $subtotal,
            'taxAmount'         => $taxAmount,
            'total'             => $total,
            'subtotalFormat'    => number_format($subtotal, 2, ',', '.'),
            'taxAmountFormat'   => number_format($taxAmount, 2, ',', '.'),
            'totalFormat'       => number_format($total, 2, ',', '.'),
        ];

        return response()->json($response);
    }
}