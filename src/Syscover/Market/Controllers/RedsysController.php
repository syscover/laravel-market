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

        $view = event(new RedsysResponseSuccessful($order));

        if(View::exists($view)) return view($view, ['order' => $order]);

        return null;
    }

    public function error()
    {
        $order = RedsysService::error();

        $view = event(new RedsysResponseError($order));

        if(View::exists($view)) return view($view, ['order' => $order]);

        return null;
    }
}
