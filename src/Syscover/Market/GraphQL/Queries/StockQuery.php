<?php namespace Syscover\Market\GraphQL\Queries;

use GraphQL;
use GraphQL\Type\Definition\Type;
use Folklore\GraphQL\Support\Query;
use Syscover\Core\Services\SQLService;
use Syscover\Market\Models\Stock;

class StockQuery extends Query
{
    protected $attributes = [
        'name'          => 'StockQuery',
        'description'   => 'Query to get a stock'
    ];

    public function type()
    {
        return GraphQL::type('MarketStock');
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
        $query = SQLService::getQueryFiltered(Stock::builder(), $args['sql']);

        return $query->first();
    }
}