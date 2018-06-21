<?php namespace Syscover\Market\Events;

use Syscover\Market\Models\Order;

class RedsysResponseSuccessful
{
    public $order;

    /**
     * Create a new event instance.
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
    }
}