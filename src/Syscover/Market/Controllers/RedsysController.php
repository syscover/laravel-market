<?php namespace Syscover\Market\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\View;
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
            if(View::exists($response)) return view($response, [
                'order'     => $order,
                'status'    => 'successful'
            ]);
        }

        return null;
    }

    public function error()
    {
        $order = RedsysService::error();

        $responses = event(new RedsysResponseError($order));

        foreach ($responses as $response)
        {
            if(View::exists($response)) return view($response, [
                'order'     => $order,
                'status'    => 'error'
            ]);
        }

        return null;
    }
}
