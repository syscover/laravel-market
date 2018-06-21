<?php namespace Syscover\Market\Events;

use Syscover\Market\Models\Order;

class RedsysAsyncResponse
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