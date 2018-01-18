<?php namespace Syscover\Market\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Ssheduardo\Redsys\Facades\Redsys;
use Syscover\Market\Models\Order;
use Syscover\Market\Services\RedsysService;

class RedsysController extends BaseController
{
    public function index(Request $request)
    {
        // log
        Log::info('Enter in market.redsys.notification route whit parameters', $request->all());

        $params = RedsysService::parameters();

        try
        {
            $parameters = Redsys::getMerchantParameters($request->input('Ds_MerchantParameters'));
            $DsResponse = $parameters['Ds_Response'];
            $DsResponse += 0;

            if(Redsys::check($params->key, $request->all()) && $DsResponse <= 99)
            {
                $nOrder = str_replace($params->pefix, '', $parameters['Ds_Order']);

                // get order
                $order = Order::builder()
                    ->where('id', $nOrder)
                    ->first();

                // change order status to next status, depending on your method of payment
                Order::where('id', $nOrder)->update([
                    'status_id' => $order->order_status_successful_id
                ]);

                // log register on order
                $order->setOrderLog(trans('market::pulsar.message_redsys_payment_successful'));

                return response()->json([
                    'status'    => 'success'
                ]);
            }
            else
            {
                Log::error('Error in market.redsys.notification route whit parameters', $DsResponse);
            }
        }
        catch(\Exception $e)
        {
            Log::error('Error exception in market.redsys.notification route', $e->getMessage());

            return response()->json([
                'status'    => 'error',
                'message'   => $e->getMessage()
            ]);
        }
    }
}
