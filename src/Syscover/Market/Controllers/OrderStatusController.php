<?php namespace Syscover\Market\Controllers;

use Illuminate\Http\Request;
use Syscover\Core\Controllers\CoreController;
use Syscover\Market\Models\OrderStatus;

class OrderStatusController extends CoreController
{
    protected $model = OrderStatus::class;

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
            $idLang = $id;
        }
        else
        {
            $id = OrderStatus::max('id');
            $id++;
            $idLang = null;
        }

        $orderStatus = OrderStatus::create([
            'id'                    => $id,
            'lang_id'               => $request->input('lang_id'),
            'name'                  => $request->input('name'),
            'active'                => $request->has('active'),
            'data_lang'             => OrderStatus::addLangDataRecord($request->input('lang_id'), $idLang)
        ]);

        $response['status'] = "success";
        $response['data']   = $orderStatus;

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
        OrderStatus::where('id', $id)->where('lang_id', $lang)->update([
            'name'                  => $request->input('name'),
            'active'                => $request->has('active'),
        ]);

        $orderStatus = OrderStatus::where('id', $id)->where('lang_id', $lang)->first();

        $response['status'] = "success";
        $response['data']   = $orderStatus;

        return response()->json($response);
    }
}
