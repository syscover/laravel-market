<?php namespace Syscover\Market\GraphQL\Queries;

use GraphQL;
use GraphQL\Type\Definition\Type;
use Folklore\GraphQL\Support\Query;
use Syscover\Market\Models\CustomerGroupCustomerClassTax;

class CustomerGroupsCustomerClassTaxesPaginationQuery extends Query
{
    protected $attributes = [
        'name'          => 'CustomerGroupsCustomerClassTaxesPaginationQuery',
        'description'   => 'Query to get relation between customer group and customer class taxes list'
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
            // set setEagerLoads to clean eager loads to use FOUND_ROWS() MySql Function
            'query' => CustomerGroupCustomerClassTax::calculateFoundRows()->builder()
        ];
    }
}