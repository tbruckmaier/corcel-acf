<?php

namespace Tbruckmaier\Corcelacf;

use Illuminate\Database\Eloquent\Relations\HasOne;

class AcfRelation extends HasOne
{
    public function getResults()
    {
        return $this->getCorrectAcfField($this->query->first());
    }

    protected function getRelationValue(array $dictionary, $key, $type)
    {
        $value = $dictionary[$key];
        return $this->getCorrectAcfField(reset($value));
    }

    protected function getCorrectAcfField($acfField)
    {
        if (!$acfField) {
            return null;
        }
        $data = $this->parent->meta->pluck('meta_value', 'meta_key');
        return $acfField->setData($data)->setLocalKey(substr($this->localKey, 1));
    }

    /**
     * This method is used for getting the acf field name from the parent
     * ("field_5b585b950134f"), we need to return access the meta data here
     */
    public function getParentKey()
    {
        return $this->parent->getMeta($this->localKey);
    }
}
