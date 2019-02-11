<?php namespace Syscover\Market\GraphQL\Services;

use Syscover\Core\GraphQL\Services\CoreGraphQLService;
use Syscover\Market\Models\CustomerDiscountHistory;
use Syscover\Market\Services\CustomerDiscountHistoryService;

class CustomerDiscountHistoryGraphQLService extends CoreGraphQLService
{
    protected $model = CustomerDiscountHistory::class;
    protected $service = CustomerDiscountHistoryService::class;
}
