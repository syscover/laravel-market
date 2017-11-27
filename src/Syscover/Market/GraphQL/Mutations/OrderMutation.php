<?php namespace Syscover\Market\GraphQL\Mutations;

use GraphQL;
use GraphQL\Type\Definition\Type;
use Folklore\GraphQL\Support\Mutation;
use Syscover\Core\Services\SQLService;
use Syscover\Market\Models\Order;
use Syscover\Market\Services\OrderService;

class OrderMutation extends Mutation
{
    public function type()
    {
        return GraphQL::type('MarketOrder');
    }

    public function args()
    {
        return [
            'object' => [
                'name' => 'object',
                'type' => Type::nonNull(GraphQL::type('MarketOrderInput'))
            ]
        ];
    }
}

class AddOrderMutation extends OrderMutation
{
    protected $attributes = [
        'name'          => 'addOrder',
        'description'   => 'Add new order'
    ];

    public function resolve($root, $args)
    {
        return OrderService::create($args['object']);
    }
}

class UpdateOrderMutation extends OrderMutation
{
    protected $attributes = [
        'name' => 'updateOrder',
        'description' => 'Update order'
    ];

    public function resolve($root, $args)
    {
        return OrderService::update($args['object'], $args['id']);
    }
}

class DeleteOrderMutation extends OrderMutation
{
    protected $attributes = [
        'name' => 'deleteOrder',
        'description' => 'Delete order'
    ];

    public function args()
    {
        return [
            'id' => [
                'name' => 'id',
                'type' => Type::nonNull(Type::int())
            ]
        ];
    }

    public function resolve($root, $args)
    {
        $object = SQLService::destroyRecord($args['id'], Order::class);

        return $object;
    }
}
