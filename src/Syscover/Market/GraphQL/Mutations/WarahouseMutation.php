<?php namespace Syscover\Market\GraphQL\Mutations;

use GraphQL;
use GraphQL\Type\Definition\Type;
use Folklore\GraphQL\Support\Mutation;
use Syscover\Core\Services\SQLService;
use Syscover\Market\Models\Warehouse;
use Syscover\Market\Services\WarehouseService;

class WarehouseMutation extends Mutation
{
    public function type()
    {
        return GraphQL::type('MarketWarehouse');
    }

    public function args()
    {
        return [
            'object' => [
                'name' => 'object',
                'type' => Type::nonNull(GraphQL::type('MarketWarehouseInput'))
            ],
        ];
    }
}

class AddWarehouseMutation extends WarehouseMutation
{
    protected $attributes = [
        'name' => 'addWarehouse',
        'description' => 'Add new warehouse'
    ];

    public function resolve($root, $args)
    {
        return WarehouseService::create($args['object']);
    }
}

class UpdateWarehouseMutation extends WarehouseMutation
{
    protected $attributes = [
        'name' => 'updateWarehouse',
        'description' => 'Update warehouse'
    ];

    public function resolve($root, $args)
    {
        return WarehouseService::update($args['object'], $args['object']['id']);
    }
}

class DeleteWarehouseMutation extends WarehouseMutation
{
    protected $attributes = [
        'name' => 'deleteWarehouse',
        'description' => 'Delete warehouse'
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
        $object = SQLService::destroyRecord($args['id'], Warehouse::class);

        return $object;
    }
}
