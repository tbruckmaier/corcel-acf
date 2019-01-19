<?php

namespace Tbruckmaier\Corcelacf\Models;

/**
 * Fallback field for all unknown fields. Returns the plain value stored in the
 * post_meta table
 */
class Generic extends BaseField
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
}
