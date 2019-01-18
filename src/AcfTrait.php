<?php

namespace Tbruckmaier\Corcelacf;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

/**
 * Trait to be used in the corcel models with acf fields. Provides helper
 * methods to define relationships for the single acf fields
 */
trait AcfTrait
{
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
