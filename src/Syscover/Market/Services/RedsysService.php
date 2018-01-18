<?php namespace Syscover\Market\Services;

use Illuminate\Support\Facades\Log;
use Ssheduardo\Redsys\Facades\Redsys;
use Syscover\Market\Models\Order;

class RedsysService
{
    /**
     * Create payment across Redsys
     *
     * @param $order
     * @param $xhr
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public static function createPayment($order, $xhr)
    {
        try{
            $params     = RedsysService::parameters();
            $orderId    = RedsysService::getOrderId($order->id);

            Redsys::setAmount($order->getTotal(2, '.', ''));
            Redsys::setOrder($orderId . $params->suffix);
            Redsys::setMerchantcode($params->merchantCode);
            Redsys::setCurrency($params->currency);
            Redsys::setTransactiontype($params->transactionType);
            Redsys::setTerminal($params->terminal);
            Redsys::setNotification(route($params->redsysAsyncRoute));
            Redsys::setUrlOk(route($params->redsysSuccessfulRoute));
            Redsys::setUrlKo(route($params->redsysErrorRoute));
            Redsys::setVersion($params->version);
            Redsys::setTradeName($params->merchantName);
            Redsys::setTitular($order->customer_name . ' ' . $order->customer_surname);
            Redsys::setProductDescription(__($params->descriptionTrans, ['order' => $orderId . $params->suffix]));
            Redsys::setEnviroment($params->environment);
            Redsys::setMerchantSignature(Redsys::generateMerchantSignature($params->key));      // key

            if($xhr)
            {
                return Redsys::createForm();
            }
            else {
                return view('core::common.display', [
                    'content' => Redsys::executeRedirection()
                ]);
            }
        }
        catch(\Exception $e) {
            // log register on order
            $order->setOrderLog(trans('market::pulsar.message_customer_go_to_tpv_error', [
                'error' => $e->getMessage()
            ]));

            echo $e->getMessage();
        }
    }

    /**
     * Actions to do when Redsys response is successful
     *
     * @param $request
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|null|static|static[]
     * @throws \Exception
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
                $orderId =  str_replace(config('pulsar-market.orderIdSuffix'), '', $parameters['Ds_Order']);

                $order = Order::find($orderId);

                // change order status
                $paymentMethod      = $order->payment_method;
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
            throw new \Exception('To get Redsys successful response: ' . $e->getMessage());
        }
    }

    /**
     * Actions to do when Redsys response is error
     *
     * @param $request
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|null|static|static[]
     * @throws \Exception
     */
    public static function error($request)
    {
        // log
        Log::info('Enter in RedsysService::error service whit parameters', $request->all());

        try {
            $parameters = Redsys::getMerchantParameters($request->input('Ds_MerchantParameters'));

            // get order ID
            $orderId = str_replace(config('pulsar-market.orderIdSuffix'), '', $parameters['Ds_Order']);

            $order = Order::find($orderId);

            // set log error in order
            $order->setOrderLog(__('market::pulsar.message_redsys_payment_error', [
                'error' => $parameters['Ds_Response']
            ]));

            return $order;

        }
        catch (\Exception $e)
        {
            throw new \Exception('To get Redsys error response: ' . $e->getMessage());
        }
    }

    /**
     * Get Redsys parameters
     *
     * @return object
     * @throws \Exception
     */
    public static function parameters()
    {
        if(config('pulsar-market.redsysMode') == 'test')
        {
            return (object)[
                'suffix'                    => config('pulsar-market.orderIdSuffix'),
                'environment'               => config('pulsar-market.redsysMode'),
                'key'                       => config('pulsar-market.redsysTestKey'),
                'merchantName'              => config('pulsar-market.redsysTestMerchantName'),
                'merchantCode'              => config('pulsar-market.redsysTestMerchantCode'),
                'currency'                  => config('pulsar-market.redsysTestCurrency'),
                'terminal'                  => config('pulsar-market.redsysTestTerminal'),
                'method'                    => config('pulsar-market.redsysTestMethod'),
                'transactionType'           => config('pulsar-market.redsysTestTransactionType'),
                'version'                   => config('pulsar-market.redsysTestVersion'),
                'descriptionTrans'          => config('pulsar-market.redsysTestDescriptionTrans'),
                'redsysAsyncRoute'          => config('pulsar-market.redsysAsyncRoute'),
                'redsysSuccessfulRoute'     => config('pulsar-market.redsysSuccessfulRoute'),
                'redsysErrorRoute'          => config('pulsar-market.redsysErrorRoute'),

            ];
        }
        elseif(config('market.redsysMode') == 'live')
        {
            return (object)[
                'suffix'                    => config('pulsar-market.orderIdSuffix'),
                'environment'               => config('pulsar-market.redsysMode'),
                'key'                       => config('pulsar-market.redsysLiveKey'),
                'merchantName'              => config('pulsar-market.redsysLiveMerchantName'),
                'merchantCode'              => config('pulsar-market.redsysLiveMerchantCode'),
                'currency'                  => config('pulsar-market.redsysLiveCurrency'),
                'terminal'                  => config('pulsar-market.redsysLiveTerminal'),
                'method'                    => config('pulsar-market.redsysLiveMethod'),
                'transactionType'           => config('pulsar-market.redsysLiveTransactionType'),
                'version'                   => config('pulsar-market.redsysLiveVersion'),
                'descriptionTrans'          => config('pulsar-market.redsysLiveDescriptionTrans'),
                'redsysAsyncRoute'          => config('pulsar-market.redsysAsyncRoute'),
                'redsysSuccessfulRoute'     => config('pulsar-market.redsysSuccessfulRoute'),
                'redsysErrorRoute'          => config('pulsar-market.redsysErrorRoute'),
            ];
        }
        else
        {
            throw new \Exception('You must set MARKET_REDSYS_MODE like test or live');
        }
    }

    /**
     * get order ID formated for Redsys
     *
     * @param $id
     * @return string
     */
    private static function getOrderId($id)
    {
        if(strlen($id) < 4) $id = str_pad($id, 4, '0', STR_PAD_LEFT);

        return $id;
    }
}