<?php namespace Syscover\Market\GraphQL\Mutations;

use GraphQL;
use GraphQL\Type\Definition\Type;
use Folklore\GraphQL\Support\Mutation;
use Syscover\Core\Services\SQLService;
use Syscover\Market\Models\PaymentMethod;
use Syscover\Market\Services\PaymentMethodService;

class PaymentMethodMutation extends Mutation
{
    public function type()
    {
        return GraphQL::type('MarketPaymentMethod');
    }

    public function args()
    {
        return [
            'object' => [
                'name' => 'object',
                'type' => Type::nonNull(GraphQL::type('MarketPaymentMethodInput'))
            ],
        ];
    }
}

class AddPaymentMethodMutation extends PaymentMethodMutation
{
    protected $attributes = [
        'name'          => 'addPaymentMethod',
        'description'   => 'Add new order status'
    ];

    public function resolve($root, $args)
    {
        return PaymentMethodService::create($args['object']);
    }
}

class UpdatePaymentMethodMutation extends PaymentMethodMutation
{
    protected $attributes = [
        'name' => 'updatePaymentMethod',
        'description' => 'Update order status'
    ];

    public function resolve($root, $args)
    {
        return PaymentMethodService::update($args['object'], $args['object']['id'], $args['object']['lang_id']);
    }
}

class DeletePaymentMethodMutation extends PaymentMethodMutation
{
    protected $attributes = [
        'name' => 'deletePaymentMethod',
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
        $object = SQLService::destroyRecord($args['id'], PaymentMethod::class, $args['lang']);

        return $object;
    }
}
