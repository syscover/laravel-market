<?php namespace Syscover\Market\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Syscover\Admin\Models\Attachment;
use Syscover\Core\Models\CoreModel;
use Syscover\Market\Services\TaxRuleService;
use Syscover\Admin\Traits\CustomizableFields;
use Syscover\Admin\Traits\CustomizableValues;
use Syscover\Admin\Traits\Translatable;

/**
 * Class Product
 * @package Syscover\Market\Models
 */

class Product extends CoreModel
{
    use CustomizableValues;
    use CustomizableFields, Translatable;

	protected $table        = 'market_product';
	protected $fillable     = ['id', 'sku', 'field_group_id', 'type_id', 'parent_id', 'weight', 'active', 'sort', 'price_type_id', 'subtotal', 'product_class_tax_id', 'data_lang', 'data'];
    protected $casts        = [
        'active'                    => 'boolean',
        'data_lang'                 => 'array',
        'data'                      => 'array',
        'market_product_data'       => 'array', // field created from join in builder scope
        'market_product_lang_data'  => 'array', // field created from join in builder scope
    ];
    public $with = [
        'lang',
        'field_group',
        'categories',
        'stocks',
        'children_products',
        'attachments'
    ];
    public $lazyRelations       = ['attachments'];

    private static $rules       = [
        'price_type_id'         => 'required',
        'type_id'               => 'required',
    ];

    public static function validate($data)
    {
        return Validator::make($data, static::$rules);
	}

    public function scopeBuilder($query)
    {
        return $query
            ->join('market_product_lang', 'market_product.id', '=', 'market_product_lang.id')
            ->addSelect('market_product.*', 'market_product_lang.*', 'market_product_lang.data as market_product_lang_data', 'market_product.data as market_product_data');
    }

    public function scopeCalculateFoundRows($query)
    {
        return $query->select(DB::raw('SQL_CALC_FOUND_ROWS market_product.id'));
    }

    public function scopeCategoriesProducts($query, $categories)
    {
        return $query
            ->builder()
            ->whereIn('market_product.id', function($query) use ($categories) {
                $query
                    ->select('product_id')
                    ->from('market_products_categories')
                    ->whereIn('category_id', $categories)
                    ->groupBy('product_id')
                    ->get();
            });
    }

    public function children_products()
    {
        return $this->hasMany(
            Product::class,
            'parent_id',
            'id'
            )
            ->builder();
    }

    public function parent_product()
    {
        return $this->belongsTo(Product::class, 'parent_id')
            ->builder();
    }

    public function categories()
    {
        return $this->belongsToMany(
            Category::class,
            'market_products_categories',
            'product_id',
            'category_id',
            'id',
            'id'
        );
    }

    public function attachments()
    {
        return $this->morphMany(
                Attachment::class,
                'object',
                'object_type',
                'object_id',
                'id'
            )
            ->where('admin_attachment.lang_id', $this->lang_id ? $this->lang_id : user_lang())
            ->orderBy('sort', 'asc');
    }

    public function stocks()
    {
        return $this->hasMany(Stock::class, 'product_id', 'id');
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
     * Return total stock of all warehouses
     *
     * @return int
     */
    public function getTotalStock()
    {
        return $this->stocks->reduce(function($value, $item) {
            return $value + $item->stock;
        });
    }

    /**
     * Dynamically access route parameters.
     *
     * @param  string  $name
     * @return mixed
     */
    public function __get($name)
    {
        // price of product
        if($name === 'price')
        {
            if(config('pulsar-market.product_tax_display_prices') == TaxRuleService::PRICE_WITHOUT_TAX)
            {
                return $this->subtotal;
            }
            elseif(config('pulsar-market.product_tax_display_prices') == TaxRuleService::PRICE_WITH_TAX)
            {
                return $this->total; // call magic method
            }
        }

        // total price
        if($name === 'total')
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
        if($name === 'tax_amount')
        {
            $taxes = TaxRuleService::taxCalculateOverSubtotal($this->subtotal, $this->tax_rules);
            return $taxes->sum('taxAmount');
        }

        if($name === 'tax_rules')
        {
            $sessionTaxRules = session('pulsar-market.tax_rules');

            $sessionTaxRules = $sessionTaxRules->filter(function ($taxRule, $key) {
                return $taxRule->product_class_taxes->where('id', $this->product_class_tax_id)->count() > 0;
            });

            return $sessionTaxRules->sortBy('priority');
        }

        // custom fields
        $data = $this->getAttribute('data');

        if(
            isset($data['custom_fields']) &&
            is_array($data['custom_fields']) &&
            array_key_exists($name, $data['custom_fields'])
        )
        {
            return $data['custom_fields'][$name];
        }

        return parent::__get($name);
    }
}