<?php namespace Syscover\Market\GraphQL\Types;

use GraphQL;
use GraphQL\Type\Definition\Type;
use Folklore\GraphQL\Support\Type as GraphQLType;
use Syscover\Core\GraphQL\ScalarTypes\ObjectType;

class OrderType extends GraphQLType
{
    protected $attributes = [
        'name' => 'Order',
        'description' => 'Order'
    ];

    public function fields()
    {
        return [
            'id' => [
                'type' => Type::nonNull(Type::int()),
                'description' => 'The id of order'
            ],
            'date' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'Date of order'
            ],
            'payment_method_id' => [
                'type' => Type::nonNull(Type::int()),
                'description' => 'Payment method ID'
            ],
            'payment_methods' => [
                'type' => Type::nonNull(Type::listOf(GraphQL::type('MarketPaymentMethod'))),
                'description' => 'Payment method ID'
            ],
            'status_id' => [
                'type' => Type::nonNull(Type::int()),
                'description' => 'Status ID'
            ],
            'ip' => [
                'type' => Type::string(),
                'description' => 'Customer IP'
            ],
            'data' => [
                'type' => app(ObjectType::class),
                'description' => 'Data of order'
            ],
            'comments' => [
                'type' => Type::string(),
                'description' => 'Comments of order'
            ],
            'transaction_id' => [
                'type' => Type::string(),
                'description' => 'Transaction ID generate by PayPal or any payment method'
            ],
            'rows' => [
                'type' => Type::listOf(GraphQL::type('MarketOrderRow')),
                'description' => 'Rows of order'
            ],
            'discounts' => [
                'type' => Type::listOf(GraphQL::type('MarketCustomerDiscountHistory')),
                'description' => 'Discounts of this order'
            ],

            //****************
            //* amounts
            //****************
            'discount_amount' => [
                'type' => Type::nonNull(Type::float()),
                'description' => 'Total amount to discount, fixed plus percentage discounts'
            ],
            'subtotal_with_discounts' => [
                'type' => Type::nonNull(Type::float()),
                'description' => 'Subtotal with discounts applied'
            ],
            'tax_amount' => [
                'type' => Type::nonNull(Type::float()),
                'description' => 'Total tax amount'
            ],
            'cart_items_total_without_discounts' => [
                'type' => Type::nonNull(Type::float()),
                'description' => 'Total of cart items. Amount with tax, without discount and without shipping'
            ],
            'subtotal' => [
                'type' => Type::nonNull(Type::float()),
                'description' => 'Amount without tax and without shipping'
            ],
            'shipping_amount' => [
                'type' => Type::nonNull(Type::float()),
                'description' => 'Shipping amount'
            ],
            'total' => [
                'type' => Type::nonNull(Type::float()),
                'description' => 'Subtotal and shipping amount with tax'
            ],

            //****************
            //* gift
            //****************
            'has_gift' => [
                'type' => Type::boolean(),
                'description' => 'Check if order has a gift'
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

            //****************
            //* customer
            //****************
            'customer_id' => [
                'type' => Type::nonNull(Type::int()),
                'description' => 'Customer Id'
            ],
            'customer_group_id' => [
                'type' => Type::nonNull(Type::int()),
                'description' => 'Customer group Id'
            ],
            'customer_company' => [
                'type' => Type::string(),
                'description' => 'Customer company'
            ],
            'customer_tin' => [
                'type' => Type::string(),
                'description' => 'Customer TIN/CIF/RFC'
            ],
            'customer_name' => [
                'type' => Type::string(),
                'description' => 'Customer namne'
            ],
            'customer_surname' => [
                'type' => Type::string(),
                'description' => 'Customer surname'
            ],
            'customer_email' => [
                'type' => Type::string(),
                'description' => 'Customer email'
            ],
            'customer_mobile' => [
                'type' => Type::string(),
                'description' => 'Customer mobile'
            ],
            'customer_phone' => [
                'type' => Type::string(),
                'description' => 'Customer phone'
            ],

            //****************
            //* invoice data
            //****************
            'has_invoice' => [
                'type' => Type::boolean(),
                'description' => 'Check if this order has invoice'
            ],
            'invoiced' => [
                'type' => Type::boolean(),
                'description' => 'Check if has been created invoice on billing program'
            ],
            'invoice_number' => [
                'type' => Type::string(),
                'description' => 'If has invoice, set invoice number'
            ],
            'invoice_company' => [
                'type' => Type::string(),
                'description' => 'Company name of invoice'
            ],
            'invoice_tin' => [
                'type' => Type::string(),
                'description' => 'Description '
            ],
            'invoice_name' => [
                'type' => Type::string(),
                'description' => 'Name of invoice'
            ],
            'invoice_surname' => [
                'type' => Type::string(),
                'description' => 'Surname of invoice'
            ],
            'invoice_email' => [
                'type' => Type::string(),
                'description' => 'Email of invoice'
            ],
            'invoice_mobile' => [
                'type' => Type::string(),
                'description' => 'Mobile of invoice'
            ],
            'invoice_phone' => [
                'type' => Type::string(),
                'description' => 'Phone of invoice'
            ],
            'invoice_country_id' => [
                'type' => Type::string(),
                'description' => 'Country id of invoice'
            ],
            'invoice_territorial_area_1_id' => [
                'type' => Type::string(),
                'description' => 'Territorial area 1 id of invoice'
            ],
            'invoice_territorial_area_2_id' => [
                'type' => Type::string(),
                'description' => 'Territorial area 2 id of invoice'
            ],
            'invoice_territorial_area_3_id' => [
                'type' => Type::string(),
                'description' => 'Territorial area 3 id of invoice'
            ],
            'invoice_zip' => [
                'type' => Type::string(),
                'description' => 'ZIP of invoice'
            ],
            'invoice_locality' => [
                'type' => Type::string(),
                'description' => 'Locality of invoice'
            ],
            'invoice_address' => [
                'type' => Type::string(),
                'description' => 'Address of invoice'
            ],
            'invoice_latitude' => [
                'type' => Type::string(),
                'description' => 'Latitude of invoice'
            ],
            'invoice_longitude' => [
                'type' => Type::string(),
                'description' => 'Longitude of invoice'
            ],
            'invoice_comments' => [
                'type' => Type::string(),
                'description' => 'Comments of invoice'
            ],

            //****************
            //* shipping data
            //****************
            'has_shipping' => [
                'type' => Type::boolean(),
                'description' => 'Check if this order has shipping'
            ],
            'shipping_tracking_id' => [
                'type' => Type::string(),
                'description' => 'Code generate by shipping company to get tracking of shipping'
            ],
            'shipping_company' => [
                'type' => Type::string(),
                'description' => 'Company name of shipping'
            ],
            'shipping_name' => [
                'type' => Type::string(),
                'description' => 'Name of shipping'
            ],
            'shipping_surname' => [
                'type' => Type::string(),
                'description' => 'Surname of shipping'
            ],
            'shipping_email' => [
                'type' => Type::string(),
                'description' => 'Email of shipping'
            ],
            'shipping_mobile' => [
                'type' => Type::string(),
                'description' => 'Mobile of shipping'
            ],
            'shipping_phone' => [
                'type' => Type::string(),
                'description' => 'Phone of shipping'
            ],
            'shipping_country_id' => [
                'type' => Type::string(),
                'description' => 'The country id of shipping'
            ],
            'shipping_territorial_area_1_id' => [
                'type' => Type::string(),
                'description' => 'The territorial area 1 id of shipping'
            ],
            'shipping_territorial_area_2_id' => [
                'type' => Type::string(),
                'description' => 'The territorial area 2 id of shipping'
            ],
            'shipping_territorial_area_3_id' => [
                'type' => Type::string(),
                'description' => 'The territorial area 3 id of shipping'
            ],
            'shipping_zip' => [
                'type' => Type::string(),
                'description' => 'ZIP of shipping'
            ],
            'shipping_locality' => [
                'type' => Type::string(),
                'description' => 'Locality of shipping'
            ],
            'shipping_address' => [
                'type' => Type::string(),
                'description' => 'Address of shipping'
            ],
            'shipping_latitude' => [
                'type' => Type::string(),
                'description' => 'Latitude of shipping'
            ],
            'shipping_longitude' => [
                'type' => Type::string(),
                'description' => 'Longitude of shipping'
            ],
            'shipping_comments' => [
                'type' => Type::string(),
                'description' => 'Comments of shipping'
            ],
        ];
    }

    public function resolveRowsField($object, $args)
    {
        return $object->rows;
    }

    public function resolveDiscountsField($object, $args)
    {
        return $object->discounts;
    }
}