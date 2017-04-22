<?php namespace Syscover\Market\Controllers;

use Illuminate\Http\Request;
use Syscover\Core\Controllers\CoreController;
use Syscover\Market\Models\CustomerClassTax;

class CustomerClassTaxController extends CoreController
{
    protected $model = CustomerClassTax::class;

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $object = CustomerClassTax::create([
            'name' => $request->input('name')
        ]);

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
        CustomerClassTax::where('id', $id)->update([
            'name' => $request->input('name')
        ]);

        $object = CustomerClassTax::find($id);

        $response['status'] = "success";
        $response['data']   = $object;

        return response()->json($response);
    }
}
