<?php namespace Syscover\Market\Models;

use Syscover\Core\Models\CoreModel;
use Illuminate\Support\Facades\Validator;
use Syscover\Crm\Models\CustomerGroup;

/**
 * Class CustomerGroupCustomerClassTax
 * @package Syscover\Market\Models
 */

class CustomerGroupCustomerClassTax extends CoreModel
{
    protected $table        = 'market_customer_group_customer_class_tax';
    protected $fillable     = ['customer_group_id', 'customer_class_tax_id'];
    protected $primaryKey   = 'customer_group_id';
    public $with            = ['customer_group', 'customer_class_tax'];

    private static $rules   = [
        'customer_group_id'     => 'required',
        'customer_class_tax_id' => 'required'
    ];

    public static function validate($data, $specialRules = [])
    {
        if(isset($specialRules['customer_group_id']) && ! $specialRules['customer_group_id']) static::$rules['customer_group_id'] = '';

        return Validator::make($data, static::$rules);
    }

    public function scopeBuilder($query)
    {
        return $query
            ->join('crm_customer_group', 'market_customer_group_customer_class_tax.customer_group_id', '=', 'crm_customer_group.id')
            ->join('market_customer_class_tax', 'market_customer_group_customer_class_tax.customer_class_tax_id', '=', 'market_customer_class_tax.id')
            ->select(
                'crm_customer_group.*',
                'market_customer_class_tax.*',
                'market_customer_group_customer_class_tax.*',
                'crm_customer_group.name as crm_customer_group_name',
                'market_customer_class_tax.name as market_customer_class_tax_name',
                'crm_customer_group.id as crm_customer_group_id',
                'market_customer_class_tax.id as market_customer_class_tax_id'
            );
    }

    public function customer_group()
    {
        return $this->belongsTo(CustomerGroup::class, 'customer_group_id');
    }

    public function customer_class_tax()
    {
        return $this->belongsTo(CustomerClassTax::class, 'customer_class_tax_id');
    }
}
