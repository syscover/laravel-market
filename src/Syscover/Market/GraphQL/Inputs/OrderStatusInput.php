<?php namespace Syscover\Market\GraphQL\Inputs;

use GraphQL\Type\Definition\Type;
use Folklore\GraphQL\Support\Type as GraphQLType;
use Syscover\Core\GraphQL\Types\AnyType;

class OrderStatusInput extends GraphQLType
{
    protected $attributes = [
        'name'          => 'OrderStatusInput',
        'description'   => 'Order status of order'
    ];

    protected $inputObject = true;

    public function fields()
    {
        return [
            'id' => [
                'type' => app(AnyType::class),
                'description' => 'The id of order status'
            ],
            'lang_id' => [
                'type' => Type::string(),
                'description' => 'Lang of order status'
            ],
            'name' => [
                'type' => Type::string(),
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