<?php namespace Syscover\Market\GraphQL\Types;

use GraphQL\Type\Definition\Type;
use Folklore\GraphQL\Support\Type as GraphQLType;

class StockType extends GraphQLType
{
    protected $attributes = [
        'name' => 'Stock',
        'description' => 'Stock'
    ];

    public function fields()
    {
        return [
            'warehouse_id' => [
                'type' => Type::nonNull(Type::int()),
                'description' => 'The id of warehouse'
            ],
            'product_id' => [
                'type' => Type::nonNull(Type::int()),
                'description' => 'The id of product'
            ],
            'stock' => [
                'type' => Type::nonNull(Type::float()),
                'description' => 'The id of product'
            ],
            'minimum_stock' => [
                'type' => Type::nonNull(Type::float()),
                'description' => 'The id of product'
            ]
        ];
    }
}