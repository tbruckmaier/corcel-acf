<?php

namespace Tbruckmaier\Corcelacf;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Greabock\Tentacles\EloquentTentacle;
use Illuminate\Support\Arr;

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

    protected static function bootTraits()
    {
        parent::bootTraits();
        self::createAcfRelations();
    }

    /**
     * Called upon booting the model. Check all registered acf relation names
     * and create the actual relation method
     */
    public static function createAcfRelations()
    {
        $methods = [];

        foreach (self::getAcfRelations() as $relationData) {

            // the relation is either a plain string with the acf field name, in
            // that case we get the field configuration from the database (for
            // use with acf-json). Or the acf field name is the array key, and
            // the value is the config array
            if (is_array($relationData)) {
                $relationName = Arr::get($relationData, 'name');
                $config = $relationData;
            } else {
                $relationName = $relationData;
                $config = null;
            }

            // create a acf relation dynamically
            $methodName = 'acf_' . $relationName;
            self::addExternalMethod($methodName, function () use ($relationName, $config) {
                return $this->hasAcf($relationName, $config);
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
     * Create an instance of the acf relation. Originally adapted from
     * Illuminate\Database\Eloquent\Concerns\HasRelationships::hasOne
     */
    public function hasAcf($localKey, $config = null)
    {
        $instance = $this->newRelatedInstance(Models\BaseField::class);

        // this calls BaseField::newEloquentBuilder() and therefore returns an
        // instance of FieldBuilder
        $query = $instance->newQuery();

        // if we have a custom field config, set it (happens if acf fields are
        // defined in php). If the config is not set, the field itself will
        // retrieve it from the database later
        if ($config) {
            $query->setFieldConfig($config);
        }

        $foreignKey = 'post_name';

        $underscore = '_' . $localKey;

        return $this->newHasOneAcf($query, $this, $instance->getTable().'.'.$foreignKey, $underscore);
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
