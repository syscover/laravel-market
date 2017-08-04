<?php namespace Syscover\Market\GraphQL;

use GraphQL;

class MarketGraphQLServiceProvider
{
    public static function bootGraphQLTypes()
    {
        // ORDER STATUS
        GraphQL::addType(\Syscover\Market\GraphQL\Types\OrderStatusType::class, 'MarketOrderStatus');
        GraphQL::addType(\Syscover\Market\GraphQL\Inputs\OrderStatusInput::class, 'MarketOrderStatusInput');

        // PAYMENT METHOD
        GraphQL::addType(\Syscover\Market\GraphQL\Types\PaymentMethodType::class, 'MarketPaymentMethod');
        GraphQL::addType(\Syscover\Market\GraphQL\Inputs\PaymentMethodInput::class, 'MarketPaymentMethodInput');

        // CUSTOMER CLASS TAX
        GraphQL::addType(\Syscover\Market\GraphQL\Types\CustomerClassTaxType::class, 'MarketCustomerClassTax');
        GraphQL::addType(\Syscover\Market\GraphQL\Inputs\CustomerClassTaxInput::class, 'MarketCustomerClassTaxInput');

        // PRODUCT CLASS TAX
        GraphQL::addType(\Syscover\Market\GraphQL\Types\ProductClassTaxType::class, 'MarketProductClassTax');
        GraphQL::addType(\Syscover\Market\GraphQL\Inputs\ProductClassTaxInput::class, 'MarketProductClassTaxInput');

        // GROUP CUSTOMER CLASS TAX
        GraphQL::addType(\Syscover\Market\GraphQL\Types\GroupCustomerClassTaxType::class, 'MarketGroupCustomerClassTax');
        GraphQL::addType(\Syscover\Market\GraphQL\Inputs\GroupCustomerClassTaxInput::class, 'MarketGroupCustomerClassTaxInput');

        // TAX RATE ZONE
        GraphQL::addType(\Syscover\Market\GraphQL\Types\TaxRateZoneType::class, 'MarketTaxRateZone');
        GraphQL::addType(\Syscover\Market\GraphQL\Inputs\TaxRateZoneInput::class, 'MarketTaxRateZoneInput');
    }

