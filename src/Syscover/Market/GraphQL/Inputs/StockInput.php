<?php namespace Syscover\Market\GraphQL\Inputs;

use GraphQL\Type\Definition\Type;
use Folklore\GraphQL\Support\Type as GraphQLType;

class StockInput extends GraphQLType
{
    protected $attributes = [
        'name' => 'StockInput',
        'description' => 'Stock'
    ];

    protected $inputObject = true;

    public function fields()
    {
        return [
            'warehouse_id' => [
                'type' => Type::int(),
                'description' => 'The id of warehouse'
            ],
            'product_id' => [
                'type' => Type::int(),
                'description' => 'The id of product'
            ],
            'stock' => [
                'type' => Type::float(),
                'description' => 'The id of product'
            ],
            'minimum_stock' => [
                'type' => Type::float(),
                'description' => 'The id of product'
            ]
        ];
    }
}