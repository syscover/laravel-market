<?php namespace Syscover\Market\GraphQL\Queries;

use GraphQL;
use GraphQL\Type\Definition\Type;
use Folklore\GraphQL\Support\Query;
use Syscover\Core\Services\SQLService;
use Syscover\Market\Models\OrderStatus;

class OrderStatusQuery extends Query
{
    protected $attributes = [
        'name'          => 'OrderStatusQuery',
        'description'   => 'Query to get a order status'
    ];

    public function type()
    {
        return GraphQL::type('MarketOrderStatus');
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
        $query = SQLService::getQueryFiltered(OrderStatus::builder(), $args['sql']);

        return $query->first();
    }
}