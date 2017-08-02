<?php namespace Syscover\Market\GraphQL\Queries;

use GraphQL;
use GraphQL\Type\Definition\Type;
use Folklore\GraphQL\Support\Query;
use Syscover\Core\Services\SQLService;
use Syscover\Market\Models\ProductClassTax;

class ProductClassTaxQuery extends Query
{
    protected $attributes = [
        'name'          => 'ProductClassTaxQuery',
        'description'   => 'Query to get a product class tax'
    ];

    public function type()
    {
        return GraphQL::type('MarketProductClassTax');
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
        $query = SQLService::getQueryFiltered(ProductClassTax::builder(), $args['sql']);

        return $query->first();
    }
}