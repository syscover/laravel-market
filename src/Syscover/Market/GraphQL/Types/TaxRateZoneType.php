<?php namespace Syscover\Market\GraphQL\Types;

use GraphQL\Type\Definition\Type;
use Folklore\GraphQL\Support\Type as GraphQLType;

class TaxRateZoneType extends GraphQLType {

    protected $attributes = [
        'name'          => 'TaxRateZoneType',
        'description'   => 'Tax rate zone to do tax rules'
    ];

    public function fields()
    {
        return [
            'id' => [
                'type' => Type::nonNull(Type::int()),
                'description' => 'The id of customer class tax'
            ],
            'name' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'The name of customer class tax'
            ],
            'country_id' => [
                'type' => Type::nonNull(Type::string()),
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
            'cp' => [
                'type' => Type::string(),
                'description' => 'The cp of tax zone'
            ],
            'tax_rate' => [
                'type' => Type::float(),
                'description' => 'Tax to apply'
            ],
        ];
    }
}