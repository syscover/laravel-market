<?php namespace Syscover\Market\GraphQL\Services;

use Syscover\Core\GraphQL\Services\CoreGraphQLService;
use Syscover\Market\Models\Warehouse;
use Syscover\Market\Services\WarehouseService;

class WarehouseGraphQLService extends CoreGraphQLService
{
    public function __construct(Warehouse $model, WarehouseService $service)
    {
        $this->model = $model;
        $this->service = $service;
    }
}
