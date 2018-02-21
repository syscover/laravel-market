<?php namespace Syscover\Market\Services;

use Carbon\Carbon;
use Syscover\Market\Models\CartPriceRule;
use Syscover\Market\Models\Category;

class CartPriceRuleService
{
    /**
     * Function to create a cart price rule
     * @param   array $object
     * @return  \Syscover\Market\Models\CartPriceRule
     * @throws  \Exception
     */
    public static function create($object)
    {
        if(empty($object['lang_id']))   throw new \Exception('You have to define a lang_id field to create a cart price rule');

        if(empty($object['id']))
        {
            if(! empty($object['name'])) $object['names'] = [['id' => $object['lang_id'], 'value' => $object['name']]];
            if(! empty($object['description'])) $object['descriptions'] = [['id' => $object['lang_id'], 'value' => $object['description']]];

            $object['data_lang'] = CartPriceRule::addDataLang($object['lang_id']);

            return CartPriceRule::create(CartPriceRuleService::builder($object));
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

    /**
     * @param   array     $object     contain properties of cart price rule
     * @return  \Syscover\Market\Models\CartPriceRule
     * @throws \Exception
     */
    public static function update($object)
    {
        if(! empty($object['name']) || ! empty($object['description'])) $cartPriceRule = CartPriceRule::find($object['id']);

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

        // set group_ids field
        if(! empty($object['group_ids']))
        {
            $object['group_ids'] = json_encode($object['group_ids']);
        }

        CartPriceRule::where('id', $object['id'])->update(CartPriceRuleService::builder($object));

        return Category::find($object['id']);
    }

    private static function builder($object)
    {
        $object = collect($object);
        $data = [];

        if($object->has('names'))                       $data['names'] = $object->get('names');
        if($object->has('descriptions'))                $data['descriptions'] = $object->get('descriptions');
        if($object->has('active'))                      $data['active'] = $object->get('active');
        if($object->has('group_ids'))                   $data['group_ids'] = $object->get('group_ids');
        if($object->has('customer_ids'))                $data['customer_ids'] = $object->get('customer_ids');
        if($object->has('combinable'))                  $data['combinable'] = $object->get('combinable');
        if($object->has('priority'))                    $data['priority'] = $object->get('priority');
        if($object->has('has_coupon'))                  $data['has_coupon'] = $object->get('has_coupon');
        if($object->has('coupon_code'))                 $data['coupon_code'] = $object->get('coupon_code');
        if($object->has('customer_uses'))               $data['customer_uses'] = $object->get('customer_uses');
        if($object->has('coupon_uses'))                 $data['coupon_uses'] = $object->get('coupon_uses');
        if($object->has('total_uses'))                  $data['total_uses'] = $object->get('total_uses');
        // use preg_replace to format date from Google Chrome, attach (Hota de verano romance) string
        if($object->has('enable_from'))                 $data['enable_from'] = (new Carbon(preg_replace('/\(.*\)/','', $object['enable_from']), config('app.timezone')))->toDateTimeString();
        if($object->has('enable_to'))                   $data['enable_to'] = (new Carbon(preg_replace('/\(.*\)/','', $object['enable_to']), config('app.timezone')))->toDateTimeString();
        if($object->has('condition_rules'))             $data['condition_rules'] = $object->get('condition_rules');
        if($object->has('discount_type_id'))            $data['discount_type_id'] = $object->get('discount_type_id');
        if($object->has('discount_fixed_amount'))       $data['discount_fixed_amount'] = $object->get('discount_fixed_amount');
        if($object->has('discount_percentage'))         $data['discount_percentage'] = $object->get('discount_percentage');
        if($object->has('maximum_discount_amount'))     $data['maximum_discount_amount'] = $object->get('maximum_discount_amount');
        if($object->has('apply_shipping_amount'))       $data['apply_shipping_amount'] = $object->get('apply_shipping_amount');
        if($object->has('free_shipping'))               $data['free_shipping'] = $object->get('free_shipping');
        if($object->has('product_rules'))               $data['product_rules'] = $object->get('product_rules');
        if($object->has('data_lang'))                   $data['data_lang'] = $object->get('data_lang');

        return $data;
    }
}