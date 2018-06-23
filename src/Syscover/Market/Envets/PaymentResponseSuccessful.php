<?php namespace Syscover\Market\Events;

use Syscover\Market\Models\Order;

class PaymentResponseSuccessful
{
    public $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }
}