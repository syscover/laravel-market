<?php namespace Syscover\Market\GraphQL\Mutations;

use GraphQL;
use GraphQL\Type\Definition\Type;
use Folklore\GraphQL\Support\Mutation;
use Syscover\Core\Services\SQLService;
use Syscover\Market\Models\TaxRule;

class TaxRuleMutation extends Mutation
{
    public function type()
    {
        return GraphQL::type('MarketTaxRule');
    }

    public function args()
    {
        return [
            'object' => [
                'name' => 'object',
                'type' => Type::nonNull(GraphQL::type('MarketTaxRuleInput'))
            ],
        ];
    }
}

class AddTaxRuleMutation extends TaxRuleMutation
{
    protected $attributes = [
        'name'          => 'addTaxRule',
        'description'   => 'Add new tax rule'
    ];

    public function resolve($root, $args)
    {
        $object = TaxRule::create($args['object']);

        $object->tax_rate_zones()->sync($args['object']['tax_rate_zones_id']);
        $object->customer_class_taxes()->sync($args['object']['customer_class_taxes_id']);
        $object->product_class_taxes()->sync($args['object']['product_class_taxes_id']);

        return $object;
    }
}

class UpdateTaxRuleMutation extends TaxRuleMutation
{
    protected $attributes = [
        'name' => 'updateTaxRule',
        'description' => 'Update tax rule'
    ];

    public function resolve($root, $args)
    {
        TaxRule::where('id', $args['object']['id'])
            ->update([
                'name'          => $args['object']['name'],
                'translation'   => $args['object']['translation'],
                'priority'      => $args['object']['priority'],
                'sort'          => $args['object']['sort']
            ]);

        $object = TaxRule::where('id', $args['object']['id'])
            ->first();

        $object->tax_rate_zones()->sync($args['object']['tax_rate_zones_id']);
        $object->customer_class_taxes()->sync($args['object']['customer_class_taxes_id']);
        $object->product_class_taxes()->sync($args['object']['product_class_taxes_id']);

        return $object;
    }
}

class DeleteTaxRuleMutation extends TaxRuleMutation
{
    protected $attributes = [
        'name' => 'deleteTaxRule',
        'description' => 'Delete tax rule'
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
        $object = SQLService::destroyRecord($args['id'], TaxRule::class);

        $object->tax_rate_zones()->detach();
        $object->customer_class_taxes()->detach();
        $object->product_class_taxes()->detach();

        return $object;
    }
}
