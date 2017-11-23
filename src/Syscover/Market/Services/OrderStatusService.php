<?php namespace Syscover\Market\Services;

use Syscover\Market\Models\OrderStatus;

class OrderStatusService
{
    /**
     * Function to create a order status
     * @param   array  $object
     * @return  \Syscover\Market\Models\OrderStatus
     * @throws  \Exception
     */
    public static function create($object)
    {
        if(empty($object['id']))
        {
            $id = OrderStatus::max('id');
            $id++;
            $object['id'] = $id;
        }

        $object['data_lang'] = OrderStatus::addDataLang($object['lang_id'], $object['id']);

        return OrderStatus::create($object);
    }

    /**
     * @param   array     $object     contain properties of section
     * @return  \Syscover\Market\Models\OrderStatus
     */
    public static function update($object)
    {
        // pass object to collection
        $object = collect($object);

        OrderStatus::where('id', $object->get('id'))
            ->update([
                'name'                  => $object->get('name'),
                'active'                => $object->get('active')
            ]);

        return OrderStatus::where('id', $object->get('id'))
            ->first();
    }
}