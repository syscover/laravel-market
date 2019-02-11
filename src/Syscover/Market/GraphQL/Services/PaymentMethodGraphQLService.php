<?php namespace Syscover\Market\GraphQL\Services;

use Syscover\Core\GraphQL\Services\CoreGraphQLService;
use Syscover\Market\Models\PaymentMethod;
use Syscover\Market\Services\PaymentMethodService;

class PaymentMethodGraphQLService extends CoreGraphQLService
{
    public function __construct(PaymentMethod $model, PaymentMethodService $service)
    {
        $this->model = $model;
        $this->service = $service;
    }
}
