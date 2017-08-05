<?php namespace Syscover\Market\GraphQL\Inputs;

use GraphQL\Type\Definition\Type;
use Folklore\GraphQL\Support\Type as GraphQLType;

class TaxRuleInput extends GraphQLType
{
    protected $attributes = [
        'name'          => 'TaxRateZoneInput',
        'description'   => 'Tax rate zone to do tax rules'
    ];

    protected $inputObject = true;

    public function fields()
    {
        return [
            'id' => [
                'type' => Type::int(),
                'description' => 'The id of tax rule'
            ],
            'name' => [
                'type' => Type::string(),
                'description' => 'The name of tax rule'
            ],
            'translation' => [
                'type' => Type::string(),
                'description' => 'Translation key of tax rule'
            ],
            'priority' => [
                'type' => Type::int(),
                'description' => 'Priority to apply thi tax rule'
            ],
            'sort' => [
                'type' => Type::int(),
                'description' => 'Sort to appear this tax rule'
            ],
            'tax_rate_zones_id' => [
                'type' => Type::listOf(Type::int()),
                'description' => 'List of tax rate zone id'
            ],
            'customer_class_taxes_id' => [
                'type' => Type::listOf(Type::int()),
                'description' => 'List of customer class tax id'
            ],
            'product_class_taxes_id' => [
                'type' => Type::listOf(Type::int()),
                'description' => 'List of product class tax id'
            ]
        ];
    }
}