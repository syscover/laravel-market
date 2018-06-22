<?php namespace Syscover\Market\Events;

use Syscover\Market\Models\Order;

class RedsysResponseError
{
    public $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }
}