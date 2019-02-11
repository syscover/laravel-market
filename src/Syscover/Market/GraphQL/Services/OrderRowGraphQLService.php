<?php namespace Syscover\Market\GraphQL\Services;

use Syscover\Core\GraphQL\Services\CoreGraphQLService;
use Syscover\Market\Models\OrderRow;
use Syscover\Market\Services\OrderRowService;

class OrderRowGraphQLService extends CoreGraphQLService
{
    public function __construct(OrderRow $model, OrderRowService $service)
    {
        $this->model = $model;
        $this->service = $service;
    }
}
