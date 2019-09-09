<?php namespace Syscover\Market\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Route;
use Syscover\Market\Events\PaymentResponseError;
use Syscover\Market\Events\PaymentResponseSuccessful;
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

        $paymentResponses   = event(new PaymentResponseSuccessful($order));
        $redsysResponses    = event(new RedsysResponseSuccessful($order));
        $responses          = array_merge($paymentResponses, $redsysResponses);

        // check if response is a string route
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

        // Check is response is a redirect
        foreach ($responses as $response)
        {
            if (get_class($response) === 'Illuminate\Http\RedirectResponse')
            {
                return response;
            }
        }

        return null;
    }

    public function error()
    {
        $order = RedsysService::error();

        $paymentResponses   = event(new PaymentResponseError($order));
        $redsysResponses    = event(new RedsysResponseError($order));
        $responses          = array_merge($paymentResponses, $redsysResponses);

        foreach ($responses as $response)
        {
            if(is_string($response) && Route::has($response))
            {
                return redirect()
                    ->route($response, ['id' => $order->id])
                    ->with([
                        'status' => 'error'
                    ]);
            }
        }

        return null;
    }
}
