<?php namespace Syscover\Market\GraphQL\Inputs;

use GraphQL\Type\Definition\Type;
use Folklore\GraphQL\Support\Type as GraphQLType;

class CustomerClassTaxInput extends GraphQLType
{
    protected $attributes = [
        'name'          => 'CustomerClassTaxInput',
        'description'   => 'Customer class tax to be related with customer group'
    ];

    protected $inputObject = true;

    public function fields()
    {
        return [
            'id' => [
                'type' => Type::int(),
                'description' => 'The id of customer class tax'
            ],

            'name' => [
                'type' => Type::string(),
                'description' => 'The name of customer class tax'
            ]
        ];
    }
}