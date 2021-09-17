<?php

namespace Tbruckmaier\Corcelacf\Models;

use Illuminate\Contracts\Support\Arrayable;

/**
 * Fallback field for all unknown fields. Returns the plain value stored in the
 * post_meta table
 */
class Generic extends BaseField implements Arrayable
{
    /**
     * @return string
     */
    public function getValueAttribute()
    {
        return $this->internal_value;
    }

    /**
     * When echoing a field, just return the value
     *
     * @return string
     */
    public function __toString()
    {
        return (string)$this->value;
    }

    /**
     * Called by toArray() or toJson() (even if it is just a sub field). Return
     * the calculcated value in that case
     *
     * @return mixed Array representation of the object.
     */
    public function toArray()
    {
        if ($this->value instanceof Arrayable) {
            return $this->value->toArray();
        }
        return $this->value;
    }
}
