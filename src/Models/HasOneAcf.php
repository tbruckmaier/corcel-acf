<?php

namespace Corcel\Acf\Models;

use Illuminate\Database\Eloquent\Relations\HasOne;
use Corcel\Acf\Models\PostAcfField;
use Corcel\Acf\Models\PostAcfFieldImage;

class HasOneAcf extends HasOne
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
        $data = $this->parent->meta->pluck('meta_value', 'meta_key');
        return $acfField->setData($data)->setLocalKey(substr($this->localKey, 1));
    }
}
