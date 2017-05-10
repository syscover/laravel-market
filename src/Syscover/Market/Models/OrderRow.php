<?php namespace Syscover\Market\Models;

use Syscover\Core\Models\CoreModel;
use Illuminate\Support\Facades\Validator;

/**
 * Class OrderRow
 * @package Syscover\Market\Models
 */

class OrderRow extends CoreModel
{
	protected $table        = 'order_row';
    protected $fillable     = ['id', 'lang_id', 'order_id', 'product_id', 'name', 'description', 'data', 'price', 'quantity', 'subtotal', 'total_without_discounts', 'discount_subtotal_percentage', 'discount_total_percentage', 'discount_subtotal_percentage_amount', 'discount_total_percentage_amount', 'discount_subtotal_fixed_amount', 'discount_total_fixed_amount', 'discount_amount', 'tax_rules', 'tax_amount', 'has_gift', 'gift_from', 'gift_to', 'gift_message'];
    public $timestamps      = false;

    private static $rules   = [];

    public static function validate($data)
    {
        return Validator::make($data, static::$rules);
	}

    public function scopeBuilder($query, $lang = null)
    {
        return $query->leftJoin('product', 'order_row.product_id', '=', 'product.id')
            ->leftJoin('product_lang', function($join) use ($lang) {
                $join->on('product.id', '=', 'product_lang.id');
                if($lang !== null)
                    $join->where('product_lang.lang_id', '=', $lang);
                else
                    // always need filter by lang, because order form,
                    // need filter your order rows by lang without pass lang variable
                    $join->where('product_lang.lang_id', '=', base_lang());
            });
    }

    /**
     * Get price amount formated
     *
     * @param   int     $decimals
     * @param   string  $decimalPoint
     * @param   string  $thousandSeparator
     * @return  float
     */
    public function getPrice($decimals = 2, $decimalPoint = ',', $thousandSeparator = '.')
    {
        return number_format($this->price, $decimals, $decimalPoint, $thousandSeparator);
    }

    /**
     * Get format quantity over this cart item
     *
     * @param   int     $decimals
     * @param   string  $decimalPoint
     * @param   string  $thousandSeparator
     * @return  string
     */
    public function getQuantity($decimals = 0, $decimalPoint = ',', $thousandSeparator = '.')
    {
        return number_format($this->quantity, $decimals, $decimalPoint, $thousandSeparator);
    }

    /**
     * Get subtotal
     *
     * @param   int     $decimals
     * @param   string  $decimalPoint
     * @param   string  $thousandSeparator
     * @return  string
     */
    public function getSubtotal($decimals = 2, $decimalPoint = ',', $thousandSeparator = '.')
    {
        return number_format($this->subtotal, $decimals, $decimalPoint, $thousandSeparator);
    }

    /**
     * Get format total without dicounts
     *
     * @param   int     $decimals
     * @param   string  $decimalPoint
     * @param   string  $thousandSeparator
     * @return  string
     */
    public function getTotalWithoutDiscounts($decimals = 2, $decimalPoint = ',', $thousandSeparator = '.')
    {
        return number_format($this->total_without_discounts, $decimals, $decimalPoint, $thousandSeparator);
    }

    /**
     * Get format discount subtotal percentage
     *
     * @param   int     $decimals
     * @param   string  $decimalPoint
     * @param   string  $thousandSeparator
     * @return  string
     */
    public function getDiscountSubtotalPercentage($decimals = 0, $decimalPoint = ',', $thousandSeparator = '.')
    {
        return number_format($this->discount_subtotal_percentage, $decimals, $decimalPoint, $thousandSeparator);
    }

    /**
     * Get format discount total percentage
     *
     * @param   int     $decimals
     * @param   string  $decimalPoint
     * @param   string  $thousandSeparator
     * @return  string
     */
    public function getDiscountTotalPercentage($decimals = 0, $decimalPoint = ',', $thousandSeparator = '.')
    {
        return number_format($this->discount_total_percentage, $decimals, $decimalPoint, $thousandSeparator);
    }

    /**
     * Get format discount subtotal percentage amount
     *
     * @param   int     $decimals
     * @param   string  $decimalPoint
     * @param   string  $thousandSeparator
     * @return  string
     */
    public function getDiscountSubtotalPercentageAmount($decimals = 2, $decimalPoint = ',', $thousandSeparator = '.')
    {
        return number_format($this->discount_subtotal_percentage_amount, $decimals, $decimalPoint, $thousandSeparator);
    }

    /**
     * Get format discount total percentage amount
     *
     * @param   int     $decimals
     * @param   string  $decimalPoint
     * @param   string  $thousandSeparator
     * @return  string
     */
    public function getDiscountTotalPercentageAmount($decimals = 2, $decimalPoint = ',', $thousandSeparator = '.')
    {
        return number_format($this->discount_total_percentage_amount, $decimals, $decimalPoint, $thousandSeparator);
    }

    /**
     * Get format discount subtotal fixed amount
     *
     * @param   int     $decimals
     * @param   string  $decimalPoint
     * @param   string  $thousandSeparator
     * @return  string
     */
    public function getDiscountSubtotalFixedAmount($decimals = 2, $decimalPoint = ',', $thousandSeparator = '.')
    {
        return number_format($this->discount_subtotal_fixed_amount, $decimals, $decimalPoint, $thousandSeparator);
    }

    /**
     * Get format discount total fixed amount
     *
     * @param   int     $decimals
     * @param   string  $decimalPoint
     * @param   string  $thousandSeparator
     * @return  string
     */
    public function getDiscountTotalFixedAmount($decimals = 2, $decimalPoint = ',', $thousandSeparator = '.')
    {
        return number_format($this->discount_total_fixed_amount, $decimals, $decimalPoint, $thousandSeparator);
    }

    /**
     * Get format discount amount
     *
     * @param   int     $decimals
     * @param   string  $decimalPoint
     * @param   string  $thousandSeparator
     * @return  string
     */
    public function getDiscountAmount($decimals = 2, $decimalPoint = ',', $thousandSeparator = '.')
    {
        return number_format($this->discount_amount, $decimals, $decimalPoint, $thousandSeparator);
    }

    /**
     * Get format subtotal with discounts amount
     *
     * @param   int     $decimals
     * @param   string  $decimalPoint
     * @param   string  $thousandSeparator
     * @return  string
     */
    public function getSubtotalWithDiscounts($decimals = 2, $decimalPoint = ',', $thousandSeparator = '.')
    {
        return number_format($this->subtotal_with_discounts, $decimals, $decimalPoint, $thousandSeparator);
    }

    /**
     * Get format tax amount
     *
     * @param   int     $decimals
     * @param   string  $decimalPoint
     * @param   string  $thousandSeparator
     * @return  string
     */
    public function getTaxAmount($decimals = 2, $decimalPoint = ',', $thousandSeparator = '.')
    {
        return number_format($this->tax_amount, $decimals, $decimalPoint, $thousandSeparator);
    }

    /**
     * Get format total
     *
     * @param   int     $decimals
     * @param   string  $decimalPoint
     * @param   string  $thousandSeparator
     * @return  string
     */
    public function getTotal($decimals = 2, $decimalPoint = ',', $thousandSeparator = '.')
    {
        return number_format($this->total, $decimals, $decimalPoint, $thousandSeparator);
    }
}