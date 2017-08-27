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

        // TAX RULE
        GraphQL::addType(\Syscover\Market\GraphQL\Types\TaxRuleType::class, 'MarketTaxRule');
        GraphQL::addType(\Syscover\Market\GraphQL\Inputs\TaxRuleInput::class, 'MarketTaxRuleInput');

        // CATEGORY
        GraphQL::addType(\Syscover\Market\GraphQL\Types\CategoryType::class, 'MarketCategory');
        GraphQL::addType(\Syscover\Market\GraphQL\Inputs\CategoryInput::class, 'MarketCategoryInput');

        // PRODUCT
        GraphQL::addType(\Syscover\Market\GraphQL\Types\ProductType::class, 'MarketProduct');
        GraphQL::addType(\Syscover\Market\GraphQL\Inputs\ProductInput::class, 'MarketProductInput');

        // WAREHOUSE
        GraphQL::addType(\Syscover\Market\GraphQL\Types\WarehouseType::class, 'MarketWarehouse');
        GraphQL::addType(\Syscover\Market\GraphQL\Inputs\WarehouseInput::class, 'MarketWarehouseInput');

        // STOCK
        GraphQL::addType(\Syscover\Market\GraphQL\Types\StockType::class, 'MarketStock');
        GraphQL::addType(\Syscover\Market\GraphQL\Inputs\StockInput::class, 'MarketStockInput');
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

                // TAX RULE
                'marketTaxRulesPagination'                  => \Syscover\Market\GraphQL\Queries\TaxRulesPaginationQuery::class,
                'marketTaxRules'                            => \Syscover\Market\GraphQL\Queries\TaxRulesQuery::class,
                'marketTaxRule'                             => \Syscover\Market\GraphQL\Queries\TaxRuleQuery::class,

                // CATEGORY
                'marketCategoriesPagination'                => \Syscover\Market\GraphQL\Queries\CategoriesPaginationQuery::class,
                'marketCategories'                          => \Syscover\Market\GraphQL\Queries\CategoriesQuery::class,
                'marketCategory'                            => \Syscover\Market\GraphQL\Queries\CategoryQuery::class,

                // PRODUCT
                'marketProductsPagination'                  => \Syscover\Market\GraphQL\Queries\ProductsPaginationQuery::class,
                'marketProducts'                            => \Syscover\Market\GraphQL\Queries\ProductsQuery::class,
                'marketProduct'                             => \Syscover\Market\GraphQL\Queries\ProductQuery::class,

                // PRODUCT TAXES
                'marketProductTaxes'                        => \Syscover\Market\GraphQL\Queries\ProductTaxesQuery::class,

                // WAREHOUSE
                'marketWarehousesPagination'                => \Syscover\Market\GraphQL\Queries\WarehousesPaginationQuery::class,
                'marketWarehouses'                          => \Syscover\Market\GraphQL\Queries\WarehousesQuery::class,
                'marketWarehouse'                           => \Syscover\Market\GraphQL\Queries\WarehouseQuery::class,

                // STOCK
                'marketStocksPagination'                    => \Syscover\Market\GraphQL\Queries\StocksPaginationQuery::class,
                'marketStocks'                              => \Syscover\Market\GraphQL\Queries\StocksQuery::class,
                'marketStock'                               => \Syscover\Market\GraphQL\Queries\StockQuery::class,
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

                // TAX RULE
                'marketAddTaxRule'                          => \Syscover\Market\GraphQL\Mutations\AddTaxRuleMutation::class,
                'marketUpdateTaxRule'                       => \Syscover\Market\GraphQL\Mutations\UpdateTaxRuleMutation::class,
                'marketDeleteTaxRule'                       => \Syscover\Market\GraphQL\Mutations\DeleteTaxRuleMutation::class,

                // CATEGORY
                'marketAddCategory'                         => \Syscover\Market\GraphQL\Mutations\AddCategoryMutation::class,
                'marketUpdateCategory'                      => \Syscover\Market\GraphQL\Mutations\UpdateCategoryMutation::class,
                'marketDeleteCategory'                      => \Syscover\Market\GraphQL\Mutations\DeleteCategoryMutation::class,

                // PRODUCT
                'marketAddProduct'                          => \Syscover\Market\GraphQL\Mutations\AddProductMutation::class,
                'marketUpdateProduct'                       => \Syscover\Market\GraphQL\Mutations\UpdateProductMutation::class,
                'marketDeleteProduct'                       => \Syscover\Market\GraphQL\Mutations\DeleteProductMutation::class,

                // WAREHOUSE
                'marketAddWarehouse'                        => \Syscover\Market\GraphQL\Mutations\AddWarehouseMutation::class,
                'marketUpdateWarehouse'                     => \Syscover\Market\GraphQL\Mutations\UpdateWarehouseMutation::class,
                'marketDeleteWarehouse'                     => \Syscover\Market\GraphQL\Mutations\DeleteWarehouseMutation::class,

                // STOCK
                'marketAddStock'                            => \Syscover\Market\GraphQL\Mutations\AddStockMutation::class,
                'marketUpdateStock'                         => \Syscover\Market\GraphQL\Mutations\UpdateStockMutation::class,
                'marketDeleteStock'                         => \Syscover\Market\GraphQL\Mutations\DeleteStockMutation::class,
            ]
        ]));
    }
}