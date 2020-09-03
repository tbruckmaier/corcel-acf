<?php

namespace Tbruckmaier\Corcelacf;

use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Collection;

class AcfRelation extends HasOne
{
    /**
     * Whenever getting acf fields, make sure they belong to a not-deleted acf
     * group
     */
    public function addConstraints()
    {
        parent::addConstraints();
        $this->query->active();
    }

    public function getResults()
    {
        return $this->getCorrectAcfField($this->query->first());
    }

    protected function getRelationValue(array $dictionary, $key, $type)
    {
        $value = $dictionary[$key];
        return $this->getCorrectAcfField(reset($value));
    }

    /**
     * Set the models meta data to the acf field. If $model is given, use that
     * model's meta data, otherwise the original parent model. (this is needed
     * for eager loading of many models)
     */
    protected function getCorrectAcfField($acfField, $model = null)
    {
        if (!$acfField) {
            return null;
        }
        if (!$model) {
            $model = $this->parent;
        }

        $data = $model->meta->pluck('meta_value', 'meta_key');
        return (clone $acfField)->setData($data)->setLocalKey(substr($this->localKey, 1));
    }

    /**
     * This method is used for getting the acf field name from the parent
     * ("field_5b585b950134f"), we need to return access the meta data here
     */
    public function getParentKey()
    {
        return $this->parent->getMeta($this->localKey);
    }

    protected function getKeys(array $models, $key = null)
    {
        return collect($models)->map(function ($value) use ($key) {
            return $key ? $value->getMeta($key) : $value->getKey();
        })->values()->unique()->sort()->all();
    }

    /**
     * Overwrite parent method for eager loading
     */
    protected function matchOneOrMany(array $models, Collection $results, $relation, $type)
    {
        $dictionary = $this->buildDictionary($results);

        // Once we have the dictionary we can simply spin through the parent models to
        // link them up with their children using the keyed dictionary to make the
        // matching very convenient and easy work. Then we'll just return them.
        foreach ($models as $i => $model) {
            if (isset($dictionary[$key = $model->getMeta($this->localKey)])) {
                $model->setRelation(
                    // the original method uses getRelationValue() here, but
                    // uses the meta data of the first model always.
                    $relation,
                    $this->getCorrectAcfField(reset($dictionary[$key]), $model)
                );
            }
        }

        return $models;
    }
}
