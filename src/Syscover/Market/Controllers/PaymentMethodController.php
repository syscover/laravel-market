<?php namespace Syscover\Market\Controllers;

use Illuminate\Http\Request;
use Syscover\Core\Controllers\CoreController;
use Syscover\Market\Models\PaymentMethod;

class PaymentMethodController extends CoreController
{
    protected $model = PaymentMethod::class;

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // check if there is id
        if($request->has('id'))
        {
            $id     = $request->input('id');
        }
        else
        {
            $id = PaymentMethod::max('id');
            $id++;
        }

        $object = PaymentMethod::create([
            'id'                            => $id,
            'lang_id'                       => $request->input('lang_id'),
            'name'                          => $request->input('name'),
            'order_status_successful_id'    => $request->input('order_status_successful_id'),
            'minimum_price'                 => $request->input('minimum_price'),
            'maximum_price'                 => $request->input('maximum_price'),
            'instructions'                  => $request->input('instructions'),
            'sort'                          => $request->input('sort'),
            'active'                        => $request->has('active'),
            'data_lang'                     => PaymentMethod::addLangDataRecord($request->input('lang_id'), $id)
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
     * @param   string  $lang
     * @return  \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id, $lang)
    {
        PaymentMethod::where('id', $id)->where('lang_id', $lang)->update([
            'name'                          => $request->input('name'),
            'order_status_successful_id'    => $request->input('order_status_successful_id'),
            'minimum_price'                 => $request->input('minimum_price'),
            'maximum_price'                 => $request->input('maximum_price'),
            'instructions'                  => $request->input('instructions'),
            'sort'                          => $request->input('sort'),
            'active'                        => $request->has('active'),
        ]);

        $object = PaymentMethod::where('id', $id)->where('lang_id', $lang)->first();

        $response['status'] = "success";
        $response['data']   = $object;

        return response()->json($response);
    }
}
