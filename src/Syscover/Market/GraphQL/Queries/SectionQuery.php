<?php namespace Syscover\Market\GraphQL\Queries;

use GraphQL;
use GraphQL\Type\Definition\Type;
use Folklore\GraphQL\Support\Query;
use Syscover\Core\Services\SQLService;
use Syscover\Market\Models\Section;

class SectionQuery extends Query
{
    protected $attributes = [
        'name'          => 'SectionQuery',
        'description'   => 'Query to get section'
    ];

    public function type()
    {
        return GraphQL::type('MarketSection');
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
        $query = SQLService::getQueryFiltered(Section::builder(), $args['sql']);

        return $query->first();
    }
}