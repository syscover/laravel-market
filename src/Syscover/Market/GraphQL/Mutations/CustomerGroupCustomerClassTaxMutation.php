<?php namespace Syscover\Market\GraphQL\Mutations;

use GraphQL;
use GraphQL\Type\Definition\Type;
use Folklore\GraphQL\Support\Mutation;
use Syscover\Market\Models\CustomerGroupCustomerClassTax;

class CustomerGroupCustomerClassTaxMutation extends Mutation
{
    public function type()
    {
        return GraphQL::type('MarketCustomerGroupCustomerClassTax');
    }
}

class AddCustomerGroupCustomerClassTaxMutation extends CustomerGroupCustomerClassTaxMutation
{
    protected $attributes = [
        'name'          => 'addCustomerGroupCustomerClassTax',
        'description'   => 'Add new relation between customer group and customer class tax'
    ];

    public function args()
    {
        return [
            'object' => [
                'name' => 'object',
                'type' => Type::nonNull(GraphQL::type('MarketCustomerGroupCustomerClassTaxInput'))
            ]
        ];
    }

    public function resolve($root, $args)
    {
        return CustomerGroupCustomerClassTax::create($args['object']);
    }
}

class UpdateCustomerGroupCustomerClassTaxMutation extends CustomerGroupCustomerClassTaxMutation
{
    protected $attributes = [
        'name' => 'updateCustomerGroupCustomerClassTax',
        'description' => 'Update relation between customer group and customer class tax'
    ];

    public function args()
    {
        return [
            'customer_group_id' => [
                'name' => 'customer_group_id',
                'type' => Type::nonNull(Type::int())
            ],
            'customer_class_tax_id' => [
                'name' => 'customer_class_tax_id',
                'type' => Type::nonNull(Type::int())
            ],
            'object' => [
                'name' => 'object',
                'type' => Type::nonNull(GraphQL::type('MarketCustomerGroupCustomerClassTaxInput'))
            ]
        ];
    }

    public function resolve($root, $args)
    {
        CustomerGroupCustomerClassTax::where('customer_group_id', $args['customer_group_id'])
            ->where('customer_class_tax_id', $args['customer_class_tax_id'])
            ->update($args['object']);

        return CustomerGroupCustomerClassTax::where('customer_group_id', $args['object']['customer_group_id'])
            ->where('customer_class_tax_id', $args['object']['customer_class_tax_id'])
            ->first();
    }
}

class DeleteCustomerGroupCustomerClassTaxMutation extends CustomerGroupCustomerClassTaxMutation
{
    protected $attributes = [
        'name' => 'deleteCustomerGroupCustomerClassTax',
        'description' => 'Delete relation between customer group and customer class tax'
    ];

    public function args()
    {
        return [
            'customer_group_id' => [
                'name' => 'customer_group_id',
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
        $object = CustomerGroupCustomerClassTax::builder()
            ->where('customer_group_id', $args['customer_group_id'])
            ->where('customer_class_tax_id', $args['customer_class_tax_id'])
            ->first();

        $object->delete();

        return $object;
    }
}
