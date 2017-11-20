<?php namespace Syscover\Market\GraphQL\Inputs;

use GraphQL\Type\Definition\Type;
use Folklore\GraphQL\Support\Type as GraphQLType;
use Syscover\Core\GraphQL\ScalarTypes\ObjectType;

class ProductInput extends GraphQLType {

    protected $attributes = [
        'name'          => 'Product',
        'description'   => 'Product'
    ];

    protected $inputObject = true;

    public function fields()
    {
        return [
            'id' => [
                'type' => Type::int(),
                'description' => 'The id of product'
            ],
            'object_id' => [
                'type' => Type::int(),
                'description' => 'The id of product for translate object'
            ],
            'lang_id' => [
                'type' => Type::string(),
                'description' => 'lang of product'
            ],
            'name' => [
                'type' => Type::string(),
                'description' => 'The name of product'
            ],
            'slug' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'The name of product'
            ],
            'code' => [
                'type' => Type::string(),
                'description' => 'bar code'
            ],
            'categories_id' => [
                'type' => Type::listOf(Type::int()),
                'description' => 'Id of categories of product'
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
            'price' => [
                'type' => Type::float(),
                'description' => 'price of product'
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
            'attachments' => [
                'type' => Type::listOf(app(ObjectType::class)),
                'description' => 'List of attachments added to article'
            ],
            'customFields' => [
                'type' => app(ObjectType::class),
                'description' => 'Properties from custom fields'
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