<?php namespace Syscover\Market\Services;

use Syscover\Market\Models\PaymentMethod;

class PaymentMethodService
{
    /**
     * Function to create a payment method
     * @param   array                           $object
     * @return  \Syscover\Market\Models\PaymentMethod
     * @throws  \Exception
     */
    public static function create($object)
    {
        if(empty($object['id']))
        {
            $id = PaymentMethod::max('id');
            $id++;
            $object['id'] = $id;
        }

        $object['data_lang'] = PaymentMethod::addDataLang($object['lang_id'], $object['id']);
        
        return PaymentMethod::create($object);
    }

    /**
     * @param   array     $object     contain properties of payment method
     * @return  \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|null|static|static[]
     */
    public static function update($object)
    {
        // pass object to collection
        $object = collect($object);

        PaymentMethod::where('id', $object->get('id'))
            ->update([
                'name'                          => $object->get('name'),
                'order_status_successful_id'    => $object->get('order_status_successful_id'),
                'minimum_price'                 => $object->get('minimum_price'),
                'maximum_price'                 => $object->get('maximum_price'),
                'instructions'                  => $object->get('instructions'),
                'sort'                          => $object->get('sort'),
                'active'                        => $object->get('active'),
            ]);

        return PaymentMethod::where('id', $object->get('id'))
            ->first();
    }

    public static function managePaymentMethod($request, $order)
    {
        // Redsys Payment (debit and credit cart)
        if($request->input('payment_method_id') === '1')
        {
            RedsysService::createPayment($order);
        }

        // PayPal Payment
        elseif($request->input('payment_method_id') === '2')
        {
            PayPalService::createPayment($order);
        }
    }
}