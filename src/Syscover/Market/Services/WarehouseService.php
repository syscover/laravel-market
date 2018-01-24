<?php namespace Syscover\Market\Services;

use Syscover\Market\Models\Warehouse;

class WarehouseService
{
    /**
     * Function to create a warehouse
     * @param   array                           $object
     * @return  \Syscover\Market\Models\Warehouse
     * @throws  \Exception
     */
    public static function create($object)
    {
        return Warehouse::create($object);
    }

    /**
     * @param   array     $object     contain properties of section
     * @param   int       $id         id of warehouse
     * @return  \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|null|static|static[]
     */
    public static function update($object, $id)
    {
        // pass object to collection
        $object = collect($object);

        Warehouse::where('id', $id)
            ->update([
                'name'                  => $object->get('name'),
                'country_id'            => $object->get('country_id'),
                'territorial_area_1_id' => $object->get('territorial_area_1_id'),
                'territorial_area_2_id' => $object->get('territorial_area_2_id'),
                'territorial_area_3_id' => $object->get('territorial_area_3_id'),
                'zip'                   => $object->get('zip'),
                'locality'              => $object->get('locality'),
                'address'               => $object->get('address'),
                'latitude'              => $object->get('latitude'),
                'longitude'             => $object->get('longitude'),
                'active'                => $object->has('active'),
            ]);

        return Warehouse::find($object->get('id'));
    }
}