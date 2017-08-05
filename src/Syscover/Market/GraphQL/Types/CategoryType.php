<?php namespace Syscover\Market\GraphQL\Types;

use GraphQL\Type\Definition\Type;
use Folklore\GraphQL\Support\Type as GraphQLType;

class CategoryType extends GraphQLType {

    protected $attributes = [
        'name'          => 'Category',
        'description'   => 'Category that user can to do in application'
    ];

    public function fields()
    {
        return [
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
                'type' => Type::string(),
                'description' => 'The name of category'
            ],
            'sort' => [
                'type' => Type::int(),
                'description' => 'Activate sort in article'
            ],
            'data_lang' => [
                'type' => Type::listOf(Type::string()),
                'description' => 'JSON string that contain information about object translations'
            ]
        ];
    }
}