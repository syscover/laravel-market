<?php namespace Syscover\Market\Models;

use Syscover\Core\Models\CoreModel;
use Illuminate\Support\Facades\Validator;

/**
 * Class GroupCustomerClassTax
 * @package Syscover\Market\Models
 */

class GroupCustomerClassTax extends CoreModel
{
	protected $table        = 'group_customer_class_tax';
    protected $fillable     = ['group_id', 'customer_class_tax_id'];
    protected $primaryKey   = 'group_id';
    public $timestamps      = false;
    private static $rules   = [
        'group_id'              => 'required',
        'customer_class_tax_id' => 'required'
    ];

    public static function validate($data, $specialRules = [])
    {
        if(isset($specialRules['group_id']) && ! $specialRules['group_id']) static::$rules['group_id'] = '';

        return Validator::make($data, static::$rules);
	}

    public function scopeBuilder($query)
    {
        return $query->join('group', 'group_customer_class_tax.group_id', '=', 'group.id')
            ->join('customer_class_tax', 'group_customer_class_tax.customer_class_tax_id', '=', 'customer_class_tax.id')
            ->select(
                'customer_class_tax.*', 'group.*',
                'group.id as group_id', 'group.name as group_name',
                'customer_class_tax.id as customer_class_tax_id', 'customer_class_tax.name as customer_class_tax_name'
            );
    }
}