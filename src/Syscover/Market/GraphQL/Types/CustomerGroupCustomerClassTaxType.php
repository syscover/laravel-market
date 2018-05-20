<?php namespace Syscover\Market\GraphQL\Types;

use GraphQL\Type\Definition\Type;
use Folklore\GraphQL\Support\Type as GraphQLType;

class CustomerGroupCustomerClassTaxType extends GraphQLType {

    protected $attributes = [
        'name'          => 'CustomerGroupCustomerClassTaxType',
        'description'   => 'Customer class tax relations between customer group and customer class tax'
    ];

    public function fields()
    {
        return [
            'customer_group_id' => [
                'type' => Type::nonNull(Type::int()),
                'description' => 'The id of customer group'
            ],
            'customer_group_name' => [
                'type' => Type::string(),
                'description' => 'The name of customer group'
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