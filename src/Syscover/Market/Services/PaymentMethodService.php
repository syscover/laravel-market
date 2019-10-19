<?php namespace Syscover\Market\Services;

use Syscover\Market\Models\PaymentMethod;

class PaymentMethodService
{
    public static function create($object)
    {
        self::checkCreate($object);

        if(empty($object['id'])) $object['id'] = next_id(PaymentMethod::class);

        $object['data_lang'] = PaymentMethod::getDataLang($object['lang_id'], $object['id']);
        
        return PaymentMethod::create(self::builder($object));
    }

    public static function update($object)
    {
        self::checkUpdate($object);
        PaymentMethod::where('ix', $object['ix'])->update(self::builder($object));
        PaymentMethod::where('id', $object['id'])->update(self::builder($object, ['active', 'sort']));

        return PaymentMethod::find($object['ix']);
    }

    private static function builder($object, $filterKeys = null)
    {
        $object = collect($object);
        if($filterKeys) $object = $object->only($filterKeys);

        return $object->only(['id', 'lang_id', 'name', 'order_status_successful_id', 'minimum_price', 'maximum_price', 'instructions', 'sort', 'active', 'data_lang'])->toArray();
    }

    private static function checkCreate($object)
    {
        if(empty($object['lang_id']))   throw new \Exception('You have to define a lang_id field to create a payment method');
        if(empty($object['name']))      throw new \Exception('You have to define a name field to create a payment method');
    }

    private static function checkUpdate($object)
    {
        if(empty($object['ix']))        throw new \Exception('You have to define a ix field to update a payment method');
        if(empty($object['id']))        throw new \Exception('You have to define a id field to update a payment method');
    }

    //********************
    //* Custom methods
    //********************
    public static function createPaymentMethod($paymentMethodId, $order, $xhr = false)
    {
        // Redsys Payment (debit and credit cart)
        if((int) $paymentMethodId === 1)
        {
            return RedsysService::createPayment($order, $xhr);
        }

        // PayPal Payment
        elseif((int) $paymentMethodId === 2)
        {
            return PayPalPaymentService::createPayment($order, $xhr);
        }

        // Stripe Payment
        elseif((int) $paymentMethodId === 4)
        {
            return StripePaymentService::createPayment($order, $xhr);
        }

        throw new \Exception('You have to define a payment method valid to manage payment method');
    }
}
