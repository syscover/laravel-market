<?php namespace Syscover\Market\GraphQL\Services;

use Syscover\Core\GraphQL\Services\CoreGraphQLService;
use Syscover\Market\Models\Order;
use Syscover\Market\Services\OrderService;

class OrderGraphQLService extends CoreGraphQLService
{
    protected $modelClassName = Order::class;
    protected $serviceClassName = OrderService::class;
}