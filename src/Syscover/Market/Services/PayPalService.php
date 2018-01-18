<?php namespace Syscover\Market\Services;

use Syscover\Market\Models\Order;

class PayPalService
{
    /**
     * Create payment across PayPal
     *
     * @param $order
     * @param $xhr
     * @return string
     */
    public static function createPayment($order, $xhr)
    {
        // log register on order
        $order->setOrderLog(trans('market::pulsar.message_customer_go_to_paypal'));

        if($xhr) {
            return self::createForm($order->id);
        }
        else {
            return self::executeRedirection($order->id);
        }
    }

    /**
     * Execute redirection to PayPal
     *
     * @return string
     */
    private static function executeRedirection($orderId)
    {
        $form =  self::createForm($orderId);
        echo $form . '<script>document.forms["marketPaymentForm"].submit();</script>';
    }

    /**
     * Generate form html
     *
     * @return string
     */
    private static function createForm($orderId)
    {
        $form='
            <form id="marketPaymentForm" action="' . route('market.paypal.create.payment') . '" method="post">
                <input type="hidden" name="_token" value="' . csrf_token() . '">
                <input type="hidden" name="_order" value="' . $orderId . '">
            </form>
        ';

        return $form;
    }

    /**
     * Actions to do when PayPal response is successful
     *
     * @param $request
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|null|static|static[]
     */
    public static function successful($request)
    {
        $order = Order::find($request->input('order'));
        $order->setOrderLog(__('market::pulsar.message_paypal_payment_successful'));

        // change order status
        $paymentMethod      = $order->payment_methods->where('lang_id', user_lang())->first();
        $order->status_id   = $paymentMethod->order_status_successful_id;
        $order->save();

        return $order;
    }

    /**
     * Actions to do when PayPal response is error
     *
     *
     * @param $request
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|null|static|static[]
     */
    public static function error($request)
    {
        $order = Order::find($request->input('order'));
        $order->setOrderLog(__('market::pulsar.message_paypal_payment_error'));

        return $order;
    }
}