<?php namespace Syscover\Market\GraphQL\Queries;

use GraphQL;
use GraphQL\Type\Definition\Type;
use Folklore\GraphQL\Support\Query;
use Syscover\Market\Models\Stock;

class StocksPaginationQuery extends Query
{
    protected $attributes = [
        'name'          => 'StocksPaginationQuery',
        'description'   => 'Query to get stock list'
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
            'query' => Stock::calculateFoundRows()->builder()
        ];
    }
}