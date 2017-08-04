<?php namespace Syscover\Market\GraphQL\Inputs;

use GraphQL\Type\Definition\Type;
use Folklore\GraphQL\Support\Type as GraphQLType;

class GroupCustomerClassTaxInput extends GraphQLType
{
    protected $attributes = [
        'name'          => 'CustomerClassTaxInput',
        'description'   => 'Customer class tax relations between customer group and customer class tax'
    ];

    protected $inputObject = true;

    public function fields()
    {
        return [
            'group_id' => [
                'type' => Type::int(),
                'description' => 'The id of group customer class tax'
            ],
            'customer_class_tax_id' => [
                'type' => Type::int(),
                'description' => 'The name of customer class tax'
            ]
        ];
    }
}