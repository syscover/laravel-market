<?php namespace Syscover\Market\Services;

use Illuminate\Support\Facades\Log;
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
        // log
        OrderService::log($order->id, __('market::pulsar.message_customer_throw_to_paypal'));
        Log::info('Create form to submit PayPalController, order: ' . $order->id);

        if($xhr)
        {
            return self::createForm($order->id);
        }
        else
        {
            return self::executeRedirection($order->id);
        }
    }

    /**
     * Execute redirection to PayPal
     */
    private static function executeRedirection($id)
    {
        $form =  self::createForm($id);
        echo $form . '<script>document.forms["marketPaymentForm"].submit();</script>';
    }

    /**
     * Generate form html
     */
    private static function createForm($id)
    {
        $form='
            <form id="marketPaymentForm" action="' . route('pulsar.market.paypal_create_payment') . '" method="post">
                <input type="hidden" name="_token" value="' . csrf_token() . '">
                <input type="hidden" name="_order" value="' . $id . '">
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