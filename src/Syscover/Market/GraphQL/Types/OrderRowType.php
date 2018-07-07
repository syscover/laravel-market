<?php namespace Syscover\Market\GraphQL\Types;

use GraphQL\Type\Definition\Type;
use Folklore\GraphQL\Support\Type as GraphQLType;
use Syscover\Core\GraphQL\ScalarTypes\ObjectType;

class OrderRowType extends GraphQLType
{
    protected $attributes = [
        'name' => 'OrderRow',
        'description' => 'OrderRow'
    ];

    public function fields()
    {
        return [
            'id' => [
                'type' => Type::nonNull(Type::int()),
                'description' => 'The id of order row'
            ],
            'lang_id' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'The lang of order row'
            ],
            'order_id' => [
                'type' => Type::nonNull(Type::int()),
                'description' => 'The id of order that below this order row'
            ],
            'product_id' => [
                'type' => Type::int(),
                'description' => 'The id of product'
            ],

            //****************
            //* Product
            //****************
            'name' => [
                'type' => Type::string(),
                'description' => 'Name of product'
            ],
            'description' => [
                'type' => Type::string(),
                'description' => 'Description of product'
            ],
            'data' => [
                'type' => app(ObjectType::class),
                'description' => 'Data of order row'
            ],

            //****************
            //* amounts
            //****************
            'price' => [
                'type' => Type::nonNull(Type::float()),
                'description' => 'Unit price'
            ],
            'quantity' => [
                'type' => Type::nonNull(Type::float()),
                'description' => 'Number of units'
            ],
            'subtotal' => [
                'type' => Type::nonNull(Type::float()),
                'description' => 'Subtotal without tax'
            ],
            'total_without_discounts' => [
                'type' => Type::nonNull(Type::float()),
                'description' => 'Total from row without discounts'
            ],

            //****************
            //* discounts
            //****************
            'discount_subtotal_percentage' => [
                'type' => Type::nonNull(Type::float()),
                'description' => ''
            ],
            'discount_total_percentage' => [
                'type' => Type::nonNull(Type::float()),
                'description' => ''
            ],
            'discount_subtotal_percentage_amount' => [
                'type' => Type::nonNull(Type::float()),
                'description' => ''
            ],
            'discount_total_percentage_amount' => [
                'type' => Type::nonNull(Type::float()),
                'description' => ''
            ],
            'discount_subtotal_fixed_amount' => [
                'type' => Type::nonNull(Type::float()),
                'description' => ''
            ],
            'discount_total_fixed_amount' => [
                'type' => Type::nonNull(Type::float()),
                'description' => ''
            ],
            'discount_amount' => [
                'type' => Type::nonNull(Type::float()),
                'description' => ''
            ],

            //***************************
            //* subtotal with discounts
            //***************************
            'subtotal_with_discounts' => [
                'type' => Type::nonNull(Type::float()),
                'description' => ''
            ],

            //****************
            //* taxes
            //****************
            'tax_rules' => [
                'type' => Type::nonNull(Type::string()),
                'description' => ''
            ],
            'tax_amount' => [
                'type' => Type::nonNull(Type::float()),
                'description' => ''
            ],

            //****************
            //* total
            //****************
            'total' => [
                'type' => Type::nonNull(Type::float()),
                'description' => 'Amount with tax and discounts'
            ],

            //****************
            //* gift
            //****************
            'has_gift' => [
                'type' => Type::boolean(),
                'description' => 'Check if order row has a gift'
            ],
            'gift_from' => [
                'type' => Type::string(),
                'description' => ''
            ],
            'gift_to' => [
                'type' => Type::string(),
                'description' => ''
            ],
            'gift_message' => [
                'type' => Type::string(),
                'description' => ''
            ],
            'gift_comments' => [
                'type' => Type::string(),
                'description' => ''
            ],
        ];
    }
}