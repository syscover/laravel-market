<?php namespace Syscover\Market\Services;

use Ssheduardo\Redsys\Facades\Redsys;

class RedsysService
{
    public static function createPayment($order)
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

            return view('core::common.display', [
                'content' => Redsys::executeRedirection()
            ]);
        }
        catch(\Exception $e)
        {
            // log register on order
            $order->setOrderLog(trans('market::pulsar.message_customer_go_to_tpv_error', [
                'error' => $e->getMessage()
            ]));

            echo $e->getMessage();
        }
    }

    public static function parameters()
    {
        if(config('pulsar.market.redsysMode') == 'test')
        {
            return (object)[
                'suffix'                    => config('pulsar.market.orderIdSuffix'),
                'environment'               => config('pulsar.market.redsysMode'),
                'key'                       => config('pulsar.market.redsysTestKey'),
                'merchantName'              => config('pulsar.market.redsysTestMerchantName'),
                'merchantCode'              => config('pulsar.market.redsysTestMerchantCode'),
                'currency'                  => config('pulsar.market.redsysTestCurrency'),
                'terminal'                  => config('pulsar.market.redsysTestTerminal'),
                'method'                    => config('pulsar.market.redsysTestMethod'),
                'transactionType'           => config('pulsar.market.redsysTestTransactionType'),
                'version'                   => config('pulsar.market.redsysTestVersion'),
                'descriptionTrans'          => config('pulsar.market.redsysTestDescriptionTrans'),
                'redsysAsyncRoute'          => config('pulsar.market.redsysAsyncRoute'),
                'redsysSuccessfulRoute'     => config('pulsar.market.redsysSuccessfulRoute'),
                'redsysErrorRoute'          => config('pulsar.market.redsysErrorRoute'),

            ];
        }
        elseif(config('market.redsysMode') == 'live')
        {
            return (object)[
                'suffix'                    => config('pulsar.market.orderIdSuffix'),
                'environment'               => config('pulsar.market.redsysMode'),
                'key'                       => config('pulsar.market.redsysLiveKey'),
                'merchantName'              => config('pulsar.market.redsysLiveMerchantName'),
                'merchantCode'              => config('pulsar.market.redsysLiveMerchantCode'),
                'currency'                  => config('pulsar.market.redsysLiveCurrency'),
                'terminal'                  => config('pulsar.market.redsysLiveTerminal'),
                'method'                    => config('pulsar.market.redsysLiveMethod'),
                'transactionType'           => config('pulsar.market.redsysLiveTransactionType'),
                'version'                   => config('pulsar.market.redsysLiveVersion'),
                'descriptionTrans'          => config('pulsar.market.redsysLiveDescriptionTrans'),
                'redsysAsyncRoute'          => config('pulsar.market.redsysAsyncRoute'),
                'redsysSuccessfulRoute'     => config('pulsar.market.redsysSuccessfulRoute'),
                'redsysErrorRoute'          => config('pulsar.market.redsysErrorRoute'),
            ];
        }
        else
        {
            throw new \Exception('You must set MARKET_REDSYS_MODE like test or live');
        }
    }

    private static function getOrderId($id)
    {
        if(strlen($id) < 4) $id = str_pad($id, 4, '0', STR_PAD_LEFT);

        return $id;
    }
}