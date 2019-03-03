<?php namespace Syscover\Market\Services;

use Syscover\Market\Models\CartPriceRule;
use Syscover\Market\Models\Order;

class CartPriceRuleService
{
    public static function create($object)
    {
        self::checkCreate($object);

        if(empty($object['id']))
        {
            if(! empty($object['name']))        $object['names'] = [['id' => $object['lang_id'], 'value' => $object['name']]];
            if(! empty($object['description'])) $object['descriptions'] = [['id' => $object['lang_id'], 'value' => $object['description']]];

            $object['data_lang'] = CartPriceRule::addDataLang($object['lang_id']);

            return CartPriceRule::create(self::builder($object));
        }
        else
        {
            $object         = collect($object);

            // get object to update data and data_lang field
            $cartPriceRule  = CartPriceRule::find($object->get('id'));

            // get values
            $names          = $cartPriceRule->names;
            $descriptions   = $cartPriceRule->descriptions;
            $dataLang       = $cartPriceRule->data_lang;

            // set values
            $names[]   = [
                'id'    => $object->get('lang_id'),
                'value' => $object->get('name')
            ];
            $descriptions[]   = [
                'id'    => $object->get('lang_id'),
                'value' => $object->get('description')
            ];
            $dataLang[] = $object->get('lang_id');

            // update values
            $cartPriceRule->names           = $names;
            $cartPriceRule->descriptions    = $descriptions;
            $cartPriceRule->data_lang       = $dataLang;

            $cartPriceRule->save();

            return $cartPriceRule;
        }
    }

    public static function update($object)
    {
        self::checkUpdate($object);

        if(! empty($object['name']) || ! empty($object['description']))
        {
            $cartPriceRule = CartPriceRule::find($object['id']);
        }

        // set name field
        if(! empty($object['name']))
        {
            if(empty($object['lang_id']))   throw new \Exception('You have to define a lang_id field to update a name field');

            $names = collect($cartPriceRule->names);
            $names->transform(function($obj) use ($object) {
                if($obj['id'] === $object['lang_id']) $obj['value'] = $object['name'];
                return $obj;
            });

            $object['names'] = json_encode($names);
        }

        // set description field
        if(! empty($object['description']))
        {
            if(empty($object['lang_id']))   throw new \Exception('You have to define a lang_id field to update a description field');

            $descriptions = collect($cartPriceRule->descriptions);
            $descriptions->transform(function($obj) use ($object) {
                if($obj['id'] === $object['lang_id']) $obj['value'] = $object['description'];
                return $obj;
            });

            $object['descriptions'] = json_encode($descriptions);
        }

        // set customer_group_ids field
        if(! empty($object['customer_group_ids'])) $object['customer_group_ids'] = json_encode($object['customer_group_ids']);

        CartPriceRule::where('id', $object['id'])->update(self::builder($object));

        return CartPriceRule::find($object['id']);
    }

    private static function builder($object, $filterKeys = null)
    {
        $object = collect($object);
        if($filterKeys)
        {
            $object = $object->only($filterKeys);
        }
        else
        {
            $object = $object->only([
                'names',
                'descriptions',
                'active',
                'customer_group_ids',
                'customer_ids',
                'combinable',
                'priority',
                'has_coupon',
                'coupon_code',
                'customer_uses',
                'coupon_uses',
                'total_uses',
                'enable_from',
                'enable_to',
                'discount_type_id',
                'discount_fixed_amount',
                'discount_percentage',
                'maximum_discount_amount',
                'apply_shipping_amount',
                'free_shipping',
                'rules',
                'data_lang'
            ]);
        }

        if($object->has('enable_from')) $object['enable_from'] = empty($object->get('enable_from')) ? null : date_time_string($object->get('enable_from'));
        if($object->has('enable_to')) $object['enable_to'] = empty($object->get('enable_to')) ? null : date_time_string($object->get('enable_to'));

        return $object->toArray();
    }

    private static function checkCreate($object)
    {
        if(empty($object['lang_id']))   throw new \Exception('You have to define a lang_id field to create a cart price rule');
        if(empty($object['name']))      throw new \Exception('You have to define a name field to create a cart price rule');

    }

    private static function checkUpdate($object)
    {
        if(empty($object['id'])) throw new \Exception('You have to define a id field to update a cart price rule');
    }


    public static function incrementPriceRule(Order $order)
    {
        foreach ($order->discounts as $discount)
        {
            if ($discount->rule_type === 'Syscover\Market\Models\CartPriceRule')
            {
                $cartPriceRule = CartPriceRule::find($discount->rule_id);
                $cartPriceRule->increment('total_uses');

            }
            info('Increment cart price rule ' . $discount->rule_type . ' with id: ' . $discount->rule_id . ' in one use');
        }
    }
}
