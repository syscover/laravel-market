<?php namespace Syscover\Market\GraphQL\Queries;

use GraphQL;
use GraphQL\Type\Definition\Type;
use Folklore\GraphQL\Support\Query;
use Syscover\Core\Services\SQLService;
use Syscover\Market\Models\TaxRule;

class TaxRulesPaginationQuery extends Query
{
    protected $attributes = [
        'name'          => 'TaxRulesPaginationQuery',
        'description'   => 'Query to get tax rules list'
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
        $query = SQLService::getQueryFiltered(TaxRule::builder(), $args['sql']);

        // count records filtered
        $filtered = $query->count();

        // N total records
        $total = SQLService::countPaginateTotalRecords(TaxRule::builder());

        return (Object) [
            'total'     => $total,
            'filtered'  => $filtered,
            'query'     => $query
        ];
    }
}