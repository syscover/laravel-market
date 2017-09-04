<?php namespace Syscover\Market\GraphQL\Inputs;

use GraphQL\Type\Definition\Type;
use Folklore\GraphQL\Support\Type as GraphQLType;

class OrderRowInput extends GraphQLType
{
    protected $attributes = [
        'name' => 'OrderRowInput',
        'description' => 'OrderRow'
    ];

    protected $inputObject = true;

    public function fields()
    {
        return [
            'id' => [
                'type' => Type::int(),
                'description' => 'The id of order row'
            ],
            'lang_id' => [
                'type' => Type::string(),
                'description' => 'The lang of order row'
            ],
            'order_id' => [
                'type' => Type::int(),
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
                'type' => Type::string(),
                'description' => 'Data of order row'
            ],

            //****************
            //* amounts
            //****************
            'price' => [
                'type' => Type::float(),
                'description' => 'Unit price'
            ],
            'quantity' => [
                'type' => Type::float(),
                'description' => 'Number of units'
            ],
            'subtotal' => [
                'type' => Type::float(),
                'description' => 'Subtotal without tax'
            ],
            'total_without_discounts' => [
                'type' => Type::float(),
                'description' => 'Total from row without discounts'
            ],

            //****************
            //* discounts
            //****************
            'discount_subtotal_percentage' => [
                'type' => Type::float(),
                'description' => ''
            ],
            'discount_total_percentage' => [
                'type' => Type::float(),
                'description' => ''
            ],
            'discount_subtotal_percentage_amount' => [
                'type' => Type::float(),
                'description' => ''
            ],
            'discount_total_percentage_amount' => [
                'type' => Type::float(),
                'description' => ''
            ],
            'discount_subtotal_fixed_amount' => [
                'type' => Type::float(),
                'description' => ''
            ],
            'discount_total_fixed_amount' => [
                'type' => Type::float(),
                'description' => ''
            ],
            'discount_amount' => [
                'type' => Type::float(),
                'description' => ''
            ],

            //***************************
            //* subtotal with discounts
            //***************************
            'subtotal_with_discounts' => [
                'type' => Type::float(),
                'description' => ''
            ],

            //****************
            //* taxes
            //****************
            'tax_rules' => [
                'type' => Type::string(),
                'description' => ''
            ],
            'tax_amount' => [
                'type' => Type::float(),
                'description' => ''
            ],

            //****************
            //* total
            //****************
            'total' => [
                'type' => Type::float(),
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