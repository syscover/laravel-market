<?php namespace Syscover\Market\GraphQL\Types;

use GraphQL;
use GraphQL\Type\Definition\Type;
use Folklore\GraphQL\Support\Type as GraphQLType;

class TaxRuleType extends GraphQLType {

    protected $attributes = [
        'name'          => 'TaxRuleType',
        'description'   => 'Tax rule'
    ];

    public function fields()
    {
        return [
            'id' => [
                'type' => Type::nonNull(Type::int()),
                'description' => 'The id of tax rule'
            ],
            'name' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'The name of tax rule'
            ],
            'translation' => [
                'type' => Type::string(),
                'description' => 'Translation key of tax rule'
            ],
            'priority' => [
                'type' => Type::nonNull(Type::int()),
                'description' => 'Priority to apply thi tax rule'
            ],
            'sort' => [
                'type' => Type::nonNull(Type::int()),
                'description' => 'Sort to appear this tax rule'
            ],
            'tax_rate_zones' => [
                'type' => Type::listOf(GraphQL::type('MarketTaxRateZone')),
                'description' => 'List of tax rate zone'
            ],
            'customer_class_taxes' => [
                'type' => Type::listOf(GraphQL::type('MarketCustomerClassTax')),
                'description' => 'List of tax rate zone'
            ],
            'product_class_taxes' => [
                'type' => Type::listOf(GraphQL::type('MarketProductClassTax')),
                'description' => 'List of tax rate zone'
            ]
        ];
    }
}