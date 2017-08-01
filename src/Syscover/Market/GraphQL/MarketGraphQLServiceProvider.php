<?php namespace Syscover\Market\GraphQL;

use GraphQL;

class MarketGraphQLServiceProvider
{
    public static function bootGraphQLTypes()
    {
        // ORDER STATUS
        GraphQL::addType(\Syscover\Market\GraphQL\Types\OrderStatusType::class, 'MarketOrderStatus');
        GraphQL::addType(\Syscover\Market\GraphQL\Inputs\OrderStatusInput::class, 'MarketOrderStatusInput');
    }

    public static function bootGraphQLSchema()
    {
        GraphQL::addSchema('default', array_merge_recursive(GraphQL::getSchemas()['default'], [
            'query' => [
                // ORDER STATUS
                'marketOrderStatusesPagination'     => \Syscover\Market\GraphQL\Queries\OrderStatusesPaginationQuery::class,
                'marketOrderStatuses'               => \Syscover\Market\GraphQL\Queries\OrderStatusesQuery::class,
                'marketOrderStatus'                 => \Syscover\Market\GraphQL\Queries\OrderStatusQuery::class,
            ],
            'mutation' => [
                // ORDER STATUS
                'marketAddOrderStatus'              => \Syscover\Market\GraphQL\Mutations\AddOrderStatusMutation::class,
                'marketUpdateOrderStatus'           => \Syscover\Market\GraphQL\Mutations\UpdateOrderStatusMutation::class,
                'marketDeleteOrderStatus'           => \Syscover\Market\GraphQL\Mutations\DeleteOrderStatusMutation::class,
            ]
        ]));
    }
}