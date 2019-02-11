<?php namespace Syscover\Market\GraphQL\Services;

use Syscover\Admin\Services\AttachmentService;
use Syscover\Core\GraphQL\Services\CoreGraphQLService;
use Syscover\Core\Services\SQLService;
use Syscover\Market\Models\Product;
use Syscover\Market\Models\ProductLang;
use Syscover\Market\Models\TaxRule;
use Syscover\Market\Services\ProductService;
use Syscover\Market\Services\TaxRuleService;

class ProductGraphQLService extends CoreGraphQLService
{
    public function __construct(Product $model, ProductService $service)
    {
        $this->model = $model;
        $this->service = $service;
    }

    public function taxes($root, array $args)
    {
        $price              = $args['price'];
        $productClassTax    = $args['productClassTax'];

        // get tax rules
        $taxRules = TaxRule::builder()
            ->where('country_id', config('pulsar-market.default_country_tax'))
            ->where('customer_class_tax_id', config('pulsar-market.default_customer_class_tax'))
            ->where('product_class_tax_id', $productClassTax)
            ->orderBy('priority', 'asc')
            ->get();

        if(
            isset($args['product_tax_prices']) &&  $args['product_tax_prices'] == TaxRuleService::PRICE_WITHOUT_TAX ||
            (int) config('pulsar-market.product_tax_prices') == TaxRuleService::PRICE_WITHOUT_TAX
        )
        {
            $taxes      = TaxRuleService::taxCalculateOverSubtotal($price, $taxRules);
            $taxAmount  = $taxes->sum('tax_amount');
            $subtotal   = $price;
            $total      = $subtotal + $taxAmount;

        }
        elseif (
            isset($args['product_tax_prices']) &&  $args['product_tax_prices'] == TaxRuleService::PRICE_WITH_TAX ||
            (int) config('pulsar-market.product_tax_prices') == TaxRuleService::PRICE_WITH_TAX
        )
        {
            $taxes      = TaxRuleService::taxCalculateOverTotal($price, $taxRules);
            $taxAmount  = $taxes->sum('tax_amount');
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

    public function delete($root, array $args)
    {
        // delete object
        $object = SQLService::deleteRecord($args['id'], get_class($this->model), $args['lang_id'], ProductLang::class);

        // delete attachments object
        AttachmentService::deleteAttachments($args['id'], get_class($this->model), $args['lang_id']);

        return $object;
    }
}
