<?php namespace Syscover\Market\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Lang;
use PayPal\Rest\ApiContext;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Api\Payer;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Amount;
use PayPal\Api\Transaction;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Payment;
use PayPal\Api\PaymentExecution;
use Syscover\Market\Models\Order;

class PayPalPaymentService
{
    public static function createPayment($order, $xhr)
    {
        // get paypal parameters
        $payPalParameters = self::parameters();

        $payer = new Payer();
        $payer->setPaymentMethod('paypal');

        //***********************
        //** create products
        //***********************
        $products = [];
        foreach($order->rows as $row)
        {
            $item = new Item();
            $item->setName($row->name)                                          // product name
            ->setCurrency('EUR')                                       // currency
            ->setQuantity(intval($row->quantity))                               // quantity
            ->setPrice($row->total_without_discounts / $row->quantity);   // unit price

            $products[] = $item;
        }

        //***********************
        //** shipping
        //***********************
        if($order->shipping_amount > 0)
        {
            $item = new Item();
            $item->setName(__(Lang::has('common.paypal_shipping_description') ? 'common.paypal_shipping_description' : 'market::pulsar.paypal_shipping_description'))
                ->setCurrency('EUR')            // currency
                ->setQuantity(1)                // quantity
                ->setPrice($order->shipping_amount);    // price

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
                $item->setName(
                    collect($discount->names)->where('id', user_lang())->first() ?
                        collect($discount->names)->where('id', user_lang())->first()['value'] : trans_choice('core::common.discount', 1)
                )
                    ->setCurrency('EUR')                          // currency
                    ->setQuantity(1)                              // quantity
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
            ->setDescription(__(Lang::has('common.paypal_item_list_description') ? 'common.paypal_item_list_description' : 'market::pulsar.paypal_item_list_description'));

        // config URL request
        $redirectUrls = new RedirectUrls();
        $redirectUrls
            ->setReturnUrl(route('pulsar.market.paypal_payment_successful'))
            ->setCancelUrl(route('pulsar.market.paypal_payment_error', ['id' => $order->id]));

        // create payment
        $payment = new Payment();
        $payment
            ->setIntent('sale')
            ->setPayer($payer)
            ->setRedirectUrls($redirectUrls)
            ->setTransactions([$transaction]);

        // set payment experience
        if($payPalParameters->web_profile) $payment->setExperienceProfileId($payPalParameters->web_profile);

        try
        {
            $payment->create(PayPalCoreService::getApiContext());
        }
        catch(\Exception $e)
        {
            Log::critical($e->getMessage());
            throw new \Exception('There are any error to create PayPal payment: ' . $e->getMessage());
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
        $order->transaction_id = $payment->getId();
        $order->save();

        // log
        OrderService::log($order->id, __('market::pulsar.message_customer_throw_to_paypal'));
        Log::info('Create form to throw to PayPal, order: ' . $order->id);

        if(isset($redirectUrl))
        {
            if($xhr)
            {
                return self::createForm($redirectUrl);
            }
            else
            {
                return view('core::common.display', ['content' => self::executeRedirection($redirectUrl)]);
            }
        }
        else
        {
            throw new \Exception('There are any error to create PayPal payment');
        }
    }

    /**
     * Generate form html
     */
    private static function createForm($url)
    {
        return '<form id="marketPaymentForm" action="' . $url . '" method="post"></form>';
    }

    /**
     * Execute redirection to PayPal
     */
    private static function executeRedirection($redirectUrl)
    {
        $form =  self::createForm($redirectUrl);
        echo $form . '<script>document.forms["marketPaymentForm"].submit();</script>';
    }


    /**
     * Get Paypal parameters
     */
    private static function parameters()
    {
        if(config('pulsar-market.paypal_mode') == 'sandbox')
        {
            $parameters = [
                'web_profile' => config('pulsar-market.paypal_sandbox_web_profile'),
            ];
        }
        elseif(config('pulsar-market.paypal_mode') == 'live')
        {
            $parameters = [
                'web_profile' => config('pulsar-market.paypal_live_web_profile'),
            ];
        }
        else
        {
            throw new \Exception('You must set MARKET_PAYPAL_MODE like sandbox or live');
        }

        return (object) $parameters;
    }

    /**
     * Actions to do when PayPal response is successful
     */
    public static function successful()
    {
        $apiContext = PayPalCoreService::getApiContext();

        $paymentId  = request('paymentId');
        $payment    = Payment::get($paymentId, $apiContext);
        $execution  = new PaymentExecution();
        $execution->setPayerId(request('PayerID'));

        try
        {
            $response = $payment->execute($execution, $apiContext);
        }
        catch(\Exception $e)
        {
            throw new \Exception('Exception to get PayPal successful response: ' . $e->getMessage());
        }

        $order = Order::builder()->where('transaction_id',  request('paymentId'))->first();

        if($response->getState() === 'approved')
        {
            // change order status
            $paymentMethod      = $order->payment_methods->where('lang_id', user_lang())->first();
            $order->status_id   = $paymentMethod->order_status_successful_id;
            $order->save();

            // log register on order
            OrderService::log($order->id, __('market::pulsar.message_paypal_payment_successful'));

            return $order;
        }
        else
        {
            // manage error?
            return null;
        }
    }

    /**
     * Actions to do when PayPal response is error
     */
    public static function error($id)
    {
        // log
        Log::info('Enter in PayPalPaymentService::error service whit parameters: ', request()->all());

        // get order ID
        $order = Order::find($id);

        // log
        OrderService::log($order->id, __('market::pulsar.message_paypal_payment_error'));

        return $order;
    }
}