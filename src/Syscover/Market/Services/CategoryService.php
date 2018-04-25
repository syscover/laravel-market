<?php namespace Syscover\Market\Services;

use Syscover\Market\Models\Category;

class CategoryService
{
    public static function create($object)
    {
        if(empty($object['id'])) $object['id'] = next_id(Category::class);

        $object['data_lang'] = Category::addDataLang($object['lang_id'], $object['id']);

        return Category::create($object);
    }

    public static function update($object)
    {
        CategoryService::check($object);

        Category::where('id', $object['id'])->update(CategoryService::builder($object, ['parent_id', 'active']));
        Category::where('ix', $object['ix'])->update(CategoryService::builder($object, ['name', 'slug', 'description']));

        return Category::find($object['ix']);
    }

    private static function builder($object, $filterKeys = null)
    {
        $object = collect($object);
        if($filterKeys) $object = $object->only($filterKeys);

        $data = [];

        if($object->has('id'))          $data['id'] = $object->get('id');
        if($object->has('lang_id'))     $data['lang_id'] = $object->get('lang_id');
        if($object->has('parent_id'))   $data['parent_id'] = $object->get('parent_id');
        if($object->has('name'))        $data['name'] = $object->get('name');
        if($object->has('slug'))        $data['slug'] = $object->get('slug');
        if($object->has('active'))      $data['active'] = $object->get('active');
        if($object->has('description')) $data['description'] = $object->get('description');
        if($object->has('data_lang'))   $data['data_lang'] = $object->get('data_lang');

        return $data;
    }

    private static function check($object)
    {
        if(empty($object['lang_id']))   throw new \Exception('You have to define a lang_id field to create a category');
        if(empty($object['name']))      throw new \Exception('You have to define a name field to create a category');
        if(empty($object['slug']))      throw new \Exception('You have to define a slug field to create a category');
        if(! isset($object['active']))  throw new \Exception('You have to define a active field to create a category');
    }
}