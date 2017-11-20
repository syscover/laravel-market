<?php namespace Syscover\Market\GraphQL\Queries;

use GraphQL;
use GraphQL\Type\Definition\Type;
use Folklore\GraphQL\Support\Query;
use Syscover\Core\Services\SQLService;
use Syscover\Market\Models\Product;

class ProductQuery extends Query
{
    protected $attributes = [
        'name'          => 'ProductQuery',
        'description'   => 'Query to get a product'
    ];

    public function type()
    {
        return GraphQL::type('MarketProduct');
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
        $query = SQLService::getQueryFiltered(Product::builder(), $args['sql']);
        $object = $query->first();

        $object->load('attachments');

        return $object;
    }
}