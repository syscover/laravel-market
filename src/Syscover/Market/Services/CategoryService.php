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
        if(empty($object['object_id']))
        {
            $id = Category::max('object_id');
            $id++;
            $object['object_id'] = $id;
        }

        $object['data_lang'] = Category::addDataLang($object['lang_id'], $object['object_id']);

        return Category::create($object);
    }

    /**
     * @param   array     $object     contain properties of section
     * @return  \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|null|static|static[]
     */
    public static function update($object)
    {
        // pass object to collection
        $object = collect($object);

        Category::where('id', $object->get('id'))
            ->where('lang_id', $object->get('lang_id'))
            ->update([
                'parent_id'             => $object->get('parent_id'),
                'name'                  => $object->get('name'),
                'slug'                  => $object->get('slug'),
                'active'                => $object->get('active'),
                'description'           => $object->get('description'),
            ]);

        return Category::where('id', $object->get('id'))
            ->where('lang_id', $object->get('lang_id'))
            ->first();
    }
}