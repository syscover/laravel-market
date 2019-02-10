<?php namespace Syscover\Market\GraphQL\Services;

use Syscover\Core\GraphQL\Services\CoreGraphQLService;
use Syscover\Market\Models\PaymentMethod;
use Syscover\Market\Services\PaymentMethodService;

class PaymentMethodGraphQLService extends CoreGraphQLService
{
    protected $model = PaymentMethod::class;
    protected $serviceClassName = PaymentMethodService::class;
}
