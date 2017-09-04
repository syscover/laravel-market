<?php namespace Syscover\Market\GraphQL\Queries;

use GraphQL;
use GraphQL\Type\Definition\Type;
use Folklore\GraphQL\Support\Query;
use Syscover\Core\Services\SQLService;
use Syscover\Market\Models\OrderRow;

class OrderRowQuery extends Query
{
    protected $attributes = [
        'name'          => 'OrderRowQuery',
        'description'   => 'Query to get a order'
    ];

    public function type()
    {
        return GraphQL::type('MarketOrderRow');
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
        $query = SQLService::getQueryFiltered(OrderRow::builder(), $args['sql']);

        return $query->first();
    }
}