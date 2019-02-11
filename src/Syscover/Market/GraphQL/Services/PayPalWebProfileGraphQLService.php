<?php namespace Syscover\Market\GraphQL\Services;

use Syscover\Core\GraphQL\Services\CoreGraphQLService;
use Syscover\Market\Services\PayPalWebProfileService;

class PayPalWebProfileGraphQLService extends CoreGraphQLService
{
    public function __construct(PayPalWebProfileService $service)
    {
        $this->service = $service;
    }

    public function paginate($root, array $args)
    {
        $webProfiles = collect($this->service->get());

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

    public function find($root, array $args)
    {
        $operation = collect($args['sql'])->first();

        if(isset($operation['value']))
        {
            return PayPalWebProfileService::find($operation['value']);
        }
        return null;
    }

    public function create($root, array $args)
    {
        return PayPalWebProfileService::create($args['payload']);
    }

    public function update($root, array $args)
    {
        return PayPalWebProfileService::update($args['payload']);
    }

    public function delete($root, array $args)
    {
        return PayPalWebProfileService::delete($args['id']);
    }
}
