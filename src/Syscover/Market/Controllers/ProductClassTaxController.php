<?php namespace Syscover\Market\Controllers;

use Illuminate\Http\Request;
use Syscover\Core\Controllers\CoreController;
use Syscover\Market\Models\ProductClassTax;

class ProductClassTaxController extends CoreController
{
    protected $model = ProductClassTax::class;

    public function store(Request $request)
    {
        $object = ProductClassTax::create([
            'name'  => $request->input('name')
        ]);

        $response['status'] = "success";
        $response['data']   = $object;

        return response()->json($response);
    }

    public function update(Request $request, $id)
    {
        ProductClassTax::where('id', $id)->update([
            'name' => $request->input('name')
        ]);

        $object = ProductClassTax::find($id);

        $response['status'] = "success";
        $response['data']   = $object;

        return response()->json($response);
    }
}
