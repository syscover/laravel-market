<?php namespace Syscover\Market\Models;

use Syscover\Admin\Models\Attachment;
use Syscover\Core\Models\CoreModel;
use Illuminate\Support\Facades\Validator;
use Syscover\Admin\Models\Lang;
use Syscover\Core\Scopes\TableLangScope;
use Syscover\Market\Services\TaxRuleService;

/**
 * Class Product
 * @package Syscover\Market\Models
 */

class Product extends CoreModel
{
	protected $table        = 'product';
    protected $fillable     = ['id', 'field_group_id', 'product_type_id', 'parent_product_id', 'weight', 'active', 'sort', 'price_type_id', 'subtotal', 'product_class_tax_id', 'data_lang', 'data'];
    public $timestamps      = false;
    protected $casts        = [
        'active'    => 'boolean',
        'data_lang' => 'array',
        'data'      => 'array'
    ];
    public $with                = ['lang', 'attachments'];
    public $lazyRelations       = ['categories'];

    private static $rules       = [
        'price_type_id'         => 'required',
        'product_type_id'       => 'required',
    ];

    public static function validate($data)
    {
        return Validator::make($data, static::$rules);
	}

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new TableLangScope);
    }

    public function scopeBuilder($query)
    {
        return $query;
    }

    public function lang()
    {
        return $this->belongsTo(Lang::class, 'lang_id');
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'products_categories', 'product_id', 'category_id')
            ->where('category.lang_id', $this->lang_id);
    }

    public function attachments()
    {
        return $this->morphMany(Attachment::class, 'object')
            ->where('attachment.lang_id', $this->lang_id)
            ->orderBy('sort', 'asc');
    }

    /**
     * Returns formatted product price.
     *
     * @param   int       $decimals
     * @param   string    $decimalPoint
     * @param   string    $thousandSeperator
     * @return  string
     */
    public function getPrice($decimals = 2, $decimalPoint = ',', $thousandSeperator = '.')
    {
        return number_format($this->price, $decimals, $decimalPoint, $thousandSeperator);
    }

    /**
     * Returns formatted product subtotal.
     *
     * @param   int       $decimals
     * @param   string    $decimalPoint
     * @param   string    $thousandSeperator
     * @return  string
     */
    public function getSubtotal($decimals = 2, $decimalPoint = ',', $thousandSeperator = '.')
    {
        return number_format($this->subtotal, $decimals, $decimalPoint, $thousandSeperator);
    }

    /**
     * Returns formatted product tax amount.
     *
     * @param   int       $decimals
     * @param   string    $decimalPoint
     * @param   string    $thousandSeperator
     * @return  string
     */
    public function getTaxAmount($decimals = 2, $decimalPoint = ',', $thousandSeperator = '.')
    {
        return number_format($this->tax_amount, $decimals, $decimalPoint, $thousandSeperator);
    }

    /**
     * Returns formatted product total amount.
     *
     * @param   int       $decimals
     * @param   string    $decimalPoint
     * @param   string    $thousandSeperator
     * @return  string
     */
    public function getTotal($decimals = 2, $decimalPoint = ',', $thousandSeperator = '.')
    {
        return number_format($this->total, $decimals, $decimalPoint, $thousandSeperator);
    }

    /**
     * Dynamically access route parameters.
     *
     * @param  string  $key
     * @return mixed
     */
    public function __get($key)
    {
        // price of product
        if($key === 'price')
        {
            if(config('pulsar.market.taxProductDisplayPrices') == TaxRuleService::PRICE_WITHOUT_TAX)
            {
                return $this->subtotal;
            }
            elseif(config('pulsar.market.taxProductDisplayPrices') == TaxRuleService::PRICE_WITH_TAX)
            {
                return $this->total; // call magic method
            }
        }

        // total price
        if($key === 'total')
        {
            if($this->tax_amount !== null)
            {
                return $this->subtotal + $this->tax_amount;
            }

            $taxes              = TaxRuleService::taxCalculateOverSubtotal($this->subtotal, $this->tax_rules->where('product_class_tax_id', $this->product_class_tax_id));
            $this->tax_amount   = $taxes->sum('taxAmount');
            $this->total        = $this->subtotal + $this->tax_amount;

            return $this->total;
        }

        // taxAmount property
        if($key === 'tax_amount')
        {
            $taxes = TaxRuleService::taxCalculateOverSubtotal($this->subtotal, $this->tax_rules);
            return $taxes->sum('taxAmount');
        }

        if($key === 'tax_rules')
        {
            $sessionTaxRules = session('pulsar.market.taxRules');

            $sessionTaxRules->transform(function ($taxRule, $key) {
                if($taxRule->productClassTaxes->where('id', $this->product_class_tax_id)->count() > 0)
                    return $taxRule;
            });

            return $sessionTaxRules->sortBy('priority');

            // TODO comprobar rendimiento
            /*$this->tax_rules = TaxRule::builder()
                ->where('country_id', config('pulsar.market.taxCountryDefault'))
                ->where('customer_class_tax_id', config('pulsar.market.taxCustomerClassDefault'))
                ->where('product_class_tax_id', $this->product_class_tax_id)
                ->orderBy('priority', 'asc')
                ->get();*/

            //return $this->tax_rules;
        }

        // call parent method in model
        return parent::getAttribute($key);
    }
}