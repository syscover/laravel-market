<?php namespace Syscover\Market\Services;

class PayPalService
{
    public static function createPayment($order)
    {
        // log register on order
        $order->setOrderLog(trans('market::pulsar.message_customer_go_to_paypal'));

        $orderId = self::getOrderId($order->id);

        return view('core::common.display', [
            'content' => self::executeRedirection($orderId . config('pulsar.market.orderIdSuffix'))
        ]);
    }

    /**
     * Execute redirection to PayPal
     *
     * @return string
     */
    private static function executeRedirection($order)
    {
        echo self::createForm($order);
        echo '<script>document.forms["paypalForm"].submit();</script>';
    }

    /**
     * Generate form html
     *
     * @return string
     */
    private static function createForm($order)
    {
        $form='
            <form id="paypalForm" action="' . route('createMarketPayPalPayment') . '" method="post">
                <input type="hidden" name="_token" value="' . csrf_token() . '"/>
                <input type="hidden" name="_order" value="' . $order . '"/>
            </form>
        ';

        return $form;
    }

    private static function getOrderId($id)
    {
        if(strlen($id) < 4) $id = str_pad($id, 4, '0', STR_PAD_LEFT);

        return $id;
    }
}