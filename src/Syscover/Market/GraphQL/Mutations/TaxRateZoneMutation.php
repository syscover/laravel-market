<?php namespace Syscover\Market\GraphQL\Mutations;

use GraphQL;
use GraphQL\Type\Definition\Type;
use Folklore\GraphQL\Support\Mutation;
use Syscover\Core\Services\SQLService;
use Syscover\Market\Models\TaxRateZone;

class TaxRateZoneMutation extends Mutation
{
    public function type()
    {
        return GraphQL::type('MarketTaxRateZone');
    }

    public function args()
    {
        return [
            'object' => [
                'name' => 'object',
                'type' => Type::nonNull(GraphQL::type('MarketTaxRateZoneInput'))
            ],
        ];
    }
}

class AddTaxRateZoneMutation extends TaxRateZoneMutation
{
    protected $attributes = [
        'name'          => 'addTaxRateZone',
        'description'   => 'Add new tax rate zone'
    ];

    public function resolve($root, $args)
    {
        return TaxRateZone::create($args['object']);
    }
}

class UpdateTaxRateZoneMutation extends TaxRateZoneMutation
{
    protected $attributes = [
        'name' => 'updateTaxRateZone',
        'description' => 'Update tax rate zone'
    ];

    public function resolve($root, $args)
    {
        TaxRateZone::where('id', $args['object']['id'])
            ->update($args['object']);

        return TaxRateZone::where('id', $args['object']['id'])
            ->first();
    }
}

class DeleteTaxRateZoneMutation extends TaxRateZoneMutation
{
    protected $attributes = [
        'name' => 'deleteTaxRateZone',
        'description' => 'Delete tax rate zone'
    ];

    public function args()
    {
        return [
            'id' => [
                'name' => 'id',
                'type' => Type::nonNull(Type::int())
            ]
        ];
    }

    public function resolve($root, $args)
    {
        $object = SQLService::destroyRecord($args['id'], TaxRateZone::class);

        return $object;
    }
}
