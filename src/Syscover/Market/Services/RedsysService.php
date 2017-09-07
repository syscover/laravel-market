<?php namespace Syscover\Market\Services;

use Ssheduardo\Redsys\Facades\Redsys;

class RedsysService
{
    public static function createPayment($order)
    {
        try{

            $params = RedsysService::parameters();

            Redsys::setEnviroment($order->environment);
            Redsys::setTitular($order->customer_name . ' ' . $order->customer_surname);
            Redsys::setTradeName($order->merchantName);
            Redsys::setProductDescription(__($order->descriptionTrans, ['order' => $order->id]));
            Redsys::setAmount($order->total);
            Redsys::setOrder($params->prefix . $order->id);
            Redsys::setMerchantcode($params->merchantCode);
            Redsys::setCurrency($params->currency);
            Redsys::setTransactiontype($params->transactionType);
            Redsys::setTerminal($params->terminal);
            Redsys::setMethod($params->method);                                                 // card or iupay
            Redsys::setVersion($params->version);
            Redsys::setMerchantSignature(Redsys::generateMerchantSignature($params->key));      // key

            Redsys::setNotification(route($params->redsysAsyncRoute));
            Redsys::setUrlOk(route($params->marketRedsysOK));
            Redsys::setUrlKo(route($params->marketRedsysKO));

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
        if(config('market.redsysMode') == 'test')
        {
            return (object)[
                'prefix'            => config('market.orderIdPrefix'),
                'environment'       => config('market.redsysMode'),
                'key'               => config('market.redsysTestKey'),
                'merchantName'      => config('market.redsysTestMerchantName'),
                'merchantCode'      => config('market.redsysTestMerchantCode'),
                'currency'          => config('market.redsysTestCurrency'),
                'terminal'          => config('market.redsysTestTerminal'),
                'method'            => config('market.redsysTestMethod'),
                'transactionType'   => config('market.redsysTestTransactionType'),
                'version'           => config('market.redsysTestVersion'),
                'descriptionTrans'  => config('market.redsysTestDescriptionTrans'),
                'redsysAsyncRoute'  => config('market.redsysAsyncRoute'),
                'redsysOKRoute'     => config('market.marketRedsysOK'),
                'redsysKORoute'     => config('market.marketRedsysKO'),

            ];
        }
        elseif(config('market.redsysMode') == 'live')
        {
            return (object)[
                'prefix'            => config('market.orderIdPrefix'),
                'environment'       => config('market.redsysMode'),
                'key'               => config('market.redsysLiveKey'),
                'merchantName'      => config('market.redsysLiveMerchantName'),
                'merchantCode'      => config('market.redsysLiveMerchantCode'),
                'currency'          => config('market.redsysLiveCurrency'),
                'terminal'          => config('market.redsysLiveTerminal'),
                'method'            => config('market.redsysLiveMethod'),
                'transactionType'   => config('market.redsysLiveTransactionType'),
                'version'           => config('market.redsysLiveVersion'),
                'descriptionTrans'  => config('market.redsysLiveDescriptionTrans'),
                'redsysAsyncRoute'  => config('market.redsysAsyncRoute'),
                'redsysOKRoute'     => config('market.marketRedsysOK'),
                'redsysKORoute'     => config('market.marketRedsysKO'),
            ];
        }
        else
        {
            throw new \Exception('You must set MARKET_REDSYS_MODE like test or live');
        }
    }
}