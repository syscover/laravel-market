<?php namespace Syscover\Market\GraphQL\Services;

use Syscover\Core\GraphQL\Services\CoreGraphQLService;
use Syscover\Market\Models\Warehouse;
use Syscover\Market\Services\WarehouseService;

class WarehouseGraphQLService extends CoreGraphQLService
{
    protected $modelClassName = Warehouse::class;
    protected $serviceClassName = WarehouseService::class;
}