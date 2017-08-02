<?php namespace Syscover\Market\GraphQL\Queries;

use GraphQL;
use GraphQL\Type\Definition\Type;
use Folklore\GraphQL\Support\Query;
use Syscover\Core\Services\SQLService;
use Syscover\Market\Models\CustomerClassTax;

class CustomerClassTaxesPaginationQuery extends Query
{
    protected $attributes = [
        'name'          => 'CustomerClassTaxesPaginationQuery',
        'description'   => 'Query to get customer class taxes list'
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
        $query = SQLService::getQueryFiltered(CustomerClassTax::builder(), $args['sql']);

        // count records filtered
        $filtered = $query->count();

        // N total records
        $total = SQLService::countPaginateTotalRecords(CustomerClassTax::builder());

        return (Object) [
            'total'     => $total,
            'filtered'  => $filtered,
            'query'     => $query
        ];
    }
}