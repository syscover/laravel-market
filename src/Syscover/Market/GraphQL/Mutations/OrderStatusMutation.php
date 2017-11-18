<?php namespace Syscover\Market\GraphQL\Mutations;

use GraphQL;
use GraphQL\Type\Definition\Type;
use Folklore\GraphQL\Support\Mutation;
use Syscover\Core\Services\SQLService;
use Syscover\Market\Models\OrderStatus;

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
        if(! isset($args['object']['id']))
        {
            $id = OrderStatus::max('id');
            $id++;

            $args['object']['id'] = $id;
        }

        $args['object']['data_lang'] = OrderStatus::addDataLang($args['object']['lang_id'], $args['object']['id']);

        return OrderStatus::create($args['object']);
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
        OrderStatus::where('id', $args['object']['id'])
            ->where('lang_id', $args['object']['lang_id'])
            ->update($args['object']);

        return OrderStatus::where('id', $args['object']['id'])
            ->where('lang_id', $args['object']['lang_id'])
            ->first();
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
            'id' => [
                'name' => 'id',
                'type' => Type::nonNull(Type::string())
            ],
            'lang' => [
                'name' => 'lang',
                'type' => Type::nonNull(Type::string())
            ]
        ];
    }

    public function resolve($root, $args)
    {
        $object = SQLService::destroyRecord($args['id'], OrderStatus::class, $args['lang']);

        return $object;
    }
}
