<?php namespace Syscover\Market\GraphQL\Types;

use GraphQL\Type\Definition\Type;
use Folklore\GraphQL\Support\Type as GraphQLType;
use Syscover\Core\GraphQL\ScalarTypes\ObjectType;

class CustomerDiscountHistoryType extends GraphQLType {

    protected $attributes = [
        'name'          => 'CustomerDiscountHistory',
        'description'   => 'CustomerDiscountHistory from customer'
    ];

    public function fields()
    {
        return [
            'id' => [
                'type' => Type::nonNull(Type::int()),
                'description' => 'The id of customer discount history'
            ],
            'customer_id' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'The id of customer'
            ],
            'order_id' => [
                'type' => Type::nonNull(Type::int()),
                'description' => 'The id of order'
            ],
            'applied' => [
                'type' => Type::nonNull(Type::boolean()),
                'description' => 'In case of order is canceled, you can deactivate discount'
            ],
            'rule_type' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'Type of rule of this discount [CartPriceRule, CatalogPriceRule, CustomerPriceRule]'
            ],
            'rule_id' => [
                'type' => Type::int(),
                'description' => 'The id of rule'
            ],
            'names' => [
                'type' => app(ObjectType::class),
                'description' => 'Rule names value in different languages'
            ],
            'descriptions' => [
                'type' => app(ObjectType::class),
                'description' => 'Rule descriptions value in different languages'
            ],
            'has_coupon' => [
                'type' => Type::nonNull(Type::boolean()),
                'description' => 'Check if has coupon this discount'
            ],
            'coupon_code' => [
                'type' => Type::boolean(),
                'description' => 'The coupon of this discount'
            ],
            'discount_type_id' => [
                'type' => Type::int(),
                'description' => 'Discount type on shopping cart discounts [without discount, discount percentage subtotal, discount fixed amount subtotal, discount percentage total, discount fixed amount total]'
            ],
            'discount_fixed_amount' => [
                'type' => Type::float(),
                'description' => 'Fixed amount to discount over shopping cart'
            ],
            'discount_percentage' => [
                'type' => Type::float(),
                'description' => 'Limit amount to discount, if the discount is a percentage'
            ],
            'maximum_discount_amount' => [
                'type' => Type::float(),
                'description' => 'Limit amount to discount, if the discount is a percentage'
            ],
            'apply_shipping_amount' => [
                'type' => Type::boolean(),
                'description' => 'Check if apply discount to shipping amount'
            ],
            'free_shipping' => [
                'type' => Type::boolean(),
                'description' => 'Check if this discount has free shipping'
            ],
            'discount_amount' => [
                'type' => Type::float(),
                'description' => 'Total discount amount apply with this rule'
            ],
            'data_lang' => [
                'type' => Type::listOf(Type::string()),
                'description' => 'JSON string that contain information about object translations'
            ],
            'price_rule' => [
                'type' => app(ObjectType::class),
                'description' => 'JSON string'
            ]
        ];
    }
}