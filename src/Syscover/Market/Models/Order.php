<?php namespace Syscover\Market\Models;

use Carbon\Carbon;
use Syscover\Core\Models\CoreModel;
use Illuminate\Support\Facades\Validator;
use Syscover\Crm\Models\Customer;

/**
 * Class Order
 * @package Syscover\Market\Models
 */

class Order extends CoreModel
{
	protected $table        = 'market_order';
    protected $fillable     = ['id', 'date', 'payment_method_id', 'status_id', 'ip', 'data', 'comments', 'transaction_id', 'discount_amount', 'subtotal_with_discounts', 'tax_amount', 'cart_items_total_without_discounts', 'subtotal', 'shipping_amount', 'total', 'has_gift', 'gift_from', 'gift_to', 'gift_message', 'customer_id', 'customer_group_id', 'customer_company', 'customer_tin', 'customer_name', 'customer_surname', 'customer_email', 'customer_mobile', 'customer_phone', 'has_invoice', 'invoiced', 'invoice_number', 'invoice_company', 'invoice_tin', 'invoice_name', 'invoice_surname', 'invoice_email', 'invoice_mobile', 'invoice_phone', 'invoice_country_id', 'invoice_territorial_area_1_id', 'invoice_territorial_area_2_id', 'invoice_territorial_area_3_id', 'invoice_cp', 'invoice_locality', 'invoice_address', 'invoice_latitude', 'invoice_longitude', 'invoice_comments', 'has_shipping', 'shipping_company', 'shipping_name', 'shipping_surname', 'shipping_email', 'shipping_mobile', 'shipping_phone', 'shipping_country_id', 'shipping_territorial_area_1_id', 'shipping_territorial_area_2_id', 'shipping_territorial_area_3_id', 'shipping_cp', 'shipping_locality', 'shipping_address', 'shipping_latitude', 'shipping_longitude', 'shipping_comments'];
    protected $casts        = [
        'data' => 'array'
    ];
    public $with = [
        'payment_methods',
        'statuses'
    ];
    private static $rules   = [
        'status'            => 'required',
        'payment_method_id' => 'required',
        'gift_from'         => 'between:2,255',
        'gift_to'           => 'between:2,255',
        'customer_id'       => 'required',
        'customer_company'  => 'between:2,255',
        'customer_tin'      => 'between:2,255',
        'customer_name'     => 'between:2,255',
        'customer_surname'  => 'between:2,255',
        'customer_email'    => 'required|between:2,255|email',
    ];

    public static function validate($data)
    {
        return Validator::make($data, static::$rules);
	}

    public function scopeBuilder($query)
    {
        // we not relate to countries, because there are two countries which relate, invoice and shipping, has not been found to create aliases on columns within a join
        return $query->join('crm_customer', 'market_order.customer_id', '=', 'crm_customer.id')
            ->join('market_payment_method', function ($join) {
                 $join->on('market_order.payment_method_id', '=', 'market_payment_method.id')
                     ->where('market_payment_method.lang_id', '=', base_lang());
            })
            ->join('market_order_status', function ($join) {
                $join->on('market_order.status_id', '=', 'market_order_status.id')
                    ->where('market_order_status.lang_id', '=', base_lang());
            })
            ->select('crm_customer.*', 'market_payment_method.*', 'market_order_status.*', 'market_order.*', 'crm_customer.id as crm_customer_id', 'market_payment_method.id as market_payment_method_id', 'market_order_status.id as market_order_status_id', 'market_order.id as market_order_id');
    }

    public function statuses()
    {
        return $this->hasMany(OrderStatus::class, 'id','status_id');
    }

    public function payment_methods()
    {
        return $this->hasMany(PaymentMethod::class, 'id', 'payment_method_id');
    }

    public function rows()
    {
        return $this->hasMany(OrderRow::class, 'order_id');
    }

    public function discounts()
    {
        return $this->hasMany(CustomerDiscountHistory::class, 'order_id');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function setOrderLog($message)
    {
        $dataOrder = json_decode($this->data, true);

        if(! isset($dataOrder['log']))
            $dataOrder['log'] = [];

        array_unshift($dataOrder['log'], [
            'time'      => Carbon::now(),
            'status'    => $this->status_id,
            'message'   => $message
        ]);

        $this->data = json_encode($dataOrder);
        $this->save();
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
     * Get format cart items total without discounts
     *
     * @param   int     $decimals
     * @param   string  $decimalPoint
     * @param   string  $thousandSeparator
     * @return  string
     */
    public function getCartItemsTotalWithoutDiscounts($decimals = 2, $decimalPoint = ',', $thousandSeparator = '.')
    {
        return number_format($this->cart_items_total_without_discounts, $decimals, $decimalPoint, $thousandSeparator);
    }

    /**
     * Get format shipping amount
     *
     * @param   int     $decimals
     * @param   string  $decimalPoint
     * @param   string  $thousandSeparator
     * @return  string
     */
    public function getShippingAmount($decimals = 2, $decimalPoint = ',', $thousandSeparator = '.')
    {
        return number_format($this->shipping_amount, $decimals, $decimalPoint, $thousandSeparator);
    }

    /**
     * Get total without shipping
     *
     * @param   int     $decimals
     * @param   string  $decimalPoint
     * @param   string  $thousandSeparator
     * @return  string
     */
    public function getTotalWithoutShipping($decimals = 2, $decimalPoint = ',', $thousandSeparator = '.')
    {
        return number_format($this->total - $this->shipping_amount, $decimals, $decimalPoint, $thousandSeparator);
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