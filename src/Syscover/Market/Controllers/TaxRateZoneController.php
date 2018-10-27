<?php namespace Syscover\Market\Controllers;

use Illuminate\Http\Request;
use Syscover\Core\Controllers\CoreController;
use Syscover\Market\Models\TaxRateZone;

/**
 * Class TaxRateZoneController
 * @package Syscover\Market\Controllers
 */

class TaxRateZoneController extends CoreController
{
    protected $model = TaxRateZone::class;

    public function store(Request $request)
    {
        $object = TaxRateZone::create([
            'name'                  => $request->input('name'),
            'country_id'            => $request->input('country_id'),
            'territorial_area_1_id' => $request->input('territorial_area_1_id'),
            'territorial_area_2_id' => $request->input('territorial_area_2_id'),
            'territorial_area_3_id' => $request->input('territorial_area_3_id'),
            'zip'                   => $request->input('zip'),
            'tax_rate'              => $request->input('tax_rate'),
        ]);

        $response['status'] = "success";
        $response['data']   = $object;

        return response()->json($response);
    }

    public function update(Request $request, $id)
    {
        TaxRateZone::where('id', $id)->update([
            'name'                  => $request->input('name'),
            'country_id'            => $request->input('country_id'),
            'territorial_area_1_id' => $request->input('territorial_area_1_id'),
            'territorial_area_2_id' => $request->input('territorial_area_2_id'),
            'territorial_area_3_id' => $request->input('territorial_area_3_id'),
            'zip'                   => $request->input('zip'),
            'tax_rate'              => $request->input('tax_rate'),
        ]);

        $object = TaxRateZone::find($id);

        $response['status'] = "success";
        $response['data']   = $object;

        return response()->json($response);
    }
}