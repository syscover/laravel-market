<?php namespace Syscover\Market\Controllers;

use Illuminate\Routing\Controller as BaseController;
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

        $response = event(new RedsysResponseSuccessful($order));

        return $this->getView($response);
    }

    public function error()
    {
        $order = RedsysService::error();

        $response = event(new RedsysResponseError($order));

        return $this->getView($response);
    }

    private function getView($response)
    {
        $filtered = collect($response)->filter(function ($value, $key) {
            return $key === 'view';
        });

        if($filtered->count() > 0)
        {
            return view($filtered->get('view'));
        }
        else
        {
            return null;
        }
    }
}
