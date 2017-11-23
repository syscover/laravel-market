<?php namespace Syscover\Market\GraphQL\Mutations;

use GraphQL;
use GraphQL\Type\Definition\Type;
use Folklore\GraphQL\Support\Mutation;
use Syscover\Core\Services\SQLService;
use Syscover\Market\Models\OrderStatus;
use Syscover\Market\Services\OrderStatusService;

class OrderStatusMutation extends Mutation
{
    public function type()
    {
        return GraphQL::type('MarketOrderStatus');
    }

    public function args()
    {
        return [
            'object' => [
                'name' => 'object',
                'type' => Type::nonNull(GraphQL::type('MarketOrderStatusInput'))
            ],
        ];
    }
}

class AddOrderStatusMutation extends OrderStatusMutation
{
    protected $attributes = [
        'name'          => 'addOrderStatus',
        'description'   => 'Add new order status'
    ];

    public function resolve($root, $args)
    {
        return OrderStatusService::create($args['object']);
    }
}

class UpdateOrderStatusMutation extends OrderStatusMutation
{
    protected $attributes = [
        'name' => 'updateOrderStatus',
        'description' => 'Update order status'
    ];

    public function resolve($root, $args)
    {
        return OrderStatusService::update($args['object']);
    }
}

class DeleteOrderStatusMutation extends OrderStatusMutation
{
    protected $attributes = [
        'name' => 'deleteOrderStatus',
        'description' => 'Delete order status'
    ];

    public function args()
    {
        return [
            'lang_id' => [
                'name' => 'lang_id',
                'type' => Type::nonNull(Type::string())
            ],
            'id' => [
                'name' => 'id',
                'type' => Type::nonNull(Type::int())
            ],
        ];
    }

    public function resolve($root, $args)
    {
        $object = SQLService::destroyRecord($args['id'], OrderStatus::class, $args['lang_id']);

        return $object;
    }
}
