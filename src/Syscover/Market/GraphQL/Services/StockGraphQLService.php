<?php namespace Syscover\Market\GraphQL\Services;

use Syscover\Core\GraphQL\Services\CoreGraphQLService;
use Syscover\Market\Models\Stock;
use Syscover\Market\Services\StockService;

class StockGraphQLService extends CoreGraphQLService
{
    protected $model = Stock::class;
    protected $service = StockService::class;

    public function resolveSetStock($root, array $args)
    {
        return StockService::set($args['object']);
    }
}