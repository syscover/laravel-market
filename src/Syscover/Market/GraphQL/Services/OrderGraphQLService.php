<?php namespace Syscover\Market\GraphQL\Services;

use Syscover\Core\GraphQL\Services\CoreGraphQLService;
use Syscover\Market\Models\Order;
use Syscover\Market\Services\OrderService;

class OrderGraphQLService extends CoreGraphQLService
{
    protected $model = Order::class;
    protected $service = OrderService::class;
}