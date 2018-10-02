<?php namespace Syscover\Market\GraphQL\Services;

use Syscover\Core\GraphQL\Services\CoreGraphQLService;
use Syscover\Market\Models\TaxRateZone;
use Syscover\Market\Services\TaxRateZoneService;

class TaxRateZoneGraphQLService extends CoreGraphQLService
{
    protected $modelClassName = TaxRateZone::class;
    protected $serviceClassName = TaxRateZoneService::class;
}