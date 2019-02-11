<?php namespace Syscover\Market\GraphQL\Services;

use Syscover\Core\GraphQL\Services\CoreGraphQLService;
use Syscover\Market\Models\Order;
use Syscover\Market\Services\OrderService;

class OrderGraphQLService extends CoreGraphQLService
{
    public function __construct(Order $model, OrderService $service)
    {
        $this->model = $model;
        $this->service = $service;
    }
}
