<?php namespace Syscover\Market\Events;

use Syscover\Market\Models\Order;

class PaymentResponseError
{
    public $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }
}