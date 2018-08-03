<?php namespace Syscover\Market\Models;

use Illuminate\Support\Facades\DB;
use Syscover\Core\Models\CoreModel;
use Illuminate\Support\Facades\Validator;

/**
 * Class TaxRule
 * @package Syscover\Market\Models
 */

class TaxRule extends CoreModel
{
	protected $table        = 'market_tax_rule';
    protected $fillable     = ['id', 'name', 'translation', 'priority', 'sort'];
    public $with            = ['tax_rate_zones', 'customer_class_taxes', 'product_class_taxes'];

    private static $rules   = [];

    public static function validate($data)
    {
        return Validator::make($data, static::$rules);
	}

    public function scopeBuilder($query)
    {
        return $query
            ->join('market_tax_rules_tax_rates_zones', 'market_tax_rule.id', '=', 'market_tax_rules_tax_rates_zones.tax_rule_id')
            ->join('market_tax_rules_customer_class_taxes', 'market_tax_rule.id', '=', 'market_tax_rules_customer_class_taxes.tax_rule_id')
            ->join('market_tax_rules_product_class_taxes', 'market_tax_rule.id', '=', 'market_tax_rules_product_class_taxes.tax_rule_id')
            ->join('market_tax_rate_zone', 'market_tax_rules_tax_rates_zones.tax_rate_zone_id', '=', 'market_tax_rate_zone.id')
            ->join('market_customer_class_tax', 'market_tax_rules_customer_class_taxes.customer_class_tax_id', '=', 'market_customer_class_tax.id')
            ->join('market_product_class_tax', 'market_tax_rules_product_class_taxes.product_class_tax_id', '=', 'market_product_class_tax.id')
            ->addSelect('market_tax_rule.*')
            ->groupBy('market_tax_rule.id', 'market_tax_rule.name', 'market_tax_rule.translation', 'market_tax_rule.priority', 'market_tax_rule.sort');
    }

    public function scopeCalculateFoundRows($query)
    {
        return $query->select(DB::raw('SQL_CALC_FOUND_ROWS market_tax_rule.id'));
    }

    public function tax_rate_zones()
    {
        return $this->belongsToMany(TaxRateZone::class, 'market_tax_rules_tax_rates_zones', 'tax_rule_id', 'tax_rate_zone_id');
    }

    public function customer_class_taxes()
    {
        return $this->belongsToMany(CustomerClassTax::class, 'market_tax_rules_customer_class_taxes', 'tax_rule_id', 'customer_class_tax_id');
    }
    
    public function product_class_taxes()
    {
        return $this->belongsToMany(ProductClassTax::class, 'market_tax_rules_product_class_taxes', 'tax_rule_id', 'product_class_tax_id');
    }
}