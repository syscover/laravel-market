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
use Syscover\Market\Models\Order;

class PayPalService
{
    public static function createPayment($order, $xhr)
    {
        $params     = PayPalService::parameters();

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
            $payment->create($params->apiContext);
        }
        catch(\Exception $e)
        {
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

        if(isset($redirectUrl))
        {
            return redirect()->away($redirectUrl);
        }
        else
        {
            throw new \Exception('There are any error to create PayPal payment');
        }
    }

    /**
     * Get Paypal parameters
     */
    public static function parameters()
    {
        if(config('pulsar-market.paypal_mode') == 'sandbox')
        {
            $parameters = [
                'webProfile'                => config('pulsar-market.paypal_sandbox_web_profile'),
                'clientID'                  => config('pulsar-market.paypal_sandbox_client_id'),
                'secret'                    => config('pulsar-market.paypal_sandbox_secret')
            ];
        }
        elseif(config('pulsar-market.paypal_mode') == 'live')
        {
            $parameters = [
                'webProfile'                => config('pulsar-market.paypal_live_web_profile'),
                'clientID'                  => config('pulsar-market.paypal_live_client_id'),
                'secret'                    => config('pulsar-market.paypal_live_secret')
            ];
        }
        else
        {
            throw new \Exception('You must set MARKET_PAYPAL_MODE like sandbox or live');
        }

        $parameters['apiContext']           = new ApiContext(new OAuthTokenCredential($parameters['clientID'], $parameters['secret']));
        // SDK configuration
        $parameters['apiContext']->setConfig([
            'mode'                          => config('pulsar-market.paypal_mode'),    // Specify mode, sandbox or live
            'http.ConnectionTimeOut'        => 30,                                          // Specify the max request time in seconds
            'log.LogEnabled'                => true,                                        // Whether want to log to a file
            'log.FileName'                  => storage_path() . '/logs/paypal.log',         // Specify the file that want to write on
            'log.LogLevel'                  => 'FINE'                                       // Available option 'FINE', 'INFO', 'WARN' or 'ERROR', Logging is most verbose in the 'FINE' level and decreases as you proceed towards ERROR
        ]);

        return (object)$parameters;
    }







    /******************************************************************************************************************************************************************

    /**
     * Create payment across PayPal
     *
     * @param $order
     * @param $xhr
     * @return string
     */
    public static function createPayment_old($order, $xhr)
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