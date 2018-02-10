<?php namespace Syscover\Market\Services;

use Syscover\Market\Models\Category;

class CategoryService
{
    /**
     * Function to create a category
     * @param   array                           $object
     * @return  \Syscover\Market\Models\Category
     * @throws  \Exception
     */
    public static function create($object)
    {
        if(empty($object['id']))
        {
            $id = Category::max('id');
            $id++;
            $object['id'] = $id;
        }

        $object['data_lang'] = Category::addDataLang($object['lang_id'], $object['id']);

        return Category::create($object);
    }

    /**
     * @param   array     $object     contain properties of section
     * @return  \Syscover\Market\Models\Category
     */
    public static function update($object)
    {
        // pass object to collection
        $object = collect($object);

        Category::where('ix', $object->get('ix'))
            ->update([
                'parent_id'             => $object->get('parent_id'),
                'name'                  => $object->get('name'),
                'slug'                  => $object->get('slug'),
                'active'                => $object->get('active'),
                'description'           => $object->get('description'),
            ]);

        return Category::where('id', $object->get('ix'))
            ->first();
    }
}