    public static function bootGraphQLSchema()
    {
        GraphQL::addSchema('default', array_merge_recursive(GraphQL::getSchemas()['default'], [
            'query' => [
                // ORDER STATUS
                'marketOrderStatusesPagination'             => \Syscover\Market\GraphQL\Queries\OrderStatusesPaginationQuery::class,
                'marketOrderStatuses'                       => \Syscover\Market\GraphQL\Queries\OrderStatusesQuery::class,
                'marketOrderStatus'                         => \Syscover\Market\GraphQL\Queries\OrderStatusQuery::class,

                // PAYMENT METHOD
                'marketPaymentMethodsPagination'            => \Syscover\Market\GraphQL\Queries\PaymentMethodsPaginationQuery::class,
                'marketPaymentMethods'                      => \Syscover\Market\GraphQL\Queries\PaymentMethodsQuery::class,
                'marketPaymentMethod'                       => \Syscover\Market\GraphQL\Queries\PaymentMethodQuery::class,

                // CUSTOMER CLASS TAX
                'marketCustomerClassTaxesPagination'        => \Syscover\Market\GraphQL\Queries\CustomerClassTaxesPaginationQuery::class,
                'marketCustomerClassTaxes'                  => \Syscover\Market\GraphQL\Queries\CustomerClassTaxesQuery::class,
                'marketCustomerClassTax'                    => \Syscover\Market\GraphQL\Queries\CustomerClassTaxQuery::class,

                // PRODUCT CLASS TAX
                'marketProductClassTaxesPagination'         => \Syscover\Market\GraphQL\Queries\ProductClassTaxesPaginationQuery::class,
                'marketProductClassTaxes'                   => \Syscover\Market\GraphQL\Queries\ProductClassTaxesQuery::class,
                'marketProductClassTax'                     => \Syscover\Market\GraphQL\Queries\ProductClassTaxQuery::class,

                // GROUP CUSTOMER CLASS TAX
                'marketGroupCustomerClassTaxesPagination'   => \Syscover\Market\GraphQL\Queries\GroupCustomerClassTaxesPaginationQuery::class,
                'marketGroupCustomerClassTaxes'             => \Syscover\Market\GraphQL\Queries\GroupCustomerClassTaxesQuery::class,
                'marketGroupCustomerClassTax'               => \Syscover\Market\GraphQL\Queries\GroupCustomerClassTaxQuery::class,

                // TAX RATE ZONE
                'marketTaxRateZonesPagination'              => \Syscover\Market\GraphQL\Queries\TaxRateZonesPaginationQuery::class,
                'marketTaxRateZones'                        => \Syscover\Market\GraphQL\Queries\TaxRateZonesQuery::class,
                'marketTaxRateZone'                         => \Syscover\Market\GraphQL\Queries\TaxRateZoneQuery::class,
            ],
            'mutation' => [
                // ORDER STATUS
                'marketAddOrderStatus'                      => \Syscover\Market\GraphQL\Mutations\AddOrderStatusMutation::class,
                'marketUpdateOrderStatus'                   => \Syscover\Market\GraphQL\Mutations\UpdateOrderStatusMutation::class,
                'marketDeleteOrderStatus'                   => \Syscover\Market\GraphQL\Mutations\DeleteOrderStatusMutation::class,

                // PAYMENT METHOD
                'marketAddPaymentMethod'                    => \Syscover\Market\GraphQL\Mutations\AddPaymentMethodMutation::class,
                'marketUpdatePaymentMethod'                 => \Syscover\Market\GraphQL\Mutations\UpdatePaymentMethodMutation::class,
                'marketDeletePaymentMethod'                 => \Syscover\Market\GraphQL\Mutations\DeletePaymentMethodMutation::class,

                // CUSTOMER CLASS TAX
                'marketAddCustomerClassTax'                 => \Syscover\Market\GraphQL\Mutations\AddCustomerClassTaxMutation::class,
                'marketUpdateCustomerClassTax'              => \Syscover\Market\GraphQL\Mutations\UpdateCustomerClassTaxMutation::class,
                'marketDeleteCustomerClassTax'              => \Syscover\Market\GraphQL\Mutations\DeleteCustomerClassTaxMutation::class,

                // PRODUCT CLASS TAX
                'marketAddProductClassTax'                  => \Syscover\Market\GraphQL\Mutations\AddProductClassTaxMutation::class,
                'marketUpdateProductClassTax'               => \Syscover\Market\GraphQL\Mutations\UpdateProductClassTaxMutation::class,
                'marketDeleteProductClassTax'               => \Syscover\Market\GraphQL\Mutations\DeleteProductClassTaxMutation::class,

                // GROUP CUSTOMER CLASS TAX
                'marketAddGroupCustomerClassTax'            => \Syscover\Market\GraphQL\Mutations\AddGroupCustomerClassTaxMutation::class,
                'marketUpdateGroupCustomerClassTax'         => \Syscover\Market\GraphQL\Mutations\UpdateGroupCustomerClassTaxMutation::class,
                'marketDeleteGroupCustomerClassTax'         => \Syscover\Market\GraphQL\Mutations\DeleteGroupCustomerClassTaxMutation::class,

                // TAX RATE ZONE
                'marketAddTaxRateZone'                      => \Syscover\Market\GraphQL\Mutations\AddTaxRateZoneMutation::class,
                'marketUpdateTaxRateZone'                   => \Syscover\Market\GraphQL\Mutations\UpdateTaxRateZoneMutation::class,
                'marketDeleteTaxRateZone'                   => \Syscover\Market\GraphQL\Mutations\DeleteTaxRateZoneMutation::class,
            ]
        ]));
    }
}