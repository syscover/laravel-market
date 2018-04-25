<?php namespace Syscover\Market\Services;

use Syscover\Market\Models\PaymentMethod;

class PaymentMethodService
{
    public static function create($object)
    {
        PaymentMethodService::check($object);

        if(empty($object['id'])) $object['id'] = next_id(PaymentMethod::class);

        $object['data_lang'] = PaymentMethod::addDataLang($object['lang_id'], $object['id']);
        
        return PaymentMethod::create(PaymentMethodService::builder($object));
    }

    public static function update($object)
    {
        PaymentMethodService::check($object);
        PaymentMethod::where('ix', $object['ix'])->update(PaymentMethodService::builder($object));
        PaymentMethod::where('id', $object['id'])->update(PaymentMethodService::builder($object, ['active', 'sort']));

        return PaymentMethod::find($object['ix']);
    }

    private static function builder($object, $filterKeys = null)
    {
        $object = collect($object);
        if($filterKeys) $object = $object->only($filterKeys);

        $data = [];

        if($object->has('id'))                          $data['id'] = $object->get('id');
        if($object->has('lang_id'))                     $data['lang_id'] = $object->get('lang_id');
        if($object->has('name'))                        $data['name'] = $object->get('name');
        if($object->has('order_status_successful_id'))  $data['order_status_successful_id'] = $object->get('order_status_successful_id');
        if($object->has('minimum_price'))               $data['minimum_price'] = $object->get('minimum_price');
        if($object->has('maximum_price'))               $data['maximum_price'] = $object->get('maximum_price');
        if($object->has('sort'))                        $data['sort'] = $object->get('sort');
        if($object->has('active'))                      $data['active'] = $object->get('active');
        if($object->has('data_lang'))                   $data['data_lang'] = $object->get('data_lang');

        return $data;
    }

    private static function check($object)
    {
        if(empty($object['lang_id']))   throw new \Exception('You have to define a lang_id field to create a payment method');
        if(empty($object['name']))      throw new \Exception('You have to define a name field to create a payment method');
        if(! isset($object['active']))  throw new \Exception('You have to define a active field to create a payment method');
    }

    public static function managePaymentMethod($request, $order, $xhr = false)
    {
        // Redsys Payment (debit and credit cart)
        if($request->input('payment_method_id') === '1')
        {
            return RedsysService::createPayment($order, $xhr);
        }

        // PayPal Payment
        elseif($request->input('payment_method_id') === '2')
        {
            return PayPalService::createPayment($order, $xhr);
        }

        // Stripe Payment
        elseif($request->input('payment_method_id') === '4')
        {

        }
    }
}