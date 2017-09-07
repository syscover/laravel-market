<?php namespace Syscover\Market\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Log;

class RedsysController extends BaseController
{
    public function index(Request $request)
    {
        // log
        Log::info('Enter in marketRedsysNotification route whit parameters', $request->all());

        try
        {
            // package obtain from , https://github.com/ssheduardo/sermepa
            $redsys     = new Tpv();
            $parameters = $redsys->getMerchantParameters($request->input('Ds_MerchantParameters'));
            $DsResponse = $parameters['Ds_Response'];
            $DsResponse += 0;

            if($redsys->check(config('market.redsysMode') == 'live'? config('market.redsysLiveKey') : config('market.redsysTestKey'), $request->all()) && $DsResponse <= 99)
            {
                $nOrder = str_replace(config('market.orderIdPrefix'), '', $parameters['Ds_Order']);

                // get order
                $order = Order::builder()
                    ->where('id_116', $nOrder)
                    ->first();

                // change order status to next status, depending on your method of payment
                Order::where('id_116', $nOrder)->update([
                    // get next status
                    'status_id_116' => $order->order_status_successful_id_115
                ]);

                //*******************************************************
                // If you wan send confirmation email, this is the place
                //*******************************************************

                // log register on order
                Order::setOrderLog($nOrder, trans('market::pulsar.message_tpv_payment_successful'));

                return response()->json([
                    'status'    => 'success'
                ]);
            }
            else
            {
                return response()->json([
                    'status'    => 'error',
                    'error'     => $DsResponse
                ]);
            }
        }
        catch(\Exception $e)
        {
            echo $e->getMessage();
        }
    }
}
