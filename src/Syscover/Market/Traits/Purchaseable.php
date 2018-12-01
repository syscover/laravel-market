<?php namespace Syscover\Market\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Syscover\Crm\Services\AddressService;
use Syscover\Crm\Services\CustomerService;
use Syscover\Market\Models\CartPriceRule;
use Syscover\Market\Services\CustomerDiscountHistoryService;
use Syscover\Market\Services\OrderRowService;
use Syscover\Market\Services\OrderService;
use Syscover\Market\Services\PaymentMethodService;
use Syscover\ShoppingCart\Facades\CartProvider;

trait Purchaseable
{
    public function purchase(Request $request)
    {
        // check that cart has products
        if(CartProvider::instance()->getCartItems()->count() == 0)
        {
            return response()->json([
                'status'    => 'error',
                'message'   => 'Shopping cart is empty'
            ]);
        }

        // flag to know if is user authenticate
        $isUser             = false;
        $auth               = collect($request->input('auth'));
        $shippingAddress    = collect($request->input('shippingAddress'));
        $invoiceAddress     = collect();

        // is authenticate
        if(Auth::guard('crm')->check())
        {
            // get customer
            $customer   = Auth::guard('crm')->user();
            $isUser     = true;
        }
        else
        {
            // create customer
            $customer = CustomerService::create([
                'lang_id'   => user_lang(),
                'group_id'  => 1,
                'name'      => $shippingAddress->get('name'),
                'surname'   => $shippingAddress->get('surname'),
                'mobile'    => $shippingAddress->get('mobile'),
                'user'      => $auth->get('username'),
                'password'  => $auth->get('password'),
                'email'     => $auth->get('username')
            ]);

            // login new customer
            Auth::guard('crm')->login($customer);
        }

        // save shipping direction
        if($request->input('saveShippingAddress') || ! $isUser)
        {
            AddressService::create([
                'type_id'               => 1,
                'customer_id'           => $customer->id,
                'alias'                 => $request->input('shippingAddressAlias') ? $request->input('shippingAddressAlias') : __('core::common.main'),
                'lang_id'               => user_lang(),
                'company'               => $shippingAddress->get('company'),
                'name'                  => $shippingAddress->get('name'),
                'surname'               => $shippingAddress->get('surname'),
                'mobile'                => $shippingAddress->get('mobile'),
                'country_id'            => $shippingAddress->get('country_id'),
                'territorial_area_1_id' => $shippingAddress->get('territorial_area_1_id'),
                'territorial_area_2_id' => $shippingAddress->get('territorial_area_2_id'),
                'territorial_area_3_id' => $shippingAddress->get('territorial_area_3_id'),
                'zip'                   => $shippingAddress->get('zip'),
                'locality'              => $shippingAddress->get('locality'),
                'address'               => $shippingAddress->get('address'),
                'favorite'              => ! $isUser
            ]);
        }

        // save invoice direction
        if($request->input('saveInvoiceAddress') || ! $isUser)
        {
            if($request->input('hasInvoice'))
            {
                $invoiceAddress = collect($request->input('invoiceAddress'));

                AddressService::create([
                    'type_id'               => 2,
                    'customer_id'           => $customer->id,
                    'alias'                 => $request->input('invoiceAddressAlias') ? $request->input('invoiceAddressAlias') : __('core::common.main'),
                    'lang_id'               => user_lang(),
                    'company'               => $invoiceAddress->get('company'),
                    'tin'                   => $invoiceAddress->get('tin'),
                    'name'                  => $invoiceAddress->get('name'),
                    'surname'               => $invoiceAddress->get('surname'),
                    'mobile'                => $invoiceAddress->get('mobile'),
                    'country_id'            => $invoiceAddress->get('country_id'),
                    'territorial_area_1_id' => $invoiceAddress->get('territorial_area_1_id'),
                    'territorial_area_2_id' => $invoiceAddress->get('territorial_area_2_id'),
                    'territorial_area_3_id' => $invoiceAddress->get('territorial_area_3_id'),
                    'zip'                   => $invoiceAddress->get('zip'),
                    'locality'              => $invoiceAddress->get('locality'),
                    'address'               => $invoiceAddress->get('address'),
                    'favorite'              => ! $isUser
                ]);
            }
            else
            {
                AddressService::create([
                    'type_id'               => 2,
                    'customer_id'           => $customer->id,
                    'alias'                 => __('web.main'),
                    'lang_id'               => user_lang(),
                    'company'               => $shippingAddress->get('company'),
                    'tin'                   => null,
                    'name'                  => $shippingAddress->get('name'),
                    'surname'               => $shippingAddress->get('surname'),
                    'mobile'                => $shippingAddress->get('mobile'),
                    'country_id'            => $shippingAddress->get('country_id'),
                    'territorial_area_1_id' => $shippingAddress->get('territorial_area_1_id'),
                    'territorial_area_2_id' => $shippingAddress->get('territorial_area_2_id'),
                    'territorial_area_3_id' => $shippingAddress->get('territorial_area_3_id'),
                    'zip'                   => $shippingAddress->get('zip'),
                    'locality'              => $shippingAddress->get('locality'),
                    'address'               => $shippingAddress->get('address'),
                    'favorite'              => true
                ]);
            }
        }

        $dataOrder = array_merge(OrderService::initDataOrder($customer), [
            // data order
            'payment_method_id'                 => $request->input('paymentMethod'),
            'status_id'                         => 1, // status_id: 1 Pending payment, status_id: 2 Payment Confirmed

            // shipping
            'has_shipping'                      => true,
            'shipping_company'                  => $shippingAddress->get('company'),
            'shipping_name'                     => $shippingAddress->get('name'),
            'shipping_surname'                  => $shippingAddress->get('surname'),
            'shipping_mobile'                   => $shippingAddress->get('mobile'),
            'shipping_country_id'               => $shippingAddress->get('country_id'),
            'shipping_territorial_area_1_id'    => $shippingAddress->get('territorial_area_1_id'),
            'shipping_territorial_area_2_id'    => $shippingAddress->get('territorial_area_2_id'),
            'shipping_territorial_area_3_id'    => $shippingAddress->get('territorial_area_3_id'),
            'shipping_zip'                      => $shippingAddress->get('zip'),
            'shipping_locality'                 => $shippingAddress->get('locality'),
            'shipping_address'                  => $shippingAddress->get('address'),
            'shipping_comments'                 => $request->input('shippingComments'),

            // invoice
            'has_invoice'                       => $request->input('hasInvoice'),
            'invoice_company'                   => $invoiceAddress->get('company'),
            'invoice_tin'                       => $invoiceAddress->get('tin'),
            'invoice_name'                      => $invoiceAddress->get('name'),
            'invoice_surname'                   => $invoiceAddress->get('surname'),
            'invoice_mobile'                    => $invoiceAddress->get('mobile'),
            'invoice_country_id'                => $invoiceAddress->get('country_id'),
            'invoice_territorial_area_1_id'     => $invoiceAddress->get('territorial_area_1_id'),
            'invoice_territorial_area_2_id'     => $invoiceAddress->get('territorial_area_2_id'),
            'invoice_territorial_area_3_id'     => $invoiceAddress->get('territorial_area_3_id'),
            'invoice_zip'                       => $invoiceAddress->get('zip'),
            'invoice_locality'                  => $invoiceAddress->get('locality'),
            'invoice_address'                   => $invoiceAddress->get('address'),
        ]);

        // create order
        $order = OrderService::create($dataOrder);

        // set order rows
        OrderRowService::insert(
            OrderRowService::getDataOrderRow($order)
        );

        // register discount history
        if(CartProvider::instance()->getPriceRules()->count() > 0)
        {
            // save price rule in customer discount history
            CustomerDiscountHistoryService::insert(
                CustomerDiscountHistoryService::getDataCustomerDiscountHistory($customer, $order)
            );

            // increment total used in cart price rule
            CartPriceRule::whereIn('id', CartProvider::instance()->getPriceRules()->pluck('id'))->increment('total_uses');
        }

        // destroy shopping cart
        CartProvider::instance()->destroy();

        // launch request to payment method for launch payment
        $paymentForm = PaymentMethodService::createPaymentMethod($request->input('paymentMethod'), $order, true);

        return response()->json([
            'status'        => 200,
            'statusText'    => 'success',
            'order'         => $order,
            'form'          => $paymentForm
        ]);
    }
}
