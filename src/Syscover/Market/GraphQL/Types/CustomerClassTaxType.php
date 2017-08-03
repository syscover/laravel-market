<?php namespace Syscover\Market\GraphQL\Types;

use GraphQL\Type\Definition\Type;
use Folklore\GraphQL\Support\Type as GraphQLType;

class CustomerClassTaxType extends GraphQLType {

    protected $attributes = [
        'name'          => 'CustomerClassTax',
        'description'   => 'Customer class tax to be related with customer group'
    ];

    public function fields()
    {
        return [
            'id' => [
                'type' => Type::nonNull(Type::int()),
                'description' => 'The id of customer class tax'
            ],
            'name' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'The name of customer class tax'
            ]
        ];
    }
}