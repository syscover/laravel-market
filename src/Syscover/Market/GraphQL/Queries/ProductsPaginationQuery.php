<?php namespace Syscover\Market\GraphQL\Queries;

use GraphQL;
use GraphQL\Type\Definition\Type;
use Folklore\GraphQL\Support\Query;
use Syscover\Market\Models\Product;

class ProductsPaginationQuery extends Query
{
    protected $attributes = [
        'name'          => 'ProductsPaginationQuery',
        'description'   => 'Query to get products list'
    ];

    public function type()
    {
        return GraphQL::type('CoreObjectPagination');
    }

    public function args()
    {
        return [
            'filters' => [
                'name'          => 'filters',
                'type'          => Type::listOf(GraphQL::type('CoreSQLQueryInput')),
                'description'   => 'to filter queries'
            ],
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
            'query' => Product::calculateFoundRows()->builder()
        ];
    }
}