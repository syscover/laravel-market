<?php namespace Syscover\Market\GraphQL\Queries;

use GraphQL;
use GraphQL\Type\Definition\Type;
use Folklore\GraphQL\Support\Query;
use Syscover\Core\Services\SQLService;
use Syscover\Market\Models\TaxRateZone;

class TaxRateZoneQuery extends Query
{
    protected $attributes = [
        'name'          => 'TaxRateZoneQuery',
        'description'   => 'Query to get a tax rate zone'
    ];

    public function type()
    {
        return GraphQL::type('MarketTaxRateZone');
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
        $query = SQLService::getQueryFiltered(TaxRateZone::builder(), $args['sql']);

        return $query->first();
    }
}