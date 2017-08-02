<?php namespace Syscover\Market\GraphQL\Inputs;

use GraphQL\Type\Definition\Type;
use Folklore\GraphQL\Support\Type as GraphQLType;

class ProductClassTaxInput extends GraphQLType
{
    protected $attributes = [
        'name'          => 'ProductClassTaxInput',
        'description'   => 'Product class tax to be related with product'
    ];

    protected $inputObject = true;

    public function fields()
    {
        return [
            'id' => [
                'type' => Type::int(),
                'description' => 'The id of product class tax'
            ],

            'name' => [
                'type' => Type::string(),
                'description' => 'The name of product class tax'
            ]
        ];
    }
}