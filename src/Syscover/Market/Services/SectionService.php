<?php namespace Syscover\Market\Services;

use Syscover\Market\Models\Section;

class SectionService
{
    public static function create($object)
    {
        SectionService::checkCreate($object);

        $object['data_lang'] = Section::addDataLang($object['lang_id'], $object['id']);

        return Section::create(SectionService::builder($object));
    }

    public static function update($object)
    {
        SectionService::checkUpdate($object);

        // get original id of section
        $section = Section::find($object['ix']);

        Section::where('id', $section->id)->update(SectionService::builder($object, ['id']));
        Section::where('ix', $object['ix'])->update(SectionService::builder($object, ['lang_id', 'name', 'slug', 'data_lang']));

        return Section::find($object['ix']);
    }

    private static function builder($object, $filterKeys = null)
    {
        $object = collect($object);
        if($filterKeys) return $object->only($filterKeys)->toArray();

        return $object->only(['id', 'lang_id', 'name', 'slug', 'data_lang'])->toArray();
    }

    private static function checkCreate($object)
    {
        if(empty($object['id']))        throw new \Exception('You have to define a id field to create a section');
        if(empty($object['lang_id']))   throw new \Exception('You have to define a lang_id field to create a section');
        if(empty($object['name']))      throw new \Exception('You have to define a name field to create a section');
        if(empty($object['slug']))      throw new \Exception('You have to define a slug field to create a section');
    }

    private static function checkUpdate($object)
    {
        if(empty($object['ix']))     throw new \Exception('You have to define a id field to update a section');
    }
}