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
        if(! isset($object['id']))
        {
            $id = Category::max('id');
            $id++;
            $object['id'] = $id;
        }

        $object['data_lang'] = Category::addLangDataRecord($object['lang_id'], $object['id']);

        return Category::create($object);
    }

    /**
     * @param   array     $object     contain properties of section
     * @param   int       $id         id of category
     * @param   string    $lang       lang of category
     * @return  \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|null|static|static[]
     */
    public static function update($object, $id, $lang)
    {
        // pass object to collection
        $object = collect($object);

        Category::where('id', $id)
            ->where('lang_id', $lang)
            ->update([
                'parent_id'             => $object->get('parent_id'),
                'name'                  => $object->get('name'),
                'slug'                  => $object->get('slug'),
                'active'                => $object->get('active'),
                'description'           => $object->get('description'),
            ]);

        return Category::find($object->get('id'));
    }
}