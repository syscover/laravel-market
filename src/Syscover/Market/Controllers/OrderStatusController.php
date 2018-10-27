<?php namespace Syscover\Market\Controllers;

use Illuminate\Http\Request;
use Syscover\Core\Controllers\CoreController;
use Syscover\Market\Models\OrderStatus;
use Syscover\Market\Services\OrderStatusService;

class OrderStatusController extends CoreController
{
    protected $model = OrderStatus::class;

    public function store(Request $request)
    {
        $response['status'] = "success";
        $response['data']   = OrderStatusService::create($request->all());

        return response()->json($response);
    }

    public function update(Request $request)
    {
        $response['status'] = "success";
        $response['data']   = OrderStatusService::update($request->all());

        return response()->json($response);
    }
}
