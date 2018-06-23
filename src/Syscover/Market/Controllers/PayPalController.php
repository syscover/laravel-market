<?php namespace Syscover\Market\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Syscover\Market\Events\PaypalResponseError;
use Syscover\Market\Events\PaypalResponseSuccessful;
use Syscover\Market\Services\PayPalService;

class PayPalController extends BaseController
{
    public function successful(Request $request)
    {
        $order = PayPalService::successful();

        $responses = event(new PaypalResponseSuccessful($order));

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
        dd(request()->all());

        //$order = PayPalService::error();

        // $responses = event(new PaypalResponseError($order));
    }
}