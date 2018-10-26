<?php namespace Syscover\Market\GraphQL\Services;

use Syscover\Core\GraphQL\Services\CoreGraphQLService;
use Syscover\Market\Services\PayPalWebProfileService;

class PayPalWebProfileGraphQLService extends CoreGraphQLService
{
    protected $serviceClassName = PayPalWebProfileService::class;

    public function paginate($root, array $args)
    {
        $webProfiles = collect($this->service->list());

        $webProfiles = $webProfiles->map(function($webProfile) {
            return $webProfile->toArray();
        });

        $total = count($webProfiles);

        return (Object) [
            'total'     => $total,
            'objects'   => $webProfiles,
            'filtered'  => 10
        ];
    }
}