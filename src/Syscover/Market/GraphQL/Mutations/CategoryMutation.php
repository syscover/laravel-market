<?php namespace Syscover\Market\GraphQL\Mutations;

use GraphQL;
use GraphQL\Type\Definition\Type;
use Folklore\GraphQL\Support\Mutation;
use Syscover\Core\Services\SQLService;
use Syscover\Market\Models\Category;
use Syscover\Market\Services\CategoryService;

class CategoryMutation extends Mutation
{
    public function type()
    {
        return GraphQL::type('MarketCategory');
    }

    public function args()
    {
        return [
            'object' => [
                'name' => 'object',
                'type' => Type::nonNull(GraphQL::type('MarketCategoryInput'))
            ],
        ];
    }
}

class AddCategoryMutation extends CategoryMutation
{
    protected $attributes = [
        'name'          => 'addCategory',
        'description'   => 'Add new category'
    ];

    public function resolve($root, $args)
    {
        return CategoryService::create($args['object']);
    }
}

class UpdateCategoryMutation extends CategoryMutation
{
    protected $attributes = [
        'name' => 'updateCategory',
        'description' => 'Update category'
    ];

    public function resolve($root, $args)
    {
        return CategoryService::update($args['object']);
    }
}

class DeleteCategoryMutation extends CategoryMutation
{
    protected $attributes = [
        'name' => 'deleteCategory',
        'description' => 'Delete category'
    ];

    public function args()
    {
        return [
            'lang_id' => [
                'name' => 'lang_id',
                'type' => Type::nonNull(Type::string())
            ],
            'id' => [
                'name' => 'id',
                'type' => Type::nonNull(Type::int())
            ],
        ];
    }

    public function resolve($root, $args)
    {
        $object = SQLService::destroyRecord($args['id'], Category::class, $args['lang_id']);

        return $object;
    }
}
