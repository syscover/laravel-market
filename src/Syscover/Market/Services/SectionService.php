<?php namespace Syscover\Market\Services;

use Syscover\Core\Services\Service;
use Syscover\Core\Exceptions\ModelNotChangeException;
use Syscover\Market\Models\Section;

class SectionService extends Service
{
    public function store(array $data)
    {
        $this->validate($data, [
            'id'        => 'required|between:2,30',
            'lang_id'   => 'required|size:2',
            'name'      => 'required|between:2,255',
            'slug'      => 'required|between:2,255',
        ]);

        $object['data_lang'] = Section::getDataLang($data['lang_id'], $data['id']);

        return Section::create($data);
    }


    public function update(array $data, int $ix)
    {
        $this->validate($data, [
            'ix'        => 'required|integer',
            'id'        => 'required|between:2,30',
            'lang_id'   => 'required|size:2',
            'name'      => 'required|between:2,255',
            'slug'      => 'required|between:2,255',
        ]);

        $object = Section::findOrFail($ix);
        $oldId  = $object->id; // retrieve the id for common update

        $object->fill($data);

        // check is model has changed
        if ($object->isClean()) throw new ModelNotChangeException('At least one value must change');

        // save changes
        $object->save();

        // save changes in all object, with the same id
        // this method is exclusive form elements multi language
        $commonData = $object->only('id');

        Section::where('id', $oldId)->update($commonData);

        return $object;
    }
}
