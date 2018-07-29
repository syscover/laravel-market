<?php namespace Syscover\Market\GraphQL\Queries;

use GraphQL;
use GraphQL\Type\Definition\Type;
use Folklore\GraphQL\Support\Query;
use Syscover\Market\Models\TaxRateZone;

class TaxRateZonesPaginationQuery extends Query
{
    protected $attributes = [
        'name'          => 'TaxRateZonesPaginationQuery',
        'description'   => 'Query to get tax rate zones list'
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
            'query' => TaxRateZone::calculateFoundRows()->builder()
        ];
    }
}