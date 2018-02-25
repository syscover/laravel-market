<?php namespace Syscover\Market\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Log;
use PayPal\Rest\ApiContext;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Api\Amount;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\RedirectUrls;
use PayPal\Api\PaymentExecution;
use PayPal\Api\Transaction;
use Syscover\Market\Models\Order;

class PayPalController extends BaseController
{
    private $apiContext;
    private $webProfile;

    public function __construct(Request $request)
    {
        // Set mode
        if(config('pulsar-market.payPalMode') == 'live')
        {
            $this->webProfile   = config('pulsar-market.payPalLiveWebProfile');
            $clientID           = config('pulsar-market.payPalLiveClientId');
            $secret             = config('pulsar-market.payPalLiveSecret');
        }
        elseif(config('pulsar-market.payPalMode') == 'sandbox')
        {
            $this->webProfile   = config('pulsar-market.payPalSandboxWebProfile');
            $clientID           = config('pulsar-market.payPalSandboxClientId');
            $secret             = config('pulsar-market.payPalSandboxSecret');
        }
        else
        {
            throw new \Exception('You must set MARKET_PAYPAL_MODE like sandbox or live');
        }

        // init PayPal API Context
        $this->apiContext   = new ApiContext(new OAuthTokenCredential($clientID, $secret));

        // SDK configuration
        $this->apiContext->setConfig([
            'mode'                      => config('pulsar-market.payPalMode'),    // Specify mode, sandbox or live
            'http.ConnectionTimeOut'    => 30,                                  // Specify the max request time in seconds
            'log.LogEnabled'            => true,                                // Whether want to log to a file
            'log.FileName'              => storage_path() . '/logs/paypal.log', // Specify the file that want to write on
            'log.LogLevel'              => 'FINE'                               // Available option 'FINE', 'INFO', 'WARN' or 'ERROR', Logging is most verbose in the 'FINE' level and decreases as you proceed towards ERROR
        ]);
    }

    public function createPayment(Request $request)
    {
        if($request->has('_order'))
        {
            $order     = Order::builder()->where('market_order.id',  $request->input('_order'))->first();
            $orderRows = $order->rows;
        }
        else
        {
            throw new \Exception('You must establish a order to create PayPal payment');
        }

        $payer = new Payer();
        $payer->setPaymentMethod('paypal');

        //***********************
        //** create products
        //***********************
        $products = [];
        foreach($orderRows as $row)
        {
            $item = new Item();
            $item->setName(e($row->name))                                                  // product name
                ->setCurrency('EUR')                                                    // currency
                ->setQuantity(intval($row->quantity))                                   // quantity
                ->setPrice($row->total_without_discounts / $row->quantity);             // unit price

            $products[] = $item;
        }

        //***********************
        //** shipping
        //***********************
        if($order->shipping_amount > 0)
        {
            $item = new Item();
            $item->setName(__(Lang::has('common.paypal_shipping_description') ? 'common.paypal_shipping_description' : 'market::pulsar.paypal_shipping_description'))
                ->setCurrency('EUR')                        // currency
                ->setQuantity(1)                            // quantity
                ->setPrice($order->shipping_amount);        // price

            $products[] = $item;
        }

        //***********************
        //** discounts
        //***********************
        $discounts = $order->discounts;
        foreach ($discounts as $discount)
        {
            if($discount->discount_amount > 0)
            {
                $item = new Item();
                $item->setName(collect($discount->names)->where('id', user_lang())->first() ? collect($discount->names)->where('id', user_lang())->first()['value'] : trans_choice('core::common.discount', 1))
                    ->setCurrency('EUR')                            // currency
                    ->setQuantity(1)                                // quantity
                    ->setPrice($discount->discount_amount * -1);    // price

                $products[] = $item;
            }
        }

        // products list
        $itemList = new ItemList();
        $itemList->setItems($products);

        // total charge
        $amount = new Amount();
        $amount
            ->setCurrency('EUR')
            ->setTotal($order->total);

        // create transaction
        $transaction = new Transaction();
        $transaction
            ->setAmount($amount)
            ->setItemList($itemList)
            ->setDescription(__(Lang::has('common.paypal_item_list_description')?
                'common.paypal_item_list_description' : 'market:pulsar.paypal_item_list_description'));

        // config URL request
        $redirectUrls = new RedirectUrls();
        $redirectUrls
            ->setReturnUrl(route('market.paypal.response'))
            ->setCancelUrl(route('market.paypal.response'));

        // create payment
        $payment = new Payment();
        $payment
            ->setIntent('sale')
            //->setExperienceProfileId($this->webProfile)
            ->setPayer($payer)
            ->setRedirectUrls($redirectUrls)
            ->setTransactions([$transaction]);

        try
        {
            $payment->create($this->apiContext);
        }
        catch(\Exception $ex)
        {
            throw new \Exception('There are any error to create PayPal payment: ' . $ex->getMessage());
        }

        foreach($payment->getLinks() as $link)
        {
            if($link->getRel() === 'approval_url')
            {
                $redirectUrl = $link->getHref();
                break;
            }
        }

        // record payment id on order
        Order::where('id', $order->id)
            ->update([
                'transaction_id' => $payment->getId()
            ]);

        if(isset($redirectUrl))
        {
            return redirect()->away($redirectUrl);
        }

        return redirect()->route('home')
            ->with('error', 'Unknown error occurred');
    }

    public function paymentResponse(Request $request)
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

            $route = route(config('pulsar-market.payPalSuccessfulRoute'));
        }
        else
        {
            $route = route(config('pulsar-market.payPalErrorRoute'));
        }

        echo '
                <form id="redirect_paypal_form" action="' . $route . '" method="post">
                    <input type="hidden" name="_token" value="' . csrf_token() . '" />
                    <input type="hidden" name="order" value="' . $order->id . '" />
                    <input type="hidden" name="state" value="' . $response->getState() . '" />
                </form>
                <script>document.getElementById("redirect_paypal_form").submit();</script>
            ';
    }
}