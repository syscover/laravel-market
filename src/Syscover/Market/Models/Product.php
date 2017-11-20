<?php namespace Syscover\Market\Models;

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
	protected $fillable     = ['id', 'code', 'field_group_id', 'type_id', 'parent_id', 'weight', 'active', 'sort', 'price_type_id', 'subtotal', 'product_class_tax_id', 'data_lang', 'data'];
    protected $casts        = [
        'active'    => 'boolean',
        'data_lang' => 'array',
        'data'      => 'array'
    ];
    public $with = [
        'lang',
        'fieldGroup',
        'categories',
        'stocks',
        'children_products'
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
        return $query->join('market_product_lang', 'market_product.id', '=', 'market_product_lang.object_id')
            ->select('market_product_lang.*', 'market_product.*', 'market_product_lang.id as market_product_lang_id', 'market_product.id as market_product_id');
    }

    public function children_products()
    {
        return $this->hasMany(Product::class, 'parent_id', 'id')
            ->builder();
    }

    public function parent_product()
    {
        return $this->belongsTo(Product::class, 'parent_id')
            ->builder();
    }

//    public function categories()
//    {
//        return $this->belongsToMany(Category::class, 'market_products_categories', 'product_id', 'category_id');
//    }
    public function categories()
    {
        return $this->belongsToMany(
            Category::class,
            'market_products_categories',
            'product_id',
            'category_object_id',
            'id',
            'object_id'
        );
    }

    public function attachments()
    {
        return $this->morphMany(Attachment::class, 'object')
            ->where('admin_attachment.lang_id', $this->lang_id)
            ->orderBy('sort', 'asc');
    }

    public function stocks()
    {
        return $this->hasMany(Stock::class, 'product_id', 'id');
    }



//    public function whereChildrenProperty($property, $value)
//    {
//        $response = collect();
//
//        foreach ($this->products as $product)
//        {
//            if(
//                isset($product->data['properties']) &&
//                isset($product->data['properties'][$property]) &&
//                $product->data['properties'][$property] === $value
//            )
//            {
//               $response->push($product);
//            }
//        }
//        return $response;
//    }



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
            if(config('pulsar-market.productTaxDisplayPrices') == TaxRuleService::PRICE_WITHOUT_TAX)
            {
                return $this->subtotal;
            }
            elseif(config('pulsar-market.productTaxDisplayPrices') == TaxRuleService::PRICE_WITH_TAX)
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
            $sessionTaxRules = session('pulsar-market.taxRules');

            $sessionTaxRules->transform(function ($taxRule, $name) {
                if($taxRule->product_class_taxes->where('id', $this->product_class_tax_id)->count() > 0)
                    return $taxRule;
            });

            return $sessionTaxRules->sortBy('priority');
        }

        // custom fields
        if(
            isset($this->data['customFields']) &&
            is_array($this->data['customFields']) &&
            array_key_exists($name, $this->data['customFields'])
        )
        {
            return $this->data['customFields'][$name];
        }

        return parent::__get($name);
    }
}