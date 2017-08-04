<?php namespace Syscover\Market\GraphQL\Types;

use GraphQL\Type\Definition\Type;
use Folklore\GraphQL\Support\Type as GraphQLType;

class GroupCustomerClassTaxType extends GraphQLType {

    protected $attributes = [
        'name'          => 'GroupCustomerClassTaxType',
        'description'   => 'Customer class tax relations between customer group and customer class tax'
    ];

    public function fields()
    {
        return [
            'group_id' => [
                'type' => Type::nonNull(Type::int()),
                'description' => 'The id of group customer class tax'
            ],
            'group_name' => [
                'type' => Type::string(),
                'description' => 'The name of group'
            ],
            'customer_class_tax_id' => [
                'type' => Type::nonNull(Type::int()),
                'description' => 'The id of customer class tax'
            ],
            'customer_class_tax_name' => [
                'type' => Type::string(),
                'description' => 'The name of customer class tax'
            ]
        ];
    }
}