<?php namespace Syscover\Market\GraphQL\Types;

use GraphQL;
use GraphQL\Type\Definition\Type;
use Folklore\GraphQL\Support\Type as GraphQLType;
use Syscover\Core\GraphQL\ScalarTypes\ObjectType;

class ProductType extends GraphQLType {

    protected $attributes = [
        'name'          => 'Product',
        'description'   => 'Product'
    ];

    public function fields()
    {
        return [
            'ix' => [
                'type' => Type::nonNull(Type::int()),
                'description' => 'The index of product'
            ],
            'id' => [
                'type' => Type::nonNull(Type::int()),
                'description' => 'The id of product'
            ],
            'lang_id' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'lang of product'
            ],
            'name' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'The name of product'
            ],
            'slug' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'The name of product'
            ],
            'sku' => [
                'type' => Type::string(),
                'description' => 'Stock keeping unit'
            ],
            'categories' => [
                'type' => Type::listOf(GraphQL::type('MarketCategory')),
                'description' => 'Categories of product'
            ],
            'sections' => [
                'type' => Type::listOf(GraphQL::type('MarketSection')),
                'description' => 'Sections of product'
            ],
            'field_group_id' => [
                'type' => Type::int(),
                'description' => 'Id of field group'
            ],
            'type_id' => [
                'type' => Type::nonNull(Type::int()),
                'description' => 'Id of product type'
            ],
            'parent_id' => [
                'type' => Type::int(),
                'description' => 'Id of parent product'
            ],
            'weight' => [
                'type' => Type::nonNull(Type::float()),
                'description' => 'Id of parent product'
            ],
            'active' => [
                'type' => Type::boolean(),
                'description' => 'Active product'
            ],
            'sort' => [
                'type' => Type::int(),
                'description' => 'sort or product'
            ],
            'price_type_id' => [
                'type' => Type::nonNull(Type::int()),
                'description' => 'Id of parent product'
            ],
            'subtotal' => [
                'type' => Type::float(),
                'description' => 'Subtotal of product'
            ],
            'product_class_tax_id' => [
                'type' => Type::int(),
                'description' => 'Id of parent product'
            ],
            'description' => [
                'type' => Type::string(),
                'description' => 'Description of product'
            ],
            'data_lang' => [
                'type' => Type::listOf(Type::string()),
                'description' => 'JSON string that contain information about object translations'
            ],
            'data' => [
                'type' => app(ObjectType::class),
                'description' => 'JSON string'
            ],
            'attachments' => [
                'type' => Type::listOf(GraphQL::type('AdminAttachment')),
                'description' => 'List of attachments that has this article'
            ]
        ];
    }

    public function resolveCategoriesField($object, $args)
    {
        return $object->categories->where('lang_id', $object->lang_id);
    }
}