<?php namespace Syscover\Market\GraphQL\Services;

use Syscover\Core\GraphQL\Services\CoreGraphQLService;
use Syscover\Market\Models\OrderStatus;
use Syscover\Market\Services\OrderStatusService;

class OrderStatusGraphQLService extends CoreGraphQLService
{
    public function __construct(OrderStatus $model, OrderStatusService $service)
    {
        $this->model = $model;
        $this->service = $service;
    }
}
