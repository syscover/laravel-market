<?php namespace Syscover\Market\Controllers;

use Illuminate\Http\Request;
use Syscover\Core\Controllers\CoreController;
use Syscover\Market\Models\CustomerGroupCustomerClassTax;

class CustomerGroupCustomerClassTaxController extends CoreController
{
    protected $model = CustomerGroupCustomerClassTax::class;

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $object = CustomerGroupCustomerClassTax::create([
            'group_id'                  => $request->input('group_id'),
            'customer_class_tax_id'     => $request->input('customer_class_tax_id')
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
        CustomerGroupCustomerClassTax::where('group_id', $id)->update([
            'customer_class_tax_id' => $request->input('customer_class_tax_id')
        ]);

        $object = CustomerGroupCustomerClassTax::find($id);

        $response['status'] = "success";
        $response['data']   = $object;

        return response()->json($response);
    }
}
