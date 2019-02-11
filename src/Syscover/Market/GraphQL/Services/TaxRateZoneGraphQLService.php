<?php namespace Syscover\Market\GraphQL\Services;

use Syscover\Core\GraphQL\Services\CoreGraphQLService;
use Syscover\Market\Models\TaxRateZone;
use Syscover\Market\Services\TaxRateZoneService;

class TaxRateZoneGraphQLService extends CoreGraphQLService
{
    public function __construct(TaxRateZone $model, TaxRateZoneService $service)
    {
        $this->model = $model;
        $this->service = $service;
    }
}
