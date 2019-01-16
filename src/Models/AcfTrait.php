<?php

namespace Corcel\Acf\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

/**
 * Trait to be used in the corcel models with acf fields. Provides helper
 * methods to define relationships for the single acf fields
 */
trait AcfTrait
{
    /**
     * Corcel makes it possible to access meta values as regular attributes
     * (e.g. $post->metavalue) by overwriting __get(). Inside the relations,
     * getAttribute() is used a lot (instead of __get()), so we need to have
     * this behaviour here as well
     */
    public function getAttribute($key)
    {
        $value = parent::getAttribute($key);

        // if there is not regular relation, attribute etc, try to find a meta
        // value
        if ($value === null and !empty($this->meta)) {
            return $this->getMeta($key);
        }

        return $value;
    }

    public function hasOneAcf($localKey)
    {
        $className = \Corcel\Acf\Models\AcfField::class;

        $instance = $this->newRelatedInstance($className);

        $foreignKey = 'post_name';

        $underscore = '_' . $localKey;

        return $this->newHasOneAcf($instance->newQuery(), $this, $instance->getTable().'.'.$foreignKey, $underscore);
    }

    protected function newHasOneAcf(Builder $query, Model $parent, $foreignKey, $localKey)
    {
        return new HasOneAcf($query, $parent, $foreignKey, $localKey);
    }
}
