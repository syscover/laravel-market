<?php namespace Syscover\Market\Models;

use Syscover\Core\Models\CoreModel;
use Illuminate\Support\Facades\Validator;

/**
 * Class TaxRateZone
 * @package Syscover\Market\Models
 */

class TaxRateZone extends CoreModel
{
	protected $table        = 'tax_rate_zone';
    protected $fillable     = ['id', 'name', 'country_id', 'territorial_area_1_id', 'territorial_area_2_id', 'territorial_area_3_id', 'cp', 'tax_rate'];
    public $timestamps      = false;
    private static $rules   = [];

    public static function validate($data)
    {
        return Validator::make($data, static::$rules);
	}

    public function scopeBuilder($query)
    {
        return $query;
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