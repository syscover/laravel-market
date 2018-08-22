<?php namespace Syscover\Market\Services;

use Syscover\Market\Models\Section;

class SectionService
{
    public static function create($object)
    {
        SectionService::checkCreate($object);
        return Section::create(SectionService::builder($object));
    }

    public static function update($object)
    {
        SectionService::checkUpdate($object);
        Section::where('ix', $object['ix'])->update(SectionService::builder($object));

        return Section::find($object['ix']);
    }

    private static function builder($object)
    {
        $object = collect($object);
        return $object->only(['id', 'lang_id', 'name', 'slug'])->toArray();
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