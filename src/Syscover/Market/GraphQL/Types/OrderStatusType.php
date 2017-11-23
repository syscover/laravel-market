<?php namespace Syscover\Market\GraphQL\Types;

use GraphQL\Type\Definition\Type;
use Folklore\GraphQL\Support\Type as GraphQLType;

class OrderStatusType extends GraphQLType {

    protected $attributes = [
        'name'          => 'OrderStatus',
        'description'   => 'Order status of order'
    ];

    public function fields()
    {
        return [
            'ix' => [
                'type' => Type::nonNull(Type::int()),
                'description' => 'The index of order status'
            ],
            'id' => [
                'type' => Type::nonNull(Type::int()),
                'description' => 'The id of order status'
            ],
            'lang_id' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'Lang of order status'
            ],
            'name' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'The name of order status'
            ],
            'active' => [
                'type' => Type::boolean(),
                'description' => 'Set if order status is active'
            ],
            'data_lang' => [
                'type' => Type::listOf(Type::string()),
                'description' => 'JSON string that contain information about object translations'
            ]
        ];
    }
}