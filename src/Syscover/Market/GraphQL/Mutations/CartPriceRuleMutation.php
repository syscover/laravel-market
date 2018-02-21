<?php namespace Syscover\Market\GraphQL\Mutations;

use GraphQL;
use GraphQL\Type\Definition\Type;
use Folklore\GraphQL\Support\Mutation;
use Syscover\Core\Services\SQLService;
use Syscover\Market\Models\CartPriceRule;
use Syscover\Market\Services\CartPriceRuleService;

class CartPriceRuleMutation extends Mutation
{
    public function type()
    {
        return GraphQL::type('MarketCartPriceRule');
    }

    public function args()
    {
        return [
            'object' => [
                'name' => 'object',
                'type' => Type::nonNull(GraphQL::type('MarketCartPriceRuleInput'))
            ],
        ];
    }
}

class AddCartPriceRuleMutation extends CartPriceRuleMutation
{
    protected $attributes = [
        'name'          => 'addCartPriceRule',
        'description'   => 'Add new cart price rule'
    ];

    public function resolve($root, $args)
    {
        return CartPriceRuleService::create($args['object']);
    }
}

class UpdateCartPriceRuleMutation extends CartPriceRuleMutation
{
    protected $attributes = [
        'name' => 'updateCartPriceRule',
        'description' => 'Update cart price rule'
    ];

    public function resolve($root, $args)
    {
        return CartPriceRuleService::update($args['object']);
    }
}

class DeleteCartPriceRuleMutation extends CartPriceRuleMutation
{
    protected $attributes = [
        'name' => 'deleteCartPriceRule',
        'description' => 'Delete cart price rule'
    ];

    public function args()
    {
        return [
            'id' => [
                'name' => 'id',
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
        $object = SQLService::destroyRecord($args['id'], CartPriceRule::class, $args['lang_id']);

        return $object;
    }
}
