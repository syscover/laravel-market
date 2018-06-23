<?php namespace Syscover\Market\Services;

use Illuminate\Support\Facades\Log;
use Ssheduardo\Redsys\Facades\Redsys;
use Syscover\Market\Models\Order;

class RedsysService
{
    /**
     * Create payment by Redsys
     */
    public static function createPayment($order, $xhr)
    {
        try
        {
            $params     = RedsysService::parameters();
            $orderId    = RedsysService::getOrderId($order->id);

            /****************************************************************
             * Attention!! the order of the definition of the parameters
             * can affect the correct execution of the script
             *****************************************************************/

            // set values
            Redsys::setOrder($orderId . $params->orderIdSuffix);
            Redsys::setAmount($order->getTotal(2, '.', ''));

            // params
            Redsys::setTitular($order->customer_name . ' ' . $order->customer_surname);
            Redsys::setEnviroment($params->environment);
            Redsys::setTradeName($params->merchantName);
            Redsys::setProductDescription(__($params->descriptionTrans, ['order' => $orderId . $params->orderIdSuffix]));
            Redsys::setMerchantcode($params->merchantCode);
            Redsys::setTerminal($params->terminal);
            Redsys::setCurrency($params->currency);
            Redsys::setTransactiontype($params->transactionType);
            Redsys::setVersion($params->version);
            Redsys::setIdForm('marketPaymentForm');

            // set urls
            Redsys::setNotification(route($params->asyncResponseRoute));
            Redsys::setUrlOk(route($params->successfulRoute));
            Redsys::setUrlKo(route($params->errorRoute));

            // set signature
            Redsys::setMerchantSignature(Redsys::generateMerchantSignature($params->key));      // key

            // log
            OrderService::log($order->id, __('market::pulsar.message_customer_throw_to_redsys'));
            Log::info('Create form to throw to REDSYS, order: ' . $order->id);

            if($xhr)
            {
                return Redsys::createForm();
            }
            else
            {
                return view('core::common.display', ['content' => Redsys::executeRedirection()]);
            }
        }
        catch(\Exception $e)
        {
            // log register on order
            OrderService::log($order->id, __('market::pulsar.message_customer_redsys_error', ['error' => $e->getMessage()]));
            Log::error($e->getMessage());

            throw new \Exception('Exception to create payment Redsys: ' . $e->getMessage());
        }
    }

    /**
     * Create async response for Redsys
     */
    public static function asyncResponse()
    {
        // log
        Log::info('Enter in market.redsys.notification route whit parameters', request()->all());

        $params = RedsysService::parameters();

        try
        {
            $parameters = Redsys::getMerchantParameters(request('Ds_MerchantParameters'));
            $DsResponse = $parameters['Ds_Response'];
            $DsResponse += 0;

            if(Redsys::check($params->key, request()->all()) && $DsResponse <= 99)
            {
                // get order ID
                $orderId = str_replace(config('pulsar-market.order_id_suffix'), '', $parameters['Ds_Order']);

                $order = Order::find((int)$orderId);

                // change order status
                $paymentMethod      = $order->payment_methods->where('lang_id', user_lang())->first();
                $order->status_id   = $paymentMethod->order_status_successful_id;
                $order->save();

                // log register on order
                OrderService::log($order->id, __('market::pulsar.message_redsys_payment_successful'));

                return [
                    'status'    => 'success',
                    'order'     => $order
                ];
            }
            else
            {
                Log::error('Error in api.market.redsys_async_response route whit parameters: ', $DsResponse);

                return [
                    'status'    => 'error',
                    'message'   => $DsResponse,
                    'order'     => null
                ];
            }
        }
        catch(\Exception $e)
        {
            Log::error('Error exception in market.redsys.notification route', $e->getMessage());

            return [
                'status'    => 'error',
                'message'   => $e->getMessage(),
                'order'     => null
            ];
        }
    }

