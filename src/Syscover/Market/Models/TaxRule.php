<?php namespace Syscover\Market\Models;

use Syscover\Core\Models\CoreModel;
use Illuminate\Support\Facades\Validator;

/**
 * Class TaxRule
 * @package Syscover\Market\Models
 */

class TaxRule extends CoreModel
{
	protected $table        = 'tax_rule';
    protected $fillable     = ['id', 'name', 'translation', 'priority', 'sort'];
    public $timestamps      = false;
    public $relations       = ['taxRateZones', 'customerClassTaxes', 'productClassTaxes'];
    private static $rules   = [];

    public static function validate($data)
    {
        return Validator::make($data, static::$rules);
	}

    public function scopeBuilder($query)
    {
        return $query->join('tax_rules_tax_rates_zones', 'tax_rule.id', '=', 'tax_rules_tax_rates_zones.tax_rule_id')
            ->join('tax_rules_customer_class_taxes', 'tax_rule.id', '=', 'tax_rules_customer_class_taxes.tax_rule_id')
            ->join('tax_rules_product_class_taxes', 'tax_rule.id', '=', 'tax_rules_product_class_taxes.tax_rule_id')
            ->join('tax_rate_zone', 'tax_rules_tax_rates_zones.tax_rate_zone_id', '=', 'tax_rate_zone.id')
            ->join('customer_class_tax', 'tax_rules_customer_class_taxes.customer_class_tax_id', '=', 'customer_class_tax.id')
            ->join('product_class_tax', 'tax_rules_product_class_taxes.product_class_tax_id', '=', 'product_class_tax.id')
            ->select('tax_rule.*')
            ->groupBy('tax_rule.id', 'tax_rule.name', 'tax_rule.translation', 'tax_rule.priority', 'tax_rule.sort');
    }

    public function taxRateZones()
    {
        return $this->belongsToMany(TaxRateZone::class, 'tax_rules_tax_rates_zones', 'tax_rule_id', 'tax_rate_zone_id');
    }

    public function customerClassTaxes()
    {
        return $this->belongsToMany(CustomerClassTax::class, 'tax_rules_customer_class_taxes', 'tax_rule_id', 'customer_class_tax_id');
    }
    
    public function productClassTaxes()
    {
        return $this->belongsToMany(ProductClassTax::class, 'tax_rules_product_class_taxes', 'tax_rule_id', 'product_class_tax_id');
    }
}