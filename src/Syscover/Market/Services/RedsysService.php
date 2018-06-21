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

            // params
            Redsys::setTitular($order->customer_name . ' ' . $order->customer_surname);
            Redsys::setEnviroment($params->environment);
            Redsys::setTradeName($params->merchantName);
            Redsys::setProductDescription(__($params->descriptionTrans, ['order' => $orderId . $params->suffix]));
            Redsys::setMerchantcode($params->merchantCode);
            Redsys::setTerminal($params->terminal);
            Redsys::setCurrency($params->currency);
            Redsys::setMerchantSignature(Redsys::generateMerchantSignature($params->key));      // key
            Redsys::setTransactiontype($params->transactionType);
            Redsys::setVersion($params->version);
            Redsys::setIdForm('marketPaymentForm');
            Redsys::setNotification(route($params->asyncResponseRoute));

            // set values
            Redsys::setOrder($orderId . $params->orderIdSuffix);
            Redsys::setAmount($order->getTotal(2, '.', ''));

            // set urls
            Redsys::setUrlOk(route($params->redsysSuccessfulRoute));
            Redsys::setUrlKo(route($params->redsysErrorRoute));

            // log
            OrderService::log($order->id, __('market::pulsar.message_customer_throw_to_redsys'));

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
     * Actions to do when Redsys response is successful
     */
    public static function successful($request)
    {
        // log
        Log::info('Enter in RedsysService::successful service whit parameters', $request->all());

        $params = RedsysService::parameters();

        try {
            $parameters = Redsys::getMerchantParameters($request->input('Ds_MerchantParameters'));
            $DsResponse = $parameters['Ds_Response'];
            $DsResponse += 0;

            if (Redsys::check($params->key, $request->all()) && $DsResponse <= 99)
            {
                // get order ID
                $orderId =  str_replace(config('pulsar-market.order_id_suffix'), '', $parameters['Ds_Order']);

                $order = Order::find((int)$orderId);

                // change order status
                $paymentMethod      = $order->payment_methods->where('lang_id', user_lang())->first();
                $order->status_id   = $paymentMethod->order_status_successful_id;
                $order->save();

                return $order;
            }
            else
            {
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
    public static function error($request)
    {
        // log
        Log::info('Enter in RedsysService::error service whit parameters', $request->all());

        try {
            $parameters = Redsys::getMerchantParameters($request->input('Ds_MerchantParameters'));

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
                'version'                   => config('pulsar-market.redsys_test_version'),
                'redsysSuccessfulRoute'     => config('pulsar-market.redsysSuccessfulRoute'),
                'redsysErrorRoute'          => config('pulsar-market.redsysErrorRoute'),

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
                'version'                   => config('pulsar-market.redsys_live_version'),
                'redsysSuccessfulRoute'     => config('pulsar-market.redsysSuccessfulRoute'),
                'redsysErrorRoute'          => config('pulsar-market.redsysErrorRoute'),
            ];
        }
        else
        {
            throw new \Exception('You must set MARKET_REDSYS_MODE like test or live');
        }

        $parameters['asyncResponseRoute']   = config('pulsar-market.redsys_async_response_route');
        $parameters['orderIdSuffix']        = config('pulsar-market.order_id_suffix');
        $parameters['environment']          = config('pulsar-market.redsys_mode');

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