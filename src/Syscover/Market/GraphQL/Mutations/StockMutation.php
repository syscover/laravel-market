<?php namespace Syscover\Market\GraphQL\Mutations;

use GraphQL;
use GraphQL\Type\Definition\Type;
use Folklore\GraphQL\Support\Mutation;
use Syscover\Market\Services\StockService;

class StockMutation extends Mutation
{
    public function type()
    {
        return GraphQL::type('MarketStock');
    }

    public function args()
    {
        return [
            'object' => [
                'name' => 'object',
                'type' => Type::nonNull(GraphQL::type('MarketStockInput'))
            ],
        ];
    }
}

class SetStockMutation extends StockMutation
{
    protected $attributes = [
        'name' => 'setStock',
        'description' => 'Create or update stock'
    ];

    public function resolve($root, $args)
    {
        return StockService::set($args['object']);
    }
}
