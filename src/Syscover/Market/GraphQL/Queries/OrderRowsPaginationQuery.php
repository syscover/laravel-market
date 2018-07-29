<?php namespace Syscover\Market\GraphQL\Queries;

use GraphQL;
use GraphQL\Type\Definition\Type;
use Folklore\GraphQL\Support\Query;
use Syscover\Market\Models\OrderRow;

class OrderRowsPaginationQuery extends Query
{
    protected $attributes = [
        'name'          => 'OrderRowsPaginationQuery',
        'description'   => 'Query to get order rows'
    ];

    public function type()
    {
        return GraphQL::type('CoreObjectPagination');
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
        return (Object) [
            // set setEagerLoads to clean eager loads to use FOUND_ROWS() MySql Function
            'query' => OrderRow::calculateFoundRows()->builder()->setEagerLoads([])
        ];
    }
}