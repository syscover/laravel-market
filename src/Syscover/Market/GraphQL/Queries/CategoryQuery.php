<?php namespace Syscover\Market\GraphQL\Queries;

use GraphQL;
use GraphQL\Type\Definition\Type;
use Folklore\GraphQL\Support\Query;
use Syscover\Core\Services\SQLService;
use Syscover\Market\Models\Category;
use Syscover\Market\Models\TaxRule;

class CategoryQuery extends Query
{
    protected $attributes = [
        'name'          => 'CategoryQuery',
        'description'   => 'Query to get a category'
    ];

    public function type()
    {
        return GraphQL::type('MarketCategory');
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
        $query = SQLService::getQueryFiltered(Category::builder(), $args['sql']);

        return $query->first();
    }
}