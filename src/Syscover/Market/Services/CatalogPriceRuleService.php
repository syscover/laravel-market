<?php namespace Syscover\Market\Services;

class CatalogPriceRuleService
{
    public static function checkFreeShipping($object)
    {
        $freeShippingCountry    = collect(config('pulsar-market.free_shipping'))->get($object['ship_to_country']);
        $freeShippingZips       = $freeShippingCountry->get('zip'); // get zips from country
        $zipPattern             = $object['ship_to_zip'];

        for ($i = strlen($object['ship_to_zip']); $i > 0; $i--)
        {
            if(in_array($zipPattern, $freeShippingZips))
            {
                return [
                    'ship_to_country'   => $object['ship_to_country'],
                    'ship_to_zip'       => $object['ship_to_zip'],
                    'zip_pattern'       => $zipPattern,
                    'is_free'           => true,
                    'status'            => 200,
                    'statusText'        => 'success'
                ];
            }
            // replace last position by *
            $zipPattern = substr_replace($zipPattern,'*', $i - 1, 1);
        }

        return [
            'ship_to_country'   => $object['ship_to_country'],
            'ship_to_zip'       => $object['ship_to_zip'],
            'is_free'           => false,
            'status'            => 200,
            'statusText'        => 'success'
        ];
    }
}