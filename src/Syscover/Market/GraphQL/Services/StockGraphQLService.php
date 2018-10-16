<?php namespace Syscover\Market\GraphQL\Services;

use Syscover\Core\GraphQL\Services\CoreGraphQLService;
use Syscover\Market\Models\Stock;
use Syscover\Market\Services\StockService;

class StockGraphQLService extends CoreGraphQLService
{
    protected $modelClassName = Stock::class;
    protected $serviceClassName = StockService::class;

    public function resolveSetStock($root, array $args)
    {
        return $this->service->set($args['payload']);
    }
}