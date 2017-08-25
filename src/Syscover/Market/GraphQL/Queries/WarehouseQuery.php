<?php namespace Syscover\Market\GraphQL\Queries;

use GraphQL;
use GraphQL\Type\Definition\Type;
use Folklore\GraphQL\Support\Query;
use Syscover\Core\Services\SQLService;
use Syscover\Market\Models\Warehouse;

class WarehouseQuery extends Query
{
    protected $attributes = [
        'name'          => 'WarehouseQuery',
        'description'   => 'Query to get a warehouse'
    ];

    public function type()
    {
        return GraphQL::type('MarketWarehouse');
    }

    public function args()
    {
        return [
            'sql' => [
                'name'          => 'sql',
                'type'          => Type::listOf(GraphQL::type('CoreSQLQueryInput')),
                'description'   => 'Field to add SQL operations'
            ]
        ];
    }

    public function resolve($root, $args)
    {
        $query = SQLService::getQueryFiltered(Warehouse::builder(), $args['sql']);

        return $query->first();
    }
}