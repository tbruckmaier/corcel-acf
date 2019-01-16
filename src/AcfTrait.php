<?php

namespace Corcel\Acf;

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

    /**
     * Create an instance of the acf relation. Copied from
     * Illuminate\Database\Eloquent\Concerns\HasRelationships::hasOne
     */
    public function hasAcf($localKey)
    {
        $className = Models\BaseField::class;

        $instance = $this->newRelatedInstance($className);

        $foreignKey = 'post_name';

        $underscore = '_' . $localKey;

        return $this->newHasOneAcf($instance->newQuery(), $this, $instance->getTable().'.'.$foreignKey, $underscore);
    }

    /**
     * Create an instance of the acf relation. Copied from
     * Illuminate\Database\Eloquent\Concerns\HasRelationships::newHasOne
     */
    protected function newHasOneAcf(Builder $query, Model $parent, $foreignKey, $localKey)
    {
        return new AcfRelation($query, $parent, $foreignKey, $localKey);
    }
}
