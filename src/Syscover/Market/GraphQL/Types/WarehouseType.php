<?php namespace Syscover\Market\GraphQL\Types;

use GraphQL\Type\Definition\Type;
use Folklore\GraphQL\Support\Type as GraphQLType;

class WarehouseType extends GraphQLType
{
    protected $attributes = [
        'name' => 'Warehouse',
        'description' => 'Warehouse'
    ];

    public function fields()
    {
        return [
            'id' => [
                'type' => Type::nonNull(Type::int()),
                'description' => 'The id of warehouse'
            ],
            'name' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'Name of warehouse'
            ],

            // geolocation data
            'country_id' => [
                'type' => Type::string(),
                'description' => 'The country id of warehouse'
            ],
            'territorial_area_1_id' => [
                'type' => Type::string(),
                'description' => 'The territorial area 1 id of warehouse'
            ],
            'territorial_area_2_id' => [
                'type' => Type::string(),
                'description' => 'The territorial area 2 id of warehouse'
            ],
            'territorial_area_3_id' => [
                'type' => Type::string(),
                'description' => 'The territorial area 3 id of warehouse'
            ],
            'zip' => [
                'type' => Type::string(),
                'description' => 'The ZIP of warehouse'
            ],
            'locality' => [
                'type' => Type::string(),
                'description' => 'Locality of warehouse'
            ],
            'address' => [
                'type' => Type::string(),
                'description' => 'Address of warehouse'
            ],
            'latitude' => [
                'type' => Type::string(),
                'description' => 'Latitude of warehouse'
            ],
            'longitude' => [
                'type' => Type::string(),
                'description' => 'Longitude of warehouse'
            ],
            'active' => [
                'type' => Type::boolean(),
                'description' => 'Active warehouse'
            ]
        ];
    }
}