<?php namespace Syscover\Market\Controllers;

use Illuminate\Http\Request;
use Syscover\Core\Controllers\CoreController;
use Syscover\Market\Models\TaxRule;

/**
 * Class TaxRuleController
 * @package Syscover\Market\Controllers
 */

class TaxRuleController extends CoreController
{
    protected $model = TaxRule::class;

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $object = TaxRule::create([
            'name'          => $request->input('name'),
            'translation'   => $request->input('translation'),
            'priority'      => $request->input('priority'),
            'sort'          => $request->input('sort')
        ]);

        $object->taxRateZones()->sync($request->input('tax_rate_zones_id'));
        $object->customerClassTaxes()->sync($request->input('customer_class_taxes_id'));
        $object->productClassTaxes()->sync($request->input('product_class_taxes_id'));

        $response['status'] = "success";
        $response['data']   = $object;

        return response()->json($response);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param   \Illuminate\Http\Request  $request
     * @param   int     $id
     * @return  \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        TaxRule::where('id', $id)->update([
            'name'          => $request->input('name'),
            'translation'   => $request->input('translation'),
            'priority'      => $request->input('priority'),
            'sort'          => $request->input('sort')
        ]);

        $object = TaxRule::find($id);

        $object->taxRateZones()->sync($request->input('tax_rate_zones_id'));
        $object->customerClassTaxes()->sync($request->input('customer_class_taxes_id'));
        $object->productClassTaxes()->sync($request->input('product_class_taxes_id'));

        $response['status'] = "success";
        $response['data']   = $object;

        return response()->json($response);
    }
}