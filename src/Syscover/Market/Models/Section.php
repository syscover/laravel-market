<?php namespace Syscover\Market\Models;

use Illuminate\Support\Facades\Validator;
use Syscover\Core\Models\CoreModel;
use Syscover\Admin\Traits\Langable;

/**
 * Class Section
 * @package Syscover\Market\Models
 */

class Section extends CoreModel
{
    use Langable;

	protected $table        = 'market_section';
    protected $primaryKey   = 'ix';
    protected $fillable     = ['ix', 'id', 'lang_id', 'name', 'slug', 'data_lang'];
    protected $with         = ['lang'];
    protected $casts        = [
        'data_lang' => 'array'
    ];
}
