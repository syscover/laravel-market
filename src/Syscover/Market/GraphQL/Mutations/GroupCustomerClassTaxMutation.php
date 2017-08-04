<?php namespace Syscover\Market\GraphQL\Mutations;

use GraphQL;
use GraphQL\Type\Definition\Type;
use Folklore\GraphQL\Support\Mutation;
use Syscover\Market\Models\GroupCustomerClassTax;

class GroupCustomerClassTaxMutation extends Mutation
{
    public function type()
    {
        return GraphQL::type('MarketGroupCustomerClassTax');
    }
}

class AddGroupCustomerClassTaxMutation extends GroupCustomerClassTaxMutation
{
    protected $attributes = [
        'name'          => 'addGroupCustomerClassTax',
        'description'   => 'Add new group customer class tax'
    ];

    public function args()
    {
        return [
            'object' => [
                'name' => 'object',
                'type' => Type::nonNull(GraphQL::type('MarketGroupCustomerClassTaxInput'))
            ]
        ];
    }

    public function resolve($root, $args)
    {
        return GroupCustomerClassTax::create($args['object']);
    }
}

class UpdateGroupCustomerClassTaxMutation extends GroupCustomerClassTaxMutation
{
    protected $attributes = [
        'name' => 'updateGroupCustomerClassTax',
        'description' => 'Update group customer class tax'
    ];

    public function args()
    {
        return [
            'group_id' => [
                'name' => 'group_id',
                'type' => Type::nonNull(Type::int())
            ],
            'customer_class_tax_id' => [
                'name' => 'customer_class_tax_id',
                'type' => Type::nonNull(Type::int())
            ],
            'object' => [
                'name' => 'object',
                'type' => Type::nonNull(GraphQL::type('MarketGroupCustomerClassTaxInput'))
            ]
        ];
    }

    public function resolve($root, $args)
    {
        GroupCustomerClassTax::where('group_id', $args['group_id'])
            ->where('customer_class_tax_id', $args['customer_class_tax_id'])
            ->update($args['object']);

        return GroupCustomerClassTax::where('group_id', $args['object']['group_id'])
            ->where('customer_class_tax_id', $args['object']['customer_class_tax_id'])
            ->first();
    }
}

class DeleteGroupCustomerClassTaxMutation extends GroupCustomerClassTaxMutation
{
    protected $attributes = [
        'name' => 'deleteGroupCustomerClassTax',
        'description' => 'Delete group customer class tax'
    ];

    public function args()
    {
        return [
            'group_id' => [
                'name' => 'group_id',
                'type' => Type::nonNull(Type::int())
            ],
            'customer_class_tax_id' => [
                'name' => 'customer_class_tax_id',
                'type' => Type::nonNull(Type::int())
            ],
        ];
    }

    public function resolve($root, $args)
    {
        // Custom delete a single record
        $object = GroupCustomerClassTax::builder()
            ->where('group_id', $args['group_id'])
            ->where('customer_class_tax_id', $args['customer_class_tax_id'])
            ->first();

        $object->delete();

        return $object;
    }
}
