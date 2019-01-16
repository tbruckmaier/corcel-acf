<?php

namespace Tbruckmaier\Corcelacf\Models;

/**
 * Fallback field for all unknown fields. Returns the plain value stored in the
 * post_meta table
 */
class Generic extends BaseField
{
    /**
     * @return boolean
     */
    public function getValueAttribute()
    {
        return $this->internal_value;
    }
}
