<?php namespace Syscover\Market\GraphQL\Services;

use Syscover\Core\GraphQL\Services\CoreGraphQLService;
use Syscover\Market\Models\CustomerDiscountHistory;
use Syscover\Market\Services\CustomerDiscountHistoryService;

class CustomerDiscountHistoryGraphQLService extends CoreGraphQLService
{
    public function __construct(CustomerDiscountHistory $model, CustomerDiscountHistoryService $service)
    {
        $this->model = $model;
        $this->service = $service;
    }
}
