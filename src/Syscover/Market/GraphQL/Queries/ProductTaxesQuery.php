<?php namespace Syscover\Market\GraphQL\Queries;

use GraphQL\Type\Definition\Type;
use Folklore\GraphQL\Support\Query;
use Syscover\Core\GraphQL\ScalarTypes\ObjectType;
use Syscover\Market\Models\TaxRule;
use Syscover\Market\Services\TaxRuleService;

class ProductTaxesQuery extends Query
{
    protected $attributes = [
        'name'          => 'ProductTaxesQuery',
        'description'   => 'Query to get a tax of product'
    ];

    public function type()
    {
        return app(ObjectType::class);
    }

    public function args()
    {
        return [
            'price' => [
                'name'          => 'price',
                'type'          => Type::nonNull(Type::float()),
                'description'   => 'subtotal price'
            ],
            'productClassTax' => [
                'name'          => 'productClassTax',
                'type'          => Type::int(),
                'description'   => 'Product class tax'
            ],
            // force to calculate price over PRICE_WITHOUT_TAX or PRICE_WITH_TAX, when show product the price always is PRICE_WITHOUT_TAX
            'productTaxPrices' => [
                'name'          => 'productTaxPrices',
                'type'          => Type::int(),
                'description'   => 'Product class tax'
            ]
        ];
    }

    public function resolve($root, $args)
    {
        $price              = $args['price'];
        $productClassTax    = $args['productClassTax'];

        // get tax rules
        $taxRules = TaxRule::builder()
            ->where('country_id', config('pulsar-market.default_tax_country'))
            ->where('customer_class_tax_id', config('pulsar-market.default_class_customer_tax'))
            ->where('product_class_tax_id', $productClassTax)
            ->orderBy('priority', 'asc')
            ->get();

        if(
            isset($args['productTaxPrices']) &&  $args['productTaxPrices'] == TaxRuleService::PRICE_WITHOUT_TAX ||
            (int) config('pulsar-market.productTaxPrices') == TaxRuleService::PRICE_WITHOUT_TAX
        )
        {
            $taxes      = TaxRuleService::taxCalculateOverSubtotal($price, $taxRules);
            $taxAmount  = $taxes->sum('taxAmount');
            $subtotal   = $price;
            $total      = $subtotal + $taxAmount;

        }
        elseif (
            isset($args['productTaxPrices']) &&  $args['productTaxPrices'] == TaxRuleService::PRICE_WITH_TAX ||
            (int) config('pulsar-market.productTaxPrices') == TaxRuleService::PRICE_WITH_TAX
        )
        {
            $taxes      = TaxRuleService::taxCalculateOverTotal($price, $taxRules);
            $taxAmount  = $taxes->sum('taxAmount');
            $total      = $price;
            $subtotal   = $total - $taxAmount;
        }

        return [
            'taxes'             => $taxes,
            'subtotal'          => $subtotal,
            'taxAmount'         => $taxAmount,
            'total'             => $total,
            'subtotalFormat'    => number_format($subtotal, 2, ',', '.'),
            'taxAmountFormat'   => number_format($taxAmount, 2, ',', '.'),
            'totalFormat'       => number_format($total, 2, ',', '.'),
        ];
    }
}