<?php namespace Syscover\Market\GraphQL\Mutations;

use GraphQL;
use GraphQL\Type\Definition\Type;
use Folklore\GraphQL\Support\Mutation;
use Syscover\Core\Services\SQLService;
use Syscover\Market\Models\ProductClassTax;

class ProductClassTaxMutation extends Mutation
{
    public function type()
    {
        return GraphQL::type('MarketProductClassTax');
    }

    public function args()
    {
        return [
            'object' => [
                'name' => 'object',
                'type' => Type::nonNull(GraphQL::type('MarketProductClassTaxInput'))
            ],
        ];
    }
}

class AddProductClassTaxMutation extends ProductClassTaxMutation
{
    protected $attributes = [
        'name'          => 'addProductClassTax',
        'description'   => 'Add new product class tax'
    ];

    public function resolve($root, $args)
    {
        return ProductClassTax::create($args['object']);
    }
}

class UpdateProductClassTaxMutation extends ProductClassTaxMutation
{
    protected $attributes = [
        'name' => 'updateProductClassTax',
        'description' => 'Update product class tax'
    ];

    public function resolve($root, $args)
    {
        ProductClassTax::where('id', $args['object']['id'])
            ->update($args['object']);

        return ProductClassTax::find($args['object']['id']);
    }
}

class DeleteProductClassTaxMutation extends ProductClassTaxMutation
{
    protected $attributes = [
        'name' => 'deleteProductClassTax',
        'description' => 'Delete product class tax'
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
        $object = SQLService::destroyRecord($args['id'], ProductClassTax::class);

        return $object;
    }
}
