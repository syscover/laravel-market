<?php namespace Syscover\Market\Controllers;

use Illuminate\Http\Request;
use Syscover\Core\Controllers\CoreController;
use Syscover\Market\Models\PaymentMethod;
use Syscover\Market\Services\PaymentMethodService;

class PaymentMethodController extends CoreController
{
    protected $model = PaymentMethod::class;

    public function store(Request $request)
    {
        $response['status'] = "success";
        $response['data']   = PaymentMethodService::create($request->all());

        return response()->json($response);
    }

    public function update(Request $request)
    {
        $response['status'] = "success";
        $response['data']   = PaymentMethodService::update($request->all());

        return response()->json($response);
    }
}
