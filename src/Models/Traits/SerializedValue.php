<?php

namespace Tbruckmaier\Corcelacf\Models\Traits;

trait SerializedValue
{
    public function getInternalValueAttribute()
    {
        if (!$this->data->has($this->localKey)) {
            return $this->getDefaultValue([]);
        }

        $value = $this->data->get($this->localKey);
        if (!$value) {
            return $value;
        }
        return @unserialize($value);
    }
}
