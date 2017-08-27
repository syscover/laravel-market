<?php namespace Syscover\Market\GraphQL\Mutations;

use GraphQL;
use GraphQL\Type\Definition\Type;
use Folklore\GraphQL\Support\Mutation;
use Syscover\Core\Services\SQLService;
use Syscover\Market\Models\Stock;
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

class AddStockMutation extends StockMutation
{
    protected $attributes = [
        'name' => 'addStock',
        'description' => 'Add new stock'
    ];

    public function resolve($root, $args)
    {
        return StockService::create($args['object']);
    }
}

class UpdateStockMutation extends StockMutation
{
    protected $attributes = [
        'name' => 'updateStock',
        'description' => 'Update stock'
    ];

    public function resolve($root, $args)
    {
        return StockService::update($args['object'], $args['object']['id']);
    }
}

class DeleteStockMutation extends StockMutation
{
    protected $attributes = [
        'name' => 'deleteStock',
        'description' => 'Delete stock'
    ];

    public function args()
    {
        return [
            'id' => [
                'name' => 'id',
                'type' => Type::nonNull(Type::string())
            ]
        ];
    }

    public function resolve($root, $args)
    {
        $object = SQLService::destroyRecord($args['id'], Stock::class);

        return $object;
    }
}
