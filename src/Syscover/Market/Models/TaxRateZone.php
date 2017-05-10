<?php namespace Syscover\Market\Models;

use Syscover\Core\Models\CoreModel;
use Illuminate\Support\Facades\Validator;
use Syscover\Admin\Models\Country;

/**
 * Class TaxRateZone
 * @package Syscover\Market\Models
 */

class TaxRateZone extends CoreModel
{
	protected $table        = 'tax_rate_zone';
    protected $fillable     = ['id', 'name', 'country_id', 'territorial_area_1_id', 'territorial_area_2_id', 'territorial_area_3_id', 'cp', 'tax_rate'];
    public $timestamps      = false;
    public $with            = ['country.lang'];

    private static $rules   = [];

    public static function validate($data)
    {
        return Validator::make($data, static::$rules);
	}

    public function scopeBuilder($query)
    {
        return $query->join('country', function ($join) {
                $join->on('tax_rate_zone.country_id', '=', 'country.id')
                    ->where('country.lang_id', '=', base_lang());
            })
            ->select('tax_rate_zone.*');
    }

    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id')
            ->where('country.lang_id', '=', base_lang());
    }

    /**
     * Returns formatted tax rate.
     *
     * @param   int       $decimals
     * @param   string    $decimalPoint
     * @param   string    $thousandSeperator
     * @return  string
     */
    public function getTaxRate($decimals = 0, $decimalPoint = ',', $thousandSeperator = '.')
    {
        return number_format($this->tax_rate, $decimals, $decimalPoint, $thousandSeperator);
    }
}