<?php namespace Syscover\Market\GraphQL\Queries;

use GraphQL;
use GraphQL\Type\Definition\Type;
use Folklore\GraphQL\Support\Query;
use Syscover\Core\Services\SQLService;
use Syscover\Market\Models\GroupCustomerClassTax;

class GroupCustomerClassTaxesPaginationQuery extends Query
{
    protected $attributes = [
        'name'          => 'GroupCustomerClassTaxesPaginationQuery',
        'description'   => 'Query to get group customer class taxes list'
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
        $query = SQLService::getQueryFiltered(GroupCustomerClassTax::builder(), $args['sql']);

        // count records filtered
        $filtered = $query->count();

        // N total records
        $total = SQLService::countPaginateTotalRecords(GroupCustomerClassTax::builder());

        return (Object) [
            'total'     => $total,
            'filtered'  => $filtered,
            'query'     => $query
        ];
    }
}