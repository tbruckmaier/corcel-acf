<?php

namespace Tbruckmaier\Corcelacf;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

/**
 * This class belongs to a certain Corcel model and bundles all acf relations
 */
class Acf
{
    /**
     * The related post instance
     *
     * @var Model
     */
    protected $post;

    public function __construct(Model $post)
    {
        $this->post = $post;
    }

    /**
     * When accessing an acf relation as a method (`$post->acf->field1()`), we
     * return an instance of the acf field (the relation itself is of no use to
     * anything outside)
     *
     *
     * @param string $method
     *
     * @return Models\BaseField
     */
    public function __call($method, $parameters)
    {
        $key = $method;

        // basically a copy of getRelationValue()
        if (!$this->post->relationLoaded($key)) {
            $this->post->setRelation($key, $this->hasAcf($key)->getResults());
        }

        return $this->post->getRelation($key);
    }

    /**
     * When accessing an acf relation as an attribute (`$post->acf->field1`), we
     * return the AcfField's value (e.g. the parsed response from
     * getValueAttribute())
     *
     * @author tbruckmaier
     *
     * @param string $key
     *
     * @return mixed
     */
    public function __get($key)
    {
        $relation = $this->$key();
        if (!$relation) {
            return null;
        }
        return $this->$key()->value;
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

        return $this->newHasOneAcf($instance->newQuery(), $this->post, $instance->getTable().'.'.$foreignKey, $underscore);
    }

    /**
     * Create an instance of the acf relation. Copied from
     * Illuminate\Database\Eloquent\Concerns\HasRelationships::newHasOne
     */
    protected function newHasOneAcf(Builder $query, Model $parent, $foreignKey, $localKey)
    {
        return new AcfRelation($query, $parent, $foreignKey, $localKey);
    }

    /**
     * Copied from Illuminate\Database\Eloquent\Concerns\HasRelationships
     *
     * @param  string  $class
     * @return mixed
     */
    protected function newRelatedInstance($class)
    {
        return tap(new $class, function ($instance) {
            if (! $instance->getConnectionName()) {
                $instance->setConnection($this->post->getConnectionName());
            }
        });
    }

}
