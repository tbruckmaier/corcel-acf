<?php

namespace Tbruckmaier\Corcelacf\Support;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Collection;

/**
 * Base group for group, repeater & flexible content layouts bundling all common
 * methods
 */
abstract class BaseLayout implements Arrayable
{
    /**
     * The fields of this layout
     *
     * @var Collection|null
     */
    protected $data;


    /**
     * When accessing fields as method, return the AcfField instatance
     */
    public function __call($method, $parameters)
    {
        return $this->data->get($method);
    }

    /**
     * When accessing fields as an attribute, return the AcfField's value
     */
    public function __get($key)
    {
        $relation = $this->$key();
        if (!$relation) {
            return null;
        }

        return $relation->value;
    }

    public function __isset($key)
    {
        return $this->data->has($key);
    }

    /**
     * Make the underlying data accessible.
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Called from toArray(), even if toArray() was called on a parent field.
     * Forward it to the actual data
     *
     * @return mixed Array representation of the object.
     */
    public function toArray()
    {
        return $this->getData()->toArray();
    }
}
