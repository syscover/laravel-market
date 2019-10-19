<?php namespace Syscover\Market\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Syscover\Admin\Models\Attachment;
use Syscover\Market\Models\Order;
use Syscover\Market\Models\Product;

class StripePaymentService
{
    public static function createPayment($order)
    {
        return 
        '<form id="marketPaymentForm" method="post" action="'. route('pulsar.market.manage_stripe_payment') .'">
            ' . csrf_field() . '
            <input type="hidden" name="order" value="' . $order->id .'">
        </form>';
    }

    /**
     * Execute redirection to PayPal
     */
    private static function executeRedirection($redirectUrl)
    {
        $form =  self::createForm($redirectUrl);
        echo $form . '<script>document.forms["marketPaymentForm"].submit();</script>';
    }

    public static function managePayment(Request $request)
    {
        $orderId    = $request->input('order');
        $order      = Order::builder()->find($orderId);
        $lineItems  = [];

        foreach ($order->rows as $row) 
        {
            // TODO, crear un evento para controlar la carga de productos
            $product    = Product::builder()->find($row->product_id);
            $attachment = Attachment::builder()->where('object_id', $row->product->parent_id)->first();

            $rowAux = [
                'name'          => str_replace('<sup>&reg;</sup>', '', $product->name),
                'description'   => $product->description,
                'images'        => [$attachment->url],
                'amount'        => (int)($row->total)*100,
                'currency'      => 'eur',
                'quantity'      => (int)$row->quantity,
            ];

            $lineItems[] = $rowAux;
        }

        try 
        {
            // https://stripe.com/docs/api/checkout/sessions/create
            Stripe::setApiKey(config('services.stripe.secret'));
            $stripe_session = StripeSession::create([
                'payment_method_types'          => ['card'],
                'billing_address_collection'    => null,
                'locale'                        => user_lang(),
                'client_reference_id'           => $order->id,
                'customer_email'                => $order->email,
                'line_items'                    => $lineItems,
                'success_url'                   => route('web.stripe.success').'?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url'                    => route('web.stripe.cancel'),
            ]);

            // log
            OrderService::log($order->id, __('market::pulsar.message_customer_throw_to_stripe'));
            Log::info('Create form to throw to Stripe, order: ' . $order->id);
        }
        catch (\Exception $e) 
        {
            // log register on order
            OrderService::log($order->id, __('market::pulsar.message_customer_redsys_error', ['error' => $e->getMessage()]));
            Log::critical($e->getMessage());

            throw new \Exception('Exception to create payment stripe: ' . $e->getMessage());
        }

        return  '<script src="https://js.stripe.com/v3/"></script> 
                <script>
                    var stripe = Stripe(\'' . config('services.stripe.key') . '\');
                    stripe.redirectToCheckout({
                        // Make the id field from the Checkout Session creation API response
                        // available to this file, so you can provide it as parameter here
                        // instead of the CHECKOUT_SESSION_ID placeholder.
                        sessionId: \'' . $stripe_session->id . '\'
                    }).then(function (result) {
                        // If `redirectToCheckout` fails due to a browser or network
                        // error, display the localized error message to your customer
                        // using `result.error.message`.
                        console.log(result);
                    });
                </script>';
    }

    /**
     * Actions to do when Stripe response is successful
     */
    public static function successful(Request $request)
    {
        try 
        {
            Stripe::setApiKey(config('services.stripe.secret'));
            $stripeSession = StripeSession::retrieve($request->input('session_id'));

            $stripeUserMail     = $stripeSession->customer_email;
            $stripeOrderId      = $stripeSession->client_reference_id;
            $stripeLocale       = $stripeSession->locale;


            $order = Order::builder()->find($stripeOrderId);

            // change order status
            MarketableService::setOrderPaymentSuccessful($order);
        }
        catch (\Exception $ex) 
        {    
        }
    }
}
