<?php namespace Syscover\Market\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Log;
use Ssheduardo\Redsys\Facades\Redsys;
use Syscover\Market\Models\Order;
use Syscover\Market\Services\RedsysService;

class RedsysController extends BaseController
{
    public function index(Request $request)
    {
        // log
        Log::info('Enter in marketRedsysNotification route whit parameters', $request->all());

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
                Log::error('Error in marketRedsysNotification route whit parameters', $DsResponse);
            }
        }
        catch(\Exception $e)
        {
            Log::error('Error exception in marketRedsysNotification route', $e->getMessage());
            return response()->json([
                'status'    => 'error',
                'message'   => $e->getMessage()
            ]);
        }
    }

    public function paymentSuccessful(Request $request)
    {
        // log
        Log::info('Enter in marketRedsysSuccessful route whit parameters', $request->all());

        $params = RedsysService::parameters();

        try {
            $parameters = Redsys::getMerchantParameters($request->input("Ds_MerchantParameters"));
            $DsResponse = $parameters["Ds_Response"];
            $DsResponse += 0;

            if(Redsys::check($params->key, $request->all()) && $DsResponse <= 99)
            {
//                return redirect()->route('clubViewOrder-' . user_lang(), [
//                    'order' => str_replace($params->suffix, '', $parameters['Ds_Order'])
//                ]);
                dd('ok');
            }
            else
            {
                Log::error('Error in marketRedsysSuccessful route whit parameters', $DsResponse);
                dd('error');
                //return redirect()->route('error-' . user_lang());
            }
        }
        catch (\Exception $e)
        {
            Log::error('Error exception in marketRedsysSuccessful route', $e->getMessage());

            return response()->json([
                'status'    => 'error',
                'message'   => $e->getMessage()
            ]);
        }
    }

    public function paymentError(Request $request)
    {
        dd('ERRORRRR');
        try
        {
            $parameters = Redsys::getMerchantParameters($request->input("Ds_MerchantParameters"));

            //$nOrder     = str_replace(config('market.orderIdPrefix'), '', $parameters['Ds_Order']);

            // set log error in order
            //Order::setOrderLog($nOrder, trans('market::pulsar.message_tpv_payment_error', ['error' => $parameters['Ds_Response']]));

            //return redirect()->route('error-' . user_lang());
        }
        catch(\Exception $e)
        {
            //echo $e->getMessage();
        }
    }
}
