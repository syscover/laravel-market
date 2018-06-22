<?php namespace Syscover\Market\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use PayPal\Api\Payment;
use PayPal\Api\PaymentExecution;
use Syscover\Market\Models\Order;

class PayPalController extends BaseController
{
    public function successful(Request $request)
    {
        $paymentId  = $request->input('paymentId');
        $payment    = Payment::get($paymentId, $this->apiContext);
        $execution  = new PaymentExecution();
        $execution->setPayerId($request->input('PayerID'));

        try
        {
            $response = $payment->execute($execution, $this->apiContext);
        }
        catch(\Exception $e)
        {
            throw new \Exception('There are any error to create PayPal response: ' . $e->getMessage());
        }

        $order = Order::builder()->where('transaction_id',  $request->input('paymentId'))->first();

        if($response->getState() === 'approved')
        {
            if(! empty($order->order_status_successful_id))
            {
                // set next status to complete payment method
                Order::where('id', $order->id)
                    ->update([
                        'status_id' => $order->order_status_successful_id
                    ]);
            }

            $route = route(config('pulsar-market.paypal_successful_route'));
        }
        else
        {
            $route = route(config('pulsar-market.paypal_error_route'));
        }

        echo '
                <form id="redirect_paypal_form" action="' . $route . '" method="post">
                    <input type="hidden" name="_token" value="' . csrf_token() . '">
                    <input type="hidden" name="order" value="' . $order->id . '">
                    <input type="hidden" name="state" value="' . $response->getState() . '">
                </form>
                <script>document.getElementById("redirect_paypal_form").submit();</script>
            ';
    }

    public function error(Request $request)
    {

    }
}