<?php namespace Syscover\Market\GraphQL\Services;

use Syscover\Core\GraphQL\Services\CoreGraphQLService;
use Syscover\Market\Models\Stock;
use Syscover\Market\Services\StockService;

class StockGraphQLService extends CoreGraphQLService
{
    public function __construct(Stock $model, StockService $service)
    {
        $this->model = $model;
        $this->service = $service;
    }

    public function resolveSetStock($root, array $args)
    {
        return $this->service->set($args['payload']);
    }
}
