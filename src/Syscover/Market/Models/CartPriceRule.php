<?php namespace Syscover\Market\Models;

use Syscover\Core\Models\CoreModel;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

/**
 * Class CartPriceRule
 * @package Syscover\Market\Models
 */

class CartPriceRule extends CoreModel
{
	protected $table        = 'market_cart_price_rule';
    protected $fillable     = ['id', 'names', 'descriptions', 'active', 'customer_group_ids', 'customer_ids', 'combinable', 'priority', 'has_coupon', 'coupon_code', 'customer_uses', 'coupon_uses', 'total_uses', 'enable_from', 'enable_to', 'discount_type_id', 'discount_fixed_amount', 'discount_percentage', 'maximum_discount_amount', 'apply_shipping_amount', 'free_shipping', 'rules', 'data_lang'];
    protected $casts        = [
        'names'                 => 'array',
        'descriptions'          => 'array',
        'customer_group_ids'    => 'array',
        'customer_ids'          => 'array',
        'condition_rules'       => 'array',
        'product_rules'         => 'array',
        'data_lang'             => 'array'
    ];

    private static $rules   = [
        'name'              => 'required',
        'discount_type_id'  => 'required'
    ];

    public static function validate($data)
    {
        return Validator::make($data, static::$rules);
	}

    // Accessors
    public function getEnableFromAttribute($value)
    {
        // https://es.wikipedia.org/wiki/ISO_8601
        // return (new Carbon($value))->toW3cString();
        if($value)
        {
            return (new Carbon($value))->format('Y-m-d\TH:i:s');
        }
        return null;
    }

    public function getEnableToAttribute($value)
    {
        // https://es.wikipedia.org/wiki/ISO_8601
        // return (new Carbon($value))->toW3cString();
        if($value)
        {
            return (new Carbon($value))->format('Y-m-d\TH:i:s');
        }
        return null;
    }

    /**
     * Overwrite deleteTranslationRecord from CoreModel to delete
     * json language field, in names and descriptions columns
     * @param       $id
     * @param       $langId
     * @param bool  $deleteLangDataRecord
     * @param array $filters  filters to select and delete records
     */
    public static function deleteTranslationRecord($id, $langId, $deleteLangDataRecord = true, $filters = [])
    {
        $field = CartPriceRule::find($id);

        $names = collect($field->names); // get names
        $field->names = $names->filter(function($value, $key) use ($langId) {
            return $value['id'] !== $langId;
        });

        $descriptions = collect($field->descriptions); // get descriptions
        $field->descriptions = $descriptions->filter(function($value, $key) use ($langId) {
            return $value['id'] !== $langId;
        });

        $field->save(); // save values

        // set values on data_lang
        CartPriceRule::deleteDataLang($langId, $id, 'id');
    }
}