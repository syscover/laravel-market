<?php namespace Syscover\Market\GraphQL\Services;

use Syscover\Core\GraphQL\Services\CoreGraphQLService;
use Syscover\Market\Models\OrderRow;
use Syscover\Market\Services\OrderRowService;

class OrderRowGraphQLService extends CoreGraphQLService
{
    protected $model = OrderRow::class;
    protected $service = OrderRowService::class;
}