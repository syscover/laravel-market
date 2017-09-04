<?php namespace Syscover\Market\GraphQL\Queries;

use GraphQL;
use GraphQL\Type\Definition\Type;
use Folklore\GraphQL\Support\Query;
use Syscover\Core\Services\SQLService;
use Syscover\Market\Models\OrderRow;

class OrderRowsQuery extends Query
{
    protected $attributes = [
        'name'          => 'OrderStatusesQuery',
        'description'   => 'Query to get order statuses'
    ];

    public function type()
    {
        return Type::listOf(GraphQL::type('MarketOrderRow'));
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
        $query = OrderRow::builder();

        if(isset($args['sql']))
        {
            $query = SQLService::getQueryFiltered($query, $args['sql']);
            $query = SQLService::getQueryOrderedAndLimited($query, $args['sql']);
        }

        return $query->get();
    }
}