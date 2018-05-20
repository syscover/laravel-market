<?php namespace Syscover\Market\GraphQL\Inputs;

use GraphQL\Type\Definition\Type;
use Folklore\GraphQL\Support\Type as GraphQLType;

class CustomerGroupCustomerClassTaxInput extends GraphQLType
{
    protected $attributes = [
        'name'          => 'CustomerGroupCustomerClassTaxInput',
        'description'   => 'Customer class tax relations between customer group and customer class tax'
    ];

    protected $inputObject = true;

    public function fields()
    {
        return [
            'customer_group_id' => [
                'type' => Type::int(),
                'description' => 'The id of customer group'
            ],
            'customer_class_tax_id' => [
                'type' => Type::int(),
                'description' => 'The id of customer class tax'
            ]
        ];
    }
}