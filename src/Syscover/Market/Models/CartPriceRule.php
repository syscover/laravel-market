<?php namespace Syscover\Market\Models;

use Syscover\Core\Models\CoreModel;
use Illuminate\Support\Facades\Validator;
use Syscover\Admin\Models\Lang;

/**
 * Class CartPriceRule
 * @package Syscover\Market\Models
 */

class CartPriceRule extends CoreModel
{
	protected $table        = 'cart_price_rule';
    protected $fillable     = ['id', 'name_text_id', 'description_text_id', 'active', 'has_coupon', 'coupon_code', 'combinable', 'uses_coupon', 'uses_customer', 'total_used', 'enable_from', 'enable_from_text', 'enable_to', 'enable_to_text', 'apply', 'discount_type_id', 'discount_fixed_amount', 'discount_percentage', 'maximum_discount_amount', 'apply_shipping_amount', 'free_shipping', 'rules', 'data_lang'];
    public $timestamps      = false;
    private static $rules   = [
        'name'              => 'required',
        'discount_type_id'  => 'required'
    ];

    public static function validate($data)
    {
        return Validator::make($data, static::$rules);
	}

    public function scopeBuilder($query, $lang = null)
    {
        return $query->select(DB::raw('*, text1.text AS name_text_value, text2.text AS description_text_value'))
            ->join('text AS text1', function ($join) use ($lang) {
                $join->on('cart_price_rule.name_text_id', '=', 'text1.id');
                if($lang !== null)  $join->where('text1.lang_id', '=', $lang);
            })
            ->join('text AS text2', function ($join) use ($lang) {
                $join->on('cart_price_rule.description_text_id', '=', 'text2.id');
                if($lang !== null)  $join->where('text2.lang_id', '=', $lang);
            });
    }

    /**
     * Get lang from Text object, that it has relation with name_text_id
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function getLang()
    {
        return $this->belongsTo(Lang::class, 'lang_id');
    }
}