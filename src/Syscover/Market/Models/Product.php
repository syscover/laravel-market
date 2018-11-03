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
    use CustomizableValues {
        __get as traitGet;
    }
    use CustomizableFields, Translatable;

	protected $table        = 'market_product';
	protected $fillable     = ['sku', 'field_group_id', 'object_type', 'object_id', 'type_id', 'parent_id', 'weight', 'active', 'sort', 'price_type_id', 'subtotal', 'product_class_tax_id', 'data_lang', 'data'];
    protected $casts        = [
        'active'                    => 'boolean',
        'data_lang'                 => 'array',
        'data'                      => 'array',
        'market_product_data'       => 'array', // field created from join in builder scope
        'market_product_lang_data'  => 'array', // field created from join in builder scope
    ];
    protected $with         = [
        'categories',
        'children_products',
        'field_group',
        'lang',
        'sections',
        'stocks'
    ];
    protected $appends      = ['tax_amount', 'price'];
    public $lazyRelations   = ['attachments'];

    private static $rules   = [
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

    public function scopeSectionsProducts($query, $sections)
    {
        return $query
            ->builder()
            ->whereIn('market_product.id', function($query) use ($sections) {
                $query
                    ->select('product_id')
                    ->from('market_products_sections')
                    ->whereIn('section_id', $sections)
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

    public function sections()
    {
        return $this->belongsToMany(
            Section::class,
            'market_products_sections',
            'product_id',
            'section_id',
            'id',
            'id'
        );
    }

    public function marketable()
    {
        return $this->morphTo('marketable', 'object_type', 'object_id', 'id');
    }

    /**
     * Is not possible add 'attachments' to $with paramenter, it need to be instantiated to get lang parameter
     * It's possible pass lang parameter with this method
     *
     * Product::with(['attachments' => function ($q) use ($langId) {
     *   $q->where('admin_attachment.lang_id', $langId);
     * }])->get();
     */
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
     * Accessor to get tax_amount attribute from api request
     *
     * @return mixed
     */
    public function getTaxAmountAttribute()
    {
        return $this->tax_amount;
    }

    /**
     * Accessor to get price attribute from api request
     *
     * @return mixed
     */
    public function getPriceAttribute()
    {
        return $this->price;
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

            $taxes              = TaxRuleService::taxCalculateOverSubtotal($this->subtotal, $this->tax_rules);
            $this->tax_amount   = $taxes->sum('tax_amount');
            $this->total        = $this->subtotal + $this->tax_amount;

            return $this->total;
        }

        // taxAmount property
        if($name === 'tax_amount')
        {
            $taxes = TaxRuleService::taxCalculateOverSubtotal($this->subtotal, $this->tax_rules);
            return $taxes->sum('tax_amount');
        }

        if($name === 'tax_rules')
        {
            return TaxRuleService::getTaxRules()->where('product_class_tax_id', $this->product_class_tax_id);
        }

        return $this->traitGet($name);
    }
}