<?php

namespace Tbruckmaier\Corcelacf\Support\Traits;

/**
 * Common methods for flexible content & repeater layout blocks
 */
trait LayoutBlock
{
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
}
