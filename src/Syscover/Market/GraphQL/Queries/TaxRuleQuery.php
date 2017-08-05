<?php namespace Syscover\Market\GraphQL\Queries;

use GraphQL;
use GraphQL\Type\Definition\Type;
use Folklore\GraphQL\Support\Query;
use Syscover\Core\Services\SQLService;
use Syscover\Market\Models\TaxRule;

class TaxRuleQuery extends Query
{
    protected $attributes = [
        'name'          => 'TaxRuleQuery',
        'description'   => 'Query to get a tax rule'
    ];

    public function type()
    {
        return GraphQL::type('MarketTaxRule');
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

        return $query->first();
    }
}