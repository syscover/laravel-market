<?php namespace Syscover\Market\GraphQL\Mutations;

use GraphQL;
use GraphQL\Type\Definition\Type;
use Folklore\GraphQL\Support\Mutation;
use Syscover\Admin\Services\AttachmentService;
use Syscover\Core\Services\SQLService;
use Syscover\Market\Models\ProductLang;
use Syscover\Market\Models\Tag;
use Syscover\Market\Models\Product;
use Syscover\Market\Services\ProductService;

class ProductMutation extends Mutation
{
    public function type()
    {
        return GraphQL::type('MarketProduct');
    }

    public function args()
    {
        return [
            'object' => [
                'name' => 'object',
                'type' => Type::nonNull(GraphQL::type('MarketProductInput'))
            ],
        ];
    }
}

class AddProductMutation extends ProductMutation
{
    protected $attributes = [
        'name' => 'addProduct',
        'description' => 'Add new product'
    ];

    public function resolve($root, $args)
    {
        return ProductService::create($args['object']);
    }
}

class UpdateProductMutation extends ProductMutation
{
    protected $attributes = [
        'name' => 'updateProduct',
        'description' => 'Update product'
    ];

    public function resolve($root, $args)
    {
        return ProductService::update($args['object'], $args['object']['id'], $args['object']['lang_id']);
    }
}

class DeleteProductMutation extends ProductMutation
{
    protected $attributes = [
        'name' => 'deleteProduct',
        'description' => 'Delete product'
    ];

    public function args()
    {
        return [
            'id' => [
                'name' => 'id',
                'type' => Type::nonNull(Type::string())
            ],
            'lang' => [
                'name' => 'lang',
                'type' => Type::nonNull(Type::string())
            ]
        ];
    }

    public function resolve($root, $args)
    {
        // destroy object
        $object = SQLService::destroyRecord($args['id'], Product::class, $args['lang'], ProductLang::class);

        // destroy attachments
        AttachmentService::deleteAttachments($args['id'], Product::class, $args['lang']);

        return $object;
    }
}
