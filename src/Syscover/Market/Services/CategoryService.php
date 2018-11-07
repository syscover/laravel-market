<?php namespace Syscover\Market\Services;

use Syscover\Market\Models\Category;

class CategoryService
{
    public static function create($object)
    {
        self::checkCreate($object);

        if(empty($object['id'])) $object['id'] = next_id(Category::class);

        $object['data_lang'] = Category::addDataLang($object['lang_id'], $object['id']);

        return Category::create(self::builder($object));
    }

    public static function update($object)
    {
        self::checkUpdate($object);
        Category::where('id', $object['id'])->update(self::builder($object, ['parent_id', 'active']));
        Category::where('ix', $object['ix'])->update(self::builder($object, ['name', 'slug', 'description']));

        return Category::find($object['ix']);
    }

    private static function builder($object, $filterKeys = null)
    {
        $object = collect($object);
        if($filterKeys) return $object->only($filterKeys)->toArray();

        return  $object->only(['id', 'lang_id', 'parent_id', 'name', 'slug', 'active', 'description', 'data_lang', 'data'])->toArray();
    }

    private static function checkCreate($object)
    {
        if(empty($object['lang_id']))   throw new \Exception('You have to define a lang_id field to create a category');
        if(empty($object['name']))      throw new \Exception('You have to define a name field to create a category');
        if(empty($object['slug']))      throw new \Exception('You have to define a slug field to create a category');
    }

    private static function checkUpdate($object)
    {
        if(empty($object['ix'])) throw new \Exception('You have to define a ix field to update a category');
        if(empty($object['id'])) throw new \Exception('You have to define a id field to update a category');
    }
}