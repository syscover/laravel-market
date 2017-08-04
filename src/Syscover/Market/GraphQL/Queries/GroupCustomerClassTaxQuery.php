<?php namespace Syscover\Market\GraphQL\Queries;

use GraphQL;
use GraphQL\Type\Definition\Type;
use Folklore\GraphQL\Support\Query;
use Syscover\Core\Services\SQLService;
use Syscover\Market\Models\GroupCustomerClassTax;

class GroupCustomerClassTaxQuery extends Query
{
    protected $attributes = [
        'name'          => 'GroupCustomerClassTaxQuery',
        'description'   => 'Query to get a group customer class tax'
    ];

    public function type()
    {
        return GraphQL::type('MarketGroupCustomerClassTax');
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

        return $query->first();
    }
}