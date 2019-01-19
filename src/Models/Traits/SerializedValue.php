<?php

namespace Tbruckmaier\Corcelacf\Models\Traits;

trait SerializedValue
{
    public function getInternalValueAttribute()
    {
        $value = $this->data->get($this->localKey);
        if (!$value) {
            return $value;
        }
        return @unserialize($value);
    }
}
