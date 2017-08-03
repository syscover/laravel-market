<?php namespace Syscover\Market\GraphQL\Types;

use GraphQL\Type\Definition\Type;
use Folklore\GraphQL\Support\Type as GraphQLType;

class ProductClassTaxType extends GraphQLType {

    protected $attributes = [
        'name'          => 'ProductClassTax',
        'description'   => 'Product class tax to be related with product'
    ];

    public function fields()
    {
        return [
            'id' => [
                'type' => Type::nonNull(Type::int()),
                'description' => 'The id of product class tax'
            ],
            'name' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'The name of product class tax'
            ]
        ];
    }
}