<?php namespace Syscover\Market\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Route;
use Syscover\Market\Events\RedsysAsyncResponse;
use Syscover\Market\Events\RedsysResponseError;
use Syscover\Market\Events\RedsysResponseSuccessful;
use Syscover\Market\Services\RedsysService;

class RedsysController extends BaseController
{
    public function asyncResponse()
    {
        $response = RedsysService::asyncResponse();

        event(new RedsysAsyncResponse($response['order']));

        return response()->json($response);
    }

    public function successful()
    {
        $order = RedsysService::successful();

        $responses = event(new RedsysResponseSuccessful($order));

        foreach ($responses as $response)
        {
            if(is_string($response) && Route::has($response))
            {
                return redirect()
                    ->route($response, ['id' => $order->id])
                    ->with([
                        'status' => 'successful'
                    ]);
            }
        }

        return null;
    }

    public function error()
    {
        $order = RedsysService::error();

        $responses = event(new RedsysResponseError($order));

        foreach ($responses as $response)
        {
            if(is_string($response) && Route::has($response))
            {
                return redirect()
                    ->route($response, ['id' => $order->id])
                    ->with([
                        'status' => 'successful'
                    ]);
            }
        }

        return null;
    }
}
