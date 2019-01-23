<?php

namespace Tbruckmaier\Corcelacf;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Greabock\Tentacles\EloquentTentacle;

/**
 * This trait can be used with any Corcel model. It contains helper methods to
 * create the acf relations, if the static property $acfRelations is defined,
 * the relations are instantiated
 */
trait AcfTrait
{
    // "Monkey-patching for eloquent models"
    use EloquentTentacle;

    protected static $acfRelations = [];

    /**
     * Called upon booting the model. Register all acf relations
     */
    public static function bootAcfTrait()
    {
        foreach (self::getAcfRelations() as $relationName) {
            $methodName = 'acf_' . $relationName;

            self::addExternalMethod($methodName, function () use ($relationName) {
                return $this->hasAcf($relationName);
            });
        }
    }

    /**
     * Add a set of acf relations
     *
     * @param array $acfRelations
     */
    public static function addAcfRelations(array $acfRelations)
    {
        self::$acfRelations = array_merge(self::getAcfRelations(), $acfRelations);
    }

    /**
     * Return the currently configured acf relations
     *
     * @author tbruckmaier
     *
     * @return array
     */
    public static function getAcfRelations()
    {
        return (property_exists(self::class, 'acfRelations') ? self::$acfRelations : []);
    }

    /**
     * Return an instance of the helper class
     */
    public function getAcfAttribute()
    {
        return new Acf($this);
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
