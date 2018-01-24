<?php namespace Syscover\Market\GraphQL\Inputs;

use GraphQL\Type\Definition\Type;
use Folklore\GraphQL\Support\Type as GraphQLType;

class TaxRateZoneInput extends GraphQLType
{
    protected $attributes = [
        'name'          => 'TaxRateZoneInput',
        'description'   => 'Tax rate zone to do tax rules'
    ];

    protected $inputObject = true;

    public function fields()
    {
        return [
            'id' => [
                'type' => Type::int(),
                'description' => 'The id of customer class tax'
            ],
            'name' => [
                'type' => Type::string(),
                'description' => 'The name of customer class tax'
            ],
            'country_id' => [
                'type' => Type::string(),
                'description' => 'The id of country'
            ],
            'territorial_area_1_id' => [
                'type' => Type::string(),
                'description' => 'The id of territorial area 1 of country'
            ],
            'territorial_area_2_id' => [
                'type' => Type::string(),
                'description' => 'The id of territorial area 2 of country'
            ],
            'territorial_area_3_id' => [
                'type' => Type::string(),
                'description' => 'The id of territorial area 3 of country'
            ],
            'zip' => [
                'type' => Type::string(),
                'description' => 'The ZIP of tax zone'
            ],
            'tax_rate' => [
                'type' => Type::float(),
                'description' => 'Tax to apply'
            ],
        ];
    }
}