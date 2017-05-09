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
        // check if there is id to know if is a new lang
        if($request->has('id'))
        {
            $id     = $request->input('id');
            $idAux  = $id;
        }
        else
        {
            $id = OrderStatus::max('id');
            $id++;
            $idAux = null;
        }

        $object = OrderStatus::create([
            'id'                    => $id,
            'lang_id'               => $request->input('lang_id'),
            'name'                  => $request->input('name'),
            'active'                => $request->input('active'),
            'data_lang'             => OrderStatus::addLangDataRecord($request->input('lang_id'), $idAux)
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
        OrderStatus::where('id', $id)->where('lang_id', $lang)->update([
            'name'                  => $request->input('name'),
            'active'                => $request->input('active'),
        ]);

        $object = OrderStatus::where('id', $id)->where('lang_id', $lang)->first();

        $response['status'] = "success";
        $response['data']   = $object;

        return response()->json($response);
    }
}
