<?php namespace Syscover\Market\GraphQL\Types;

use GraphQL\Type\Definition\Type;
use Folklore\GraphQL\Support\Type as GraphQLType;
use Syscover\Core\GraphQL\ScalarTypes\ObjectType;

class CategoryType extends GraphQLType {

    protected $attributes = [
        'name'          => 'Category',
        'description'   => 'Category of product'
    ];

    public function fields()
    {
        return [
            'ix' => [
                'type' => Type::nonNull(Type::int()),
                'description' => 'The index of category'
            ],
            'id' => [
                'type' => Type::nonNull(Type::int()),
                'description' => 'The id of category'
            ],
            'lang_id' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'lang of category'
            ],
            'parent_id' => [
                'type' => Type::int(),
                'description' => 'parent category'
            ],
            'name' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'The name of category'
            ],
            'slug' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'The name of category'
            ],
            'active' => [
                'type' => Type::boolean(),
                'description' => 'Active category'
            ],
            'description' => [
                'type' => Type::string(),
                'description' => 'Description of category'
            ],
            'data_lang' => [
                'type' => Type::listOf(Type::string()),
                'description' => 'JSON string that contain information about object translations'
            ],
            'data' => [
                'type' => app(ObjectType::class),
                'description' => 'JSON string'
            ]
        ];
    }
}