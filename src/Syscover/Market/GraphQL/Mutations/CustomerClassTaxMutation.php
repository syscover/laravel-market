<?php namespace Syscover\Market\GraphQL\Mutations;

use GraphQL;
use GraphQL\Type\Definition\Type;
use Folklore\GraphQL\Support\Mutation;
use Syscover\Core\Services\SQLService;
use Syscover\Market\Models\CustomerClassTax;

class CustomerClassTaxMutation extends Mutation
{
    public function type()
    {
        return GraphQL::type('MarketCustomerClassTax');
    }

    public function args()
    {
        return [
            'object' => [
                'name' => 'object',
                'type' => Type::nonNull(GraphQL::type('MarketCustomerClassTaxInput'))
            ],
        ];
    }
}

class AddCustomerClassTaxMutation extends CustomerClassTaxMutation
{
    protected $attributes = [
        'name'          => 'addCustomerClassTax',
        'description'   => 'Add new customer class tax'
    ];

    public function resolve($root, $args)
    {
        return CustomerClassTax::create($args['object']);
    }
}

class UpdateCustomerClassTaxMutation extends CustomerClassTaxMutation
{
    protected $attributes = [
        'name' => 'updateCustomerClassTax',
        'description' => 'Update customer class tax'
    ];

    public function resolve($root, $args)
    {
        CustomerClassTax::where('id', $args['object']['id'])
            ->update($args['object']);

        return CustomerClassTax::find($args['object']['id']);
    }
}

class DeleteCustomerClassTaxMutation extends CustomerClassTaxMutation
{
    protected $attributes = [
        'name' => 'deleteCustomerClassTax',
        'description' => 'Delete customer class tax'
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
        $object = SQLService::destroyRecord($args['id'], CustomerClassTax::class);

        return $object;
    }
}
