<?php namespace Syscover\Market\Models;

use Syscover\Core\Models\CoreModel;
use Illuminate\Support\Facades\Validator;
use Syscover\Crm\Models\Group;

/**
 * Class GroupCustomerClassTax
 * @package Syscover\Market\Models
 */

class GroupCustomerClassTax extends CoreModel
{
    protected $table        = 'market_group_customer_class_tax';
    protected $fillable     = ['group_id', 'customer_class_tax_id'];
    protected $primaryKey   = 'group_id';
    public $with            = ['group', 'customer_class_tax'];

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
        return $query
            ->join('crm_group', 'market_group_customer_class_tax.group_id', '=', 'crm_group.id')
            ->join('market_customer_class_tax', 'market_group_customer_class_tax.customer_class_tax_id', '=', 'market_customer_class_tax.id')
            ->select('crm_group.*', 'market_customer_class_tax.*', 'market_group_customer_class_tax.*', 'crm_group.name as group_name', 'market_customer_class_tax.name as customer_class_tax_name');
    }

    public function group()
    {
        return $this->belongsTo(Group::class, 'group_id');
    }

    public function customer_class_tax()
    {
        return $this->belongsTo(CustomerClassTax::class, 'customer_class_tax_id');
    }
}