    /**
     * Actions to do when Redsys response is successful
     */
    public static function successful()
    {
        // log
        Log::info('Enter in RedsysService::successful service whit parameters', request()->all());

        $params = RedsysService::parameters();

        try {
            $parameters = Redsys::getMerchantParameters(request('Ds_MerchantParameters'));
            $DsResponse = $parameters['Ds_Response'];
            $DsResponse += 0;

            if (Redsys::check($params->key, request()->all()) && $DsResponse <= 99)
            {
                // get order ID
                $orderId =  str_replace(config('pulsar-market.order_id_suffix'), '', $parameters['Ds_Order']);

                $order = Order::find((int)$orderId);

                return $order;
            }
            else
            {
                // manage error?
                return null;
            }
        }
        catch (\Exception $e)
        {
            throw new \Exception('Exception to get Redsys successful response: ' . $e->getMessage());
        }
    }

    /**
     * Actions to do when Redsys response is error
     */
    public static function error()
    {
        // log
        Log::info('Enter in RedsysService::error service whit parameters: ', request()->all());

        try
        {
            $parameters = Redsys::getMerchantParameters(request('Ds_MerchantParameters'));

            // get order ID
            $orderId = str_replace(config('pulsar-market.order_id_suffix'), '', $parameters['Ds_Order']);

            $order = Order::find($orderId);

            // log
            OrderService::log($order->id, __('market::pulsar.message_redsys_payment_error'));

            return $order;
        }
        catch (\Exception $e)
        {
            throw new \Exception('To get Redsys error response: ' . $e->getMessage());
        }
    }

    /**
     * Get Redsys parameters
     */
    public static function parameters()
    {
        if(config('pulsar-market.redsys_mode') == 'test')
        {
            $parameters = [
                'merchantName'              => config('pulsar-market.redsys_test_merchant_name'),
                'descriptionTrans'          => config('pulsar-market.redsys_test_description_trans'),
                'merchantCode'              => config('pulsar-market.redsys_test_merchant_code'),
                'terminal'                  => config('pulsar-market.redsys_test_terminal'),
                'currency'                  => config('pulsar-market.redsys_test_currency'),
                'key'                       => config('pulsar-market.redsys_test_key'),
                'method'                    => config('pulsar-market.redsys_test_method'),
                'transactionType'           => config('pulsar-market.redsys_test_transaction_type'),
                'version'                   => config('pulsar-market.redsys_test_version')
            ];
        }
        elseif(config('pulsar-market.redsys_mode') == 'live')
        {
            $parameters = [
                'merchantName'              => config('pulsar-market.redsys_live_merchant_name'),
                'descriptionTrans'          => config('pulsar-market.redsys_live_description_trans'),
                'merchantCode'              => config('pulsar-market.redsys_live_merchant_code'),
                'terminal'                  => config('pulsar-market.redsys_live_terminal'),
                'currency'                  => config('pulsar-market.redsys_live_currency'),
                'key'                       => config('pulsar-market.redsys_live_key'),
                'method'                    => config('pulsar-market.redsys_live_method'),
                'transactionType'           => config('pulsar-market.redsys_live_transaction_type'),
                'version'                   => config('pulsar-market.redsys_live_version')
            ];
        }
        else
        {
            throw new \Exception('You must set MARKET_REDSYS_MODE like test or live');
        }

        $parameters['asyncResponseRoute']   = config('pulsar-market.redsys_async_response_route');
        $parameters['orderIdSuffix']        = config('pulsar-market.order_id_suffix');
        $parameters['environment']          = config('pulsar-market.redsys_mode');

        // routes
        $parameters['successfulRoute']      = config('pulsar-market.redsys_successful_route');
        $parameters['errorRoute']           = config('pulsar-market.redsys_error_route');

        return (object)$parameters;
    }

    /**
     * get order ID formated for Redsys with a 4 digits at least
     */
    private static function getOrderId($id)
    {
        if(strlen($id) < 4) $id = str_pad($id, 4, '0', STR_PAD_LEFT);
        return $id;
    }
}