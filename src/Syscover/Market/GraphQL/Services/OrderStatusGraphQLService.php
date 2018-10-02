<?php namespace Syscover\Market\GraphQL\Services;

use Syscover\Core\GraphQL\Services\CoreGraphQLService;
use Syscover\Market\Models\OrderStatus;
use Syscover\Market\Services\OrderStatusService;

class OrderStatusGraphQLService extends CoreGraphQLService
{
    protected $modelClassName = OrderStatus::class;
    protected $serviceClassName = OrderStatusService::class;
}