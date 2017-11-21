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
        return PaymentMethodService::update($args['object']);
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
            'object_id' => [
                'name' => 'object_id',
                'type' => Type::nonNull(Type::int())
            ],
            'lang_id' => [
                'name' => 'lang_id',
                'type' => Type::nonNull(Type::string())
            ]
        ];
    }

    public function resolve($root, $args)
    {
        $object = SQLService::destroyRecord($args['object_id'], PaymentMethod::class, $args['lang_id']);

        return $object;
    }
}
