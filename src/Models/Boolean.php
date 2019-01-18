<?php

namespace Tbruckmaier\Corcelacf\Models;

class Boolean extends Generic
{
    /**
     * @return bool
     */
    public function getValueAttribute()
    {
        return (bool)$this->internal_value;
    }
}
